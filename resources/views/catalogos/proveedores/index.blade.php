<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
 <!-- Content here -->
     <div class="row">
         <div class="col-md-10"></div>
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
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
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
@endsection
