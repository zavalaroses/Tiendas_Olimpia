<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApartadoMueble extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'apartado_muebles';
    protected $fillable = [
        'id_apartado',
        'id_mueble',
        'cantidad',
        'estatus',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
