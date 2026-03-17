@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center fw-bold text-primary mb-4">
        Lịch đã đặt của tôi
    </h3>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered text-center align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Sân</th>
                        <th>Ngày</th>
                        <th>Buổi</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($bookings as $booking)

                        <tr>

                            <td>{{ $booking->facility->name }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}
                            </td>

                            <td>
                                @if($booking->session == 'morning') Sáng
                                @elseif($booking->session == 'afternoon') Chiều
                                @else Tối
                                @endif
                            </td>

                            <td>
                                {{ number_format($booking->price) }} VNĐ
                            </td>

                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                @elseif($booking->status == 'approved')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @else
                                    <span class="badge bg-danger">Bị từ chối</span>
                                @endif
                            </td>

                            <td>

                                @if($booking->status == 'pending')

                                    <form 
                                        action="{{ route('booking.cancel', $booking->id) }}" 
                                        method="POST"
                                    >
                                        @csrf
                                        <button 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn chắc chắn muốn hủy?')"
                                        >
                                            Hủy
                                        </button>
                                    </form>

                                @else

                                    <span class="text-muted">---</span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-muted">
                                Bạn chưa có lịch đặt nào
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection