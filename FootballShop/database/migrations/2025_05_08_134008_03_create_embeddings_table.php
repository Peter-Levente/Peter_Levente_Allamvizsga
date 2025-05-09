<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('embeddings', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('context');
            $table->text('content');
            $table->vector('embedding', 1536);
            $table->integer('related_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string(');');  // unknown type: 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('embeddings');
    }
};