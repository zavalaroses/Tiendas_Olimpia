<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalidaProducto extends Model
{
     use HasFactory;
    use SoftDeletes;

    protected $table = 'salida_producto';
    protected $fillable = [
        'id_salida',
        'id_mueble',
        'id_tienda',
        'cantidad',
        'id_usuario',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
