@extends('layouts.app')
@section('content')
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<div class="container">
 <!-- Content here -->
    {{-- Tabs para usuarios y comisiones --}}
    <ul class="nav nav-pills custom-tabs mb4" id="usuariosTab" role="tablist" style="padding-top: 1em">
        <li class="nav-item me-2" role="presentation">
            <button class="nav-link active" 
                id="comisionesTabButton" 
                data-bs-toggle="tab" 
                data-bs-target="#comisionesTab" 
                type="button" 
                role="tab">
                <i class="fa-solid fa-money-bill-trend-up me-2"></i>
                Comisiones
            </button>
        </li>
        <li class="nav-item me-2" role="presentation">
            <button
                class="nav-link"
                id="historialTabButton"
                data-bs-toggle="tab"
                data-bs-target="#historialTab"
                type="button"
                role="tab"
            >
                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                Historial
            </button>
        </li>
        <li class="nav-item me-2" role="presentation">
            <button class="nav-link" 
                id="profile-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#profile" 
                type="button" 
            >
                <i class="fa-solid fa-users me-2"></i>
                Usuarios
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabsContent">
        {{-- Contenido del tab de comisiones --}}
        <br>
        <div class="tab-pane fade show active" id="comisionesTab" role="tabpanel">
            {{-- Header premium --}}
            <div class="commission-header-card mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-icon">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <div>
                                <h2 class="commission-title mb-1">
                                    Comisiones Pendientes de pago
                                </h2>
                                <p class="text-muted mb-0">
                                    Resumen de ventas entregadas y
                                    comisiones generadas.
                                </p>
                                <small class="text-secondary">
                                    Semana:
                                    <span id="lblSemanaComision">
                                        Cargando...
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        {{-- <button class="btn btn-dark rounded-pill px-4">
                            <i class="fa-solid fa-file-pdf me-2"></i>
                            Exportar
                        </button> --}}
                    </div>
                </div>
            </div>
            {{-- KPIS --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="summary-card">
                        <div class="summary-icon bg-primary-soft">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>

                        <div>
                            <small>Comisión total</small>
                            <h3 id="totalComisionSemana">
                                $0.00
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="summary-card">
                        <div class="summary-icon bg-success-soft">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <small>Vendedores activos</small>
                            <h3 id="vendedorasActivas">
                                0
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="summary-card">
                        <div class="summary-icon bg-warning-soft">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <small>Total vendido</small>
                            <h3 id="totalVendido">
                                $0.00
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="summary-card">
                        <div class="summary-icon bg-danger-soft">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <small>Pendiente pago</small>
                            <h3 id="pendientePago">
                                $0.00
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Tabla --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl_comisiones" class="table table-borderless table-centered" style="width: 100%">
                            {{-- <caption class="captionTbl"></caption> --}}
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Tienda</th>
                                    <th>Entregas</th>
                                    <th>Total vendido</th>
                                    <th>Comisión</th>
                                    <th>Estatus</th>
                                    <th width="180">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- contenido del tab para historial de comisiones --}}
        <div class="tab-pane fade"
            id="historialTab"
            role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i>
                        Historial de comisiones
                    </h4>
                    <div class="alert alert-light border rounded-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label>Fecha inicio</label>
                                <input
                                    type="date"
                                    id="hc_fecha_inicio"
                                    class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Fecha fin</label>
                                <input
                                    type="date"
                                    id="hc_fecha_fin"
                                    class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Usuario</label>
                                <select
                                    id="hc_usuario"
                                    class="form-select">

                                    <option value="">
                                        Todos
                                    </option>

                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-dark w-100" onclick="dao.getHistorialComisiones()">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                                    Buscar
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless align-middle" id="tbl_historial_comisiones">
                                <caption class="captionTbl"></caption>
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha pago</th>
                                        <th>Usuario</th>
                                        <th>Tienda</th>
                                        <th>Entregas</th>
                                        <th>Total vendido</th>
                                        <th>Comisión</th>
                                        <th>Pagado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Contenido del tab de usuarios --}}
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
                <div class="card shadow-sm border-0 rounded-4">
                    <br>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button id="btnAddUser" type="button" name="btnAddUser" class="btnNuevoUsuario">Nuevo Usuario</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl_users" style="width: 100%;" class="table table-borderless table-centered">
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
                                        <th scope="col">Tienda</th>
                                        <th scope="col">Rol</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Fecha de ingreso</th>
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
    </div>

    {{-- fin de tabs --}}
     
</div>
@include('usuarios.modalAddUsuario')
@include('usuarios.modalVerComisiones')
<script src="/js/utilerias.js"></script>
<script src="/js/usuarios/init.js"></script>
<script>
    $(document).ready(function () {
       dao.getData(); 
       dao.getDataComisionesActivas();
       dao.getResumenComisiones();
       dao.getHistorialComisiones();
    });
</script>
@endsection
