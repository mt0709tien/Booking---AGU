<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}