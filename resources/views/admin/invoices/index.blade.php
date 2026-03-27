@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="mb-4">Quản lý hóa đơn</h3>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Booking</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($invoices as $invoice)

                    <tr>
                        <td>{{ $invoice->id }}</td>

                        {{-- USER --}}
                        <td>
                            {{ $invoice->user->ho_ten ?? 'Không có' }}
                        </td>

                        {{-- BOOKING --}}
                        <td>
                            @if($invoice->booking)
                                #{{ $invoice->booking->id }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- MONEY --}}
                        <td class="text-danger fw-bold">
                            {{ number_format($invoice->tong_tien) }} ₫
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($invoice->status == 'paid')
                                <span class="badge bg-success">
                                    Đã thanh toán
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    Chưa thanh toán
                                </span>
                            @endif
                        </td>

                        {{-- DATE --}}
                        <td>
                            {{ $invoice->created_at->format('d/m/Y H:i') }}
                        </td>

                        {{-- ACTION --}}
                        <td>

                            {{-- XEM --}}
                            <a href="{{ route('admin.invoice.show', $invoice->id) }}"
                               class="btn btn-info btn-sm">
                                👁 Xem
                            </a>

                            {{-- (OPTION) ĐÁNH DẤU ĐÃ THANH TOÁN --}}
                            @if($invoice->status != 'paid')
                                <form action="{{ route('admin.invoice.paid', $invoice->id) }}"
                                      method="POST"
                                      style="display:inline;">
                                    @csrf
                                    <button class="btn btn-success btn-sm"
                                            onclick="return confirm('Xác nhận đã thanh toán?')">
                                        ✔️ Đã thanh toán
                                    </button>
                                </form>
                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center">
                            Không có hóa đơn nào
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $invoices->links() }}
            </div>

        </div>
    </div>

</div>

@endsection