<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Entrada;
use App\Models\DetalleInv;
use App\Models\InventarioTienda;
use App\Models\PagoIngresoInventario;
use App\Models\Transaccion;
use App\Models\Cuenta;
use App\Models\catalogos\Mueble;
use App\Http\Controllers\CajaController;
use Carbon\Carbon;
use Log;

class InventarioController extends Controller
{
    public function getInventario (){
        return view('inventario.index');
    }
    public function postAddEntrada(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required|array|min:1',
                'id.*' => 'required|integer',
    
                'nombre' => 'required|array|min:1',
                'nombre.*' => 'required|string|max:255',
    
                'cantidad' => 'required|array|min:1',
                'cantidad.*' => 'required|numeric|min:1',

                'precio' => 'required|array|min:1',
                'precio.*'=> 'required|numeric|min:0.01',

                'proveedor'=>'required',
                'fecha_ingreso'=>'required',
                'total'=> 'required|numeric|min:0.01',
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
            $idTienda = $request->id_tienda ? $request->id_tienda : Auth::user()->tienda_id;
            $proveedor = DB::table('proveedores')->where('id',$request->proveedor)->whereNull('deleted_at')->value('nombre');
            $horaMx = Carbon::now('America/Mexico_City')->format('H:i:s');
            $c1 = $proveedor ? $proveedor : 'NP'; 
            $codigo = $request->fecha_ingreso.'-'.$horaMx.'-'.$c1;
            $entrada = Entrada::create([
                'tienda_id'=> $idTienda,
                'proveedor_id'=>$request->proveedor,
                'usuario_id'=>Auth::user()->id,
                'fecha'=> $request->fecha_ingreso,
                'codigo_trazabilidad'=>$codigo,
                'total_compra'=>$request->total,
                'total_pagado'=>0,
                'estatus_pagado'=>'pendiente',
            ]);
            
            foreach ($request->id as $index => $muebleId) {
                DetalleInv::create([
                    'ingreso_id' => $entrada->id,
                    'mueble_id' => $muebleId,
                    'cantidad' => $request->cantidad[$index],
                    'precio_compra'=>$request->precio[$index],
                ]);
                $inventarioExist = InventarioTienda::where('tienda_id',$idTienda)->where('mueble_id',$muebleId)->exists();
                if ($inventarioExist) {
                    $afectedRow = InventarioTienda::where([
                        'tienda_id'=>$idTienda,
                        'mueble_id'=>$muebleId,
                    ])->increment('cantidad_stock',$request->cantidad[$index],['updated_at'=>now()]);
                }else {
                    InventarioTienda::create([
                        'tienda_id'=>$idTienda,
                        'mueble_id'=>$muebleId,
                        'estatus_id'=>1,
                        'cantidad_stock'=>$request->cantidad[$index]
                    ]);
                }
            }
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,200);
        }
        $response = [
            'icon'=>'success',
            'title'=>'Exito',
            'text'=>'Entrada agregada con exito.',
        ];
        return response()->json($response,200);
    }
    public function getData($id = null){
        try {
            $idTienda = $id ? $id : Auth::user()->tienda_id;

            $inventario = InventarioTienda::select(
                'inventario_tienda.id',
                't.nombre as tienda',
                't.id as id_tienda',
                'm.nombre as mueble',
                'm.id as id_mueble',
                'm.precio',
                'e.estatus as estatus',
                'inventario_tienda.cantidad_stock',
                'inventario_tienda.cantidad_apartados',
                'inventario_tienda.por_entregar',
                'inventario_tienda.en_garantia'
            )
            ->leftJoin('muebles as m','m.id','=','inventario_tienda.mueble_id')
            ->leftJoin('tiendas as t','t.id','=','inventario_tienda.tienda_id')
            ->leftJoin('estatus_inventario as e','e.id','=','inventario_tienda.estatus_id')
            ->when($idTienda, function($q)use($idTienda){
                $q->where('tienda_id',$idTienda);
            })
            ->orderBy('inventario_tienda.id')
            ->orderBy('t.nombre')
            ->get();
            return response()->json($inventario,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getPrecioCompra($id){
        $precio = Mueble::where('id', $id)
            ->selectRaw('
                CASE 
                    WHEN precio_compra > 0 THEN precio_compra
                    ELSE precio
                END as precio
            ')
        ->value('precio');
        return response()->json($precio,200);
    }
    public function getPagos(){
        return view('pagos.index');
    }
    public function getDataPagos($idTienda = null){
        $tienda = $idTienda ? $idTienda : Auth::user()->tienda_id;
        $pagos = Entrada::leftJoin('tiendas as t','t.id','=','ingresos_inventario.tienda_id')
            ->leftJoin('proveedores as p','p.id','=','ingresos_inventario.proveedor_id')
            ->leftJoin('users as u','u.id','=','ingresos_inventario.usuario_id')
            ->select(
                'ingresos_inventario.id',
                't.nombre as tienda',
                'ingresos_inventario.fecha',    
                'p.nombre as proveedor',
                'ingresos_inventario.codigo_trazabilidad',
                'total_compra',
                'total_pagado',
                'estatus_pago',
                'u.name as usuario',
            )
            ->when($tienda, function($q) use($tienda){
                $q->where('ingresos_inventario.tienda_id',$tienda);
            })
            ->orderBy('ingresos_inventario.id', 'desc')
        ->get();
        return response()->json($pagos,200);
    }
    public function postPagarMercancia(Request $request){
        $request->validate([
            'entrada_id' => 'required',
            'monto' => 'required|numeric|min:1',
            'tipo_pago' => 'required|in:efectivo,transferencia'
        ]);
        try {
            DB::beginTransaction();

            $entrada = Entrada::lockForUpdate()->find($request->entrada_id);

            $saldo = $entrada->total_compra - $entrada->total_pagado;

            if ($request->tipo_pago == 'efectivo') {
                # validamos que cuente con el efectivo necesario...
                $efectivoApertura = CajaController::getEfectivoApertura($entrada->tienda_id);
                $movimientosEfectivo = CajaController::getMovimientoEfectivoEnCaja($entrada->tienda_id);

                $efectivoDispobible = $efectivoApertura + $movimientosEfectivo;
                if ($request->monto > $efectivoDispobible) {
                    # regresamos validacion de montos no aceptados...
                    $response = [
                        'title'=>'Advertencia!',
                        'icon' => 'warning',
                        'text' => 'No cuentas con esta cantidad en efectivo.'
                    ];
                    return response()->json($response,200);
                }
            }

            if ($request->monto > $saldo) {
                return response()->json([
                    'icon'=>'warning',
                    'title'=>'Error',
                    'text'=>'El monto excede el saldo'
                ],200);
            }
            PagoIngresoInventario::create([
                'ingreso_id' => $entrada->id,
                'tienda_id'=>$entrada->tienda_id,
                'usuario_id' => Auth::user()->id,
                'monto' => $request->monto,
                'metodo_pago' => $request->tipo_pago,
                'descripcion' => $request->observacion,
                'fecha' => Carbon::now('America/Mexico_City')->format('Y-m-d'),
            ]);

            // 2️⃣ Actualizar totales
            $entrada->total_pagado += $request->monto;
            $entrada->estatus_pago = 
                $entrada->total_pagado >= $entrada->total_compra
                ? 'pagado'
                : 'parcial';
            $entrada->save();

            
            // registramos la transaccion en la caja
            Transaccion::create([
                'tienda_id' =>$entrada->tienda_id,
                'venta_id'=>$entrada->id,
                'cantidad'=>$request->monto,
                'tipo_pago'=>$request->tipo_pago,
                'tipo_movimiento'=>'salida',
                'descripcion'=>'Pago inventario',
                'user_id'=>Auth::user()->id,
            ]);
            if ($request->tipo_pago != 'efectivo') {
                # agregamos el movimiento a la cuenta...
                Cuenta::create([
                    'tienda_id'=>$entrada->tienda_id,     
                    'user_id'=>Auth::user()->id,  
                    'monto'=>$request->monto,  
                    'tipo_movimiento'=>'salida',
                    'concepto'=>'transferencia',       
                    'referencia'=> $entrada->id,     
                    'descripcion'=>'Pago inventario',           
                ]); 
            }
            DB::commit();

            return response()->json([
                'icon'=>'success',
                'title'=>'Pago registrado',
                'text'=>'El pago se registró correctamente'
            ],200);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            DB::rollBack();
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,200);
        }
    }
    public function getDataEntradaById($id){
        $detalle = Entrada::leftJoin('tiendas as t','t.id','=','ingresos_inventario.tienda_id')
            ->leftJoin('proveedores as p','p.id','=','ingresos_inventario.proveedor_id')
            ->leftJoin('users as u','u.id','=','ingresos_inventario.usuario_id')
            ->select(
                'ingresos_inventario.id',
                't.nombre as tienda',
                'ingresos_inventario.fecha',    
                'p.nombre as proveedor',
                'ingresos_inventario.codigo_trazabilidad',
                'total_compra',
                'total_pagado',
                'estatus_pago',
                'u.name as usuario',
            )
            ->where('ingresos_inventario.id',$id)
        ->first();

        $muebles = Entrada::leftJoin('detalle_ingresos as di','di.ingreso_id','=','ingresos_inventario.id')
            ->leftJoin('muebles as m','m.id','=','di.mueble_id')
            ->select(
                'm.nombre as mueble',
                'di.cantidad',
                'di.precio_compra',
            )
            ->where('ingresos_inventario.id',$id)
            ->orderBy('m.nombre')
        ->get();

        $pagos = Entrada::join('pagos_ingresos_inventario as pi','pi.ingreso_id','=','ingresos_inventario.id')
            ->leftJoin('users as u','u.id','=','pi.usuario_id')
            ->select(
                'u.name as usuario',
                'pi.monto',
                'pi.metodo_pago',
                'pi.fecha',
                'pi.descripcion',
            )
            ->orderBy('pi.id')
            ->where('ingresos_inventario.id',$id)
        ->get();
        $response = [
            'detalle'=>$detalle,
            'muebles'=>$muebles,
            'pagos'=>$pagos
        ];

        return response()->json($response,200);
    }

}
