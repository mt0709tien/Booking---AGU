<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class AutoCancelBooking extends Command
{
    protected $signature = 'booking:auto-cancel';
    protected $description = 'Tự động hủy booking sau 1 giờ nếu chưa nhận sân';

    public function handle()
{
    $now = Carbon::now();

    $bookings = Booking::where('status', 'approved')
        ->where('is_checked_in', false)
        ->with(['sportBookings', 'roomBookings'])
        ->get();

    foreach ($bookings as $booking) {

        $sport = $booking->sportBookings->first();
        $room = $booking->roomBookings->first();

        //  XAC ĐINH THOI GIAN BAT ĐAU
        if ($sport) {
            $start = Carbon::parse($sport->booking_date . ' ' . $sport->start_time);
        } elseif ($room) {

            // mapping session -> giờ
            if ($room->session == 'morning') {
                $start = Carbon::parse($room->booking_date . ' 07:00:00');
            } elseif ($room->session == 'afternoon') {
                $start = Carbon::parse($room->booking_date . ' 13:00:00');
            } elseif ($room->session == 'evening') {
                $start = Carbon::parse($room->booking_date . ' 17:00:00');
            } else {
                continue;
            }

        } else {
            continue;
        }

        // QUA 1 GIO CHUA CHECK-IN → HUY
        if ($start->copy()->addHour()->lessThan($now)) {

            $booking->status = 'cancelled';
            $booking->save();
        }
    }

    $this->info('Đã hủy booking quá giờ (tính theo giờ bắt đầu)');
}
}