<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorDonation;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ApiUserController extends Controller
{
    // Dashboard Summary
    public function events(Request $request)
    {
        $user = $request->user();
        $region = $user->location['region'];  

        $upcomingEvents = Event::where('status', 'pending')
            ->where('location->region', $region)
            ->with('user:id,name,email,phone')
            ->get();

        return response()->json([
            'events' => $upcomingEvents,
        ]);
    }


    // Donation History
    public function myDonations(Request $request)
    {
        $donations = DonorDonation::where('donor_id', $request->user()->id)
            ->with(['recipient:id,name,email,avatar', 'hospital:id,name,email,location'])
            ->latest()
            ->get();

        return response()->json($donations);
    }

    // Registered Events
    public function registeredEvents(Request $request)
    {
        $events = EventRegistration::with([
                'user:id,name',
                'event:id,title,event_date,image,location,created_by',
                'event.user:id,name'
            ])
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($events);
    }

    // Blood Requests in Region and Matching Blood Type
    public function requests(Request $request)
    {
        $user = $request->user();
        $region = $user->location['region'];
        $bloodType = $user->blood_type;

        $bloodRequests = BloodRequest::whereIn('status', ['pending', 'partially matched', 'matched'])
            ->where('blood_type', $bloodType)
            ->whereHas('hospital', function ($query) use ($region) {
                $query->where('location->region', $region);
            })
            ->with([
                'recipient:id,name,email,avatar,location,phone',
                'hospital:id,name,email,location',
                'donors:id,name,email,avatar,phone'
            ])
            ->get();

        return response()->json([
            'requests' => $bloodRequests
        ]);
    }


    // Register a user (donor) to an event
    public function enrollToEvent(Request $request, Event $event)
    {
        $user = $request->user();

        // Ensure user is a donor
        if ($user->role !== 'user') {
            return response()->json([
                'message' => 'Only donors can register for events.'
            ], 403);
        }

        // Check if already registered
        $alreadyRegistered = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'message' => 'You are already enrolled in this event.'
            ], 409);
        }

        // Register for the event
        $registration = EventRegistration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Successfully registered for the event.',
            'registration' => $registration
        ], 201);
    }

    // Unenroll from an event
    public function unenrollfromEvent(EventRegistration $enroll)
    {
        // Ensure only the owner can delete their registration
        if (auth()->id() !== $enroll->user_id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $enroll->delete();

        return response()->json([
            'message' => 'Successfully unenrolled from the event.'
        ]);
    }



    // Get hospitals in recipient's region
    public function getHospitals(Request $request)
    {
        $region = $request->user()->location['region'];

        $hospitals = User::where('role', 'hospital')
            ->whereJsonContains('location->region', $region)
            ->get(['id', 'name', 'email', 'location']);

        return response()->json([
            'hospitals' => $hospitals
        ]);
    }

    // View recipient's blood requests
    public function myRequests(Request $request)
    {
        $recipientId = $request->user()->id;

        $requests = BloodRequest::where('recipient_id', $recipientId)
            ->with([
                'recipient:id,name,email,avatar,location,phone',
                'hospital:id,name,email,location',
                'donors:id,name,email,avatar,phone'
            ])->get();

        return response()->json([
            'myRequests' => $requests
        ]);
    }

    // Dummy endpoint for "Find Donors" (implement as needed)
    public function findDonors(Request $request)
    {
        $region = $request->user()->location['region'] ?? null;

        $donors = User::where('role', 'user')
            ->where('location->region', $region)
            ->get(['id', 'name', 'email', 'blood_type', 'phone', 'location']);

        return response()->json([
            'donors' => $donors
        ]);
    }

    // Get authenticated user's profile
    public function viewProfile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    // Update authenticated user's profile
    public function update(Request $request)
    {
        $user = $request->user();

        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'location' => 'nullable|array',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($fields['password'])) {
            $fields['password'] = Hash::make($fields['password']);
        } else {
            unset($fields['password']);
        }

        $user->update($fields);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->fresh(),
        ]);
    }






}
