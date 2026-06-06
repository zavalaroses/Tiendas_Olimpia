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
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tienda_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('accion');
            $table->string('modulo');
            $table->string('modelo');
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->decimal('monto', 12,2)->nullable();
            $table->enum('tipo_movimiento', ['cargo','abono','ajuste','ninguno','otros','entrada'])->default('ninguno');
            $table->text('descripcion')->nullable();
            $table->ipAddress('ip')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};
