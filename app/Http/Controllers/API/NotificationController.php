<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource; // Import the resource
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Fetch notifications specific to the user, ordered by sent_at in descending order
        // You might also want to fetch multicast notifications that were sent to everyone
        $notifications = Notification::where('user_id', $user->id)
                                    ->orWhereNull('user_id') // Include multicast notifications (where user_id is null)
                                    ->orderBy('sent_at', 'desc')
                                    ->paginate(10); // Paginate for performance

        return NotificationResource::collection($notifications);
    }

    /**
     * Mark a notification as read (optional).
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure the authenticated user owns this notification or it's a general multicast one
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $notification->update(['status' => 'read']); // Assuming you have a 'read' status

        return response()->json(['message' => 'Notification marked as read.']);
    }
}