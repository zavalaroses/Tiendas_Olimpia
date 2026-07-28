<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreFinanciero extends Model
{
    use HasFactory;

    protected $table = 'cierres_financieros';
    
    protected $fillable = [
        'tienda_id',
        'fecha',
        'inventario',
        'caja',
        'bancos',
        'apartados',
        'saldo_favor',
        'adeudos',
        'balance',
    ];

    protected $cast = [
        'fecha' => 'date',
    ];
}
