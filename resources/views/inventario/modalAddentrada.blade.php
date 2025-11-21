<div class="modal fade" data-bs-backdrop='static' id="modalAddEntrada" role="dialog" aria-labelledby="modalAddEntradad" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalAddEntrada','frm_add_entrada','tbl_lista_entrada');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">NUEVA ENTRADA</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_add_entrada" name="frm_add_entrada">
                  <div class="col-md-6">
                    <label for="proveedor" class="form-label">Proveedor</label>
                    <select name="proveedor" id="proveedor" class="form-control"></select>
                  </div>
                  <div class="col-md-6">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" readonly>
                  </div>
                  <div class="col-md-6">
                    <label for="producto" class="form-label">Producto</label>
                    <select name="producto" id="producto" class="form-control"></select>
                  </div>
                  <div class="col-md-4">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" step="1">
                  </div>
                  <div class="col-md-2">
                    <label for="agregar_entrada" class="form-label">Agregar</label>
                    <button type="button" id="btnAddListEntrada" name="btnAddListEntrada" class="form-control btAdd" >
                      <i class="fa-solid fa-plus" ></i>
                    </button>
                  </div>
                  <div class="col-md-12">
                    <table id="tbl_lista_entrada" name= "tbl_lista_entrada" style="width: 100%; display:none">
                      <thead>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalAddEntrada','frm_add_entrada','tbl_lista_entrada');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_add_entrada">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function () {
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_ingreso').value = today;
  });
</script>