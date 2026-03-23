@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active text-danger fw-bold">{{ $category->name }}</li>
            </ol>
        </nav>
        <h2 class="fw-bolder display-5 text-dark">{{ $category->name }}</h2>
        <p class="text-muted">Khám phá và chọn không gian phù hợp với nhu cầu của bạn.</p>
    </div>

    <div class="row g-4">
        @foreach($category->facilities as $facility)
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden facility-glass-card">
                
                <div class="facility-image-wrapper">
                    <img src="{{ $facility->image ? asset('images/'.$facility->image) : 'https://via.placeholder.com/500x350' }}" 
                         class="card-img-top img-zoom" alt="{{ $facility->name }}">
                    
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

                <div class="card-body p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold text-dark mb-0">{{ $facility->name }}</h4>
                        <div class="facility-icon-circle bg-danger-soft">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                        </div>
                    </div>
                    
                    <p class="text-secondary small mb-4 line-clamp-2">
                        {{ $facility->description }}
                    </p>

                    <div class="pricing-timeline mb-4">
                        <div class="timeline-item">
                            <div class="time-label">Sáng</div>
                            <div class="price-tag morning">{{ number_format($facility->category->price_morning) }}đ</div>
                        </div>
                        <div class="timeline-divider"></div>
                        <div class="timeline-item">
                            <div class="time-label">Chiều</div>
                            <div class="price-tag afternoon">{{ number_format($facility->category->price_afternoon) }}đ</div>
                        </div>
                        <div class="timeline-divider"></div>
                        <div class="timeline-item">
                            <div class="time-label">Tối</div>
                            <div class="price-tag evening">{{ number_format($facility->category->price_evening) }}đ</div>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->user()->vai_tro == 'admin')
    <a href="{{ route('booking.create', $facility) }}"
       class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-center">
        Quản lý 
    </a>
@else
    <a href="{{ route('booking.create', $facility) }}" 
       class="btn btn-dark w-100 rounded-3 py-2 fw-bold d-flex justify-content-between align-items-center px-4 btn-action">
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

<style>
    /* 1. Facility Card - Glassmorphism Effect */
    .facility-glass-card {
        transition: all 0.4s ease;
    }
    .facility-glass-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
    }

    /* 2. Image Wrapper & Zoom */
    .facility-image-wrapper {
        position: relative;
        height: 240px;
        overflow: hidden;
    }
    .img-zoom {
        transition: transform 0.6s ease;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .facility-glass-card:hover .img-zoom {
        transform: scale(1.1);
    }

    /* 3. Status Overlay */
    .status-overlay {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        padding: 6px 15px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .badge-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .animate-pulse {
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    /* 4. Pricing Timeline */
    .pricing-timeline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8f9fa;
        padding: 12px;
        border-radius: 12px;
    }
    .timeline-item { text-align: center; flex: 1; }
    .time-label { font-size: 0.7rem; color: #adb5bd; text-transform: uppercase; font-weight: bold; margin-bottom: 2px; }
    .price-tag { font-size: 0.95rem; font-weight: 800; }
    .price-tag.morning { color: #007bff; }
    .price-tag.afternoon { color: #fd7e14; }
    .price-tag.evening { color: #dc3545; }
    .timeline-divider { width: 1px; height: 25px; background: #dee2e6; margin: 0 5px; }

    /* 5. Utility */
    .bg-danger-soft { background: #fff5f5; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .btn-action { transition: background 0.3s; }
    .btn-action:hover { background: #ff4d4d !important; border-color: #ff4d4d; }
</style>
@endsection