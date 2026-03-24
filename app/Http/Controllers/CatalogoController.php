<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\catalogos\Tiendas;
use App\Models\catalogos\Chofer;
use App\Models\catalogos\Mueble;
use App\Models\catalogos\Proveedor;
use App\Models\PagoIngresoInventario;
use App\Models\Transaccion;
use App\Models\Cuenta;
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
        $request->validate([
            'nombre'=>['required','string','max:255'],
            'codigo'=>['required','string','max:255'],
            'descripcion'=>['nullable','string','max:255'],
            'precio'=>['required','numeric','min:0'],
            'compra'=>['nullable','numeric','min:0']
        ]);
        try {
            DB::beginTransaction();
            
            $mueble = Mueble::create([
                'nombre'=>$request->nombre,
                'codigo'=>$request->codigo,
                'descripcion'=>$request->descripcion ? $request->descripcion : null,
                'precio'=>$request->precio,
                'precio_compra'=>$request->compra ?? 0
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
            $muebles = Mueble::where('estatus','!=','InActivo')->get();
            $response = [
                'muebles'=>$muebles,
                'rol'=>Auth::user()->rol
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
        $request->validate([
            'id'=>['required'],
            'nombre'=>['required','string','max:255'],
            'codigo'=>['required','string','max:255'],
            'descripcion'=>['nullable','string','max:255'],
            'precio'=>['required','numeric','min:0'],
            'compra'=>['nullable','numeric','min:0']
        ]);
        try {
            DB::beginTransaction();
            
            Mueble::where('id',$request->id)->update([
                'nombre'=>$request->nombre,
                'codigo'=>$request->codigo,
                'descripcion'=>$request->descripcion,
                'precio'=>$request->precio,
                'precio_compra'=>$request->compra ?? 0,
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
    public function getEstadoCuentaProveedor(Request $request){
        try {
            $proveedorId = $request->id;
            $tiendaId = $request->tienda;
            $inicio = $request->inicio;
            $fin = $request->fin;

            $proveedor = Proveedor::find($proveedorId);

            // 🟢 BASE MOVIMIENTOS (UNION)
            $base = DB::table(function ($query) use ($proveedorId, $tiendaId){
                $query->select(
                    'e.id as referencia',
                    'e.proveedor_id',
                    'e.tienda_id',
                    'e.fecha',
                    DB::raw("'Compra' as tipo"),
                    DB::raw("CONCAT('Entrada #', e.id) as concepto"),
                    DB::raw("e.total_compra as cargo"),
                    DB::raw("0 as abono")
                )
                ->from('ingresos_inventario as e')
                ->where('e.proveedor_id', $proveedorId)
                ->whereNull('e.deleted_at')
                ->when($tiendaId, fn($q)=> $q->where('e.tienda_id',$tiendaId))

                ->unionAll(

                    DB::table('pagos_ingresos_inventario as p')
                        ->select(
                            'p.id as referencia',
                            'p.proveedor_id',
                            'p.tienda_id',
                            'p.fecha',
                            DB::raw("
                                CASE 
                                    WHEN p.tipo = 'abono' THEN 'Pago'
                                    WHEN p.tipo = 'cargo' THEN 'Saldo a favor'
                                END as tipo
                            "),
                            DB::raw("
                                CASE 
                                    WHEN p.tipo = 'abono' THEN CONCAT('Pago entrada #',p.ingreso_id)
                                    WHEN p.tipo = 'cargo' THEN 'Saldo a favor'
                                END as concepto
                            "),
                            DB::raw("0 as cargo"),
                            DB::raw("p.monto as abono")
                        )
                        ->where('p.proveedor_id',$proveedorId)
                        ->when($tiendaId, fn($q)=> $q->where('p.tienda_id',$tiendaId))
                );
            });

            // 🟡 SALDO INICIAL
            $saldoInicial = DB::table(DB::raw("({$base->toSql()}) as movimientos"))
                ->mergeBindings($base)
                ->when($inicio, fn($q)=> $q->whereDate('fecha','<',$inicio))
                ->selectRaw("SUM(abono - cargo) as saldo")
                ->value('saldo') ?? 0;

            // 🟣 MOVIMIENTOS EN RANGO
            $movimientos = DB::table(DB::raw("({$base->toSql()}) as movimientos"))
                ->mergeBindings($base)
                ->when($inicio, fn($q)=> $q->whereDate('fecha','>=',$inicio))
                ->when($fin, fn($q)=> $q->whereDate('fecha','<=',$fin))
                ->orderBy('fecha','asc')
                ->orderBy('referencia','asc')
                ->get();

            // 🔵 CALCULAR SALDOS Y TOTALES
            $saldo = $saldoInicial;
            $totalCargos = 0;
            $totalAbonos = 0;

            $movimientos = collect($movimientos)->map(function ($item) use (&$saldo, &$totalCargos, &$totalAbonos){
                $saldo = $saldo - $item->cargo + $item->abono;

                $totalCargos += $item->cargo;
                $totalAbonos += $item->abono;

                $item->saldo = $saldo;
                return $item;
            });

            $saldoFinal = $saldo;

            return response()->json([
                'proveedor' => $proveedor,
                'resumen'=> [
                    'saldo_inicial'=>(float)$saldoInicial,
                    'total_cargos'=>(float)$totalCargos,
                    'total_abonos'=>(float)$totalAbonos,
                    'saldo_final'=>(float)$saldoFinal
                ],
                'movimientos'=>$movimientos
            ],200);

        } catch (\Throwable $th) {
            Log::debug($th);
            return response()->json([
                'icon'=>'error',
                'text'=>$th->getMessage()
            ],500);
        }
    }
    public function postAddSaldoProveedor(Request $request){
        $request->validate([
            'proveedor_id' => 'required',
            'monto' => 'required|numeric|min:1',
            'metodo_pago' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $proveedor = Proveedor::findOrFail($request->proveedor_id);

            PagoIngresoInventario::create([
                'ingreso_id' => null,
                'proveedor_id' => $proveedor->id,
                'tienda_id' => $request->tienda ? $request->tienda : Auth::user()->tienda_id,
                'usuario_id' => Auth::user()->id,
                'monto' => $request->monto,
                'metodo_pagp' => $request->metodo_pago,
                'tipo' => 'cargo',
                'descripcion' => $request->descripcion ? $request->descripcion : null,
                'fecha' => now()
            ]);
            // Afectar caja
            Transaccion::create([
                'tienda_id' => $request->tienda ? $request->tienda : Auth::user()->tienda_id,
                'cantidad' => $request->monto,
                'tipo_pago' => $request->metodo_pago,
                'tipo_movimiento' => 'salida',
                'descripcion' => 'Saldo a favor proveedor',
                'user_id' => Auth::user()->id
            ]);
            // afectamos a la cuenta si es de cuenta
            if ($request->metodo_pago !== 'efectivo') {
                Cuenta::create([
                    'tienda_id' => $request->tienda ? $request->tienda : Auth::user()->tienda_id,
                    'user_id' => Auth::user()->id,
                    'monto' => $request->monto,
                    'tipo_movimiento' => 'salida',
                    'concepto' => 'transferencia',
                    'descripcion' => 'Saldo a favor proveedor'
                ]);
            }

            DB::commit();
            return response()->json([
            'icon' => 'success',
            'title' => 'Exito',
            'text' => 'Saldo agregado correctamente'
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'icon'=>'error',
                'title'=>'Error',
                'text'=>$th->getMessage()
            ]);
        }
    }
}
