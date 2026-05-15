<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Apartado;
use App\Models\InventarioTienda;
use App\Models\Salida;
use App\Models\Corte;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getDataDashboard(){
        try {
            $idTienda = Auth::user()->tienda_id ?? null;
            $dinero =  Apartado::withTrashed()
                ->whereDate('created_at',today())
                ->when($idTienda, fn($q)=> $q->where('tienda_id',$idTienda))
                ->sum('monto_anticipo');
            
            $inventario = InventarioTienda::when($idTienda, fn($q) => $q->where('tienda_id', $idTienda))
                ->sum('cantidad_stock');

            $apartadosActivos = Apartado::when($idTienda, fn($q)=> $q->where('tienda_id',$idTienda))->count();

            $porEntregar = Salida::where('estatus','Por entregar')
                ->when($idTienda, fn($q)=> $q->where('tienda_id',$idTienda))
                ->count();

            $baseQuery = fn() => Transaccion::when($idTienda, function($q) use ($idTienda) {
                $q->where('tienda_id', $idTienda);
            });
            //  INGRESOS
            $efectivo = $baseQuery()
                ->where('tipo_movimiento', 'entrada')
                ->where('tipo_pago', 'efectivo')
                ->sum('cantidad');
            //  EGRESOS
            $egresosEfectivo = $baseQuery()
                ->where('tipo_movimiento', 'salida')
                ->where('tipo_pago', 'efectivo')
                ->sum('cantidad');
            // EFECTIVO DE APERTURA
            $efectivoApertura = Corte::where('tienda_id', $idTienda)
                ->orderByDesc('id') // o fecha_cierre
                ->value('saldo_final') ?? 0;

            $totalEfectivo = $efectivo - $egresosEfectivo;
            $dineroEnCaja = $totalEfectivo + $efectivoApertura;

            return response()->json([
                'vendido' => floatval($dinero),
                'inventario' => $inventario,
                'apartados' => $apartadosActivos,
                'porEntregar' => $porEntregar,
                'enCaja' => $dineroEnCaja,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

}
