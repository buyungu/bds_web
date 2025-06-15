<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FcmNotification;
use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorDonation;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            ->with([
                'user:id,name,email,phone',
                'registrations' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ])
            ->get()
            ->map(function ($event) use ($user) {
                $registration = $event->registrations->first();

                $event->is_enrolled = $registration ? true : false;
                $event->enrollment_id = $registration ? $registration->id : null;

                unset($event->registrations);
                return $event;
            });

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
            ->get()
            ->map(function ($request) use ($user) {
                // Append the 'has_donated' attribute
                $request->has_donated = $request->donors->contains('id', $user->id);
                return $request;
            });

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

        // Send FCM notification to the user
        if ($user->fcm_token) {
            FcmNotification::send(
                $user->fcm_token,
                'Event Registration',
                "You have successfully enrolled in the event: {$event->title}",
                [
                    'route' => "/event-details/{$event->id}", // Flutter expects this for navigation
                    'event_id' => $event->id, 'type' => 'event']
            );
        }

        return response()->json([
            'message' => 'Successfully registered for the event.',
            'registration' => $registration
        ], 201);
    }

    // Unenroll from an event
    public function unenrollfromEvent(Request $request, EventRegistration $enroll)
    {
        $user = $request->user();

        // Ensure only the owner can delete their registration
        if (auth()->id() !== $enroll->user_id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get the related event before deleting the registration
        $event = $enroll->event;

        $enroll->delete();

        // Send FCM notification to the user
        if ($user->fcm_token && $event) {
            FcmNotification::send(
                $user->fcm_token,
                'Event Registration',
                "You have successfully unenrolled from the event: {$event->title}",
                [
                    'route' => "/event-details/{$event->id}", // Flutter expects this for navigation
                    'event_id' => $event->id, 'type' => 'event'
                ]
            );
        }

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
        $user = $request->user();
        $recipientId = $request->user()->id;

        $requests = BloodRequest::where('recipient_id', $recipientId)
            ->with([
                'recipient:id,name,email,avatar,location,phone',
                'hospital:id,name,email,location',
                'donors:id,name,email,avatar,phone'
            ])->latest()->get()
            ->map(function ($request) use ($user) {
                // Append the 'has_donated' attribute
                $request->has_donated = $request->donors->contains('id', $user->id);
                return $request;
            });

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
            'phone' => ['required', 'regex:/^(\+?[0-9]{10,15}|0[0-9]{9})$/'],
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($fields['password'])) {
            $fields['password'] = Hash::make($fields['password']);
        } else {
            unset($fields['password']);
        }

        $request->user()->fill($fields);
        $user->update($fields);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->fresh(),
        ]);
    }

    // Donate blood
    public function donate(Request $request, $bloodRequestId)
    {
        $donor = $request->user();
        $bloodRequest = BloodRequest::findOrFail($bloodRequestId);

        // Check if donor already donated
        if ($bloodRequest->donors()->wherePivot('donor_id', $donor->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already donated for this request.'
            ], 400);
        }

        // send FCM notification to the donor
        if ($donor->fcm_token) {
            FcmNotification::send(
                $donor->fcm_token,
                'Donation Confirmation',
                "You have pledged to donate for request: {$bloodRequest->id}",
                [
                    'blood_request_id' => $bloodRequest->id, 
                    'type' => 'donation'
                ]
            );
        }

        // Send FCM notification to the recipient and hospital
        if ($bloodRequest->recipient->fcm_token) {
            FcmNotification::send(
                $bloodRequest->recipient->fcm_token,
                'New Donation',
                "A donor has pledged to donate for your request: {$bloodRequest->id}",
                ['blood_request_id' => $bloodRequest->id, 'type' => 'donation']
            );
        }

        // Check if request is already fulfilled
        if ($bloodRequest->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This request is already fulfilled.'
            ], 400);
        }

        // Attach donor
        $bloodRequest->donors()->attach($donor->id);

        // Reduce quantity
        $bloodRequest->quantity -= 1;

        // Update status
        $bloodRequest->status = ($bloodRequest->quantity > 0) ? 'partially matched' : 'matched';
        $bloodRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Thank you for donating!',
            'data' => [
                'blood_request_id' => $bloodRequest->id,
                'remaining_quantity' => $bloodRequest->quantity,
                'status' => $bloodRequest->status
            ]
        ], 200);
    }


    public function createBloodRequest(Request $request) 
    {
        $validated = $request->validate([
            'hospital_id' => 'required|exists:users,id',
            'blood_type' => 'required|string',
            'quantity' => 'required|integer',
            'urgency' => 'required|string',
        ]);

        $validated = array_merge($validated, [
            'recipient_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        $bloodRequest = new BloodRequest($validated);
        $bloodRequest->save();

        // Send FCM notification to the recipient
        if ($request->user()->fcm_token) {
            FcmNotification::send(
                $request->user()->fcm_token,
                'Blood Request Created',
                "Your blood request has been created successfully.",
                [
                    'blood_request_id' => $bloodRequest->id, 'type' => 'donation'
                ]
            );
        }

        // Send FCM notification to donors in the same region whose specified blood type matches

        $region = $request->user()->location['region'] ?? null;
        $bloodType = $bloodRequest->blood_type;

        // Find donors in the same region and with the same blood type, who have an FCM token
        $donors = User::where('role', 'user')
            ->where('blood_type', $bloodType)
            ->where('location->region', $region)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (!empty($donors)) {
            FcmNotification::sendToMany(
                $donors,
                'New Blood Request',
                "A new blood request for {$bloodType} is available in your region.",
                [
                    'blood_request_id' => $bloodRequest->id,
                    'type' => 'donation',
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Your blood request has been submitted successfully.',
            'data' => $bloodRequest
        ], 201);

    }

    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'Token saved']);
    }

}
