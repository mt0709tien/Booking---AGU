@extends('layouts.app')

@section('content')

<div class="container">

<h3 class="mb-4">Danh sách đặt lịch</h3>

<table class="table table-bordered table-striped">

<thead>
<tr>
<th>ID</th>
<th>Người dùng</th>
<th>Họ tên</th>
<th>SĐT</th>
<th>Cơ sở</th>
<th>Ngày đặt</th>
<th>Ca</th>
<th>Giá</th>
<th>Thanh toán</th>
<th>Trạng thái</th>
<th>Thời gian đặt</th>
<th>Hành động</th>
</tr>
</thead>

<tbody>

@foreach($bookings as $booking)

<tr>

<td>{{ $booking->id }}</td>

<td>{{ $booking->user->ho_ten }}</td>

<td>{{ $booking->fullname }}</td>

<td>{{ $booking->phone }}</td>

<td>{{ $booking->facility->name }}</td>

<td>{{ $booking->booking_date }}</td>

<td>
    @if($booking->session == 'morning')
        Sáng (7h - 11h)
    @elseif($booking->session == 'afternoon')
        Chiều (13h - 17h)
    @elseif($booking->session == 'evening')
        Tối (17h - 21h)
    @endif
</td>

<td>{{ number_format($booking->price) }} VNĐ</td>

<td>{{ $booking->payment_method }}</td>

<!-- 🔥 TRẠNG THÁI -->
<td>
    @if($booking->status == 'pending')
        <span class="badge bg-warning text-dark">Chờ duyệt</span>

    @elseif($booking->status == 'approved')
        <span class="badge bg-success">Đã duyệt</span>

    @elseif($booking->status == 'rejected')
        <span class="badge bg-danger">Từ chối</span>

    @elseif($booking->status == 'cancelled')
        <span class="badge bg-secondary">Đã hủy</span>
    @endif
</td>

<td>{{ $booking->created_at }}</td>

<!-- 🔥 HÀNH ĐỘNG -->
<td>

    @if($booking->status == 'pending')

        <a href="{{ route('admin.booking.approve',$booking->id) }}"
           class="btn btn-success btn-sm">
           ✔️ Duyệt
        </a>

        <a href="{{ route('admin.booking.reject',$booking->id) }}"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Bạn có chắc muốn từ chối?')">
           ❌ Từ chối
        </a>

    @else
        <span class="text-muted">Đã xử lý</span>
    @endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection