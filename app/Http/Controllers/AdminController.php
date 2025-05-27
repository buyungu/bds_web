<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\District;
use App\Models\Event;
use App\Models\Region;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        
        return inertia('Admin/Dashboard', [
            'countUsers' => User::count(),
            'countEvents' => Event::count(),
            'countBlood' => BloodStock::sum('quantity'),
            'countRequests' => BloodRequest::count(),
            'users' => User::
                filter(request(['search', 'role']))
                ->paginate(10)
                ->withQueryString(),
            'status' => session('status'),
            'success'=> session('success'),
            'events' => Event::with('user:id,name')
                ->latest()
                ->paginate(6),
        ]);
    }

    public function manageUsers() {
        return inertia('Admin/ManageUser', [
            'regions' => Region::all(),
            'districts' => District::all(),
            'wards' => Ward::all(),
            'users' => User::
                filter(request(['search', 'role']))
                ->paginate(10)
                ->withQueryString(),
            'status' => session('status'),
            'success'=> session('success'),
            
        ]);
    }

    public function addUser() {

        // Fetch all regions, districts, and wards from the database
        $regions = Region::all();
        $districts = District::all();
        $wards = Ward::all();

        return inertia('Admin/Users/AddUser', [
            'users' => User::paginate(10),
            'regions' => $regions,
            'districts' => $districts,
            'wards' => $wards,
            'status' => session('status'),
            'success'=> session('success'),
        ]);
    }
   
    public function profile(Request $request) {
        return Inertia::render('Admin/Profile', [
            'user' => $request->user(),
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }
    public function events() {
        return Inertia::render('Admin/Events', [
            'events' => Event::with('user:id,name')
                ->filter(request(['search','user_id']))
                ->latest()
                ->paginate(6)
                ->withQueryString(),
            'status' => session('status'),
        ]);
    }
  
    public function reports() {
        return inertia('Admin/Reports');
    }

    public function inventory() {
        
        // Aggregate Blood Stock Data
        $bloodStock = BloodStock::select('blood_type', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('blood_type')
            ->filter(request(['search', 'region', 'district', 'ward', 'blood_type']))
            ->get();
        
        // Retrieve blood stock with hospital details including region, district, and ward
        $bloodData = BloodStock::with([
            'hospital:id,name,ward_id', // Load hospital with ward_id
            'hospital.ward:id,name,district_id', // Load ward with district_id
            'hospital.ward.district:id,name,region_id', // Load district with region_id
            'hospital.ward.district.region:id,name' // Load region
            ])
            ->filter(request(['search', 'region', 'district', 'ward', 'blood_type']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Fetch all regions with districts and wards (for filters or dropdowns)
        $regions = Region::all();
        $districts = District::with('region:id,name')->get();
        $wards = Ward::with('district:id,name')->get();

        // Return data to the frontend via Inertia
        return inertia('Admin/Inventory', [
            'bloodStock' => $bloodStock,
            'bloodData' => $bloodData,
            'regions' => $regions,
            'districts' => $districts,
            'wards' => $wards,
        ]);
    }
}

// , [
//     'usersCount' => User::count(),
//     'eventsCount' => Event::count(),
//     'pendingRequests' => BloodRequest::where('status', 'pending')->count(),
// ]
