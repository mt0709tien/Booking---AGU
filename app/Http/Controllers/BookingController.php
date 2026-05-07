<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\SportBooking; 
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\BookingNotification;

class BookingController extends Controller
{
    private function isAdmin()
    {
        return auth()->check() && strtolower(trim(auth()->user()->vai_tro)) === 'admin';
    }

    /*
    |------------------------------------------------------------------
    | Trang hiển thị lịch
    |------------------------------------------------------------------
    */
    public function create(Facility $facility)
    {
        Carbon::setLocale('vi');
        $weekDays = [];

        for ($i = 0; $i < 7; $i++) {

            $date = Carbon::today()->addDays($i);

            $getSlot = function($session) use ($facility, $date) {
                return RoomBooking::where('facility_id', $facility->id)
                    ->whereDate('booking_date', $date)
                    ->where('session', $session)
                    ->whereHas('booking', function ($q) {
                        $q->whereIn('status', ['pending','approved','locked']);
                    })
                    ->first();
            };

            $weekDays[] = [
                'date' => $date,
                'morning' => $getSlot('morning'),
                'afternoon' => $getSlot('afternoon'),
                'evening' => $getSlot('evening'),
            ];
        }

        return view('booking.create', compact('facility', 'weekDays'));
    }

    /*
    |------------------------------------------------------------------
    | FORM MULTIPLE
    |------------------------------------------------------------------
    */
   public function formMultiple(Request $request)
{
    $items = $request->bookings;

    if (!$items) {
        return back()->with('error', 'Vui lòng chọn ít nhất 1 ca!');
    }

    // Nếu tất cả item là lock và là admin → bỏ qua form, lock luôn
    $allLock = collect($items)->every(fn($i) => str_ends_with($i, '|lock'));

    if ($allLock && auth()->check() && strtolower(trim(auth()->user()->vai_tro)) === 'admin') {

        $facilityId = explode('|', $items[0])[0] ?? null;

        $facility = Facility::findOrFail($facilityId);

        // Tạo booking locked trực tiếp
        $groupId = \Illuminate\Support\Str::uuid();

        \DB::transaction(function () use ($items, $facility, $groupId) {

            $booking = Booking::create([
                'group_id'       => $groupId,
                'user_id'        => auth()->id(),
                'fullname'       => auth()->user()->ho_ten,
                'phone'          => '---',
                'price'          => 0,
                'payment_method' => 'admin_lock',
                'status'         => 'locked',
            ]);

            foreach ($items as $item) {

                $parts = explode('|', $item);
                // sport: facility_id|date|start|end|lock  → 5 phần
                // room:  facility_id|date|session|lock    → 4 phần

                if (count($parts) === 5) {
                    // sport
                    $startTime = strlen($parts[2]) == 5 ? $parts[2] . ':00' : $parts[2];
                    $endTime   = strlen($parts[3]) == 5 ? $parts[3] . ':00' : $parts[3];

                    SportBooking::create([
                        'booking_id'   => $booking->id,
                        'facility_id'  => $parts[0],
                        'booking_date' => $parts[1],
                        'start_time'   => $startTime,
                        'end_time'     => $endTime,
                    ]);

                } elseif (count($parts) === 4) {
                    // room
                    RoomBooking::create([
                        'booking_id'   => $booking->id,
                        'facility_id'  => $parts[0],
                        'booking_date' => $parts[1],
                        'session'      => $parts[2],
                    ]);
                }
            }
        });

        return redirect()->route('booking.create', $facilityId)
            ->with('success', 'Đã khóa sân thành công!');
    }

    return view('booking.form-multiple', compact('items'));
}

    /*
    |------------------------------------------------------------------
    | LƯU MULTIPLE
    |------------------------------------------------------------------
    */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required',
            'bookings' => 'required|array'
        ]);

        $groupId = Str::uuid();

        return \DB::transaction(function () use ($request, $groupId) {

            $totalPrice = 0;
            $validItems = [];
            $hasConflict = false;

            foreach ($request->bookings as $item) {

                $parts = explode('|', $item);
                if (count($parts) < 3) continue;

                $facility = Facility::find($parts[0]);
                if (!$facility) continue;

                $category = $facility->category;

                // =========================
                // ===== PHÒNG =====
                // =========================
                if ($category->type === 'room') {

                    list($facility_id, $date, $session) = $parts;

                    // [FIX 1] Kiểm tra lại xem có type=lock không (index 3)
                    $type = $parts[3] ?? 'book';

                    // [FIX 2] Chỉ admin mới được lock
                    if ($type === 'lock' && !$this->isAdmin()) {
                        continue;
                    }

                    $exists = RoomBooking::where('facility_id', $facility_id)
                        ->whereDate('booking_date', $date)
                        ->where('session', $session)
                        ->whereHas('booking', function ($q) {
                            $q->whereIn('status', ['pending','approved','locked']);
                        })
                        ->lockForUpdate() // [FIX 3] Chống race condition
                        ->exists();

                    if ($exists) {
                        $hasConflict = true;
                        continue;
                    }

                    $price = match($session) {
                        'morning' => $category->price_morning,
                        'afternoon' => $category->price_afternoon,
                        default => $category->price_evening
                    };

                    $totalPrice += $price;

                    $validItems[] = [
                        'type' => 'room',
                        'facility_id' => $facility_id,
                        'date' => $date,
                        'session' => $session,
                        'booking_type' => $type, // [FIX 1] Lưu lại type để dùng khi tạo booking
                    ];
                }

                // =========================
                // ===== SÂN =====
                // =========================
                else {

                    // [FIX 1] Parse đúng 5 phần tử, lấy type ở index 4
                    if (count($parts) < 4) continue;
                    $facility_id = $parts[0];
                    $date        = $parts[1];
                    $start       = $parts[2];
                    $end         = $parts[3];
                    $type        = $parts[4] ?? 'book';

                    // [FIX 2] Chỉ admin mới được lock
                    if ($type === 'lock' && !$this->isAdmin()) {
                        continue;
                    }

                    $startTime = strlen($start) == 5 ? $start . ':00' : $start;
                    $endTime   = strlen($end) == 5 ? $end . ':00' : $end;

                    if (strtotime($endTime) <= strtotime($startTime)) continue;

                    // Check trùng trong request
                    $isOverlap = false;

                    foreach ($validItems as $v) {

                        if ($v['type'] !== 'sport') continue;

                        if ($v['facility_id'] != $facility_id || $v['date'] != $date) continue;

                        if (
                            strtotime($startTime) < strtotime($v['end_time']) &&
                            strtotime($endTime) > strtotime($v['start_time'])
                        ) {
                            $isOverlap = true;
                            break;
                        }
                    }

                    if ($isOverlap) {
                        $hasConflict = true;
                        continue;
                    }

                    // [FIX 3] Check trùng DB + lockForUpdate chống race condition
                    $exists = SportBooking::where('facility_id', $facility_id)
                        ->where('booking_date', $date)
                        ->whereHas('booking', function ($q) {
                            $q->whereIn('status', ['pending','approved','locked']);
                        })
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<', $endTime)
                              ->where('end_time', '>', $startTime);
                        })
                        ->lockForUpdate() // [FIX 3] Chống race condition
                        ->exists();

                    if ($exists) {
                        $hasConflict = true;
                        continue;
                    }

                    // Tính tiền
                    $hours = (strtotime($endTime) - strtotime($startTime)) / 3600;
                    $price = $hours * $category->price_hour;

                    $totalPrice += $price;

                    $validItems[] = [
                        'type' => 'sport',
                        'facility_id' => $facility_id,
                        'date' => $date,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'booking_type' => $type, // [FIX 1] Lưu lại type
                    ];
                }
            }

            // Tất cả bị trùng
            if (empty($validItems)) {
                return redirect()->route('booking.create', $request->bookings[0] ?? null)
                    ->with('error', 'Tất cả khung giờ đã bị trùng!');
            }

            // Có trùng nhưng vẫn còn slot hợp lệ
            if ($hasConflict) {
                session()->flash('error', 'Một số khung giờ đã bị trùng và bị bỏ qua!');
            }

            // =========================
            // TẠO BOOKING
            // =========================

            // [FIX 1] Nếu tất cả item là lock thì status = locked, ngược lại = pending
            $allLock = collect($validItems)->every(fn($i) => ($i['booking_type'] ?? 'book') === 'lock');
            $bookingStatus = $allLock ? 'locked' : 'pending';

            $booking = Booking::create([
                'group_id' => $groupId,
                'user_id' => auth()->check() ? auth()->id() : null,
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => $bookingStatus, // [FIX 1] Dùng đúng status
            ]);

            foreach ($validItems as $item) {

                if ($item['type'] === 'room') {
                    RoomBooking::create([
                        'booking_id' => $booking->id,
                        'facility_id' => $item['facility_id'],
                        'booking_date' => $item['date'],
                        'session' => $item['session'],
                    ]);
                }

                if ($item['type'] === 'sport') {
                    SportBooking::create([
                        'booking_id' => $booking->id,
                        'facility_id' => $item['facility_id'],
                        'booking_date' => $item['date'],
                        'start_time' => $item['start_time'],
                        'end_time' => $item['end_time'],
                    ]);
                }
            }

            // Chỉ gửi notification khi không phải admin lock
            if (!$allLock) {
                $admins = User::where('vai_tro', 'admin')->get();

                foreach ($admins as $admin) {
                    $admin->notify(new BookingNotification(
                        'Đặt sân mới',
                        "Khách hàng {$booking->fullname} vừa đặt sân.",
                        route('admin.bookings')
                    ));
                }
            }

            // SAU:
if (auth()->check()) {
    if ($allLock) {
        // Lấy facility_id từ validItem đầu tiên để redirect về đúng trang sân
        $facilityId = $validItems[0]['facility_id'] ?? null;
        return redirect()->route('booking.create', $facilityId)
            ->with('success', 'Đã khóa sân thành công!');
    }
    return redirect()->route('booking.my')
        ->with('success', 'Đặt lịch thành công!');
}

            return redirect()->route('booking.home')
                ->with('success', 'Đặt lịch thành công! Chúng tôi sẽ liên hệ với bạn sớm.');
        });
    }

    /*
    |------------------------------------------------------------------
    | Lịch của tôi
    |------------------------------------------------------------------
    */
    public function myBookings()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('booking.my', compact('bookings'));
    }

    /*
    |------------------------------------------------------------------
    | Hủy
    |------------------------------------------------------------------
    */
  // User gửi yêu cầu hủy (pending → hủy luôn, approved → chờ admin)
public function cancel($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->user_id != auth()->id()) {
        abort(403);
    }

    if ($booking->is_checked_in) {
        return back()->with('error', 'Không thể hủy sau khi đã nhận sân.');
    }

    // pending → hủy luôn không cần admin duyệt
    if ($booking->status === 'pending') {
        $booking->status = 'cancelled';
        $booking->save();
        return back()->with('success', 'Đã hủy thành công!');
    }

    // approved → gửi yêu cầu chờ admin
    if ($booking->status === 'approved') {
        $booking->status = 'cancel_requested';
        $booking->save();
        return back()->with('success', 'Đã gửi yêu cầu hủy, chờ admin xác nhận.');
    }

    return back()->with('error', 'Không thể hủy ở trạng thái này.');
}

// Admin đồng ý hủy
public function approveCancel($id)
{
    $booking = Booking::findOrFail($id);
    $booking->status = 'cancelled';
    $booking->save();
    return back()->with('success', 'Đã xác nhận hủy booking.');
}

// Admin từ chối hủy
public function rejectCancel($id)
{
    $booking = Booking::findOrFail($id);
    $booking->status = 'approved';
    $booking->save();
    return back()->with('success', 'Đã từ chối yêu cầu hủy.');
}
    /*
    |------------------------------------------------------------------
    | Thanh toán
    |------------------------------------------------------------------
    */
    public function togglePayment(Request $request)
    {
        $booking = Booking::findOrFail($request->id);

        $booking->is_paid = !$booking->is_paid;
        $booking->paid_at = $booking->is_paid ? now() : null;

        $booking->save();

        return back();
    }

    public function checkin(Request $request)
    {
        $booking = Booking::findOrFail($request->id);

        if ($booking->status !== 'approved') {
            return back()->with('error', 'Chỉ được nhận sân khi đã duyệt!');
        }

        $booking->is_checked_in = true;
        $booking->checked_in_at = now();
        $booking->save();

        return back()->with('success', 'Đã nhận sân!');
    }

    public function index(Request $request)
    {
        $query = Booking::query();

        if ($request->facility) {
            $query->whereHas('roomBookings.facility', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->facility . '%');
            })
            ->orWhereHas('sportBookings.facility', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->facility . '%');
            });
        }

        if ($request->date) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('roomBookings', function ($r) use ($request) {
                    $r->whereDate('booking_date', $request->date);
                })
                ->orWhereHas('sportBookings', function ($s) use ($request) {
                    $s->whereDate('booking_date', $request->date);
                });
            });
        }

        $bookings = $query->latest()->get();

        return view('admin.booking.index', compact('bookings'));
    }

    public function review($id)
    {
        $booking = Booking::findOrFail($id);
        return view('booking.review', compact('booking'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|min:1|max:5',
            'comment' => 'nullable|max:500'
        ]);

        return redirect()->route('booking.my')
            ->with('success', 'Cảm ơn bạn đã gửi đánh giá.');
    }

    public function checkSlot(Request $request)
    {
        $exists = SportBooking::where('facility_id', $request->facility_id)
            ->whereDate('booking_date', $request->date)
            ->whereHas('booking', function ($q) {
                $q->whereIn('status', ['pending','approved','locked']);
            })
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end)
                  ->where('end_time', '>', $request->start);
            })
            ->exists();

        return response()->json(['conflict' => $exists]);
    }

    public function getBookedSlots(Request $request)
    {
        $facilityId = $request->facility_id;
        $date = $request->date;

        $slots = SportBooking::where('facility_id', $facilityId)
            ->whereDate('booking_date', $date)
            ->whereHas('booking', function ($q) {
                $q->whereIn('status', ['approved', 'locked', 'pending']);
            })
            ->get()
            ->map(function ($s) {
                return [
                    'start_time' => substr($s->start_time, 0, 5),
                    'end_time'   => substr($s->end_time, 0, 5),
                ];
            });

        return response()->json([
            'slots' => $slots
        ]);
    }
}