@extends('layouts.app')
@section('content')
<div class="container">
    {{-- parte 1 kpis --}}
    <br>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h4 class="mb-4">Reporte General de Ventas</h4>
            <div class="row" style="padding-bottom: 0.5rem">
                <div class="col-md-10 d-flex">
                    <div class="col-md-4">
                        <select name="tiendas" id="tiendas" class="form-control"></select>
                    </div>
                    <div class="col-md-8 d-flex">
                        <div class="col-md-6">
                            <input type="date" id="fecha_inicio" name="inicio" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <input type="date" id="fecha_fin" name="fin" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-light border rounded-4">
                <div class="row g-3 mb-4">
                    {{-- KPIS --}}
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-success-soft">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                            <div>
                                <small>Ventas Totales</small>
                                <h4 id="totalVentas"></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-info-soft">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <small># Ventas</small>
                                <h4 id="nVentas"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-warning-soft">
                                <i class="fa-solid fa-bookmark"></i>
                            </div>
                            <div>
                                <small># Apartados</small>
                                <h4 id="nApartados"></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-purple-soft">
                                <i class="fa-solid fa-calculator"></i>
                            </div>
                            <div>
                                <small>Nota Promedio</small>
                                <h4 id="notaPromedio"></h4>
                            </div>
                        </div>
                    </div>
                    {{-- mes anterior --}}
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-success-soft">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div>
                                <small>Ventas Mes Anterior</small>
                                <h4 id="totalVentasAnt"></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-info-soft">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <div>
                                <small># Ventas Mes anterior</small>
                                <h4 id="nVentasAnt"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-warning-soft">
                                <i class="fa-solid fa-bookmark"></i>
                            </div>
                            <div>
                                <small># Apartados Mes anterior</small>
                                <h4 id="nApartadosAnt"></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div  id="variacionKpi" class="summary-icon bg-purple-soft" >
                                <i class="fa-solid fa-arrow-trend-up" id="variacionArrow"></i>
                            </div>
                            <div>
                                <small>Variación</small>
                                <h4 id="variacion"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    {{-- parte 2 resumen financiero --}}
    <div class="alert alert-light border rounded-4 mt-3">

        <div class="d-flex align-items-center mb-3">
            <i class="fa-solid fa-scale-balanced me-2 text-primary"></i>
            <h5 class="mb-0">Resumen Financiero</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>
                            <i class="fa-solid fa-boxes-stacked text-primary me-1"></i>
                            Inventario
                        </th>
                        <th>
                            <i class="fa-solid fa-cash-register text-success me-1"></i>
                            Caja
                        </th>
                        <th>
                            <i class="fa-solid fa-building-columns text-info me-1"></i>
                            Cuenta
                        </th>
                        <th>
                            <i class="fa-solid fa-bookmark text-warning me-1"></i>
                            Apartados
                        </th>
                        <th>
                            <i class="fa-solid fa-wallet text-success me-1"></i>
                            Saldo Favor
                        </th>
                        <th>
                            <i class="fa-solid fa-file-invoice-dollar text-danger me-1"></i>
                            Adeudos
                        </th>
                        <th>
                            <i class="fa-solid fa-scale-balanced text-primary me-1"></i>
                            Balance
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <!-- Fecha actual -->
                    <tr>
                        <td>
                            <strong id="fechaActual">
                                
                            </strong>
                        </td>

                        <td id="inventarioActual"></td>
                        <td id="cajaActual"></td>
                        <td id="cuentaActual"></td>
                        <td id="apartadosActual"></td>
                        <td id="saldoFavorActual"></td>
                        <td id="adeudosActual"></td>

                        <td class="fw-bold text-success" id="balanceActual">
                            
                        </td>
                    </tr>

                    <!-- Fecha comparativa -->
                    <tr>
                        <td>
                            <strong id="fechaAnterior">
                                
                            </strong>
                        </td>

                        <td id="inventarioAnterior"></td>
                        <td id="cajaAnterior"></td>
                        <td id="cuentaAnterior"></td>
                        <td id="apartadosAnterior"></td>
                        <td id="saldoFavorAnterior"></td>
                        <td id="adeudosAnterior"></td>

                        <td class="fw-bold text-success" id="balanceAnterior">
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
    {{-- parte 3 ventas por tienda y vendedores --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="row">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h4 class="mb-4">Ventas por Tienda</h4>
                        <div class="alert alert-light border rounded-4">
                            <div class="table-responsive">
                                <table id="tbl_ventas" class="table table-borderless table-centered"
                                    style="border-collapse:collapse; font-size:13px"
                                >
                                    <thead>
                                        <tr>
                                            <th>Tienda</th>
                                            <th>Vendido</th>
                                            <th>Costos</th>
                                            <th>Diferencia</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h4 class="mb-4">Top Vendedores</h4>
                        <div class="alert alert-light border rounded-4">
                            <div class="table-responsive">
                                <table id="tbl_vendedores" class="table table-borderless table-centered"
                                    style="border-collapse:collapse; font-size:13px"
                                >
                                    <thead>
                                        <tr>
                                            <th>Vendedor</th>
                                            <th>Tienda</th>
                                            <th>Vendido</th>
                                            <th>#Notas</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- parte 4 top muebles y formas de pago --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="row">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h4 class="mb-4">Top Muebles</h4>
                        <div class="alert alert-light border rounded-4">
                            <div class="table-responsive">
                                <table id="tbl_muebles" class="table table-borderless table-centered"
                                    style="border-collapse:collapse; font-size:13px"
                                >
                                    <thead>
                                        <tr>
                                            <th>Tienda</th>
                                            <th>Mueble</th>
                                            <th>Vendidos</th>
                                            <th>Recaudado</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h4 class="mb-4">Formas de Pago</h4>
                        <div class="alert alert-light border rounded-4">
                            <div class="table-responsive">
                                <table id="tbl_pagos" class="table table-borderless table-centered"
                                    style="border-collapse:collapse; font-size:13px"
                                >
                                    <thead>
                                        <tr>
                                            <th>Método</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- parte 5 Utilidad --}}
    {{-- <div class="row mb-3">
        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-success-soft">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div>
                    <small>Ventas</small>
                    <h3 id="utilVentas">$240,000</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-danger-soft">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
                <div>
                    <small>Costos</small>
                    <h3 id="utilCostos">73</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-success-soft">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <small>Ganancias</small>
                    <h3 id="utilGanancias">40</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-info-soft">
                    <i class="fa-solid fa-percent"></i>
                </div>
                <div>
                    <small>Margen %</small>
                    <h3 id="utilMargen">$5000</h3>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- paare 6 cobranza pendiente --}}
    <div class="row mb-3">
        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-warning-soft">
                    <i class="fa-solid fa-bookmark"></i>
                </div>
                <div>
                    <small>Apartados activos</small>
                    <h3 id="apartadosPendientes"></h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-danger-soft">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <small>Saldo pendiente</small>
                    <h3 id="saldoPendiente"></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-success-soft">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <small>Entregas</small>
                    <h3 id="entregas"></h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-warning-soft">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <div>
                    <small>Entregas Pendientes</small>
                    <h3 id="entregasPendientes"></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/utilerias.js"></script>
<script src="/js/reportes/init.js"></script>
<script>
    $(document).ready(function () {
        dao.getKpisPrincipales();
        dao.getTablasTops();
        dao.getKpis2();
        dao.getDataBalances();
    })
</script>
    
@endsection