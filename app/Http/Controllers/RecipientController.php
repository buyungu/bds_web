<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{

    public function requestBlood()
    {
        $region = Auth::user()->location['region'];
        $hospitals = User::where('role', 'hospital')
            ->whereJsonContains('location->region', $region)
            ->get();

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
                'hospital:id,name,email,avatar,location'          
            ])->latest()->paginate(6);
        return inertia('Recipient/Requests',[
            'bloodRequests' => $bloodRequests,
            'status' => session('status'),
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
