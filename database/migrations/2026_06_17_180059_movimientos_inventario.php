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
        schema::create('movimientos_inventario', function (Blueprint $table){
            $table->id();
            $table->foreignId('tienda_id')->constrained();
            $table->foreignId('mueble_id')->constrained();
            $table->enum('tipo',[
                'compra',
                'venta',
                'apartado',
                'cancelacion_apartado',
                'entrega',
                'ajuste_entrada',
                'ajuste_salida',
                'garantia_entrada',
                'garantia_salida',
            ]);
            $table->integer('cantidad');
            $table->integer('cantidad_movimiento');
            $table->decimal('costo_unitario',12,2)->nullable();
            $table->string('referencia_tipo')->nullable();
            $table->date('fecha_movimiento');
            $table->timestamps();
            $table->index([
                'tienda_id',
                'mueble_id',
                'fecha_movimiento'
            ]);
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
