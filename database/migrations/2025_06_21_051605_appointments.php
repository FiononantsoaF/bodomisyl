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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('service_id');
            $table->timestamp('start_times')->nullable();
            $table->timestamp('end_times')->nullable();
            $table->enum('status', ['confirmed', 'pending', 'cancelled'])->default('pending');
            $table->text('comment')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('service_id')->references('id')->on('services');
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
