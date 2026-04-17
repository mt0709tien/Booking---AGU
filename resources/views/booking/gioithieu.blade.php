@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Inter', sans-serif;
        color: #334155;
        scroll-behavior: smooth;
    }

    .about-header {
        background: linear-gradient(135deg, #eff1f4 0%, #1269f5 100%);
        padding: 80px 0;
        border-radius: 0 0 50px 50px;
        color: white;
        margin-bottom: -50px;
    }

    .intro-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: none;
        transition: transform 0.3s ease;
    }

    .intro-card:hover {
        transform: translateY(-5px);
    }

    .section-title {
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 25px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, #3b82f6, transparent);
        border-radius: 10px;
    }

    .doc-card {
        border: none;
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .doc-card:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .doc-icon {
        width: 50px;
        height: 50px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 15px;
    }

    .facility-img-wrapper {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .feature-tag {
        background: #f8fafc;
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
    }

    .feature-tag i {
        color: #3b82f6;
        margin-right: 10px;
    }
</style>

<div class="about-header text-center">
    <div class="container">
        <h1 class="fw-bold display-4 mb-3 text-uppercase">Giới thiệu cơ sở vật chất</h1>
        <p class="lead opacity-75">
            Trường Đại học An Giang cung cấp nhiều loại cơ sở vật chất hiện đại phục vụ học tập, sự kiện và thể thao
        </p>
    </div>
</div>

<div class="container pb-5" style="position: relative; z-index: 2;">

    <!-- Văn bản -->
    <div class="intro-card mb-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-file-signature me-2 text-primary"></i>
                Văn bản & Quy định tài chính
            </h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="doc-card">
                    <div class="doc-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Thông báo niêm yết giá cho thuê tài sản công</h6>
                        <p class="small text-muted mb-2">Số: 11/TB-ĐHAG</p>
                        <a href="{{ asset('images/11-TB-DHAG Thong bao v-v niem yet gia cho thue tai san cong tai Truong DHAG theo hinh thuc cho thue truc tiep.pdf') }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="doc-card">
                    <div class="doc-icon" style="background: rgba(16,185,129,0.1); color:#10b981;">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Thông báo giá thuê sân thể thao</h6>
                        <p class="small text-muted mb-2">Sân bóng đá, bóng chuyền, bóng rổ</p>
                        <a href="{{ asset('images/TB GIÁ THUÊ SÂN BÓNG ĐÁ, BÓNG CHUYỀN, BÓNG RỔ.signed_0.pdf') }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hội trường -->
    <div class="card intro-card border-0 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <div class="facility-img-wrapper">
                    <img src="{{ asset('images/HT.jpg') }}" class="img-fluid w-100" alt="Hội trường">
                </div>
            </div>
            <div class="col-md-6 ps-md-5">
                <h2 class="section-title">Hội trường</h2>
                <p class="text-muted mb-4">
                    Hội trường được trang bị đầy đủ hệ thống âm thanh, máy chiếu, máy lạnh và thiết bị hỗ trợ hội nghị hiện đại.
                </p>
                <div class="feature-tag"><i class="fas fa-users"></i> Sức chứa: 600 chỗ, 300 chỗ, 150 chỗ</div>
                <div class="feature-tag"><i class="fas fa-video"></i> Máy chiếu & âm thanh hội nghị</div>
                <div class="feature-tag"><i class="fas fa-calendar-check"></i> Phù hợp hội nghị và sự kiện</div>
            </div>
        </div>
    </div>

    <!-- Phòng học -->
    <div class="card intro-card border-0 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-md-6 order-md-2">
                <div class="facility-img-wrapper">
                    <img src="{{ asset('images/PM.jpg') }}" class="img-fluid w-100" alt="Phòng học">
                </div>
            </div>
            <div class="col-md-6 pe-md-5 order-md-1">
                <h2 class="section-title">Phòng học & Phòng thực hành</h2>
                <p class="text-muted mb-4">
                    Các phòng học rộng rãi, thoáng mát và được trang bị đầy đủ thiết bị phục vụ giảng dạy.
                </p>
                <div class="feature-tag"><i class="fas fa-users"></i> Sức chứa từ 50 - 100 sinh viên</div>
                <div class="feature-tag"><i class="fas fa-desktop"></i> Trang bị máy chiếu và âm thanh</div>
                <div class="feature-tag"><i class="fas fa-laptop-code"></i> Phòng máy phục vụ thực hành</div>
            </div>
        </div>
    </div>

    <!-- Sân thể thao -->
    <div class="card intro-card border-0 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <div class="facility-img-wrapper">
                    <img src="{{ asset('images/TT.jpg') }}" class="img-fluid w-100" alt="Thể thao">
                </div>
            </div>
            <div class="col-md-6 ps-md-5">
                <h2 class="section-title">Sân thể thao</h2>
                <p class="text-muted mb-4">
                    Trường có nhiều sân thể thao phục vụ rèn luyện sức khỏe và tổ chức thi đấu.
                </p>
                <div class="feature-tag"><i class="fas fa-futbol"></i> Sân bóng đá</div>
                <div class="feature-tag"><i class="fas fa-volleyball-ball"></i> Sân bóng chuyền</div>
                <div class="feature-tag"><i class="fas fa-basketball-ball"></i> Sân bóng rổ</div>
                <div class="feature-tag"><i class="fas fa-table-tennis-paddle-ball"></i> Sân tennis</div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="text-muted small">&copy; 2026 Trường Đại học An Giang - ĐHQG-HCM</p>
    </div>

</div>
@endsection