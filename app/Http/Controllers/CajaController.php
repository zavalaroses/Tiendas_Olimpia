<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaccion;
use Carbon\Carbon;
use Log;

class CajaController extends Controller
{
    public function getIndex(){
        return view('caja.index');
    }
    public function getData($tienda = null){
        $idTienda = $tienda ? $tienda : Auth::user()->tienda_id;
        $data = Transaccion::join('users as u','u.id','=','user_id')
            ->select(
                'movimientos_tienda.id',
                'movimientos_tienda.tienda_id',
                'venta_id',
                'cantidad',
                'tipo_pago',
                'tipo_movimiento',
                'descripcion',
                'u.name as usuario',
                'movimientos_tienda.created_at as fecha'
            )
            ->when($idTienda,function($q) use($idTienda){
                $q->where('movimientos_tienda.tienda_id',$idTienda);
            })
            ->orderBy('movimientos_tienda.created_at')
        ->get();
        return response()->json($data,200);
    }
    public function getResumenCorte($tienda = null){
        $idTienda = $tienda ?: Auth::user()->tienda_id;

        //  Función que arma la misma Query base
        $baseQuery = fn() => Transaccion::when($idTienda, function($q) use ($idTienda) {
            $q->where('tienda_id', $idTienda);
        });

        //  INGRESOS
        $efectivo = $baseQuery()
            ->where('tipo_movimiento', 'entrada')
            ->where('tipo_pago', 'efectivo')
            ->sum('cantidad');

        $cuenta = $baseQuery()
            ->where('tipo_movimiento', 'entrada')
            ->whereIn('tipo_pago', ['tarjeta', 'transferencia'])
            ->sum('cantidad');

        //  EGRESOS
        $egresosEfectivo = $baseQuery()
            ->where('tipo_movimiento', 'salida')
            ->where('tipo_pago', 'efectivo')
            ->sum('cantidad');

        $egresosCuenta = $baseQuery()
            ->where('tipo_movimiento', 'salida')
            ->whereIn('tipo_pago', ['tarjeta', 'transferencia'])
            ->sum('cantidad');

        //  CÁLCULOS
        $totalEfectivo = $efectivo - $egresosEfectivo;
        $totalCuenta = $cuenta - $egresosCuenta;

        $ingresosTotales = $efectivo + $cuenta;
        $egresosTotales = $egresosEfectivo + $egresosCuenta;

        $totalGeneral = $ingresosTotales - $egresosTotales;

        return response()->json([
            'efectivo'        => $efectivo,
            'cuenta'          => $cuenta,
            'egresosEfectivo' => $egresosEfectivo,
            'egresosCuenta'   => $egresosCuenta,
            'ingresoTotal'    => $ingresosTotales,
            'egresoTotal'     => $egresosTotales,
            'totalEfectivo'   => $totalEfectivo,
            'totalCuenta'     => $totalCuenta,
            'totalGeneral'    => $totalGeneral,
            'userRol'         => Auth::user()->rol
        ], 200);
    }


}
