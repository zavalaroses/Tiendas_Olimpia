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
        Schema::table('pagos_ingresos_inventario', function (Blueprint $table) {

            $table->unsignedBigInteger('proveedor_id')
                  ->nullable()
                  ->after('ingreso_id');

            $table->enum('tipo', ['abono', 'cargo'])
                  ->default('abono')
                  ->after('monto');

            $table->foreign('proveedor_id')
                  ->references('id')
                  ->on('proveedores')
                  ->onDelete('cascade');
        
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos_ingresos_inventario', function (Blueprint $table) {
            
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn(['proveedor_id', 'tipo']);
        });
    }
};
