@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background: #f8fafc;
        font-family: 'Inter', sans-serif;
    }

    /* Header Section */
    .page-header {
        background: linear-gradient(135deg, #48efb8 0%, #0aa378 100%);
        padding: 80px 0 100px; /* Tăng padding bottom cho thanh search nổi */
        border-radius: 0 0 50px 50px;
        color: white;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,.1);
    }

    /* Thanh tìm kiếm chuyên nghiệp (Floating Search Box) */
    .search-container {
        margin-top: -45px; /* Đẩy lên đè lên header */
        position: relative;
        z-index: 10;
        margin-bottom: 50px;
    }

    .search-wrapper {
        background: #ffffff;
        padding: 12px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .search-wrapper .form-control, 
    .search-wrapper .form-select {
        border: 1px solid transparent;
        padding: 12px 15px;
        height: 50px;
        background-color: #f8fafc;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .search-wrapper .form-control:focus, 
    .search-wrapper .form-select:focus {
        background-color: #fff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }

    .input-group-custom {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border-radius: 12px;
        padding-left: 15px;
        transition: 0.3s;
    }

    .btn-search-custom {
        height: 50px;
        background: #10b981;
        color: white;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-search-custom:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(16, 185, 129, 0.2);
    }

    /* Category Title */
    .category-title {
        margin: 40px 0 25px;
        text-align: center;
    }

    .category-title span {
        display: inline-block;
        background: #fff;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 700;
        color: #065f46;
        box-shadow: 0 4px 12px rgba(0,0,0,.05);
        border: 1px solid #e2e8f0;
    }

    /* Card Styling */
    .facility-glass-card {
        transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-radius: 24px;
        overflow: hidden;
        border: none !important;
        background: #fff;
    }

    .facility-glass-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px rgba(0,0,0,.12) !important;
    }

    .facility-image-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .img-zoom {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .6s ease;
    }

    .facility-glass-card:hover .img-zoom {
        transform: scale(1.1);
    }

    .status-overlay {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        padding: 6px 14px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .badge-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .card-body {
        background: #ffffff;
        text-align: center;
        padding: 25px !important;
    }

    .facility-glass-card h4 {
        color: #1e293b;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .price-hour {
        color: #10b981;
        font-size: 22px;
        font-weight: 800;
    }

    /* Pricing Timeline for other categories */
    .pricing-timeline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px;
        border-radius: 16px;
    }

    .time-label {
        font-size: 10px;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .price-tag {
        font-weight: 700;
        font-size: 13px;
    }

    .price-tag.morning { color: #059669; }
    .price-tag.afternoon { color: #d97706; }
    .price-tag.evening { color: #4f46e5; }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #64748b;
        min-height: 40px;
    }

    .btn-action {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-action:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        transform: scale(1.02);
    }

    .btn-admin {
        background: #1e293b;
        color: white;
        border: none;
    }

    @media (max-width: 768px) {
        .page-header { padding: 60px 0 80px; }
        .search-container { margin-top: -30px; padding: 0 15px; }
    }
</style>

<div class="page-header">
    <div class="container">
        <h1 class="fw-bold display-5 mb-3">CƠ SỞ VẬT CHẤT</h1>
        <p class="lead opacity-75">
            Tìm kiếm không gian hoàn hảo cho hoạt động của bạn
        </p>
    </div>
</div>

<div class="container search-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="search-wrapper">
                <form method="GET" action="{{ route('facilities.index') }}">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group-custom">
                                <i class="fas fa-search text-muted"></i>
                                <input type="text" 
                                       name="keyword" 
                                       value="{{ request('keyword') }}"
                                       class="form-control" 
                                       placeholder="Tìm tên sân, phòng học, hội trường...">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group-custom">
                                <i class="fas fa-th-large text-muted"></i>
                                <select name="category" class="form-select">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn-search-custom w-100">
                                <i class="fas fa-filter me-1"></i> LỌC KẾT QUẢ
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    @php $found = false; @endphp

    @foreach($categories as $category)
        @if($category->facilities->count() > 0)
            @php $found = true; @endphp

            <div class="category-title">
                <span>
                    <i class="fas fa-layer-group me-2 text-success"></i>{{ $category->name }}
                </span>
            </div>

            <div class="row g-4">
                @foreach($category->facilities as $facility)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card shadow-sm facility-glass-card h-100">
                        
                        <div class="facility-image-wrapper">
                            <img src="{{ $facility->image ? asset('images/'.$facility->image) : 'https://via.placeholder.com/500x350' }}"
                                 class="img-zoom">

                            <div class="status-overlay">
                                @if(($facility->bookings_count ?? 0) >= 14)
                                    <span class="badge-status-dot bg-danger"></span> Hết chỗ
                                @else
                                    <span class="badge-status-dot bg-success"></span> Sẵn sàng
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <h4 class="text-truncate">{{ $facility->name }}</h4>
                            
                            <p class="small line-clamp-2 mb-4">
                                {{ $facility->description ?? 'Chưa có mô tả cho cơ sở này.' }}
                            </p>

                            @if($facility->category->type == 'sport')
                                <div class="mb-4">
                                    <div class="time-label">Đơn giá</div>
                                    <div class="price-hour">
                                        {{ number_format($facility->category->price_hour) }}đ <small class="text-muted" style="font-size: 12px;">/ giờ</small>
                                    </div>
                                </div>
                            @else
                                <div class="pricing-timeline mb-4">
                                    <div class="text-center">
                                        <div class="time-label">Sáng</div>
                                        <div class="price-tag morning">{{ number_format($facility->category->price_morning / 1000) }}k</div>
                                    </div>
                                    <div style="width: 1px; height: 25px; background: #e2e8f0;"></div>
                                    <div class="text-center">
                                        <div class="time-label">Chiều</div>
                                        <div class="price-tag afternoon">{{ number_format($facility->category->price_afternoon / 1000) }}k</div>
                                    </div>
                                    <div style="width: 1px; height: 25px; background: #e2e8f0;"></div>
                                    <div class="text-center">
                                        <div class="time-label">Tối</div>
                                        <div class="price-tag evening">{{ number_format($facility->category->price_evening / 1000) }}k</div>
                                    </div>
                                </div>
                            @endif

                            <a href="{{ route('booking.create', $facility) }}"
                               class="btn {{ auth()->check() && auth()->user()->vai_tro == 'admin' ? 'btn-admin' : 'btn-action' }} w-100 rounded-3 py-2 fw-bold">
                                {{ auth()->check() && auth()->user()->vai_tro == 'admin' ? 'QUẢN LÝ CƠ SỞ' : 'ĐẶT LỊCH NGAY' }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    @endforeach

    @if(!$found)
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" width="100" class="opacity-25 mb-3">
            <h5 class="text-muted">Rất tiếc, không tìm thấy kết quả phù hợp!</h5>
            <a href="{{ route('facilities.index') }}" class="btn btn-link text-success text-decoration-none">Hiển thị tất cả</a>
        </div>
    @endif
</div>
@endsection