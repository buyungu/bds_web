<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'location' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations():HasMany
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }



    // FILTERS

    public function scopeFilter($query, array $filters){
        if ($filters['search'] ?? false) {
            $query->where( function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')
                    ->orWhere('location->address', 'like', '%' . request('search') . '%')
                    ->orWhere('location->district', 'like', '%' . request('search') . '%')
                    ->orWhere('location->region', 'like', '%' . request('search') . '%');
            });
                
        }

        if ($filters['user_id'] ?? false) {
            $query->where('created_by', request('user_id'));
        }
    }
}
