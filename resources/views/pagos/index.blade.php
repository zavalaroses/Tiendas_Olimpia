<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
 <!-- Content here -->
    <div class="row d-fles" style="text-align: center">
        <h2>Entradas de inventario</h2>
    </div>
     <div class="row">
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
     <br>
    <div class="row">
        <div class="table-responsive">
            <table id="tbl_lista_pagos" style="width: 100%;" class="table table-borderless table-centered">
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
                        <th scope="col">Trazabilidad</th>
                        <th scope="col">Total compra</th>
                        <th scope="col">Total pagado</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="/js/utilerias.js"></script>
<script src="/js/pagos/init.js"></script>
@include('pagos.modalPagarEntrada')
@include('pagos.modalDetalleEntrada')
@endsection
