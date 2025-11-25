<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventarioTienda extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventario_tienda';
    protected $fillable = [
        'tienda_id',
        'mueble_id',
        'estatus_id',
        'cantidad_stock',
        'cantidad_apartados',
        'por_entregar'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
