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
        Schema::create('fiche_client', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('client_id');
            $table->text('objectifs')->nullable(); 
            $table->text('indications')->nullable(); 
            $table->text('observations')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade'); 
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
