<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #333;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .header small {
            color: #666;
        }

        .divider {
            border-top: 2px solid #000;
            margin: 10px 0 15px;
        }

        /* INFO */
        .info {
            margin-bottom: 10px;
            font-size: 13px;
        }

        /* SUMMARY BOX */
        .summary {
            width: 100%;
            margin-bottom: 15px;
        }

        .summary td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        .summary .total {
            background: #e6f4ea;
            font-size: 14px;
        }

        .summary .cash {
            background: #fff3cd;
        }

        .summary .bank {
            background: #e7f1ff;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2a5298;
            color: white;
            padding: 6px;
            font-size: 11px;
        }

        td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
        }

    </style>
</head>

<body>

{{-- HEADER --}}
<div class="header">
    <h2>BÁO CÁO DOANH THU</h2>
    <small>Trung tâm Quản lý dịch vụ - Đại học An Giang</small>
</div>

<div class="divider"></div>

{{-- THỜI GIAN --}}
<div class="info">
    <strong>Thời gian:</strong>
    @if($type == 'day')
        Ngày {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
    @elseif($type == 'month' && request('month'))
        Tháng {{ \Carbon\Carbon::parse(request('month'))->format('m-Y') }}
    @elseif($type == 'year' && request('year'))
        Năm {{ request('year') }}
    @endif
</div>

{{-- TỔNG --}}
<table class="summary">
    <tr>
        <td class="total">Tổng doanh thu<br>{{ number_format($totalAll ?? 0) }} VNĐ</td>
        <td class="cash">Tiền mặt<br>{{ number_format($totalCash ?? 0) }} VNĐ</td>
        <td class="bank">Chuyển khoản<br>{{ number_format($totalBank ?? 0) }} VNĐ</td>
    </tr>
</table>

{{-- TABLE --}}
<h4>Chi tiết giao dịch</h4>

<table>
    <thead>
        <tr>
            <th>Thanh toán</th>
            <th>Đặt</th>
            <th>Sử dụng</th>
            <th>Cơ sở</th>
            <th>Loại</th>
            <th>Thời gian</th>
            <th>Khách</th>
            <th>SĐT</th>
            <th>PTTT</th>
            <th>Giá</th>
        </tr>
    </thead>

    <tbody>
        @forelse(($grouped ?? collect())->flatten() as $b)
        <tr>

            <td>{{ $b->paid_at ? \Carbon\Carbon::parse($b->paid_at)->format('d-m-Y') : '' }}</td>

            <td>{{ $b->created_at ? \Carbon\Carbon::parse($b->created_at)->format('d-m-Y') : '' }}</td>

            <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d-m-Y') : '' }}</td>

            <td>{{ $b->facility_name ?? ($b->facility->name ?? '') }}</td>

            <td>
                {{ $b->category_type == 'sport' ? 'Sân' : 'Phòng' }}
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

            <td>
                {{ $b->payment_method == 'Tiền mặt' ? 'Tiền mặt' : 'Chuyển khoản' }}
            </td>

            <td><strong>{{ number_format($b->price) }}</strong></td>

        </tr>
        @empty
        <tr>
            <td colspan="10" style="text-align:center;">Không có dữ liệu</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- FOOTER --}}
<div class="footer">
    Ngày xuất: {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>