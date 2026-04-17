<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;

class Category extends Model
{
    protected $fillable = [
    'name',
    'type', // 🔥 BẮT BUỘC
    'price_morning',
    'price_afternoon',
    'price_evening',
    'price_hour' // 🔥 BẮT BUỘC
];

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
}