<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Category;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {

        /*
        ============================
        SÂN BÓNG ĐÁ
        ============================
        */

        $football = Category::where('name','Sân bóng đá')->first();

        Facility::create([
            'category_id'=>$football->id,
            'name'=>'Sân bóng đá 1',
            'description'=>'Sân bóng đá tiêu chuẩn'
        ]);

        Facility::create([
            'category_id'=>$football->id,
            'name'=>'Sân bóng đá 2',
            'description'=>'Sân bóng đá tiêu chuẩn'
        ]);


        /*
        ============================
        SÂN BÓNG RỔ
        ============================
        */

        $basketball = Category::where('name','Sân bóng rổ')->first();

        Facility::create([
            'category_id'=>$basketball->id,
            'name'=>'Sân bóng rổ 1',
            'description'=>'Sân bóng rổ tiêu chuẩn'
        ]);

        Facility::create([
            'category_id'=>$basketball->id,
            'name'=>'Sân bóng rổ 2',
            'description'=>'Sân bóng rổ tiêu chuẩn'
        ]);


        /*
        ============================
        SÂN BÓNG CHUYỀN
        ============================
        */

        $volleyball = Category::where('name','Sân bóng chuyền')->first();

        Facility::create([
            'category_id'=>$volleyball->id,
            'name'=>'Sân bóng chuyền 1',
            'description'=>'Sân bóng chuyền tiêu chuẩn'
        ]);

        Facility::create([
            'category_id'=>$volleyball->id,
            'name'=>'Sân bóng chuyền 2',
            'description'=>'Sân bóng chuyền tiêu chuẩn'
        ]);


        /*
        ============================
        SÂN TENNIS
        ============================
        */

        $tennis = Category::where('name','Sân tennis')->first();

        Facility::create([
            'category_id'=>$tennis->id,
            'name'=>'Sân tennis 1',
            'description'=>'Sân tennis tiêu chuẩn'
        ]);

        Facility::create([
            'category_id'=>$tennis->id,
            'name'=>'Sân tennis 2',
            'description'=>'Sân tennis tiêu chuẩn'
        ]);


        /*
        ============================
        HỘI TRƯỜNG
        ============================
        */

        $hall600 = Category::where('name','Hội trường 600')->first();

        Facility::create([
            'category_id'=>$hall600->id,
            'name'=>'Hội trường 600A',
            'description'=>'Hội trường 600 chỗ'
        ]);

        Facility::create([
            'category_id'=>$hall600->id,
            'name'=>'Hội trường 600B',
            'description'=>'Hội trường 600 chỗ'
        ]);


        $hall300 = Category::where('name','Hội trường 300')->first();

        Facility::create([
            'category_id'=>$hall300->id,
            'name'=>'Hội trường 300A',
            'description'=>'Hội trường 300 chỗ'
        ]);

        Facility::create([
            'category_id'=>$hall300->id,
            'name'=>'Hội trường 300B',
            'description'=>'Hội trường 300 chỗ'
        ]);


        $hall150 = Category::where('name','Hội trường 150')->first();

        foreach(['Hội trường 150A','Hội trường 150B','Hội trường 150C','Hội trường 150D'] as $room){

            Facility::create([
                'category_id'=>$hall150->id,
                'name'=>$room,
                'description'=>'Hội trường 150 chỗ'
            ]);

        }


        /*
        ============================
        PHÒNG HỌC
        ============================
        */

        $classroom = Category::where('name','Phòng học')->first();

        $blocks = ['A','B','C','D'];

        foreach($blocks as $block){

            for($i=101;$i<=109;$i++){

                Facility::create([
                    'category_id'=>$classroom->id,
                    'name'=>$block.$i,
                    'description'=>'Phòng học nhà '.$block
                ]);

            }

        }


        /*
        ============================
        PHÒNG MÁY
        ============================
        */

        $computer = Category::where('name','Phòng máy')->first();

        for($i=1;$i<=9;$i++){

            Facility::create([
                'category_id'=>$computer->id,
                'name'=>'MT0'.$i,
                'description'=>'Phòng máy vi tính'
            ]);

        }

    }
}