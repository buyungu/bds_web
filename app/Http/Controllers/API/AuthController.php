<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'avatar' => ['file', 'nullable', 'max:3000'],
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:3',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => ['required', 'regex:/^(\+?[0-9]{10,15}|0[0-9]{9})$/'],
            'location' => 'required|array',
        ]);

        if ($request->hasFile('avatar')) {
            $fields['avatar'] = Storage::disk('public')->put('avatars', $request->file('avatar'));
        }

        $user = User::create($fields);

        event(new Registered($user));

        $token = $user->createToken('api-token')->plainTextToken;


        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // API Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    // API Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    // Get Authenticated User
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
