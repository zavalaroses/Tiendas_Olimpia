<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Garantias extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'garantias';
    protected $fillable = [
        			
        'venta_id',
        'mueble_id',
        'tienda_id',
        'motivo',
        'cantidad',
        'usuario_id',
        'fecha'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
