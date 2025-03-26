<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Ball Mastery Starter Kit',
                'price' => 791.29,
                'category' => 'Equipment',
                'description' => '',
                'image' => 'https://fpro.com/cdn/shop/files/HOVER3_football_1080x.png?v=1741611741',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Veo Cam 3 Camera 5G - Green',
                'price' => 7514.69,
                'category' => 'Equipment',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/231/200231/795x1035/veo-002_9.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Substitution board Yakimasport - Black',
                'price' => 5752.96,
                'category' => 'Equipment',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/416/155416/795x1035/100305_1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stopwatch Select',
                'price' => 94.51,
                'category' => 'Equipment',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/500/131500/795x1035/7491500222_1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
