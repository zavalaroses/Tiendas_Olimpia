<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tiendas extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'tiendas';
    protected $fillable = [
        'nombre',
        'ubicacion',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
