<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // tạo index thường thay thế
        DB::statement("
            CREATE INDEX room_booking_facility_id_index
            ON room_bookings(facility_id)
        ");

        // xóa unique
        DB::statement("
            ALTER TABLE room_bookings
            DROP INDEX unique_room_booking
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE room_bookings
            ADD UNIQUE unique_room_booking
            (facility_id, booking_date, session)
        ");

        DB::statement("
            DROP INDEX room_booking_facility_id_index
            ON room_bookings
        ");
    }
};