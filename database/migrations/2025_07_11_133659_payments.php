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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('deposit', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('status', ['paid', 'partial'])->default('partial');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
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
