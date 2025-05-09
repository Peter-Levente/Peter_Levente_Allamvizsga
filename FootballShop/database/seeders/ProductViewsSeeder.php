<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductViewsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_views')->insert([
            ['id' => 1, 'user_id' => 1, 'product_id' => 36, 'viewed_at' => '2025-05-03 12:24:00'],
            ['id' => 2, 'user_id' => 1, 'product_id' => 40, 'viewed_at' => '2025-05-03 12:24:41'],
            ['id' => 3, 'user_id' => 1, 'product_id' => 23, 'viewed_at' => '2025-05-03 12:26:04'],
            ['id' => 4, 'user_id' => 1, 'product_id' => 23, 'viewed_at' => '2025-05-03 12:26:26'],
            ['id' => 5, 'user_id' => 1, 'product_id' => 15, 'viewed_at' => '2025-05-03 12:26:53'],
            ['id' => 6, 'user_id' => 1, 'product_id' => 24, 'viewed_at' => '2025-05-03 12:27:00'],
            ['id' => 7, 'user_id' => 1, 'product_id' => 25, 'viewed_at' => '2025-05-03 12:27:29'],
            ['id' => 8, 'user_id' => 1, 'product_id' => 1, 'viewed_at' => '2025-05-05 10:40:08'],
            ['id' => 9, 'user_id' => 1, 'product_id' => 15, 'viewed_at' => '2025-05-05 10:40:40'],
            ['id' => 10, 'user_id' => 1, 'product_id' => 15, 'viewed_at' => '2025-05-05 10:41:02'],
            ['id' => 11, 'user_id' => 1, 'product_id' => 32, 'viewed_at' => '2025-05-05 13:16:07'],
            ['id' => 12, 'user_id' => 1, 'product_id' => 8, 'viewed_at' => '2025-05-05 13:29:50'],
            ['id' => 13, 'user_id' => 1, 'product_id' => 3, 'viewed_at' => '2025-05-06 08:59:54'],
            ['id' => 14, 'user_id' => 1, 'product_id' => 39, 'viewed_at' => '2025-05-06 09:00:00'],
            ['id' => 15, 'user_id' => 1, 'product_id' => 39, 'viewed_at' => '2025-05-06 09:00:04'],
            ['id' => 16, 'user_id' => 1, 'product_id' => 38, 'viewed_at' => '2025-05-06 09:00:07'],
            ['id' => 17, 'user_id' => 1, 'product_id' => 29, 'viewed_at' => '2025-05-06 09:00:09'],
            ['id' => 18, 'user_id' => 1, 'product_id' => 39, 'viewed_at' => '2025-05-06 09:00:12'],
            ['id' => 19, 'user_id' => 1, 'product_id' => 40, 'viewed_at' => '2025-05-06 09:00:15'],
            ['id' => 20, 'user_id' => 1, 'product_id' => 15, 'viewed_at' => '2025-05-06 09:00:27'],
            ['id' => 21, 'user_id' => 1, 'product_id' => 17, 'viewed_at' => '2025-05-06 09:00:32'],
            ['id' => 22, 'user_id' => 1, 'product_id' => 22, 'viewed_at' => '2025-05-06 09:00:36'],
            ['id' => 23, 'user_id' => 1, 'product_id' => 22, 'viewed_at' => '2025-05-06 09:00:40'],
            ['id' => 24, 'user_id' => 1, 'product_id' => 17, 'viewed_at' => '2025-05-06 09:00:48'],
            ['id' => 25, 'user_id' => 1, 'product_id' => 2, 'viewed_at' => '2025-05-07 14:46:19.519741'],
            ['id' => 26, 'user_id' => 1, 'product_id' => 61, 'viewed_at' => '2025-05-07 14:46:31.32869'],
            ['id' => 27, 'user_id' => 1, 'product_id' => 42, 'viewed_at' => '2025-05-07 14:51:12.723069'],
            ['id' => 28, 'user_id' => 1, 'product_id' => 41, 'viewed_at' => '2025-05-07 14:51:15.069262'],
            ['id' => 29, 'user_id' => 1, 'product_id' => 41, 'viewed_at' => '2025-05-07 14:51:38.008703'],
            ['id' => 30, 'user_id' => 1, 'product_id' => 42, 'viewed_at' => '2025-05-07 14:52:40.389977'],
            ['id' => 31, 'user_id' => 1, 'product_id' => 42, 'viewed_at' => '2025-05-07 14:52:42.605787'],
            ['id' => 32, 'user_id' => 1, 'product_id' => 43, 'viewed_at' => '2025-05-07 14:52:43.104964'],
            ['id' => 33, 'user_id' => 1, 'product_id' => 44, 'viewed_at' => '2025-05-07 14:52:45.139179'],
            ['id' => 34, 'user_id' => 1, 'product_id' => 54, 'viewed_at' => '2025-05-07 14:52:50.253506'],
            ['id' => 35, 'user_id' => 1, 'product_id' => 57, 'viewed_at' => '2025-05-07 14:53:08.348498'],
            ['id' => 36, 'user_id' => 1, 'product_id' => 57, 'viewed_at' => '2025-05-07 14:53:08.850673'],
            ['id' => 37, 'user_id' => 1, 'product_id' => 57, 'viewed_at' => '2025-05-07 14:53:13.471697'],
            ['id' => 38, 'user_id' => 1, 'product_id' => 54, 'viewed_at' => '2025-05-07 14:53:18.593874'],
            ['id' => 39, 'user_id' => 1, 'product_id' => 56, 'viewed_at' => '2025-05-07 14:53:21.286897'],
            ['id' => 40, 'user_id' => 1, 'product_id' => 48, 'viewed_at' => '2025-05-07 14:53:35.665304'],
            ['id' => 41, 'user_id' => 1, 'product_id' => 53, 'viewed_at' => '2025-05-07 14:53:40.494608'],
            ['id' => 42, 'user_id' => 1, 'product_id' => 49, 'viewed_at' => '2025-05-07 14:53:46.655055'],
            ['id' => 43, 'user_id' => 1, 'product_id' => 55, 'viewed_at' => '2025-05-07 14:54:22.788142'],
            ['id' => 44, 'user_id' => 1, 'product_id' => 56, 'viewed_at' => '2025-05-07 14:54:26.761036'],
            ['id' => 45, 'user_id' => 1, 'product_id' => 52, 'viewed_at' => '2025-05-07 14:54:29.166297'],
            ['id' => 46, 'user_id' => 1, 'product_id' => 42, 'viewed_at' => '2025-05-07 14:54:42.119713'],
            ['id' => 47, 'user_id' => 1, 'product_id' => 46, 'viewed_at' => '2025-05-07 14:55:13.378993'],
            ['id' => 48, 'user_id' => 1, 'product_id' => 52, 'viewed_at' => '2025-05-07 14:55:18.758077'],
            ['id' => 49, 'user_id' => 1, 'product_id' => 45, 'viewed_at' => '2025-05-07 14:55:21.980372'],
            ['id' => 50, 'user_id' => 1, 'product_id' => 55, 'viewed_at' => '2025-05-07 16:53:20.700477'],
            ['id' => 51, 'user_id' => 1, 'product_id' => 40, 'viewed_at' => '2025-05-07 16:54:01.310663'],
            ['id' => 52, 'user_id' => 1, 'product_id' => 29, 'viewed_at' => '2025-05-07 16:54:05.104135'],
            ['id' => 53, 'user_id' => 1, 'product_id' => 28, 'viewed_at' => '2025-05-07 16:54:07.809671'],
            ['id' => 54, 'user_id' => 1, 'product_id' => 5, 'viewed_at' => '2025-05-07 16:54:13.744555'],
            ['id' => 55, 'user_id' => 1, 'product_id' => 3, 'viewed_at' => '2025-05-07 17:09:44.870955'],
            ['id' => 56, 'user_id' => 1, 'product_id' => 10, 'viewed_at' => '2025-05-07 17:09:50.017814'],
            ['id' => 57, 'user_id' => 1, 'product_id' => 1, 'viewed_at' => '2025-05-08 13:40:10.522258'],
            ['id' => 58, 'user_id' => 1, 'product_id' => 3, 'viewed_at' => '2025-05-08 13:40:13.947197']
        ]);

        // szinkronizáljuk az ID sorozatot
        DB::statement("
        SELECT setval(
            pg_get_serial_sequence('product_views','id'),
            (SELECT COALESCE(MAX(id), 0) FROM product_views)
        );
    ");
    }
}
