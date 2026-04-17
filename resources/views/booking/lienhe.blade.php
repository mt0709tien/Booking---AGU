@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { background-color: #f4f7f6; }
    .contact-header {
        background: linear-gradient(135deg, #d2c3c3 0%, #b71c1c 100%);
        padding: 80px 0;
        border-radius: 0 0 50px 50px;
        color: white;
        margin-bottom: -50px;
    }
    .info-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        background: #fff;
    }
    .info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    .bg-light-danger { background-color: rgba(229, 57, 53, 0.1); color: #e53935; }
    .bg-light-blue { background-color: rgba(33, 150, 243, 0.1); color: #2196f3; }
    .bg-light-success { background-color: rgba(76, 175, 80, 0.1); color: #4caf50; }
    .bg-light-warning { background-color: rgba(255, 152, 0, 0.1); color: #ff9800; }
    
    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
</style>

<div class="contact-header text-center">
    <div class="container">
        <h1 class="fw-extrabold display-4 mb-3">LIÊN HỆ HỖ TRỢ</h1>
        <p class="lead opacity-75">Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn</p>
    </div>
</div>

<div class="container pb-5" style="position: relative; z-index: 2;">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-3 col-md-6">
            <div class="card info-card shadow-sm h-100 p-4 text-center">
                <div class="icon-box bg-light-danger mx-auto">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h5 class="fw-bold">Địa chỉ</h5>
                <p class="text-muted small">
                    Tầng trệt, Tòa nhà Thư viện<br>
                    18 Ung Văn Khiêm, P. Đông Xuyên,<br>
                    TP. Long Xuyên, An Giang
                </p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card shadow-sm h-100 p-4 text-center">
                <div class="icon-box bg-light-blue mx-auto">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h5 class="fw-bold">Người phụ trách</h5>
                <p class="text-muted">Phạm Thị Mỹ Tiên</p>
                <span class="badge bg-light-blue text-primary rounded-pill px-3 py-2">Quản lý dịch vụ</span>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card shadow-sm h-100 p-4 text-center">
                <div class="icon-box bg-light-success mx-auto">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h5 class="fw-bold">Email</h5>
                <a href="mailto:mytien6510@gmail.com" class="text-decoration-none fw-bold text-success">
                    mytien6510@gmail.com
                </a>
                <p class="text-muted small mt-2">Phản hồi trong 24h</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card shadow-sm h-100 p-4 text-center">
                <div class="icon-box bg-light-warning mx-auto">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
                <h5 class="fw-bold">Điện thoại</h5>
                <a href="tel:0373276510" class="text-decoration-none fw-bold text-warning fs-5">
                    0373 276 510
                </a>
                <p class="text-muted small mt-2">Thứ 2 - Thứ 7 (7h - 17h)</p>
            </div>
        </div>
    </div>

    <div class="row mt-5 justify-content-center">
        <div class="col-md-10">
            <div class="card info-card shadow-sm border-0 p-2">
                <div class="row align-items-center g-0">
                    <div class="col-md-7">
                        <div class="p-4">
                            <h4 class="fw-bold text-danger mb-3">Trung tâm Quản lý dịch vụ</h4>
                            <p class="text-muted">Hệ thống đặt sân trực tuyến giúp bạn tiết kiệm thời gian và đảm bảo có sân chơi vào khung giờ mong muốn. Nếu gặp khó khăn khi thanh toán hoặc hủy lịch, đừng ngần ngại gọi cho chúng tôi.</p>
                            <div class="d-flex gap-3">
                                <div class="text-center p-3 border rounded-3 flex-fill bg-light">
                                    <h6 class="mb-0 fw-bold">100%</h6>
                                    <small class="text-muted">Hỗ trợ tận tâm</small>
                                </div>
                                <div class="text-center p-3 border rounded-3 flex-fill bg-light">
                                    <h6 class="mb-0 fw-bold">Sáng suốt</h6>
                                    <small class="text-muted">Giải quyết nhanh</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 d-none d-md-block text-center">
                        <img src="https://img.freepik.com/free-vector/customer-support-illustration-concept_23-2148889374.jpg" class="img-fluid p-4" alt="Support">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection