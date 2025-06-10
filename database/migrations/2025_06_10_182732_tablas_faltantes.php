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
        //
        // Migración: clientes
        Schema::create('choferes', function (Blueprint $table) {
            $table->id();
            $table->integer('tienda_id');
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

         // Migración: apartado_muebles
        Schema::create('apartado_muebles', function (Blueprint $table) {
            $table->id();
            $table->integer('id_apartado');
            $table->integer('id_mueble');
            $table->integer('cantidad');
            $table->string('estatus'); 
            $table->timestamps();
            $table->softDeletes();
        });

         // Migración: venta_producto
        Schema::create('salida_producto', function (Blueprint $table) {
            $table->id();
            $table->integer('id_salida');
            $table->integer('id_mueble');
            $table->integer('id_tienda');
            $table->integer('cantidad');
            $table->integer('id_usuario'); 
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
        Schema::dropIfExists('choferes');
        Schema::dropIfExists('apartado_muebles');
        Schema::dropIfExists('salida_producto');
    }
};
