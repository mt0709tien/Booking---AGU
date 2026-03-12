<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{

    // Trang hiển thị lịch đặt
    public function create(Facility $facility)
    {
        $weekDays = [];

        for ($i = 0; $i < 7; $i++) {

            $date = Carbon::now()->startOfWeek()->addDays($i);

            $weekDays[] = [

                'date' => $date,

                // Ca sáng
                'morning' => Booking::where('facility_id',$facility->id)
                    ->where('booking_date',$date)
                    ->where('session','morning')
                    ->exists(),

                // Ca chiều
                'afternoon' => Booking::where('facility_id',$facility->id)
                    ->where('booking_date',$date)
                    ->where('session','afternoon')
                    ->exists(),

                // Ca tối
                'evening' => Booking::where('facility_id',$facility->id)
                    ->where('booking_date',$date)
                    ->where('session','evening')
                    ->exists(),

            ];
        }

        return view('booking.create', compact('facility','weekDays'));
    }


    // Trang form nhập thông tin đặt
    public function form(Request $request, Facility $facility)
    {

        $date = $request->date;
        $session = $request->session;

        // Lấy giá theo buổi
        if($session == 'morning'){
            $price = $facility->category->price_morning;
        }

        if($session == 'afternoon'){
            $price = $facility->category->price_afternoon;
        }

        if($session == 'evening'){
            $price = $facility->category->price_evening;
        }

        return view('booking.form',compact(
            'facility',
            'date',
            'session',
            'price'
        ));
    }


    // Lưu dữ liệu đặt lịch
    public function store(Request $request)
    {

       Booking::create([

'user_id' => auth()->id(),

'facility_id'=>$request->facility_id,

'booking_date'=>$request->booking_date,

'session'=>$request->session,

'fullname'=>$request->fullname,

'phone'=>$request->phone,

'price'=>$request->price,

'payment_method'=>$request->payment_method

]);

        return redirect()->route('booking.create',$request->facility_id)
            ->with('success','Đặt lịch thành công!');
    }

}