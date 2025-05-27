<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'district_id',
    ];

    // Relationship between Ward and District
    public function district() {
        return $this->belongsTo(District::class);
    }

    // A Ward has many Users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // A Ward has many Events
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
