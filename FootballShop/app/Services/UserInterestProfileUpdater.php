<?php

namespace App\Services;

use App\Models\ProductView;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\OrderItem;

// A felhasználó érdeklődési embedding profilját frissítő szolgáltatás
class UserInterestProfileUpdater
{
    /**
     * A felhasználó profiljának frissítése megtekintett, kosárba tett és megvásárolt termékek alapján.
     * A különböző interakciók súlyozással járulnak hozzá az átlagolt embeddinghez.
     *
     * @param int $userId A felhasználó azonosítója
     * @return bool True, ha történt frissítés, false ha nem volt elérhető embedding
     */
    public function update(int $userId): bool
    {
        // 1. Megtekintett termékek (súly: 1x)
        $viewedProductIds = ProductView::where('user_id', $userId)->pluck('product_id')->toArray();

        $viewedEmbeddings = DB::connection('pgsql')->table('product_embeddings')
            ->whereIn('product_id', $viewedProductIds)
            ->pluck('embedding')
            ->toArray();

        // 2. Kosárban lévő termékek (súly: 3x)
        $cartProductIds = Cart::where('user_id', $userId)->pluck('product_id')->toArray();

        $cartEmbeddings = DB::connection('pgsql')->table('product_embeddings')
            ->whereIn('product_id', $cartProductIds)
            ->pluck('embedding')
            ->toArray();

        // 3. Megvásárolt termékek (súly: 5x)
        $orderProductIds = OrderItem::whereIn('order_id', function ($query) use ($userId) {
            $query->select('id')->from('orders')->where('user_id', $userId);
        })->pluck('product_id')->toArray();

        $purchasedEmbeddings = DB::connection('pgsql')->table('product_embeddings')
            ->whereIn('product_id', $orderProductIds)
            ->pluck('embedding')
            ->toArray();

        // Ha egyik listában sincs adat, nem frissítünk
        if (empty($viewedEmbeddings) && empty($cartEmbeddings) && empty($purchasedEmbeddings)) {
            return false;
        }

        /**
         * Segédfüggvény: szöveges embedding tömb duplikálása súly alapján
         * Az eredmény egy float tömböket tartalmazó tömb lesz (n*dimenzió)
         *
         * @param array $embeddings Szöveges PGVector embeddingek (pl. "[0.1,0.2,...]")
         * @param int $factor Hányszor szorozzuk meg (pl. súly = 3)
         * @return array Float vektorok
         */
        $expand = function ($embeddings, int $factor): array {
            $result = [];
            foreach ($embeddings as $embedding) {
                // array_map('floatval', ...): minden elemet lebegőpontos számmá alakít.
                // trim($embedding, '[]'): levágja a [ és ] jeleket.
                $vec = array_map('floatval', explode(',', trim($embedding, '[]')));
                for ($i = 0; $i < $factor; $i++) {
                    $result[] = $vec;
                }
            }
            return $result;
        };

        // Minden vektor súlyozottan bekerül az összevonásba
        $allVectors = array_merge(
            $expand($viewedEmbeddings, 1),
            $expand($cartEmbeddings, 3),
            $expand($purchasedEmbeddings, 5)
        );

        // Átlagolás: dimenziónként összegezés, majd osztás az elemszámmal
        $sum = array_fill(0, count($allVectors[0]), 0.0);
        foreach ($allVectors as $vec) {
            foreach ($vec as $i => $val) {
                $sum[$i] += $val;
            }
        }
        $count = count($allVectors);
        // Ez végigmegy a $sum tömb minden elemén (komponensen), és:
        // elvégzi az osztást: kiszámítja az átlagot adott dimenzióban
        // kerekít 6 tizedesjegyre
        $avg = array_map(fn($val) => round($val / $count, 6), $sum);
        $pgVector = '[' . implode(',', $avg) . ']'; // arra szolgál, hogy az átlagolt embedding vektort PostgreSQL-kompatibilis szövegformátumba alakítsa, amelyet a pgvector típus elfogad.

        // Végleges profil mentése vagy frissítése PostgreSQL-ben (pgvector típus)
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
