let dao = {
    getData: function () {
        const tienda = document.getElementById('tiendas');
        let idTienda = tienda ? tienda.value : '';
        $.ajax({
            type:'get',
            url:'/get-data-pagos/'+idTienda,
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tbl_lista_pagos');
            const columns = [
                {"targets": [0],"mData":'id'},
                {"targets": [1],"mData":'tienda'},
                {"targets": [2],"mData":'codigo_trazabilidad'},
                {"targets": [3],"mData":'total_compra'},
                {"targets": [4],"mData":'total_pagado'},
                {"targets": [5],"mData":function (o) {
                    if (o.estatus_pago == 'pendiente') {
                        return `<span class="yellow" style="font-size:0.875rem !important; max-width: 100px;">${o.estatus_pago}</span>`;
                    }else if (o.estatus_pago == 'pagado') {
                        return `<span class="green" style="font-size:0.875rem !important; max-width: 100px;">${o.estatus_pago}</span>`;
                    } else {
                        return `<span class="blue" style="font-size:0.875rem !important; max-width: 100px;">${o.estatus_pago}</span>`;
                    }
                }},
                {"targets": [6],"mData":'usuario'},
                {"targets": [7],"mData":function (o) {
                    if (o.estatus_pago != 'pagado') {
                        return `
                            <button class="dropdown-item" onclick="dao.moadalPago(${o.id},${o.total_pagado},${o.total_compra})">
                                <i class="fa-solid fa-comment-dollar" style="color:#7C0A20"></i>&nbsp;Pagar
                            </button>
                            <button class="dropdown-item" onclick="dao.modalDetallesPagos(${o.id})">
                                <i class="fa fa-eye" style="color: #D48D8D"></i>&nbsp;Detalles
                            </button>
                        `;
                    }else{
                        return `
                            <button class="dropdown-item" onclick="dao.modalDetallesPagos(${o.id})">
                                <i class="fa fa-eye" style="color: #D48D8D"></i>&nbsp;Detalles
                            </button>
                        `;
                    }
                    
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response)

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
            select.append(new Option('Selecciona una tienda',''));
            response.map(function (val,i) {
                if (id !='' && id == val.id) {
                    select.append(new Option(response[i].nombre,response[i].id, true, true));
                }else{
                    select.append(new Option(response[i].nombre,response[i].id, false,false));
                }
            });
        })
    },
    moadalPago: function (id,total_pagado,total_compra) {
        let total = parseFloat(total_compra);
        let pagado = parseFloat(total_pagado);
        let saldo = total - pagado;

        $('#entrada_id').val(id);
        $('#total_compra').val(total.toFixed(2));
        $('#total_pagado').val(pagado.toFixed(2));
        $('#saldo').val(saldo.toFixed(2));
        $('#monto').val('');
        $('#observacion').val('');
        const modalPagarEntrada = new bootstrap.Modal(document.getElementById('modalPagarEntrada'));
        modalPagarEntrada.show(); 
    },
    postAddPagoEntrada: function () {
        Swal.fire({
            title: '¿Confirmar pago?',
            text: 'Esta acción no se puede deshacer',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, pagar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#frm_pagar_entrada')[0];
                var data = new FormData(form);
                const tienda = document.getElementById('tiendas');
                if (tienda) {
                    data.append("id_tienda", tienda.value);
                }
                $.ajax({
                    url:'/post-pagar-mercancia',
                    type:'post',
                    data:data,
                    enctype:"multipart/form-data",
                    processData:false,
                    contentType:false,
                    cache:false,
                    headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
                }).done(function (response) {
                    Swal.fire({
                        icon:response.icon,
                        title:response.title,
                        text:response.text,
                    });
                    if (response.icon == 'success') {
                        closeModal('modalPagarEntrada','frm_pagar_entrada','');
                        dao.getData();
                    }
                })
            }
        })  
    },
    modalDetallesPagos: function (id) {
        $.ajax({
            url:'/get-detalle-ingresos/'+id,
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (resp) {
            const d = resp.detalle;

            // DATOS GENERALES
            $('#d_tienda').text(d.tienda);
            $('#d_proveedor').text(d.proveedor);
            $('#d_usuario').text(d.usuario);
            $('#d_fecha').text(d.fecha);
            $('#d_codigo').text(d.codigo_trazabilidad);
            $('#d_total').text(parseFloat(d.total_compra).toFixed(2));
            $('#d_pagado').text(parseFloat(d.total_pagado).toFixed(2));
            $('#d_estatus').text(d.estatus_pago);

            // MUEBLES
            let mueblesHtml = '';
            resp.muebles.forEach(m => {
                let subtotal = m.cantidad * m.precio_compra;
                mueblesHtml += `
                    <tr>
                        <td>${m.mueble}</td>
                        <td>${m.cantidad}</td>
                        <td>$${parseFloat(m.precio_compra).toFixed(2)}</td>
                        <td>$${subtotal.toFixed(2)}</td>
                    </tr>
                `;
            });
            $('#tblDetalleMuebles').html(mueblesHtml);

            // PAGOS
            let pagosHtml = '';

            if (!resp.pagos || resp.pagos.length === 0) {

                pagosHtml = `
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <i class="fa fa-circle-info"></i>
                            Sin pagos registrados
                        </td>
                    </tr>
                `;

            } else {

                resp.pagos.forEach(p => {
                    pagosHtml += `
                        <tr>
                            <td>${p.usuario ?? '—'}</td>
                            <td>$${parseFloat(p.monto ?? 0).toFixed(2)}</td>
                            <td>${p.metodo_pago ?? '—'}</td>
                            <td>${p.fecha ?? '—'}</td>
                            <td>${p.descripcion ?? '—'}</td>
                        </tr>
                    `;
                });

            }

            $('#tblDetallePagos').html(pagosHtml);
            const modalDetalleEntrada = new bootstrap.Modal(document.getElementById('modalDetalleEntrada'));
            modalDetalleEntrada.show(); 
        });
        
    }

};
let init = {
    validatePAgo: function(form){
        _gen.validate(form,{
          rules:{
            monto : {required: true},
            tipo_pago : {required: true},
          },
          messages: {
            monto : {required: 'Este campo es requerido'},
            tipo_pago : {required: 'Este campo es requerido'},
          }
        })
    },

};

$(document).ready(function () {
    dao.getData();
    dao.getCatTiendas('tiendas');
    $('#btn_pagar_entrada').on('click', function (e) {
        e.preventDefault();
        init.validatePAgo($('#frm_pagar_entrada'));
        if ($('#frm_pagar_entrada').valid()) {
            let total = parseFloat(document.getElementById('total_compra').value);
            let monto = parseFloat(document.getElementById('monto').value);
            if (monto > total) {
                Swal.fire({
                    icon:'warning',
                    title:'Advertencia!',
                    text:'El monto no puede ser mayor que el total a pagar.'
                });
                return
            }
            dao.postAddPagoEntrada();
        }
    });
    $('#tiendas').on('change',function (e) {
        e.preventDefault();
        dao.getData();
    });

});