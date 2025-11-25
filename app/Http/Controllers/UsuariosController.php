<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

use Log;

class UsuariosController extends Controller
{
    //
    public function getUsuarios(){
        return view('usuarios.index');
    }
    public function getDataUsuarios($tienda = null){
        try {
            $users = User::select('users.id','t.nombre as tienda','name','apellidos','email','r.nombre as rol','users.created_at as ingreso')
            ->when($tienda, function($query)use($tienda){
                $query->where('tienda_id',$tienda);
            })
            ->leftJoin('tiendas as t','t.id','=','users.tienda_id')
            ->leftJoin('roles as r','r.id','=','users.rol')
            ->get();
            return response()->json($users,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getCatalogoRoles(){
        try {
            $roles = DB::table('roles')->select('id','nombre')
                ->whereNull('deleted_at')
                ->where('id','!=',1)
            ->get();
            return response()->json($roles,200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
