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
            
            'users' => User::
                filter(request(['search', 'role']))
                ->paginate(10)
                ->withQueryString(),
            'status' => session('status'),
            'success'=> session('success'),
            
        ]);
    }

    public function addUser() {

       

        return inertia('Admin/Users/AddUser', [
            'users' => User::paginate(10),
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
            'events' => Event::with('user:id,name,location')
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
            'hospital:id,name,location' // Load hospital with location
            ])
            ->filter(request(['search', 'region', 'district', 'ward', 'blood_type']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

       
        // Return data to the frontend via Inertia
        return inertia('Admin/Inventory', [
            'bloodStock' => $bloodStock,
            'bloodData' => $bloodData,
       
        ]);
    }
}
