<?php

namespace App\Services;

use App\Models\ProductView;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\OrderItem;

class UserInterestProfileUpdater
{
    public function update(int $userId): bool
    {
        // 1. MEGTEKINTETT termékek (1x szorzó)
        $viewedProductIds = ProductView::where('user_id', $userId)->pluck('product_id')->toArray();

        $viewedEmbeddings = DB::connection('pgsql')->table('product_embeddings')
            ->whereIn('product_id', $viewedProductIds)
            ->pluck('embedding')
            ->toArray();

        // 2. KOSÁRBA HELYEZETT termékek (3x szorzó)
        $cartProductIds = Cart::where('user_id', $userId)->pluck('product_id')->toArray();

        $cartEmbeddings = DB::connection('pgsql')->table('product_embeddings')
            ->whereIn('product_id', $cartProductIds)
            ->pluck('embedding')
            ->toArray();

        // 3. MEGVÁSÁROLT termékek (5x szorzó)
        $orderProductIds = OrderItem::whereIn('order_id', function ($query) use ($userId) {
            $query->select('id')->from('orders')->where('user_id', $userId);
        })->pluck('product_id')->toArray();

        $purchasedEmbeddings = DB::connection('pgsql')->table('product_embeddings')
            ->whereIn('product_id', $orderProductIds)
            ->pluck('embedding')
            ->toArray();

        // Ha nincs egy embedding se, nincs mit frissíteni
        if (empty($viewedEmbeddings) && empty($cartEmbeddings) && empty($purchasedEmbeddings)) {
            return false;
        }

        // Segédfüggvény: vektorokat szorozzuk (duplikálás)
        $expand = function ($embeddings, $factor) {
            $result = [];
            foreach ($embeddings as $embedding) {
                $vec = array_map('floatval', explode(',', trim($embedding, '[]')));
                for ($i = 0; $i < $factor; $i++) {
                    $result[] = $vec;
                }
            }
            return $result;
        };

        // Súlyozott vektorok összevonása
        $allVectors = array_merge(
            $expand($viewedEmbeddings, 1),
            $expand($cartEmbeddings, 3),
            $expand($purchasedEmbeddings, 5)
        );

        // Átlagolás
        $sum = array_fill(0, count($allVectors[0]), 0.0);
        foreach ($allVectors as $vec) {
            foreach ($vec as $i => $val) {
                $sum[$i] += $val;
            }
        }
        $count = count($allVectors);
        $avg = array_map(fn($val) => round($val / $count, 6), $sum);
        $pgVector = '[' . implode(',', $avg) . ']';

        // Mentés a PostgreSQL-be
        DB::connection('pgsql')->table('user_interest_profiles')->updateOrInsert(
            ['user_id' => $userId],
            [
                'embedding' => DB::raw("'$pgVector'::vector"),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        return true;
    }
}
