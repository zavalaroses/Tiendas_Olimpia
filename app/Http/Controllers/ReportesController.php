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

class ReportesController extends Controller
{
    public function getReportes(){
        return view('reportes.index');
    }

    public function getDataResumen(Request $request){
        $tiendaId = $request->tienda ?: Auth::user()->tienda_id;
        $inicio = $request->inicio;
        $fin = $request->fin;

        // helper filtro fechas
        $filtroFecha = function ($q, $col = 'created_at') use ($inicio, $fin){
            if ($inicio) $q->whereDate($col, '>=', $inicio);
            if ($fin) $q->whereDate($col, '<=', $fin);
        };

        // Ventas
        $ventas = Transaccion::where('tipo_movimiento','entrada')
            ->when($tiendaId, fn($q) => $q->where('tienda_id',$tiendaId) )
            ->when($inicio || $fin, fn($q) => $filtroFecha($q) )
        ->sum('cantidad');

        // Gastos
        $gastos = Transaccion::where('tipo_movimiento','salida')
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
        $caja = Transaccion::when($tiendaId, fn($q)=>$q->where('tienda_id',$tiendaId))
            ->selectRaw("
                SUM(CASE WHEN tipo_movimiento = 'entrada' THEN cantidad ELSE 0 END ) -
                SUM(CASE WHEN tipo_movimiento = 'salida' THEN cantidad ELSE 0 END )
            as total")
            ->where('tipo_pago','efectivo')
        ->value('total') ?? 0;

        // Cuenta 
        $cuenta = Cuenta::when($tiendaId, fn($q)=>$q->where('tienda_id',$tiendaId))
            ->selectRaw("
                SUM(CASE WHEN tipo_movimiento = 'entrada' THEN monto ELSE 0 END) -
                SUM(CASE WHEN tipo_movimiento = 'salida' THEN monto ELSE 0 END)
                as total")
        ->value('total') ?? 0;

        // Adeudo proveedores
        $adeudo = Entrada::when($tiendaId,fn($q)=>$q->where('tienda_id',$tiendaId))
            ->selectRaw('
                SUM(total_compra - total_pagado) as total
            ')
        ->value('total') ?? 0;

        return response()->json([
            'ventas'=>(float)$ventas,
            'gastos'=>(float)$gastos,
            'utilidad'=>(float)$utilidad,
            'inventario'=>(float)$inventario,
            'caja'=>(float)$caja,
            'cuenta'=>(float)$cuenta,
            'adeudo'=>(float)$adeudo
        ],200);

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

        $data = Transaccion::where('tipo_movimiento','entrada')
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

        $data = Transaccion::leftJoin('users as u','u.id','=','movimientos_tienda.user_id')
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

        // helper filtros fechas
        $filtroFecha = function ($q, $col = 'inventario_tienda.created_at') use ($inicio, $fin){
            if ($inicio) $q->whereDate($col, '>=', $inicio);
            if ($fin) $q->whereDate($col, '<=', $fin);
        };

        $data = InventarioTienda::join('muebles as m','m.id','=','inventario_tienda.mueble_id')
            ->selectRaw("
                m.nombre as mueble,
                inventario_tienda.cantidad_stock as stock,
                m.precio_compra,
                inventario_tienda.cantidad_stock * m.precio_compra as valor
            ")
            ->when($inicio || $fin, fn($q) => $filtroFecha($q))
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



}
