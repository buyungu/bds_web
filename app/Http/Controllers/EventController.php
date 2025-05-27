<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Region;
use App\Models\Ward;
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

        // Fetch all regions, districts, and wards from the database
        $regions = Region::all();
        $districts = District::all();
        $wards = Ward::all();

        return Inertia::render('Events/Create', [
            'status' => session('status'),
            'regions' => $regions,
            'districts' => $districts,
            'wards' => $wards,
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
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'image' => 'nullable|file|max:3072|mimes:jpeg,jpg,png,webp',
        ]);

        if ($request->hasFile('image')) {
            $fields['image'] = Storage::disk('public')->put('images/events', $request->image);
        }

        Auth::user()->events()->create($fields);

        return back()->with('status', 'Listing created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): Response
    {
        // Retrieve all donors registered for this event
        $donors = EventRegistration::where('event_id', $event->id)
            ->with(['user:id,name,email,blood_type,ward_id',
            'user.ward:id,name,district_id',
            'user.ward.district:id,name,region_id',
            'user.ward.district.region:id,name',
        ])
        ->paginate(10);

        return Inertia::render('Events/Show', [
            'event' => $event->load(['user:id,name,avatar',
            'ward:id,name,district_id',
            'ward.district:id,name,region_id',
            'ward.district.region:id,name',
        ]),
            'enrolledDonors' => $donors,
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
        // Fetch all regions, districts, and wards from the database
        $regions = Region::all();
        $districts = District::all();
        $wards = Ward::all();

        return Inertia::render('Events/Edit', [
            'event' => $event,
            'status' => session('status'),
            'regions' => $regions,
            'districts' => $districts,
            'wards' => $wards,
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
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
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
