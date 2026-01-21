<div class="modal fade" id="modalDetalleCorte" tabindex="-1" aria-labelledby="detalleCorteLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="detalleCorteLabel">Detalle del Corte</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">

        <!-- Datos principales -->
        <div id="info_corte" class="mb-3"></div>

        <h6 class="fw-bold">Transacciones</h6>

        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tipo</th>
              <th>Monto</th>
              <th>Pago</th>
              <th>Cliente</th>
              <th>Fecha</th>
              <th>Usuario</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="detalle_corte_body">
          </tbody>
        </table>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>
