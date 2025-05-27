<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region_id',
    ];

    // A district belongs to a region
    public function region() {
        return $this->belongsTo(Region::class);
    }

    // A district hasMany Wards
    public function wards() {
        return $this->hasMany(Ward::class);
    }
}
