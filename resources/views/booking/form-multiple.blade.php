{{-- resources/views/booking/form-multiple.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center mb-4 fw-bold text-primary">
        Đặt lịch
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

    <div class="row justify-content-center">

    <div class="col-md-7">
            <div class="card shadow">
                <div class="card-body">

                    <form method="POST" action="{{ route('booking.store.multiple') }}">
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
                            <label class="text-danger fw-bold">*Họ tên</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="text-danger fw-bold">*Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required>
                            <small class="text-muted">
                                Vui lòng nhập đúng số điện thoại để trung tâm liên hệ xác nhận đơn đặt của bạn
                            </small>
                        </div>

                        {{-- THANH TOÁN --}}
                        <div class="mb-3">
                            <label>Thanh toán</label>

                            <select name="payment_method" class="form-control">
                                <option value="Tiền mặt">Tiền mặt</option>
                                <option value="Chuyển khoản">Chuyển khoản</option>
                            </select>

                            <small class="text-muted d-block mt-1">
                                Nếu chọn tiền mặt vui lòng liên hệ trực tiếp tại Trung tâm Quản lý dịch vụ (Tầng trệt, Tòa nhà Thư viện và các Trung tâm, 18
Ung Văn Khiêm, phường Đông Xuyên, TP. Long Xuyên, tỉnh An Giang) để thanh toán.
                            </small>

                            <small class="text-muted d-block">
                                Nếu chọn chuyển khoản vui lòng chuyển khoản vào mã QR hoặc chuyển khoản trực tiếp vào tài khoản trường Đại Học An Giang - Chủ tài khoản: Trường Đại học An Giang.
- Số tài khoản: 0151000012164.
- Ngân hàng: Vietcombank chi nhánh An Giang.
- Nội dung chuyển khoản ghi rõ: HỌ TÊN-ĐK SÂN ….(vd: ĐK sân bóng
đá/bóng chuyền/bóng rổ).
.
                            </small>
                        </div>

                        {{-- 🔥 LƯU Ý --}}
                        <div class="mt-3 p-3 bg-warning-subtle border border-warning rounded text-start">

                            <p class="text-danger fw-bold mb-2">⚠️ Lưu ý:</p>

                            <p class="mb-1">
                                * Sau khi đặt vui lòng đợi điện thoại xác nhận đơn đặt phòng/sân của bạn.
                            </p>

                            <p class="mb-0">
                                * Đơn đặt sân của quý khách sẽ tự động hủy nếu sau 1 giờ tính từ thời gian bắt đầu ca quý khách không nhận phòng/sân.
                            </p>

                        </div>

                        {{-- NÚT --}}
                        <button class="btn btn-primary w-100 mt-3">
                            Xác nhận đặt
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection