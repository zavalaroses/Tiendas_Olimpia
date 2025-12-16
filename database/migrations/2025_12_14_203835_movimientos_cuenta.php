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
        Schema::create('movimientos_cuenta', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('tienda_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        
            $table->decimal('monto', 12, 2);
        
            // entrada | salida
            $table->enum('tipo_movimiento', ['entrada', 'salida']);
        
            // tarjeta | transferencia | retiro | ajuste
            $table->enum('concepto', [
                'tarjeta',
                'transferencia',
                'retiro',
                'ajuste'
            ]);
        
            $table->string('referencia')->nullable();
            $table->text('descripcion')->nullable();
        
            $table->timestamp('fecha_movimiento')->useCurrent();
        
            $table->timestamps();
            $table->softDeletes();
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
