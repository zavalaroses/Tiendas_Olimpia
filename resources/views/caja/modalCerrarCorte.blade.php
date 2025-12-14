<div class="modal fade" data-bs-backdrop='static' id="modalCerrarCorte" role="dialog" aria-labelledby="modalCerrarCorte" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalCerrarCorte','frm_cierre_corte','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">Corte de caja</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
              <!-- Resumen del corte -->
              <div class="summary-box p-3 rounded mb-3" style="background:#f6f6f6;">
                <h6 class="fw-bold mb-3">Resumen del Corte</h6>
      
                <div class="summary-row d-flex justify-content-between mb-2">
                  <span>Efectivo de apertura:</span>
                  <span id="corte_apertura">$ 0.00</span>
                </div>
      
                <div class="summary-row d-flex justify-content-between mb-2">
                  <span>Ingresos en efectivo:</span>
                  <span id="corte_ingresos_efectivo">$ 0.00</span>
                </div>
      
                <div class="summary-row d-flex justify-content-between mb-2">
                  <span>Ingresos con tarjeta:</span>
                  <span id="corte_ingresos_tarjeta">$ 0.00</span>
                </div>
      
                <div class="summary-row d-flex justify-content-between mb-2">
                  <span>Salidas de dinero:</span>
                  <span id="corte_salidas">$ 0.00</span>
                </div>
      
                <div class="summary-row d-flex justify-content-between mb-2 fw-bold border-top pt-2">
                  <span>Efectivo esperado:</span>
                  <span id="corte_efectivo_esperado">$ 0.00</span>
                </div>
              </div>
              
              <form id="frm_cierre_corte">
                <!-- Efectivo contado -->
                <div class="mb-3">
                  <label for="efectivo_contado" class="form-label fw-bold">Efectivo en caja:</label>
                  <input type="number" min="0" class="form-control" id="efectivo_contado" name="efectivo_contado" placeholder="Ingresa el efectivo contado">
                </div>
        
                <!-- Observaciones -->
                <div class="mb-3">
                  <label for="observaciones_corte" class="form-label">Observaciones (opcional):</label>
                  <textarea id="observaciones_corte" class="form-control" rows="3" placeholder="Anota comentarios si hay diferencias"></textarea>
                </div>
        
                <!-- Diferencia -->
                <div class="summary-row d-flex justify-content-between mt-3 fw-bold">
                  <span>Diferencia:</span>
                  <input type="hidden" id="input_total" name="input_total">
                  <input type="hidden" id="diferencia" name="diferencia">
                  <span id="corte_diferencia" class="text-danger">$ 0.00</span>
                </div>
              </form>
                
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalCerrarCorte','frm_cierre_corte','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_cerrar_corte">CERRAR CORTE</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>