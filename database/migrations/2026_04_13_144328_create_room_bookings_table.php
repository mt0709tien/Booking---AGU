<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_bookings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('booking_id')
        ->constrained('bookings')
        ->cascadeOnDelete();

    $table->foreignId('facility_id')
        ->constrained('facilities')
        ->cascadeOnDelete();

    $table->date('booking_date');

    $table->enum('session', ['morning', 'afternoon', 'evening']);

    $table->timestamps();

    $table->unique([
        'facility_id',
        'booking_date',
        'session'
    ], 'unique_room_booking');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};