@extends('layouts.app')

@section('content')

<div class="container py-4">

<h3 class="text-center mb-4">
@if(isset($isMultiple))
    Đặt nhiều lịch
@else
    Đặt lịch {{ $facility->name }}
@endif
</h3>

<div class="card shadow">

<div class="card-body">

<form method="POST"
action="{{ isset($isMultiple) ? route('booking.store.multiple') : route('booking.store') }}">

@csrf

{{-- ================= SINGLE ================= --}}
@if(!isset($isMultiple))

    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
    <input type="hidden" name="booking_date" value="{{ $date }}">
    <input type="hidden" name="session" value="{{ $session }}">

    <div class="mb-3">
        <label>Ngày đặt</label>
        <input type="text"
        class="form-control"
        value="{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}"
        readonly>
    </div>

    <div class="mb-3">
        <label>Buổi</label>
        <input type="text"
        class="form-control"
        value="{{ $session }}"
        readonly>
    </div>

    <div class="mb-3">
        <label>Số tiền</label>
        <input type="text"
        class="form-control"
        value="{{ number_format($price) }} VNĐ"
        readonly>
    </div>

@endif


{{-- ================= MULTIPLE ================= --}}
@if(isset($isMultiple))

    <input type="hidden" name="bookings" value="{{ implode(',', $items) }}">

    <div class="mb-3">
        <label><strong>Các ca đã chọn:</strong></label>

        <ul class="list-group">
            @foreach($items as $item)

                @php
                    list($facility_id, $date, $session) = explode('|', $item);
                @endphp

                <li class="list-group-item">
                    Ngày: {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                    - Ca:
                    @if($session == 'morning') Sáng
                    @elseif($session == 'afternoon') Chiều
                    @else Tối
                    @endif
                </li>

            @endforeach
        </ul>
    </div>

@endif


{{-- ================= THÔNG TIN CHUNG ================= --}}
<div class="mb-3">
<label>Họ tên</label>
<input type="text"
name="fullname"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Số điện thoại</label>
<input type="text"
name="phone"
class="form-control"
required>
</div>

<div class="mb-3">

<label>Thanh toán</label>

<select name="payment_method" class="form-control">

<option value="Tiền mặt">Tiền mặt</option>
<option value="Chuyển khoản">Chuyển khoản</option>

</select>

</div>

<button class="btn btn-primary w-100">
Xác nhận đặt
</button>

</form>

</div>

</div>

</div>

@endsection