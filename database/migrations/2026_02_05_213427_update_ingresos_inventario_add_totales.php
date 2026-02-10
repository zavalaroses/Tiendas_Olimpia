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
        Schema::table('ingresos_inventario',function(Blueprint $table){
            $table->decimal('total_compra',12,2)->default(0)->after('codigo_trazabilidad');
            $table->decimal('total_pagado',12,2)->default(0)->after('total_compra');
            $table->enum('estatus_pago',['pendiente','parcial','pagado'])
                ->default('pendiente')
                ->after('total_pagado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingresos_inventario',function(Blueprint $table){
            $table->dropColumn([
                'total_compra',
                'total_pagado',
                'estatus_pago',
            ]);
        });
    }
};
