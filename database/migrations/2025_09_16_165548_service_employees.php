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
        Schema::create('service_employees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('employee_id');

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->timestamps(); // ajoute created_at et updated_at automatiquement
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
