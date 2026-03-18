<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            // ✅ CHO PHÉP NULL (QUAN TRỌNG)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();

            $table->date('booking_date');

            $table->string('session'); // morning / afternoon / evening

            $table->string('fullname');
            $table->string('phone');

            $table->integer('price');

            $table->string('payment_method');

            // ✅ TRẠNG THÁI
            $table->string('status')->default('pending');
            // pending | approved | rejected | cancelled | locked

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }

};