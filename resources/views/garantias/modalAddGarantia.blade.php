<div class="modal fade" data-bs-backdrop='static' id="modalAddGarantia" role="dialog" aria-labelledby="modalAddGarantia" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalAddGarantia','frm_add_garantia','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">nueva garantia</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_add_garantia" name="frm_add_garantia">
                  <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                      <input type="hidden" name="id_mueble" id="id_mueble_g">
                      <input type="hidden" name="tienda" id="tienda_g">
                      <input type="hidden" name="id_salida" id="id_salida_g">
                      <label for="mueble" class="form-label">Mueble</label>
                      <input type="text" class="form-control" id="mueble_g" name="mueble" disabled>
                    </div>
                    <div class="col-md-2">
                      <label for="cantidad" class="form-label">Cantidad</label>
                      <input type="number" class="form-control" id="cantidad_g" name="cantidad">
                    </div>
                    <div class="col-md-2"></div>

                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <label for="descripcion">Motivo</label>
                      <textarea name="descripcion" id="descripcion_g" cols="5" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-2"></div>
                    
                  </div>
                  
                </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalAddGarantia','frm_add_garantia','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_ad_garantia">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>
