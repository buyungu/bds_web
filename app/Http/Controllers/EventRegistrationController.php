<?php

namespace App\Http\Controllers;

use App\Helpers\FcmNotification;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    { 
        // Validate the request ensure a donor is not already registered
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = auth()->user();

        // Ensure the user is a donor
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'You must be a user to register for an event.');
        }

        // Check if the donor is already registered for this event
        $existingRegistration = EventRegistration::where('user_id', $request->user_id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'You are already enrolled to this event');
        }

        // Register the Donor for this event
        EventRegistration::create([
            'user_id' => $request->user_id,
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

        return back()->with('status', 'You have successfully registered for the event!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventRegistration $enroll)
    {
        // Get the related event before deleting the registration
        $event = $enroll->event;
        $user = Auth::user();

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
        return redirect()->back()->with('status', 'Unenrolled from an event successful!! ');
    }
}
