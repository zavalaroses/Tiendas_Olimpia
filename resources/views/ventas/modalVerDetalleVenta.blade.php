<div class="modal fade" id="modalVerDetalleVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Detalle de la venta <span id="da_id_nota" class="text-muted"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- ================= DATOS GENERALES ================= -->
                <h6 class="text-muted mb-2">Información general</h6>
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <label class="fw-bold">Cliente</label>
                        <div id="da_cliente">-</div>
                    </div>

                    <div class="col-md-2">
                        <label class="fw-bold">Tienda</label>
                        <div id="da_tienda">-</div>
                    </div>

                    <div class="col-md-3">
                        <label class="fw-bold">Usuario</label>
                        <div id="da_usuario">-</div>
                    </div>

                    <div class="col-md-3">
                        <label class="fw-bold">Fecha de entrega</label>
                        <div id="da_fecha">-</div>
                    </div>

                    {{-- segundo nivel --}}
                    <div class="col-md-2">
                        <label class="fw-bold">Estatus</label>
                        <div id="da_estatus">-</div>
                    </div>

                    <div class="col-md-2">
                        <label class="fw-bold">Total</label>
                        <div id="da_total">-</div>
                    </div>

                    <div class="col-md-2">
                        <label class="fw-bold">Costo envío</label>
                        <div id="da_envio">-</div>
                    </div>

                    <div class="col-md-3">
                        <label class="fw-bold">Liquidado el</label>
                        <div id="da_liquidado_at">-</div>
                    </div>

                    <div class="col-md-3">
                        <label class="fw-bold">Chofer asignado</label>
                        <div id="da_chofer">-</div>
                    </div>

                    

                    

                </div>

                <hr>

                <!-- ================= PRODUCTOS ================= -->
                <h6 class="text-muted mb-2">Productos</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Mueble</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="da_productos">
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Sin productos
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>

                <!-- ================= PAGOS ================= -->
                <h6 class="text-muted mb-2">Historial de pagos</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Monto</th>
                                <th>Tipo pago</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="da_pagos">
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Sin pagos registrados
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
