@extends('layouts.admin')

@section('admin_content')

<div class="container">

    <h3 class="mb-4">
        Đơn đặt - {{ $facility->name }}
    </h3>

    <a href="{{ route('admin.facilities') }}" class="btn btn-secondary mb-3">
        ← Quay lại
    </a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Họ tên</th>
                    <th>SĐT</th>
                    <th>Ngày</th>
                    <th>Ca</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Thời gian đặt</th>
                    <th>Hành động</th>
                    <th>Thanh toán</th>
                </tr>
            </thead>

            <tbody>

            @forelse($bookings as $booking)

            <tr>

                <td>{{ $booking->id }}</td>

                {{-- USER --}}
                <td>
                    @if($booking->user)
                        {{ $booking->user->ho_ten }}

                        @if(strtolower($booking->user->vai_tro) == 'admin')
                            <span class="badge bg-danger">Admin</span>
                        @else
                            <span class="badge bg-success">User</span>
                        @endif
                    @else
                        Khách
                        <span class="badge bg-secondary">Guest</span>
                    @endif
                </td>

                <td>{{ $booking->fullname }}</td>
                <td>{{ $booking->phone }}</td>

                {{-- NGÀY --}}
                <td>
                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                </td>

                {{-- CA --}}
                <td>
                    @switch($booking->session)
                        @case('morning')
                            Sáng (7h - 11h)
                            @break
                        @case('afternoon')
                            Chiều (13h - 17h)
                            @break
                        @case('evening')
                            Tối (17h - 21h)
                            @break
                        @default
                            Không xác định
                    @endswitch
                </td>

                {{-- GIÁ --}}
                <td class="fw-bold text-success">
                    {{ number_format($booking->price) }} VNĐ
                </td>

                {{-- TRẠNG THÁI --}}
                <td>
                    @switch($booking->status)
                        @case('locked')
                            <span class="badge bg-dark">Đã khóa</span>
                            @break
                        @case('pending')
                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                            @break
                        @case('approved')
                            <span class="badge bg-success">Đã duyệt</span>
                            @break
                        @case('rejected')
                            <span class="badge bg-danger">Từ chối</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-secondary">Đã hủy</span>
                            @break
                        @default
                            <span class="badge bg-light text-dark">Không rõ</span>
                    @endswitch
                </td>

                {{-- THỜI GIAN --}}
                <td>
                    {{ $booking->created_at?->format('H:i d/m/Y') }}
                </td>

                {{-- HÀNH ĐỘNG --}}
                <td>

                    @php
                        $isAdmin = $booking->user && strtolower($booking->user->vai_tro) == 'admin';
                    @endphp

                    {{-- cảnh báo chưa thanh toán --}}
                    @if(!$booking->is_paid && $booking->payment_method == 'Chuyển khoản')
                        <div class="mb-1">
                            <span class="badge bg-danger">⚠️ Chưa thanh toán</span>
                        </div>
                    @endif

                    {{-- DUYỆT --}}
                    @if($booking->status == 'pending' && !$isAdmin)

                        <a href="{{ route('admin.booking.approve',$booking->id) }}"
                           class="btn btn-success btn-sm">
                            ✔️ Duyệt
                        </a>

                        <a href="{{ route('admin.booking.reject',$booking->id) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc muốn từ chối?')">
                            ❌ Từ chối
                        </a>

                    {{-- MỞ KHÓA --}}
                    @elseif($booking->status == 'locked')

                        <form action="{{ route('admin.booking.unlock') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="facility_id" value="{{ $booking->facility_id }}">
                            <input type="hidden" name="date" value="{{ $booking->booking_date }}">
                            <input type="hidden" name="session" value="{{ $booking->session }}">

                            <button class="btn btn-dark btn-sm"
                                onclick="return confirm('Mở khóa ca này?')">
                                🔓 Mở khóa
                            </button>
                        </form>

                    {{-- HÓA ĐƠN --}}
                    @elseif($booking->status == 'approved')

                        @if($booking->group_id)

                            @php
                                $firstBooking = \App\Models\Booking::where('group_id', $booking->group_id)
                                                    ->orderBy('id')
                                                    ->first();
                            @endphp

                            @if($firstBooking && $firstBooking->id == $booking->id)
                                <a href="{{ route('admin.invoice.group', $booking->group_id) }}"
                                   class="btn btn-primary btn-sm">
                                    🧾 Xuất hóa đơn
                                </a>
                            @else
                                <span class="text-muted">Đã gộp</span>
                            @endif

                        @else
                            <a href="{{ route('admin.invoice.create', $booking->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                🧾 Xuất lẻ
                            </a>
                        @endif

                    {{-- ADMIN --}}
                    @elseif($isAdmin)
                        <span class="text-primary fw-bold">Admin tạo</span>

                    @else
                        <span class="text-muted">Đã xử lý</span>
                    @endif

                </td>

                {{-- THANH TOÁN --}}
                <td>

                    <form action="{{ route('admin.booking.togglePayment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $booking->id }}">

                        @if($booking->payment_method == 'Tiền mặt')

                            <button class="btn btn-sm {{ $booking->is_paid ? 'btn-success' : 'btn-warning' }}">
                                {{ $booking->is_paid ? '💵 Đã thu' : '💵 Chưa thu' }}
                            </button>

                        @elseif($booking->payment_method == 'Chuyển khoản')

                            <button class="btn btn-sm {{ $booking->is_paid ? 'btn-primary' : 'btn-danger' }}">
                                {{ $booking->is_paid ? '💳 Đã CK' : '⏳ Chờ CK' }}
                            </button>

                        @elseif($booking->payment_method == 'admin_lock')

                            <span class="badge bg-dark">🔒 Admin khóa</span>

                        @else
                            <span class="text-muted">Không rõ</span>
                        @endif

                    </form>

                </td>

            </tr>

            @empty
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">
                        Không có dữ liệu
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection