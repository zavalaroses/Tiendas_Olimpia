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
        Schema::create('depositos_caja_cuenta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('movimiento_tienda_id')->constrained('movimientos_tienda')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('movimiento_cuenta_id')->constrained('movimientos_cuenta')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('monto', 10, 2);
            $table->enum('estatus', ['aplicado','cancelado'])->default('aplicado');
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
        Schema::dropIfExists('depositos_caja_cuenta');
    }
};
