@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Bước tiến trình (tùy chọn) --}}
    <div class="text-center mb-5">
        <h2 class="fw-800 text-dark">Xác nhận thông tin</h2>
        <p class="text-muted">Vui lòng kiểm tra lại các lựa chọn và hoàn tất thông tin đặt lịch</p>
    </div>

    @if(session('success') || session('error'))
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    @endif

    <div class="row justify-content-center g-4">
        {{-- CỘT TRÁI: FORM THÔNG TIN --}}
        <div class="col-lg-6 col-xl-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-user-edit me-2"></i>Thông tin liên hệ</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('booking.store.multiple') }}" id="checkout-form">
                        @csrf
                        @foreach($items as $item)
                            <input type="hidden" name="bookings[]" value="{{ $item }}">
                        @endforeach

                        <div class="mb-3">
                            <label class="form-label fw-600 text-dark">Họ và tên <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="far fa-user"></i></span>
                                <input type="text" name="fullname" class="form-control bg-light border-start-0 ps-0" placeholder="Nguyễn Văn A" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600 text-dark">Số điện thoại <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone-alt"></i></span>
                                <input type="text" name="phone" class="form-control bg-light border-start-0 ps-0" placeholder="0901234xxx" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600 text-dark">Phương thức thanh toán</label>
                            <div class="payment-selector d-flex gap-3">
                                <div class="payment-option flex-fill">
                                    <input type="radio" name="payment_method" value="Tiền mặt" id="pay_cash" checked>
                                    <label for="pay_cash" class="btn btn-outline-light text-dark border w-100 py-3">
                                        <i class="fas fa-money-bill-wave d-block mb-1"></i> Tiền mặt
                                    </label>
                                </div>
                                <div class="payment-option flex-fill">
                                    <input type="radio" name="payment_method" value="Chuyển khoản" id="pay_bank">
                                    <label for="pay_bank" class="btn btn-outline-light text-dark border w-100 py-3">
                                        <i class="fas fa-university d-block mb-1"></i> Chuyển khoản
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="qrBox" class="text-center mb-4 transition-fade" style="display: none;">
                            <div class="qr-wrapper p-3 border rounded-4 bg-white shadow-sm position-relative">
                                <p class="small fw-bold text-muted mb-2 text-uppercase">Quét mã VietQR</p>
                                <img id="qr-image" class="img-fluid rounded" style="max-height: 250px;">
                                <div class="mt-2 py-2 px-3 bg-light rounded-pill d-inline-block border">
                                    <small class="text-dark">Nội dung: <strong id="qr-content" class="text-primary"></strong></small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm btn-confirm">
                            XÁC NHẬN ĐẶT LỊCH NGAY
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 2rem;">
                <div class="card-header bg-dark text-white py-3 border-0 rounded-t-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i>Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="booking-list mb-4">
                        @php $totalPrice = 0; @endphp
                        @foreach($items as $item)
                            @php
                                $parts = explode('|', $item);
                                $facility = \App\Models\Facility::with('category')->find($parts[0]);
                                if(count($parts) == 3){
                                    list($fid, $date, $session) = $parts;
                                    $price = match($session) {
                                        'morning' => $facility->category->price_morning,
                                        'afternoon' => $facility->category->price_afternoon,
                                        default => $facility->category->price_evening
                                    };
                                    $timeText = $session == 'morning' ? 'Sáng (7h-11h)' : ($session == 'afternoon' ? 'Chiều (13h-17h)' : 'Tối (17h-21h)');
                                } else {
                                    list($fid, $date, $start, $end) = $parts;
                                    $hours = (strtotime($end) - strtotime($start)) / 3600;
                                    $price = $hours * $facility->category->price_hour;
                                    $timeText = "$start - $end";
                                }
                                $totalPrice += $price;
                            @endphp
                            
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">{{ $facility->name }}</h6>
                                    <div class="small text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="far fa-clock me-1"></i>{{ $timeText }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-dark">{{ number_format($price) }}đ</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted fw-bold h5 mb-0">TỔNG CỘNG</span>
                        <span class="text-primary fw-800 h4 mb-0">{{ number_format($totalPrice) }}đ</span>
                    </div>

                    <div class="alert alert-warning border-0 rounded-4 p-3 small mb-0">
                        <div class="d-flex gap-2">
                            <i class="fas fa-exclamation-circle text-warning mt-1"></i>
                            <div>
                                <strong class="d-block mb-1">Lưu ý quan trọng:</strong>
                                <ul class="ps-3 mb-0">
                                    <li>Vui lòng kiểm tra kỹ thời gian đã chọn.</li>
                                    <li>Đơn sẽ tự động hủy nếu không đến đúng giờ.</li>
                                    <li>Xác nhận sẽ được gửi sau khi nhân viên kiểm tra thanh toán.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #4361ee;
    --primary-soft: #f0f3ff;
}

body { background-color: #f5f7fb; }
.fw-800 { font-weight: 800; }
.fw-600 { font-weight: 600; }

.rounded-4 { border-radius: 1rem !important; }
.rounded-t-4 { border-radius: 1rem 1rem 0 0 !important; }

/* Custom Form Styles */
.form-control:focus {
    background-color: #fff;
    border-color: var(--primary-color);
    box-shadow: none;
}

.input-group-text {
    color: #94a3b8;
    border: 1px solid #dee2e6;
}

/* Payment Selector */
.payment-selector .payment-option input {
    display: none;
}

.payment-selector label {
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 12px;
}

.payment-selector input:checked + label {
    border-color: var(--primary-color) !important;
    background-color: var(--primary-soft) !important;
    color: var(--primary-color) !important;
    font-weight: bold;
}

/* QR Style */
.qr-wrapper {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.transition-fade {
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.btn-primary { background-color: var(--primary-color); border: none; }
.btn-primary:hover { background-color: #3730a3; }

.btn-confirm {
    letter-spacing: 1px;
}

.text-primary { color: var(--primary-color) !important; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    const qrBox = document.getElementById('qrBox');
    const qrImage = document.getElementById('qr-image');
    const qrContent = document.getElementById('qr-content');
    const phoneInput = document.querySelector('input[name="phone"]');

    const bank = "970436"; // Vietcombank
    const account = "1032674159";

    function generateQR() {
        let amount = {{ $totalPrice }};
        let phone = phoneInput.value.replace(/\s+/g, '') || "KHACH";
        let content = "DATSAN" + phone;

        let url = `https://img.vietqr.io/image/${bank}-${account}-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(content)}`;

        qrImage.src = url;
        qrContent.innerText = content;
    }

    function handlePaymentChange() {
        const selectedValue = document.querySelector('input[name="payment_method"]:checked').value;
        if (selectedValue === 'Chuyển khoản') {
            qrBox.style.display = 'block';
            generateQR();
        } else {
            qrBox.style.display = 'none';
        }
    }

    paymentOptions.forEach(opt => opt.addEventListener('change', handlePaymentChange));

    phoneInput.addEventListener('input', function () {
        if (document.querySelector('input[name="payment_method"]:checked').value === 'Chuyển khoản') {
            generateQR();
        }
    });

    // Chạy lần đầu khi load trang
    handlePaymentChange();
});
</script>
@endsection