<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([

            // ===== SÂN THỂ THAO =====
            [
                'name' => 'Sân bóng đá',
                'type' => 'sport',
                'price_morning' => null,
                'price_afternoon' => null,
                'price_evening' => null,
                'price_hour' => 100000,
            ],

            [
                'name' => 'Sân bóng rổ',
                'type' => 'sport',
                'price_morning' => null,
                'price_afternoon' => null,
                'price_evening' => null,
                'price_hour' => 80000,
            ],

            [
                'name' => 'Sân bóng chuyền',
                'type' => 'sport',
                'price_morning' => null,
                'price_afternoon' => null,
                'price_evening' => null,
                'price_hour' => 90000,
            ],

            [
                'name' => 'Sân tennis',
                'type' => 'sport',
                'price_morning' => null,
                'price_afternoon' => null,
                'price_evening' => null,
                'price_hour' => 120000,
            ],

            // ===== PHÒNG =====
            [
                'name' => 'Hội trường 600',
                'type' => 'room',
                'price_morning' => 500000,
                'price_afternoon' => 550000,
                'price_evening' => 600000,
                'price_hour' => null,
            ],

            [
                'name' => 'Hội trường 300',
                'type' => 'room',
                'price_morning' => 400000,
                'price_afternoon' => 450000,
                'price_evening' => 500000,
                'price_hour' => null,
            ],

            [
                'name' => 'Hội trường 150',
                'type' => 'room',
                'price_morning' => 300000,
                'price_afternoon' => 350000,
                'price_evening' => 400000,
                'price_hour' => null,
            ],

            [
                'name' => 'Phòng học',
                'type' => 'room',
                'price_morning' => 250000,
                'price_afternoon' => 300000,
                'price_evening' => 320000,
                'price_hour' => null,
            ],

            [
                'name' => 'Phòng máy',
                'type' => 'room',
                'price_morning' => 320000,
                'price_afternoon' => 400000,
                'price_evening' => 450000,
                'price_hour' => null,
            ],

        ]);
    }
}