<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('admin.report', $data);
    }

    public function export(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('admin.report_pdf', $data);

        return $pdf->download('bao-cao-doanh-thu.pdf');
    }

    private function getReportData($request)
    {
        $type = $request->type ?? 'day';

        $query = Booking::with([
            'roomBookings.facility.category',
            'sportBookings.facility.category'
        ])
        ->where('status', 'approved')
        ->whereNotNull('paid_at');

        if ($type == 'day') {

            $date = $request->date ?? Carbon::today()->toDateString();
            $query->whereDate('paid_at', $date);

        } elseif ($type == 'month') {

            $month = $request->month ?? Carbon::now()->format('Y-m');
            $carbon = Carbon::parse($month);

            $query->whereMonth('paid_at', $carbon->month)
                  ->whereYear('paid_at', $carbon->year);

        } else {

            $year = $request->year ?? Carbon::now()->year;
            $query->whereYear('paid_at', $year);
        }

        if ($request->payment) {
            $query->where('payment_method', $request->payment);
        }

        $bookings = $query->get();

        $bookings = $bookings->map(function ($b) {

            $room = $b->roomBookings->first();
            $sport = $b->sportBookings->first();

            $facility = $room?->facility ?? $sport?->facility;

            $b->facility_name = $facility->name ?? 'Không có';
            $b->category_type = $facility->category->type ?? 'unknown';

            $b->booking_date = $room?->booking_date ?? $sport?->booking_date;

            $b->start_time = $sport?->start_time;
            $b->end_time = $sport?->end_time;

            $b->session = $room?->session;

            return $b;
        });

        if ($type == 'year') {
            $grouped = $bookings->groupBy(function ($item) {
                return 'Tháng ' . Carbon::parse($item->paid_at)->format('m');
            });
        } else {
            $grouped = $bookings->groupBy('facility_name');
        }
        
        $totalAll = $bookings->sum('price');

        $totalCash = $bookings
            ->where('payment_method', 'Tiền mặt')
            ->sum('price');

        $totalBank = $bookings
            ->where('payment_method', 'Chuyển khoản')
            ->sum('price');

        return [
            'grouped' => $grouped,
            'type' => $type,
            'date' => $request->date ?? null,
            'month' => $request->month ?? null,
            'year' => $request->year ?? null,
            'totalAll' => $totalAll,
            'totalCash' => $totalCash,
            'totalBank' => $totalBank
        ];
    }
}