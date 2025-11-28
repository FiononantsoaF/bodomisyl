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
        Schema::table('carte_cadeau_client', function (Blueprint $table) {
            $table->string('message')->nullable(true)->after('benef_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carte_cadeau_client', function (Blueprint $table) {
            //
        });
    }
};
