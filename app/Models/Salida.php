<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salida extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'salidas';
    protected $fillable = [
        'cliente_id',
        'apartado_id',
        'chofer_id',
        'usuario_id',
        'fecha_entrega',
        'pdf_entrega',
        'estatus',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
