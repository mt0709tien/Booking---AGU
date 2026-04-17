@extends('layouts.admin')

@section('admin_content')

<div class="container">

<h3 class="mb-4">Chi tiết hóa đơn</h3>

@php
    $booking = $invoice->booking;

    // lấy facility an toàn (room hoặc sport)
    $room = $booking?->roomBookings?->first();
    $sport = $booking?->sportBookings?->first();

    $facility = $room?->facility ?? $sport?->facility ?? null;

    $date = $room?->booking_date ?? $sport?->booking_date ?? null;
@endphp

<div class="card shadow">
    <div class="card-body">

        <p><strong>Mã hóa đơn:</strong> #{{ $invoice->id }}</p>

        <p><strong>Khách hàng:</strong> {{ $invoice->user->ho_ten ?? 'Khách' }}</p>

        <p><strong>SĐT:</strong> {{ $booking->phone ?? '' }}</p>

        <p><strong>Cơ sở:</strong> {{ $facility->name ?? 'Không có' }}</p>

        <p><strong>Ngày đặt:</strong>
            {{ $date ? \Carbon\Carbon::parse($date)->format('d/m/Y') : 'N/A' }}
        </p>

        <hr>

        <h5>Danh sách dịch vụ</h5>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>

            <tbody>
                @foreach($invoice->details as $item)
                <tr>
                    <td>
    @php
        $booking = $invoice->booking;
        $room = $booking?->roomBookings?->first();
        $sport = $booking?->sportBookings?->first();
    @endphp

    @if($sport)
        {{ \Carbon\Carbon::parse($sport->start_time)->format('H:i') }}
        -
        {{ \Carbon\Carbon::parse($sport->end_time)->format('H:i') }}

    @elseif($room)
        @if($room->session == 'morning')
            Sáng (7h - 11h)
        @elseif($room->session == 'afternoon')
            Chiều (13h - 17h)
        @elseif($room->session == 'evening')
            Tối (17h - 21h)
        @else
            Không xác định
        @endif
    @else
        Không có dữ liệu
    @endif
</td>
                    <td>{{ $item->so_luong }}</td>
                    <td>{{ number_format($item->don_gia) }} VNĐ</td>
                    <td>{{ number_format($item->so_luong * $item->don_gia) }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="text-end text-danger">
            Tổng tiền: {{ number_format($invoice->tong_tien) }} VNĐ
        </h4>

        <div class="mt-3 text-end">

            @if($invoice->status != 'paid')
                <form action="{{ route('admin.invoice.paid', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">
                        ✔️ Xác nhận đã thanh toán
                    </button>
                </form>
            @else
                <span class="badge bg-success">Đã thanh toán</span>
            @endif

            <a href="{{ route('admin.invoice.pdf', $invoice->id) }}"
               class="btn btn-dark">
               📄 Xuất PDF
            </a>

            <a href="{{ route('admin.invoices') }}"
               class="btn btn-secondary">
               ← Quay lại
            </a>

        </div>

    </div>
</div>

</div>

@endsection