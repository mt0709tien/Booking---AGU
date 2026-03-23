{{-- resources/views/booking/form-multiple.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center mb-4 fw-bold text-primary">
        Đặt nhiều lịch
    </h3>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">

            <form method="POST" action="{{ route('booking.store.multiple') }}">
                @csrf

                <input type="hidden" name="bookings" value="{{ implode(',', $items) }}">

                {{-- ================= DANH SÁCH CA ĐÃ CHỌN ================= --}}
                <div class="mb-3">
                    <label><strong>Các ca đã chọn:</strong></label>
                    <ul class="list-group">
                        @php $totalPrice = 0; @endphp
                        @foreach($items as $item)
                            @php
                                list($facility_id, $date, $session) = explode('|', $item);
                                $facility = \App\Models\Facility::find($facility_id);
                                $price = 0;
                                if ($session == 'morning') $price = $facility->category->price_morning;
                                elseif ($session == 'afternoon') $price = $facility->category->price_afternoon;
                                elseif ($session == 'evening') $price = $facility->category->price_evening;
                                $totalPrice += $price;
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Ngày: {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}  
                                - Ca: 
                                @if($session == 'morning') Sáng
                                @elseif($session == 'afternoon') Chiều
                                @else Tối
                                @endif
                                <span class="badge bg-secondary">{{ number_format($price) }} VNĐ</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Tổng tiền --}}
                <div class="mb-3">
                    <label><strong>Tổng tiền:</strong></label>
                    <input type="text" class="form-control" value="{{ number_format($totalPrice) }} VNĐ" readonly>
                </div>

                {{-- ================= THÔNG TIN NGƯỜI ĐẶT ================= --}}
                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Thanh toán</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="Tiền mặt">Tiền mặt</option>
                        <option value="Chuyển khoản">Chuyển khoản</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100">Xác nhận đặt nhiều lịch</button>

            </form>

        </div>
    </div>

</div>

@endsection