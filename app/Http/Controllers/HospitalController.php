<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitalId = Auth::id(); // Get logged-in hospital ID

        // ✅ Total Blood Stock (Only for this hospital)
        $totalBloodStock = BloodInventory::where('hospital_id', $hospitalId)
                            ->sum('quantity');

        // ✅ Pending Blood Requests (Requests that need attention)
        $pendingBloodRequests = BloodRequest::where('hospital_id', $hospitalId)
                            ->whereIn('status', ['pending','partially matched', 'matched'])
                            ->count();

        // ✅ Upcoming Blood Donation Events (Future events related to the hospital)
        $upcomingEvents = Event::where('created_by', $hospitalId)
                            ->where('event_date', '>=', now())
                            ->count();

        // ✅ Registered Donors Count (Donors associated with this hospital)
        $registeredDonors = User::where('role', 'user')
                            ->where('location->district', Auth::user()->location["district"])
                            ->count();

        return inertia('Hospital/Dashboard',[
            'totalBloodStock' => $totalBloodStock,
            'pendingBloodRequests' => $pendingBloodRequests,
            'upcomingEvents' => $upcomingEvents,
            'registeredDonors' => $registeredDonors,
        ]);
    }

    public function manageRequests()
    {
        $hospitalId = Auth::user()->hospital_id ?? Auth::id();

         // Aggregate Blood Request Data
         $bloodRequestQuantity = BloodRequest::select('blood_type', DB::raw('SUM(quantity) as total_quantity'))
         ->where('hospital_id', $hospitalId)->groupBy('blood_type')
         ->get();

        $bloodRequests = BloodRequest::where('hospital_id', $hospitalId)
            ->with(['recipient:id,name,email,blood_type,avatar',
                    'hospital:id,name,location',
            ])->latest()->paginate(10);

        return inertia('Hospital/Requests', [
            'bloodRequests' => $bloodRequests,
            'bloodRequestQuantity' => $bloodRequestQuantity,
        ]);
    }
    public function donorDatabase()
    {
        // ✅ Registered Donors Count (Donors associated with this hospital)
        $registeredDonors = User::where('role', 'user')
                            ->where('location->district', Auth::user()->location["district"])
                            ->paginate(6);

        return inertia('Hospital/Donors',[
            'registeredDonors' => $registeredDonors,
        ]);
    }
    public function inventory()
    {
        $hospitalId = Auth::id(); // Get logged-in hospital ID


        // Aggregate blood type Data
        $bloodType = BloodInventory::select('blood_type', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('blood_type')
            ->where('hospital_id', $hospitalId)
            ->get();

        // Fetch all blood stock for the hospital
        $bloodStock = BloodInventory::where('hospital_id', $hospitalId)->paginate(10);

        return inertia('Hospital/Inventory/Inventory',[
            'bloodStock' => $bloodStock,
            'bloodType' => $bloodType,
            'status' => session('status'),
        ]);
    }

    public function events(Request $request)
    {
        $events = $request->user()->events()
            ->filter(request(['search']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return inertia('Hospital/Events', [
            'events' => $events,
            'status' => session('status')
        ]);
    }
    public function reports()
    {
        return inertia('Hospital/Reports');
    }
    public function profile(Request $request)
    {
        return inertia('Hospital/Profile', [
            'user' => $request->user(),
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }
}
