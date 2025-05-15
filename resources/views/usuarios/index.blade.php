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
             <button id="btnAddUser" type="button" name="btnAddUser" class="btnNuevoUsuario">Nuevo Usuario</button>
         </div>
     </div>
     <br>
    <div class="row">
        <div class="table-responsive">
            <table id="tbl_users" style="width: 100%;">
                <caption class="captionTbl">
                    <br>
                    <div class="row" style="align-items: center; justify-content: center;">
                        <div class="col-md-6 titleTUser1">USUARIOS</div>
                        <div class="col-md-6 titleTUser2">REGISTRADOS</div>
                    </div>
                </caption>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Fecha de ingreso</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Área</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection