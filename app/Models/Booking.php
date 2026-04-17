<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;




class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'price',
        'payment_method',
        'status',
        'group_id'
    ];

    public function roomBookings()
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function sportBookings()
    {
        return $this->hasMany(SportBooking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function facility()
{
    return $this->belongsTo(Facility::class, 'facility_id');
}
}