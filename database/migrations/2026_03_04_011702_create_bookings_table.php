<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('facility_id')
        ->constrained('facilities')
        ->onDelete('cascade');

    $table->string('fullname');
    $table->string('phone');

    $table->date('booking_date');
    $table->string('session');

    $table->string('payment_method');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
