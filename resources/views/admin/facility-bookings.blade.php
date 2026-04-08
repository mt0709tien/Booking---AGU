@extends('layouts.admin')

@section('admin_content')

<div class="container">

<h3 class="mb-4">
    Đơn đặt - {{ $facility->name }}
</h3>

<a href="{{ route('admin.facilities') }}" class="btn btn-secondary mb-3">
← Quay lại
</a>

<table class="table table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Người đặt</th>
<th>Ngày</th>
<th>Ca</th>
<th>Trạng thái</th>
</tr>
</thead>

<tbody>

@foreach($bookings as $booking)
<tr>
<td>{{ $booking->id }}</td>

<td>{{ $booking->fullname }}</td>

<td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>

<td>{{ $booking->session }}</td>

<td>
    @if($booking->status == 'locked')
        <span class="badge bg-dark">Đã khóa</span>
    @elseif($booking->status == 'approved')
        <span class="badge bg-success">Đã duyệt</span>
    @elseif($booking->status == 'pending')
        <span class="badge bg-warning">Chờ duyệt</span>
    @endif
</td>

</tr>
@endforeach

</tbody>

</table>

</div>

@endsection