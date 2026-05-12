@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="container mt-4">
        <div class="alert alert-success text-center shadow-sm">
            {{ session('success') }}
        </div>
    </div>
@endif
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --agu-red: #dc3545; 
        --agu-dark: #1e293b;
    }

    body {
        background-color: #f8fafc;
        font-family: 'Poppins', sans-serif;
    }

    .hero-banner {
        background:
            linear-gradient(rgba(15,23,42,0.65), rgba(15,23,42,0.65)),
            url('{{ asset('images/AGU.jpg') }}') no-repeat center center;
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        border-radius: 0 0 40px 40px;
        margin-bottom: -35px;
    }

    .hero-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        font-weight: 300;
        letter-spacing: 1px;
    }

    .facility-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        transition: all .4s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,.03);
    }

    .facility-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,.12);
    }

    .img-container {
        position: relative;
        height: 180px;
        overflow: hidden;
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .6s;
    }

    .facility-card:hover .img-container img {
        transform: scale(1.08);
    }

    .category-label {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(220,53,69,.92);
        color: white;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: .75rem;
        font-weight: 600;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        background: #fff;
        border-radius: 50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:-25px auto 15px;
        position:relative;
        z-index:2;
        box-shadow:0 4px 12px rgba(0,0,0,.12);
        font-size:1.4rem;
        border:3px solid #fff;
    }

    .section-header {
        margin-bottom: 30px;
    }

    .section-header h3 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: var(--agu-dark);
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
    }

    .section-header h3::after {
        content:"";
        position:absolute;
        left:0;
        bottom:0;
        width:50px;
        height:4px;
        background:var(--agu-red);
        border-radius:10px;
    }

    .btn-reserve {
        border-radius: 50px;
        font-weight: 600;
        padding: 8px 25px;
        transition: .3s;
    }

    @media(max-width:768px){
        .hero-banner{
            min-height:220px;
        }

        .hero-title{
            font-size:1.8rem;
        }

        .hero-subtitle{
            font-size:.95rem;
        }
    }
</style>

<div class="hero-banner shadow-lg">
    <div class="container text-center text-white animate__animated animate__fadeIn">
        <h1 class="hero-title mb-2">HỆ THỐNG ĐẶT LỊCH CƠ SỞ VẬT CHẤT</h1>
        <p class="hero-subtitle opacity-90">TRƯỜNG ĐẠI HỌC AN GIANG</p>

        <div class="mt-4">
            <a href="#explore" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-danger shadow">
                Đặt lịch ngay
            </a>
        </div>
    </div>
</div>

<div class="container mt-5 pt-5" id="explore">

    {{-- KHU THỂ THAO --}}
    <div class="section-header">
        <h3><i class="fa-solid fa-volleyball me-2 text-danger"></i>Khu Thể Thao</h3>
    </div>

    <div class="row g-4 mb-5">
        @php
            $sports = [
                ['id'=>1,'name'=>'Sân Bóng Đá','icon'=>'⚽','img'=>'BD.jpg','type'=>'Outdoor'],
                ['id'=>3,'name'=>'Sân Bóng Chuyền','icon'=>'🏐','img'=>'BC.jpg','type'=>'Indoor/Outdoor'],
                ['id'=>2,'name'=>'Sân Bóng Rổ','icon'=>'🏀','img'=>'BR.jpg','type'=>'Outdoor'],
                ['id'=>4,'name'=>'Sân Tennis','icon'=>'🎾','img'=>'TN.jpg','type'=>'Pro'],
            ];
        @endphp

        @foreach($sports as $item)
        <div class="col-lg-3 col-md-6">
            <div class="facility-card shadow-sm h-100">
                <div class="img-container">
                    <span class="category-label">{{ $item['type'] }}</span>
                    <img src="{{ asset('images/' . $item['img']) }}" alt="{{ $item['name'] }}">
                </div>
                <div class="icon-circle">{{ $item['icon'] }}</div>
                <div class="card-body text-center pt-0">
                    <h5 class="fw-bold mb-3">{{ $item['name'] }}</h5>
                    <div class="d-grid">
                        <a href="{{ route('category.show', $item['id']) }}" class="btn btn-outline-danger btn-reserve">
                            <i class="fa-regular fa-calendar-check me-2"></i>Đặt lịch
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- HỘI TRƯỜNG --}}
    {{-- HỘI TRƯỜNG --}}
<div class="section-header">
    <h3>
        <i class="fa-solid fa-building-columns me-2 text-danger"></i>
        Hội Trường
    </h3>
</div>

@php
    $halls = [
        ['id'=>5,'name'=>'Hội trường 600','desc'=>'Sức chứa tối đa 600 chỗ ngồi','img'=>'images/HT.jpg'],
        ['id'=>6,'name'=>'Hội trường 300','desc'=>'Sức chứa tối đa 300 chỗ ngồi','img'=>'images/HT300.jpg'],
        ['id'=>7,'name'=>'Hội trường 150','desc'=>'Sức chứa tối đa 150 chỗ ngồi','img'=>'images/HT150.jpg'],
    ];
@endphp

<div class="row g-4 mb-5">
    @foreach($halls as $hall)
    <div class="col-md-4">
        <div class="facility-card h-100 shadow-sm">

            <div class="img-container position-relative">
                <span class="category-label">Hall</span>
                <img 
                    src="{{ asset($hall['img']) }}" 
                    alt="{{ $hall['name'] }}" 
                    class="img-fluid rounded-top"
                >
            </div>

            <div class="icon-circle">🏛️</div>

            <div class="card-body text-center pt-0">
                <h5 class="fw-bold">{{ $hall['name'] }}</h5>
                <p class="small text-muted">{{ $hall['desc'] }}</p>

                <a 
                    href="{{ route('category.show', $hall['id']) }}" 
                    class="btn btn-danger btn-reserve w-100"
                >
                    Đăng ký
                </a>
            </div>

        </div>
    </div>
    @endforeach
</div>

    {{-- PHÒNG HỌC & MÁY --}}
    <div class="section-header">
        <h3><i class="fa-solid fa-laptop-code me-2 text-danger"></i>Phòng Học & Máy Tính</h3>
    </div>

    <div class="row g-4 pb-5">
        <div class="col-md-6">
            <div class="facility-card h-100">
                <div class="img-container">
                    <span class="category-label">Classroom</span>
                    <img src="{{ asset('images/PH.jpg') }}" alt="Phòng học">
                </div>
                <div class="icon-circle">📚</div>
                <div class="card-body text-center pt-0">
                    <h5 class="fw-bold">Phòng học lý thuyết</h5>
                    <p class="small text-muted">Hệ thống phòng học tại dãy A, B, C, D.</p>
                    <a href="{{ route('category.show', 8) }}" class="btn btn-outline-danger btn-reserve">
                        Xem lịch
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="facility-card h-100">
                <div class="img-container">
                    <span class="category-label">Computer Lab</span>
                    <img src="{{ asset('images/PMT.jpg') }}" alt="Phòng máy">
                </div>
                <div class="icon-circle">💻</div>
                <div class="card-body text-center pt-0">
                    <h5 class="fw-bold">Phòng máy tính</h5>
                    <p class="small text-muted">Phòng thực hành tin học chuyên dụng.</p>
                    <a href="{{ route('category.show', 9) }}" class="btn btn-outline-danger btn-reserve">
                        Xem lịch
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection