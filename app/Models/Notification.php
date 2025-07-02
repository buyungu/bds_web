<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fcm_token',
        'title',
        'body',
        'data',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array', // Cast the 'data' column to an array when retrieved
        'sent_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}