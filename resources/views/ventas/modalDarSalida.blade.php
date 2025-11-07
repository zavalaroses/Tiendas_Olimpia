<div class="modal fade" data-bs-backdrop='static' id="modalDarSalida" role="dialog" aria-labelledby="modalDarSalida" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalDarSalida','frm_dar_salida');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">agendar salida</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4"><p><strong>Titular: </strong></p><p id="titular"></p></div>
                <div class="col-md-4"><p><strong>Contacto: </strong></p><p id="telefono"></p></div>
                <div class="col-md-2"></div>
              </div>
              <br>
                <form class="row g-3" id="frm_dar_salida" name="frm_dar_salida">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <label for="chofer" >Chofer</label>
                            <select name="chofer" id="chofer" class="form-select">
                                <option value="">Seleccione una opcion</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha">Fecha de salida</label>
                            <input type="date" name="fechaSalida" id="fechaSalida" class="form-control">
                        </div>
                        <div class="col-md-2"></div>
                  </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalDarSalida','frm_dar_salida');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_agendar_salida">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function () {
    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, '0');
    const day = String(hoy.getDate()).padStart(2,'0');
    const fechaMin = `${year}-${month}-${day}`; // formato yyyy-MM-dd
    document.getElementById('fechaSalida').setAttribute('min', fechaMin);
  });
  
</script>