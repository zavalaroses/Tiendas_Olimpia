<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
    <div class="row d-fles" style="text-align: center">
        <h2>Historial de Cortes de Caja</h2>
    </div>
 <!-- Content here -->
    <div class="row">
        {{-- FILTROS --}}
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-3">
                <label>Tienda</label>
                <select id="filtro_tienda" name="filtro_tienda" class="form-control select2">
                </select>
            </div>

            <div class="col-md-3">
                <label>Fecha Inicial</label>
                <input type="date" id="filtro_inicio" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Fecha Final</label>
                <input type="date" id="filtro_fin" class="form-control">
            </div>
        </div>
    </div>
     <br>
    <div class="row">
        <div class="table-responsive">
            <table id="tabla_cortes" class="table table-borderless table-centered" style="width: 100%;">
                <caption class="captionTbl">
                    <br>
                    <div class="row" style="align-items: center; justify-content: center;">
                        <div class="col-md-6 titleCenter" id="tituto_tienda">Tiendas</div>
                    </div>
                </caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tienda</th>
                        <th>Usuario</th>
                        <th>Efectivo Esperado</th>
                        <th>Cuenta</th>
                        <th>General</th>
                        <th>Efectivo Contado</th>
                        <th>Diferencia</th>
                        <th>Egresos</th>
                        <th>Fecha de Corte</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@include('historial.modalDetalleCorte')
<script src="/js/utilerias.js"></script>
<script src="/js/historial/init.js"></script>

@endsection
