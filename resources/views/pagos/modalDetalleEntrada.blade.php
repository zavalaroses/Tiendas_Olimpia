<div class="modal fade" id="modalDetalleEntrada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalle de Entrada de Inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">

                <!-- DATOS GENERALES -->
                <div class="card mb-3">
                    <div class="card-header fw-bold">Información General</div>
                    <div class="card-body row">
                        <div class="col-md-4"><strong>Tienda:</strong> <span id="d_tienda"></span></div>
                        <div class="col-md-4"><strong>Proveedor:</strong> <span id="d_proveedor"></span></div>
                        <div class="col-md-4"><strong>Usuario:</strong> <span id="d_usuario"></span></div>

                        <div class="col-md-4 mt-2"><strong>Fecha:</strong> <span id="d_fecha"></span></div>
                        <div class="col-md-4 mt-2"><strong>Código:</strong> <span id="d_codigo"></span></div>
                        <div class="col-md-4 mt-2"><strong>Estatus:</strong> <span id="d_estatus"></span></div>

                        <div class="col-md-4 mt-2"><strong>Total compra:</strong> $<span id="d_total"></span></div>
                        <div class="col-md-4 mt-2"><strong>Total pagado:</strong> $<span id="d_pagado"></span></div>
                    </div>
                </div>

                <!-- MUEBLES -->
                <div class="card mb-3">
                    <div class="card-header fw-bold">Muebles ingresados</div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Mueble</th>
                                    <th>Cantidad</th>
                                    <th>Precio compra</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="tblDetalleMuebles"></tbody>
                        </table>
                    </div>
                </div>

                <!-- PAGOS -->
                <div class="card">
                    <div class="card-header fw-bold">Historial de pagos</div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody id="tblDetallePagos"></tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="modal-footer justify-content-center">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
