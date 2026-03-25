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
        Schema::table('ingresos_inventario', function (Blueprint $table) {
            //
            $table->decimal('envio',10,2)
                ->default(0)
                ->after('total_pagado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingresos_inventario', function (Blueprint $table) {
            //
            $table->dropColumn('envio');
        });
    }
};
