<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Region;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create()
    {
        // Fetch all regions, districts, and wards from the database
        $regions = Region::all();
        $districts = District::all();
        $wards = Ward::all();

        // Pass the data to the Inertia view
        return Inertia::render('Register', [
            'regions' => $regions,
            'districts' => $districts,
            'wards' => $wards,
        ]);

    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'avatar' => ['file', 'nullable', 'max:3000'],
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:3',
            'role' => 'required|in:donor,recipient,hospital,organization,admin',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'region_id' => 'nullable|exists:regions,id', // Validate region id exists in the regions table
            'district_id' => 'nullable|exists:districts,id', // Validate district id exists in the districts table
            'ward_id' => 'nullable|exists:wards,id', // Validate ward id exists in the wards table

        ]);

         // Save avatar if it exists
         if ($request->hasFile('avatar')) {
            $fields['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);
        }


        $user = User::create($fields);

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Registration successful, please log in.');
    }
}
