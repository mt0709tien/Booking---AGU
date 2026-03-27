<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans; }
    table { width: 100%; border-collapse: collapse; }
    table, th, td { border: 1px solid #000; }
    th, td { padding: 8px; text-align: left; }
</style>
</head>
<body>

<h2>HÓA ĐƠN</h2>

<p><strong>Mã:</strong> #{{ $invoice->id }}</p>
<p><strong>Khách:</strong> {{ $invoice->user->ho_ten ?? 'Khách' }}</p>
<p><strong>SĐT:</strong> {{ $invoice->booking->phone }}</p>
<p><strong>Cơ sở:</strong> {{ $invoice->booking->facility->name }}</p>

<hr>

<table>
    <thead>
        <tr>
            <th>Dịch vụ</th>
            <th>SL</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->details as $item)
        <tr>
            <td>{{ $item->ten_dich_vu }}</td>
            <td>{{ $item->so_luong }}</td>
            <td>{{ number_format($item->don_gia) }}</td>
            <td>{{ number_format($item->so_luong * $item->don_gia) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3 style="text-align:right;">
Tổng: {{ number_format($invoice->tong_tien) }} VNĐ
</h3>

</body>
</html>