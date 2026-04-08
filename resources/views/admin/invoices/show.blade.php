@extends('layouts.admin')

@section('admin_content')

<div class="container">

<h3 class="mb-4">Chi tiết hóa đơn</h3>

<div class="card shadow">
    <div class="card-body">

        <p><strong>Mã hóa đơn:</strong> #{{ $invoice->id }}</p>
        <p><strong>Khách hàng:</strong> {{ $invoice->user->ho_ten ?? 'Khách' }}</p>
        <p><strong>SĐT:</strong> {{ $invoice->booking->phone ?? '' }}</p>
        <p><strong>Cơ sở:</strong> {{ $invoice->booking->facility->name ?? '' }}</p>
        <p><strong>Ngày đặt:</strong> 
           {{ optional($invoice->booking)->booking_date 
    ? \Carbon\Carbon::parse($invoice->booking->booking_date)->format('d/m/Y') 
    : 'N/A'
}}

        <hr>

        <h5>Danh sách dịch vụ</h5>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên dịch vụ</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->details as $item)
                <tr>
                    <td>{{ $item->ten_dich_vu }}</td>
                    <td>{{ $item->so_luong }}</td>
                    <td>{{ number_format($item->don_gia) }} VNĐ</td>
                    <td>{{ number_format($item->so_luong * $item->don_gia) }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="text-end text-danger">
            Tổng tiền: {{ number_format($invoice->tong_tien) }} VNĐ
        </h4>

        <div class="mt-3 text-end">

    {{-- Thanh toán --}}
    @if($invoice->status != 'paid')
        <form action="{{ route('admin.invoice.paid', $invoice->id) }}"
              method="POST" style="display:inline;">
            @csrf
            <button class="btn btn-success">
                ✔️ Xác nhận đã thanh toán
            </button>
        </form>
    @else
        <span class="badge bg-success">Đã thanh toán</span>
    @endif

    {{-- Xuất PDF --}}
    <a href="{{ route('admin.invoice.pdf', $invoice->id) }}"
       class="btn btn-dark">
       📄 Xuất PDF
    </a>

    {{-- Quay lại --}}
    <a href="{{ route('admin.invoices') }}"
       class="btn btn-secondary">
       ← Quay lại
    </a>

</div>
    </div>
</div>

</div>

@endsection