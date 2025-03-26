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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // automatikusan hozzáad egy 'id' oszlopot
            $table->string('name', 255); // a termék neve
            $table->decimal('price', 10, 2); // az ár, maximum 10 számjegy, 2 tizedes
            $table->string('category', 50); // kategória
            $table->text('description'); // leírás
            $table->string('image', 255); // kép neve vagy elérési útja
            $table->timestamps(); // created_at és updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
