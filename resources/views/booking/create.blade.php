@extends('layouts.app')

@section('content')

<style>
    .booking-card { border-radius: 15px; border: none; }
    .table-modern thead { background-color: #f8f9fa; }
    .table-modern th { font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; }
    .slot-item { background: #f0f7ff; border-radius: 8px; border: 1px solid #d0e3ff; transition: all 0.3s; }
    .slot-item:hover { background: #e1efff; }
    .form-label { font-weight: 600; color: #495057; }
    .badge-status { font-size: 0.75rem; padding: 0.5em 0.8em; }
    .btn-action { border-radius: 8px; font-weight: 600; transition: transform 0.2s; }
    .btn-action:active { transform: scale(0.95); }
    .price-text { color: #28a745; font-size: 1.1rem; }
    input[type="checkbox"] { width: 1.2rem; height: 1.2rem; cursor: pointer; }
</style>

<div class="container py-5">

    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h2 class="fw-extrabold text-dark mb-2">{{ $facility->name }}</h2>
        <div class="mx-auto" style="width: 60px; height: 4px; background: #0d6efd; border-radius: 2px;"></div>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeIn">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeIn">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ========================= GIAO DIỆN SÂN THỂ THAO ========================= --}}
    @if($facility->category->type == 'sport')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card booking-card shadow-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-clock me-2"></i>Chọn khung giờ đặt sân</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('booking.form.multiple') }}" method="POST">
                        @csrf

                        <div class="row g-4 mb-4">
                            {{-- CHỌN NGÀY --}}
                            <div class="col-md-4">
                                <label class="form-label">Ngày đặt sân</label>
                                <div class="input-group shadow-sm rounded">
                                    <span class="input-group-text bg-white border-end-0"><i class="far fa-calendar-alt text-primary"></i></span>
                                    {{-- [FIX] Thêm onchange để lọc giờ bắt đầu --}}
                                    <input type="date" id="booking_date" class="form-control border-start-0" min="{{ date('Y-m-d') }}" onchange="filterStartTimes()">
                                </div>
                            </div>

                            {{-- GIỜ BẮT ĐẦU --}}
                            <div class="col-md-3">
                                <label class="form-label">Giờ bắt đầu</label>
                                {{-- [FIX] Thêm onchange để lọc giờ kết thúc, thêm data-minutes --}}
                                <select id="start_time" class="form-select shadow-sm" onchange="filterEndTimes()">
                                    <option value="">--:--</option>
                                    @for($h = 7; $h <= 21; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}" data-minutes="{{ $h * 60 }}">{{ sprintf('%02d:00', $h) }}</option>
                                        @if($h < 21)
                                            <option value="{{ sprintf('%02d:30', $h) }}" data-minutes="{{ $h * 60 + 30 }}">{{ sprintf('%02d:30', $h) }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>

                            {{-- GIỜ KẾT THÚC --}}
                            <div class="col-md-3">
                                <label class="form-label">Giờ kết thúc</label>
                                {{-- [FIX] Thêm data-minutes --}}
                                <select id="end_time" class="form-select shadow-sm">
                                    <option value="">--:--</option>
                                    @for($h = 7; $h <= 21; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}" data-minutes="{{ $h * 60 }}">{{ sprintf('%02d:00', $h) }}</option>
                                        @if($h < 21)
                                            <option value="{{ sprintf('%02d:30', $h) }}" data-minutes="{{ $h * 60 + 30 }}">{{ sprintf('%02d:30', $h) }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>

                            {{-- NÚT THÊM --}}
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100 btn-action shadow-sm" onclick="addSlot()">
                                    <i class="fas fa-plus"></i> Thêm
                                </button>
                            </div>
                        </div>

                        {{-- DANH SÁCH SLOT --}}
                        <div id="slotList" class="mb-4"></div>
                        <div id="hiddenInputs"></div>

                        <div class="d-flex flex-column align-items-center bg-light p-3 rounded-4 mb-4 shadow-sm border">
                            <span class="text-muted small fw-bold text-uppercase mb-1">Đơn giá</span>
                            <div class="price-text fw-bold">
                                {{ number_format($facility->category->price_hour) }} VNĐ <span class="text-muted small">/ giờ</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="fw-bold text-danger mb-2">
                               ⛔ Khung giờ đã được đặt
                            </h6>
                        <div id="bookedList"></div>
                        </div>

                        <div class="text-center d-flex justify-content-center gap-3">
                            @unless(Auth::check() && Auth::user()->vai_tro == 'admin')
                                <button type="submit" class="btn btn-success btn-action px-5 py-2 shadow" onclick="submitForm(event, 'book')">
                                    <i class="fas fa-shopping-cart me-2"></i>Xác nhận đặt sân
                                </button>
                            @endunless

                            @if(Auth::check() && Auth::user()->vai_tro == 'admin')
                                {{-- [FIX] Nút khóa có thêm xác nhận trước khi submit --}}
                                <button type="submit" class="btn btn-warning btn-action px-5 py-2 shadow" onclick="submitForm(event, 'lock')">
                                    <i class="fas fa-lock me-2"></i>Khóa sân ngay
                                </button>
                            @endif
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

<script>
let slots = [];
let bookedSlots = [];
let isChecking = false;

// =======================
// UTILS
// =======================
function toMinutes(time) {
    if(!time) return 0;
    let [h, m] = time.split(':').map(Number);
    return h * 60 + m;
}

function isOverlap(aStart, aEnd, bStart, bEnd) {
    return toMinutes(aStart) < toMinutes(bEnd) &&
           toMinutes(aEnd) > toMinutes(bStart);
}

// =======================
// [FIX] LỌC GIỜ BẮT ĐẦU THEO NGÀY
// Nếu chọn hôm nay → ẩn các giờ đã qua
// =======================
function filterStartTimes() {
    let date = document.getElementById('booking_date').value;
    let today = new Date().toISOString().split('T')[0];
    let now = new Date();
    let nowMinutes = now.getHours() * 60 + now.getMinutes();
    // Làm tròn lên 30 phút tiếp theo
    let roundedMinutes = Math.ceil(nowMinutes / 30) * 30;

    let startSelect = document.getElementById('start_time');
    startSelect.value = '';

    Array.from(startSelect.options).forEach(opt => {
        if (!opt.value) return;
        let optMinutes = parseInt(opt.getAttribute('data-minutes'));
        if (date === today) {
            opt.hidden   = optMinutes < roundedMinutes;
            opt.disabled = optMinutes < roundedMinutes;
        } else {
            opt.hidden   = false;
            opt.disabled = false;
        }
    });

    // Reset end_time khi đổi ngày
    document.getElementById('end_time').value = '';
    // Reset lại filter end_time về trạng thái đầy đủ
    Array.from(document.getElementById('end_time').options).forEach(opt => {
        opt.hidden   = false;
        opt.disabled = false;
    });
}

// =======================
// [FIX] LỌC GIỜ KẾT THÚC (phải sau giờ bắt đầu)
// =======================
function filterEndTimes() {
    let startVal = document.getElementById('start_time').value;
    if (!startVal) return;
    let startMinutes = toMinutes(startVal);

    let endSelect = document.getElementById('end_time');
    endSelect.value = '';

    Array.from(endSelect.options).forEach(opt => {
        if (!opt.value) return;
        let optMinutes = parseInt(opt.getAttribute('data-minutes'));
        opt.hidden   = optMinutes <= startMinutes;
        opt.disabled = optMinutes <= startMinutes;
    });
}

// =======================
// LOAD SLOT ĐÃ ĐẶT TỪ DB
// =======================
async function loadBookedSlots(date) {
    let facilityId = "{{ $facility->id }}";

    try {
        let res = await fetch(`/get-booked-slots?facility_id=${facilityId}&date=${date}`);
        let data = await res.json();

        bookedSlots = data.slots || [];
    } catch (e) {
        console.error("Lỗi load booked slots:", e);
        bookedSlots = [];
    }
}

// =======================
// HIỂN THỊ SLOT ĐÃ ĐẶT
// =======================
function renderBookedList() {
    let html = '';

    if (bookedSlots.length === 0) {
        html = '<div class="text-muted">Chưa có ai đặt</div>';
    } else {
        bookedSlots.forEach(s => {
            html += `
                <div class="p-2 mb-2 border rounded bg-light d-flex justify-content-between">
                    <span class="text-danger fw-bold">
                        ${s.start_time} - ${s.end_time}
                    </span>
                    <span class="badge bg-danger">Đã đặt</span>
                </div>
            `;
        });
    }

    document.getElementById('bookedList').innerHTML = html;
}

// =======================
// CHECK TRÙNG DATABASE
// =======================
async function checkDatabaseConflict(date, start, end) {
    let facilityId = "{{ $facility->id }}";

    try {
        let res = await fetch(`/check-slot?facility_id=${facilityId}&date=${date}&start=${start}&end=${end}`);
        let data = await res.json();
        return data.conflict;
    } catch (e) {
        console.error("Lỗi check DB:", e);
        return false;
    }
}

// =======================
// REALTIME CHECK
// =======================
async function checkRealtime() {
    let date = document.getElementById('booking_date').value;
    let start = document.getElementById('start_time').value;
    let end = document.getElementById('end_time').value;

    if (!date || !start || !end) return;

    if (toMinutes(start) >= toMinutes(end)) {
        alert('❌ Giờ kết thúc phải lớn hơn giờ bắt đầu!');
        document.getElementById('end_time').value = '';
        return;
    }

    // check local slots
    for (let s of slots) {
        if (s.date === date && isOverlap(start, end, s.start, s.end)) {
            alert('❌ Trùng với slot đã thêm!');
            document.getElementById('end_time').value = '';
            return;
        }
    }

    if (isChecking) return;
    isChecking = true;

    let conflict = await checkDatabaseConflict(date, start, end);

    if (conflict) {
        alert('❌ Khung giờ đã có người đặt!');
        document.getElementById('end_time').value = '';
    }

    isChecking = false;
}

// =======================
// THÊM SLOT
// =======================
async function addSlot() {
    let date = document.getElementById('booking_date').value;
    let start = document.getElementById('start_time').value;
    let end = document.getElementById('end_time').value;

    if (!date || !start || !end) {
        alert('Vui lòng nhập đầy đủ!');
        return;
    }

    if (toMinutes(start) >= toMinutes(end)) {
        alert('Giờ không hợp lệ!');
        return;
    }

    // check local
    for (let s of slots) {
        if (s.date === date && isOverlap(start, end, s.start, s.end)) {
            alert('Trùng slot đã thêm!');
            return;
        }
    }

    // check DB
    let conflict = await checkDatabaseConflict(date, start, end);
    if (conflict) {
        alert('Slot đã được đặt!');
        return;
    }

    slots.push({ date, start, end });
    renderSlots();

    document.getElementById('start_time').value = '';
    document.getElementById('end_time').value = '';
}

// =======================
// RENDER SLOT ĐÃ CHỌN
// =======================
function renderSlots() {
    let html = '';

    slots.forEach((s, i) => {
        html += `
            <div class="slot-item p-3 mb-2 d-flex justify-content-between">
                <div>
                    <span class="badge bg-primary">${s.date}</span>
                    <span class="fw-bold ms-2">${s.start} - ${s.end}</span>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeSlot(${i})">X</button>
            </div>
        `;
    });

    document.getElementById('slotList').innerHTML = html;
}

function removeSlot(index) {
    slots.splice(index, 1);
    renderSlots();
}

// =======================
// SUBMIT
// [FIX] Recheck tất cả slot trong list trước khi submit
// =======================
async function submitForm(e, type = 'book') {
    e.preventDefault();

    // [FIX] Thêm xác nhận trước khi khóa
    if (type === 'lock') {
        if (!confirm('Bạn có chắc muốn khóa sân cho các khung giờ này không?')) {
            return;
        }
    }

    let facilityId = "{{ $facility->id }}";
    let container = document.getElementById('hiddenInputs');
    let form = e.target.closest('form');

    container.innerHTML = '';

    let curDate = document.getElementById('booking_date').value;
    let curStart = document.getElementById('start_time').value;
    let curEnd = document.getElementById('end_time').value;

    let hasCurrent = curDate && curStart && curEnd;
    let hasList = slots.length > 0;

    if (!hasCurrent && !hasList) {
        alert('Chọn ít nhất 1 khung giờ!');
        return;
    }

    // [FIX] Recheck tất cả slot trong list trước khi submit
    for (let s of slots) {
        let conflict = await checkDatabaseConflict(s.date, s.start, s.end);
        if (conflict) {
            alert(`❌ Slot ${s.date} ${s.start} - ${s.end} vừa bị người khác đặt! Vui lòng kiểm tra lại.`);
            // Làm mới danh sách booked nếu đang xem cùng ngày
            let curViewDate = document.getElementById('booking_date').value;
            if (curViewDate === s.date) {
                await loadBookedSlots(s.date);
                renderBookedList();
            }
            return;
        }

        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'bookings[]';
        input.value = `${facilityId}|${s.date}|${s.start}|${s.end}|${type}`;
        container.appendChild(input);
    }

    // current input
    if (hasCurrent) {
        if (toMinutes(curStart) >= toMinutes(curEnd)) {
            alert('Giờ không hợp lệ!');
            return;
        }

        let conflict = await checkDatabaseConflict(curDate, curStart, curEnd);
        if (conflict) {
            alert('Slot hiện tại đã bị đặt!');
            return;
        }

        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'bookings[]';
        input.value = `${facilityId}|${curDate}|${curStart}|${curEnd}|${type}`;
        container.appendChild(input);
    }

    // Kiểm tra sau recheck vẫn còn slot để submit không
    if (container.querySelectorAll('input').length === 0) {
        alert('Không có khung giờ hợp lệ để đặt!');
        return;
    }

    form.submit();
}

// =======================
// EVENT
// =======================
document.getElementById('booking_date').addEventListener('change', async function () {
    let date = this.value;
    if (!date) return;

    await loadBookedSlots(date);
    renderBookedList();
});

document.getElementById('start_time').addEventListener('change', checkRealtime);
document.getElementById('end_time').addEventListener('change', checkRealtime);
</script>


    {{-- ========================= GIAO DIỆN PHÒNG / CA ========================= --}}
    @elseif($facility->category->type == 'room')
    <div class="card booking-card shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-calendar-week me-2"></i>Lịch đăng ký theo ca</h5>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('booking.form.multiple') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-modern table-hover align-middle mb-0">
                        <thead class="text-secondary">
                            <tr>
                                <th class="ps-4">Thời gian</th>
                                <th>Sáng <small class="d-block fw-normal text-muted">(7h - 11h)</small></th>
                                <th>Chiều <small class="d-block fw-normal text-muted">(13h - 17h)</small></th>
                                <th>Tối <small class="d-block fw-normal text-muted">(17h - 21h)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weekDays as $day)
                            {{-- [FIX] Tính toán buổi đã qua cho ngày hôm nay --}}
                            @php
                                $isToday    = $day['date']->isToday();
                                $currentHour = now()->hour;
                                $hideMap = [
                                    'morning'   => $isToday && $currentHour >= 11,
                                    'afternoon' => $isToday && $currentHour >= 17,
                                    'evening'   => $isToday && $currentHour >= 21,
                                ];
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ ucwords($day['date']->locale('vi')->isoFormat('dddd')) }}</div>
                                    <small class="text-muted">{{ $day['date']->format('d/m/Y') }}</small>
                                </td>

                                @foreach(['morning','afternoon','evening'] as $session)
                                @php $slot = $day[$session]; @endphp
                                <td>
                                    <div class="p-2 rounded-3 border bg-light shadow-sm text-center" style="min-height: 80px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                                        {{-- [FIX] Nếu buổi đã qua → hiển thị badge "Đã qua" --}}
                                        @if($hideMap[$session])
                                            <span class="badge bg-secondary badge-status rounded-pill">Đã qua</span>
                                        @elseif($slot && $slot->booking && $slot->booking->status != 'cancelled')
                                            {{-- LOCK --}}
                                            @if($slot->booking->status == 'locked')
                                                @if(Auth::check() && Auth::user()->vai_tro == 'admin')
                                                    {{-- [FIX] Thêm xác nhận trước khi mở khóa --}}
                                                    <form action="{{ route('admin.booking.unlock') }}" method="POST"
                                                          onsubmit="return confirm('Bạn có chắc muốn mở khóa ca này không?')">
                                                        @csrf
                                                        <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                                        <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                                                        <input type="hidden" name="session" value="{{ $session }}">
                                                        <button class="btn btn-dark btn-sm rounded-pill px-3">🔓 Mở khóa</button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-dark badge-status rounded-pill">Đã khóa</span>
                                                @endif
                                            {{-- APPROVED / PENDING --}}
                                            @elseif($slot->booking->status == 'approved')
                                                <span class="badge bg-danger badge-status rounded-pill">Đã thuê</span>
                                            @elseif($slot->booking->status == 'pending')
                                                <span class="badge bg-warning text-dark badge-status rounded-pill">Chờ duyệt</span>
                                            @endif
                                        @else
                                            {{-- SLOT TRỐNG --}}
                                            @if(Auth::check() && Auth::user()->vai_tro == 'admin')
                                                {{-- [FIX] Thêm xác nhận trước khi khóa --}}
                                                <form action="{{ route('admin.booking.lock') }}" method="POST"
                                                      onsubmit="return confirm('Bạn có chắc muốn khóa ca này không?')">
                                                    @csrf
                                                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                                    <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                                                    <input type="hidden" name="session" value="{{ $session }}">
                                                    <button class="btn btn-outline-warning btn-sm border-2 fw-bold px-3 rounded-pill">Khóa</button>
                                                </form>
                                            @else
                                                <input type="checkbox" name="bookings[]" class="form-check-input mb-1" 
                                                       value="{{ $facility->id }}|{{ $day['date']->format('Y-m-d') }}|{{ $session }}">
                                                <div class="x-small text-muted mt-1" style="font-size: 0.7rem;">Sẵn sàng</div>
                                            @endif
                                        @endif

                                        {{-- GIÁ --}}
                                        <div class="fw-bold text-success mt-1 small">
                                            @if($session == 'morning') {{ number_format($facility->category->price_morning) }}đ
                                            @elseif($session == 'afternoon') {{ number_format($facility->category->price_afternoon) }}đ
                                            @else {{ number_format($facility->category->price_evening) }}đ @endif
                                        </div>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @unless(Auth::check() && Auth::user()->vai_tro == 'admin')
                <div class="card-footer bg-white border-0 text-center py-4">
                    <button type="submit" class="btn btn-success btn-action px-5 py-2 shadow"
                            onclick="if(document.querySelectorAll('input[name=\'bookings[]\']:checked').length == 0){ alert('Chọn ít nhất 1 ca!'); return false; }">
                        <i class="fas fa-check-double me-2"></i>Tiến hành đặt lịch
                    </button>
                </div>
                @endunless
            </form>
        </div>
    </div>
    @endif

</div>

@endsection