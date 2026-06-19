<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Transaccion;
use App\Models\Corte;
use App\Models\Cuenta;

class BalanceService
{
    public function calcular(int $tiendaId, string|Carbon $fecha): array
    {
        $fecha = Carbon::parse($fecha)->endOfDay();
        $inventario = $this->calcularInventario($tiendaId, $fecha);
        $caja = $this->calcularCajas($tiendaId,$fecha);
        $bancos = $this->calcularBancos($tiendaId, $fecha);
        $apartados = $this->calcularApartados($tiendaId);
        $saldoFavor = $this->calcularSaldoFavor($tiendaId, $fecha);
        $adeudos = $this->calcularAdeudos($tiendaId, $fecha);

        $balance = $inventario + $caja + $bancos + $apartados + $saldoFavor - $adeudos;

        return [
            'inventario' => round($inventario,2),
            'caja' => round($caja,2),
            'bancos' => round($bancos,2),
            'apartados' => round($apartados,2),
            'saldo_favor' => round($saldoFavor,2),
            'adeudos' => round($adeudos,2),
            'balance' => round($balance,2),
        ];
    }
    private function calcularCaja(int $tiendaId, Carbon $fecha): float
    {
        $efectivoApertura = $this->getSaldoInicialCaja($tiendaId);
        $movimientos = Transaccion::when($tiendaId, fn($q)=>$q->where('tienda_id',$tiendaId))
            ->selectRaw("
                SUM(CASE WHEN tipo_movimiento = 'entrada' THEN cantidad ELSE 0 END ) -
                SUM(CASE WHEN tipo_movimiento = 'salida' THEN cantidad ELSE 0 END )
            as total")
            ->where('tipo_pago','efectivo')
            ->where('created_at','<=','$fecha')
        ->value('total') ?? 0;

        return $efectivoApertura + $movimientos;
    }
    private function calcularBancos(int $tiendaId, Carbon $fecha): float
    {
        $cuenta = Cuenta::when($tiendaId, fn($q)=>$q->where('tienda_id',$tiendaId))
            ->selectRaw("
                SUM(CASE WHEN tipo_movimiento = 'entrada' THEN monto ELSE 0 END) -
                SUM(CASE WHEN tipo_movimiento = 'salida' THEN monto ELSE 0 END)
                as total")
            ->where('fecha_movimiento','<=',$fecha)
        ->value('total') ?? 0;

        return $cuenta;
    }
    private function calcularSaldoFavor(int $tiendaId, Carbon $fecha): float
    {
        $saldo = DB::table('pagos_ingresos_inventario')
            ->when($tiendaId, fn($q) => $q->where('tienda_id',$tiendaId))
            ->whereNull('deleted_at')
            ->where('fecha','<=',$fecha->toDateString())
            ->select(
                DB::raw("
                    SUM(CASE WHEN tipo = 'cargo' THEN monto ELSE 0 END)-
                    SUM(CASE WHEN tipo = 'abono' THEN monto ELSE 0 END) as total
                ")
            )
        ->value('total') ?? 0;

        return $saldo;
    }
    private function calcularAdeudos(int $tiendaId, Carbon $fecha): float
    {
        return DB::table('ingresos_inventario')->when($tiendaId, fn($q) => $q->where('tienda_id',$tiendaId))
            ->whereNull('deleted_at')
            ->where('fecha','<=',$fecha->toDateString())
            ->sum(
                DB::raw('total_compra - total_pagado')
            );
    }
    private function calcularApartados(int $tiendaId): float
    {
        return DB::table('apartados')->when($tiendaId, fn($q) => $q->where('tienda_id',$tiendaId))
            ->whereNull('deleted_at')
            ->whereNull('liquidado_at')
            ->sum('monto_restante');
    }
    private function calcularInventario(int $tiendaId, Carbon $fecha): float
    {
        return (float) DB::table('movimientos_inventario')
            ->when($tiendaId, fn($q) => $q->where('tienda_id',$tiendaId))
            ->where('fecha_movimiento','<=',$fecha)
            ->selectRaw('COALESCE(SUM(cantidad_movimiento * costo_unitario), 0) as total')
        ->value('total');
    }


    private function getSaldoInicialCaja($tiendaId = null){
        if ($tiendaId) {
            return Corte::where('tienda_id', $tiendaId)
                ->orderByDesc('id')
                ->value('saldo_final') ?? 0;
        }
        return Corte::selectRaw('SUM(saldo_final) as total')
            ->whereIn('id', function($query){
                $query->selectRaw('MAX(id)')
                    ->from('cortes')
                    ->groupBy('tienda_id');
            })
            ->value('total') ?? 0;
    }
}