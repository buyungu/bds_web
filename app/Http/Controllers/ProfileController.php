<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

use function Laravel\Prompts\password;

class ProfileController extends Controller
{
    public function edit(Request $request) {
        return Inertia::render('Profile/Edit', [
            'user' => $request->user(),
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    public function updateInfo(Request $request) {
        $fields = $request->validate([
            'avatar' => ['file', 'nullable', 'max:3000'],
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'lowercase', 'email',
            Rule::unique(User::class)->ignore($request->user()->id)],
        ]);

        // Save avatar if it exists
        if ($request->hasFile('avatar')) {
            $fields['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);
        }
        
        $request->user()->fill($fields);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->back()->with('status', "Information Updated Successful");
    }

    public function updatePassword(Request $request) {
        $fields = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:3'],
        ]);

        $request->user()->update([
            'password' => Hash::make($fields['password'])
        ]);

        return back()->with('message', 'Password updated successful!!');
    }

    public function destroy(Request $request) {
        $request->validate([
            'password' => ['required', 'current_password']
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Account Deleted Successfully');
    }
}

