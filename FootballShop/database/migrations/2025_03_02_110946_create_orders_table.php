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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // automatikusan hozzáad egy 'id' oszlopot
            $table->unsignedBigInteger('user_id'); // idegen kulcs a users táblából
            $table->text('address'); // rendelés címe
            $table->decimal('total_amount', 10, 2); // összeg, maximum 10 számjegy, 2 tizedes
            $table->string('status', 20)->default('Pending'); // státusz, alapértelmezett 'Pending'
            $table->timestamps(); // created_at és updated_at
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // idegen kulcs beállítása
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
