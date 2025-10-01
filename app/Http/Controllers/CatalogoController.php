<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\catalogos\Tiendas;
use App\Models\catalogos\Chofer;
use App\Models\catalogos\Mueble;
use App\Models\catalogos\Proveedor;
use Log;

class CatalogoController extends Controller
{
    public function getChoferes(){
        return view('catalogos.choferes.index');
    }
    public function getTiendas(){
        return view('catalogos.tiendas.index');
    }
    public function getMuebles(){
        return view('catalogos.muebles.index');
    }
    public function getProveedores(){
        return view('catalogos.proveedores.index');
    }
    public function postAddTienda(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'nombre' => ['required', 'string', 'max:255'],
                'direccion' => ['required','string', 'max:255'],
            ]);

            $tienda = Tiendas::create([
                'nombre' => $request->nombre,
                'ubicacion' => $request->direccion,
            ]);
           
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
            'text'=>'Tienda agregada con exito.',
        ];
        return response()->json($response,200);

    }
    public function getDataTiendas(){
        try {
            $tiendas = Tiendas::All();
            return response()->json($tiendas,200);
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function getCatalgoTiendas(){
        try {
            $tiendas = Tiendas::All();
            return response()->json($tiendas,200);
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function postAddChofer(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'nombre' => ['required', 'string', 'max:255'],
                'apellidos' => ['required','string', 'max:255'],
                'tienda' => ['required'],
                'correo' => ['required'],
                'telefono' => ['required'],
                'direccion' => ['required','string', 'max:255'],
            ]);
            if ($request->id && $request->id != '') {
                Chofer::where('id',$request->id)->update([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'tienda_id' => $request->tienda,
                    'correo' => $request->correo,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                ]);
            }else {
                Chofer::create([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'tienda_id' => $request->tienda,
                    'correo' => $request->correo,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
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
            'text'=>'Conductor agregado con exito.',
        ];
        return response()->json($response,200);
    }
    public function getDataChoferes(){
        try {
            $choferes = Chofer::select(
                'choferes.id',
                't.nombre as tienda',
                'choferes.nombre',
                'apellidos',
                'correo',
                'telefono',
                'choferes.direccion',
            )
            ->leftJoin('tiendas as t','t.id','=','choferes.tienda_id')
            ->get();
            return response()->json($choferes,200);

        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function getChoferById(Request $request){
        try {
            $chofer = Chofer::where('id',$request->id)->first();
            
            return response()->json($chofer,200);
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function postDeleteCatChofer(Request $request){
        try {
            DB::beginTransaction();
            $salida = DB::table('salidas')->where('chofer_id',$request->id)
                ->where('fecha_entrega','>=',Carbon::now()->toDateString())
            ->exists();
            if ($salida) {
                $response = [
                    'icon'=>'warning',
                    'title'=>'Advertencia',
                    'text'=>'No es posible eliminar al condunctor ya que tiene salidas pendientes.',
                ];
                return response()->json($response,200);
            }
            Chofer::where('id',$request->id)->delete();  
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
            'text'=>'Conductor eliminado con exito.',
        ];
        return response()->json($response,200);
    }
    public function postAddMuble(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'nombre'=>['required','string','max:255'],
                'codigo'=>['required','string','max:255'],
                'descripcion'=>['required','string','max:255'],
                'precio'=>['required']
            ]);
            $mueble = Mueble::create([
                'nombre'=>$request->nombre,
                'codigo'=>$request->codigo,
                'descripcion'=>$request->descripcion,
                'precio'=>$request->precio
            ]);
            DB::commit();
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Mueble agregado con exito.',
            ];
            return response()->json($response,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug('exp '. $th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function getDataMuebles(){
        try {
            $muebles = Mueble::All();
            return response()->json($muebles,200);
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function getMuebleByid($id){
        try {
            $mueble = Mueble::where('id',$id)->first();
            return response()->json($mueble,200);
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function postUpdateMueble(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'id'=>['required'],
                'nombre'=>['required','string','max:255'],
                'codigo'=>['required','string','max:255'],
                'descripcion'=>['required','string','max:255'],
                'precio'=>['required'],
            ]);
            Mueble::where('id',$request->id)->update([
                'nombre'=>$request->nombre,
                'codigo'=>$request->codigo,
                'descripcion'=>$request->descripcion,
                'precio'=>$request->precio
            ]);
            DB::commit();
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Mueble modificado con exito.',
            ];
            return response()->json($response,200);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug('exp '. $th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function postDeleteMueble(Request $request){
        try {
            DB::beginTransaction();
            Mueble::where('id',$request->id)->delete();  
            DB::commit();
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Mueble eliminado con exito.',
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
    public function postAddProveedor(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'nombre'=>['required','string','max:255'],
                'contacto'=>['required','string','max:255'],
                'telefono'=>['required'],
                
            ]);
            Proveedor::create([
                'nombre'=>$request->nombre,
                'contacto'=>$request->contacto,
                'telefono'=>$request->telefono,
            ]);
            DB::commit();
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Registro realizado con exito.',
            ];
            return response()->json($response,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug('exp '. $th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function getDataProveedores(){
        try {
            $proveedores = Proveedor::All();
            return response()->json($proveedores,200);
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function getProveedorById(Request $request){
        try {
            $proveedor = Proveedor::where('id',$request->id)->first();
            return response()->json($proveedor,200);
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function postUpdateProveedor(Request $request){
        try {
            DB::beginTransaction();
            $request->validate([
                'nombre'=>['required','string','max:255'],
                'contacto'=>['required','string','max:255'],
                'telefono'=>['required'],
                
            ]);
            Proveedor::where('id',$request->id)->update([
                'nombre'=>$request->nombre,
                'contacto'=>$request->contacto,
                'telefono'=>$request->telefono,
            ]);
            DB::commit();
            $response = [
                'icon'=>'success',
                'title'=>'Exito',
                'text'=>'Registro realizado con exito.',
            ];
            return response()->json($response,200);
            
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error al registrar.',
            ];
            return response()->json($response,500);
        }
    }
    public function postDeleteProveedor(Request $request){
        try {
            DB::beginTransaction();
            $afectedRow = Proveedor::where('id',$request->id)->delete();
            if ($afectedRow) {
                DB::commit();
                $response = [
                    'icon'=>'success',
                    'title'=>'Exito',
                    'text'=>'Registro eliminado con exito.',
                ];
                return response()->json($response,200);
            }
            else {
                $response = [
                    'icon'=>'error',
                    'title'=>'Oops.',
                    'text'=>'A ocurrido un error al registrar.',
                ];
                return response()->json($response,500);
            }
        } catch (\Throwable $th) {
            Log::debug('exp '.$th->getMessage());
            $response = [
                'icon'=>'error',
                'title'=>'Oops.',
                'text'=>'A ocurrido un error intentelo mas tarde.',
            ];
            return response()->json($response,500);
        }
    }

}
