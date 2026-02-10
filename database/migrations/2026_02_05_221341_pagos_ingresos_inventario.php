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
        Schema::create('pagos_ingresos_inventario', function(Blueprint $table){
            $table->id();
            $table->foreignId('ingreso_id')
                ->constrained('ingresos_inventario')
                ->cascadeOnDelete();
            $table->foreignId('tienda_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->decimal('monto',12,2);
            $table->enum('metodo_pago',[
                'efectivo',
                'transferencia',
                'tarjeta',
            ]);
            $table->date('fecha');
            $table->string('descripcion')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExist('pagos_ingresos_inventario');
    }
};
