<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
use App\Notifications\BookingNotification;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function sports()
    {
        $sports = Facility::whereHas('category', function($q){
            $q->where('name','like','%sân%');
        })->get();

        return view('admin.sports', compact('sports'));
    }

    public function rooms()
    {
        $rooms = Facility::whereHas('category', function($q){
            $q->where('name','like','%phòng%');
        })->get();

        return view('admin.rooms', compact('rooms'));
    }

    public function hall()
    {
        $halls = Facility::whereHas('category', function($q){
            $q->where('name','like','%hội%');
        })->get();

        return view('admin.hall', compact('halls'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['facility','user'])->latest()->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function stats()
    {
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalFacilities = Facility::count();

        return view('admin.stats', compact(
            'totalUsers',
            'totalBookings',
            'totalFacilities'
        ));
    }

    // ================== DUYỆT ==================
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'approved';
        $booking->save();

        // 🔔 GỬI THÔNG BÁO CHO USER (NẾU CÓ)
        if ($booking->user) {
            $booking->user->notify(new BookingNotification(
                'Đã duyệt',
                'Đơn đặt của bạn đã được duyệt!',
                route('booking.my')
            ));
        }

        return back()->with('success','Đã duyệt!');
    }

    // ================== TỪ CHỐI ==================
    public function reject($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'rejected';
        $booking->save();

        // 🔔 GỬI THÔNG BÁO CHO USER (NẾU CÓ)
        if ($booking->user) {
            $booking->user->notify(new BookingNotification(
                'Từ chối',
                'Đơn đặt của bạn đã bị từ chối!',
                route('booking.my')
            ));
        }

        return back()->with('success','Đã từ chối!');
    }

    // ================== 🔥 KHÓA SÂN ==================
   public function lock(Request $request)
{
    // 🔥 XÓA TOÀN BỘ SLOT CŨ (QUAN TRỌNG NHẤT)
    Booking::where('facility_id', $request->facility_id)
        ->whereDate('booking_date', $request->date)
        ->where('session', $request->session)
        ->delete();

    // 🔥 TẠO MỚI = LOCKED
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

public function facilityBookings($id)
{
    $facility = Facility::findOrFail($id);

    $bookings = Booking::where('facility_id', $id)
        ->latest()
        ->get();

    return view('admin.facility-bookings', compact('facility','bookings'));
}

public function facilities(Request $request)
{
    $query = \App\Models\Facility::with('category');

    // tìm kiếm
    if ($request->keyword) {
        $query->where('name', 'like', '%' . $request->keyword . '%');
    }

    $facilities = $query->get();

    // 🔥 Nếu là AJAX thì trả JSON
    if ($request->ajax()) {
        return response()->json($facilities);
    }

    // bình thường trả view
    return view('admin.facilities', compact('facilities'));
}
}