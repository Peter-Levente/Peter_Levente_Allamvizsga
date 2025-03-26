<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'Adidas Real Madrid 24/25 Presentation Sweatshirt - Navy blue',
            'price' => 423.05,
            'category' => 'Clothings',
            'description' => '',
            'image' => 'https://gfx.r-gol.com/media/res/products/948/198948/795x1035/je4198_1.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
