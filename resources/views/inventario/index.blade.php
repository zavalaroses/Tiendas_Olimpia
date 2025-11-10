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
             <button id="btnAddInventario" type="button" name="btnAddInventario" class="btnNuevoUsuario">Nueva Entrada</button>
         </div>
     </div>
     <br>
    <div class="row">
        <div class="table-responsive">
            <table id="tbl_inventarios" style="width: 100%;" class="table table-borderless table-centered">
                <caption class="captionTbl">
                    <br>
                    <div class="row" style="align-items: center; justify-content: center;">
                        <div class="col-md-6 titleCenter">INVENTARIO</div>
                    </div>
                </caption>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tienda</th>
                        <th scope="col">Mueble</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Cantidad</th>
                        {{-- <th scope="col">Acciones</th> --}}
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="/js/utilerias.js"></script>
<script src="/js/inventario/init.js"></script>
@include('inventario.modalAddentrada')
@endsection
