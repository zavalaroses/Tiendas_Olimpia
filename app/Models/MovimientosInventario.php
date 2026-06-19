<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosInventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'tienda_id',
        'mueble_id',
        'tipo',
        'cantidad',
        'cantidad_movimiento',
        'costo_unitario',
        'referencia_tipo',
        'fecha_movimiento',
    ];

    protected $cast = [
        'fecha_ovimiento' => 'datetime',
    ];
}
