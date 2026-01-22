<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::transaction(function(){
            DB::statement(
                "UPDATE movimientos_tienda mt JOIN salidas as v ON v.id = mt.venta_id
                SET mt.venta_id = v.apartado_id
                WHERE mt.tipo_movimiento = 'entrada' AND mt.descripcion = 'Venta'"
            );
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
