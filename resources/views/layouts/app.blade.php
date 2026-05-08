<!DOCTYPE html>
<html>
<head>
    <title>Quản lý cơ sở vật chất</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            margin:0;
            padding-top:140px; /* 👈 tăng lên để chứa cả 2 navbar */
        }

        .navbar-nav{
            list-style:none;
        }

        .navbar-nav .nav-link{
            font-weight:600;
        }

        .navbar-nav .nav-link:hover{
            color:#ff4da6;
        }

        .navbar-pink{
            background-color:#ffd6e7;
        }

    </style>

</head>
<body>


<!-- HEADER FIXED -->
<div class="fixed-top shadow">

    <!-- TOP BAR -->
    <div style="background-color:#8FB9E6;">
    <div class="container d-flex justify-content-between align-items-center py-2">

        <!-- LOGO -->
        <div class="d-flex align-items-center">

            <img 
                src="{{ asset('images/logo-agu.png') }}"
                style="height:65px; margin-right:12px;"
            >

            <div class="lh-sm">

                <div style="font-size:18px; font-weight:700;">
                    TRƯỜNG ĐẠI HỌC AN GIANG
                </div>

                <div style="font-size:26px; font-weight:800; color:red;">
                    HỆ THỐNG QUẢN LÝ VÀ CHO THUÊ CƠ SỞ VẬT CHẤT
                </div>

            </div>

        </div>

       <!-- USER -->
        <div style="font-size:14px;">

            @auth

                <span class="me-2">
                    Xin chào, 
                    <a href="{{ route('profile') }}" class="fw-bold text-decoration-none">
                        {{ Auth::user()->ho_ten }}
                    </a>
                </span>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button class="btn btn-sm btn-light">
                        Đăng xuất
                    </button>
                </form>

            @endauth

            @guest

                <a href="{{ route('login') }}" class="btn btn-sm btn-light">
                    Đăng nhập
                </a>

            @endguest

        </div>
    </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-pink border-top border-bottom">

    <div class="container">

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">

    <!-- MENU TRÁI -->
    <ul class="navbar-nav me-auto">

        <li class="nav-item">
            <a class="nav-link" href="{{ route('booking.home') }}">
                Trang chủ
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('gioithieu') }}">
                Giới thiệu
            </a>
        </li>

         <li class="nav-item">
            <a class="nav-link" href="{{ route('lienhe') }}">
                Liên hệ
            </a>
        </li>

        <li class="nav-item">
    <a class="nav-link" href="{{ route('facilities.index') }}">
        Cơ sở vật chất
    </a>
</li>

        <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                Danh mục
            </a>

            <ul class="dropdown-menu">

                @foreach($categories as $category)

                    <li>
                        <a class="dropdown-item" href="{{ route('category.show',$category->id) }}">
                            {{ $category->name }}
                        </a>
                    </li>

                @endforeach

            </ul>

        </li>

    </ul>

    <!-- MENU PHẢI -->
    <ul class="navbar-nav align-items-center">

        @auth

            @if(Auth::user()->vai_tro === 'user')
                <li class="nav-item">
                    <a href="{{ route('booking.my') }}" class="nav-link">
                        📋 Lịch của tôi
                    </a>
                </li>
            @endif

            <!-- THÔNG BÁO -->
            <li class="nav-item dropdown">

                <button 
                    class="btn nav-link position-relative"
                    data-bs-toggle="dropdown"
                    style="border:none; background:none;"
                >
                    🔔

                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span 
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:10px;"
                        >
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width:300px;">

                    <li class="p-2 border-bottom fw-bold">
                        Thông báo
                    </li>

                    @forelse(Auth::user()->unreadNotifications as $notification)

                        <li>
                            <a class="dropdown-item p-3"
                               href="{{ url('/notification/read/'.$notification->id) }}">
                                <small class="fw-bold d-block">
                                    {{ $notification->data['title'] }}
                                </small>

                                <small class="text-muted">
                                    {{ $notification->data['message'] }}
                                </small>
                            </a>
                        </li>

                    @empty

                        <li class="p-3 text-center text-muted">
                            Không có thông báo
                        </li>

                    @endforelse

                </ul>

            </li>

            @if(Auth::user()->vai_tro === 'admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        ⚙️ Quản lý
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('booking.home') }}">
                        🛒 Đặt ngay
                    </a>
                </li>
            @endif

        @endauth

        @guest

            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    Đăng nhập
                </a>
            </li>

        @endguest

    </ul>

    </div>
    </div>

    </nav>

</div>


<!-- CONTENT -->
 @if ($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" 
                    class="btn-close" 
                    data-bs-dismiss="alert">
            </button>

        </div>
    </div>
@endif

    @yield('content')
@if (!isset($hideFooter))
<footer class="footer-agu pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4 justify-content-between">

            <div class="col-lg-4 col-md-12">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset('images/logo-agu.png') }}" alt="Logo" style="width:55px; height: auto;">
                    <div class="lh-sm">
                        <h5 class="mb-0 fw-bold text-dark text-uppercase" style="font-size: 1.1rem; letter-spacing: 0.5px;">
                            Trung tâm Quản lý dịch vụ
                        </h5>
                        <div class="text-primary fw-semibold" style="font-size: 0.9rem;">
                            Trường Đại học An Giang
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.6; max-width: 350px;">
                    Hệ thống quản lý và cho thuê cơ sở vật chất. Hỗ trợ đặt lịch và sử dụng tài nguyên hiệu quả.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-muted social-hover"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-muted social-hover"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="text-muted social-hover"><i class="bi bi-envelope-fill"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Về chúng tôi</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('booking.home') }}">Trang chủ</a></li>
                    <li><a href="{{ route('gioithieu') }}">Giới thiệu</a></li>
                    <li><a href="{{ route('lienhe') }}">Liên hệ</a></li>
                    <li><a href="{{ route('facilities.index') }}">Đặt lịch</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Chính sách</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Điều khoản</a></li>
                    <li><a href="#">Bảo mật</a></li>
                    <li><a href="#">Quy định</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="footer-title">Liên hệ</h6>
                <div class="footer-contact-info">
                    <p><i class="bi bi-geo-alt me-2 text-primary"></i> 18 Ung Văn Khiêm, Long Xuyên</p>
                    <p><i class="bi bi-person me-2 text-primary"></i> Phạm Thị Mỹ Tiên</p>
                    <p><i class="bi bi-telephone me-2 text-primary"></i> 037 327 6510</p>
                </div>
            </div>

        </div>

        <hr class="my-4" style="opacity: 0.1;">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-muted" style="font-size: 0.75rem;">
                    © {{ date('Y') }} <strong>Đại học An Giang</strong>. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-muted" style="font-size: 0.75rem;">
                    Developed by My Tien
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-agu {
        /* Đổi sang màu xanh nhạt pastel chuyên nghiệp */
        background-color: #f0f7ff; 
        border-top: 1px solid #dbeafe;
    }

    .footer-title {
        /* Chỉnh tiêu đề các cột to hơn xíu và đậm nét */
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #1e293b;
        margin-bottom: 1.2rem;
    }

    .footer-links li {
        margin-bottom: 8px;
    }

    .footer-links a {
        text-decoration: none;
        color: #475569;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .footer-links a:hover {
        color: #0d6efd;
        padding-left: 4px;
    }

    .footer-contact-info p {
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .social-hover {
        font-size: 1.1rem;
        transition: transform 0.2s, color 0.2s;
    }

    .social-hover:hover {
        color: #0d6efd !important;
        transform: translateY(-2px);
    }
</style>
@endif

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>