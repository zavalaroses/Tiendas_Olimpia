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
       
        // Migración: tiendas
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ubicacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
         // Modificacion usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('tienda_id')->after('id');
            $table->string('apellidos')->after('name');
            $table->unsignedTinyInteger('rol')->after('email');
            $table->softDeletes();
            $table->foreign('tienda_id')->references('id')->on('tiendas')->onDelete('cascade');
        });
        // Migración: proveedores
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('contacto')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Migración: muebles
        Schema::create('muebles', function (Blueprint $table) {
            $table->id(); 
            $table->string('nombre');
            $table->string('codigo'); 
            $table->text('descripcion')->nullable(); 
            $table->decimal('precio', 10, 2); 
            $table->timestamps(); 
            $table->softDeletes();
        });

        // Migración: estatus_inventario
        Schema::create('estatus_inventario', function (Blueprint $table) {
            $table->id();
            $table->integer('id_inventario_tienda');
            $table->string('estatus'); // Ej. disponible, apartado, etc.
            $table->timestamps();
            $table->softDeletes();
        });
        

        // Migración: inventario_tienda
        Schema::create('inventario_tienda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained()->onDelete('cascade');
            $table->foreignId('mueble_id')->constrained()->onDelete('cascade');
            $table->foreignId('estatus_id')->constrained('estatus_inventario');
            $table->integer('cantidad')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Migración: ingresos_inventario
        Schema::create('ingresos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas');
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha');
            $table->string('codigo_trazabilidad')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        // Migración: ingreso_muebles
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingreso_id')->constrained('ingresos_inventario')->onDelete('cascade');
            $table->foreignId('mueble_id')->constrained();
            $table->integer('cantidad');
            $table->timestamps();
            $table->softDeletes();
        });
        // Migración: clientes
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas');
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('correo');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Migración: historial_estatus_inventario
        Schema::create('apartados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('mueble_id')->constrained('muebles');
            $table->foreignId('tienda_id')->constrained('tiendas');
            $table->integer('cantidad');
            $table->decimal('monto_anticipo', 10, 2);
            $table->decimal('monto_restante', 10, 2);
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_apartado');
            $table->date('liquidado_at');
            $table->string('recibo_pdf');
            $table->timestamps();
            $table->softDeletes();
        });
        

        // Migración: entregas
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('apartado_id')->constrained('apartados');
            $table->foreignId('chofer_id');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_entrega');
            $table->string('pdf_entrega');
            $table->timestamps();
            $table->softDeletes();
        });

        // Migración: garantias
        Schema::create('garantias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mueble_id')->constrained('muebles');
            $table->foreignId('tienda_id')->constrained('tiendas');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->text('motivo');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Migracion roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tienda_id']);
            $table->dropColumn(['tienda_id', 'apellidos', 'rol', 'deleted_at']);
        });
        Schema::dropIfExists('garantias');
        Schema::dropIfExists('salidas');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('detalle_ingresos');
        Schema::dropIfExists('ingresos_inventario');
        Schema::dropIfExists('inventario_tienda');
        Schema::dropIfExists('apartados');
        Schema::dropIfExists('estatus_inventario');
        Schema::dropIfExists('muebles');
        Schema::dropIfExists('proveedores');
        Schema::dropIfExists('tiendas');
        
        
        
        
        
        
        
        
        
        
        
        
    }
};
