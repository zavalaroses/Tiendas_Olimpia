<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Log;

class GarantiasController extends Controller
{
    public function getGarantias  (){
        return view('garantias.index');
    }
    public function getMueblesByTienda($tienda = null){
        $idTienda = $tienda ? $tienda : Auth::user()->tienda_id;
        $muebles = DB::table('inventario_tienda as i')
            ->join('muebles as m','m.id','=','i.mueble_id')
            ->select('m.id','m.nombre')
            ->where('i.tienda_id',$idTienda)
            ->whereNull('i.deleted_at')
        ->get();
        return response()->json($muebles,200);
    }
}
