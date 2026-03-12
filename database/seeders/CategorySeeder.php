<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['name' => 'Sân bóng đá', 'price' => 320000],
            ['name' => 'Sân bóng rổ', 'price' => 220000],
            ['name' => 'Sân bóng chuyền', 'price' => 280000],
            ['name' => 'Sân tennis', 'price' => 350000],
            ['name' => 'Hội trường 600', 'price' => 550000],
            ['name' => 'Hội trường 300', 'price' => 450000],
            ['name' => 'Hội trường 150', 'price' => 350000],
            ['name' => 'Phòng học', 'price' => 300000],
            ['name' => 'Phòng máy', 'price' => 400000],
        ]);
    }
}