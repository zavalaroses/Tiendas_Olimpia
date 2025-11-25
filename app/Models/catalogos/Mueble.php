<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mueble extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'muebles';
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'precio'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
