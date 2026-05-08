<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\SportBooking;
use App\Notifications\BookingNotification;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // ================== FILTER FACILITY ==================

    public function sports()
    {
        $sports = Facility::whereHas('category', function ($q) {
            $q->where('type', 'sport');
        })->get();

        return view('admin.sports', compact('sports'));
    }

    public function rooms()
    {
        $rooms = Facility::whereHas('category', function ($q) {
            $q->where('type', 'room');
        })->get();

        return view('admin.rooms', compact('rooms'));
    }

    public function hall()
    {
        $halls = Facility::whereHas('category', function ($q) {
            $q->where('name', 'like', '%Hội%');
        })->get();

        return view('admin.hall', compact('halls'));
    }

    // BOOKINGS 

    public function bookings(Request $request)
{
    $query = Booking::with([
        'user',
        'roomBookings.facility.category',
        'sportBookings.facility.category'
    ]);

    //  Loc theo ten co so
    if ($request->filled('facility')) {
        $facility = $request->facility;

        $query->where(function ($q) use ($facility) {
            $q->whereHas('roomBookings.facility', function ($sub) use ($facility) {
                $sub->where('name', 'like', "%$facility%");
            })
            ->orWhereHas('sportBookings.facility', function ($sub) use ($facility) {
                $sub->where('name', 'like', "%$facility%");
            });
        });
    }

    // loc theo ngay
    if ($request->filled('date')) {
        $date = $request->date;

        $query->where(function ($q) use ($date) {
            $q->whereHas('roomBookings', function ($sub) use ($date) {
                $sub->whereDate('booking_date', $date);
            })
            ->orWhereHas('sportBookings', function ($sub) use ($date) {
                $sub->whereDate('booking_date', $date);
            });
        });
    }

    $bookings = $query->latest()->get();

    return view('admin.bookings', compact('bookings'));
}

    // STATS 

    public function stats(Request $request)
{
    $totalUsers = User::count();
    $totalBookings = Booking::count();
    $totalFacilities = Facility::count();

    $from = now()->startOfMonth();
    $to = now()->endOfDay();

    switch ($request->filter) {
        case 'today':
            $from = now()->startOfDay();
            break;

        case '7days':
            $from = now()->subDays(6)->startOfDay();
            break;

        case 'month':
            $from = now()->startOfMonth();
            break;

        case 'year':
            $from = now()->startOfYear();
            break;
    }

    if ($request->filled('from') && $request->filled('to')) {
        $from = Carbon\Carbon::parse($request->from)->startOfDay();
        $to = Carbon\Carbon::parse($request->to)->endOfDay();
    }

    // tong doanh thu theo thoi gian thanh toan
    $totalRevenue = Booking::where('is_paid', 1)
        ->whereNotNull('paid_at')
        ->whereBetween('paid_at', [$from, $to])
        ->sum('price');

   
    // Bieu do doanh thu theo thoi gian thanh toan
    $chart = Booking::selectRaw('DATE(paid_at) as day, SUM(price) as total')
        ->where('is_paid', 1)
        ->whereNotNull('paid_at')
        ->whereBetween('paid_at', [$from, $to])
        ->groupBy('day')
        ->orderBy('day')
        ->get();

    $dayLabels = $chart->pluck('day')->map(function ($d) {
        return Carbon\Carbon::parse($d)->format('d/m');
    });

    $revenueData = $chart->pluck('total');

    // Top cơ sở doanh thu
    $topFacilities = Facility::withSum([
        'bookings as total_revenue' => function ($q) use ($from, $to) {
            $q->where('is_paid', 1)
              ->whereNotNull('paid_at')
              ->whereBetween('paid_at', [$from, $to]);
        }
    ], 'price')
    ->orderByDesc('total_revenue')
    ->take(5)
    ->get();

    return view('admin.stats', compact(
        'totalUsers',
        'totalBookings',
        'totalFacilities',
        'totalRevenue',
        'dayLabels',
        'revenueData',
        'topFacilities'
    ));
}

    //  DUYỆT 

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'approved';
        $booking->save();

        if ($booking->user) {
            $booking->user->notify(new BookingNotification(
                'Đã duyệt',
                'Đơn đặt của bạn đã được duyệt!',
                route('booking.my')
            ));
        }

        return back()->with('success', 'Đã duyệt!');
    }

    //  TỪ CHỐI

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'rejected';
        $booking->save();

        if ($booking->user) {
            $booking->user->notify(new BookingNotification(
                'Từ chối',
                'Đơn đặt của bạn đã bị từ chối!',
                route('booking.my')
            ));
        }

        return back()->with('success', 'Đã từ chối!');
    }

    // KHÓA 

    public function lock(Request $request)
    {
        // XÓA SLOT CŨ
        RoomBooking::where('facility_id', $request->facility_id)
            ->whereDate('booking_date', $request->date)
            ->where('session', $request->session)
            ->delete();

        // TẠO BOOKING CHA
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'fullname' => auth()->user()->ho_ten,
            'phone' => '---',
            'price' => 0,
            'payment_method' => 'admin_lock',
            'status' => 'locked'
        ]);

        // TẠO ROOM BOOKING

        RoomBooking::create([
            'booking_id' => $booking->id,
            'facility_id' => $request->facility_id,
            'booking_date' => $request->date,
            'session' => $request->session,
        ]);

        return back()->with('success', 'Đã khóa!');
    }

    //  MỞ KHÓA

    public function unlock(Request $request)
    {
        RoomBooking::where('facility_id', $request->facility_id)
            ->whereDate('booking_date', $request->date)
            ->where('session', $request->session)
            ->delete();

        SportBooking::where('facility_id', $request->facility_id)
            ->whereDate('booking_date', $request->date)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->delete();

        return back()->with('success', 'Đã mở khóa!');
    }
    public function lockSport(Request $request)
{
    $facility_id = $request->facility_id;
    $date = $request->date;
    $start = $request->start_time;
    $end = $request->end_time;

    // XÓA SLOT TRÙNG 
    SportBooking::where('facility_id', $facility_id)
        ->whereDate('booking_date', $date)
        ->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_time', [$start, $end])
              ->orWhereBetween('end_time', [$start, $end])
              ->orWhere(function ($q2) use ($start, $end) {
                  $q2->where('start_time', '<=', $start)
                     ->where('end_time', '>=', $end);
              });
        })
        ->delete();

    // TẠO BOOKING CHA (ADMIN LOCK)
    $booking = Booking::create([
        'user_id' => auth()->id(),
        'fullname' => auth()->user()->ho_ten,
        'phone' => '---',
        'price' => 0,
        'payment_method' => 'admin_lock',
        'status' => 'locked'
    ]);

    // TẠO SPORT BOOKING
    SportBooking::create([
        'booking_id' => $booking->id,
        'facility_id' => $facility_id,
        'booking_date' => $date,
        'start_time' => $start,
        'end_time' => $end,
        'status' => 'locked'
    ]);

    return back()->with('success', 'Đã khóa sân theo giờ!');
}

    //  THEO CƠ SỞ 

    public function facilityBookings($id)
    {
        $facility = Facility::findOrFail($id);

        $bookings = Booking::whereHas('roomBookings', function ($q) use ($id) {
                $q->where('facility_id', $id);
            })
            ->orWhereHas('sportBookings', function ($q) use ($id) {
                $q->where('facility_id', $id);
            })
            ->with([
                'roomBookings.facility',
                'sportBookings.facility'
            ])
            ->latest()
            ->get();

        return view('admin.facility-bookings', compact('facility', 'bookings'));
    }

    //  FACILITY LIST 
    public function facilities(Request $request)
    {
        $query = Facility::with('category');

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $facilities = $query->get();

        if ($request->ajax()) {
            return response()->json($facilities);
        }

        return view('admin.facilities', compact('facilities'));
    }

    //  EXPORT PDF 

    public function export(Request $request)
    {
        $bookings = Booking::with([
            'roomBookings.facility',
            'sportBookings.facility'
        ])->get();

        // group theo cơ sở
        $grouped = $bookings->groupBy(function ($item) {

            $room = $item->roomBookings->first();
            $sport = $item->sportBookings->first();

            return $room?->facility->name
                ?? $sport?->facility->name
                ?? 'Không rõ';
        });

        $totalAll = $bookings->sum('price');

        $pdf = Pdf::loadView('admin.report_pdf', [
            'grouped' => $grouped,
            'totalAll' => $totalAll,
            'type' => $request->type,
            'date' => $request->date,
            'month' => $request->month,
            'year' => $request->year,
        ]);

        return $pdf->download('bao_cao.pdf');
    }
}