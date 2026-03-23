<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingNotification;
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
    ->first(),

'afternoon' => Booking::where('facility_id', $facility->id)
    ->whereDate('booking_date', $date)
    ->where('session', 'afternoon')
    ->first(),

'evening' => Booking::where('facility_id', $facility->id)
    ->whereDate('booking_date', $date)
    ->where('session', 'evening')
    ->first(),
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

        // 🔥 CHẶN SLOT
        $exists = Booking::where('facility_id', $request->facility_id)
            ->whereDate('booking_date', $request->booking_date)
            ->where('session', $request->session)
            ->whereIn('status', ['pending', 'approved', 'locked'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Khung giờ này đã được đặt hoặc bị khóa!');
        }

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

        // 🔥 TẠO BOOKING (CHO CẢ GUEST)
        $booking = Booking::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'facility_id' => $request->facility_id,
            'booking_date' => $request->booking_date,
            'session' => $request->session,
            'fullname' => auth()->check() 
                ? auth()->user()->ho_ten 
                : $request->fullname,
            'phone' => $request->phone,
            'price' => $price,
            'payment_method' => $request->payment_method,
            'status' => auth()->check() && auth()->user()->vai_tro == 'admin'
    ? 'locked'
    : 'pending'
        ]);

        // 🔔 GỬI THÔNG BÁO CHO ADMIN
        $admins = User::where('vai_tro', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new BookingNotification(
                'Đơn mới',
                'Có người vừa đặt sân!',
                route('admin.bookings')
            ));
        }

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

        if ($booking->user_id != auth()->id()) {
            abort(403);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Đã hủy lịch thành công!');
    }

    

public function formMultiple(Request $request)
{
    $items = $request->bookings;
    if (!$items) return back()->with('error', 'Chưa chọn ca!');

    // Lấy facility_id từ item đầu tiên để hiển thị tên sân nếu cần
    list($facility_id) = explode('|', is_array($items) ? $items[0] : $items);
    $facility = Facility::find($facility_id);

    return view('booking.form-multiple', [
        'items' => is_array($items) ? $items : [$items],
        'facility' => $facility, // Truyền thêm biến này
        'isMultiple' => true
    ]);
}

public function storeMultiple(Request $request)
{
    // Quan trọng: Kiểm tra xem bookings gửi lên là mảng hay chuỗi
    $items = $request->bookings;
    
    // Nếu bạn dùng implode ở View, thì ở đây phải explode lại
    if (is_string($items)) {
        $items = explode(',', $items);
    }

    if (!$items || count($items) == 0) {
        return back()->with('error', 'Dữ liệu đặt lịch bị trống!');
    }

    foreach ($items as $item) {
        // Tách dữ liệu: 1|2026-03-20|morning
        $parts = explode('|', $item);
        if (count($parts) < 3) continue;

        list($facility_id, $date, $session) = $parts;
        $facility = Facility::find($facility_id);

        if (!$facility) continue;

        // Kiểm tra trùng một lần nữa cho chắc chắn
        $exists = Booking::where('facility_id', $facility_id)
            ->whereDate('booking_date', $date)
            ->where('session', $session)
            ->whereIn('status', ['pending', 'approved', 'locked'])
            ->exists();

        if ($exists) continue;

        // Tính giá
        $priceField = "price_{$session}";
        $price = $facility->category->$priceField;

        // Tạo đơn hàng
        Booking::create([
            'user_id' => auth()->id(),
            'facility_id' => $facility_id,
            'booking_date' => $date,
            'session' => $session,
            'fullname' => $request->fullname, // Lấy từ form khách nhập
            'phone' => $request->phone,       // Lấy từ form khách nhập
            'price' => $price,
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ]);
    }

    // Thông báo Admin (giữ nguyên logic notify của bạn)
    // ...

    return redirect()->route('booking.home')->with('success', 'Đặt nhiều lịch thành công!');
}
public function lock(Request $request)
{
    $exists = Booking::where('facility_id', $request->facility_id)
        ->whereDate('booking_date', $request->date)
        ->where('session', $request->session)
        ->whereIn('status', ['pending', 'approved'])
        ->exists();

    if ($exists) {
        return back()->with('error', 'Ca này đã có người đặt hoặc đã khóa!');
    }

    Booking::create([
        'user_id' => auth()->id(), // 🔥 QUAN TRỌNG
        'facility_id' => $request->facility_id,
        'booking_date' => $request->date,
        'session' => $request->session,
        'fullname' => auth()->user()->ho_ten,
        'phone' => '---',
        'price' => 0,
        'payment_method' => 'admin_lock',
        'status' => 'locked' // 🔥 QUAN TRỌNG
    ]);

    return back()->with('success', 'Đã khóa sân!');
}
}