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
        Schema::table('inventario_tienda', function(Blueprint $table){
            $table->integer('en_garantia')->nullable()->default(0)->after('por_entregar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario_tienda', function(Blueprint $table){
            $table->dropColumn('en_garantia');
        });
    }
};
