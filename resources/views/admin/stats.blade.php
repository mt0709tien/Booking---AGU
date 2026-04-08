@extends('layouts.admin')

@section('admin_content')

<div class="container-fluid">

    <h3 class="mb-4 fw-bold">📊 Thống kê hệ thống</h3>

    <!-- TỔNG QUAN -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold text-primary">{{ number_format($totalUsers) }}</h4>
                    <p class="mb-0">Tổng người dùng</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold text-success">{{ number_format($totalBookings) }}</h4>
                    <p class="mb-0">Tổng đặt lịch</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold text-danger">{{ number_format($totalFacilities) }}</h4>
                    <p class="mb-0">Tổng cơ sở vật chất</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER NHANH -->
    <div class="mb-3">
        <a href="?filter=today" class="btn btn-sm btn-primary">Hôm nay</a>
        <a href="?filter=7days" class="btn btn-sm btn-secondary">7 ngày</a>
        <a href="?filter=month" class="btn btn-sm btn-success">Tháng</a>
        <a href="?filter=year" class="btn btn-sm btn-warning">Năm</a>
    </div>

    <!-- FILTER NGÀY -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>

        <div class="col-md-3">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>

        <div class="col-md-4">
            <button type="submit" class="btn btn-danger">Lọc</button>
            <a href="{{ route('admin.stats') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- CHART -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">
                Doanh thu (đã thanh toán)
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
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

</div>

<!-- CHART JS -->
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
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context){
                            return context.raw.toLocaleString('vi-VN') + ' ₫';
                        }
                    }
                }
            },
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