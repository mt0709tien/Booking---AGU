<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // 📋 Danh sách hóa đơn
    public function index()
    {
        $invoices = Invoice::with('user','booking')
            ->latest()
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    // 🧾 Tạo hóa đơn từ booking
    public function create($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('admin.invoices.create', compact('booking'));
    }

    // 💾 Lưu hóa đơn
    public function store(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        $invoice = Invoice::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'tong_tien' => $booking->price,
            'status' => 'pending'
        ]);

        InvoiceDetail::create([
            'invoice_id' => $invoice->id,
            'ten_dich_vu' => 'Đặt sân: ' . ($booking->facility->name ?? ''),
            'so_luong' => 1,
            'don_gia' => $booking->price
        ]);

        return redirect()->route('admin.invoices')
            ->with('success','Tạo hóa đơn thành công');
    }

    // 👁 Xem chi tiết
    public function show($id)
    {
        $invoice = Invoice::with('details','user','booking')->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }
    public function createByGroup($groupId)
{
    $bookings = Booking::where('group_id', $groupId)->get();

    if ($bookings->isEmpty()) {
        return back()->with('error','Không có booking');
    }

    // ❌ tránh tạo trùng
    $exists = Invoice::whereIn('booking_id', $bookings->pluck('id'))->exists();

    if ($exists) {
        return back()->with('error','Đã có hóa đơn cho đơn này');
    }

    $total = $bookings->sum('price');

    $invoice = Invoice::create([
        'user_id' => $bookings->first()->user_id,
        'tong_tien' => $total,
        'status' => 'pending'
    ]);

    // 🔥 tạo chi tiết cho từng ca
    foreach ($bookings as $b) {
        InvoiceDetail::create([
            'invoice_id' => $invoice->id,
            'ten_dich_vu' => 'Ca ' . $b->session,
            'so_luong' => 1,
            'don_gia' => $b->price
        ]);
    }

    return redirect()->route('admin.invoices')
        ->with('success','Xuất hóa đơn thành công');
}
public function markAsPaid($id)
{
    $invoice = Invoice::findOrFail($id);

    $invoice->status = 'paid';
    $invoice->save();

    return back()->with('success', 'Đã thanh toán hóa đơn!');
}


public function exportPDF($id)
{
    $invoice = Invoice::with('details','user','booking.facility')
        ->findOrFail($id);

    $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));

    return $pdf->download('hoa-don-'.$invoice->id.'.pdf');
}
}