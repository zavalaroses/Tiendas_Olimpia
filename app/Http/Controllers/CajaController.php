<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaccion;
use App\Models\Corte;
use App\Models\Cuenta;
use Carbon\Carbon;
use Log;

class CajaController extends Controller
{
    public function getIndex(){
        return view('caja.index');
    }
    public function getManejoCuenta(){
        return view('caja.indexCuenta');
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
            ->where(function($q){
                if (Auth::user()->rol == 2) {
                    # si es usuario tienga quitamos los gastos de la cuenta...
                    $q->where('tipo_movimiento','!=','salida')
                        ->orWhere('tipo_pago','!=','tarjeta');
                }
            })
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

        $efectivoApertura = Corte::where('tienda_id', $idTienda)
            ->orderByDesc('id') // o fecha_cierre
        ->value('saldo_final') ?? 0;

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
            'userRol'         => Auth::user()->rol,
            'efectivoApertura'=> $efectivoApertura
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
                'saldo_final'=>$request->saldoFinal
            ]);
            Transaccion::where('tienda_id',$idTienda)->whereNull('deleted_at')->update([
                'corte_caja_id'=>$corte->id
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
            $transaccion = Transaccion::create([
                'tienda_id' =>$idTienda,
                'venta_id'=>null,
                'cantidad'=>$request->cantidad,
                'tipo_pago'=>$tipoPago,
                'tipo_movimiento'=>'salida',
                'descripcion'=>$request->descripcion,
                'user_id'=>Auth::user()->id,
            ]);

            if ($tipoPago == 'tarjeta') {
                # agregamos el movimiento a la cuenta...
                Cuenta::create([
                    'tienda_id'=>$idTienda,     
                    'user_id'=>Auth::user()->id,  
                    'monto'=>$request->cantidad,  
                    'tipo_movimiento'=>'salida',
                    'concepto'=>'tarjeta',       
                    'referencia'=> $transaccion->id,     
                    'descripcion'=>$request->descripcion,           
                ]); 
            }

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
                ->orderBy('cortes.id','DESC')
            ->get();
            return response()->json($cortes,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getDetalleCorte($id){
        $corte = DB::table('cortes as c')
            ->join('tiendas as t','t.id','=','c.tienda_id')
            ->join('users as u', 'u.id','=','c.user_id')
            ->select(
                'c.id as id',
                't.nombre as tienda',
                'u.name as usuario',
                'c.created_at as fecha',
                'c.total_general',
                'c.total_efectivo',
                'c.efectivo_contado',
                'c.diferencia'
            )
            ->where('c.id',$id)
        ->first();

        $transacciones = DB::table('movimientos_tienda as mt')
            ->join('users as u','u.id','=','mt.user_id')
            ->leftJoin('apartados as a','a.id','=','mt.venta_id')
            ->leftJoin('clientes as c','c.id','=','a.cliente_id')
            ->select(
                DB::raw("IFNULL(a.id,'-') as id"),
                'mt.id as id_transaccion',
                'mt.tipo_movimiento as tipo',
                'mt.cantidad as monto',
                'mt.tipo_pago as pago',
                'mt.created_at as fecha',
                'u.name as usuario',
                DB::raw("IFNULL(CONCAT(c.nombre,' ',c.apellidos),'-') as cliente")

            )
            ->where('mt.corte_caja_id',$id)
            ->orderBy('id','DESC')
        ->get();

        $totalEntrada = DB::table('movimientos_tienda as mt')
            ->join('users as u','u.id','=','mt.user_id')
            ->where('mt.tipo_movimiento','entrada')
            ->where('mt.corte_caja_id',$id)
            ->sum('mt.cantidad');
        $totalSalida = DB::table('movimientos_tienda as mt')
            ->join('users as u','u.id','=','mt.user_id')
            ->where('mt.tipo_movimiento','salida')
            ->where('mt.corte_caja_id',$id)
            ->sum('mt.cantidad');

        $response = [
            'corte'=>$corte,
            'transacciones'=>$transacciones,
            'totalEntrada'=>$totalEntrada,
            'totalSalida'=>$totalSalida,
        ];
        return response()->json($response,200);
        
    }
    public function getDataCuenta($tienda = null){
        $idTienda = $tienda ?: Auth::user()->tienda_id;
        $data = Cuenta::leftJoin('tiendas as t','t.id','=','movimientos_cuenta.tienda_id')
            ->leftJoin('users as u','u.id','=','movimientos_cuenta.user_id')
            ->select(
                'movimientos_cuenta.id',
                't.nombre as tienda',
                'u.name as usuario',
                'monto',
                'tipo_movimiento',
                'concepto',
                'referencia',
                'descripcion',
                'fecha_movimiento as fecha',
            )
            ->when($idTienda, function($q)use($idTienda){
                $q->where('movimientos_cuenta.tienda_id',$idTienda);
            })
            ->orderBy('movimientos_cuenta.id','DESC')
            ->get();

        $entradas = Cuenta::where('tipo_movimiento','entrada')
            ->when($idTienda, function($q)use($idTienda){
                $q->where('tienda_id',$idTienda);
            })
        ->sum('monto');
        $salidas = Cuenta::where('tipo_movimiento','salida')
            ->when($idTienda, function($q)use($idTienda){
                $q->where('tienda_id',$idTienda);
            })
        ->sum('monto');
        $saldoCuenta = $entradas - $salidas;

        $response = [
            'data'=>$data,
            'entradas'=>$entradas,
            'salidas'=>$salidas,
            'saldoCuenta'=>$saldoCuenta
        ];

        return response()->json($response,200);

    }
    public function postAddIngresoCuenta(Request $request){
        try {
            $request->validate([
                'cantidad' => 'required|numeric',
                'descripcion' => 'required',
            ]);
            DB::beginTransaction();

            Cuenta::create([
                'tienda_id'=>$request->tienda,     
                'user_id'=>Auth::user()->id,  
                'monto'=>$request->cantidad,  
                'tipo_movimiento'=>'entrada',
                'concepto'=>'ajuste',            
                'descripcion'=>$request->descripcion,           
            ]); 

            DB::commit();

            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Ingreso realizado correctamente',
            ];
            return response()->json($response,200);


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function getDetalleTransaccion($id){
        $transaccion = DB::table('movimientos_tienda as mt')
            ->leftJoin('tiendas as t','t.id','=','mt.tienda_id')
            ->leftJoin('apartados as a','a.id','=','mt.venta_id')
            ->leftJoin('users as u','u.id','=','mt.user_id')
            ->leftJoin('clientes as c','c.id','=','a.cliente_id')
            ->leftJoin('apartado_muebles as am','am.id_apartado','=','a.id')
            ->leftJoin('muebles as m','m.id','=','am.id_mueble')
            ->select(
                'a.id as id_nota',
                DB::raw('(a.monto_anticipo + a.monto_restante) as total_nota'),
                'a.monto_anticipo',
                'a.monto_restante',
                'a.costo_envio',
                'a.fecha_apartado',
                'a.liquidado_at',
                'mt.cantidad as monto',
                'mt.tipo_pago',
                'mt.tipo_movimiento',
                'mt.descripcion',
                't.nombre as tienda',
                DB::raw("IFNULL(CONCAT(c.nombre,' ',c.apellidos),'-') as cliente"),
                DB::raw("IFNULL(u.name,'-') as usuario"),
                DB::raw("GROUP_CONCAT(m.nombre SEPARATOR ', ') as muebles")
            )
            ->where('mt.id',$id)
            ->groupBy('a.id','a.monto_anticipo','a.monto_restante','a.costo_envio','a.fecha_apartado','a.liquidado_at','mt.cantidad','mt.tipo_pago','mt.tipo_movimiento','mt.descripcion','t.nombre','c.nombre','c.apellidos','u.name')
        ->first();
        return response()->json($transaccion,200);
    }


}
