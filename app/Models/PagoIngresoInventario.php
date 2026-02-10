<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagoIngresoInventario extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'pagos_ingresos_inventario';
    protected $fillable = [
        'ingreso_id',
        'tienda_id',
        'usuario_id',
        'monto',
        'metodo_pago',
        'fecha',
        'descripcion',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
