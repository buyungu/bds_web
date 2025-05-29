<?php

namespace App\Http\Controllers\Admin;

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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'avatar' => ['file', 'nullable', 'max:3000'],
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:3',
            'role' => 'required|in:donor,recipient,hospital,organization,admin',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => ['required', 'regex:/^(\+?[0-9]{10,15}|0[0-9]{9})$/'],
            'location' => 'nullable|array',
        ]);

        // Save avatar if it exists
        if ($request->hasFile('avatar')) {
            $fields['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);
        }

        $user = User::create($fields);

        event(new Registered($user));

        return back()->with('success', 'User created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        

        return Inertia::render('Admin/Users/UserEdit', [
            "status" => session('status'),
            "user" => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $fields = $request->validate([
            'avatar' => ['file', 'nullable', 'max:3000'],
            'name' => 'required',
            'email' => 'required|email|unique:users,email' . $user->id,
            'role' => 'required|in:donor,recipient,hospital,organization,admin',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => ['required', 'regex:/^(\+?[0-9]{10,15}|0[0-9]{9})$/'],
           'location' => 'nullable|array',
        ]);

        // Save avatar if it exists
        if ($request->hasFile('avatar')) {
            $fields['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);
        }

        $user->update($fields);

        return back()->with('success', 'User created successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }
}
