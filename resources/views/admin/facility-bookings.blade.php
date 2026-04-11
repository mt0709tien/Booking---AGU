@extends('layouts.admin')

@section('admin_content')

<div class="container">

<h3 class="mb-4">
    Đơn đặt - {{ $facility->name }}
</h3>

<a href="{{ route('admin.facilities') }}" class="btn btn-secondary mb-3">
← Quay lại
</a>

<table class="table table-bordered table-striped">

<thead>
<tr>
<th>ID</th>
<th>Người dùng</th>
<th>Họ tên</th>
<th>SĐT</th>
<th>Ngày</th>
<th>Ca</th>
<th>Giá</th>
<th>Trạng thái</th>
<th>Thời gian đặt</th>
<th>Hành động</th>
<th>Thanh toán</th>
</tr>
</thead>

<tbody>

@foreach($bookings as $booking)

<tr>

<td>{{ $booking->id }}</td>

<td>
    @if($booking->user)
        {{ $booking->user->ho_ten }}

        @if(strtolower($booking->user->vai_tro) == 'admin')
            <span class="badge bg-danger">Admin</span>
        @else
            <span class="badge bg-success">User</span>
        @endif
    @else
        Khách
        <span class="badge bg-secondary">Guest</span>
    @endif
</td>

<td>{{ $booking->fullname }}</td>
<td>{{ $booking->phone }}</td>

<td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>

<td>
    @if($booking->session == 'morning')
        Sáng (7h - 11h)
    @elseif($booking->session == 'afternoon')
        Chiều (13h - 17h)
    @elseif($booking->session == 'evening')
        Tối (17h - 21h)
    @else
        Không xác định
    @endif
</td>

<td>{{ number_format($booking->price) }} VNĐ</td>

<!-- TRẠNG THÁI -->
<td>
    @if($booking->status == 'locked')
        <span class="badge bg-dark">Đã khóa</span>

    @elseif($booking->status == 'pending')
        <span class="badge bg-warning text-dark">Chờ duyệt</span>

    @elseif($booking->status == 'approved')
        <span class="badge bg-success">Đã duyệt</span>

    @elseif($booking->status == 'rejected')
        <span class="badge bg-danger">Từ chối</span>

    @elseif($booking->status == 'cancelled')
        <span class="badge bg-secondary">Đã hủy</span>

    @else
        <span class="badge bg-light text-dark">Không rõ</span>
    @endif
</td>

<!-- THỜI GIAN -->
<td>
    {{ $booking->created_at ? $booking->created_at->format('H:i d/m/Y') : '' }}
</td>

<!-- HÀNH ĐỘNG -->
<td>

@php
    $isAdmin = $booking->user && strtolower($booking->user->vai_tro) == 'admin';
@endphp

@if(!$booking->is_paid && $booking->payment_method == 'Chuyển khoản')
    <div class="mb-1">
        <span class="badge bg-danger">
            ⚠️ Chưa thanh toán
        </span>
    </div>
@endif

@if($booking->status == 'pending' && !$isAdmin)

    <a href="{{ route('admin.booking.approve',$booking->id) }}"
       class="btn btn-success btn-sm">
       ✔️ Duyệt
    </a>

    <a href="{{ route('admin.booking.reject',$booking->id) }}"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Bạn có chắc muốn từ chối?')">
       ❌ Từ chối
    </a>

@elseif($booking->status == 'locked')

    <form action="{{ route('admin.booking.unlock') }}" method="POST" style="display:inline;">
        @csrf
        <input type="hidden" name="facility_id" value="{{ $booking->facility_id }}">
        <input type="hidden" name="date" value="{{ $booking->booking_date }}">
        <input type="hidden" name="session" value="{{ $booking->session }}">

        <button class="btn btn-dark btn-sm"
            onclick="return confirm('Mở khóa ca này?')">
            🔓 Mở khóa
        </button>
    </form>

@elseif($booking->status == 'approved')

    @if($booking->group_id)

        @php
            $firstBooking = \App\Models\Booking::where('group_id', $booking->group_id)
                                ->orderBy('id')
                                ->first();
        @endphp

        @if($firstBooking && $firstBooking->id == $booking->id)
            <a href="{{ route('admin.invoice.group', $booking->group_id) }}"
               class="btn btn-primary btn-sm">
               🧾 Xuất hóa đơn
            </a>
        @else
            <span class="text-muted">Đã gộp</span>
        @endif

    @else
        <a href="{{ route('admin.invoice.create', $booking->id) }}"
           class="btn btn-outline-primary btn-sm">
           🧾 Xuất lẻ
        </a>
    @endif

@elseif($isAdmin)

    <span class="text-primary fw-bold">Admin tạo</span>

@else

    <span class="text-muted">Đã xử lý</span>

@endif

</td>

<!-- THANH TOÁN -->
<td>

<form action="{{ route('admin.booking.togglePayment') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $booking->id }}">

    @if($booking->payment_method == 'Tiền mặt')

        @if($booking->is_paid)
            <button class="btn btn-success btn-sm">
                💵 Đã thu
            </button>
        @else
            <button class="btn btn-warning btn-sm">
                💵 Chưa thu
            </button>
        @endif

    @elseif($booking->payment_method == 'Chuyển khoản')

        @if($booking->is_paid)
            <button class="btn btn-primary btn-sm">
                💳 Đã CK
            </button>
        @else
            <button class="btn btn-danger btn-sm">
                ⏳ Chờ CK
            </button>
        @endif

    @elseif($booking->payment_method == 'admin_lock')

        <span class="badge bg-dark">🔒 Admin khóa</span>

    @else
        <span class="text-muted">Không rõ</span>
    @endif

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection