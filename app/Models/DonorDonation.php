<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorDonation extends Model
{
    use HasFactory;
    
    protected $fillable = ['donor_id', 'recipient_id', 'hospital_id', 'blood_type', 'quantity'];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function hospital()
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }
}

