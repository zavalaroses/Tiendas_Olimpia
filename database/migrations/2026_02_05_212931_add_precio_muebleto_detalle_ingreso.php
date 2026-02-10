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
        Schema::table('detalle_ingresos', function(Blueprint $table){
            $table->decimal('precio_compra',12,2)
                ->after('cantidad')
                ->comment('Precio de compra del mueble al momento de la venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_ingresos', function(Blueprint $table){
            $table->dropColumn('precio_compra');
        });
    }
};
