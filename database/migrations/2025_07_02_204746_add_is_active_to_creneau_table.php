<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('creneau', function (Blueprint $table) {
            $table->integer('is_active')->default(1)->after('creneau');
        });
    }
    public function down()
    {
        // Schema::table('creneau', function (Blueprint $table) {
        //     $table->dropColumn('is_active');
        // });
    }
};
