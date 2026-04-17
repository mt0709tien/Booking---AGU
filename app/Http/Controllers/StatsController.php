<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        // ===== THỐNG KÊ TỔNG =====
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalFacilities = Facility::count();

        // ===== DOANH THU =====
        $totalRevenue = Booking::where('is_paid', true)
            ->where('status', 'approved')
            ->sum('price');

        // ===== TOP CƠ SỞ (FIX GROUP BY) =====
        $topFacilities = DB::table(DB::raw("
            (
                SELECT rb.facility_id, SUM(b.price) as total_revenue
                FROM bookings b
                JOIN room_bookings rb ON b.id = rb.booking_id
                WHERE b.is_paid = 1 AND b.status = 'approved'
                GROUP BY rb.facility_id

                UNION ALL

                SELECT sb.facility_id, SUM(b.price) as total_revenue
                FROM bookings b
                JOIN sport_bookings sb ON b.id = sb.booking_id
                WHERE b.is_paid = 1 AND b.status = 'approved'
                GROUP BY sb.facility_id
            ) as combined
        "))
        ->join('facilities', 'combined.facility_id', '=', 'facilities.id')
->join('categories', 'facilities.category_id', '=', 'categories.id')
->select(
    'facilities.id',
    'facilities.name',
    'categories.type',
    DB::raw('SUM(total_revenue) as total_revenue')
)
->groupBy('facilities.id', 'facilities.name', 'categories.type')
        ->groupBy('facilities.id', 'facilities.name', 'facilities.category_id')
        ->orderByDesc('total_revenue')
        ->limit(5)
        ->get();

        // ===== FILTER =====
        $filter = $request->filter ?? '7days';

        $revenueData = [];
        $dayLabels = [];

        // ===== FUNCTION LẤY DOANH THU THEO NGÀY =====
        $getRevenueByDate = function ($date) {
            return DB::table('bookings as b')
                ->leftJoin('room_bookings as rb', 'b.id', '=', 'rb.booking_id')
                ->leftJoin('sport_bookings as sb', 'b.id', '=', 'sb.booking_id')
                ->where(function ($q) use ($date) {
                    $q->whereDate('rb.booking_date', $date)
                      ->orWhereDate('sb.booking_date', $date);
                })
                ->where('b.is_paid', 1)
                ->where('b.status', 'approved')
                ->sum('b.price');
        };

        // ===== RANGE =====
        if ($request->from && $request->to) {

            $start = Carbon::parse($request->from);
            $end = Carbon::parse($request->to);

            while ($start <= $end) {

                $dayLabels[] = $start->format('d/m');
                $revenueData[] = $getRevenueByDate($start);

                $start->addDay();
            }
        }

        // ===== HÔM NAY =====
        elseif ($filter == 'today') {

            $today = Carbon::today();

            $dayLabels[] = $today->format('d/m');
            $revenueData[] = $getRevenueByDate($today);
        }

        // ===== THÁNG =====
        elseif ($filter == 'month') {

            $now = Carbon::now();

            for ($i = 1; $i <= $now->daysInMonth; $i++) {

                $date = Carbon::create($now->year, $now->month, $i);

                $dayLabels[] = $date->format('d');
                $revenueData[] = $getRevenueByDate($date);
            }
        }

        // ===== NĂM =====
        elseif ($filter == 'year') {

            for ($i = 1; $i <= 12; $i++) {

                $dayLabels[] = 'Tháng ' . $i;

                $revenueData[] = DB::table('bookings as b')
                    ->leftJoin('room_bookings as rb', 'b.id', '=', 'rb.booking_id')
                    ->leftJoin('sport_bookings as sb', 'b.id', '=', 'sb.booking_id')
                    ->where(function ($q) use ($i) {
                        $q->whereMonth('rb.booking_date', $i)
                          ->orWhereMonth('sb.booking_date', $i);
                    })
                    ->whereYear('b.created_at', now()->year)
                    ->where('b.is_paid', 1)
                    ->where('b.status', 'approved')
                    ->sum('b.price');
            }
        }

        // ===== 7 NGÀY =====
        else {

            for ($i = 6; $i >= 0; $i--) {

                $date = Carbon::now()->subDays($i);

                $dayLabels[] = $date->format('d/m');
                $revenueData[] = $getRevenueByDate($date);
            }
        }

        return view('admin.stats', compact(
            'totalUsers',
            'totalBookings',
            'totalFacilities',
            'totalRevenue',
            'topFacilities',
            'revenueData',
            'dayLabels',
            'filter'
        ));
    }
}