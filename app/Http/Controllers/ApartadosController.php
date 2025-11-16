<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\catalogos\Mueble;
use App\Models\Apartado;
use App\Models\Cliente;
use App\Models\ApartadoMueble;
use App\Models\InventarioTienda;
use App\Models\Salida;
use App\Models\SalidaProducto;
use App\Models\Transaccion;

use Log;
use Carbon\Carbon;

class ApartadosController extends Controller
{
    public function getApartados(){
        return view('apartados.index');
    }
    public function getPreciosById($id){
        try {
            $precio = Mueble::where('id',$id)->value('precio');
            return response()->json($precio,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function postAddPartido(Request $request){
        try {
            Log::debug($request);
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'telefono' => 'required',
                'direccion' => 'required|string|max:255',
                'anticipo' => 'required',
                'total' => 'required',
                'fecha' => 'required',
                'forma_pago'=>'required',
                'id' => 'required|array|min:1',
                'id.*' => 'required|integer',
                'producto' => 'required|array|min:1',
                'producto.*' => 'required|string|max:255',
                'cantidad' => 'required|array|min:1',
                'cantidad.*' => 'required|numeric|min:1',
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
            DB::beginTransaction();
            $idtienda = $request->id_tienda ? $request->id_tienda : Auth::user()->tienda_id;
            $idCliente = null;
            //revisamo si el cliente existe
            $oldCliente = Cliente::where([
                'nombre'=>$request->nombre,
                'apellidos'=>$request->apellidos
            ])->first();

            // si el cliente existe asignamos el di si no lo creamos
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
            // calcular el monto restante
            $restante = (float)$request->total - (float)$request->anticipo;
            // registramos el apartado
            $apartado =  Apartado::create([
                'cliente_id'=>$idCliente,
                'tienda_id'=>$idtienda,
                'monto_anticipo'=>$request->anticipo,
                'monto_restante'=>$restante,
                'usuario_id'=>Auth::user()->id,
                'fecha_apartado'=>Carbon::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d'),
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
                    $inventario->increment('cantidad_apartados',$request->cantidad[$i]);
                }else {
                    
                    throw new \Exception("Inventarios insuficiente para el mueble", 1);
                }
            }
            // registramos la transaccion en la caja
            Transaccion::create([
                'tienda_id' =>$idtienda,
                'venta_id'=>$apartado->id,
                'cantidad'=>$request->anticipo,
                'tipo_pago'=>$request->forma_pago,
                'tipo_movimiento'=>'entrada',
                'descripcion'=>'Monto de anticipo',
                'user_id'=>Auth::user()->id,
            ]);


            DB::commit();
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Entrada agregada con exito.',
            ];
            return response()->json($response,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function getDataApartados($tienda = null){
        try {
            $idTienda = $tienda ? $tienda : Auth::user()->tienda_id;
            $apartados = DB::table('apartados as a')
                ->leftJoin('clientes as c','c.id','=','a.cliente_id')
                ->leftJoin('apartado_muebles as am','am.id_apartado','=','a.id')
                ->leftJoin('muebles as m','m.id','=','am.id_mueble')
                ->select(
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) as cliente"),
                    'm.nombre as mueble',
                    'am.cantidad as cantidad',
                    'a.monto_anticipo as anticipo',
                    'a.monto_restante as restante',
                    'a.fecha_apartado',
                    'a.id as id'
                )
                ->whereNull('a.deleted_at')
                ->when($idTienda, function($q)use($idTienda){
                    $q->where('a.tienda_id',$idTienda);
                })
                ->orderBy('a.id')
            ->get();
            return response()->json($apartados,200);
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    public function getMontoRestante($id){
        try {
            $restante = Apartado::where('id',$id)->first();
            return response()->json($restante,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function postAddAdelanto(Request $request){
        try {
            DB::beginTransaction();

            $restante = Apartado::where('id',$request->id_apartado)->value('monto_restante');
            if ((float)$request->adelanto > (float)$restante) {
                $response = [
                    'icon'=>'warning',
                    'title'=>'Advertencia',
                    'text'=>'El monto del adelanto es mayor al monto restante.',
                ];
                return response()->json($response,200);
            }
            Apartado::where('id',$request->id_apartado)->decrement('monto_restante',floatval($request->adelanto));
            Apartado::where('id', $request->id_apartado)->increment('monto_anticipo', floatval($request->adelanto));

            $newRestante = Apartado::where('id',$request->id_apartado)->value('monto_restante');

            if ((float)$newRestante == 0) {
                $apartadoOld = Apartado::leftJoin('apartado_muebles as am','am.id_apartado','=','apartados.id')
                    ->select(
                        'apartados.id as id',
                        'apartados.cliente_id',
                        'apartados.tienda_id',
                        'apartados.usuario_id',
                        'am.id as id_am',
                        'am.id_mueble',
                        'am.cantidad',
                    )
                    ->where('apartados.id',$request->id_apartado)
                ->get(); 
              
                Apartado::where('id')->update(['liquidado_at'=>Carbon::now()->toDateString()]);
                Apartado::where('id',$request->id_apartado)->delete();

                $salida = Salida::create([
                    'cliente_id'=>$apartadoOld[0]->cliente_id,
                    'apartado_id'=>$apartadoOld[0]->id,
                    'chofer_id'=>null,
                    'usuario_id'=>Auth::user()->id,
                    'fecha_entrega'=>Carbon::now()->toDateString(),
                    'pdf_entrega'=>null,
                    'estatus'=>'Por entregar'
                ]);
                foreach ($apartadoOld as  $apartado) {
                    SalidaProducto::create([
                        'id_salida'=>$salida->id,
                        'id_mueble'=>$apartado->id_mueble,
                        'id_tienda'=>Auth::user()->tienda_id,
                        'cantidad'=>$apartado->cantidad,
                        'id_usuario'=>Auth::user()->id
                    ]);
                }
                
                $response = [
                    'icon' =>'success',
                    'title' =>'Exito',
                    'text' => 'Monto liquidado, salida registrada.',
                ];
                DB::commit();
                return response()->json($response,200);
            }
            
            
            $response = [
                'icon' =>'success',
                'title' =>'Exito',
                'text' => 'Monto actualizado con exito.',
            ];
            DB::commit();
            return response()->json($response,200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        

    }
}
