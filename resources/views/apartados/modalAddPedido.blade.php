<div class="modal fade" data-bs-backdrop='static' id="modalAddPedido" role="dialog" aria-labelledby="modalAddPedido" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header captionModal">
              <div class="row" style="width: 100%">
                <div class="col-md-12" style="display: flex; justify-content:right; margin-top:0%; margin-bottom:0%;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalAddPedido','frm_add_pedido','');">
                    <h5><span aria-hidden="true">&times;</span></h5>
                  </button>
                </div>
                <div class="col-md-12" style="display: flex; justify-content:center; margin-top:0px;">
                  <div class="col-md-6 " style="font: normal normal normal 30px/41px Galano Grotesque;
                    letter-spacing: 0px;
                    color: #000;
                    text-transform: uppercase; text-align:center;">Nuevo Pedido</div>
                </div>
              </div>
            </div>
            <br>
            <div class="modal-body">
                <form class="row g-3" id="frm_add_pedido" name="frm_add_pedido">
                  <fieldset>
                    <legend>Cliente</legend>
                    <div class="row">
                      <div class="col-md-4">
                          <label for="cliente" for="nombre">Nombre(s)</label>
                          <input type="text" class="form-control" id="nombre_pedido" name="nombre">
                      </div>
                      <div class="col-md-4">
                          <label for="apellidos">Apellidos</label>
                          <input type="text" class="form-control" id="apellidos_pedido" name="apellidos">
                      </div>
                      <div class="col-md-4">
                          <label for="telefono">Teléfono</label>
                          <input type="text" class="form-control" id="telefono_pedido" name="telefono">
                      </div>
                      <div class="mini-br"></div>
                      <div class="col-md-12">
                          <label for="direccion">Dirección</label>
                          <input type="text" class="form-control" id="direccion_pedido" name="direccion">
                      </div>
                      <div class="mini-br"></div>
                  </div>
                  </fieldset>
                  <fieldset>
                    <legend>Mueble</legend>
                    <div class="row">
                      <div class="col-md-8">
                          <label for="nombre" >Nombre</label>
                          <input type="text" class="form-control" id="nombre_mueble" name="mueble">
                      </div>
                      <div class="col-md-4">
                          <label for="precio">Precio venta</label>
                          <input type="number" name="precio" id="precio_mueble" class="form-control"  min="0">
                      </div>
                      <div class="col-md-12">
                          <label for="descripcion">Descripción</label>
                          <input type="text" name="descripcion" id="descripcion_mueble" class="form-control">
                      </div>
                    </div>
                  </fieldset>
                
                  <div class="row">
                    <div class="col-md-3">
                        <label for="total">Costo de envio</label>
                        <input type="number" id="envio_pedido" name="envio" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="total">Cantidad</label>
                        <input type="number" id="cantidad_mueble" name="cantidad" class="form-control" min="1" value="1" >
                    </div>
                    <div class="col-md-3">
                        <label for="anticipo">Anticipo</label>
                        <input type="number" id="anticipo_pedido" name="anticipo" class="form-control">
                    </div>
                    <div class="col-md-3">
                      <label for="total">Total</label>
                      <input type="text" id="total_pedido" name="total" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha">Forma de pago</label>
                        <select name="forma_pago" id="forma_pago_pedido" class="form-control">
                          <option value=1>Efectivo</option>
                          <option value=2>Tarjeta</option>
                          <option value=3>Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <label for=""> </label>
                      <input type="text" id="fecha_pedido" name="fecha" class="form-control" readonly>
                    </div>
                  </div>                    
                </form>
            </div>
            <div class="modal-footer" style="display: flex; justify-content:center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="col-md-5">
                        <button type="reset" class="form-control btnCancel" onclick="closeModal('modalAddPedido','frm_add_pedido','');">CANCELAR</button>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-5">
                        <button type="button" class="form-control btnAgregar" id="btn_add_pedido_apartado">AGREGAR</button>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>