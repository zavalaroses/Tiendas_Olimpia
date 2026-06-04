@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-4">Apartados</h4>
            <div class="alert alert-light border rounded-4">
                <div class="row" style="padding-bottom: 0.5rem">
                    <div class="col-md-8 d-flex">
                        @if(Auth::user()->rol == 1)
                        <div class="col-md-4">
                            <select name="tiendas" id="tiendas" class="form-control"></select> 
                        </div>
                    @endif
                    <div class="col-md-8 d-flex px-2">
                            <div class="col-md-6">
                                <select name="muebles" id="muebles" class="form-control">
                                    <option value="">Selecciona un mueble</option>
                                </select>
                            </div>
                            <div class="col-md-6 px-2">
                                <select name="clientes" id="clientes" class="form-control">
                                    <option value="">Selecciona un cliente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end justify-content-end">
                        <button id="btnAddPedido" type="button" name="btnAddPedido" class="btnNuevoUsuario">+ Pedido</button>
                        <button id="btnAddApartado" type="button" name="btnAddApartado" class="btnNuevoUsuario">Nuevo Apartado</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tbl_apartados" class="table table-borderless table-centered">
                        <caption class="captionTbl">
                            <br>
                            <div class="row" style="align-items: center; justify-content: center;">
                                <div class="col-md-6 titleCenter" id="tituto_tienda">Tienda</div>
                            </div>
                        </caption>
                        <thead>
                            <tr>
                                <th scope="col">ID Nota</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Anticipo</th>
                                <th scope="col">Restante</th>
                                <th scope="col">Fecha Apartado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/utilerias.js"></script>
<script src="/js/apartados/init.js"></script>
@include('apartados.modalAddApartados')
@include('apartados.modalPagarAdelanto')
@include('apartados.modalAddPedido')
@include('apartados.modalDetalleApartado')
@include('apartados.modalEditApartados')
@endsection
