@extends('layouts.admin')

@section('admin_content')

<div class="container py-4">

    <h3 class="text-center text-danger fw-bold mb-4">
        BÁO CÁO DOANH THU
    </h3>

    {{-- FORM --}}
    <form method="GET" class="row g-3 mb-4">

    {{-- TYPE --}}
    <div class="col-md-3">
        <select name="type" id="type" class="form-select" onchange="this.form.submit()">
            <option value="day" {{ request('type','day')=='day'?'selected':'' }}>Theo ngày</option>
            <option value="month" {{ request('type')=='month'?'selected':'' }}>Theo tháng</option>
            <option value="year" {{ request('type')=='year'?'selected':'' }}>Theo năm</option>
        </select>
    </div>

    {{-- INPUT --}}
    <div class="col-md-4">

        @php $type = request('type','day'); @endphp

        @if($type == 'day')
            <input type="date" name="date"
                   value="{{ request('date', date('Y-m-d')) }}"
                   class="form-control">

        @elseif($type == 'month')
            <input type="month" name="month"
                   value="{{ request('month', date('Y-m')) }}"
                   class="form-control">

        @else
            <input type="number" name="year"
                   value="{{ request('year', date('Y')) }}"
                   class="form-control"
                   placeholder="Nhập năm">
        @endif

    </div>

    {{-- PAYMENT --}}
    <div class="col-md-3">
        <select name="payment" class="form-select">
            <option value="" {{ request('payment')==''?'selected':'' }}>-- Tất cả --</option>
            <option value="Tiền mặt" {{ request('payment')=='Tiền mặt'?'selected':'' }}>Tiền mặt</option>
            <option value="Chuyển khoản" {{ request('payment')=='Chuyển khoản'?'selected':'' }}>Chuyển khoản</option>
        </select>
    </div>

    {{-- BUTTON --}}
    <div class="col-md-2">
        <button class="btn btn-danger w-100">Xem</button>
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

    {{-- TIỀN --}}
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

    {{-- CASH DETAIL --}}
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
                            <th>Loại</th>
                            <th>Thời gian</th>
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

                            <td>{{ $b->facility_name }}</td>

                            <td>
                                @if($b->category_type == 'sport')
                                    <span class="badge bg-primary">Sân</span>
                                @else
                                    <span class="badge bg-success">Phòng</span>
                                @endif
                            </td>

                            <td>
                                @if($b->start_time && $b->end_time)
                                    {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                                @else
                                    {{ $b->session == 'morning' ? 'Sáng' : ($b->session == 'afternoon' ? 'Chiều' : 'Tối') }}
                                @endif
                            </td>

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

    {{-- BANK DETAIL --}}
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
                            <th>Loại</th>
                            <th>Thời gian</th>
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

                            <td>{{ $b->facility_name }}</td>

                            <td>
                                @if($b->category_type == 'sport')
                                    <span class="badge bg-primary">Sân</span>
                                @else
                                    <span class="badge bg-success">Phòng</span>
                                @endif
                            </td>

                            <td>
                                @if($b->start_time && $b->end_time)
                                    {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                                @else
                                    {{ $b->session == 'morning' ? 'Sáng' : ($b->session == 'afternoon' ? 'Chiều' : 'Tối') }}
                                @endif
                            </td>

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

    {{-- EXPORT BUTTON --}}
    <div class="col-md-3 mt-3">
        <a href="{{ route('report.export', request()->all() + ['type_export' => 'pdf']) }}" 
           class="btn btn-danger w-100">
            🖨️ Xuất báo cáo
        </a>
    </div>

</div>

<script>
function toggleDetail(id) {
    let el = document.getElementById(id);
    el.style.display = (el.style.display === "none" || el.style.display === "") 
        ? "block" 
        : "none";
}
</script>

@endsection