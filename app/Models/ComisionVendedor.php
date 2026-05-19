<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComisionVendedor extends Model
{
    use HasFactory;

    protected $table = 'comisiones_vendedores';
    protected $fillable = [
        'tienda_id',
        'usuario_id',
        'apartado_id',
        'salida_id',
        'monto_venta',
        'porcentaje',
        'monto_comision',
        'fecha_entrega',
        'pagada',
        'fecha_pago',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
