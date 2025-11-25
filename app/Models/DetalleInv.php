<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleInv extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'detalle_ingresos';
    protected $fillable = [
        'ingreso_id',
        'mueble_id',
        'cantidad',
    ];  
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
