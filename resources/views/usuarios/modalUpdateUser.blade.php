<div class="modal fade" data-bs-backdrop='static' id="modalUpdateUser" role="dialog" aria-labelledby="modalEditarUser" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalUpdateUser','frm_upd_user','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">MODIFICAR USUARIO</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_upd_user" name="frm_upd_user">
                    <div class="row">
                      <div class="col-md-4">
                        <input type="hidden" name="id" id="id_upd">
                        <label for="name_ed" class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" id="name_ed" name="name">
                      </div>
                      <div class="col-md-4">
                        <label for="apellidos_ed" class="form-label">Apellido(s)</label>
                        <input type="text" class="form-control" id="apellidos_ed" name="apellidos">
                      </div>
                      <div class="col-md-4">
                        <label for="tienda" class="form-label">Tienda</label>
                        <select id="tienda_ed" class="form-select" name="tienda" disabled>
                        </select>
                      </div>
                      <div class="col-md-4">
                          <label for="email_ed" class="form-label">Email</label>
                          <input type="email" name="email" id="email_ed" class="form-control">
                      </div>
                      <div class="col-md-4">
                        <label for="rol_ed" class="form-label">Puesto</label>
                        <select id="rol_ed" class="form-select" name="rol" disabled>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label for="comision_ed" class="form-label">% Comisión</label>
                        <input type="number" id="comision_ed" name="comision" min="1" class="form-control">
                      </div>
                    </div>
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalUpdateUser','frm_upd_user','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_update_user">GUARDAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>