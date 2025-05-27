<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodInventoryController extends Controller
{
    /**
     * Display a listing of the blood inventory for the hospital.
     */
    public function index()
    {
        $hospitalId = Auth::id();
        $inventory = BloodInventory::where('hospital_id', $hospitalId)->paginate(10);

        return inertia('Hospital/Inventory/Inventory', [
            'inventory' => $inventory,
        ]);
    }

    /**
     * Show the form for creating a new blood inventory entry.
     */
    public function create()
    {
        return inertia('Hospital/Inventory/AddInventory', [
            'status' => session('status'),
        ]);
    }

    /**
     * Store a newly created blood inventory entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|numeric|min:1',
            'source' => 'required|in:donation,purchase',
        ]);

        Auth::user()->inventory()->create($validated);


        return redirect()->route('hospital.inventory')->with('status', 'Blood stock added successfully.');
    }

    /**
     * Display the specified blood inventory record.
     */
    public function show(BloodInventory $bloodInventory)
    {
        return inertia('Hospital/Inventory/ViewInventory', [
            'inventory' => $bloodInventory
        ]);
    }

    /**
     * Show the form for editing the specified blood inventory record.
     */
    public function edit(BloodInventory $inventory)
    {
        return inertia('Hospital/Inventory/EditInventory', [
            'inventory' => $inventory
        ]);
    }

    /**
     * Update the specified blood inventory record.
     */
    public function update(Request $request, BloodInventory $inventory)
    {
        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|numeric|min:1',
            'source' => 'required|in:donation,event,organization,purchase',
        ]);

        $inventory->update($request->only(['blood_type', 'quantity', 'source']));

        return redirect()->route('hospital.inventory')->with('status', 'Blood stock updated successfully.');
    }

    /**
     * Remove the specified blood inventory record.
     */
    public function destroy(BloodInventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('hospital.inventory')->with('status', 'Blood stock removed.');
    }
}