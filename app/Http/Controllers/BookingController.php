<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\SportBooking; 
use Carbon\Carbon;

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
        $hasConflict = false; // 🔥 thêm

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

                $exists = RoomBooking::where('facility_id', $facility_id)
                    ->whereDate('booking_date', $date)
                    ->where('session', $session)
                    ->whereHas('booking', function ($q) {
                        $q->whereIn('status', ['pending','approved','locked']);
                    })
                    ->exists();

                if ($exists) {
                    $hasConflict = true; // 🔥 đánh dấu trùng
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
                ];
            }

            // =========================
            // ===== SÂN =====
            // =========================
            else {

                list($facility_id, $date, $start, $end) = $parts;

                $startTime = strlen($start) == 5 ? $start . ':00' : $start;
                $endTime   = strlen($end) == 5 ? $end . ':00' : $end;

                if (strtotime($endTime) <= strtotime($startTime)) continue;

                // 🔥 CHECK TRÙNG TRONG REQUEST
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
                    $hasConflict = true; // 🔥 đánh dấu trùng
                    continue;
                }

                // 🔥 CHECK TRÙNG DB
                $exists = SportBooking::where('facility_id', $facility_id)
                    ->where('booking_date', $date)
                    ->whereHas('booking', function ($q) {
                        $q->whereIn('status', ['pending','approved','locked']);
                    })
                    ->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    })
                    ->exists();

                if ($exists) {
                    $hasConflict = true; // 🔥 đánh dấu trùng
                    continue;
                }

                // 💰 tính tiền
                $hours = (strtotime($endTime) - strtotime($startTime)) / 3600;
                $price = $hours * $category->price_hour;

                $totalPrice += $price;

                $validItems[] = [
                    'type' => 'sport',
                    'facility_id' => $facility_id,
                    'date' => $date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
            }
        }

        // ❌ tất cả bị trùng
        if (empty($validItems)) {
            return redirect()->route('booking.create', $request->bookings[0] ?? null)
                ->with('error', 'Tất cả khung giờ đã bị trùng!');
        }

        // ⚠️ có trùng nhưng vẫn còn slot hợp lệ
        if ($hasConflict) {
            session()->flash('error', 'Một số khung giờ đã bị trùng và bị bỏ qua!');
        }

        // =========================
        // TẠO BOOKING
        // =========================
        $booking = Booking::create([
            'group_id' => $groupId,
            'user_id' => auth()->check() ? auth()->id() : null,
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'status' => 'pending'
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

        if (auth()->check()) {
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
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id != auth()->id()) {
            abort(403);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Đã hủy!');
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

    // chỉ cho checkin khi đã duyệt
    if ($booking->status !== 'approved') {
        return back()->with('error', 'Chỉ được nhận sân khi đã duyệt!');
    }

    // cập nhật
    $booking->is_checked_in = true;
    $booking->checked_in_at = now();
    $booking->save();

    return back()->with('success', 'Đã nhận sân!');
}
public function index(Request $request)
{
    $query = Booking::query();

    // lọc theo tên cơ sở
    if ($request->facility) {
        $query->whereHas('roomBookings.facility', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->facility . '%');
        })
        ->orWhereHas('sportBookings.facility', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->facility . '%');
        });
    }

    // lọc theo ngày
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

}