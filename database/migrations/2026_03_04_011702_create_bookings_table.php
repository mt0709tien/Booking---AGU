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

// User đặt lịch
$table->foreignId('user_id')->constrained()->cascadeOnDelete();

// Cơ sở vật chất
$table->foreignId('facility_id')->constrained()->cascadeOnDelete();

// Ngày đặt
$table->date('booking_date');

// Buổi
$table->string('session'); // morning / afternoon / evening

// Thông tin người đặt
$table->string('fullname');
$table->string('phone');

// Giá
$table->integer('price');

// Thanh toán
$table->string('payment_method');

$table->timestamps();

});
}

public function down(): void
{
Schema::dropIfExists('bookings');
}

};