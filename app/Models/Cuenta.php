<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuenta extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'movimientos_cuenta';
    protected $fillable = [
        'tienda_id',     
        'user_id',  
        'monto',  
        'tipo_movimiento',
        'concepto',       
        'referencia',     
        'descripcion',           
        'fecha_movimiento', 
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
