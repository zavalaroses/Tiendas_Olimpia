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
        Schema::table('garantias', function (Blueprint $table) {

            
            if (Schema::hasColumn('garantias', 'cliente_id')) {
                $table->dropForeign(['cliente_id']);
                $table->dropColumn('cliente_id');
            }

           
            $table->unsignedBigInteger('venta_id')->nullable()->after('id');

           
            $table->foreign('venta_id')
                  ->references('id')
                  ->on('apartados')
                  ->nullOnDelete();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garantias', function (Blueprint $table) {

            $table->dropForeign(['venta_id']);
            $table->dropColumn('venta_id');

            $table->unsignedBigInteger('cliente_id')->nullable();

            $table->foreign('cliente_id')
                  ->references('id')
                  ->on('clientes')
                  ->nullOnDelete();
        });
    }
};
