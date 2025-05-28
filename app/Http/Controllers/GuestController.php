<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        
        return inertia('Home', [
            'events' => Event::with('user:id,name')->where('status', 'pending')->latest()->paginate(6),
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }
}
