<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        // Schema::dropIfExists('category');
    }
};
