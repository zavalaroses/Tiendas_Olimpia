@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-4">Historial de Cortes de Caja</h4>
            <div class="alert alert-light border rounded-4">
                <div class="row" style="margin-bottom: 0.5rem;">
                    <div class="col-md-3">
                        <select id="filtro_tienda" name="filtro_tienda" class="form-control select2">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="filtro_inicio" class="form-control">
                    </div>
                    <div class="col-md-3">
                        
                        <input type="date" id="filtro_fin" class="form-control">
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tabla_cortes" class="table table-borderless table-centered">
                        <caption class="captionTbl"></caption>
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
    </div>
</div>
@include('historial.modalDetalleCorte')
@include('historial.modalDetalleTransaccion')
<script src="/js/utilerias.js"></script>
<script src="/js/historial/init.js"></script>

@endsection
