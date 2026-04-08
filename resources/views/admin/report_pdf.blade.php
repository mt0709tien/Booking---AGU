<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: DejaVu Sans; 
            font-size: 13px;
        }

        h2 { 
            text-align: center; 
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 10px;
        }

        .box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 10px;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }

        th, td { 
            border: 1px solid #000; 
            padding: 5px; 
            text-align: center; 
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

<h2>HÓA ĐƠN DOANH THU</h2>

{{-- 🔥 THỜI GIAN --}}
<div class="info">
    @if($type == 'day')
        Ngày: {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
    @elseif($type == 'month' && request('month'))
        Tháng: {{ \Carbon\Carbon::parse(request('month'))->format('m-Y') }}
    @elseif($type == 'year' && request('year'))
        Năm: {{ request('year') }}
    @endif
</div>

{{-- 🔥 TỔNG --}}
<div class="box total">
    Tổng doanh thu: {{ number_format($totalAll ?? 0) }} VNĐ
</div>

{{-- 🔥 TIỀN MẶT --}}
<div class="box">
    💵 Tiền mặt: {{ number_format($totalCash ?? 0) }} VNĐ
</div>

{{-- 🔥 CHUYỂN KHOẢN --}}
<div class="box">
    💳 Chuyển khoản: {{ number_format($totalBank ?? 0) }} VNĐ
</div>

{{-- 🔥 CHI TIẾT --}}
<h3>Chi tiết giao dịch</h3>

<table>
    <thead>
        <tr>
            <th>Ngày thanh toán</th>
            <th>Ngày đặt</th> <!-- ✅ thêm -->
            <th>Ngày sử dụng</th> <!-- ✅ thêm -->
            <th>Cơ sở</th>
            <th>Ca</th>
            <th>Khách</th>
            <th>SĐT</th>
            <th>Thanh toán</th>
            <th>Giá</th>
        </tr>
    </thead>

    <tbody>
        @forelse(($grouped ?? collect())->flatten() as $b)
        <tr>
            {{-- Ngày thanh toán --}}
            <td>
                {{ $b->paid_at ? \Carbon\Carbon::parse($b->paid_at)->format('d-m-Y') : '' }}
            </td>

            {{-- ✅ Ngày đặt --}}
            <td>
                {{ $b->created_at ? \Carbon\Carbon::parse($b->created_at)->format('d-m-Y') : '' }}
            </td>

            {{-- ✅ Ngày sử dụng (đặt sân / đặt lịch) --}}
            <td>
                {{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d-m-Y') : '' }}
            </td>

            <td>{{ $b->facility->name ?? '' }}</td>

            <td>
                {{ $b->session == 'morning' ? 'Sáng' : ($b->session == 'afternoon' ? 'Chiều' : 'Tối') }}
            </td>

            <td>{{ $b->fullname }}</td>
            <td>{{ $b->phone }}</td>

            <td>
                {{ $b->payment_method == 'Tiền mặt' ? 'Tiền mặt' : 'Chuyển khoản' }}
            </td>

            <td>{{ number_format($b->price) }} VNĐ</td>
        </tr>
        @empty
        <tr>
            <td colspan="9">Không có dữ liệu</td>
        </tr>
        @endforelse
    </tbody>

</table>

</body>
</html>