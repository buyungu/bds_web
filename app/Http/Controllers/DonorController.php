<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\DonorDonation;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{
    public function index()
    {
        $regionId = Auth::user()->region_id;
        $bloodType = Auth::user()->blood_type;
        $donorId = Auth::user()->id;

        $activeRequests = BloodRequest::whereIn('status', ['pending','partially matched'])
            ->where('blood_type', $bloodType)
            ->whereHas('hospital.ward.district.region', function ($query) use ($regionId){
                $query->where('id', $regionId);
            })->count();

        $totalDonations = DonorDonation::where('donor_id', $donorId)->count();

        $upcomingEvents = Event::where('status', 'pending')
            ->where('region_id', $regionId)
            ->count();

        return inertia('Donor/Dashboard', [
            'activeRequests' => $activeRequests,
            'totalDonations' => $totalDonations,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function donations()
    {
        $regionId = Auth::user()->region_id;
        $bloodType = Auth::user()->blood_type;
        $donorId = Auth::user()->id;
        $donations = DonorDonation::where('donor_id', $donorId)
            ->with('recipient:id,name,email,avatar')
            ->with([
                'hospital:id,name,email,ward_id',
                'hospital.ward:id,name,district_id',
                'hospital.ward.district:id,name,region_id',
                'hospital.ward.district.region:id,name'
            ])->latest()->paginate(6);

        return inertia('Donor/Donations', [
            'donations' => $donations,
        ]);
    }
    
    public function events(Request $request)
    {
        $userId = Auth::user()->id;
        $events = EventRegistration::with([
                    'user:id,name', 
                    'event:id,title,event_date,image,created_by',
                    'event.user:id,name'])
                    ->where('user_id', $userId)
                    ->latest()
                    ->paginate(6);

        return inertia('Donor/Events', [
            'events' => $events,
            'status' => session('status'),
        ]);
    }
    public function requests()
    {
        $regionId = Auth::user()->region_id;
        $bloodType = Auth::user()->blood_type;
        $bloodRequests = BloodRequest::whereIn('status', ['pending','partially matched', 'matched'])
            ->where('blood_type', $bloodType)
            ->whereHas('hospital.ward.district.region', function ($query) use ($regionId){
                $query->where('id', $regionId);
            })
            ->with('recipient:id,name,email,avatar')
            ->with([
                'hospital:id,name,email,ward_id',
                'hospital.ward:id,name,district_id',
                'hospital.ward.district:id,name,region_id',
                'hospital.ward.district.region:id,name'
            ])->paginate(6);
        return inertia('Donor/Requests', [
            'bloodRequests' => $bloodRequests,
            'status' => session('status'),
            'error' => session('error'),
        ]);
    }
    public function profile(Request $request)
    {
        return inertia('Donor/Profile', [
            'user' => $request->user(),
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

}

// , [
//     'upcomingEvents' => Event::whereDate('date', '>=', now())->get(),
//     'bloodRequests' => BloodRequest::where('status', 'pending')
//         ->where('blood_type', Auth::user()->blood_type)
//         ->get(),
// ]
