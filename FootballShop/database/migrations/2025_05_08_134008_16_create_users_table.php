<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('email');
            $table->string('password');
            $table->string('remember_token');
            $table->string('name');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string(');');  // unknown type: 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};