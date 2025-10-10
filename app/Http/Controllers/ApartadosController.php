<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\catalogos\Mueble;
use App\Models\Apartado;
use App\Models\Cliente;
use App\Models\ApartadoMueble;
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
        Log::debug($request);
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'telefono' => 'required',
                'direccion' => 'required|string|max:255',
                'anticipo' => 'required',
                'total' => 'required',
                'fecha' => 'required',
                'id' => 'required|array|min:1',
                'id.*' => 'required|integer',
                'producto' => 'required|array|min:1',
                'producto.*' => 'required|string|max:255',
                'cantidad' => 'required|array|min:1',
                'cantidad.*' => 'required|numeric|min:1',
            ]);
            DB::beginTransaction();
            $idCliente = null;
            $oldCliente = Cliente::select('*')
                ->where(['nombre'=>$request->nombre,'apellidos'=>$request->apellidos])->first();
            if ($oldCliente) {
                $idCliente = $OldCliente->id;
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
            $restante = (float)$request->total - (float)$request->anticipo;
            $apartado =  Apartado::create([
                'cliente_id'=>$idCliente,
                'tienda_id'=>Auth::user()->tienda_id,
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
            }
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
}
