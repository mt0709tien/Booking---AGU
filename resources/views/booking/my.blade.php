@extends('layouts.app')

@section('content')

{{-- Tùy chỉnh CSS nhẹ nhàng --}}
<style>
    .booking-table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #495057;
        border-top: none;
    }
    .booking-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    .badge-soft {
        padding: 0.5em 0.8em;
        border-radius: 6px;
        font-weight: 600;
    }
    .facility-name {
        font-weight: 600;
        color: #2d3748;
    }
    .booking-date {
        font-size: 0.9rem;
        color: #4a5568;
    }
</style>

<div class="container py-5">

    <div class="row mb-4">
        <div class="col-12 text-center">
            <h3 class="fw-bold text-primary">
                <i class="fas fa-calendar-alt me-2"></i>Lịch đã đặt của tôi
            </h3>
            <p class="text-muted small">Theo dõi và quản lý các yêu cầu đặt chỗ của bạn</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm text-center mb-4">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm text-center mb-4">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table booking-table table-hover mb-0 text-center">
                    <thead>
                        <tr>
                            <th>Cơ sở</th>
                            <th>Ngày đặt</th>
                            <th>Khung giờ</th>
                            <th>Tổng giá</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            @php
                                $firstRoom = $booking->roomBookings->first();
                                $firstSport = $booking->sportBookings->first();
                            @endphp
                            <tr>
                                {{-- CƠ SỞ --}}
                                <td>
                                    <div class="facility-name">
                                        {{ optional(optional($firstRoom)->facility)->name 
                                           ?? optional(optional($firstSport)->facility)->name 
                                           ?? 'Không xác định' }}
                                    </div>
                                </td>

                                {{-- NGÀY --}}
                                <td>
                                    @foreach($booking->roomBookings as $room)
                                        <div class="booking-date"><i class="far fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($room->booking_date)->format('d/m/Y') }}</div>
                                    @endforeach

                                    @foreach($booking->sportBookings as $sport)
                                        <div class="booking-date"><i class="far fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($sport->booking_date)->format('d/m/Y') }}</div>
                                    @endforeach
                                </td>

                                {{-- THỜI GIAN --}}
                                <td>
                                    {{-- ROOM --}}
                                    @foreach($booking->roomBookings as $room)
                                        <div class="mb-1">
                                            @if($room->session == 'morning')
                                                <span class="badge bg-info bg-opacity-10 text-info badge-soft">Sáng (7h-11h)</span>
                                            @elseif($room->session == 'afternoon')
                                                <span class="badge bg-primary bg-opacity-10 text-primary badge-soft">Chiều (13h-17h)</span>
                                            @elseif($room->session == 'evening')
                                                <span class="badge bg-dark bg-opacity-10 text-dark badge-soft">Tối (17h-21h)</span>
                                            @endif
                                        </div>
                                    @endforeach

                                    {{-- SPORT --}}
                                    @foreach($booking->sportBookings as $sport)
                                        <div class="mb-1">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary badge-soft">
                                                <i class="far fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($sport->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sport->end_time)->format('H:i') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </td>

                                {{-- GIÁ --}}
                                <td class="text-success fw-bold">
                                    {{ number_format($booking->price ?? 0) }}đ
                                </td>

                                {{-- TRẠNG THÁI --}}
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark badge-soft">Chờ duyệt</span>
                                    @elseif($booking->status == 'approved')
                                        <span class="badge bg-success badge-soft">Đã duyệt</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="badge bg-secondary badge-soft">Đã hủy</span>
                                    @elseif($booking->status == 'rejected')
                                        <span class="badge bg-danger badge-soft">Từ chối</span>
                                    @elseif($booking->status == 'locked')
                                        <span class="badge bg-dark badge-soft">Đã khóa</span>
                                    @elseif($booking->status == 'cancel_requested')
                                         <span class="badge bg-warning text-dark badge-soft">Yêu cầu hủy</span>
                                    @else
                                        <span class="badge bg-light text-dark badge-soft">Khác</span>
                                    @endif
                                </td>

                                {{-- THANH TOÁN --}}
                                <td>
                                    @if($booking->is_paid)
                                        <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i>Đã thanh toán</span>
                                    @else
                                        <span class="text-danger small fw-bold"><i class="fas fa-times-circle me-1"></i>Chưa thanh toán</span>
                                    @endif
                                </td>

                                {{-- HÀNH ĐỘNG --}}
                                <td>
    <div class="d-flex flex-column align-items-center gap-1">

        {{-- nút thanh toán --}}
        @if(!$booking->is_paid 
            && $booking->payment_method == 'transfer'
            && $booking->status != 'cancelled')
            <a href="{{ route('booking.payment', $booking->id) }}"
               class="btn btn-sm btn-outline-success w-100">
                <i class="fas fa-credit-card me-1"></i> Thanh toán
            </a>
        @endif

        {{-- pending → hủy luôn --}}
@if($booking->status == 'pending')
    <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="w-100">
        @csrf
        <button class="btn btn-sm btn-outline-danger w-100"
                onclick="return confirm('Bạn chắc chắn muốn hủy yêu cầu này?')">
            <i class="fas fa-trash-alt me-1"></i> Hủy
        </button>
    </form>

{{-- approved + chưa nhận sân → yêu cầu hủy --}}
@elseif($booking->status == 'approved' && !$booking->is_checked_in)
    <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="w-100">
        @csrf
        <button class="btn btn-sm btn-outline-warning w-100"
                onclick="return confirm('Gửi yêu cầu hủy đến admin?')">
            <i class="fas fa-hand-paper me-1"></i> Yêu cầu hủy
        </button>
    </form>

{{-- approved + đã nhận sân → đánh giá --}}
@elseif($booking->status == 'approved' && $booking->is_checked_in)
    <a href="{{ route('booking.review', $booking->id) }}"
       class="btn btn-sm btn-outline-primary w-100">
        <i class="fas fa-star me-1"></i> Đánh giá
    </a>

{{-- đang chờ admin duyệt hủy --}}
@elseif($booking->status == 'cancel_requested')
    <span class="badge bg-warning text-dark">
        <i class="fas fa-hourglass-half me-1"></i> Chờ admin duyệt hủy
    </span>

@else
    <span class="text-muted small">N/A</span>
@endif

    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-muted">
                                    <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                    Bạn chưa có lịch đặt nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection