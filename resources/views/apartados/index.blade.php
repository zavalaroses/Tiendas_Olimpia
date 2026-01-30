<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
<br>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
    <div class="row d-fles" style="text-align: center">
        <h2>APARTADOS</h2>
    </div>
 <!-- Content here -->
     <div class="row">
         <div class="col-md-8">
            @if(Auth::user()->rol == 1)
               <div class="col-md-4">
                   <select name="tiendas" id="tiendas" class="form-control"></select> 
               </div>
           @endif
         </div>
         <div class="col-md-4">
            <button id="btnAddPedido" type="button" name="btnAddPedido" class="btnNuevoUsuario">+ Pedido</button>
            <button id="btnAddApartado" type="button" name="btnAddApartado" class="btnNuevoUsuario">Nuevo Apartado</button>
         </div>
     </div>
     <br>
    <div class="row">
        <div class="table-responsive">
            <table id="tbl_apartados" style="width: 100%;" class="table table-borderless table-centered">
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
<script src="/js/utilerias.js"></script>
<script src="/js/apartados/init.js"></script>
@include('apartados.modalAddApartados')
@include('apartados.modalPagarAdelanto')
@include('apartados.modalAddPedido')
@include('apartados.modalDetalleApartado')
@include('apartados.modalEditApartados')
@endsection
