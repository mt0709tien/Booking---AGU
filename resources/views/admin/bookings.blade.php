@extends('layouts.app')

@section('content')

<h3 class="mb-4">Danh sách đặt lịch</h3>

<table class="table table-bordered">

<tr>
<th>Người đặt</th>
<th>Cơ sở</th>
<th>Ngày</th>
<th>Giờ</th>
</tr>

@foreach($bookings as $booking)

<tr>
<td>{{ $booking->user->ho_ten }}</td>
<td>{{ $booking->facility->name }}</td>
<td>{{ $booking->date }}</td>
<td>{{ $booking->time }}</td>
</tr>

@endforeach

</table>

@endsection