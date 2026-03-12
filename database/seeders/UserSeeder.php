<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'ho_ten' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'vai_tro' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ho_ten' => 'Phạm Thị Mỹ Tiên',
                'email' => 'mytien@gmail.com',
                'password' => Hash::make('123456'),
                'vai_tro' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}