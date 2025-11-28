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
        // Schema::table('payments', function (Blueprint $table) {
        //     $table->string('code_carte_cadeau_client');
        //     $table->foreign('code_carte_cadeau_client')
        //         ->references('code')
        //         ->on('carte_cadeau_client')
        //         ->onDelete('cascade');
        // });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
