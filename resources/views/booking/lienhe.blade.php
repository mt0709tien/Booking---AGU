@extends('layouts.app')

@section('content')

<div class="container py-5">

    <!-- Tiêu đề -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-danger">📞 LIÊN HỆ HỖ TRỢ</h1>
        <p class="text-muted">
            Nếu bạn cần hỗ trợ về việc đặt sân hoặc sử dụng hệ thống, vui lòng liên hệ với chúng tôi
        </p>
    </div>

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <!-- Địa chỉ -->
                    <div class="mb-3">
                        <h5 class="fw-bold">🏫 Địa chỉ</h5>
                        <p class="mb-0">
                            Trung tâm Quản lý dịch vụ<br>
                            Tầng trệt, Tòa nhà Thư viện và các Trung tâm<br>
                            18 Ung Văn Khiêm, phường Đông Xuyên,<br>
                            TP. Long Xuyên, tỉnh An Giang
                        </p>
                    </div>

                    <hr>

                    <!-- Người phụ trách -->
                    <div class="mb-3">
                        <h5 class="fw-bold">👤 Người phụ trách</h5>
                        <p class="mb-0">
                            Phạm Thị Mỹ Tiên
                        </p>
                    </div>

                    <hr>

                    <!-- Email -->
                    <div class="mb-3">
                        <h5 class="fw-bold">📧 Email</h5>
                        <p class="mb-0">
                            <a href="mailto:mytien6510@gmail.com" class="text-decoration-none">
                               mytien6510@gmail.com
                            </a>
                        </p>
                    </div>

                    <hr>

                    <!-- Điện thoại -->
                    <div>
                        <h5 class="fw-bold">📱 Điện thoại</h5>
                        <p class="mb-0">
                            <a href="tel:0373276510" class="text-decoration-none">
                                0373276510
                            </a>
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection