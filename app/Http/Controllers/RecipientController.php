<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    public function index()
    {
        return inertia('Recipient/Dashboard');
    }

    public function requestBlood()
    {
        $regionId = Auth::user()->region_id;
        $hospitals = User::where('role', 'hospital')->where('region_id', $regionId)->get();

        return inertia('Recipient/Request',[
            'hospitals' => $hospitals,
            'status' => session('status'),
        ]);
    }

    public function myRequests()
    {
        $recipientId = Auth::user()->id;
        $bloodRequests = BloodRequest::where('recipient_id', $recipientId)
            ->with([
                'hospital:id,name,email,avatar,ward_id',
                'hospital.ward:id,name,district_id',
                'hospital.ward.district:id,name,region_id',
                'hospital.ward.district.region:id,name'
            ])->latest()->paginate(6);
        return inertia('Recipient/Requests',[
            'bloodRequests' => $bloodRequests,
        ]);
    }
    public function findDonors()
    {
        return inertia('Recipient/Donors');
    }
    public function profile(Request $request)
    {
        return inertia('Recipient/Profile', [
            'user' => $request->user(),
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }
}
