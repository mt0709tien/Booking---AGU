@extends('layouts.app')

@section('content')

<div class="container py-4">

<h3 class="text-center fw-bold text-primary mb-4">
{{ $facility->name }}
</h3>

@if(session('success'))
<div class="alert alert-success text-center">
{{ session('success') }}
</div>
@endif

<div class="card shadow-sm">
<div class="card-body">
<div class="table-responsive">

<table class="table table-bordered text-center align-middle">

<thead class="table-light">
<tr>
<th>Thứ</th>
<th>Ngày</th>
<th>Sáng <br> (7h - 11h)</th>
<th>Chiều <br> (13h - 17h)</th>
<th>Tối <br> (17h - 21h)</th>
</tr>
</thead>

<tbody>

@foreach($weekDays as $day)

<tr>

<td class="fw-bold">
{{ $day['date']->isoFormat('dd') }}
</td>

<td>
{{ $day['date']->format('d-m-Y') }}
</td>

{{-- SÁNG --}}
<td>

@if(Auth::check() && Auth::user()->vai_tro == 'admin')

    {{-- ADMIN --}}
    @if($day['morning'])

        <span class="badge bg-dark px-3 py-2">
            Đã khóa
        </span>

    @else

        <button class="btn btn-warning">
            Khóa sân
        </button>

    @endif

@else

    {{-- USER --}}
    @if($day['morning'])

        <span class="badge bg-danger px-3 py-2">
            Đã thuê
        </span>

    @else

        <a
        href="{{ route('booking.form',[
        'facility'=>$facility->id,
        'date'=>$day['date']->format('Y-m-d'),
        'session'=>'morning'
        ]) }}"
        class="btn btn-outline-success">
        Đặt
        </a>

    @endif

@endif

<div class="small text-muted mt-1">
{{ number_format($facility->category->price_morning) }} VNĐ
</div>

</td>


{{-- CHIỀU --}}
<td>

@if(Auth::check() && Auth::user()->vai_tro == 'admin')

    @if($day['afternoon'])

        <span class="badge bg-dark px-3 py-2">
            Đã khóa
        </span>

    @else

        <button class="btn btn-warning">
            Khóa sân
        </button>

    @endif

@else

    @if($day['afternoon'])

        <span class="badge bg-danger px-3 py-2">
            Đã thuê
        </span>

    @else

        <a
        href="{{ route('booking.form',[
        'facility'=>$facility->id,
        'date'=>$day['date']->format('Y-m-d'),
        'session'=>'afternoon'
        ]) }}"
        class="btn btn-outline-success">
        Đặt
        </a>

    @endif

@endif

<div class="small text-muted mt-1">
{{ number_format($facility->category->price_afternoon) }} VNĐ
</div>

</td>


{{-- TỐI --}}
<td>

@if(Auth::check() && Auth::user()->vai_tro == 'admin')

    @if($day['evening'])

        <span class="badge bg-dark px-3 py-2">
            Đã khóa
        </span>

    @else

        <button class="btn btn-warning">
            Khóa sân
        </button>

    @endif

@else

    @if($day['evening'])

        <span class="badge bg-danger px-3 py-2">
            Đã thuê
        </span>

    @else

        <a
        href="{{ route('booking.form',[
        'facility'=>$facility->id,
        'date'=>$day['date']->format('Y-m-d'),
        'session'=>'evening'
        ]) }}"
        class="btn btn-outline-success">
        Đặt
        </a>

    @endif

@endif

<div class="small text-muted mt-1">
{{ number_format($facility->category->price_evening) }} VNĐ
</div>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>
</div>

</div>

@endsection