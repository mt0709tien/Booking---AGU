@extends('layouts.app')

@section('content')

{{-- HEADER FULL WIDTH --}}
<div class="page-header text-center">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-3">
                <li class="breadcrumb-item">
                    <a href="/" class="text-decoration-none text-white-50">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active text-white fw-bold">
                    {{ $category->name }}
                </li>
            </ol>
        </nav>

        <h1 class="fw-extrabold display-4 mb-3">
            {{ strtoupper($category->name) }}
        </h1>

        <p class="lead opacity-75">
            Khám phá và lựa chọn không gian phù hợp với nhu cầu của bạn
        </p>
    </div>
</div>

<div class="container pb-5 d-flex justify-content-center" style="position: relative; z-index:2;">
    <div style="max-width:1100px; width:100%;">

        
        {{-- LIST --}}
        <div class="row g-4 justify-content-center">
            @foreach($category->facilities as $facility)
            <div class="col-xl-4 col-lg-6 col-md-6 d-flex justify-content-center">
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden facility-glass-card w-100" style="max-width: 350px;">

                    {{-- IMAGE --}}
                    <div class="facility-image-wrapper">
                        <img src="{{ $facility->image ? asset('images/'.$facility->image) : 'https://via.placeholder.com/500x350' }}"
                             class="card-img-top img-zoom">

                        <div class="status-overlay">
                            @if(($facility->bookings_count ?? 0) >= 14)
                                <span class="badge-status-dot bg-danger"></span>
                                <span class="text-white fw-bold small">Đã kín lịch</span>
                            @else
                                <span class="badge-status-dot bg-success animate-pulse"></span>
                                <span class="text-white fw-bold small">Còn chỗ trống</span>
                            @endif
                        </div>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body p-4 text-center">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="fw-bold mb-0">
                                {{ $facility->name }}
                            </h4>

                            <div class="facility-icon-circle">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                            </div>
                        </div>

                        <p class="small mb-4 line-clamp-2">
                            {{ $facility->description }}
                        </p>

                        {{-- PRICE --}}
                        @if($facility->category->type == 'sport')
                            <div class="mb-4 text-center">
                                <div class="fw-bold text-muted small mb-1">
                                    Giá theo giờ
                                </div>

                                <div class="price-hour">
                                    {{ number_format($facility->category->price_hour) }}đ / giờ
                                </div>
                            </div>
                        @else
                            <div class="pricing-timeline mb-4">
                                <div class="timeline-item">
                                    <div class="time-label">Sáng</div>
                                    <div class="price-tag morning">
                                        {{ number_format($facility->category->price_morning) }}đ
                                    </div>
                                </div>

                                <div class="timeline-divider"></div>

                                <div class="timeline-item">
                                    <div class="time-label">Chiều</div>
                                    <div class="price-tag afternoon">
                                        {{ number_format($facility->category->price_afternoon) }}đ
                                    </div>
                                </div>

                                <div class="timeline-divider"></div>

                                <div class="timeline-item">
                                    <div class="time-label">Tối</div>
                                    <div class="price-tag evening">
                                        {{ number_format($facility->category->price_evening) }}đ
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- BUTTON --}}
                        @if(auth()->check() && auth()->user()->vai_tro == 'admin')
                            <a href="{{ route('booking.create', $facility) }}"
                               class="btn btn-danger w-100 rounded-3 py-2 fw-bold">
                                Quản lý
                            </a>
                        @else
                            <a href="{{ route('booking.create', $facility) }}"
                               class="btn btn-action w-100 rounded-3 py-2 fw-bold d-flex justify-content-between align-items-center px-4">
                                <span>Bắt đầu đặt lịch</span>
                                <i class="fas fa-chevron-right small"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #b3a3ac 0%, #fc09b3 100%);
    padding: 70px 0;
    border-radius: 0 0 50px 50px;
    color: white;
    margin-bottom: 50px;
    box-shadow: 0 10px 25px rgba(183, 28, 28, 0.15);
}

.page-header .breadcrumb {
    background: transparent;
}

.page-header .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.6);
}

.page-header a:hover {
    color: white !important;
}

.facility-glass-card {
    transition: all .4s ease;
}

.facility-glass-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 40px rgba(0,0,0,.12) !important;
}

.facility-image-wrapper {
    position: relative;
    height: 240px;
    overflow: hidden;
}

.img-zoom {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .6s ease;
}

.facility-glass-card:hover .img-zoom {
    transform: scale(1.08);
}

.status-overlay {
    position: absolute;
    bottom: 15px;
    left: 15px;
    background: rgba(0,0,0,.5);
    backdrop-filter: blur(8px);
    padding: 6px 15px;
    border-radius: 50px;
    display: flex;
    align-items: center;
}

.badge-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 8px;
}

.animate-pulse {
    animation: pulse-purple 2s infinite;
}

/* Hiệu ứng pulse đổi sang màu tím hồng */
@keyframes pulse-purple {
    0% { box-shadow: 0 0 0 0 rgba(252, 9, 179, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(252, 9, 179, 0); }
    100% { box-shadow: 0 0 0 0 rgba(252, 9, 179, 0); }
}

/* Nền card màu tím siêu nhạt */
.card-body {
    background: linear-gradient(135deg, #f8f7ff, #f3e8ff);
}

/* Màu chữ tiêu đề tím đậm */
.facility-glass-card h4 {
    color: #5b21b6;
}

.facility-glass-card p {
    color: #4b5563;
}

/* Giá tiền tông tím */
.price-hour {
    color: #9333ea;
    font-size: 24px;
    font-weight: bold;
}

/* Timeline khung giá */
.pricing-timeline {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #faf5ff; /* Tím cực nhạt */
    border: 1px solid #e9d5ff;
    padding: 12px;
    border-radius: 12px;
}

.timeline-item {
    flex: 1;
    text-align: center;
}

.time-label {
    font-size: .7rem;
    color: #6b7280;
    font-weight: bold;
}

.timeline-divider {
    width: 1px;
    height: 25px;
    background: #e5e7eb;
}

/* Màu các tag giá theo tông tím gradient */
.price-tag {
    padding: 6px 8px;
    border-radius: 10px;
    font-weight: bold;
}

.price-tag.morning {
    background: #f3e8ff;
    color: #7e22ce;
}

.price-tag.afternoon {
    background: #e9d5ff;
    color: #6b21ae;
}

.price-tag.evening {
    background: #a855f7;
    color: #fff;
}

/* Icon location nền tím nhạt */
.facility-icon-circle {
    background: linear-gradient(135deg, #f3e8ff, #d8b4fe);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Nút bấm chuyển sang màu tím hồng đồng nhất với Header */
.btn-action {
    background: linear-gradient(45deg, #d946ef, #a855f7);
    color: white;
    border: none;
    transition: opacity 0.3s;
}

.btn-action:hover {
    background: linear-gradient(45deg, #c026d3, #9333ea);
    color: white;
    opacity: 0.9;
}
</style>

@endsection