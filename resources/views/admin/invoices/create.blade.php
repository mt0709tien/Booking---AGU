@extends('layouts.admin')

@section('admin_content')

<div class="container">

<h3 class="mb-4">Tạo hóa đơn</h3>

<div class="card shadow">
    <div class="card-body">

        <form method="POST" action="{{ route('admin.invoice.store') }}">
            @csrf

            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="mb-3">
                <label>Khách hàng</label>
                <input type="text" class="form-control"
                       value="{{ $booking->fullname }}" readonly>
            </div>

            <div class="mb-3">
                <label>SĐT</label>
                <input type="text" class="form-control"
                       value="{{ $booking->phone }}" readonly>
            </div>

            <div class="mb-3">
                <label>Cơ sở</label>
                <input type="text" class="form-control"
                       value="{{ $booking->facility->name ?? '' }}" readonly>
            </div>

            <div class="mb-3">
                <label>Ngày đặt</label>
                <input type="text" class="form-control"
                       value="{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}" readonly>
            </div>

            <div class="mb-3">
                <label>Ca</label>
                <input type="text" class="form-control"
                       value="{{ $booking->session }}" readonly>
            </div>

            <div class="mb-3">
                <label>Giá</label>
                <input type="text" class="form-control"
                       value="{{ number_format($booking->price) }} VNĐ" readonly>
            </div>

            <button class="btn btn-success w-100">
                🧾 Xác nhận tạo hóa đơn
            </button>

        </form>

    </div>
</div>

</div>

@endsection