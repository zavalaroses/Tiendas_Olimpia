<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BitacoraHelper
{
    public static function registrar($data){
        try {
            DB::table('bitacora')->insert([
                'tienda_id' => $data['tienda_id'] ?? auth()->user()->tienda_id,
                'usuario_id' => Auth::id(),
                'accion' => $data['accion'],
                'modulo' => $data['modulo'],
                'modelo' => $data['modelo'] ?? null,
                'datos_anteriores' => $data['datos_anteriores'] ?? null,
                'datos_nuevos' => $data['datos_nuevos'] ?? null,
                'monto' => $data['monto'] ?? null,
                'tipo_movimiento' => $data['tipo_movimiento'] ?? 'ninguno',
                'descripcion' => $data['descripcion'] ?? null,
                'ip' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
}