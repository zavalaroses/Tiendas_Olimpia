<div class="modal fade" id="modalVerComisiones" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title mb-1">
                        Detalle de comisión
                    </h5>

                    <small class="text-muted">
                        <span id="dc_usuario">
                            -
                        </span>
                    </small>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                {{-- Resumen --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">

                                <small class="text-muted">
                                    Usuario
                                </small>

                                <div class="fw-bold" id="dc_usuario_resumen">
                                    -
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">

                                <small class="text-muted">
                                    Tienda
                                </small>

                                <div class="fw-bold" id="dc_tienda">
                                    -
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">

                                <small class="text-muted">
                                    Entregas
                                </small>

                                <div class="fw-bold" id="dc_entregas">
                                    0
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 bg-success-subtle h-100">
                            <div class="card-body">

                                <small class="text-muted">
                                    Comisión total
                                </small>

                                <div class="fw-bold text-success fs-5" id="dc_total_comision">
                                    $0.00
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <hr>

                {{-- Tabla detalle --}}
                <h6 class="text-muted mb-3">
                    Ventas incluidas en la comisión
                </h6>

                <div class="table-responsive">

                    <table class="table table-sm table-bordered align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Fecha entrega</th>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Total venta</th>
                                <th>Comisión</th>
                                {{-- <th width="120">
                                    Acción
                                </th> --}}
                            </tr>

                        </thead>

                        <tbody id="dc_detalle">

                            <tr>
                                <td colspan="6" class="text-center text-muted">

                                    Sin información

                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="modal-footer justify-content-between">

                <div>

                    <span class="fw-bold">
                        Total comisión:
                    </span>

                    <span class="text-success fw-bold fs-5" id="dc_footer_total">

                        $0.00

                    </span>

                </div>

                <button class="btn btn-secondary" data-bs-dismiss="modal">

                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>
