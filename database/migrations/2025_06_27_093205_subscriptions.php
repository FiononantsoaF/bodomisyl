<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id(); 

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('services_id');

            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->integer('total_session')->default(0);
            $table->integer('used_session')->default(0);

            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('services_id')->references('id')->on('services')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        // Schema::dropIfExists('subscriptions');
    }

};
