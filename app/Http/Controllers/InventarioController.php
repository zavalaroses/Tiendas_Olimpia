<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Entrada;
use App\Models\DetalleInv;
use App\Models\InventarioTienda;
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
                'proveedor'=>'required',
                'fecha_ingreso'=>'required',
            ]);
            $proveedor = DB::table('proveedores')->where('id',$request->proveedor)->whereNull('deleted_at')->value('nombre');
            $horaMx = Carbon::now('America/Mexico_City')->format('H:i:s');
            $c1 = $proveedor ? $proveedor : 'NP'; 
            $codigo = $request->fecha_ingreso.'-'.$horaMx.'-'.$c1;
            $entrada = Entrada::create([
                'tienda_id'=> Auth::user()->tienda_id,
                'proveedor_id'=>$request->proveedor,
                'usuario_id'=>Auth::user()->id,
                'fecha'=> $request->fecha_ingreso,
                'codigo_trazabilidad'=>$codigo,
            ]);
            
            foreach ($request->id as $index => $muebleId) {
                DetalleInv::create([
                    'ingreso_id' => $entrada->id,
                    'mueble_id' => $muebleId,
                    'cantidad' => $request->cantidad[$index],
                ]);
                $inventarioExist = InventarioTienda::where('tienda_id',Auth::user()->tienda_id)->where('mueble_id',$muebleId)->exists();
                if ($inventarioExist) {
                    $afectedRow = InventarioTienda::where([
                        'tienda_id'=>Auth::user()->tienda_id,
                        'mueble_id'=>$muebleId,
                    ])->increment('cantidad',$request->cantidad[$index],['updated_at'=>now()]);
                }else {
                    InventarioTienda::create([
                        'tienda_id'=>Auth::user()->tienda_id,
                        'mueble_id'=>$muebleId,
                        'estatus_id'=>1,
                        'cantidad'=>$request->cantidad[$index]
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
            return response()->json($response,500);
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
            $inventario = InventarioTienda::select(
                'inventario_tienda.id',
                't.nombre as tienda',
                'm.nombre as mueble',
                'e.estatus as estatus',
                'inventario_tienda.cantidad_stock',
                'inventario_tienda.cantidad_apartados',
            )
            ->leftJoin('muebles as m','m.id','=','inventario_tienda.mueble_id')
            ->leftJoin('tiendas as t','t.id','=','inventario_tienda.tienda_id')
            ->leftJoin('estatus_inventario as e','e.id','=','inventario_tienda.estatus_id')
            ->when($id, function($q)use($id){
                $q->where('tienda_id',$id);
            })
            ->orderBy('inventario_tienda.id')
            ->orderBy('t.nombre')
            ->get();
            return response()->json($inventario,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
