<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingNotification;
use Carbon\Carbon;

class BookingController extends Controller
{

    // 🔥 Hàm check admin chuẩn (fix tận gốc)
    private function isAdmin()
    {
        return auth()->check() && strtolower(trim(auth()->user()->vai_tro)) === 'admin';
    }

    /*
    |--------------------------------------------------------------------------
    | Trang hiển thị lịch đặt
    |--------------------------------------------------------------------------
    */
    public function create(Facility $facility)
{
     Carbon::setLocale('vi');
    $weekDays = [];

    for ($i = 0; $i < 7; $i++) {

        $date = Carbon::today()->addDays($i);

        // 🔥 Hàm lấy slot chuẩn (ưu tiên locked > approved > pending)
        $getSlot = function($session) use ($facility, $date) {
    return Booking::where('facility_id', $facility->id)
        ->whereDate('booking_date', $date)
        ->where('session', $session)
        ->orderByRaw("
            CASE 
                WHEN status = 'locked' THEN 1
                WHEN status = 'approved' THEN 2
                WHEN status = 'pending' THEN 3
                ELSE 4
            END
        ")
        ->first();
};
        

        $weekDays[] = [
            'date' => $date,
            'morning' => $getSlot('morning'),
            'afternoon' => $getSlot('afternoon'),
            'evening' => $getSlot('evening'),
        ];
    }

    return view('booking.create', compact('facility', 'weekDays'));
}
    /*
    |--------------------------------------------------------------------------
    | Lưu đặt lịch
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $facility = Facility::find($request->facility_id);

        // 🔥 CHỈ CHẶN USER
        if(!$this->isAdmin()){
            $exists = Booking::where('facility_id', $request->facility_id)
                ->whereDate('booking_date', $request->booking_date)
                ->where('session', $request->session)
                ->whereIn('status', ['pending', 'approved', 'locked'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Khung giờ này đã được đặt hoặc bị khóa!');
            }
        }

        // 🔥 LẤY GIÁ
        switch ($request->session) {
            case 'morning':
                $price = $facility->category->price_morning;
                break;
            case 'afternoon':
                $price = $facility->category->price_afternoon;
                break;
            case 'evening':
                $price = $facility->category->price_evening;
                break;
            default:
                return back()->with('error', 'Ca không hợp lệ!');
        }

        // 🔥 TẠO BOOKING
        Booking::create([
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
            'status' => $this->isAdmin() ? 'locked' : 'pending'
        ]);

        // 🔔 Notify (chỉ khi user)
        if(!$this->isAdmin()){
            $admins = User::where('vai_tro', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new BookingNotification(
                    'Đơn mới',
                    'Có người vừa đặt sân!',
                    route('admin.bookings')
                ));
            }
        }

        return redirect()
            ->route('booking.create', $request->facility_id)
            ->with('success', 'Đặt lịch thành công!');
    }

    /*
    |--------------------------------------------------------------------------
    | Lịch của tôi
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
    | Hủy lịch
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

    /*
    |--------------------------------------------------------------------------
    | Form nhiều ca
    |--------------------------------------------------------------------------
    */
    /*
|--------------------------------------------------------------------------
| Form nhiều ca
|--------------------------------------------------------------------------
*/
public function formMultiple(Request $request)
{
    $items = $request->bookings;

    if (!$items) {
        return back()->with('error', 'Chưa chọn ca!');
    }

    // lấy facility từ item đầu
    $first = is_array($items) ? $items[0] : $items;
    list($facility_id) = explode('|', $first);

    $facility = Facility::find($facility_id);

    return view('booking.form-multiple', [
        'items' => is_array($items) ? $items : [$items],
        'facility' => $facility,
        'isMultiple' => true
    ]);
}

public function storeMultiple(Request $request)
{
    $items = $request->bookings;

    if (is_string($items)) {
        $items = explode(',', $items);
    }

    if (!$items || count($items) == 0) {
        return back()->with('error', 'Dữ liệu đặt lịch bị trống!');
    }

    // 🔥 TẠO GROUP ID CHUNG
    $groupId = Str::uuid();

    foreach ($items as $item) {

        $parts = explode('|', $item);
        if (count($parts) < 3) continue;

        list($facility_id, $date, $session) = $parts;
        $facility = Facility::find($facility_id);

        if (!$facility) continue;

        // 🔥 CHỈ CHẶN USER
        if(!$this->isAdmin()){
            $exists = Booking::where('facility_id', $facility_id)
                ->whereDate('booking_date', $date)
                ->where('session', $session)
                ->whereIn('status', ['pending', 'approved', 'locked'])
                ->exists();

            if ($exists) continue;
        }

        $priceField = "price_{$session}";
        $price = $facility->category->$priceField;

        Booking::create([
            'group_id' => $groupId, // 🔥 QUAN TRỌNG NHẤT
            'user_id' => auth()->check() ? auth()->id() : null,
            'facility_id' => $facility_id,
            'booking_date' => $date,
            'session' => $session,
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'price' => $price,
            'payment_method' => $request->payment_method,
            'status' => $this->isAdmin() ? 'locked' : 'pending'
        ]);
    }

    // 🔔 Notify
    if(!$this->isAdmin()){
        $admins = User::where('vai_tro', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new BookingNotification(
                'Đơn mới',
                'Có người vừa đặt nhiều lịch!',
                route('admin.bookings')
            ));
        }
    }

return redirect()->route('booking.my')
    ->with('success', 'Đặt thành công. Vui lòng đợi điện thoại xác nhận đơn của bạn');
}
    /*
    |--------------------------------------------------------------------------
    | Admin khóa sân
    |--------------------------------------------------------------------------
    */
    public function lock(Request $request)
    {
        if(!$this->isAdmin()){
            $exists = Booking::where('facility_id', $request->facility_id)
                ->whereDate('booking_date', $request->date)
                ->where('session', $request->session)
                ->whereIn('status', ['pending', 'approved', 'locked'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Ca này đã có người đặt hoặc đã khóa!');
            }
        }

        Booking::create([
            'user_id' => auth()->id(),
            'facility_id' => $request->facility_id,
            'booking_date' => $request->date,
            'session' => $request->session,
            'fullname' => auth()->user()->ho_ten,
            'phone' => '---',
            'price' => 0,
            'payment_method' => 'admin_lock',
            'status' => 'locked'
        ]);

        return back()->with('success', 'Đã khóa sân!');
    }


public function unlock(Request $request)
{
    Booking::where('facility_id', $request->facility_id)
        ->whereDate('booking_date', $request->date)
        ->where('session', $request->session)
        ->where('status', 'locked') // 🔥 chỉ xóa lock
        ->delete();

    return back()->with('success', 'Đã mở khóa!');
}
public function togglePayment(Request $request)
{
    $booking = Booking::findOrFail($request->id);

    $booking->is_paid = !$booking->is_paid;

    if ($booking->is_paid) {
        $booking->paid_at = now(); // 🔥 GHI THỜI GIAN THANH TOÁN
    } else {
        $booking->paid_at = null;
    }

    $booking->save();

    return back();
}
public function payment($id)
{
    $booking = Booking::findOrFail($id);

    return view('booking.payment', compact('booking'));
}
public function checkin(Request $request)
{
    $booking = Booking::findOrFail($request->id);

    $booking->is_checked_in = 1;
    $booking->checked_in_at = now();
    $booking->save();

    return back()->with('success', 'Đã xác nhận nhận sân!');
}
}