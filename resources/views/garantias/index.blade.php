@extends('layouts.app')
@section('content')
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<br>
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-4">Garantias</h4>
            <div class="alert alert-light border rounded-4">
                <div class="row" style="padding-bottom: 0.5rem">
                    <div class="col-md-10">
                        @if(Auth::user()->rol == 1)
                        <div class="col-md-4">
                            <select name="tiendas" id="tiendas" class="form-control"></select> 
                        </div>
                    @endif
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tblGarantias" class="table table-borderless table-centered">
                        <caption class="captionTbl"></caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Tienda</th>
                                <th scope="col">Mueble</th>
                                <th scope="col">Motivo</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Fecha de registo</th>
                                <th scope="col">Cliente</th>
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
<script src="/js/garantias/init.js"></script>
@endsection
