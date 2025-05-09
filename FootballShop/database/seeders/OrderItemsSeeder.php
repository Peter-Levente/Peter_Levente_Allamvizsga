<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_items')->insert([
            ['id' => 10, 'order_id' => 19, 'product_id' => 1, 'quantity' => 1, 'price' => 423.05, 'created_at' => '2025-04-26 12:07:26', 'updated_at' => '2025-04-26 12:07:26'],
            ['id' => 11, 'order_id' => 20, 'product_id' => 39, 'quantity' => 1, 'price' => 910.74, 'created_at' => '2025-05-03 12:17:57', 'updated_at' => '2025-05-03 12:17:57'],
            ['id' => 12, 'order_id' => 20, 'product_id' => 15, 'quantity' => 1, 'price' => 746.55, 'created_at' => '2025-05-03 12:17:57', 'updated_at' => '2025-05-03 12:17:57'],
            ['id' => 13, 'order_id' => 20, 'product_id' => 41, 'quantity' => 1, 'price' => 746.55, 'created_at' => '2025-05-03 12:17:57', 'updated_at' => '2025-05-03 12:17:57'],
            ['id' => 14, 'order_id' => 21, 'product_id' => 36, 'quantity' => 1, 'price' => 1343.79, 'created_at' => '2025-05-03 12:24:12', 'updated_at' => '2025-05-03 12:24:12'],
            ['id' => 15, 'order_id' => 22, 'product_id' => 40, 'quantity' => 1, 'price' => 1288.99, 'created_at' => '2025-05-03 12:24:50', 'updated_at' => '2025-05-03 12:24:50'],
            ['id' => 16, 'order_id' => 23, 'product_id' => 23, 'quantity' => 1, 'price' => 93.00, 'created_at' => '2025-05-03 12:26:35', 'updated_at' => '2025-05-03 12:26:35'],
            ['id' => 17, 'order_id' => 24, 'product_id' => 24, 'quantity' => 1, 'price' => 290.00, 'created_at' => '2025-05-03 12:27:11', 'updated_at' => '2025-05-03 12:27:11'],
            ['id' => 18, 'order_id' => 25, 'product_id' => 25, 'quantity' => 1, 'price' => 149.26, 'created_at' => '2025-05-03 12:27:38', 'updated_at' => '2025-05-03 12:27:38'],
            ['id' => 1, 'order_id' => 1, 'product_id' => 41, 'quantity' => 1, 'price' => 746.55, 'created_at' => '2025-05-07 14:52:23', 'updated_at' => '2025-05-07 14:52:23']
        ]);
    }
}
