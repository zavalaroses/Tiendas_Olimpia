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
            $table->dropForeign(['ingreso_id']);
        });
        
        Schema::table('pagos_ingresos_inventario', function (Blueprint $table) {
            $table->unsignedBigInteger('ingreso_id')->nullable()->change();
        
            $table->foreign('ingreso_id')
                ->references('id')
                ->on('ingresos_inventario')
                ->nullOnDelete(); // 🔥 clave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos_ingresos_inventario', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('ingreso_id')->nullable(false)->change();
        });
    }
};
