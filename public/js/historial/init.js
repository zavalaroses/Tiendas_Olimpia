var dao = {
    getData: function () {
        let tienda = document.getElementById('filtro_tienda') ? document.getElementById('filtro_tienda').value : '';
        let inicio = document.getElementById('filtro_inicio') ? document.getElementById('filtro_inicio').value : '';
        let fin = document.getElementById('filtro_fin') ? document.getElementById('filtro_fin').value : '';
        
        $.ajax({
            url:'/get-data-historial-cajas',
            data:{
                tienda: tienda,
                inicio:inicio,
                fin:fin,
            },
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tabla_cortes');
            const columns = [
                {"targets":[0],"mData":'id'},
                {"targets":[1],"mData":'tienda'},
                {"targets":[2],"mData":'usuario'},
                {"targets":[3],"mData":'total_efectivo'},
                {"targets":[4],"mData":'total_cuenta'},
                {"targets":[5],"mData":'total_general'},
                {"targets":[6],"mData":'efectivo_contado'},
                {"aTargets": [7], "mData" : 'diferencia'},
                {"aTargets": [8], "mData" : 'egresos'},
                {"aTargets": [9], "mData" : function (o) {
                    return o.fecha;
                }},
                {"aTargets": [10], "mData" : function (o) {
                  return `<button class="btn btn-sm btnAgregar" onClick="dao.detallesCorte(${o.id})">
                        <i class="fa fa-eye"></i>
                    </button>`
                }},

            ];
            _gen.setTableScrollEspecial2(table,columns,response);
            
        });
    },
    getCatTiendas: function (field,id) {
        $.ajax({
            url:'/get-catalogo-tiendas',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            var select = $('#'+field);
            select.html('');
            select.append(new Option('Todas las tiendas',''));
            response.map(function (val,i) {
                if (id !='' && id == val.id) {
                    select.append(new Option(response[i].nombre,response[i].id, true, true));
                }else{
                    select.append(new Option(response[i].nombre,response[i].id, false,false));
                }
            });
        })
    },
    detallesCorte: function (id) {
        $.ajax({
            url:'/get-detalles-corte/'+id,
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (res) {
            // ---- Datos del corte ----
            let resumen = `
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Corte:</strong> ${res.corte.id}</div>
                    <div class="col-md-3"><strong>Tienda:</strong> ${res.corte.tienda}</div>
                    <div class="col-md-3"><strong>Usuario:</strong> ${res.corte.usuario}</div>
                    <div class="col-md-3"><strong>Fecha:</strong> ${res.corte.fecha}</div>
                </div>

                <div class="row">
                    <div class="col-md-3"><strong>Total General:</strong> $${res.corte.total_general}</div>
                    <div class="col-md-3"><strong>Efectivo Esperado:</strong> $${res.corte.total_efectivo}</div>
                    <div class="col-md-3"><strong>Efectivo Contado:</strong> $${res.corte.efectivo_contado}</div>
                    <div class="col-md-3"><strong>Diferencia:</strong> 
                        <span class="${res.corte.diferencia >= 0 ? 'text-success' : 'text-danger'}">
                            $${res.corte.diferencia}
                        </span>
                    </div>
                </div>
                <hr/>
            `;
            // ---- Detalle de transacciones ----
            let filas = "";

            if (res.transacciones.length === 0) {
                filas = `<tr><td colspan="6">No hay transacciones</td></tr>`;
            } else {
                res.transacciones.forEach(t => {
                    filas += `
                    <tr>
                        <td>${t.id}</td>
                        <td>${t.tipo}</td>
                        <td>$${t.monto}</td>
                        <td>${t.pago}</td>
                        <td>${t.fecha}</td>
                        <td>${t.usuario}</td>
                    </tr>`;
                });
            }

            $('#detalle_corte_body').html(filas);

            $('#info_corte').html(resumen);
            const modalDetalleCorte = new bootstrap.Modal(document.getElementById('modalDetalleCorte'));
            modalDetalleCorte.show();
        })
        
    }

};
var init = {

};
$(document).ready(function () {
    dao.getData();
    dao.getCatTiendas('filtro_tienda','');
    $('#filtro_tienda').on('change',function (e) {
        e.preventDefault();
        dao.getData();
    })
});