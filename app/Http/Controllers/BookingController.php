<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Trang hiển thị lịch đặt
    |--------------------------------------------------------------------------
    */
    public function create(Facility $facility)
    {
        $weekDays = [];

        for ($i = 0; $i < 7; $i++) {

            $date = Carbon::today()->addDays($i);

            $weekDays[] = [

                'date' => $date,

                'morning' => Booking::where('facility_id', $facility->id)
                    ->whereDate('booking_date', $date)
                    ->where('session', 'morning')
                    ->exists(),

                'afternoon' => Booking::where('facility_id', $facility->id)
                    ->whereDate('booking_date', $date)
                    ->where('session', 'afternoon')
                    ->exists(),

                'evening' => Booking::where('facility_id', $facility->id)
                    ->whereDate('booking_date', $date)
                    ->where('session', 'evening')
                    ->exists(),
            ];
        }

        return view('booking.create', compact('facility', 'weekDays'));
    }


    /*
    |--------------------------------------------------------------------------
    | Form đặt lịch
    |--------------------------------------------------------------------------
    */
    public function form(Request $request, Facility $facility)
    {
        $date = $request->date;
        $session = $request->session;

        // Lấy giá theo buổi
        if ($session == 'morning') {
            $price = $facility->category->price_morning;
        }

        if ($session == 'afternoon') {
            $price = $facility->category->price_afternoon;
        }

        if ($session == 'evening') {
            $price = $facility->category->price_evening;
        }

        return view('booking.form', compact(
            'facility',
            'date',
            'session',
            'price'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Lưu đặt lịch
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $facility = Facility::find($request->facility_id);

        // Lấy giá
        if ($request->session == 'morning') {
            $price = $facility->category->price_morning;
        }

        if ($request->session == 'afternoon') {
            $price = $facility->category->price_afternoon;
        }

        if ($request->session == 'evening') {
            $price = $facility->category->price_evening;
        }

        Booking::create([

            'user_id' => auth()->id(),
            'facility_id' => $request->facility_id,
            'booking_date' => $request->booking_date,
            'session' => $request->session,
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'price' => $price,
            'payment_method' => $request->payment_method

        ]);

        return redirect()
            ->route('booking.create', $request->facility_id)
            ->with('success', 'Đặt lịch thành công!');
    }


    /*
    |--------------------------------------------------------------------------
    | 🔥 LỊCH CỦA TÔI
    |--------------------------------------------------------------------------
    */
    public function myBookings()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('booking.my', compact('bookings'));
    }


    /*
    |--------------------------------------------------------------------------
    | ❌ HỦY LỊCH
    |--------------------------------------------------------------------------
    */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Chỉ cho phép chủ đơn hủy
        if ($booking->user_id != auth()->id()) {
            abort(403);
        }

        $booking->delete();

        return back()->with('success', 'Đã hủy lịch thành công!');
    }

}