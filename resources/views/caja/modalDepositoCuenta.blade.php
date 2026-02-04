<div class="modal fade" data-bs-backdrop='static' id="modalDepositoCuenta" role="dialog" aria-labelledby="modalDepositoCuenta" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalDepositoCuenta','frm_depocito_cuenta','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 22px/25px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">Deposito a cuenta</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form  id="frm_depocito_cuenta" name="frm_depocito_cuenta">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <label class="form-label">Monto</label>
                            <input type="number" step="0.01" class="form-control" id="monto" name="monto">
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                        </div>
                    </div>
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalDepositoCuenta','frm_depocito_cuenta','');">CERRAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btnGuardarDeposito">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>