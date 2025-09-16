<div class="modal fade" data-bs-backdrop='static' id="modalUpdateChofer" role="dialog" aria-labelledby="modalUpdateChofer" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalUpdateChofer','frm_update_chofer');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">editar conductor</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_update_chofer" name="frm_update_chofer">
                    <div class="row">
                        <input type="hidden" name="id" id="id_ed">
                        <div class="col-md-4">
                            <label for="nombre" >Nombre</label>
                            <input type="text" class="form-control" id="nombre_ed" name="nombre">
                        </div>
                        <div class="col-md-4">
                            <label for="apellido" >Apellidos</label>
                            <input type="text" class="form-control" id="apellidos_ed" name="apellidos">
                        </div>
                        <div class="col-md-4">
                            <label for="tienda" >Tienda</label>
                            <select name="tienda" id="tienda_ed" class="form-select">
                                <option value="">Seleccione una opcion</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="correo">Correo</label>
                            <input type="email" name="correo" id="correo_ed" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" name="telefono" id="telefono_ed" class="form-control">
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-12">
                            <label for="direccion">Dirección</label>
                            <input type="text" name="direccion" id="direccion_ed" class="form-control">
                        </div>
                    </div>
                    
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalUpdateChofer','frm_update_chofer');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_update_chofer">GUARDAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>