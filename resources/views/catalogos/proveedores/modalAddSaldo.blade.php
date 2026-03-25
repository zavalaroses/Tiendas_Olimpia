<div class="modal fade" data-bs-backdrop='static' id="modalAddSaldo" role="dialog" aria-labelledby="modalAddSaldo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalAddSaldo','frm_add_saldo','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">Agregar saldo a favor</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_add_saldo" name="frm_add_saldo">
                    <div class="row">
                        
                         <input type="hidden" name="proveedor_id" id="proveedor_id">

                        <div class="mb-3">
                            <label>Monto</label>
                            <input type="number" step="0.01" name="monto" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Método de pago</label>
                            <select name="metodo_pago" class="form-control">
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Observación</label>
                            <textarea name="descripcion" class="form-control"></textarea>
                        </div>
                    </div>
                    
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalAddSaldo','frm_add_saldo','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_add_saldo">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>