<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaccion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'movimientos_tienda';
    protected $fillable = [
        'tienda_id',
        'venta_id',
        'cantidad',
        'tipo_pago',
        'tipo_movimiento',
        'descripcion',
        'user_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
