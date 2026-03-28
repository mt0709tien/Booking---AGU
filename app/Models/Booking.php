<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

protected $fillable = [

'user_id',
'facility_id',
'booking_date',
'session',
'fullname',
'phone',
'price',
'payment_method',
'status',
'group_id'

];

public function facility()
{
return $this->belongsTo(Facility::class);
}

public function user()
{
return $this->belongsTo(User::class);
}

}