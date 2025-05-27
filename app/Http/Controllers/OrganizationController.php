<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        return inertia('Organization/Dashboard');
    }

    public function events(Request $request)
    {
        $events = $request->user()->events()
            ->filter(request(['search']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return inertia('Organization/Events', [
            'events' => $events,
            'status' => session('status')
        ]);
    }

    public function profile(Request $request)
    {
        return inertia('Organization/Profile', [
            'user' => $request->user(),
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }
    public function reports()
    {
        return inertia('Organization/Reports');
    }
}
