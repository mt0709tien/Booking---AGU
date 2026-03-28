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

    <div class="row">

        {{-- ================= FORM ================= --}}
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-body">

                    <form method="POST" action="{{ route('booking.store.multiple') }}" id="booking-form">
                        @csrf

                        @foreach($items as $item)
    <input type="hidden" name="bookings[]" value="{{ $item }}">
@endforeach

                        {{-- DANH SÁCH --}}
                        <div class="mb-3">
                            <label><strong>Các ca đã chọn:</strong></label>
                            <ul class="list-group">
                                @php $totalPrice = 0; @endphp

                                @foreach($items as $item)
                                    @php
                                        list($facility_id, $date, $session) = explode('|', $item);
                                        $facility = \App\Models\Facility::find($facility_id);

                                        $price = match($session) {
                                            'morning' => $facility->category->price_morning,
                                            'afternoon' => $facility->category->price_afternoon,
                                            default => $facility->category->price_evening
                                        };

                                        $totalPrice += $price;
                                    @endphp

                                    <li class="list-group-item d-flex justify-content-between">
                                        {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }} -
                                        @if($session == 'morning') Sáng
                                        @elseif($session == 'afternoon') Chiều
                                        @else Tối
                                        @endif

                                        <span class="badge bg-secondary">
                                            {{ number_format($price) }} VNĐ
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- TỔNG TIỀN --}}
                        <div class="mb-3">
                            <label><strong>Tổng tiền</strong></label>
                            <input type="text" class="form-control"
                                value="{{ number_format($totalPrice) }} VNĐ" readonly>
                        </div>

                        {{-- THÔNG TIN --}}
                        <div class="mb-3">
                            <label>Họ tên</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>SĐT</label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Thanh toán</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="Tiền mặt">Tiền mặt</option>
                                <option value="Chuyển khoản">Chuyển khoản</option>
                            </select>
                        </div>

                        {{-- NÚT --}}
                        <button id="btn-cash" class="btn btn-primary w-100">
                            Xác nhận đặt
                        </button>

                        <button type="button" id="btn-transfer"
                            class="btn btn-success w-100 mt-2"
                            style="display:none;">
                            Tôi đã chuyển khoản
                        </button>

                    </form>

                </div>
            </div>
        </div>

        {{-- ================= QR RIÊNG ================= --}}
        <div class="col-md-5">

            <div id="qr-card" class="card shadow text-center" style="display:none;">
                <div class="card-body">

                    <h5 class="text-success">Thanh toán chuyển khoản</h5>

                    <img id="qr-image" src="" class="my-3" style="max-width:250px;">

                    <p><strong>Số tiền:</strong> {{ number_format($totalPrice) }} VNĐ</p>
                    <p><strong>Ngân hàng:</strong> Vietcombank</p>
                    <p><strong>STK:</strong> 1032 674159</p>

                    <p class="text-danger">
                        Nội dung: <span id="qr-content"></span>
                    </p>

                </div>
            </div>

        </div>

    </div>

</div>

{{-- ================= SCRIPT ================= --}}
<script>

const paymentSelect = document.getElementById('payment_method');
const qrCard = document.getElementById('qr-card');
const qrImage = document.getElementById('qr-image');
const qrContent = document.getElementById('qr-content');
const phoneInput = document.getElementById('phone');

const btnCash = document.getElementById('btn-cash');
const btnTransfer = document.getElementById('btn-transfer');

// 👉 sửa tại đây
const bank = "970436";
const account = "1032674159";

function generateQR() {

    const amount = {{ $totalPrice }};
    const phone = phoneInput.value || "KHACH";
    const content = "DAT SAN " + phone;

    const url = `https://img.vietqr.io/image/${bank}-${account}-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(content)}`;

    qrImage.src = url;
    qrContent.innerText = content;
}

// chọn phương thức
paymentSelect.addEventListener('change', function() {

    if (this.value === "Chuyển khoản") {

        qrCard.style.display = "block";
        btnCash.style.display = "none";
        btnTransfer.style.display = "block";

        generateQR();

    } else {

        qrCard.style.display = "none";
        btnCash.style.display = "block";
        btnTransfer.style.display = "none";
    }

});

// nhập SĐT -> update QR
phoneInput.addEventListener('input', function() {
    if (paymentSelect.value === "Chuyển khoản") {
        generateQR();
    }
});

// bấm đã chuyển khoản
btnTransfer.addEventListener('click', function() {

    alert("Đã ghi nhận! Vui lòng chờ admin duyệt.");

    document.getElementById('booking-form').submit();

});

</script>

@endsection