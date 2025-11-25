<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chofer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'choferes';
    protected $fillable = [
        'tienda_id',
        'nombre',
        'apellidos',
        'correo',   
        'telefono',
        'direccion',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
