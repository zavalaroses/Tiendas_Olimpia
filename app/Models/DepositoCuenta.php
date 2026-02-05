<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepositoCuenta extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'depositos_caja_cuenta';
    protected $fillable = [
        'tienda_id',
        'movimiento_tienda_id',
        'movimiento_cuenta_id',
        'user_id',
        'monto',
        'estatus',
        'descripcion'
    ];  
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
