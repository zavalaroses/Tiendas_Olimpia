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
        Schema::create('comisiones_vendedores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tienda_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('apartado_id');
            $table->unsignedBigInteger('salida_id');
            $table->decimal('monto_venta',10,2);
            $table->decimal('porcentaje',5,2);
            $table->decimal('monto_comision',12,2);
            $table->date('fecha_entrega');
            $table->boolean('pagada')->default(false);
            $table->timestamp('fecha_pago')->nullable();
            $table->unique(['apartado_id']);
            $table->index(['usuario_id','fecha_entrega']);
            $table->index('pagada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones_vendedores');
    }
};
