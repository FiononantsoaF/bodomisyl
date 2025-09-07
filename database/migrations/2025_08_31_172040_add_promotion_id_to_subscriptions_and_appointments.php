<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('promotion_id')->nullable()->after('services_id');
            $table->decimal('final_price', 10, 2)->nullable()->after('promotion_id');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('promotion_id')->nullable()->after('service_id');
            $table->decimal('final_price', 10, 2)->nullable()->after('promotion_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
};
