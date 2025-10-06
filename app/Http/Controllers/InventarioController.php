<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Entrada;
use App\Models\DetalleInv;
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
            $c1 = $proveedor ? $proveedor : 'NP'; 
            $codigo = $request->fecha_ingreso.'-'.$c1;
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
    public function getData(){
        try {
            // $inventario = Entrada::leftJoin('detalle_ingresos as d','d.ingreso_id','=','ingresos_inventario.id')
            //     ->select('')
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
