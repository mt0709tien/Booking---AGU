@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Thống kê hệ thống</h3>

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

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Doanh thu 7 ngày gần nhất</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
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
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
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