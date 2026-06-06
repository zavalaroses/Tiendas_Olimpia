@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-4">Inventario</h4>
            <div class="alert alert-light border rounded-4">
                <div class="row" style="padding-bottom: 0.5rem">
                    <div class="col-md-10">
                        @if(Auth::user()->rol == 1)
                            <div class="col-md-4">
                                <select name="tiendas" id="tiendas" class="form-control"></select> 
                            </div>
                        @endif
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        <button id="btnAddInventario" type="button" name="btnAddInventario" class="btnNuevoUsuario">Nueva Entrada</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tbl_inventarios" style="width: 100%;" class="table table-borderless table-centered">
                        <caption class="captionTbl">
                            <br>
                            <div class="row" style="align-items: center; justify-content: center;">
                                <div class="col-md-6 titleCenter" id="tituto_tienda"></div>
                            </div>
                        </caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Tienda</th>
                                <th scope="col">Mueble</th>
                                <th scope="col">Precio</th>
                                <th scope="col">En inventario</th>
                                <th scope="col">Apartados</th>
                                <th scope="col">Por entregar</th>
                                <th scope="col">En garantia</th>
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
<script src="/js/inventario/init.js"></script>
@include('inventario.modalAddentrada')
@endsection
