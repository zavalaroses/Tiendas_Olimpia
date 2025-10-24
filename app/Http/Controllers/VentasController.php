<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Salida;
use App\Models\SalidaProducto;
use App\Models\catalogos\Chofer;
use Log;
use Carbon\Carbon;


class VentasController extends Controller
{
    public function getVentas(){
        return view('ventas.index');
    }
    public function getDataSalidas(){
        try {
            $salidas = Salida::leftJoin('salida_producto as sp','sp.id_salida','=','salidas.id')
                ->leftJoin('muebles as m','m.id','=','sp.id_mueble')
                ->leftJoin('clientes as c','c.id','=','salidas.cliente_id')
                ->leftJoin('tiendas as t','t.id','=','sp.id_tienda')
                ->select(
                    'salidas.id',
                    't.nombre as tienda',
                    'm.nombre as mueble',
                    'salidas.estatus as estatus',
                    'sp.cantidad as cantidad',
                    DB::raw("CONCAT(c. nombre,' ',c.apellidos) as cliente"),
                    'salidas.fecha_entrega',
                )
                ->orderBy('salidas.id')
            ->get();

            return response()->json($salidas,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getChoferesEnvio(){
        try {
            $choferes = Chofer::select('id',DB::raw("CONCAT(nombre,' ',apellidos) as chofer"))
            ->where('tienda_id',Auth::user()->tienda_id)
            ->get();
            return response()->json($choferes,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
