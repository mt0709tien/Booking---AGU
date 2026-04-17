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
       Schema::create('invoice_details', function (Blueprint $table) {
    $table->id();

    $table->foreignId('invoice_id')
        ->constrained('invoices')
        ->cascadeOnDelete();

    // 🔥 thêm FK
    $table->foreignId('facility_id')
        ->nullable()
        ->constrained('facilities')
        ->nullOnDelete();

    $table->string('ten_dich_vu');

    $table->unsignedInteger('so_luong');

    $table->decimal('don_gia', 10, 2)->unsigned();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
