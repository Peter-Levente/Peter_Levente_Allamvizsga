<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'FK Csíkszereda Home Jersey',
                'price' => 93.00,
                'category' => 'Jerseys',
                'description' => '',
                'image' => 'https://soldigo.azureedge.net/images/15861/zaxa2brkvx.png',
            ],
            [
                'name' => 'Nike Tottenham Hotspur 23/24 Home Vapor Match Jersey',
                'price' => 694.08,
                'category' => 'Jerseys',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/680/162680/465x605/dx2625-101_1.jpg',
            ],
            [
                'name' => 'Nike FC Barcelona 23/24 Home Stadium Jersey',
                'price' => 597.66,
                'category' => 'Jerseys',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/811/179811/465x605/dx2687-456_6.jpg',
            ],
            [
                'name' => 'Nike Phantom Luna II Elite AG-PRO',
                'price' => 1554.79,
                'category' => 'Shoes',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/563/184563/465x605/fj2579-001_6.webp',
            ],
            [
                'name' => 'Adidas X Crazyfast+ FG',
                'price' => 1349.08,
                'category' => 'Shoes',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/759/185759/465x605/ie2416_1.webp',
            ],
            [
                'name' => 'Puma Future 7 Ultimate Rush FG/AG',
                'price' => 1086.95,
                'category' => 'Shoes',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/979/187979/465x605/107828-01_1.webp',
            ],
            [
                'name' => 'Nike Zoom Mercurial Superfly 9 Elite SG-PRO Player Edition',
                'price' => 1413.33,
                'category' => 'Shoes',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/513/187513/465x605/fd0250-700_1.webp',
            ],
            [
                'name' => 'Adidas Fussballliebe EURO 2024 Pro Ball (Size 5)',
                'price' => 501.01,
                'category' => 'Balls',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/924/182924/465x605/iq3682-5_1.webp',
            ],
            [
                'name' => 'Adidas UCL Pro 23/24 Ball (Size 5)',
                'price' => 558.83,
                'category' => 'Balls',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/969/186969/465x605/in9340-5_1.webp',
            ],
            [
                'name' => 'Nike Premier League Flight Ball (Size 5)',
                'price' => 610.23,
                'category' => 'Balls',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/347/184347/465x605/fb2979-101-5_1.webp',
            ],
            [
                'name' => 'Puma Orbita 1 La Liga Ball (Size 5)',
                'price' => 411.06,
                'category' => 'Balls',
                'description' => '',
                'image' => 'https://gfx.r-gol.com/media/res/products/901/181901/465x605/084106-01-5_1.webp',
            ],
        ]);
    }
}
