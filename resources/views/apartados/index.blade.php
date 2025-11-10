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
                        <div class="col-md-6 titleCenter">APARTADOS</div>
                    </div>
                </caption>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Mueble</th>
                        <th scope="col">Cantidad</th>
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
@endsection
