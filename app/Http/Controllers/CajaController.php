<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaccion;
use App\Models\Corte;
use Carbon\Carbon;
use Log;

class CajaController extends Controller
{
    public function getIndex(){
        return view('caja.index');
    }
    public function getHistorialCajas(){
        return view('historial.index');
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
            ->orderBy('movimientos_tienda.created_at', 'DESC')
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
    public function cerrarCorte(Request $request){
        try {
            $request->validate([
                'efectivo_esperado'  => 'required|numeric',
                'efectivo_contado'   => 'required|numeric',
                'observaciones'      => 'nullable|string'
            ]);
            DB::beginTransaction();
            $idTienda = $request->tienda_id != '' ? $request->tienda_id : Auth::user()->tienda_id;
            // guardamos el corte
            $totalGeneral = $request->ingresos_efectivo + $request->ingresos_tarjeta;
            $corte = Corte::create([
                'tienda_id' => $idTienda,     
                'user_id' => Auth::user()->id,  
                'total_efectivo' => $request->efectivo_esperado,  
                'total_cuenta'=>$request->ingresos_tarjeta,   
                'total_general'=>$totalGeneral,          
                'efectivo_contado' => $request->efectivo_contado,      
                'diferencia' =>$request->corte_diferencia ,           
                'egresos' =>$request->salidas, 
            ]);

            Transaccion::where('tienda_id',$idTienda)->delete();
            DB::commit();

            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Corte realizado correctamente',
            ];
            return response()->json($response,200);
        } catch (\Throwable $th) {
           DB::rollback();
            throw $th;
        }
    }
    public function postAddEgreso(Request $request){
        $request->validate([
            'cantidad' => 'required|numeric',
            'descripcion' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $idTienda = $request->tienda != '' ? $request->tienda : Auth::user()->tienda_id;
            $tipoPago = Auth::user()->rol == 1 ? 'tarjeta' : 'efectivo';
            Transaccion::create([
                'tienda_id' =>$idTienda,
                'venta_id'=>null,
                'cantidad'=>$request->cantidad,
                'tipo_pago'=>$tipoPago,
                'tipo_movimiento'=>'salida',
                'descripcion'=>$request->descripcion,
                'user_id'=>Auth::user()->id,
            ]);
            DB::commit();

            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Egreso realizado correctamente',
            ];
            return response()->json($response,200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        
    }
    public function getDataHistorialCortes(Request $request){
        try {
            $cortes = Corte::join('users as u','u.id','=','cortes.user_id')
                ->leftJoin('tiendas as t','t.id','=','cortes.tienda_id')
                ->select(
                    'cortes.id',
                    't.nombre as tienda',
                    'u.name as usuario',
                    'total_efectivo',
                    'total_cuenta',
                    'total_general',
                    'efectivo_contado',
                    'diferencia',
                    'egresos',
                    'cortes.created_at as fecha'
                )
                ->when($request->tienda, fn($q) => $q->where('cortes.tienda_id', $request->tienda))
                ->when($request->inicio, fn($q) => $q->whereDate('cortes.created_at', '>=', $request->inicio))
                ->when($request->fin, fn($q) => $q->whereDate('cortes.created_at', '<=', $request->fin))
            ->get();
            return response()->json($cortes,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
