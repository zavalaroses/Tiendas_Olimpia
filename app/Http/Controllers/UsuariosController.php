<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ComisionVendedor;
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
    public function getDataComisionesActivas(){
        try {
            $inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
            $finSemana = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();

            $comisiones = ComisionVendedor::query()
                ->join('users as u','u.id','=','comisiones_vendedores.usuario_id')
                ->join('tiendas as t','t.id','=','comisiones_vendedores.tienda_id')
                ->select(
                    'usuario_id',
                    'u.name as usuario',
                    't.nombre as tienda',
                    DB::raw('COUNT(comisiones_vendedores.id) as entregas'),
                    DB::raw('SUM(comisiones_vendedores.monto_venta) as vendido'),
                    DB::raw('SUM(comisiones_vendedores.monto_comision) as comision'),
                    DB::raw("CASE WHEN SUM(
                            CASE WHEN comisiones_vendedores.pagada = 0
                                THEN 1
                                ELSE 0
                        END
                        ) > 0 THEN 'Pendiente' ELSE 'Pagado' END as estatus
                    ")
                )
                // ->whereBetween('comisiones_vendedores.fecha_entrega',[$inicioSemana,$finSemana])
                ->whereNull('fecha_pago')
                ->groupBy('usuario_id','u.name','t.nombre')
                ->orderByDesc('comision')
                ->get();

                return response()->json([
                    'data' => $comisiones,
                    'inicio_semana' => $inicioSemana,
                    'fin_semana' => $finSemana,
                ],200);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getResumenComisiones(){
        try {
            $inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
            $finSemana = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();

            $query = ComisionVendedor::query()
                // ->whereBetween('fecha_entrega', [$inicioSemana, $finSemana])
                ->selectRaw('
                    COALESCE(SUM(monto_comision), 0) as comision_total,
                    COUNT(DISTINCT usuario_id) as vendedores_activos,
                    COALESCE(SUM(monto_venta), 0) as total_vendido,
                    COALESCE(SUM(
                        CASE 
                            WHEN pagada = 0 THEN monto_comision
                            ELSE 0
                        END
                    ), 0) as pago_pendiente
                ')
                ->where('pagada',0)
            ->first();
            return response()->json([
                'comision_total' => round($query->comision_total,2),
                'vendedores_activos' => round($query->vendedores_activos,2),
                'total_vendido' => round($query->total_vendido,2),
                'pago_pendiente' => round($query->pago_pendiente,2),
                'inicio_semana' => $inicioSemana,
                'fin_semana' => $finSemana,
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function postPagarComisionSemanal(Request $request){
        try {
            DB::beginTransaction();

            $inicioSemana = Carbon::now()
                ->startOfWeek(Carbon::MONDAY)
                ->toDateString();

            $finSemana = Carbon::now()
                ->startOfWeek(Carbon::MONDAY)
                ->addDays(5)
                ->toDateString();
            $query = ComisionVendedor::query()->where('usuario_id',$request->id)
                ->whereBetween('fecha_entrega',[$inicioSemana,$finSemana]
            )->where('pagada',0);

            $cantidad = $query->count();

            if($cantidad <= 0){
                return response()->json([
                    'icon' => 'warning',
                    'title' => 'Advertencia',
                    'text' =>'No existen comisiones pendientes para este usuario.'
                ],200);
            }
            $query->update([
                'pagada' => 1,
                'fecha_pago' => now()
            ]);

            DB::commit();

            return response()->json([
                'icon' => 'success',
                'title' => 'Éxito',
                'text' =>'Comisión pagada correctamente.'
            ],200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function getDetalleComision(int | string $usuarioId)
    {
        try {
            $detalle = ComisionVendedor::query()
                ->join('apartados as a','a.id','=','comisiones_vendedores.apartado_id')
                ->leftJoin('clientes as c','c.id','=','a.cliente_id')
                ->leftJoin('tiendas as t','t.id','=','comisiones_vendedores.tienda_id')
                ->leftJoin('users as u','u.id','=','comisiones_vendedores.usuario_id')
                ->select(
                    'comisiones_vendedores.id',
                    'comisiones_vendedores.fecha_entrega',
                    'comisiones_vendedores.monto_venta',
                    'comisiones_vendedores.monto_comision',
                    'a.id as apartado_id',
                    'a.clave as folio',
                    'c.nombre as cliente',
                    't.nombre as tienda',
                    'u.name as usuario',
                )
                ->where('comisiones_vendedores.usuario_id',$usuarioId)
                ->where('comisiones_vendedores.pagada',0)
                ->orderBy(
                    'comisiones_vendedores.fecha_entrega',
                    'asc'
                )
            ->get();

            return response()->json([
                'success' => true,
                'data' => $detalle
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }
    public function getHistorialComisiones(Request $request)
    {
        try {
            $query = ComisionVendedor::query()
                ->join('users as u', 'u.id','=', 'comisiones_vendedores.usuario_id')
                ->join('tiendas as t', 't.id','=' ,'comisiones_vendedores.tienda_id')
                ->leftJoin('users as adm','adm.id','=','comisiones_vendedores.usuario_pago_id')
                ->selectRaw("
                    DATE(fecha_pago) as fecha_pago,
                    usuario_id,
                    u.name as vendedor,
                    t.nombre as tienda,
                    adm.name as pagado_por,
                    COUNT(*) as entregas,
                    SUM(monto_venta) as total_ventas,
                    SUM(monto_comision) as total_comision
                ")->where('pagada',1);

                if($request->filled('fecha_inicio')){
                    $query->whereDate('fecha_pago','>=',$request->fecha_inicio);
                }
                if($request->filled('fecha_fin')){
                    $query->whereDate('fecha_pago','<=',$request->fecha_fin);
                }
                if($request->filled('usuario_id')){
                    $query->where('usuario_id',$request->usuario_id);
                }
                if ($request->filled('tienda_id')){
                    $query->where('comisiones_vendedores.tienda_id', $request->tienda_id);
                }
                $historial = $query->groupBy('fecha_pago','usuario_id','u.name','t.nombre','adm.name')
                ->orderBy('fecha_pago','desc')
                ->get();

                return response()->json([
                    'success' => true,
                    'data' => $historial
                ]);
        } catch (\Throwable $th) {
            Log::debug('Error al obtener historial de comisiones: '.$th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }
    public function getDetalleComisionPagada(Request $request)
    {
        try {
            $detalle = ComisionVendedor::query()
                ->join('apartados as a','a.id','=','comisiones_vendedores.apartado_id')
                ->leftJoin('clientes as c','c.id','=','a.cliente_id')
                ->leftJoin('tiendas as t','t.id','=','comisiones_vendedores.tienda_id')
                ->leftJoin('users as u','u.id','=','comisiones_vendedores.usuario_id')
                ->select(
                    'comisiones_vendedores.id',
                    'comisiones_vendedores.fecha_entrega',
                    'comisiones_vendedores.fecha_pago',
                    'comisiones_vendedores.monto_venta',
                    'comisiones_vendedores.monto_comision',
                    'u.name as usuario',
                    't.nombre as tienda',
                    'a.id as apartado_id',
                    'a.clave as folio',
                    'c.nombre as cliente'
                )
                ->where('comisiones_vendedores.usuario_id',$request->usuario_id)
                ->whereDate('comisiones_vendedores.fecha_pago',$request->fecha_pago)
                ->where('comisiones_vendedores.pagada',1)
                ->orderBy('comisiones_vendedores.fecha_entrega')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $detalle
            ]);

        } catch (\Throwable $th) {
            Log::debug('Error al obtener detalle de comisión pagada: '.$th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }
    public function getFiltroUsuarios(){
        $usuarios = ComisionVendedor::join('users as u','u.id','=','comisiones_vendedores.usuario_id')
            ->select('comisiones_vendedores.usuario_id as id','u.name as nombre')
            ->where('pagada',1)
            ->distinct()
        ->get();

        return response()->json($usuarios,200);
    }
    
}
