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
    public function getDataToSalida($id){
        try {
            $salidas = Salida::leftJoin('apartados as a','a.id','=','salidas.apartado_id')
            ->leftJoin('clientes as c','c.id','=','salidas.cliente_id')
            ->where('salidas.id',$id)->first();

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

            Salida::where('id',$request->id)->update([
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
            ]);
            $idCliente = null;
            $oldCliente = Cliente::where([
                'nombre'=>$request->nombre,
                'apellidos'=>$request->apellidos
            ])->first();

            if ($oldCliente) {
                $idCliente = $oldCliente->id;
            }else {
                $newCliente = Cliente::create([
                    'tienda_id'=>Auth::user()->tienda_id,
                    'nombre'=>$request->nombre,
                    'apellidos'=>$request->apellidos,
                    'telefono'=>$request->telefono,
                    'direccion'=>$request->direccion,
                ]);
                $idCliente = $newCliente->id;
            }

            $apartado =  Apartado::create([
                'cliente_id'=>$idCliente,
                'tienda_id'=>Auth::user()->tienda_id,
                'monto_anticipo'=>$request->total,
                'monto_restante'=>0,
                'usuario_id'=>Auth::user()->id,
                'fecha_apartado'=>Carbon::now()->toDateString(),
            ]);
            for ($i=0; $i < count($request->id) ; $i++) { 
                ApartadoMueble::create([
                    'id_apartado'=>$apartado->id,
                    'id_mueble'=>$request->id[$i],
                    'cantidad'=>$request->cantidad[$i],
                    'estatus'=>'Apartado',
                ]);
                $inventario = InventarioTienda::where([
                    'tienda_id'=>Auth::user()->tienda_id,
                    'mueble_id'=>$request->id[$i],
                ])->first();

                if ($inventario && $inventario->cantidad >= $request->cantidad[$i]) {
                    # restamos inventario...
                    $inventario->decrement('cantidad',$request->cantidad[$i]);
                }else {
                    throw new \Exception("Inventarios insuficiente para el mueble", 1);
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
                    'id_tienda'=>Auth::user()->tienda_id,
                    'cantidad'=>$request->cantidad[$i],
                    'id_usuario'=>Auth::user()->id
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
            throw $th;
        }
    }
}
