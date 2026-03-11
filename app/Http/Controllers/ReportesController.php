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
use Barryvdh\DomPDF\Facade\Pdf;

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

}
