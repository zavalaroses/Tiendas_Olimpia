<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
 <!-- Content here -->
    <ul class="nav nav-tabs" id="proveedoresTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="cuentasTab" data-bs-toggle="tab" data-bs-target="#cuentasTabs" type="button" role="tab" aria-controls="cuentasTabs" aria-selected="true">Cuentas Proveedores</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Catalogo Proveedores</button>
        </li>
    </ul>
    <br>

{{-- contenido de los tabs --}}
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="cuentasTabs" role="tabpanel" aria-labelledby="cuentasTab">
        {{-- contenido de las cuentas --}}
        <div class="row">
            <div class="col-md-10 d-flex">
                @if(Auth::user()->rol == 1)
                    <div class="col-md-4">
                        <select name="tiendas" id="tiendas" class="form-control"></select> 
                    </div>
                    <div class="col-md-6 d-flex">
                        <input type="date" id="inicio" name="inicio" class="form-control">
                        <input type="date" name="fin" id="fin" class="form-control">
                    </div>
                @endif
            </div>
        </div>
        <br>
        <div class="row">
            <div class="table-responsive">
                <table id="tbl_cuentas_proveedores" class="table table-borderless table-centered" style="width:100%">
                    <caption class="captionTbl">
                        <br>
                        <div class="row" style="align-items: center; justify-content:center;">
                            <div class="col-md-6 titleCenter">Cuentas Proveedores</div>
                        </div>
                    </caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Total de compras</th>
                            <th scope="col">Total pagado</th>
                            <th scope="col">Saldo a favor</th>
                            <th scope="col">Saldo final</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="row">
            <div class="col-md-10 d-flex">
            </div>
            <div class="col-md-2">
                <button id="btnAddProveedor" type="button" name="btnAddProveedor" class="btnNuevoUsuario">Agregar</button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="table-responsive">
                <table id="tbl_proveedores" class="table table-borderless table-centered" style="width: 100%;">
                    <caption class="captionTbl">
                        <br>
                        <div class="row" style="align-items: center; justify-content: center;">
                            <div class="col-md-6 titleCenter">Proveedores</div>
                        </div>
                    </caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Teléfono</th>
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





 {{-- parte para agregar las TABS --}}
</div>
<script src="/js/utilerias.js"></script>
<script src="/js/catalogos/proveedores/init.js"></script>
<script>
    $(document).ready(function () {
        dao.getDataProveedores();
    })
</script>
@include('catalogos.proveedores.modalAddProveedor')
@include('catalogos.proveedores.modalUpdateProveedor')
@include('catalogos.proveedores.modalVerProveedor')
@include('catalogos.proveedores.modalAddSaldo')
@endsection
