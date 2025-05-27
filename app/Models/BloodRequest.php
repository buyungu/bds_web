<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequest extends Model
{
    /** @use HasFactory<\Database\Factories\BloodRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'hospital_id',
        'blood_type',
        'quantity',
        'urgency',
        'status'
    ];

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }

    public function donors()
    {
        return $this->belongsToMany(User::class, 'donor_blood_request', 'blood_request_id', 'donor_id')
        ->withTimestamps();
    }
}
