<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'blood_type',
        'quantity',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }

    public function scopeFilter($query, array $filters) {
        if (!empty($filters['search'])) {
            $query->whereHas('hospital', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        if (!empty($filters['region'])) {
            $query->whereHas('hospital.ward.district.region', function ($q) use ($filters) {
                $q->where('name', $filters['region']);
            });
        }

        if (!empty($filters['district'])) {
            $query->whereHas('hospital.ward.district', function ($q) use ($filters) {
                $q->where('name', $filters['district']);
            });
        }

        if (!empty($filters['ward'])) {
            $query->whereHas('hospital.ward', function ($q) use ($filters) {
                $q->where('name', $filters['ward']);
            });
        }
    }
}
