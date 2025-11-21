<div class="modal fade" data-bs-backdrop='static' id="modalAddUser" role="dialog" aria-labelledby="modalEditarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalAddUser','','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">NUEVO USUARIO</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_add_user" name="frm_add_user">
                    <div class="row">
                      <div class="col-md-4">
                        <label for="nombre" class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" id="name" name="name">
                      </div>
                      <div class="col-md-4">
                        <label for="apellidos" class="form-label">Apellido(s)</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos">
                      </div>
                      <div class="col-md-4">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                      </div>
                      <div class="col-md-4">
                        <label for="tienda" class="form-label">Tienda</label>
                        <select id="tienda" class="form-select" name="tienda">
                          <option selected>Elige...</option>
                          <option>Queretaro</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" name="email" id="email" class="form-control">
                      </div>
                      <div class="col-md-4">
                        <label for="rool" class="form-label">Puesto</label>
                        <select id="rol" class="form-select" name="rol">
                          <option selected>Elige...</option>
                          <option>Queretaro</option>
                        </select>
                      </div>
                    </div>
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalAddUser','','');">CANCELAR</button>
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