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
    <td class="fw-bold">{{ ucwords($day['date']->locale('vi')->isoFormat('dddd')) }}</td>
    <td>{{ $day['date']->format('d-m-Y') }}</td>

    {{-- ===== SÁNG ===== --}}

<td>
    @php $slot = $day['morning']; @endphp


@if($slot && $slot->status != 'cancelled')

    @if($slot->status == 'locked')

        @if(Auth::check() && Auth::user()->vai_tro == 'admin')
            <form action="{{ route('admin.booking.unlock') }}" method="POST">
                @csrf
                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                <input type="hidden" name="session" value="morning">

                <button class="btn btn-dark btn-sm">🔓 Mở khóa</button>
            </form>
        @else
            <span class="badge bg-dark px-3 py-2">Đã khóa</span>
        @endif

    @elseif($slot->status == 'approved')
        <span class="badge bg-danger px-3 py-2">Đã thuê</span>

    @elseif($slot->status == 'pending')
        <span class="badge bg-warning text-dark px-3 py-2">Chờ duyệt</span>

    @endif

@else

    @if(Auth::check() && Auth::user()->vai_tro == 'admin')
        <form action="{{ route('admin.booking.lock') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
            <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
            <input type="hidden" name="session" value="morning">

            <button class="btn btn-warning btn-sm">Khóa sân</button>
        </form>
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

{{-- ===== CHIỀU ===== --}}

<td>
    @php $slot = $day['afternoon']; @endphp


@if($slot && $slot->status != 'cancelled')

    @if($slot->status == 'locked')

        @if(Auth::check() && Auth::user()->vai_tro == 'admin')
            <form action="{{ route('admin.booking.unlock') }}" method="POST">
                @csrf
                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                <input type="hidden" name="session" value="afternoon">

                <button class="btn btn-dark btn-sm">🔓 Mở khóa</button>
            </form>
        @else
            <span class="badge bg-dark px-3 py-2">Đã khóa</span>
        @endif

    @elseif($slot->status == 'approved')
        <span class="badge bg-danger px-3 py-2">Đã thuê</span>

    @elseif($slot->status == 'pending')
        <span class="badge bg-warning text-dark px-3 py-2">Chờ duyệt</span>

    @endif

@else

    @if(Auth::check() && Auth::user()->vai_tro == 'admin')
        <form action="{{ route('admin.booking.lock') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
            <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
            <input type="hidden" name="session" value="afternoon">

            <button class="btn btn-warning btn-sm">Khóa sân</button>
        </form>
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

{{-- ===== TỐI ===== --}}

<td>
    @php $slot = $day['evening']; @endphp


@if($slot && $slot->status != 'cancelled')

    @if($slot->status == 'locked')

        @if(Auth::check() && Auth::user()->vai_tro == 'admin')
            <form action="{{ route('admin.booking.unlock') }}" method="POST">
                @csrf
                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                <input type="hidden" name="session" value="evening">

                <button class="btn btn-dark btn-sm">🔓 Mở khóa</button>
            </form>
        @else
            <span class="badge bg-dark px-3 py-2">Đã khóa</span>
        @endif

    @elseif($slot->status == 'approved')
        <span class="badge bg-danger px-3 py-2">Đã thuê</span>

    @elseif($slot->status == 'pending')
        <span class="badge bg-warning text-dark px-3 py-2">Chờ duyệt</span>

    @endif

@else

    @if(Auth::check() && Auth::user()->vai_tro == 'admin')
        <form action="{{ route('admin.booking.lock') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
            <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
            <input type="hidden" name="session" value="evening">

            <button class="btn btn-warning btn-sm">Khóa sân</button>
        </form>
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