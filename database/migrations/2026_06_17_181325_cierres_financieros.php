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
        schema::create('cierres_financieros', function(Blueprint $table){
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas');
            $table->date('fecha');
            $table->decimal('inventario',14, 2);
            $table->decimal('caja',14, 2);
            $table->decimal('bancos',14, 2);
            $table->decimal('apartados',14, 2);
            $table->decimal('saldo_favor',14, 2);
            $table->decimal('adeudos',14, 2);
            $table->decimal('balance',14, 2);
            $table->timestamps();
            $table->unique(['tienda_id','fecha']);
            $table->index('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('cierres_financieros');
    }
};
