@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center fw-bold text-primary mb-4">
        {{ $facility->name }}
    </h3>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif


    {{-- Bảng lịch --}}
    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered text-center align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Thứ</th>
                            <th>Ngày</th>
                            <th>Sáng <br> (7h - 11h30)</th>
                            <th>Chiều <br> (13h - 17h30)</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($weekDays as $day)

                        <tr>

                            <td class="fw-bold">
                                {{ $day['date']->isoFormat('dd') }}
                            </td>

                            <td>
                                {{ $day['date']->format('d-m-Y') }}
                            </td>


                            {{-- Ca sáng --}}
                            <td>

                                @if($day['morning'])

                                    <span class="badge bg-danger px-3 py-2">
                                        Đã đặt
                                    </span>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-outline-success select-slot"
                                        data-date="{{ $day['date']->format('Y-m-d') }}"
                                        data-session="morning"
                                    >
                                        Chọn
                                    </button>

                                @endif

                            </td>


                            {{-- Ca chiều --}}
                            <td>

                                @if($day['afternoon'])

                                    <span class="badge bg-danger px-3 py-2">
                                        Đã đặt
                                    </span>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-outline-success select-slot"
                                        data-date="{{ $day['date']->format('Y-m-d') }}"
                                        data-session="afternoon"
                                    >
                                        Chọn
                                    </button>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- FORM ĐẶT LỊCH (Ẩn ban đầu) --}}
    <div
        id="bookingFormBox"
        class="card shadow mt-4"
        style="display:none"
    >

        <div class="card-body">

            <h5 class="fw-bold text-center mb-4">
                Thông tin đặt lịch
            </h5>

            <form
                method="POST"
                action="{{ route('booking.store') }}"
                id="bookingForm"
            >

                @csrf

                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                <input type="hidden" name="booking_date" id="booking_date">
                <input type="hidden" name="session" id="session">


                {{-- Họ tên --}}
                <div class="mb-3">

                    <label class="form-label">
                        Họ tên
                    </label>

                    <input
                        type="text"
                        name="fullname"
                        class="form-control"
                        required
                    >

                </div>


                {{-- Số điện thoại --}}
                <div class="mb-3">

                    <label class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        required
                    >

                </div>


                {{-- Giá --}}
                <div class="mb-3">

                    <label class="form-label">
                        Số tiền
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ number_format($facility->price) }} VNĐ"
                        readonly
                    >

                </div>


                {{-- Thanh toán --}}
                <div class="mb-3">

                    <label class="form-label">
                        Thanh toán
                    </label>

                    <select
                        name="payment_method"
                        class="form-control"
                    >

                        <option value="Tiền mặt">
                            Tiền mặt
                        </option>

                        <option value="Chuyển khoản">
                            Chuyển khoản
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    Xác nhận đặt
                </button>

            </form>

        </div>

    </div>

</div>



<script>

    const buttons = document.querySelectorAll('.select-slot');

    buttons.forEach(button => {

        button.addEventListener('click', function(){

            document.getElementById('booking_date').value = this.dataset.date;

            document.getElementById('session').value = this.dataset.session;

            document.getElementById('bookingFormBox').style.display = 'block';

            window.scrollTo({
                top: document.getElementById('bookingFormBox').offsetTop - 80,
                behavior: 'smooth'
            });

        });

    });

</script>

@endsection