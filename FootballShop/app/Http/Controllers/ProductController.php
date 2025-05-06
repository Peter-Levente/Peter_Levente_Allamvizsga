<?php

namespace App\Http\Controllers;

use App\Events\ProductViewed;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Minden termék lekérdezése
    public function index()
    {
        $products = Product::all();  // Összes termék lekérése
        $recommendedProducts = collect();

        if (auth()->check()) {
            $userId = auth()->id();

            $userEmbedding = DB::connection('pgsql')->table('user_interest_profiles')
                ->where('user_id', $userId)
                ->value('embedding');

            if ($userEmbedding) {
                $similarRows = DB::connection('pgsql')->select("
                    SELECT product_id, embedding <-> ?::vector as distance
                    FROM product_embeddings
                    ORDER BY embedding <-> ?::vector
                    LIMIT 10
                ", [$userEmbedding, $userEmbedding]);


                $ids = array_map(fn($row) => $row->product_id, $similarRows);
                $distances = collect($similarRows)->pluck('distance', 'product_id')->toArray();

                $recommendedProducts = Product::whereIn('id', $ids)->get();

                $recommendedProducts = $recommendedProducts->map(function ($item) use ($distances) {
                    $item->distance = $distances[$item->id] ?? null;
                    return $item;
                })->sortBy('distance')->take(10)->values();
            }
        }

        return view('index', compact('products', 'recommendedProducts'));
    }






    // Egyetlen termék lekérdezése ID alapján
    public function show($id)
    {

        if (auth()->check()) {
            ProductViewed::dispatch(auth()->id(), $id);
        }


        $product = Product::findOrFail($id);

        $embeddingRow = DB::connection('pgsql')
            ->table('product_embeddings')
            ->where('product_id', $product->id)
            ->first();

        $similarProducts = collect();

        if ($embeddingRow) {
            $similarRows = DB::connection('pgsql')->select("
            SELECT pe.product_id, pe.embedding <-> ?::vector as distance
            FROM product_embeddings pe
            WHERE pe.product_id != ?
            ORDER BY distance
            LIMIT 4
        ", [$embeddingRow->embedding, $product->id]);

            $ids = array_map(fn($item) => $item->product_id, $similarRows);
            $distances = [];
            foreach ($similarRows as $row) {
                $distances[$row->product_id] = $row->distance;
            }

            $similarProducts = Product::whereIn('id', $ids)->get();

            $similarProducts = $similarProducts->map(function ($item) use ($distances) {
                $item->distance = $distances[$item->id] ?? null;
                return $item;
            })->sortBy('distance')->values(); // csökkenő sorrend
        }

        return view('products.details', compact('product', 'similarProducts'));
    }


    // Kategória alapján termékek lekérdezése
    public function showcategory($category)
    {
        $products = Product::where('category', $category)->get(); // Lekérjük a kategória szerinti termékeket

        if ($products->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'No products found in this category.');
        }

        return view('products.category', compact('products', 'category'));
    }
}
