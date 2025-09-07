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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('size')->nullable(false)->change();
            $table->decimal('weight')->nullable(false)->change();
            $table->decimal('IMC')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::table('user', function (Blueprint $table) {
    //         //
    //     });
    // }
};
