@extends('layouts.admin')

@section('admin_content')

<div class="container py-4">

    <h3 class="text-center text-danger fw-bold mb-4">
        BÁO CÁO DOANH THU
    </h3>

    {{-- FORM --}}
    <form method="GET" class="row g-3 mb-4">

        <div class="col-md-3">
            <select name="type" id="type" class="form-select" onchange="changeType()">
                <option value="day" {{ $type=='day'?'selected':'' }}>Theo ngày</option>
                <option value="month" {{ $type=='month'?'selected':'' }}>Theo tháng</option>
                <option value="year" {{ $type=='year'?'selected':'' }}>Theo năm</option>
            </select>
        </div>

        <div class="col-md-3 type-input" id="input-day">
            <input type="date" name="date" value="{{ $date }}" class="form-control">
        </div>

        <div class="col-md-3 type-input" id="input-month">
            <input type="month" name="month" value="{{ request('month') }}" class="form-control">
        </div>

        <div class="col-md-3 type-input" id="input-year">
            <input type="number" name="year" value="{{ request('year') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <select name="payment" class="form-select">
                <option value="">-- Tất cả --</option>
                <option value="Tiền mặt">Tiền mặt</option>
                <option value="Chuyển khoản">Chuyển khoản</option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-danger w-100">Xem báo cáo</button>
        </div>

    </form>

    {{-- TỔNG --}}
    <div class="card text-center mb-4 shadow border-success">
        <div class="card-body">
            <h5 class="text-muted">
                Tổng doanh thu 
                @if($type == 'day')
                    ngày {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                @elseif($type == 'month' && request('month'))
                    tháng {{ \Carbon\Carbon::parse(request('month'))->format('m-Y') }}
                @elseif($type == 'year' && request('year'))
                    năm {{ request('year') }}
                @endif
            </h5>

            <h2 class="text-success fw-bold">
                {{ number_format($totalAll) }} VNĐ
            </h2>
        </div>
    </div>

    {{-- TIỀN MẶT + CHUYỂN KHOẢN --}}
    <div class="row mb-4">

        <div class="col-md-6">
            <div class="card text-center border-success shadow">
                <div class="card-body">
                    <h6 class="text-muted">💵 Tiền mặt</h6>
                    <h4 class="text-success fw-bold">
                        {{ number_format($totalCash) }} VNĐ
                    </h4>

                    <button class="btn btn-sm btn-success mt-2"
                        onclick="toggleDetail('cash-detail')">
                        Xem chi tiết
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-center border-primary shadow">
                <div class="card-body">
                    <h6 class="text-muted">💳 Chuyển khoản</h6>
                    <h4 class="text-primary fw-bold">
                        {{ number_format($totalBank) }} VNĐ
                    </h4>

                    <button class="btn btn-sm btn-primary mt-2"
                        onclick="toggleDetail('bank-detail')">
                        Xem chi tiết
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- CHI TIẾT TIỀN MẶT --}}
    <div id="cash-detail" style="display:none;">
        <div class="card mb-4 shadow">
            <div class="card-body">

                <h5 class="text-success">Chi tiết tiền mặt</h5>

                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày thanh toán</th>
                            <th>Ngày đặt</th>
                            <th>Ngày sử dụng</th>
                            <th>Cơ sở</th>
                            <th>Ca</th>
                            <th>Khách</th>
                            <th>SĐT</th>
                            <th>Giá</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($grouped->flatten()->where('payment_method','Tiền mặt') as $b)
                        <tr>
                            <td>{{ $b->paid_at ? \Carbon\Carbon::parse($b->paid_at)->format('d-m-Y') : '' }}</td>
                            <td>{{ $b->created_at ? \Carbon\Carbon::parse($b->created_at)->format('d-m-Y') : '' }}</td>
                            <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d-m-Y') : '' }}</td>
                            <td>{{ $b->facility->name }}</td>
                            <td>{{ $b->session == 'morning' ? 'Sáng' : ($b->session == 'afternoon' ? 'Chiều' : 'Tối') }}</td>
                            <td>{{ $b->fullname }}</td>
                            <td>{{ $b->phone }}</td>
                            <td>{{ number_format($b->price) }} VNĐ</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

    {{-- CHI TIẾT CHUYỂN KHOẢN --}}
    <div id="bank-detail" style="display:none;">
        <div class="card mb-4 shadow">
            <div class="card-body">

                <h5 class="text-primary">Chi tiết chuyển khoản</h5>

                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày thanh toán</th>
                            <th>Ngày đặt</th>
                            <th>Ngày sử dụng</th>
                            <th>Cơ sở</th>
                            <th>Ca</th>
                            <th>Khách</th>
                            <th>SĐT</th>
                            <th>Giá</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($grouped->flatten()->where('payment_method','Chuyển khoản') as $b)
                        <tr>
                            <td>{{ $b->paid_at ? \Carbon\Carbon::parse($b->paid_at)->format('d-m-Y') : '' }}</td>
                            <td>{{ $b->created_at ? \Carbon\Carbon::parse($b->created_at)->format('d-m-Y') : '' }}</td>
                            <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d-m-Y') : '' }}</td>
                            <td>{{ $b->facility->name }}</td>
                            <td>{{ $b->session == 'morning' ? 'Sáng' : ($b->session == 'afternoon' ? 'Chiều' : 'Tối') }}</td>
                            <td>{{ $b->fullname }}</td>
                            <td>{{ $b->phone }}</td>
                            <td>{{ number_format($b->price) }} VNĐ</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>

{{-- JS --}}
<script>
function toggleDetail(id) {
    let el = document.getElementById(id);
    el.style.display = (el.style.display === 'none') ? 'block' : 'none';
}

function changeType() {
    let type = document.getElementById('type').value;

    document.getElementById('input-day').style.display = 'none';
    document.getElementById('input-month').style.display = 'none';
    document.getElementById('input-year').style.display = 'none';

    if (type === 'day') {
        document.getElementById('input-day').style.display = 'block';
    } else if (type === 'month') {
        document.getElementById('input-month').style.display = 'block';
    } else {
        document.getElementById('input-year').style.display = 'block';
    }
}

document.addEventListener("DOMContentLoaded", changeType);
</script>

<form action="{{ route('report.export') }}" method="GET">
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="date" value="{{ $date }}">
    <input type="hidden" name="month" value="{{ request('month') }}">
    <input type="hidden" name="year" value="{{ request('year') }}">
    <input type="hidden" name="payment" value="{{ request('payment') }}">

    <button class="btn btn-success mb-3">
        Xuất báo cáo PDF
    </button>
</form>

@endsection