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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code_promo')->nullable(false);
            $table->decimal('pourcent', 10, 2)->nullable(true);
            $table->decimal('amount', 10, 2)->nullable(true);
            $table->dateTime('start_promo')->nullable(true);
            $table->dateTime('end_promo')->nullable(true);
            $table->text('services')->nullable(true);
            $table->text('clients')->nullable(true);
            $table->timestamps();
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
