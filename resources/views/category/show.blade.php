@extends('layouts.app')

@section('content')

<h2 class="text-center mb-5 fw-bold text-primary">
    {{ $category->name }}
</h2>

<div class="row g-4">

@foreach($category->facilities as $facility)

<div class="col-md-4">

<div class="card shadow border-0 h-100">

<img 
src="{{ $facility->image ? asset('images/'.$facility->image) : 'https://via.placeholder.com/400x250' }}"
class="card-img-top"
style="height:220px; object-fit:cover;"
>

<div class="card-body d-flex flex-column">

<h5 class="fw-bold mb-2">
{{ $facility->name }}
</h5>

<p class="text-muted small">
{{ $facility->description }}
</p>

{{-- Giá theo 3 buổi --}}
<div class="mb-3">

<div class="text-success fw-bold">
Sáng (7h - 11h):
{{ number_format($facility->category->price_morning) }} VNĐ
</div>

<div class="text-warning fw-bold">
Chiều (13h - 17h):
{{ number_format($facility->category->price_afternoon) }} VNĐ
</div>

<div class="text-danger fw-bold">
Tối (17h - 21h):
{{ number_format($facility->category->price_evening) }} VNĐ
</div>

</div>

{{-- trạng thái sân --}}
@if(($facility->bookings_count ?? 0) >= 14)

<span class="badge bg-danger mb-3">
Full tuần
</span>

@else

<span class="badge bg-success mb-3">
Còn trống
</span>

@endif

<a 
href="{{ route('booking.create',$facility) }}"
class="btn btn-primary mt-auto w-100"
>
Đặt ngay
</a>

</div>

</div>

</div>

@endforeach

</div>

@endsection