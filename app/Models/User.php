<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Booking;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'ho_ten',
        'email',
        'password',
        'vai_tro'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Quan hệ: 1 user có nhiều booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}