<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            // phân loại
            $table->enum('type', ['room', 'sport']);

            // giá theo 3 buổi (dùng cho phòng)
            $table->integer('price_morning')->nullable();     // 7h - 11h
            $table->integer('price_afternoon')->nullable();   // 13h - 17h
            $table->integer('price_evening')->nullable();     // 17h - 21h

            // giá theo giờ (dùng cho sân thể thao)
            $table->integer('price_hour')->nullable();

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};