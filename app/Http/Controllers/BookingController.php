<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create(Facility $facility)
    {
        $weekDays = [];

        for ($i = 0; $i < 7; $i++) {

            $date = Carbon::now()->startOfWeek()->addDays($i);

            $weekDays[] = [

                'date' => $date,

                'morning' => Booking::where('facility_id',$facility->id)
                    ->where('booking_date',$date)
                    ->where('session','morning')
                    ->exists(),

                'afternoon' => Booking::where('facility_id',$facility->id)
                    ->where('booking_date',$date)
                    ->where('session','afternoon')
                    ->exists(),

            ];
        }

        return view('booking.create', compact('facility','weekDays'));
    }

    public function store(Request $request)
    {
        Booking::create($request->all());

        return redirect()->back()->with('success','Đặt lịch thành công!');
    }
}