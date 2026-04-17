@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body{
        background:#f8fafc;
        font-family:'Inter',sans-serif;
    }

    .page-header{
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        padding: 75px 0;
        border-radius: 0 0 50px 50px;
        color: white;
        margin-bottom: 50px;
        box-shadow: 0 10px 25px rgba(0,0,0,.12);
    }

    .category-title{
        margin:40px 0 25px;
        text-align:center;
    }

    .category-title span{
        display:inline-block;
        background:#fff;
        padding:12px 24px;
        border-radius:50px;
        font-weight:700;
        color:#065f46;
        box-shadow:0 6px 18px rgba(0,0,0,.08);
    }

    .facility-glass-card{
        transition: all .4s ease;
        border-radius:24px;
    }

    .facility-glass-card:hover{
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,.12) !important;
    }

    .facility-image-wrapper{
        position: relative;
        height: 240px;
        overflow: hidden;
    }

    .img-zoom{
        width:100%;
        height:100%;
        object-fit:cover;
        transition: transform .6s ease;
    }

    .facility-glass-card:hover .img-zoom{
        transform: scale(1.08);
    }

    .status-overlay{
        position:absolute;
        bottom:15px;
        left:15px;
        background:rgba(0,0,0,.55);
        backdrop-filter: blur(8px);
        padding:8px 15px;
        border-radius:50px;
        display:flex;
        align-items:center;
        color:white;
        font-size:13px;
    }

    .badge-status-dot{
        width:8px;
        height:8px;
        border-radius:50%;
        margin-right:8px;
    }

    .card-body{
        background: linear-gradient(135deg,#f0fff4,#ecfdf5);
        text-align:center;
    }

    .facility-glass-card h4{
        color:#065f46;
        font-weight:700;
    }

    .price-hour{
        color:#16a34a;
        font-size:24px;
        font-weight:bold;
    }

    .pricing-timeline{
        display:flex;
        align-items:center;
        justify-content:space-between;
        background:#ecfdf5;
        border:1px solid #d1fae5;
        padding:12px;
        border-radius:14px;
    }

    .timeline-item{
        flex:1;
        text-align:center;
    }

    .time-label{
        font-size:.72rem;
        color:#6b7280;
        font-weight:bold;
    }

    .timeline-divider{
        width:1px;
        height:25px;
        background:#d1d5db;
    }

    .price-tag{
        padding:6px 8px;
        border-radius:10px;
        font-weight:bold;
        font-size:13px;
    }

    .price-tag.morning{
        background:#bbf7d0;
        color:#065f46;
    }

    .price-tag.afternoon{
        background:#86efac;
        color:#064e3b;
    }

    .price-tag.evening{
        background:#16a34a;
        color:#fff;
    }

    .facility-icon-circle{
        background:linear-gradient(135deg,#bbf7d0,#4ade80);
        width:40px;
        height:40px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:10px;
    }

    .line-clamp-2{
        display:-webkit-box;
        -webkit-line-clamp:2;
        -webkit-box-orient:vertical;
        overflow:hidden;
        text-align:center;
    }

    .btn-action{
        background: linear-gradient(45deg,#22c55e,#16a34a);
        color:white;
        border:none;
    }

    .btn-action:hover{
        background: linear-gradient(45deg,#16a34a,#15803d);
        color:white;
    }

    .btn-admin{
        background:#111827;
        color:white;
        border:none;
    }
</style>

<div class="page-header text-center">
    <div class="container">
        <h1 class="fw-bold display-4 mb-3">CƠ SỞ VẬT CHẤT</h1>
        <p class="lead opacity-75">
            Lựa chọn sân thể thao, hội trường, phòng học và phòng máy phù hợp với nhu cầu của bạn
        </p>
    </div>
</div>

<div class="container pb-5">
    @foreach($categories as $category)

        <div class="category-title">
            <span>
                <i class="fas fa-layer-group me-2"></i>{{ $category->name }}
            </span>
        </div>

        <div class="row g-4">
            @foreach($category->facilities as $facility)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm facility-glass-card h-100">

                    <div class="facility-image-wrapper">
                        <img src="{{ $facility->image ? asset('images/'.$facility->image) : 'https://via.placeholder.com/500x350' }}"
                             class="img-zoom">

                        <div class="status-overlay">
                            @if(($facility->bookings_count ?? 0) >= 14)
                                <span class="badge-status-dot bg-danger"></span>
                                Đã kín lịch
                            @else
                                <span class="badge-status-dot bg-success"></span>
                                Còn chỗ trống
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex flex-column align-items-center mb-3">
                            <div class="facility-icon-circle mb-2">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                            </div>

                            <h4 class="mb-0">{{ $facility->name }}</h4>
                        </div>

                        <p class="small mb-4 line-clamp-2">
                            {{ $facility->description }}
                        </p>

                        @if($facility->category->type == 'sport')
                            <div class="text-center mb-4">
                                <div class="small text-muted fw-bold mb-1">Giá theo giờ</div>
                                <div class="price-hour">
                                    {{ number_format($facility->category->price_hour) }}đ / giờ
                                </div>
                            </div>
                        @else
                            <div class="pricing-timeline mb-4">
                                <div class="timeline-item">
                                    <div class="time-label">SÁNG</div>
                                    <div class="price-tag morning">
                                        {{ number_format($facility->category->price_morning) }}đ
                                    </div>
                                </div>

                                <div class="timeline-divider"></div>

                                <div class="timeline-item">
                                    <div class="time-label">CHIỀU</div>
                                    <div class="price-tag afternoon">
                                        {{ number_format($facility->category->price_afternoon) }}đ
                                    </div>
                                </div>

                                <div class="timeline-divider"></div>

                                <div class="timeline-item">
                                    <div class="time-label">TỐI</div>
                                    <div class="price-tag evening">
                                        {{ number_format($facility->category->price_evening) }}đ
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->check() && auth()->user()->vai_tro == 'admin')
                            <a href="{{ route('booking.create', $facility) }}"
                               class="btn btn-admin w-100 rounded-3 py-2 fw-bold">
                                Quản lý
                            </a>
                        @else
                            <a href="{{ route('booking.create', $facility) }}"
                               class="btn btn-action w-100 rounded-3 py-2 fw-bold d-flex justify-content-center align-items-center gap-2">
                                <span>Đặt lịch ngay</span>
                                <i class="fas fa-chevron-right small"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach
        </div>

    @endforeach
</div>

@endsection