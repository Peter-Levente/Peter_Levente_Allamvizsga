<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('migrations', function (Blueprint $table) {
            $table->integer('id');
            $table->string('migration');
            $table->integer('batch');
            $table->string(');');  // unknown type: 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migrations');
    }
};