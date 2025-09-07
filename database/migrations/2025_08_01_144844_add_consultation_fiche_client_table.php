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
        Schema::table('fiche_client', function (Blueprint $table) {
            $table->text('consultations')->nullable()->after('observations');
            $table->text('programmes')->nullable()->after('consultations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fiche_client', function (Blueprint $table) {
            //
        });
    }
};
