<?php

namespace App\Http\Controllers;

use App\Helpers\FcmNotification;
use App\Models\BloodRequest;
use App\Http\Requests\UpdateBloodRequestRequest;
use App\Models\DonorDonation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodRequestController extends Controller
{
    public function store(Request $request)
    { 
        $validated = $request->validate([
            'hospital_id' => 'required|exists:users,id',
            'blood_type' => 'required|string',
            'quantity' => 'required|integer',
            'urgency' => 'required|string',
        ]);

        $validated = array_merge($validated, [
            'recipient_id' => Auth::id(),
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
                ['blood_request_id' => $bloodRequest->id, 'type' => 'donation'],
                $request->user()->id // Pass the recipient's user ID here
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
                    'type' => 'donation'
                ]
            );
        }

        return redirect()->route('recipient.requests')->with('status', "Your Blood request has been added successful");
    }

    public function donate(Request $request, $bloodRequestId)
    {
        $donor = Auth::user();
        $bloodRequest = BloodRequest::findOrFail($bloodRequestId);


    
        // ✅ Ensure donor has not already donated for this request
        if ($bloodRequest->donors()->wherePivot('donor_id', $donor->id)->exists()) {
            return back()->with('error', 'You have already donated for this request.');
        }
    
        // ✅ Ensure blood request quantity is not already fulfilled
        if ($bloodRequest->quantity <= 0) {
            return back()->with('error', 'This request is already fulfilled.');
        }
    
        // ✅ Attach donor first (prevents accidental multiple donations)
        $bloodRequest->donors()->attach($donor->id);
    
        // ✅ Reduce requested quantity by 1 pint (473 mL)
        $bloodRequest->quantity -= 1;
    
        // ✅ Update status based on remaining quantity
        $bloodRequest->status = ($bloodRequest->quantity > 0) ? 'partially matched' : 'matched';
        
        $bloodRequest->save();

        // send FCM notification to the donor
        if ($donor->fcm_token) {
            FcmNotification::send(
                $donor->fcm_token,
                'Donation Confirmation',
                "You have pledged to donate for request: {$bloodRequest->id}",
                ['blood_request_id' => $bloodRequest->id, 'type' => 'donation'],
                $donor->id // Pass the donor's user ID here
            );
        }

        // Send FCM notification to the recipient and hospital
        if ($bloodRequest->recipient->fcm_token) {
            FcmNotification::send(
                $bloodRequest->recipient->fcm_token,
                'New Donation',
                "A donor has pledged to donate for your request: {$bloodRequest->id}",
                ['blood_request_id' => $bloodRequest->id, 'type' => 'donation'],
                $bloodRequest->recipient->id // Pass the recipient's user ID here
            );
        }
    
        return back()->with('status', 'Blood donated successfully.');
    }
    
    public function assignBloodToRecipient($bloodRequestId)
    {
        $bloodRequest = BloodRequest::findOrFail($bloodRequestId);
        $hospital = Auth::user();

        // Mark as fulfilled
        $bloodRequest->status = 'fulfilled';
        $bloodRequest->save();

        // Add donors to donor_donations table
        foreach ($bloodRequest->donors as $donor) {
            DonorDonation::create([
                'donor_id' => $donor->id,
                'recipient_id' => $bloodRequest->recipient_id,
                'hospital_id' => $hospital->id,
                'blood_type' => $bloodRequest->blood_type,
                'quantity' => 1, // 473ml per donor
            ]);
        }

        // Send FCM notification to the recipient
        if ($bloodRequest->recipient->fcm_token) {
            FcmNotification::send(
                $bloodRequest->recipient->fcm_token,
                'Blood Assigned',
                "Your blood request has been fulfilled and assigned to you.",
                ['blood_request_id' => $bloodRequest->id, 'type' => 'blood_assignment']
            );
        }

        return back()->with('status', 'Blood successfully assigned to recipient.');
    }

    public function viewRequest($id)
    {
        $request = BloodRequest::with([
            'recipient:id,name,email,blood_type',
            'hospital:id,name,email,location',
            'donors:id,name,email'
        ])->findOrFail($id);

        return inertia('Donor/RequestProgress', [
            'bloodRequest' => $request
        ]);
    }

}
