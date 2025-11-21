<div class="modal fade" data-bs-backdrop='static' id="modalUpdateProveedor" role="dialog" aria-labelledby="modalUpdateProveedor" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalUpdateProveedor','frm_update_proveedor','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">editar proveedor</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_update_proveedor" name="frm_update_proveedor">
                    <div class="row">
                        <input type="hidden" name="id" id="id_ed">
                        <div class="col-md-4">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre_ed" name="nombre">
                        </div>
                        <div class="col-md-4">
                            <label for="contacto" >Correo</label>
                            <input type="text" name="contacto" id="contacto_ed" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" name="telefono" id="telefono_ed" class="form-control">
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalUpdateProveedor','frm_update_proveedor','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_update_proveedor">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>