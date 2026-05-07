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
        $football = Category::where('name','Sân bóng đá')->firstOrFail();

        foreach(['Sân bóng đá 1','Sân bóng đá 2'] as $name){
            Facility::create([
                'category_id'=>$football->id,
                'name'=>$name,
                'description'=>'Sân bóng đá tiêu chuẩn',
                'image'=>'BD.jpg'
            ]);
        }


        /*
        ============================
        SÂN BÓNG RỔ
        ============================
        */
        $basketball = Category::where('name','Sân bóng rổ')->firstOrFail();

        foreach(['Sân bóng rổ 1','Sân bóng rổ 2'] as $name){
            Facility::create([
                'category_id'=>$basketball->id,
                'name'=>$name,
                'description'=>'Sân bóng rổ tiêu chuẩn',
                'image'=>'BR.jpg'
            ]);
        }


        /*
        ============================
        SÂN BÓNG CHUYỀN
        ============================
        */
        $volleyball = Category::where('name','Sân bóng chuyền')->firstOrFail();

        foreach(['Sân bóng chuyền 1','Sân bóng chuyền 2'] as $name){
            Facility::create([
                'category_id'=>$volleyball->id,
                'name'=>$name,
                'description'=>'Sân bóng chuyền tiêu chuẩn',
                'image'=>'BC.jpg'
            ]);
        }


        /*
        ============================
        SÂN TENNIS
        ============================
        */
        $tennis = Category::where('name','Sân tennis')->firstOrFail();

        foreach(['Sân tennis 1','Sân tennis 2'] as $name){
            Facility::create([
                'category_id'=>$tennis->id,
                'name'=>$name,
                'description'=>'Sân tennis tiêu chuẩn',
                'image'=>'TN.jpg' 
            ]);
        }


        /*
        ============================
        HỘI TRƯỜNG
        ============================
        */
        $hall600 = Category::where('name','Hội trường 600')->firstOrFail();

        foreach(['Hội trường 600A','Hội trường 600B'] as $name){
            Facility::create([
                'category_id'=>$hall600->id,
                'name'=>$name,
                'description'=>'Hội trường 600 chỗ',
                'image'=>'HT.jpg'
            ]);
        }

        $hall300 = Category::where('name','Hội trường 300')->firstOrFail();

        foreach(['Hội trường 300A','Hội trường 300B'] as $name){
            Facility::create([
                'category_id'=>$hall300->id,
                'name'=>$name,
                'description'=>'Hội trường 300 chỗ',
                'image'=>'HT300.jpg'
            ]);
        }

        $hall150 = Category::where('name','Hội trường 150')->firstOrFail();

        foreach(['Hội trường 150A','Hội trường 150B','Hội trường 150C','Hội trường 150D'] as $name){
            Facility::create([
                'category_id'=>$hall150->id,
                'name'=>$name,
                'description'=>'Hội trường 150 chỗ',
                'image'=>'HT150.jpg'
            ]);
        }


        /*
        ============================
        PHÒNG HỌC
        ============================
        */
        $classroom = Category::where('name','Phòng học')->firstOrFail();

        foreach(['Phòng A','Phòng B','Phòng C','Phòng D'] as $block){
            for($i=101;$i<=109;$i++){
                Facility::create([
                    'category_id'=>$classroom->id,
                    'name'=>$block.$i,
                    'description'=>'Phòng học nhà '.$block, 
                    'image'=>'PH.jpg'
                ]);
            }
        }


        /*
        ============================
        PHÒNG MÁY
        ============================
        */
        $computer = Category::where('name','Phòng máy')->firstOrFail();

        for($i=1;$i<=9;$i++){
            Facility::create([
                'category_id'=>$computer->id,
                'name'=>'MT0'.$i,
                'description'=>'Phòng máy vi tính',
                'image'=>'PMT.jpg'
            ]);
        }

    }
}