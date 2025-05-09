<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_interest_profiles', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->vector('embedding', 1536);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string(');');  // unknown type: 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_interest_profiles');
    }
};