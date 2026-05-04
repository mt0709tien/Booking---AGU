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
                                    <input type="date" id="booking_date" class="form-control border-start-0" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            {{-- GIỜ BẮT ĐẦU --}}
                            <div class="col-md-3">
                                <label class="form-label">Giờ bắt đầu</label>
                                <select id="start_time" class="form-select shadow-sm">
                                    <option value="">--:--</option>
                                    @for($h = 7; $h <= 21; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                        @if($h < 21)
                                            <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>

                            {{-- GIỜ KẾT THÚC --}}
                            <div class="col-md-3">
                                <label class="form-label">Giờ kết thúc</label>
                                <select id="end_time" class="form-select shadow-sm">
                                    <option value="">--:--</option>
                                    @for($h = 7; $h <= 21; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                        @if($h < 21)
                                            <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
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
                            <span class="text-muted small fw-bold text-uppercase mb-1">Đơn giá tham khảo</span>
                            <div class="price-text fw-bold">
                                {{ number_format($facility->category->price_hour) }} VNĐ <span class="text-muted small">/ giờ</span>
                            </div>
                        </div>

                        <div class="text-center d-flex justify-content-center gap-3">
                            @unless(Auth::check() && Auth::user()->vai_tro == 'admin')
                                <button type="submit" class="btn btn-success btn-action px-5 py-2 shadow" onclick="submitForm(event, 'book')">
                                    <i class="fas fa-shopping-cart me-2"></i>Xác nhận đặt sân
                                </button>
                            @endunless

                            @if(Auth::check() && Auth::user()->vai_tro == 'admin')
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

    {{-- JS của bạn giữ nguyên --}}
    <script>
let slots = [];
let isChecking = false;

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
// CHECK TRÙNG DATABASE
// =======================
async function checkDatabaseConflict(date, start, end) {
    let facilityId = "{{ $facility->id }}";
    try {
        let res = await fetch(`/check-slot?facility_id=${facilityId}&date=${date}&start=${start}&end=${end}`);
        let data = await res.json();
        return data.conflict;
    } catch (e) {
        console.error("Lỗi check database:", e);
        return false;
    }
}

// =======================
// REALTIME CHECK (Khi người dùng thay đổi input)
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

    // Check trùng với các slot đã nhấn "Thêm" ở dưới
    for (let s of slots) {
        if (s.date === date && isOverlap(start, end, s.start, s.end)) {
            alert('❌ Khung giờ này trùng với khung giờ bạn đã thêm vào danh sách bên dưới!');
            document.getElementById('end_time').value = '';
            return;
        }
    }

    if (isChecking) return;
    isChecking = true;

    let conflict = await checkDatabaseConflict(date, start, end);
    if (conflict) {
        alert('❌ Khung giờ này đã có người khác đặt trong hệ thống!');
        document.getElementById('end_time').value = '';
    }

    isChecking = false;
}

// =======================
// NÚT THÊM SLOT
// =======================
async function addSlot() {
    let date = document.getElementById('booking_date').value;
    let start = document.getElementById('start_time').value;
    let end = document.getElementById('end_time').value;

    if (!date || !start || !end) {
        alert('Vui lòng chọn đầy đủ ngày, giờ bắt đầu và giờ kết thúc!');
        return;
    }

    if (toMinutes(start) >= toMinutes(end)) {
        alert('Giờ không hợp lệ!');
        return;
    }

    // Kiểm tra trùng local
    for (let s of slots) {
        if (s.date === date && isOverlap(start, end, s.start, s.end)) {
            alert('Khung giờ trùng với slot đã thêm!');
            return;
        }
    }

    // Kiểm tra trùng database
    let conflict = await checkDatabaseConflict(date, start, end);
    if (conflict) {
        alert('Khung giờ đã được đặt!');
        return;
    }

    slots.push({ date, start, end });
    renderSlots();

    // Reset khung chọn giờ (giữ lại ngày để tiện chọn tiếp)
    document.getElementById('start_time').value = '';
    document.getElementById('end_time').value = '';
}

function renderSlots() {
    let html = '';
    slots.forEach((s, i) => {
        html += `
            <div class="slot-item p-3 mb-2 d-flex justify-content-between align-items-center animate__animated animate__fadeIn">
                <div>
                    <span class="badge bg-primary me-2">${s.date}</span>
                    <span class="fw-bold">${s.start} - ${s.end}</span>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeSlot(${i})">
                    <i class="fas fa-times"></i>
                </button>
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
// NÚT XÁC NHẬN / KHÓA SÂN
// =======================
async function submitForm(e, type = 'book') {
    e.preventDefault();

    let facilityId = "{{ $facility->id }}";
    let container = document.getElementById('hiddenInputs');
    let form = e.target.closest('form');
    container.innerHTML = '';

    let curDate = document.getElementById('booking_date').value;
    let curStart = document.getElementById('start_time').value;
    let curEnd = document.getElementById('end_time').value;

    let hasCurrentInput = curDate && curStart && curEnd;
    let hasSlotsInList = slots.length > 0;

    // YÊU CẦU: Chỉ thông báo khi cả 2 đều trống
    if (!hasCurrentInput && !hasSlotsInList) {
        alert('Vui lòng chọn hoặc thêm ít nhất một khung giờ!');
        return;
    }

    // 1. Xử lý các slot đã có trong danh sách (dưới khung chọn)
    for (let s of slots) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'bookings[]';
        input.value = `${facilityId}|${s.date}|${s.start}|${s.end}|${type}`;
        container.appendChild(input);
    }

    // 2. Xử lý khung giờ đang chọn ở trên (nếu có và chưa nhấn Thêm)
    if (hasCurrentInput) {
        // Kiểm tra trùng với danh sách bên dưới
        for (let s of slots) {
            if (s.date === curDate && isOverlap(curStart, curEnd, s.start, s.end)) {
                alert('Khung giờ đang chọn trùng với danh sách đã thêm. Vui lòng kiểm tra lại!');
                container.innerHTML = '';
                return;
            }
        }

        // Kiểm tra trùng database lần cuối cho khung đang chọn
        let conflict = await checkDatabaseConflict(curDate, curStart, curEnd);
        if (conflict) {
            alert('Khung giờ đang chọn đã được đặt hoặc khóa. Vui lòng chọn giờ khác!');
            container.innerHTML = '';
            return;
        }

        // Nếu hợp lệ thì thêm vào input gửi đi luôn
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'bookings[]';
        input.value = `${facilityId}|${curDate}|${curStart}|${curEnd}|${type}`;
        container.appendChild(input);
    }

    // Cuối cùng: Submit form
    form.submit();
}

// Event listeners
document.getElementById('booking_date').addEventListener('change', checkRealtime);
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
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ ucwords($day['date']->locale('vi')->isoFormat('dddd')) }}</div>
                                    <small class="text-muted">{{ $day['date']->format('d/m/Y') }}</small>
                                </td>

                                @foreach(['morning','afternoon','evening'] as $session)
                                @php $slot = $day[$session]; @endphp
                                <td>
                                    <div class="p-2 rounded-3 border bg-light shadow-sm text-center" style="min-height: 80px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                                        @if($slot && $slot->booking && $slot->booking->status != 'cancelled')
                                            {{-- LOCK --}}
                                            @if($slot->booking->status == 'locked')
                                                @if(Auth::check() && Auth::user()->vai_tro == 'admin')
                                                    <form action="{{ route('admin.booking.unlock') }}" method="POST">
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
                                                <form action="{{ route('admin.booking.lock') }}" method="POST">
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