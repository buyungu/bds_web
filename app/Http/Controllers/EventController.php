<?php

namespace App\Http\Controllers;

use App\Helpers\FcmNotification;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Events', [
            'events' => Event::with('user:id,name')
                ->where('status', 'pending')
                ->filter(request(['search','user_id']))
                ->latest()
                ->paginate(6)
                ->withQueryString(),
            'status' => session('status'),
            'searchTerm' => $request->search,
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

       

        return Inertia::render('Events/Create', [
            'status' => session('status'),
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after:today',
            'email' => 'nullable|email',
           'location' => 'required|array',
            'image' => 'nullable|file|max:3072|mimes:jpeg,jpg,png,webp',
        ]);

        if ($request->hasFile('image')) {
            $fields['image'] = Storage::disk('public')->put('images/events', $request->image);
        }

        Auth::user()->events()->create($fields);

        // send notification to all users in the same region
        

        $region = $fields['location']['region'] ?? null;

        if ($region) {
            // Get all users in the same region with an FCM token
            $tokens = User::where('location->region', $region)
                ->whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (!empty($tokens)) {
                FcmNotification::sendToMany(
                    $tokens,
                    'New Event in Your Region',
                    "A new event \"{$fields['title']}\" has been created in your region.",
                    [
                        'type' => 'event',
                        'region' => $region,
                        'event_id' => $event->id ?? null,
                        // You can add more data as needed
                    ]
                );
            }
        }

        return back()->with('status', 'Listing created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): Response
    {
        // Retrieve all user registered for this event
        $user = EventRegistration::where('event_id', $event->id)
            ->with(['user:id,name,email,blood_type,location,avatar' ])
            ->paginate(10);

        return Inertia::render('Events/Show', [
            'event' => $event->load(['user:id,name,avatar,location',       
        ]),
            'enrolledUser' => $user,
            'canModify' => Auth::user() ? Auth::user()->can('modify', $event) : false,
            'error' => session('error'),
            'status' => session('status'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): Response
    {
        

        return Inertia::render('Events/Edit', [
            'event' => $event,
            'status' => session('status'),
            
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after:today',
            'email' => 'nullable|email',
           'location' => 'nullable|array',
            'image' => 'nullable|file|max:3072|mimes:jpeg,jpg,png,webp',
        ]);

        if ($request->hasFile('image')) {

            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $fields['image'] = Storage::disk('public')->put('images/events', $request->image);
        } else {
            $fields['image'] = $event->image;
        }

        $event->update($fields);

        // SEND NOTIFICATION TO ALL USERS IN THE SAME REGION
        $region = $fields['location']['region'] ?? null;
        if ($region) {
            // Get all users in the same region with an FCM token
            $tokens = User::where('location->region', $region)
                ->whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (!empty($tokens)) {
                FcmNotification::sendToMany(
                    $tokens,
                    'Event Updated',
                    "The event \"{$fields['title']}\" has been updated.",
                    [
                        'type' => 'event',
                        'region' => $region,
                        'event_id' => $event->id ?? null,
                        // You can add more data as needed
                    ]
                );
            }
        }
        

        return back()->with('status', 'Event Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
       if ($event->image) {
        Storage::disk('public')->delete($event->image);
       }
        $event->delete();

        return redirect()->route('events')->with('status', 'Event deleted successfully.');
    }
}
