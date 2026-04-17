<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: DejaVu Sans;
        font-size: 14px;
        color: #333;
        line-height: 1.6;
    }

    .container { padding: 20px; }

    h2 {
        text-align: center;
        color: #d33;
        margin-bottom: 20px;
    }

    .info {
        margin-bottom: 20px;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: #fafafa;
    }

    .info p { margin: 5px 0; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table th {
        background: #f2f2f2;
        padding: 10px;
        text-align: center;
    }

    table td {
        padding: 10px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    .total {
        text-align: right;
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
        color: #d33;
    }
</style>
</head>

<body>

<div class="container">

<h2>HÓA ĐƠN THANH TOÁN</h2>

@php
    $booking = $invoice->booking;

    $room = $booking?->roomBookings?->first();
    $sport = $booking?->sportBookings?->first();

    $facility = $room?->facility ?? $sport?->facility ?? null;

    // NGÀY
    $date = $room?->booking_date ?? $sport?->booking_date ?? $booking?->booking_date ?? null;

    // GIỜ
    if ($room) {
        $time = match($room->session) {
            'morning' => 'Sáng (7h - 11h)',
            'afternoon' => 'Chiều (13h - 17h)',
            'evening' => 'Tối (17h - 21h)',
            default => '---'
        };
    } elseif ($sport) {
        $time = \Carbon\Carbon::parse($sport->start_time)->format('H:i')
            . ' - ' .
            \Carbon\Carbon::parse($sport->end_time)->format('H:i');
    } else {
        $time = '---';
    }
@endphp

{{-- INFO --}}
<div class="info">

    <p><strong>Mã hóa đơn:</strong> #{{ $invoice->id }}</p>

    <p><strong>Khách:</strong> {{ $invoice->user->ho_ten ?? 'Khách' }}</p>

    <p><strong>SĐT:</strong> {{ $invoice->phone ?? ($booking->phone ?? '') }}</p>

    <p><strong>Cơ sở:</strong> {{ $facility->name ?? 'Không xác định' }}</p>

    {{-- 🔥 ĐỔI THỜI GIAN -> NGÀY --}}
    <p>
        <strong>Ngày:</strong>
        {{ $date ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '---' }}
    </p>

</div>

{{-- TABLE --}}
<table>
    <thead>
        <tr>
            <th>Thời gian</th>
            <th>SL</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>

    <tbody>
        @foreach($invoice->details as $item)
        <tr>
            <td>{{ $time }}</td>
            <td>{{ $item->so_luong }}</td>
            <td>{{ number_format($item->don_gia) }}</td>
            <td>{{ number_format($item->so_luong * $item->don_gia) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="total">
    Tổng tiền: {{ number_format($invoice->tong_tien) }} VNĐ
</div>

</div>

</body>
</html>