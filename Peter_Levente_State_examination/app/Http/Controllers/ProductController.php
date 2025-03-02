<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // Minden termék lekérdezése
    public function index()
    {
        $products = Product::all();  // Eloquent segítségével lekérjük az összes terméket
        return view('products.index', compact('products'));  // Visszaadjuk a termékeket egy view-nak
    }

    // Egyetlen termék lekérdezése ID alapján
    public function show($id)
    {
        $product = Product::find($id);  // Az Eloquent 'find' metódusa lekéri a terméket ID alapján
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
        return view('products.show', compact('product'));  // Visszaadjuk a terméket egy részletes oldalon
    }

    // Kategória alapján termékek lekérdezése
    public function category($category)
    {
        $products = Product::getProductsByCategory($category);  // Kategória alapú lekérdezés
        return view('products.category', compact('products'));  // Visszaadjuk a kategória szerint szűrt termékeket
    }
}
