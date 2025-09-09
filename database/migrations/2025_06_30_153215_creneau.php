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
        Schema::create('creneau', function (Blueprint $table) {
            $table->id();
            $table->string('creneau', 100);
            $table->timestamps();
            // $table->timestamp('created_at')->useCurrent();
            // $table->timestamp('update_at')->useCurrent();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
