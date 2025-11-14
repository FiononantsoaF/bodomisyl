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
        Schema::create('carte_cadeau', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('beneficiaire');               
            $table->string('contact')->nullable(false); 
            $table->unsignedBigInteger('client_id');   
            $table->unsignedBigInteger('service_id');   
            $table->decimal('montant', 10, 2)->default(0); 
            $table->date('date_emission');                 
            $table->integer('validite_jours')->nullable(true);   
            $table->date('date_fin')->nullable(true);          
            $table->boolean('is_active')->default(true);   
            $table->timestamps();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

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
