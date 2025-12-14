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
        Schema::table('cortes', function (Blueprint $table) {
            $table->decimal('saldo_final', 12, 2)
                  ->nullable()
                  ->default(0)
                  ->after('diferencia'); // ajusta el campo de referencia si quieres
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cortes', function (Blueprint $table) {
            //
            $table->dropColumn('saldo_final');
        });
    }
};
