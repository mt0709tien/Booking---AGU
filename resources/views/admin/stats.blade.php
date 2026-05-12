@extends('layouts.admin')

@section('admin_content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-800 text-dark mb-0">📊 Thống kê hệ thống</h3>
        <span class="text-muted small">Cập nhật lần cuối: {{ now()->format('H:i d/m/Y') }}</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-primary-soft text-primary me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($totalUsers) }}</h4>
                        <p class="text-muted small mb-0">Người dùng</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-success-soft text-success me-3">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($totalBookings) }}</h4>
                        <p class="text-muted small mb-0">Đặt lịch</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-danger-soft text-danger me-3">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($totalFacilities) }}</h4>
                        <p class="text-muted small mb-0">Cơ sở</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient-warning">
                <div class="card-body d-flex align-items-center text-white">
                    <div class="icon-box bg-white-20 text-white me-3">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ number_format($totalRevenue) }} ₫</h4>
                        <p class="small mb-0">Tổng doanh thu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('admin.stats', ['filter' => 'today']) }}" class="btn btn-white {{ request('filter') == 'today' ? 'active' : '' }}">Hôm nay</a>
                        <a href="{{ route('admin.stats', ['filter' => '7days']) }}" class="btn btn-white {{ request('filter') == '7days' ? 'active' : '' }}">7 ngày</a>
                        <a href="{{ route('admin.stats', ['filter' => 'month']) }}" class="btn btn-white {{ request('filter') == 'month' ? 'active' : '' }}">Tháng</a>
                        <a href="{{ route('admin.stats', ['filter' => 'year']) }}" class="btn btn-white {{ request('filter') == 'year' ? 'active' : '' }}">Năm</a>
                    </div>

                    <form method="GET" class="d-flex gap-2 align-items-center">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="far fa-calendar-alt"></i></span>
                            <input type="date" name="from" class="form-control border-start-0 shadow-none" value="{{ request('from') }}">
                        </div>
                        <i class="fas fa-long-arrow-alt-right text-muted"></i>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="far fa-calendar-alt"></i></span>
                            <input type="date" name="to" class="form-control border-start-0 shadow-none" value="{{ request('to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm px-3 rounded-3">Lọc</button>
                        <a href="{{ route('admin.stats') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-3">Reset</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between">
                    <h5 class="fw-bold mb-0 text-dark">Biểu đồ doanh thu</h5>
                    <div class="badge bg-primary-soft text-primary px-3 rounded-pill">Dữ liệu thời gian thực</div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="min-height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">🏆 Top doanh thu</h5>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @forelse($topFacilities as $index => $f)
                                    <tr>
                                        <td class="ps-4 border-0">
                                            <div class="rank-circle {{ $index < 3 ? 'bg-warning-soft text-warning' : 'bg-light text-muted' }}">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="border-0">
                                            <div class="fw-bold text-dark">{{ Str::limit($f->name, 20) }}</div>
                                            @if($f->type == 'sport')
                                                <span class="smaller-badge bg-primary">Sân bóng</span>
                                            @else
                                                <span class="smaller-badge bg-success">Phòng hội nghị</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 border-0 text-end">
                                            <span class="fw-bold text-danger">{{ number_format($f->total_revenue ?? 0) }}đ</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted small">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Font & Colors */
:root {
    --primary-soft: #eef2ff;
    --success-soft: #ecfdf5;
    --danger-soft: #fef2f2;
    --warning-soft: #fffbeb;
}

body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
.fw-800 { font-weight: 800; }

/* Icon boxes */
.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.bg-primary-soft { background-color: var(--primary-soft); }
.bg-success-soft { background-color: var(--success-soft); }
.bg-danger-soft { background-color: var(--danger-soft); }
.bg-warning-soft { background-color: var(--warning-soft); }
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); }

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

/* UI Elements */
.rounded-4 { border-radius: 1rem !important; }
.btn-white { background: white; border: 1px solid #e2e8f0; color: #64748b; font-weight: 500; }
.btn-white.active { background: #6366f1; color: white; border-color: #6366f1; }

.rank-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.smaller-badge {
    font-size: 0.65rem;
    padding: 2px 8px;
    border-radius: 4px;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
}

.table-hover tbody tr:hover { background-color: #f8fafc; cursor: default; }

/* Chart responsive */
#revenueChart { width: 100% !important; height: 350px !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Gradient cho biểu đồ
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dayLabels ?? []) !!},
            datasets: [{
                label: 'Doanh thu',
                data: {!! json_encode($revenueData ?? []) !!},
                borderColor: '#6366f1',
                backgroundColor: '#e6e6e9',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 14 },
                    callbacks: {
                        label: function(context){
                            return ' ' + context.raw.toLocaleString('vi-VN') + ' ₫';
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { borderDash: [5, 5], drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            return value >= 1000000 ? (value/1000000) + 'M' : value.toLocaleString('vi-VN');
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endsection