<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Garantias;
use App\Models\InventarioTienda;
use App\Models\Salida;
use Log;
use Carbon\Carbon;


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
    public function postAddGarantia(Request $request){
        try {
            DB::beginTransaction();
            $cliente = null;
            if (!$request->id_salida) {
                # si no viene de una venta verificamos que sea de un inventario 
                $exist = DB::table('inventario_tienda')
                    ->where('tienda_id',$request->tienda)
                    ->where('mueble_id',$request->id_mueble)
                    ->where('cantidad_stock','>=',$request->cantidad)
                ->exists();
                if (!$exist) {
                    # retornamos unno valido...
                    $response = [
                        'icon'=>'warning',
                        'title'=>'Inventario insuficiente',
                        'text'=>'No cuentas con inventario suficiente para agergar a garantia.',
                    ];
                    return response()->json($response,200);
                }
            }else {
                # validamos que la cantidad a garantia sea menor o igual a la cantidad de muebles vendidos...
                $exist = DB::table('salidas as s')
                    ->join('salida_producto as sp','sp.id_salida','=','s.id')
                    ->where('s.id',$request->id_salida)
                    ->where('sp.id_mueble',$request->id_mueble)
                    ->where('sp.cantidad','>=',$request->cantidad)
                    ->whereNull('sp.deleted_at')
                ->exists();
                if (!$exist) {
                    # si no existe el resultado entonces retornamos un mensaje de alerta...
                    $response = [
                        'icon'=>'warning',
                        'title'=>'Datos incorrectos',
                        'text'=>'La cantidad no puede se mayor a la cantidad de muebles vendidos.',
                    ];
                    return response()->json($response,200);
                }
                // en la garantia registramos el id de salida como el cliente para no modificar la BD...
                $cliente = $request->id_salida;
            }
            $newGarantia = Garantias::create([
                'mueble_id'=>$request->id_mueble,
                'tienda_id'=>$request->tienda,
                'cliente_id'=>$cliente,
                'motivo'=>$request->descripcion,
                'cantidad'=>$request->cantidad,
                'usuario_id'=>Auth::user()->id,
                'fecha'=>Carbon::today()
            ]);
            if ($newGarantia && !$request->id_salida) {
                # si se crea y viene de inventario movemos la cantidad  a garantia...
                InventarioTienda::where(
                    ['tienda_id'=>$request->tienda,
                    'mueble_id'=>$request->id_mueble
                ])->decrement('cantidad_stock',$request->cantidad);
            }
            if ($newGarantia && $request->id_salida) {
                # cambiamos el estatus de la venta por en garantia...
                Salida::where('id',$request->id_salida)->update([
                    'estatus'=>'En garantia'
                ]);
            }
            InventarioTienda::where(
                ['tienda_id'=>$request->tienda,
                'mueble_id'=>$request->id_mueble
            ])->increment('en_garantia',$request->cantidad);

            DB::commit();

            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Garantia agregada con exito.',
            ];
            return response()->json($response,200);

            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function getDataGarantias($tienda = null){
        $idTienda = $tienda ? $tienda : Auth::user()->tienda_id;
        $garantias = Garantias::leftJoin('tiendas as t','t.id','=','garantias.tienda_id')
            ->leftJoin('muebles as m','m.id','=','garantias.mueble_id')
            ->leftJoin('users as u','u.id','=','garantias.usuario_id')
            ->leftJoin('salidas as s','s.id','=','garantias.cliente_id')
            ->leftJoin('clientes as c','c.id','=','s.cliente_id')
            ->select(
                'garantias.id',
                't.nombre as tienda',
                'm.nombre as mueble',
                'motivo',
                'garantias.cantidad',
                'u.name',
                'u.apellidos',
                'garantias.fecha',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) as cliente")
            )
            ->when($idTienda,function($q) use($idTienda){
                $q->where('garantias.tienda_id',$idTienda);
            })
        ->get();
        return response()->json($garantias,200);
    }
    public function postTerminarGarantia(Request $request){
        // recordar que $old->id_cliente en realidas es el id de salida...
        try {
            DB::beginTransaction();
            $old = Garantias::select('*')->where('id',$request->id)->first();
    
            $deleteRow = Garantias::where('id',$request->id)->delete();

            if (!$old->cliente_id && $deleteRow) {
                # si no tiene cliente entonces viene de tienda por lo que regresamos el stock...
                $decrement =  InventarioTienda::where('tienda_id',$old->tienda_id)
                    ->where('mueble_id',$old->mueble_id)
                ->decrement('en_garantia',$old->cantidad);

                $increment = InventarioTienda::where('tienda_id',$old->tienda_id)
                    ->where('mueble_id',$old->mueble_id)
                ->increment('cantidad_stock',$old->cantidad);
            }
            if ($old->cliente_id && $deleteRow) {
                # si viene de una venta regresamos el estatus a entregado...
                // recuerda que old->cliente_id es el id de la salida importante eso...
                $update = Salida::join('salida_producto as sp','sp.id_salida','=','salidas.id')
                    ->where('salidas.id',$old->cliente_id)
                    ->where('sp.id_tienda',$old->tienda_id)
                    ->where('sp.id_mueble',$old->mueble_id)
                ->update([
                    'estatus'=>'Entregado'
                ]);
            }
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Garantia terminada con exito.',
            ];
            DB::commit();
            return response()->json($response,200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
