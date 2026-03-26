<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Facility;
use Carbon\Carbon;

class StatsController extends Controller
{
   public function index(Request $request)
{
    $totalUsers = User::count();
    $totalBookings = Booking::count();
    $totalFacilities = Facility::count();

    $filter = $request->filter ?? '7days';

    $revenueData = [];
    $dayLabels = [];

    // 🔥 DATE RANGE (ưu tiên cao nhất)
    if ($request->from && $request->to) {

        $start = Carbon::parse($request->from);
        $end = Carbon::parse($request->to);

        while ($start <= $end) {
            $dayLabels[] = $start->format('d/m');

            $revenueData[] = Booking::whereDate('booking_date', $start)
                ->where('status', 'approved')
                ->sum('price');

            $start->addDay();
        }
    }

    // 🔥 HÔM NAY
    elseif ($filter == 'today') {
        $today = Carbon::today();

        $dayLabels[] = $today->format('d/m');

        $revenueData[] = Booking::whereDate('booking_date', $today)
            ->where('status', 'approved')
            ->sum('price');
    }

    // 🔥 THÁNG
    elseif ($filter == 'month') {
        $now = Carbon::now();

        for ($i = 1; $i <= $now->daysInMonth; $i++) {
            $date = Carbon::create($now->year, $now->month, $i);

            $dayLabels[] = $date->format('d');

            $revenueData[] = Booking::whereDate('booking_date', $date)
                ->where('status', 'approved')
                ->sum('price');
        }
    }

    // 🔥 NĂM
    elseif ($filter == 'year') {
        for ($i = 1; $i <= 12; $i++) {
            $dayLabels[] = 'Tháng ' . $i;

            $revenueData[] = Booking::whereMonth('booking_date', $i)
                ->whereYear('booking_date', now()->year)
                ->where('status', 'approved')
                ->sum('price');
        }
    }

    // 🔥 7 NGÀY
    else {
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $dayLabels[] = $date->format('d/m');

            $revenueData[] = Booking::whereDate('booking_date', $date)
                ->where('status', 'approved')
                ->sum('price');
        }
    }

    return view('admin.stats', compact(
        'totalUsers',
        'totalBookings',
        'totalFacilities',
        'revenueData',
        'dayLabels',
        'filter'
    ));
}
}