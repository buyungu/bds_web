<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Http\Requests\UpdateBloodRequestRequest;
use App\Models\DonorDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodRequestController extends Controller
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

        return back()->with('status', "Your Blood request has been added successful");
    }
    public function donate(Request $request, $bloodRequestId)
    {
        $donor = Auth::user();
        $bloodRequest = BloodRequest::findOrFail($bloodRequestId);

        // dd([
        //     'auth_user_id' => Auth::id(),
        //     'auth_user' => Auth::user(),
        //     'donor_id_in_attach' => $donor->id,
        //     'blood_request_id' => $bloodRequest->id,
        // ]);
        
    
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

        return back()->with('status', 'Blood successfully assigned to recipient.');
    }


    /**
     * Display the specified resource.
     */
    public function show(BloodRequest $bloodRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodRequest $bloodRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodRequest $bloodRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodRequest $bloodRequest)
    {
        //
    }

    public function viewRequest($id)
    {
        $request = BloodRequest::with([
            'recipient:id,name,email,blood_type',
            'hospital:id,name,email,ward_id',
            'hospital.ward:id,name,district_id',
            'hospital.ward.district:id,name,region_id',
            'hospital.ward.district.region:id,name',
            'donors:id,name,email'
        ])->findOrFail($id);

        return inertia('Donor/RequestProgress', [
            'bloodRequest' => $request
        ]);
    }

}
