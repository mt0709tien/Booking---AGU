<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;

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

        return back()->with('success','Đã duyệt!');
    }

    // ================== TỪ CHỐI ==================
    public function reject($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'rejected';
        $booking->save();

        return back()->with('success','Đã từ chối!');
    }

    // ================== 🔥 KHÓA SÂN ==================
   public function lock(Request $request)
{
    // ✅ validate dữ liệu
    $request->validate([
        'facility_id' => 'required',
        'date' => 'required|date',
        'session' => 'required'
    ]);

    // 🔥 kiểm tra slot đã tồn tại chưa (kể cả locked)
    $exists = Booking::where('facility_id', $request->facility_id)
        ->whereDate('booking_date', $request->date)
        ->where('session', $request->session)
        ->whereIn('status', ['pending', 'approved', 'locked']) // 🔥 FIX QUAN TRỌNG
        ->exists();

    if ($exists) {
        return back()->with('error', 'Slot này đã có người đặt hoặc đã khóa!');
    }

    // 🔥 tạo bản ghi khóa
    Booking::create([
        'user_id' => null,
        'facility_id' => $request->facility_id,
        'booking_date' => $request->date,
        'session' => $request->session,
        'fullname' => 'ADMIN LOCK',
        'phone' => '0000000000',
        'price' => 0,
        'payment_method' => 'admin',
        'status' => 'locked'
    ]);

    return back()->with('success', 'Đã khóa sân!');
}
}