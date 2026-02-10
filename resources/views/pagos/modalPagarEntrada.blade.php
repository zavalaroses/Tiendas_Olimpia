<div class="modal fade" data-bs-backdrop='static' id="modalPagarEntrada" role="dialog" aria-labelledby="modalPagarEntrada" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalPagarEntrada','frm_pagar_entrada','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">Pagar mercancia</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
              
                <form class="row mb-5" id="frm_pagar_entrada" name="frm_pagar_entrada">
                    <input type="hidden" id="entrada_id" name="entrada_id">
                    <div class="row">
                      
                      <div class="col-md-4">
                        <label>Total compra</label>
                        <input type="text" id="total_compra" name="total_compra" class="form-control" readonly>
                      </div>

                      <div class="col-md-4">
                        <label>Total pagado</label>
                        <input type="text" id="total_pagado" name="total_pagado" class="form-control" readonly>
                      </div>

                      <div class="col-md-4">
                        <label>Saldo</label>
                        <input type="text" id="saldo" name="saldo" class="form-control text-danger fw-bold" readonly>
                      </div>
                      
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label>Monto a pagar</label>
                        <input type="number" id="monto" name="monto" class="form-control" step="0.01" min="1">
                      </div>

                      @if(Auth::user()->rol == 1)
                        <div class="col-md-6">
                          <label>Tipo de pago</label>
                          <select id="tipo_pago" class="form-select" name="tipo_pago">
                              <option value="efectivo">Efectivo</option>
                              <option value="transferencia">Transferencia</option>
                          </select>
                        </div>

                      @else
                        <div class="col-md-6">
                          <label>Tipo de pago</label>
                          <select id="tipo_pago" name="tipo_pago" class="form-select">
                              <option value="efectivo">Efectivo</option>
                          </select>
                        </div>
                        
                      @endif
                      
                    </div>

                    <br>
                    <div class="row">
                      <div class="col-md-12">
                        <label>Observación</label>
                        <textarea id="observacion" name="observacion" class="form-control"></textarea>
                      </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalPagarEntrada','frm_pagar_entrada','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_pagar_entrada">GUARDAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>

