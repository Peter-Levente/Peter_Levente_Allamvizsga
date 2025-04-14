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
        Schema::create('embeddings', function (Blueprint $table) {
            $table->id(); // `id` - egyedi azonosító (INT, AUTO_INCREMENT, PRIMARY KEY)
            $table->string('context', 50); // `context` - szöveg típusa (VARCHAR(50))
            $table->text('content'); // `content` - szöveges tartalom (TEXT)
            $table->json('embedding'); // `embedding` - vektoros reprezentáció (JSON)
            $table->integer('related_id'); // `related_id` - kapcsolódó elem azonosítója (INT)
            $table->timestamps(); // `created_at`, `updated_at` - időbélyegek
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embeddings');
    }
};
