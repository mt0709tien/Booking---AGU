<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sport_bookings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('booking_id')
        ->constrained('bookings')
        ->cascadeOnDelete();

    $table->foreignId('facility_id')
        ->constrained('facilities')
        ->cascadeOnDelete();

    $table->date('booking_date');

    $table->time('start_time');
    $table->time('end_time');

    $table->timestamps();

    $table->index(['facility_id', 'booking_date']);

    $table->unique([
        'facility_id',
        'booking_date',
        'start_time',
        'end_time'
    ], 'unique_sport_booking_exact');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_bookings');
    }
};