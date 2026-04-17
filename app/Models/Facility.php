<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\RoomBooking;
use App\Models\SportBooking;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🔥 PHÒNG
    public function roomBookings()
    {
        return $this->hasMany(RoomBooking::class);
    }

    // 🔥 SÂN
    public function sportBookings()
    {
        return $this->hasMany(SportBooking::class);
    }
}