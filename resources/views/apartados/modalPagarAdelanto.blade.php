<div class="modal fade" data-bs-backdrop='static' id="modalPagarAdelanto" role="dialog" aria-labelledby="modalPagarAdelanto" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalPagarAdelanto','frm_pagar_adelanto');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">Generar adelanto</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_pagar_adelanto" name="frm_pagar_adelanto">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <input type="hidden" name="id_apartado" id="id_apartado">
                            <label for="restante" for="restante">Restante</label>
                            <input type="text" class="form-control" id="restante" name="restante" disabled>
                        </div>
                        <div class="mini-br"></div>
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <label for="adelanto">Adelanto</label>
                            <input type="number" id="adelanto" name="adelanto" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-7">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalPagarAdelanto','frm_pagar_adelanto');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_add_pago">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>