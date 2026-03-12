<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([

            [
                'name' => 'Sân bóng đá',
                'price_morning' => 300000,
                'price_afternoon' => 320000,
                'price_evening' => 350000
            ],

            [
                'name' => 'Sân bóng rổ',
                'price_morning' => 200000,
                'price_afternoon' => 220000,
                'price_evening' => 250000
            ],

            [
                'name' => 'Sân bóng chuyền',
                'price_morning' => 250000,
                'price_afternoon' => 280000,
                'price_evening' => 300000
            ],

            [
                'name' => 'Sân tennis',
                'price_morning' => 320000,
                'price_afternoon' => 350000,
                'price_evening' => 380000
            ],

            [
                'name' => 'Hội trường 600',
                'price_morning' => 500000,
                'price_afternoon' => 550000,
                'price_evening' => 600000
            ],

            [
                'name' => 'Hội trường 300',
                'price_morning' => 400000,
                'price_afternoon' => 450000,
                'price_evening' => 500000
            ],

            [
                'name' => 'Hội trường 150',
                'price_morning' => 300000,
                'price_afternoon' => 350000,
                'price_evening' => 400000
            ],

            [
                'name' => 'Phòng học',
                'price_morning' => 250000,
                'price_afternoon' => 300000,
                'price_evening' => 320000
            ],

            [
                'name' => 'Phòng máy',
                'price_morning' => 320000,
                'price_afternoon' => 400000,
                'price_evening' => 450000
            ],

        ]);
    }
}