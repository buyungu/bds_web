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
  

        // Pass the data to the Inertia view
        return Inertia::render('Register');

    }

    public function register(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        $fields = $request->validate([
            'avatar' => ['file', 'nullable', 'max:3000'],
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:3',
            'role' => 'required|in:user,hospital,organization,admin',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => ['required', 'regex:/^(\+?[0-9]{10,15}|0[0-9]{9})$/'],
            'location' => 'required|array',
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
