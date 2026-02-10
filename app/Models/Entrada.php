<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrada extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ingresos_inventario';
    protected $fillable = [
        'tienda_id',
        'proveedor_id',
        'usuario_id',
        'fecha',
        'codigo_trazabilidad',
        'total_compra',
        'total_pagado',
        'estatus_pago',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
