<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Log;
use Carbon\Carbon;
use App\Models\Transaccion;
use App\Models\InventarioTienda;
use App\Models\Cuenta;
use App\Models\Entrada;
use App\Models\Corte;
use App\Models\PagoIngresoInventario;
use App\Models\Salida;
use App\Models\Apartado;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\BalanceService;

class ReportesController extends Controller
{
    public function getReportes(){
        return view('reportes.index');
    }

    public function getDataResumen(Request $request){
        $tiendaId = $request->tienda ?: Auth::user()->tienda_id;
        $inicio = $request->inicio;
        $fin = $request->fin;

        $data = $this->calcularResumen($tiendaId,$inicio,$fin);

        return response()->json($data,200);
    }
    public function getVentas(Request $request){
        $tiendaId = $request->tienda ?: Auth::user()->tienda_id;
        $inicio = $request->inicio;
        $fin = $request->fin;

        // helper filtro fechas
        $filtroFecha = function ($q, $col = 'created_at') use ($inicio, $fin){
            if ($inicio) $q->whereDate($col, '>=', $inicio);
            if ($fin) $q->whereDate($col, '<=', $fin);
        };

        $data = Transaccion::withTrashed()->where('tipo_movimiento','entrada')
            ->select(
                'created_at',
                'descripcion',
                'cantidad',
                'tipo_pago',
            )
            ->when($tiendaId, fn($q)=>$q->where('tienda_id',$tiendaId))
            ->when($inicio || $fin, fn($q) => $filtroFecha($q))
            ->orderByDesc('id')
        ->get();
        return response()->json($data,200);

    }
    public function getGastos(Request $request){
        $tiendaId = $request->tienda ?: Auth::user()->tienda_id;
        $inicio = $request->inicio;
        $fin = $request->fin;

         // helper filtro fechas
        $filtroFecha = function ($q, $col = 'movimientos_tienda.created_at') use ($inicio, $fin){
            if ($inicio) $q->whereDate($col, '>=', $inicio);
            if ($fin) $q->whereDate($col, '<=', $fin);
        };

        $data = Transaccion::withTrashed()->leftJoin('users as u','u.id','=','movimientos_tienda.user_id')
            ->select(
                'movimientos_tienda.cantidad',
                'movimientos_tienda.tipo_movimiento',
                'movimientos_tienda.descripcion',
                'movimientos_tienda.tipo_pago',
                'movimientos_tienda.created_at',
                'u.name as usuario',
            )
            ->where('tipo_movimiento','salida')
            ->when($tiendaId, fn($q)=>$q->where('movimientos_tienda.tienda_id',$tiendaId))
            ->when($inicio || $fin, fn($q) => $filtroFecha($q))
            ->orderByDesc('movimientos_tienda.id')
        ->get();

        return response()->json($data,200);
    }
    public function getInventario(Request $request){
        $tiendaId = $request->tienda ?: Auth::user()->tienda_id;
        $inicio = $request->inicio;
        $fin = $request->fin;

        // // helper filtros fechas
        // $filtroFecha = function ($q, $col = 'inventario_tienda.created_at') use ($inicio, $fin){
        //     if ($inicio) $q->whereDate($col, '>=', $inicio);
        //     if ($fin) $q->whereDate($col, '<=', $fin);
        // };

        $data = InventarioTienda::join('muebles as m','m.id','=','inventario_tienda.mueble_id')
            ->selectRaw("
                m.nombre as mueble,
                inventario_tienda.cantidad_stock as stock,
                m.precio_compra,
                inventario_tienda.cantidad_stock * m.precio_compra as valor
            ")
            // ->when($inicio || $fin, fn($q) => $filtroFecha($q))
            ->when($tiendaId, fn($q)=>$q->where('inventario_tienda.tienda_id',$tiendaId))
            ->orderByDesc('valor')
        ->get();

        return response()->json($data,200);
    }
    public function getProveedores(Request $request){
        $tiendaId = $request->tienda ?: Auth::user()->tienda_id;
        $inicio = $request->inicio;
        $fin = $request->fin;

        // helper filtros fechas
        $filtroFecha = function ($q, $col = 'ingresos_inventario.created_at') use ($inicio, $fin){
            if ($inicio) $q->whereDate($col, '>=', $inicio);
            if ($fin) $q->whereDate($col, '<=', $fin);
        };

        $data = Entrada::join('proveedores as p','p.id','=','ingresos_inventario.proveedor_id')
            ->when($tiendaId, fn($q)=>$q->where('ingresos_inventario.tienda_id',$tiendaId))
            ->when($inicio || $fin, fn($q) => $filtroFecha($q))
            ->selectRaw("
                p.nombre as proveedor,
                total_compra,
                total_pagado,
                (total_compra - total_pagado) as adeudo,
                estatus_pago
            ")
            ->orderByDesc('adeudo')
        ->get();

        return response()->json($data,200);
    }
    public function getSaldoInicialCaja($tiendaId = null){
        // 🔹 Si viene tienda específica
        if ($tiendaId) {

            return Corte::where('tienda_id', $tiendaId)
                ->orderByDesc('id')
                ->value('saldo_final') ?? 0;
        }

        // 🔹 Si es modo global (todas las tiendas)
        return Corte::selectRaw('SUM(saldo_final) as total')
            ->whereIn('id', function($query){
                $query->selectRaw('MAX(id)')
                    ->from('cortes')
                    ->groupBy('tienda_id');
            })
            ->value('total') ?? 0;
    }
    private function calcularResumen($tiendaId,$inicio,$fin){
        // helper filtro fechas
        $filtroFecha = function ($q, $col = 'created_at') use ($inicio, $fin){
            if ($inicio) $q->whereDate($col, '>=', $inicio);
            if ($fin) $q->whereDate($col, '<=', $fin);
        };
        // Ventas
        $ventas = Transaccion::withTrashed()->where('tipo_movimiento','entrada')
            ->when($tiendaId, fn($q) => $q->where('tienda_id',$tiendaId) )
            ->when($inicio || $fin, fn($q) => $filtroFecha($q) )
        ->sum('cantidad');
        // Gastos
        $gastos = Transaccion::withTrashed()->where('tipo_movimiento','salida')
            ->when($tiendaId, fn($q) => $q->where('tienda_id', $tiendaId) )
            ->when($inicio || $fin, fn($q) => $filtroFecha($q))
        ->sum('cantidad');
        // Utilidad
        $utilidad = $ventas - $gastos;
        // Inventario
        $inventario = InventarioTienda::join('muebles as m','m.id','=','inventario_tienda.mueble_id')
            ->when($tiendaId, fn($q) => $q->where('inventario_tienda.tienda_id',$tiendaId) )
            ->selectRaw('SUM(inventario_tienda.cantidad_stock * 
                CASE
                    WHEN m.precio_compra > 0 THEN m.precio_compra
                    ELSE m.precio
                END
            ) as total')
        ->value('total') ?? 0;
        // Dinero en caja
        $efectivoApertura = $this->getSaldoInicialCaja($tiendaId);
        $movimientos = Transaccion::when($tiendaId, fn($q)=>$q->where('tienda_id',$tiendaId))
            ->selectRaw("
                SUM(CASE WHEN tipo_movimiento = 'entrada' THEN cantidad ELSE 0 END ) -
                SUM(CASE WHEN tipo_movimiento = 'salida' THEN cantidad ELSE 0 END )
            as total")
            ->where('tipo_pago','efectivo')
        ->value('total') ?? 0;

        $caja = $efectivoApertura + $movimientos;

        // Cuenta 
        $cuenta = Cuenta::when($tiendaId, fn($q)=>$q->where('tienda_id',$tiendaId))
            ->selectRaw("
                SUM(CASE WHEN tipo_movimiento = 'entrada' THEN monto ELSE 0 END) -
                SUM(CASE WHEN tipo_movimiento = 'salida' THEN monto ELSE 0 END)
                as total")
        ->value('total') ?? 0;

        //Total compras
        $totalCompras = Entrada::when($tiendaId,fn($q)=>$q->where('tienda_id',$tiendaId))
            ->when($inicio || $fin, fn($q)=>$filtroFecha($q))
            ->sum('total_compra');
        
        //Total abonos
        $totalAbonos = PagoIngresoInventario::where('tipo','abono')
            ->when($tiendaId,fn($q)=>$q->where('tienda_id',$tiendaId))
            ->when($inicio || $fin, fn($q)=>$filtroFecha($q,'fecha'))
            ->sum('monto');
        
        // Total saldo a favor
        $saldoFavor = PagoIngresoInventario::where('tipo','cargo')
            ->when($tiendaId,fn($q)=>$q->where('tienda_id',$tiendaId))
            ->when($inicio || $fin, fn($q)=>$filtroFecha($q,'fecha'))
            ->sum('monto');
        
        // Deuda real
        $adeudo = max($totalCompras - $totalAbonos, 0);
       
        $balance = $inventario + $caja + $cuenta + $saldoFavor - $adeudo;

        return compact(
            'ventas',
            'gastos',
            'balance',
            'inventario',
            'caja',
            'cuenta',
            'adeudo',
            'saldoFavor',
        );

    }
    public function pruebaPDF(Request $request){
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        $tiendaId = $request->tiendas ?: Auth::user()->tienda_id;
        $inicio = $request->inicio;
        $fin = $request->fin;

        $tienda = DB::table('tiendas')->where('id',$tiendaId)->whereNull('deleted_at')->value('nombre');

        $data = $this->calcularResumen($tiendaId,$inicio,$fin);

        $data['inicio'] = $inicio;
        $data['fin'] = $fin;
        $data['tienda'] = $tienda ?: 'Todas las tiendas.';

        $pdf = Pdf::loadView('reportes.reportePDF',$data);

        return $pdf->stream('reporte_resumen_financiero.pdf');
    }
    // funciones para nuevos reportes
    public function getReportesVentas(){
        return view('reportes.indexVentas');
    }
    public function getKpiPrincipal(Request $request){
        $inicio = $request->inicio ? Carbon::parse($request->inicio) : null;
        $fin = $request->fin ? Carbon::parse($request->fin) : null;
        $dias = null;
        $inicioComp = null;
        $finComp = null;
        if ($inicio && $fin) {
            $dias = $inicio->diffInDays($fin) + 1;
            $inicioComp = $inicio->copy()->subDays($dias);
            $finComp = $inicio->copy()->subDay();
        }
        $vendido = Transaccion::withTrashed()
            ->when($request->tienda,fn($q)=> $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){
                if ($inicio && $fin) {
                    $query->whereBetween('created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento', 'entrada')
            ->whereNotNull('venta_id')
        ->sum('cantidad');

        $nApartados = Transaccion::withTrashed()
            ->when($request->tienda, fn($q) => $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){
                if ($inicio && $fin) {
                    $query->whereBetween('created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento','entrada')
            ->where('descripcion','Monto de anticipo')
            ->whereNotNull('venta_id')
        ->count();
        $nVentas = Transaccion::withTrashed()
            ->when($request->tienda, fn($q) => $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){
                if ($inicio && $fin) {
                    $query->whereBetween('created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento','entrada')
            ->where('descripcion','Venta')
            ->whereNotNull('venta_id')
        ->count();

        $totalNotas = Transaccion::withTrashed()
            ->when($request->tienda, fn($q)=> $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){
                if ($inicio && $fin) {
                    $query->whereBetween('created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->whereNotNull('venta_id')
            ->distinct('venta_id')
        ->count('venta_id');
        $ticketPromedio = $totalNotas > 0 ? $vendido / $totalNotas : 0;

        $vendidoAnt = Transaccion::withTrashed()
            ->when($request->tienda,fn($q)=> $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicioComp,$finComp){
                if ($inicioComp && $finComp) {
                    $query->whereBetween('created_at',[$inicioComp, $finComp]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento', 'entrada')
            ->whereNotNull('venta_id')
        ->sum('cantidad');

        $nApartadosAnt = Transaccion::withTrashed()
            ->when($request->tienda, fn($q) => $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicioComp,$finComp){
                if ($inicioComp && $finComp) {
                    $query->whereBetween('created_at',[$inicioComp, $finComp]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento','entrada')
            ->where('descripcion','Monto de anticipo')
            ->whereNotNull('venta_id')
        ->count();

        $nVentasAnt = Transaccion::withTrashed()
            ->when($request->tienda, fn($q) => $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicioComp,$finComp){
                if ($inicioComp && $finComp) {
                    $query->whereBetween('created_at',[$inicioComp, $finComp]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento','entrada')
            ->where('descripcion','Venta')
            ->whereNotNull('venta_id')
        ->count();
        
        $variacion = $this->calcularVariacion($vendido,$vendidoAnt);

        $response = [
            'vendido' => $vendido,
            'vendidoAnt' => $vendidoAnt,
            'nApartados' => $nApartados,
            'nApartadosAnt' => $nApartadosAnt,
            'nVentas' => $nVentas,
            'nVentasAnt' => $nVentasAnt,
            'ticketPromedio' => $ticketPromedio,
            'variacion' => $variacion
        ];
        return response()->json($response,200);

    }
    public function getTablasTops(Request $request){
        $inicio = $request->inicio ? Carbon::parse($request->inicio) : null;
        $fin = $request->fin ? Carbon::parse($request->fin) : null;
        $dias = null;
        $inicioComp = null;
        $finComp = null;
        if ($inicio && $fin) {
            $dias = $inicio->diffInDays($fin) + 1;
            $inicioComp = $inicio->copy()->subDays($dias);
            $finComp = $inicio->copy()->subDay();
        }

        $ventasPorTienda = DB::table('movimientos_tienda as mt')
            ->join('apartados as a','a.id','=','mt.venta_id')
            ->join('tiendas as t','t.id','=','a.tienda_id')
            ->selectRaw('
                a.tienda_id,
                t.nombre as tienda,
                SUM(mt.cantidad) as vendido
            ')
            ->when($request->tienda,fn($q)=> $q->where('mt.tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){
                if ($inicio && $fin) {
                    $query->whereBetween('mt.created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('mt.created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('mt.tipo_movimiento','entrada')
            ->whereNotNull('mt.venta_id')
            ->groupBy('a.tienda_id','t.nombre')
            ->orderBy('vendido','DESC')
            ->get();
        $costosPorTienda = DB::table('apartados as a')
            ->join('apartado_muebles as am','am.id_apartado','=','a.id')
            ->join('muebles as m','m.id','=','am.id_mueble')
            ->selectRaw('
                a.tienda_id,
                SUM(
                    am.cantidad * m.precio_compra
                ) as costo
            ')
            ->when($request->tienda,fn($q)=> $q->where('a.tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){

                if ($inicio && $fin) {
                    $query->whereBetween('am.created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('am.created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->groupBy('a.tienda_id')
            ->pluck('costo','tienda_id');
        $ventasPorTienda->transform(function ($item) use ($costosPorTienda){
            $costo = $costosPorTienda[$item->tienda_id] ?? 0;
            $item->costo = $costo;
            $item->utilidad = $item->vendido - $costo;
            return $item;
        });

        $vendedores = Transaccion::withTrashed()
            ->join('tiendas as t','t.id','=','movimientos_tienda.tienda_id')
            ->join('users as u','u.id','=','movimientos_tienda.user_id')
            ->select(
                'u.name as usuario',
                't.nombre as tienda',
                DB::raw("SUM(cantidad) as vendido"),
                DB::raw("
                    COUNT(DISTINCT(venta_id)) as notas
                ")
            )
            ->when($request->tienda,fn($q)=> $q->where('movimientos_tienda.tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){

                if ($inicio && $fin) {
                    $query->whereBetween('movimientos_tienda.created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('movimientos_tienda.created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento','entrada')
            ->whereNotNull('venta_id')
            ->orderBy('vendido','DESC')
            ->groupBy('u.name','t.nombre')
        ->get();

        $formasPago = Transaccion::withTrashed()
            ->select(
                'tipo_pago',
                DB::raw("SUM(cantidad) as vendido")
            )
            ->when($request->tienda,fn($q)=> $q->where('movimientos_tienda.tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){

                if ($inicio && $fin) {
                    $query->whereBetween('movimientos_tienda.created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('movimientos_tienda.created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento','entrada')
            ->whereNotNull('venta_id')
            ->orderBy('vendido','DESC')
            ->groupBy('tipo_pago')
        ->get();

        $muebles = DB::table('apartados as a')->join('tiendas as t','t.id','=','a.tienda_id')
            ->join('apartado_muebles as am','am.id_apartado','=','a.id')
            ->join('muebles as m','m.id','=','am.id_mueble')
            ->when($request->tienda,fn($q)=> $q->where('a.tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){

                if ($inicio && $fin) {
                    $query->whereBetween('a.created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('a.created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->selectRaw('
                t.nombre as tienda,
                SUM(am.cantidad) as vendidos,
                m.nombre as mueble,
                SUM(am.cantidad) * m.precio as recaudado
            ')
            ->groupBy('t.nombre','m.nombre','m.precio')
            ->orderBy('vendidos','DESC')
            ->limit(10)
        ->get();
        
        $response = [
            'tblVentas' => $ventasPorTienda,
            'tblVendedores' => $vendedores,
            'tblFormasPago' => $formasPago,
            'tblMuebles' => $muebles,
        ];
        return response()->json($response,200);

    }
    Public function getKpisParte2(Request $request){
        $inicio = $request->inicio ? Carbon::parse($request->inicio) : null;
        $fin = $request->fin ? Carbon::parse($request->fin) : null;

        $vendido = Transaccion::withTrashed()
            ->when($request->tienda,fn($q)=> $q->where('tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){
                if ($inicio && $fin) {
                    $query->whereBetween('created_at',[$inicio, $fin]);
                }else {
                    $query->whereBetween('created_at',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('tipo_movimiento', 'entrada')
            ->whereNotNull('venta_id')
        ->sum('cantidad');

        $apartadosActivos = Apartado::when($request->tienda, fn($q)=> $q->where('tienda_id',$request->tienda))->count();
        $saldoPendiente = Apartado::when($request->tienda, fn($q)=> $q->where('tienda_id',$request->tienda))->sum('monto_restante');
        $entregas = Salida::join('apartados as a','a.id','=','salidas.apartado_id')
            ->when($request->tienda, fn($q) => $q->where('a.tienda_id',$request->tienda))
            ->where(function($query) use($inicio,$fin){
                if ($inicio && $fin) {
                    $query->whereBetween('salidas.fecha_entrega',[$inicio, $fin]);
                }else {
                    $query->whereBetween('salidas.fecha_entrega',[
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                }
            })
            ->where('salidas.estatus','Entregado')
        ->count();
        
        $entregasPendientes = Salida::join('apartados as a','a.id','=','salidas.apartado_id')
            ->when($request->tienda, fn($q) => $q->where('a.tienda_id',$request->tienda))
            ->where('salidas.estatus','Por entregar')
        ->count();

        return response()->json([
            'apartadosActivos' => $apartadosActivos,
            'saldoPendiente' => $saldoPendiente,
            'entregas' => $entregas,
            'entregasPendientes' => $entregasPendientes
        ]);
    }
    private function calcularVariacion($actual,$anterior){
        if ($anterior == 0) {
            return $actual > 0 ? 100 : 0;
        }
        return round(
            (
                ($actual - $anterior) / $anterior
            ) * 100,
            2
        );
    }
    public function getBalances(Request $request, BalanceService $balanceService){
        $tiendaId = $request->tienda ?? null;
        $inicio = $request->inicio ? Carbon::parse($request->inicio) : null;
        $fin = $request->fin ? Carbon::parse($request->fin) : null;
        $balanceActual = null;
        $balanceAnterior = null;
        if ($inicio && $fin) {
            # Balance historico de cierres...
            $balanceActual = DB::table('cierres_financieros')
                ->whereDate('fecha', '<=', $fin)
                ->orderByDesc('fecha')
            ->first();

            $balanceAnterior = DB::table('cierres_financieros')
                ->whereDate('fecha', '<', $inicio)
                ->orderByDesc('fecha')
            ->first();
        }else {
            # balance en tiempo real vs ultimo cierre...
            $balanceActual = $balanceService->calcular($tiendaId,Carbon::now());

            $balanceAnterior = DB::table('cierres_financieros')
                ->when($tiendaId, fn($q) => $q->where('tienda_id', $tiendaId))
                ->orderByDesc('fecha')
            ->first();
        }
        return response()->json([
            'balanceActual' => $balanceActual,
            'balanceAnterior' => $balanceAnterior
        ],200);
    }

}
