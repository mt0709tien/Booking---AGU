@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Thống kê hệ thống</h3>

    <!-- Tổng quan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h4>{{ number_format($totalUsers) }}</h4>
                    <p>Tổng người dùng</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h4>{{ number_format($totalBookings) }}</h4>
                    <p>Tổng đặt lịch</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h4>{{ number_format($totalFacilities) }}</h4>
                    <p>Tổng cơ sở vật chất</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="mb-3">
        <a href="?filter=today" class="btn btn-sm btn-primary">Hôm nay</a>
        <a href="?filter=7days" class="btn btn-sm btn-secondary">7 ngày</a>
        <a href="?filter=month" class="btn btn-sm btn-success">Tháng</a>
        <a href="?filter=year" class="btn btn-sm btn-warning">Năm</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
    </div>
    <div class="col-md-3">
        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-danger">Lọc</button>
        <a href="{{ route('admin.stats') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

    <!-- Chart -->
    <div class="card shadow">
        <div class="card-header bg-white">
            <h5 class="mb-0">
    Doanh thu 
    @if(request('from') && request('to'))
        từ {{ request('from') }} đến {{ request('to') }}
    @elseif($filter == 'today') 
        hôm nay
    @elseif($filter == 'month') 
        tháng này
    @elseif($filter == 'year') 
        năm nay
    @else 
        7 ngày gần nhất
    @endif
</h5>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dayLabels) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueData) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78,115,223,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' ₫';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection