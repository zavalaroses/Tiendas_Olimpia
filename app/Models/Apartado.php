<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apartado extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'apartados';
    protected $fillable = [
        'cliente_id',
        'mueble_id',
        'tienda_id',
        'monto_anticipo',
        'monto_restante',
        'usuario_id',
        'fecha_apartado',
        'liquidado_at',
        'recibo_pdf',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
