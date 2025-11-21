<div class="modal fade" data-bs-backdrop='static' id="modalAddApartados" role="dialog" aria-labelledby="modalAddApartados" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalAddApartados','frm_add_apartado','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">Nuevo Apartado</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_add_apartado" name="frm_add_apartado">
                    <div class="row">
                      <div class="col-md-8"></div>
                      <div class="col-md-4">
                        <input type="text" id="fecha" name="fecha" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cliente" for="nombre">Nombre(s)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="col-md-4">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos">
                        </div>
                        <div class="col-md-4">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="mini-br"></div>
                        <div class="col-md-12">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>
                        <div class="mini-br"></div>
                        <div class="col-md-4">
                            <label for="anticipo">Anticipo</label>
                            <input type="number" id="anticipo" name="anticipo" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="total">Total</label>
                            <input type="text" id="total" name="total" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha">Forma de pago</label>
                            <select name="forma_pago" id="forma_pago" class="form-control">
                              <option value=1>Efectivo</option>
                              <option value=2>Tarjeta</option>
                              <option value=3>Transferencia</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                    <div class="mini-br"></div>
                      <div class="col-md-6">
                        <label for="nombre" for="mueble">Mueble</label>
                        <select name="mueble" id="mueble" class="form-select">
                          <option value="">Seleccione una opcion</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label for="cantidad" for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad">
                      </div>
                      <div class="col-md-2">
                        <label for="precioUnit" class="form-label">Precio unitaro</label>
                        <p id="precioUnit"></p>
                        <input type="hidden" name="inpPrecioUnit" id="inpPrecioUnit">
                      </div>
                      
                      <div class="col-md-2">
                        <label for="tienda" for="Agregar">Agregar</label>
                        <button type="button" id="btn_add_garantia" name="btn_add_garantia" class="form-control btAdd" >
                          <i class="fa-solid fa-plus" ></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-md-12">
                    <table id="tbl_add_list_apartados" name= "tbl_add_list_apartados" style="width: 100%; display:none">
                      <thead>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>total</th>
                        <th>Acciones</th>
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
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalAddApartados','frm_add_apartado','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_add_apartado">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>