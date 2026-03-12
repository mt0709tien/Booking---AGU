@extends('layouts.app')

@section('content')

<!-- Banner -->
<div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
    <div class="container-fluid py-4 text-center">
        <h1 class="display-6 fw-bold text-danger">
            HỆ THỐNG ĐẶT LỊCH CƠ SỞ VẬT CHẤT
        </h1>
        <p class="fs-5 text-muted">
            Đặt lịch sử dụng sân bãi, phòng học, phòng máy và hội trường tại Trường Đại học An Giang
        </p>

        @guest
            <a href="{{ route('login') }}" class="btn btn-danger btn-lg mt-3">
                Đăng nhập để đặt lịch
            </a>
        @endguest
    </div>
</div>


<!-- Danh mục đặt -->
<div class="row g-4">

    <!-- Khu thể thao -->
    <div class="col-md-4">
        <div class="card border-0 shadow-lg h-100 text-center">
            <div class="card-body">
                <h4 class="card-title text-danger">Khu thể thao</h4>
                <p class="card-text">
                    Bao gồm sân bóng đá, bóng chuyền, cầu lông và tennis.
                </p>

                @auth
                    <a href="#" class="btn btn-outline-danger">
                        Đặt ngay
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Đăng nhập để đặt
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Phòng học -->
    <div class="col-md-4">
        <div class="card border-0 shadow-lg h-100 text-center">
            <div class="card-body">
                <h4 class="card-title text-danger">Phòng học & phòng máy</h4>
                <p class="card-text">
                    Đặt lịch sử dụng phòng học và phòng máy phục vụ học tập.
                </p>

                @auth
                    <a href="#" class="btn btn-outline-danger">
                        Đặt ngay
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Đăng nhập để đặt
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Hội trường -->
    <div class="col-md-4">
        <div class="card border-0 shadow-lg h-100 text-center">
            <div class="card-body">
                <h4 class="card-title text-danger">Hội trường</h4>
                <p class="card-text">
                    Đăng ký sử dụng hội trường cho sự kiện, hội thảo.
                </p>

                @auth
                    <a href="#" class="btn btn-outline-danger">
                        Đặt ngay
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Đăng nhập để đặt
                    </a>
                @endauth
            </div>
        </div>
    </div>

</div>


<!-- Giới thiệu ngắn -->
<div class="mt-5 p-4 bg-white shadow-sm rounded text-center">
    <h5 class="fw-bold">Hệ thống hoạt động 24/7</h5>
    <p class="text-muted mb-0">
        Người dùng có thể kiểm tra lịch trống và đặt lịch trực tuyến nhanh chóng, tiện lợi.
    </p>
</div>

@endsection