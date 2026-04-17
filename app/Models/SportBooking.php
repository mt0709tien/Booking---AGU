<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'facility_id',
        'booking_date',
        'start_time',
        'end_time'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}