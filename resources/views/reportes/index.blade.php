<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
 <!-- Content here -->
    <div class="row">
    {{-- 🔎 FILTROS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label>Fecha inicio</label>
                    <input type="date" id="fecha_inicio" name="inicio" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Fecha fin</label>
                    <input type="date" id="fecha_fin" name="fin" class="form-control">
                </div>

                @if(Auth::user()->rol == 1)
                <div class="col-md-3">
                    <label>Tienda</label>
                    <select id="tiendas" name="tiendas" class="form-control">
                        <option value="">Todas</option>
                        {{-- llenar por AJAX o blade --}}
                    </select>
                </div>
                @endif

                <div class="col-md-3">
                    <button class="btn btn-primary w-100" onclick="cargarReportes()" disabled>
                        Generar reporte
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br>
    {{-- 💰 KPIs --}}
    <div class="row g-3">

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <small>Ventas</small>
                    <h4 id="kpi_ventas">$0.00</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <small>Gastos</small>
                    <h4 id="kpi_gastos">$0.00</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <small>Utilidad</small>
                    <h4 id="kpi_utilidad">$0.00</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <small>Inventario (valor)</small>
                    <h4 id="kpi_inventario">$0.00</h4>
                </div>
            </div>
        </div>

    </div>

    {{-- 🏦 SEGUNDA FILA KPIs --}}
    <div class="row g-3 mt-1">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small>Dinero en caja</small>
                    <h5 id="kpi_caja">$0.00</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small>Dinero en cuenta</small>
                    <h5 id="kpi_cuenta">$0.00</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small>Adeudo proveedores</small>
                    <h5 id="kpi_adeudo">$0.00</h5>
                </div>
            </div>
        </div>

    </div>

    {{-- 📊 TABS DETALLE --}}
    <div class="card mt-4 shadow-sm">
        <div class="card-body">

            <ul class="nav nav-tabs" id="tabsReportes">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabVentas">
                        Ventas
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabGastos">
                        Gastos
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabInventario">
                        Inventario
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabProveedores">
                        Proveedores
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3">

                {{-- Ventas --}}
                <div class="tab-pane fade show active" id="tabVentas">
                    <div id="tablaVentas">
                        <table class="table table-sm table-striped" id="tbl_apartados">
                            <thead>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Pago</th>
                                <th>Monto</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- Gastos --}}
                <div class="tab-pane fade" id="tabGastos">
                    <div id="tablaGastos">
                        <table class="table table-sm table-striped" id="tbl_gastos">
                            <thead>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Tipo pago</th>
                                <th>Monto</th>
                                <th>Usuario</th>
                            </thead>
                        </table>
                    </div>
                </div>

                {{-- Inventario --}}
                <div class="tab-pane fade" id="tabInventario">
                    <div id="tablaInventario">
                        <table class="table table-sm table-striped" id="tbl_inventario">
                            <thead>
                                <th>Mueble</th>
                                <th>Stock</th>
                                <th>Precio compra</th>
                                <th>Valor total</th>
                            </thead>
                        </table>
                    </div>
                </div>

                {{-- Proveedores --}}
                <div class="tab-pane fade" id="tabProveedores">
                    <div id="tablaProveedores">
                        <table class="table table-sm table-striped" id="tbl_proveedores">
                            <thead> 
                                <th>Proveedor</th>
                                <th>Total compra</th>
                                <th>Total pagado</th>
                                <th>Adeudo</th>
                                <th>Estatus</th>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
    
</div>

<script src="/js/utilerias.js"></script>
<script src="/js/reportes/init.js"></script>


@endsection