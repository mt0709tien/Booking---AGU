@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center mb-4 fw-bold text-success">
        Thanh toán chuyển khoản
    </h3>

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow text-center">
                <div class="card-body">

                    <p><strong>Số tiền:</strong> {{ number_format($booking->price) }} VNĐ</p>
                    <p><strong>Ngân hàng:</strong> Vietcombank</p>
                    <p><strong>STK:</strong> 1032674159</p>

                    <div id="qr-box">
                        <img id="qr-image" class="my-3" style="max-width:250px;">
                        <p class="text-danger">
                            Nội dung: <span id="qr-content"></span>
                        </p>
                    </div>

                    {{-- 🔙 NÚT QUAY LẠI --}}
                    <div class="mt-3">
                        <a href="{{ route('booking.my') }}" class="btn btn-secondary w-100">
                            ⬅️ Quay lại
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<script>

const qrImage = document.getElementById('qr-image');
const qrContent = document.getElementById('qr-content');

const bank = "970436";
const account = "1032674159";

window.onload = function() {

    const amount = {{ $booking->price }};
    const content = "DAT SAN {{ $booking->phone }}";

    const url = `https://img.vietqr.io/image/${bank}-${account}-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(content)}`;

    qrImage.src = url;
    qrContent.innerText = content;

};

</script>

@endsection