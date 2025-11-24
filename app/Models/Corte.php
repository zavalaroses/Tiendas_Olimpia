<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Corte extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cortes';
    protected $fillable = [
        'tienda_id',     
        'user_id',  
        'total_efectivo',  
        'total_cuenta',   
        'total_general',          
        'efectivo_contado',      
        'diferencia',           
        'egresos', 
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
