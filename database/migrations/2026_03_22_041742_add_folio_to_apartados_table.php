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
        Schema::table('apartados', function (Blueprint $table) {
            //
            $table->integer('folio_tienda')->nullable()->after('id');
            $table->string('clave')->nullable()->after('folio_tienda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartados', function (Blueprint $table) {
            //
            $table->dropColumn(['folio_tienda','clave']);
        });
    }
};
