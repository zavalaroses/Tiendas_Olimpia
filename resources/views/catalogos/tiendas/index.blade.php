@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-4">Catalogo Tiendas</h4>
            <div class="alert alert-light border rounded-4">
                <div class="row" style="padding-bottom: 0.5rem">
                    <div class="col-md-10"></div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        <button id="btnAddTienda" type="button" name="btnAddTienda" class="btnNuevoUsuario">Agregar</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tbl_tiendas" class="table table-borderless table-centered">
                        <caption class="captionTbl"></caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Dirección</th>
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
<script src="/js/catalogos/tiendas/init.js"></script>
<script>
    $(document).ready(function () {
        dao.getDataTiendas();
    })
</script>
@include('catalogos.tiendas.modalAddTiendas')
@endsection
