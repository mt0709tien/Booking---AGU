<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Facility;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index()
    {
        // 1. Tổng quan
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalFacilities = Facility::count();

        // 2. 🔥 Doanh thu 7 ngày gần nhất
        $revenueData = [];
        $dayLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Label dạng: 17/03
            $dayLabels[] = $date->format('d/m');

            $dailySum = Booking::whereDate('booking_date', $date->toDateString())
                ->where('status', 'approved')
                ->sum('price');

            $revenueData[] = $dailySum;
        }

        return view('admin.stats', compact(
            'totalUsers',
            'totalBookings',
            'totalFacilities',
            'revenueData',
            'dayLabels'
        ));
    }
}