<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Minden termék lekérdezése
    public function index()
    {
        $products = Product::all();  // Lekérjük az összes terméket
        return view('index', compact('products'));  // View-hoz továbbítjuk
    }

    // Egyetlen termék lekérdezése ID alapján
    public function show($id)
    {
        $product = Product::findOrFail($id);  // Automatikusan 404-et dob, ha nincs találat
        return view('products.show', compact('product'));
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
