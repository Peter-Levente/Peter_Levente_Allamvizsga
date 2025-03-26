<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Automatikusan hozzáad egy 'id' oszlopot
            $table->unsignedBigInteger('order_id'); // Idegen kulcs az 'orders' táblából
            $table->unsignedBigInteger('product_id'); // Idegen kulcs a 'products' táblából
            $table->integer('quantity'); // A termék mennyisége a rendelésben
            $table->decimal('price', 10, 2); // A termék ára
            $table->timestamps(); // created_at és updated_at oszlopok automatikusan hozzáadódnak

            // Idegen kulcsok beállítása
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
