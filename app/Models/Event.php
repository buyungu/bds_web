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

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'email',
        'image',
        'ward_id',
        'district_id',
        'region_id',
        'created_by'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations():HasMany
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    // Event belongs to a Ward
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    // Event has a District through Ward
    public function district()
    {
        return $this->ward->district;
    }

    // Event has a Region through District
    public function region()
    {
        return $this->district->region;
    }


    // FILTERS

    public function scopeFilter($query, array $filters){
        if ($filters['search'] ?? false) {
            $query->where( function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            });
                
        }

        if ($filters['user_id'] ?? false) {
            $query->where('created_by', request('user_id'));
        }
    }
}
