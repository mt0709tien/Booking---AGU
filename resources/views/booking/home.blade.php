@extends('layouts.app')

@section('content')

<div class="p-5 mb-5 rounded-3 shadow-lg position-relative" 
     style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
            url('{{ asset('images/AGU.jpg') }}') no-repeat center center; 
            background-size: cover; 
            min-height: 350px; 
            display: flex; 
            align-items: center;">
    <div class="container-fluid py-5 text-center text-white">
        <h1 class="display-4 fw-bold mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
            HỆ THỐNG ĐẶT LỊCH CƠ SỞ VẬT CHẤT
        </h1>
        <p class="fs-4 mb-0" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
            Trường Đại học An Giang
        </p>
    </div>
</div>

<div class="container">
    
    <h3 class="text-danger fw-bold mb-4 border-start border-danger border-4 ps-3">Khu Thể Thao</h3>
    <div class="row g-4 mb-5">
        @php
            $sports = [
                ['id' => 1, 'name' => 'Sân Bóng Đá', 'icon' => '⚽'],
                ['id' => 3, 'name' => 'Sân Bóng Chuyền', 'icon' => '🏐'],
                ['id' => 2, 'name' => 'Sân Bóng Rỗ', 'icon' => '🏀'],
                ['id' => 4, 'name' => 'Sân Tennis', 'icon' => '🎾'],
            ];
        @endphp
        @foreach($sports as $item)
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100 text-center hover-shadow transition">
                <div class="card-body py-4">
                    <div class="display-6 mb-2">{{ $item['icon'] }}</div>
                    <h5 class="card-title fw-bold">{{ $item['name'] }}</h5>
                    <a href="{{ route('category.show', $item['id']) }}" class="btn btn-sm btn-outline-danger mt-2 px-4">
                        Đặt ngay
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h3 class="text-danger fw-bold mb-4 border-start border-danger border-4 ps-3">Hội Trường</h3>
    <div class="row g-4 mb-5">
        @php
            $halls = [
                ['id' => 5, 'name' => 'Hội trường 600', 'desc' => 'Sức chứa tối đa 600 chỗ ngồi'],
                ['id' => 6, 'name' => 'Hội trường 300', 'desc' => 'Sức chứa tối đa 300 chỗ ngồi'],
                ['id' => 7, 'name' => 'Hội trường 150', 'desc' => 'Sức chứa tối đa 150 chỗ ngồi'],
            ];
        @endphp
        @foreach($halls as $hall)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center border-top border-danger border-3">
                <div class="card-body py-4">
                    <h5 class="card-title fw-bold text-uppercase">{{ $hall['name'] }}</h5>
                    <p class="small text-muted">{{ $hall['desc'] }}</p>
                    <a href="{{ route('category.show', $hall['id']) }}" class="btn btn-danger btn-sm px-4">
                        Đăng ký
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h3 class="text-danger fw-bold mb-4 border-start border-danger border-4 ps-3">Phòng Học & Máy Tính</h3>
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 d-flex flex-row align-items-center p-3">
                <div class="display-6 me-3">📚</div>
                <div>
                    <h5 class="fw-bold mb-1">Phòng Học Lý Thuyết</h5>
                    <p class="small text-muted mb-0">Hệ thống phòng học tại các dãy nhà A, B, C, D.</p>
                </div>
                <a href="{{ route('category.show', 8) }}" class="btn btn-outline-danger btn-sm ms-auto">Xem lịch</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 d-flex flex-row align-items-center p-3">
                <div class="display-6 me-3">💻</div>
                <div>
                    <h5 class="fw-bold mb-1">Phòng Máy Tính</h5>
                    <p class="small text-muted mb-0">Phòng thực hành tin học chuyên dụng.</p>
                </div>
                <a href="{{ route('category.show', 9) }}" class="btn btn-outline-danger btn-sm ms-auto">Xem lịch</a>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>

@endsection