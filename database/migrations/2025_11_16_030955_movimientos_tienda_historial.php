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
        Schema::create('movimientos_tienda_historial', function (Blueprint $table) {
            $table->id();

            // Clave referencia al movimiento original
            $table->unsignedBigInteger('movimiento_id')->nullable();

            // Datos espejeados
            $table->unsignedBigInteger('tienda_id');
            $table->unsignedBigInteger('venta_id')->nullable();
            $table->decimal('cantidad', 12, 2);
            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia', 'otro']);
            $table->enum('tipo_movimiento', ['entrada', 'salida']);
            $table->string('descripcion')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            // Datos adicionales para auditoría histórica
            $table->enum('accion', ['creado', 'actualizado', 'eliminado']);  // qué se hizo
            $table->json('data_original')->nullable();  // estado previo
            $table->json('data_nueva')->nullable();      // estado nuevo

            $table->unsignedBigInteger('performed_by')->nullable(); // quién hizo el cambio
            $table->timestamp('performed_at')->nullable();          // cuándo

            $table->timestamps();
            $table->softDeletes();

            // Opcional:
            // $table->foreign('movimiento_id')->references('id')->on('movimientos_tienda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('movimientos_tienda_historial');
    }
};
