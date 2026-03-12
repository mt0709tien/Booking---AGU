<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;

class Booking extends Model
{
    protected $fillable = [
        'facility_id',
        'fullname',
        'phone',
        'booking_date',
        'session',
        'payment_method'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}