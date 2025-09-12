<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\catalogos\Tiendas;
use Log;

class CatalogoController extends Controller
{
    public function getChoferes(){
        return view('catalogos.choferes.index');
    }
    public function getTiendas(){
        return view('catalogos.tiendas.index');
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
            return response()->json($response,200);
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
            return response()->json($response,200);
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
            return response()->json($response,200);
        }
    }
}
