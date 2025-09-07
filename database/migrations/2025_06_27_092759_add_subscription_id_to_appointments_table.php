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
        // Schema::table('appointments', function (Blueprint $table) {
        //     $table->unsignedBigInteger('subscription_id')->nullable()->after('service_id'); 
        //     $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
        // });
    }
    /**
     * Reverse the migrations.
     */
    // public function down()
    // {
    //     Schema::table('appointments', function (Blueprint $table) {
    //         $table->dropForeign(['subscription_id']);
    //         $table->dropColumn('subscription_id');
    //     });
    // }
    // public function down(): void
    // {
    //     Schema::table('appointments', function (Blueprint $table) {
    //         //
    //     });
    // }
};
