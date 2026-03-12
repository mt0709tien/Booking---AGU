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
<th>Thời gian đặt</th>
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

<td>{{ $booking->created_at }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection