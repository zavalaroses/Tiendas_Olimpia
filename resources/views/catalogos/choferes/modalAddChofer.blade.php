<div class="modal fade" data-bs-backdrop='static' id="modalAddChofer" role="dialog" aria-labelledby="modalAddChofer" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalAddChofer','frm_add_chofer');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">agregar conductor</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_add_chofer" name="frm_add_chofer">
                    <div class="row">
                        
                        <div class="col-md-4">
                            <label for="nombre" >Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="col-md-4">
                            <label for="apellido" >Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos">
                        </div>
                        <div class="col-md-4">
                            <label for="tienda" >Tienda</label>
                            <select name="tienda" id="tienda" class="form-select">
                                <option value="">Seleccione una opcion</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="correo">Correo</label>
                            <input type="email" name="correo" id="correo" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" name="telefono" id="telefono" class="form-control">
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-12">
                            <label for="direccion">Dirección</label>
                            <input type="text" name="direccion" id="direccion" class="form-control">
                        </div>
                    </div>
                    
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalAddChofer','frm_add_chofer');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_add_user">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>