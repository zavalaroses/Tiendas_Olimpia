<div class="modal fade" id="modalVerProveedor" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header headerModalCustom text-dark">
                <h5 class="modal-title">Estado de Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- 🟢 HEADER -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 id="prov_nombre"></h4>
                        <small id="prov_info"></small>
                    </div>

                    <div class="col-md-6 text-end">
                        <div>
                            <strong>Contacto:</strong> <span id="contact"></span><br>
                            <strong>Telefono:</strong> <span id="tel"></span><br>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <small>Saldo inicial</small>
                                <h6 id="saldo_inicial"></h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <small>Total cargos</small>
                                <h6 id="total_cargos" class="text-danger"></h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <small>Total abonos</small>
                                <h6 id="total_abonos" class="text-success"></h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <small>Saldo final</small>
                                <h6 id="saldo_final" class="fw-bold"></h6>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- 🟣 TABLA -->
                <table class="table table-bordered table-sm">
                    <thead style="background-color: #eac1c1; color:black">
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Cargo</th>
                            <th>Abono</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="tablaEstadoCuenta"></tbody>
                </table>

            </div>
        </div>
    </div>
</div>