@extends('layouts.admin')

@section('admin_content')

<div class="container">

<h3 class="mb-4">Danh sách đặt lịch</h3>

<form method="GET" action="" class="mb-3">

    <div class="row g-2 align-items-end">

        <!-- Lọc theo tên cơ sở -->
        <div class="col-md-4">
            <label class="form-label">Cơ sở</label>
            <input type="text"
                   name="facility"
                   class="form-control"
                   value="{{ request('facility') }}"
                   placeholder="Nhập tên cơ sở...">
        </div>

        <!-- Lọc theo ngày -->
        <div class="col-md-3">
            <label class="form-label">Ngày đặt</label>
            <input type="date"
                   name="date"
                   class="form-control"
                   value="{{ request('date') }}">
        </div>

        <!-- Nút lọc -->
        <div class="col-md-3">
            <button class="btn btn-primary w-100">
                🔍 Lọc
            </button>
        </div>

        <!-- Reset -->
        <div class="col-md-2">
            <a href="{{ url()->current() }}" class="btn btn-secondary w-100">
                ♻️ Reset
            </a>
        </div>

    </div>

</form>

<table class="table table-bordered table-striped">

<thead>
<tr>
<th>ID</th>
<th>Người dùng</th>
<th>Họ tên</th>
<th>SĐT</th>
<th>Cơ sở</th>
<th>Đặt ngày</th>
<th>Thời gian</th>
<th>Giá</th>
<th>Trạng thái</th>
<th>Thời gian đặt</th>
<th>Hành động</th>
<th>Thanh toán</th> 
<th>Nhận sân</th>
</tr>
</thead>

<tbody>

@foreach($bookings as $booking)

@php
    $firstRoom  = $booking->roomBookings->first();
    $firstSport = $booking->sportBookings->first();

    $facility = $firstRoom?->facility ?? $firstSport?->facility;
    $date     = $firstRoom?->booking_date ?? $firstSport?->booking_date;
@endphp

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

<td>
    {{ $facility->name ?? 'Không có' }}

    {{-- Badge loại --}}
    @if(optional(optional($facility)->category)->type == 'sport')
        <span class="badge bg-info">Sân</span>
    @else
        <span class="badge bg-primary">Phòng</span>
    @endif
</td>

<td>
    @foreach($booking->roomBookings as $room)
        <div>{{ \Carbon\Carbon::parse($room->booking_date)->format('d/m/Y') }}</div>
    @endforeach

    @foreach($booking->sportBookings as $sport)
        <div>{{ \Carbon\Carbon::parse($sport->booking_date)->format('d/m/Y') }}</div>
    @endforeach
</td>

<!-- 🔥 THỜI GIAN -->
<td>
    {{-- Sân --}}
    @foreach($booking->sportBookings as $sport)
        <div>
            {{ \Carbon\Carbon::parse($sport->start_time)->format('H:i') }}
            -
            {{ \Carbon\Carbon::parse($sport->end_time)->format('H:i') }}
        </div>
    @endforeach

    {{-- Phòng --}}
    @foreach($booking->roomBookings as $room)
        <div>
            @if($room->session == 'morning')
                Sáng (7h - 11h)
            @elseif($room->session == 'afternoon')
                Chiều (13h - 17h)
            @elseif($room->session == 'evening')
                Tối (17h - 21h)
            @endif
        </div>
    @endforeach
</td>

<td>{{ number_format($booking->price) }} VNĐ</td>

<!-- 🔥 TRẠNG THÁI -->
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

<td>
    {{ $booking->created_at ? $booking->created_at->format('H:i d/m/Y') : '' }}
</td>

<td>

@php
    $isAdmin = $booking->user && strtolower($booking->user->vai_tro) == 'admin';
@endphp

@if(!$booking->is_paid && $booking->payment_method == 'Chuyển khoản')
    <div class="mb-1">
        <span class="badge bg-danger">⚠️ Chưa thanh toán</span>
    </div>
@endif

@if($booking->status == 'pending' && !$isAdmin)

    <a href="{{ route('admin.booking.approve',$booking->id) }}"
       class="btn btn-success btn-sm">✔️ Duyệt</a>

    <a href="{{ route('admin.booking.reject',$booking->id) }}"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Bạn có chắc muốn từ chối?')">❌ Từ chối</a>

@elseif($booking->status == 'locked')

    <form action="{{ route('admin.booking.unlock') }}" method="POST" style="display:inline;">
        @csrf

        {{-- 🔥 SỬA INPUT --}}
        <input type="hidden" name="session" value="{{ $firstRoom->session ?? '' }}">
<input type="hidden" name="start_time" value="{{ $firstSport->start_time ?? '' }}">
<input type="hidden" name="end_time" value="{{ $firstSport->end_time ?? '' }}">
         <input type="hidden" name="start_time" value="{{ $sport->start_time ?? '' }}">
<input type="hidden" name="end_time" value="{{ $sport->end_time ?? '' }}">

        <button class="btn btn-dark btn-sm"
            onclick="return confirm('Mở khóa?')">
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
               class="btn btn-primary btn-sm">🧾 Xuất hóa đơn</a>
        @else
            <span class="text-muted">Đã gộp</span>
        @endif

    @else
        <a href="{{ route('admin.invoice.create', $booking->id) }}"
           class="btn btn-outline-primary btn-sm">🧾 Xuất lẻ</a>
    @endif

@elseif($isAdmin)

    <span class="text-primary fw-bold">Admin tạo</span>

@else

    <span class="text-muted">Đã xử lý</span>

@endif

</td>

<td>

<form action="{{ route('admin.booking.togglePayment') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $booking->id }}">

    @if($booking->payment_method == 'Tiền mặt')

        <button class="btn {{ $booking->is_paid ? 'btn-success' : 'btn-warning' }} btn-sm">
            💵 {{ $booking->is_paid ? 'Đã thu' : 'Chưa thu' }}
        </button>

    @elseif($booking->payment_method == 'Chuyển khoản')

        <button class="btn {{ $booking->is_paid ? 'btn-primary' : 'btn-danger' }} btn-sm">
            💳 {{ $booking->is_paid ? 'Đã CK' : 'Chờ CK' }}
        </button>

    @elseif($booking->payment_method == 'admin_lock')

        <span class="badge bg-dark">🔒 Admin khóa</span>

    @else

        <span class="text-muted">Không rõ</span>

    @endif

</form>

</td>

<td>

@if($booking->status == 'approved')

    @if($booking->is_checked_in)
        <span class="badge bg-success">✅ Đã nhận</span>
    @else
        <form action="{{ route('admin.booking.checkin') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $booking->id }}">

            <button class="btn btn-info btn-sm"
                onclick="return confirm('Xác nhận?')">
                ✔️ Nhận sân
            </button>
        </form>
    @endif

@else
    <span class="text-muted">---</span>
@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection