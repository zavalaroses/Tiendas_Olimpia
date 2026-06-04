@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-4">Catalogo Muebles</h4>
            <div class="alert alert-light border rounded-4">
                <div class="row" style="padding-bottom: 0.5rem;">
                    <div class="col-md-10"></div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        @if(Auth::user()->rol == 1)
                            <button id="btnAddMueble" type="button" name="btnAddMueble" class="btnNuevoUsuario">Agregar</button>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tbl_muebles" style="width: 100%;" class="table table-borderless table-centered">
                        <caption class="captionTbl"></caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Codigo</th>
                                <th scope="col">Descipción</th>
                                <th scope="col">Precio venta</th>
                                @if(Auth::user()->rol == 1)
                                    <th scope="col">Precio compra</th>
                                    <th scope="col">Acciones</th>
                                @endif
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
<script src="/js/catalogos/muebles/init.js"></script>
<script>
    $(document).ready(function () {
        dao.getData();
    })
</script>
@include('catalogos.muebles.modalAddMueble')
@include('catalogos.muebles.modalUpdateMueble')
@endsection
