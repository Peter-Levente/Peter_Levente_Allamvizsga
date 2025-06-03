<?php

namespace App\Http\Controllers;

use App\Events\ProductViewed;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Termékek megjelenítéséért, kereséséért, ajánlásáért és részletezéséért felelős kontroller
class ProductController extends Controller
{
    /**
     * Összes termék listázása, szűrés, rendezés és személyre szabott ajánlás
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 🔍 Keresés és rendezés beállítása
        $query = Product::query();

        // Név szerinti keresés
        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        // Rendezés kiválasztása (ár vagy név szerint)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }

        // Lekérdezzük a termékeket
        $products = $query->get();

        // 🔁 Ajánlott termékek előkészítése
        $recommendedProducts = collect();

        // Csak bejelentkezett felhasználónak számolunk ajánlást
        if (auth()->check()) {
            $userId = auth()->id();

            // Az adott felhasználó embedding vektorát lekérjük
            $userEmbedding = DB::connection('pgsql')->table('user_interest_profiles')
                ->where('user_id', $userId)
                ->value('embedding');

            if ($userEmbedding) {
                // Hasonló termékek keresése a felhasználói embedding alapján
                // A <-> kiszámítja a hasonlóságot az adatbázisban tárolt embedding és a megadott $userEmbedding vektor között.
                // ?::vector → a lekérdezés paraméterét (a felhasználó embeddingjét) explicit vector típusra konvertálja.
                $similarRows = DB::connection('pgsql')->select("
                    SELECT product_id, embedding <-> ?::vector as distance
                    FROM product_embeddings
                    ORDER BY embedding <-> ?::vector
                ", [$userEmbedding, $userEmbedding]);

                $ids = array_map(fn($row) => $row->product_id, $similarRows);
                $distances = collect($similarRows)->pluck('distance', 'product_id')->toArray();

                // Lekérjük az ajánlott termékeket és hozzárendeljük a távolságokat
                $recommendedProducts = Product::whereIn('id', $ids)->get();
                $recommendedProducts = $recommendedProducts->map(function ($item) use ($distances) {
                    $item->distance = $distances[$item->id] ?? null;
                    return $item;
                })->sortBy('distance')->take(10)->values();
            }
        }

        return view('index', compact('products', 'recommendedProducts'));
    }

    /**
     * Egy konkrét termék részleteinek megjelenítése, hasonló termékekkel
     *
     * @param int $id A termék azonosítója
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Megtekintés esemény rögzítése (ajánlórendszer számára)
        if (auth()->check()) {
            ProductViewed::dispatch(auth()->id(), $id);
        }

        $product = Product::findOrFail($id);

        // Az aktuális termék embeddingjének lekérdezése
        $embeddingRow = DB::connection('pgsql')
            ->table('product_embeddings')
            ->where('product_id', $product->id)
            ->first();

        $similarProducts = collect();

        if ($embeddingRow) {
            // Legközelebbi termékek keresése az embedding térben (10 db)
            // A <-> kiszámítja a hasonlóságot az adatbázisban tárolt embedding és a megadott $userEmbedding vektor között.
            // ?::vector → a lekérdezés paraméterét (a felhasználó embeddingjét) explicit vector típusra konvertálja.
            $similarRows = DB::connection('pgsql')->select("
                SELECT pe.product_id, pe.embedding <-> ?::vector as distance
                FROM product_embeddings pe
                WHERE pe.product_id != ?
                ORDER BY distance
                LIMIT 10
            ", [$embeddingRow->embedding, $product->id]);

            $ids = array_map(fn($item) => $item->product_id, $similarRows);

            $distances = [];
            foreach ($similarRows as $row) {
                $distances[$row->product_id] = $row->distance;
            }

            // Termékek lekérése és a távolságok hozzárendelése
            $similarProducts = Product::whereIn('id', $ids)->get();
            $similarProducts = $similarProducts->map(function ($item) use ($distances) {
                $item->distance = $distances[$item->id] ?? null;
                return $item;
            })->sortBy('distance')->values(); // Növekvő sorrendben (legjobban hasonlók elöl)
        }

        return view('products.details', compact('product', 'similarProducts'));
    }

    /**
     * Termékek megjelenítése adott kategória alapján, kereséssel és rendezéssel
     *
     * @param Request $request
     * @param string $category
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showcategory(Request $request, $category)
    {
        // Lekérjük a kategória termékeit
        $products = Product::where('category', $category)->get();

        // Ha nincs ilyen kategóriájú termék, visszairányítjuk a főoldalra
        if ($products->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'No products found in this category.');
        }

        // Új lekérdezés szűréshez és rendezéshez
        $query = Product::where('category', $category);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }

        // Szűrt/rendezett termékek
        $products = $query->get();

        return view('products.category', compact('products', 'category'));
    }
}
