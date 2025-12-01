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
        Schema::create('carte_cadeau_client', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('benef_name');  
            $table->string('benef_email'); 
            $table->unsignedBigInteger('carte_cadeau_service_id');              
            $table->string('benef_contact')->nullable(false); 
            $table->unsignedBigInteger('client_id');     
            $table->decimal('amount', 10, 2)->default(0); 
            $table->date('start_date');                 
            $table->integer('validy_days')->nullable(true);   
            $table->date('end_date')->nullable(true);          
            $table->boolean('is_active')->default(true);   
            $table->timestamps();
            $table->foreign('carte_cadeau_service_id')->references('id')->on('carte_cadeau_service')->onDelete('cascade');
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
