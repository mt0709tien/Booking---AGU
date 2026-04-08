<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;

class Category extends Model
{
    protected $fillable = [
    'name',
    'price_morning',
    'price_afternoon',
    'price_evening'

];

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
}