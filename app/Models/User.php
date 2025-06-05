<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
    *
    * @use HasApiTokens
     * @var list<string>
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'location' => 'array',
        ];
    }

    // Relationships

    public function inventory(): HasMany
    {
        return $this->hasMany(BloodInventory::class, 'hospital_id');
    }

    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'recipient_id');
    }

    public function hospitals(): HasMany
    {
        return $this->hasMany(BloodStock::class, 'hospital_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class, 'user_id');
    }

     

    public function donatedRequests()
    {
        return $this->belongsToMany(BloodRequest::class, 'donor_blood_request', 'donor_id', 'blood_request_id')
            ->withTimestamps(); 
    }


    
    public function scopeFilter($query, array $filters){
        if ($filters['search'] ?? false) {
            $query->where( function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }

        if ($filters['role'] ?? false) {
            $query->where('role' , request('role'));
        }
    }
}
