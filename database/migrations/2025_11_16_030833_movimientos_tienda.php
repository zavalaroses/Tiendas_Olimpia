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
        Schema::create('movimientos_tienda', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('tienda_id');
            $table->unsignedBigInteger('venta_id')->nullable();

            // Dinero
            $table->decimal('cantidad', 12, 2);

            // Tipo de pago: efectivo, tarjeta, transferencia, etc.
            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia', 'otro']);

            // Tipo de movimiento: entrada o salida
            $table->enum('tipo_movimiento', ['entrada', 'salida']);

            // Descripción u observación
            $table->string('descripcion')->nullable();

            // Auditoría
            $table->unsignedBigInteger('user_id')->nullable();  // Quién registró el movimiento

            $table->timestamps();
            $table->softDeletes();

            // Opcionalmente puedes agregar foreign keys:
            // $table->foreign('tienda_id')->references('id')->on('tiendas');
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('movimientos_tienda');
    }
};
