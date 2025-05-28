<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return inertia('Login',[
            'status' => session('status'),
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($fields, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            // Redirect based on role using URL paths instead of named routes
            return match ($user->role) {
                'admin' => redirect()->intended('/admin/dashboard'),
                'user' => redirect()->intended('/donor/dashboard'),
                'hospital' => redirect()->intended('/hospital/dashboard'),
                'organization' => redirect()->intended('/organization/dashboard'),
                default => redirect('/'),
            };
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
