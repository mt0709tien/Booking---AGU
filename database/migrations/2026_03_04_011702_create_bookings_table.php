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

    $table->string('group_id')->nullable();

    $table->foreignId('user_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->string('fullname');
    $table->string('phone');

    $table->unsignedBigInteger('price');

    $table->string('payment_method');

    $table->boolean('is_paid')->default(false);
    $table->timestamp('paid_at')->nullable();

    $table->boolean('is_checked_in')->default(false);
    $table->timestamp('checked_in_at')->nullable();

    $table->enum('status', [
        'pending',
        'approved',
        'rejected',
        'cancelled',
        'locked'
    ])->default('pending');

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};