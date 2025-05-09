<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            ['id' => 19, 'user_id' => 1, 'address' => 'Armaseni-noi drum principal 605', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 423.05, 'status' => 'pending', 'created_at' => '2025-04-26 12:07:26', 'updated_at' => '2025-04-26 12:07:26'],
            ['id' => 20, 'user_id' => 1, 'address' => 'Armaseni-noi, drum principal, nr: 605,', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 2403.84, 'status' => 'pending', 'created_at' => '2025-05-03 12:17:57', 'updated_at' => '2025-05-03 12:17:57'],
            ['id' => 21, 'user_id' => 1, 'address' => 'Armaseni-noi, drum principal, nr: 605,', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 1343.79, 'status' => 'pending', 'created_at' => '2025-05-03 12:24:12', 'updated_at' => '2025-05-03 12:24:12'],
            ['id' => 22, 'user_id' => 1, 'address' => 'Armaseni-noi, drum principal, nr: 605,', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 1288.99, 'status' => 'pending', 'created_at' => '2025-05-03 12:24:50', 'updated_at' => '2025-05-03 12:24:50'],
            ['id' => 23, 'user_id' => 1, 'address' => 'Armaseni-noi, drum principal, nr: 605,', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 93.00, 'status' => 'pending', 'created_at' => '2025-05-03 12:26:35', 'updated_at' => '2025-05-03 12:26:35'],
            ['id' => 24, 'user_id' => 1, 'address' => 'Armaseni-noi, drum principal, nr: 605,', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 290.00, 'status' => 'pending', 'created_at' => '2025-05-03 12:27:11', 'updated_at' => '2025-05-03 12:27:11'],
            ['id' => 25, 'user_id' => 1, 'address' => 'Armaseni-noi, drum principal, nr: 605,', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 149.26, 'status' => 'pending', 'created_at' => '2025-05-03 12:27:38', 'updated_at' => '2025-05-03 12:27:38'],
            ['id' => 1, 'user_id' => 1, 'address' => 'Armaseni-noi, drum principal, nr: 605,', 'phone' => 0726175306, 'payment_method' => 'Cash on Delivery', 'total_amount' => 746.55, 'status' => 'pending', 'created_at' => '2025-05-07 14:52:23', 'updated_at' => '2025-05-07 14:52:23']
        ]);
    }
}
