<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ApartadoMueble;
use App\Models\Apartado;
use App\Models\InventarioTienda;
use App\Models\Salida;
use App\Models\SalidaProducto;
use App\Models\Cliente;
use App\Models\Transaccion;
use App\Models\Cuenta;
use App\Models\catalogos\Chofer;
use Log;
use Carbon\Carbon;


class VentasController extends Controller
{
    public function getVentas(){
        return view('ventas.index');
    }
    public function getDataSalidas($tienda = null){
        try {

            $idTienda = $tienda ? $tienda : Auth::user()->tienda_id;

            $salidas = Salida::leftJoin('clientes as c','c.id','=','salidas.cliente_id')
                ->leftJoin('apartados as a','a.id','=','salidas.apartado_id')
                ->leftJoin('tiendas as t','t.id','=','a.tienda_id')
                ->select(
                    'a.id',
                    't.nombre as tienda',
                    't.id as id_tienda',
                    'salidas.estatus as estatus',
                    'a.monto_anticipo',
                    'a.monto_restante',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) as cliente"),
                    'salidas.fecha_entrega',
                    'salidas.id as id_salida',
                )
                ->when($idTienda, function($q) use($idTienda){
                    $q->where('a.tienda_id',$idTienda);
                })
                ->orderBy('a.id','desc')
            ->get();

            return response()->json($salidas,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getChoferesEnvio($tienda = null){
        try {
            $idTienda = $tienda ? $tienda : Auth::user()->tienda_id;
            $choferes = Chofer::select('id',DB::raw("CONCAT(nombre,' ',apellidos) as chofer"))
            ->where('tienda_id',$idTienda)
            ->get();
            return response()->json($choferes,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getDataToSalida($id){
        try {
            $salidas = Salida::leftJoin('apartados as a','a.id','=','salidas.apartado_id')
            ->leftJoin('clientes as c','c.id','=','salidas.cliente_id')
            ->where('a.id',$id)->first();

            $choferes = Chofer::select('id',DB::raw("CONCAT(nombre,' ',apellidos) as chofer"))
            ->where('tienda_id',$salidas->tienda_id ? $salidas->tienda_id : Auth::user()->tienda_id)
            ->get();
            $response = [
                'data'=>$salidas,
                'chofer'=>$choferes
            ];
            return response()->json($response,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function postAgendarSalida(Request $request){
        try {
            $request->validate([
                'chofer'=>'required',
                'fechaSalida' => 'required',
                'id' => 'required',  
            ]);

            DB::beginTransaction();

            Salida::where('apartado_id',$request->id)->update([
                'fecha_entrega'=>$request->fechaSalida,
                'chofer_id'=>$request->chofer
            ]);

            $response = [
                'icon' =>'success',
                'title' =>'Exito',
                'text' => 'Salida programada con exito.',
            ];

            DB::commit();

            return response()->json($response,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function postAddVenta(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'telefono' => 'required',
                'direccion' => 'required|string|max:255',
                'chofer' => 'required',
                'total' => 'required',
                'fecha_envio' => 'required',
                'id' => 'required|array|min:1',
                'id.*' => 'required|integer',
                'producto' => 'required|array|min:1',
                'producto.*' => 'required|string|max:255',
                'cantidad' => 'required|array|min:1',
                'cantidad.*' => 'required|numeric|min:1',
                'envio' => 'nullable|numeric|min:0',
            ]);
            if (Auth::user()->tienda_id == null && !$request->id_tienda && $request->id_tienda == null) {
                # code...
                $response = [
                    'icon'=>'warning',
                    'title'=>'Oops.',
                    'text'=>'Es necesario seleccionar una tienda.',
                ];
                return response()->json($response,200);
            }
            $idtienda = $request->id_tienda ? $request->id_tienda : Auth::user()->tienda_id;
            $idCliente = null;
            $oldCliente = Cliente::where([
                'nombre'=>$request->nombre,
                'apellidos'=>$request->apellidos
            ])->first();

            if ($oldCliente) {
                $idCliente = $oldCliente->id;
            }else {
                $newCliente = Cliente::create([
                    'tienda_id'=>$idtienda,
                    'nombre'=>$request->nombre,
                    'apellidos'=>$request->apellidos,
                    'telefono'=>$request->telefono,
                    'direccion'=>$request->direccion,
                ]);
                $idCliente = $newCliente->id;
            }

            $apartado =  Apartado::create([
                'cliente_id'=>$idCliente,
                'tienda_id'=>$idtienda,
                'monto_anticipo'=>$request->total,
                'monto_restante'=>0,
                'usuario_id'=>Auth::user()->id,
                'fecha_apartado'=>Carbon::now()->toDateString(),
                'costo_envio' => $request->envio ?? 0,
            ]);
            for ($i=0; $i < count($request->id) ; $i++) { 
                ApartadoMueble::create([
                    'id_apartado'=>$apartado->id,
                    'id_mueble'=>$request->id[$i],
                    'cantidad'=>$request->cantidad[$i],
                    'estatus'=>'Apartado',
                ]);
                $inventario = InventarioTienda::where([
                    'tienda_id'=>$idtienda,
                    'mueble_id'=>$request->id[$i],
                ])->first();

                if ($inventario && $inventario->cantidad_stock >= $request->cantidad[$i]) {
                    # restamos inventario...
                    $inventario->decrement('cantidad_stock',$request->cantidad[$i]);
                }else {
                    $response = [
                        'icon'=>'warning',
                        'title'=>'Oops.',
                        'text'=>'Inventarios insuficiente para el mueble.',
                    ];
                    return response()->json($response,200);
                    // throw new \Exception("Inventarios insuficiente para el mueble", 1);
                }
                
            }
            Apartado::where('id',$apartado->id)->update(['liquidado_at'=>Carbon::now()->toDateString()]);
            Apartado::where('id',$apartado->id)->delete();

            $salida = Salida::create([
                'cliente_id'=>$idCliente,
                'apartado_id'=>$apartado->id,
                'chofer_id'=>$request->chofer,
                'usuario_id'=>Auth::user()->id,
                'fecha_entrega' => Carbon::parse($request->fecha_envio)->format('Y-m-d'),
                'pdf_entrega'=>null,
                'estatus'=>'Por entregar'
            ]);
            for ($i=0; $i < count($request->id); $i++) { 
                # agregamos los muebles que se agregan a la venta...
                SalidaProducto::create([
                    'id_salida'=>$salida->id,
                    'id_mueble'=>$request->id[$i],
                    'id_tienda'=>$idtienda,
                    'cantidad'=>$request->cantidad[$i],
                    'id_usuario'=>Auth::user()->id
                ]);
                InventarioTienda::where('tienda_id',$idtienda)
                    ->where('mueble_id',$request->id[$i])
                    ->increment('por_entregar',$request->cantidad[$i]);
            }
            $transaccion = Transaccion::create([
                'tienda_id' =>$idtienda,
                'venta_id'=>$apartado->id,
                'cantidad'=>$request->total,
                'tipo_pago'=>$request->forma_pago,
                'tipo_movimiento'=>'entrada',
                'descripcion'=>'Venta',
                'user_id'=>Auth::user()->id,
            ]);
            if ($request->forma_pago != 1) {
                # agregamos el movimiento a la cuenta...
                $transaccionRef = 2; // tarjeta
                if ($request->forma_pago == 2) {
                    $transaccionRef = 'tarjeta';
                }elseif ($request->forma_pago == 3) {
                    $transaccionRef = 'transferencia';
                }
                Cuenta::create([
                    'tienda_id'=>$idtienda,     
                    'user_id'=>Auth::user()->id,  
                    'monto'=>$request->total,  
                    'tipo_movimiento'=>'entrada',
                    'concepto'=>$transaccionRef,       
                    'referencia'=> $transaccion->id,   
                    'descripcion'=>'Venta',           
                ]); 
            }

            $response = [
                'icon' =>'success',
                'title' =>'Exito',
                'text' => 'Venta generada con exito.',
            ];
            DB::commit();
            return response()->json($response,200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function postFinalizarVenta(Request $request){
        try {
            $checkChofer = VentasController::checkChoferAsignado($request->id);
            if (!$checkChofer) {
                # retornamos respuesta de que hay que asignar chofer de salida...
                $response = [
                    'icon'=>'warning' ,
                    'title'=>'Advertencia' ,
                    'text'=>'Es necesario asignar un chofer de entrega',
                ];
                return response()->json($response,200);
            }
            DB::beginTransaction();
            $salida = Salida::join('apartados as a','a.id','=','salidas.apartado_id')
                ->join('apartado_muebles as ap','ap.id_apartado','=','a.id')
                ->select('a.tienda_id','ap.id_mueble','ap.cantidad')
                ->where('salidas.apartado_id',$request->id)
            ->get();
            
            foreach ($salida as  $mueble) {
                # disminuimos la cantidad en inventario tienda...
                InventarioTienda::where([
                    'tienda_id'=>$mueble->tienda_id,
                    'mueble_id'=>$mueble->id_mueble,
                ])->decrement('por_entregar',$mueble->cantidad);
            }
            Salida::where('apartado_id',$request->id)->update([
                'estatus'=>'Entregado',
            ]);
            DB::commit();
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Se entrego con exito.',
            ];
            return response()->json($response,200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public static function checkChoferAsignado($idVenta){
        $chofer = Salida::where('apartado_id', $idVenta)->value('chofer_id');
        return $chofer;
    }
    public function getDetalleVenta($id){
        $detalle = Salida::leftJoin('clientes as c','c.id','=','salidas.cliente_id')
            ->leftJoin('choferes as ch','ch.id','=','salidas.chofer_id')
            ->leftJoin('users as u','u.id','=','salidas.usuario_id')
            ->leftJoin('apartados as a','a.id','=','salidas.apartado_id')
            ->leftJoin('tiendas as t','t.id','=','a.tienda_id')
            ->select(
                'a.id as id_nota',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) as cliente"),
                't.nombre as tienda',
                'ch.nombre as chofer',
                'a.monto_anticipo',
                'a.monto_restante',
                'a.costo_envio',
                'salidas.fecha_entrega as fecha',
                'a.liquidado_at',
                'salidas.estatus',
                'u.name as usuario'
            )
            ->where('salidas.apartado_id',$id)
        ->first();

        $productos = SalidaProducto::leftJoin('salidas as s','s.id','=','salida_producto.id_salida')
            ->leftJoin('muebles as m','m.id','=','salida_producto.id_mueble')
            ->select(
                'm.nombre as mueble',
                'salida_producto.cantidad',
                'm.precio'
            )
            ->where('s.apartado_id',$id)
        ->get();

        $pagos = Transaccion::withTrashed()
            ->leftJoin('users as u','u.id','=','movimientos_tienda.user_id')
            ->select(
                'cantidad',
                'tipo_pago',
                'descripcion',
                'movimientos_tienda.created_at as fecha',
                'u.name as usuario'
            )
            ->where('tipo_movimiento','entrada')
            ->where('venta_id',$id)
        ->get();

        $response = [
            'detalle'=>$detalle,
            'productos'=>$productos,
            'pagos'=>$pagos
        ];
        return response()->json($response,200);
    }
    public function getDatosGarantiaVenta($id){
        $data = Salida::leftJoin('salida_producto as sp','sp.id_salida','=','salidas.id')
            ->leftJoin('muebles as m','m.id','=','sp.id_mueble')
            ->select(
                'm.id as id_mueble',
                'm.nombre as mueble',
            )
            ->where('salidas.apartado_id',$id)
        ->get();
        return response()->json($data,200);
    }
    
}
