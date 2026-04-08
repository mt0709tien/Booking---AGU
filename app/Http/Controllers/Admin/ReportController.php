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

    // 🔥 EXPORT PDF
    public function export(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('admin.report_pdf', $data);

        return $pdf->download('bao-cao-doanh-thu.pdf');
    }

    // 🔥 HÀM CHUNG
    private function getReportData($request)
    {
        $type = $request->type ?? 'day';

        $query = Booking::with('facility.category')
            ->where('status', 'approved')
            ->whereNotNull('paid_at');

        // 🔥 FILTER TIME
        if ($type == 'day') {

            $date = $request->date ?? Carbon::today()->toDateString();
            $carbonDate = Carbon::parse($date);

            $query->whereDate('paid_at', $carbonDate);

        } 
        elseif ($type == 'month') {

            $month = $request->month ?? Carbon::now()->format('Y-m');
            $carbonMonth = Carbon::parse($month);

            $query->whereMonth('paid_at', $carbonMonth->month)
                  ->whereYear('paid_at', $carbonMonth->year);

        } 
        else { // year

            $year = $request->year ?? Carbon::now()->year;

            $query->whereYear('paid_at', $year);
        }

        // 🔥 FILTER PAYMENT
        if ($request->payment) {
            $query->where('payment_method', $request->payment);
        }

        $baseQuery = clone $query;
        $bookings = $baseQuery->get();

        // 🔥 GROUP
        if ($type == 'year') {
            $grouped = $bookings->groupBy(function ($item) {
                return 'Tháng ' . Carbon::parse($item->paid_at)->format('m');
            });
        } else {
            $grouped = $bookings->groupBy(function ($item) {
                return $item->facility->category->name ?? 'Khác';
            });
        }

        // 🔥 TOTAL
        $totalAll = $bookings->sum('price');

        $totalCash = (clone $baseQuery)
            ->where('payment_method', 'Tiền mặt')
            ->sum('price');

        $totalBank = (clone $baseQuery)
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