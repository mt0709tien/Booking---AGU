@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center fw-bold text-primary mb-4">
        {{ $facility->name }}
    </h3>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">

                <form action="{{ route('booking.form.multiple') }}" method="POST">
                    @csrf

                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Thứ</th>
                                <th>Ngày</th>
                                <th>Sáng <br> (7h - 11h)</th>
                                <th>Chiều <br> (13h - 17h)</th>
                                <th>Tối <br> (17h - 21h)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weekDays as $day)
                                <tr>
                                    <td class="fw-bold">{{ $day['date']->isoFormat('dd') }}</td>
                                    <td>{{ $day['date']->format('d-m-Y') }}</td>

                                    {{-- SÁNG --}}
                                    <td>
                                        @if($day['morning'])

                                            @if($day['morning']->status == 'locked')
                                                <span class="badge bg-dark px-3 py-2">Đã khóa</span>

                                            @elseif($day['morning']->status == 'approved')
                                                <span class="badge bg-danger px-3 py-2">Đã thuê</span>

                                            @elseif($day['morning']->status == 'pending')
                                                <span class="badge bg-warning text-dark px-3 py-2">Chờ duyệt</span>

                                            @endif

                                        @else
                                            @if(Auth::check() && Auth::user()->vai_tro == 'admin')
                                                <form id="lock-{{ $day['date']->format('Y-m-d') }}-morning"
                                                    action="{{ route('admin.booking.lock') }}" method="POST" style="display:none;">
                                                    @csrf
                                                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                                    <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                                                    <input type="hidden" name="session" value="morning">
                                                </form>

                                                <button type="button"
                                                    onclick="document.getElementById('lock-{{ $day['date']->format('Y-m-d') }}-morning').submit();"
                                                    class="btn btn-warning btn-sm">
                                                    Khóa sân
                                                </button>
                                            @else
                                                <input type="checkbox"
                                                    name="bookings[]"
                                                    value="{{ $facility->id }}|{{ $day['date']->format('Y-m-d') }}|morning">
                                            @endif
                                        @endif

                                        <div class="small text-muted mt-1">
                                            {{ number_format($facility->category->price_morning) }} VNĐ
                                        </div>
                                    </td>

                                    {{-- CHIỀU --}}
                                    <td>
                                        @if($day['afternoon'])

                                            @if($day['afternoon']->status == 'locked')
                                                <span class="badge bg-dark px-3 py-2">Đã khóa</span>

                                            @elseif($day['afternoon']->status == 'approved')
                                                <span class="badge bg-danger px-3 py-2">Đã thuê</span>

                                            @elseif($day['afternoon']->status == 'pending')
                                                <span class="badge bg-warning text-dark px-3 py-2">Chờ duyệt</span>

                                            @endif

                                        @else
                                            @if(Auth::check() && Auth::user()->vai_tro == 'admin')
                                                <form id="lock-{{ $day['date']->format('Y-m-d') }}-afternoon"
                                                    action="{{ route('admin.booking.lock') }}" method="POST" style="display:none;">
                                                    @csrf
                                                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                                    <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                                                    <input type="hidden" name="session" value="afternoon">
                                                </form>

                                                <button type="button"
                                                    onclick="document.getElementById('lock-{{ $day['date']->format('Y-m-d') }}-afternoon').submit();"
                                                    class="btn btn-warning btn-sm">
                                                    Khóa sân
                                                </button>
                                            @else
                                                <input type="checkbox"
                                                    name="bookings[]"
                                                    value="{{ $facility->id }}|{{ $day['date']->format('Y-m-d') }}|afternoon">
                                            @endif
                                        @endif

                                        <div class="small text-muted mt-1">
                                            {{ number_format($facility->category->price_afternoon) }} VNĐ
                                        </div>
                                    </td>

                                    {{-- TỐI --}}
                                    <td>
                                        @if($day['evening'])

                                            @if($day['evening']->status == 'locked')
                                                <span class="badge bg-dark px-3 py-2">Đã khóa</span>

                                            @elseif($day['evening']->status == 'approved')
                                                <span class="badge bg-danger px-3 py-2">Đã thuê</span>

                                            @elseif($day['evening']->status == 'pending')
                                                <span class="badge bg-warning text-dark px-3 py-2">Chờ duyệt</span>

                                            @endif

                                        @else
                                            @if(Auth::check() && Auth::user()->vai_tro == 'admin')
                                                <form id="lock-{{ $day['date']->format('Y-m-d') }}-evening"
                                                    action="{{ route('admin.booking.lock') }}" method="POST" style="display:none;">
                                                    @csrf
                                                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                                    <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                                                    <input type="hidden" name="session" value="evening">
                                                </form>

                                                <button type="button"
                                                    onclick="document.getElementById('lock-{{ $day['date']->format('Y-m-d') }}-evening').submit();"
                                                    class="btn btn-warning btn-sm">
                                                    Khóa sân
                                                </button>
                                            @else
                                                <input type="checkbox"
                                                    name="bookings[]"
                                                    value="{{ $facility->id }}|{{ $day['date']->format('Y-m-d') }}|evening">
                                            @endif
                                        @endif

                                        <div class="small text-muted mt-1">
                                            {{ number_format($facility->category->price_evening) }} VNĐ
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @unless(Auth::check() && Auth::user()->vai_tro == 'admin')
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-success px-4"
                                onclick="if(document.querySelectorAll('input[name=\'bookings[]\']:checked').length == 0){ alert('Chọn ít nhất 1 ca!'); return false; }">
                                Đặt lịch
                            </button>
                        </div>
                    @endunless

                </form>

            </div>
        </div>
    </div>

</div>

@endsection