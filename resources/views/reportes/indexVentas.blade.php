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
                                <h3 id="totalVentas">$240,000</h3>
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
                                <h3 id="nVentas">73</h3>
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
                                <h3 id="nApartados">40</h3>
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
                                <h3 id="notaPromedio">$5000</h3>
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
                                <h3 id="totalVentasAnt">$220,000</h3>
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
                                <h3 id="nVentasAnt">70</h3>
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
                                <h3 id="nApartadosAnt">35</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-purple-soft">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                            </div>
                            <div>
                                <small>Variación</small>
                                <h3 id="notaPromedio">12%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    
    {{-- parte dos ventas por tienda y vendedores --}}
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
                                            <th>Utilidad</th>
                                            <th>% Participación</th>
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
                                            <th>Vendido</th>
                                            <th>Comisión</th>
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
    {{-- parte 3 top muebles y formas de pago --}}
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
                                            <th>Cantidad</th>
                                            <th>Ventas</th>
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
                                            <th>Vendedor</th>
                                            <th>Vendido</th>
                                            <th>Comisión</th>
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
    {{-- parte 4 Utilidad --}}
    <div class="row mb-3">
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
    </div>
    {{-- cobranza pendiente --}}
    <div class="row mb-3">
        <div class="col-md-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon bg-warning-soft">
                    <i class="fa-solid fa-bookmark"></i>
                </div>
                <div>
                    <small>Apartados activos</small>
                    <h3 id="apartadosPendientes">$240,000</h3>
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
                    <h3 id="saldoPendiente">73</h3>
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
                    <h3 id="entregas">40</h3>
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
                    <h3 id="entregasPendientes">9</h3>
                </div>
            </div>
        </div>
    </div>

    

</div>
    
@endsection