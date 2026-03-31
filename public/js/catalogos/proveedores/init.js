dao = {
    postAddproveedor:function () {
        var form = $('#frm_add_proveedor')[0];
        var data = new FormData(form);
        $.ajax({
            type: 'POST',
            url: '/post-add-cat-proveedores',
            data:data,
            enctype:'multipart/form-data',
            processData:false,
            contentType:false,
            cache:false,
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text
            });
            if (response.icon == 'success') {
                dao.getDataProveedores();
                closeModal('modalAddProveedor','frm_add_proveedor','');
            }
        });
    },
    getDataProveedores:function () {
        $.ajax({
            url:'/get-data-cat-proveedores',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tbl_proveedores');
            const columns = [
                {"targets": [0],"mData":'id'},
                {"targets": [1],"mData":'nombre'},
                {"targets": [2],"mData":'contacto'},
                {"targets": [3],"mData":'telefono'},
                {"aTargets": [4], "mData" : function(o){
                    return  '<button class="dropdown-item" onclick="dao.editar(' + o.id + ')"><i class="fas fa-pencil-alt" style="color: #1C85AA"></i></button>'+
                            '<button class="dropdown-item" onclick="dao.eliminar(' + o.id +')"><i class="far fa-trash-alt" style="color: #7C0A20; opacity: 1;"></i></button>';
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response)
        })
    },
    editar: function (id) {
        $.ajax({
            type:'get',
            url:'/get-proveedor-to-edit',
            data:{'id':id},
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            document.getElementById('id_ed').value = response.id;
            document.getElementById('nombre_ed').value = response.nombre;
            document.getElementById('contacto_ed').value = response.contacto;
            document.getElementById('telefono_ed').value = response.telefono;
            const modalUpdateProveedor = new bootstrap.Modal(document.getElementById('modalUpdateProveedor'));
            modalUpdateProveedor.show();
            
        })
    },
    postEditarProveedor: function () {
        var form = $('#frm_update_proveedor')[0];
        var data = new FormData(form);
        $.ajax({
            type:'post',
            url:'/post-edit-proveedor',
            data:data,
            enctype:'multipart/form-data',
            processData:false,
            contentType:false,
            cache:false,
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text
            });
            if (response.icon == 'success') {
                dao.getDataProveedores();
                closeModal('modalUpdateProveedor','frm_update_proveedor','');
            }
        })
    },
    eliminar:function (id) {
        Swal.fire({
            title: "¿Estas seguro que deseas eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D48D8D',
            cancelButtonColor: '#dea9a9fd',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Continuar',
            reverseButtons:true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: '/delete-cat-proveedor',
                    data: {'id':id},
                    headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function (response) {
                    Swal.fire({
                        icon: response.icon,
                        title: response.title,
                        text: response.text,
                        allowOutsideClick: true,
                        confirmButtonText: "Listo",
                    });
                    if (response.icon == 'success') {
                        dao.getDataProveedores();
                    }
                })
            
            }
        }) 
    },
    verProveedor:function (id) {
        let idTienda = null;
        let inicio = null;
        let fin = null;
        const tienda = document.getElementById('tiendas');
        const i = document.getElementById('inicio');
        const f = document.getElementById('fin');
        if (tienda) {
            idTienda = tienda.value;
        }
        if (i) {
            inicio = i.value;
        }
        if (f) {
            fin = f.value;
        }
        
        $.ajax({
            url:'/get-estado-cuenta-proveedor',
            type:'GET',
            data:{
                id:id,
                tienda:idTienda,
                inicio:inicio,
                fin:fin
            },
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (data) {
            // 🟢 HEADER
            $('#prov_nombre').text(data.proveedor.nombre);
            // $('#contacto').text(data.proveedor.contacto);
            // $('#telefono').text(data.proveedor.telefono);
            document.getElementById('contact').innerText = data.proveedor.contacto;
            document.getElementById('tel').innerText = data.proveedor.telefono;
            
             // 🟢 RESUMEN
            $('#saldo_inicial').text(money(data.resumen.saldo_inicial));
            $('#total_cargos').text(money(data.resumen.total_cargos));
            $('#total_abonos').text(money(data.resumen.total_abonos));
            $('#saldo_final').text(money(data.resumen.saldo_final));

            let colorFinal = data.resumen.saldo_final >= 0 ? 'text-success' : 'text-danger';
            $('#saldo_final').attr('class','fw-bold '+colorFinal);

            // 🟣 TABLA
            let html = '';

            data.movimientos.forEach(m => {

                let color = m.saldo >= 0 ? 'text-success' : 'text-danger';

                html += `
                    <tr>
                        <td>${m.fecha}</td>
                        <td>${m.concepto}</td>
                        <td>${money(m.cargo)}</td>
                        <td>${money(m.abono)}</td>
                        <td class="${color}">${money(m.saldo)}</td>
                    </tr>
                `;
            });

            $('#tablaEstadoCuenta').html(html);
            
        })
        const modalVerProveedor = new bootstrap.Modal(document.getElementById('modalVerProveedor'));
        modalVerProveedor.show();
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
    abrirSaldoFavor: function (id) {
        console.log('id para agregar',id);
        $('#proveedor_id').val(id);
        const modalAddSaldo = new bootstrap.Modal(document.getElementById('modalAddSaldo'));
        modalAddSaldo.show();
    },
    addSaldoProveedor: function (){
        let data = new FormData($('#frm_add_saldo')[0]);
        let tienda = document.getElementById('tiendas');
        if (tienda &&  !tienda.value) {
            Swal.fire({
                icon:'warning',
                title:'Advertencia!',
                text:'Selecciona una tienda',
            });
            return;
        }else{
            data.append('tienda',tienda.value);
        }
        
        $.ajax({
            url:'/post-add-saldo-proveedor',
            type:'POST',
            data:data,
            processData:false,
            contentType:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text
            });
            if (response.icon == 'success') {
                dao.getCuentasProveedores();
                closeModal('modalAddSaldo','frm_add_saldo','');
            }
            
        })
    },
    getCuentasProveedores: function () {
        let tienda = document.getElementById('tiendas');
        let inicio = document.getElementById('inicio');
        let fin = document.getElementById('fin');
        let data = {};

        if (tienda && tienda.value) data.tienda = tienda.value;
        if (inicio && inicio.value) data.inicio = inicio.value;
        if (fin && fin.value) data.fin = fin.value;

        $.ajax({
            url:'/get-cuentas-proveedores',
            type:'get',
            data:data,
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tbl_cuentas_proveedores');
            const columns = [
                {"targets": [0],"mData":'id'},
                {"targets": [1],"mData":'nombre'},
                {"targets": [2],"mData":function (o) {
                    return money(o.total_compras);
                }},
                {"targets": [3],"mData":function (o) {
                    return money(o.total_pagado);
                }},
                {"targets": [4],"mData":function (o) {
                    return money(o.saldo_favor);
                }},
                {"targets": [5],"mData": function (o) {
                    if (o.saldo_actual < 0){
                        return `<span class="text-danger fw-bold">${money(o.saldo_actual)}</span>`;
                    }else if (o.saldo_actual > 0){
                        return `<span class="text-primary fw-bold">${money(o.saldo_actual)}</span>`;
                    }else if (o.saldo_actual == 0){
                        return `<span class="text-success fw-bold">${money(o.saldo_actual)}</span>`;
                    }else{
                        return money(o.saldo_actual);
                    }
                }},
                {"targets": [6],"mData": function (o){
                     return `<button class="btn btn-sm btnAgregar" onClick="dao.verProveedor(${o.id})">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button class="btn btn-success btn-sm" onclick="dao.abrirSaldoFavor(${o.id})">+ Saldo</button>`
                }}
            ];
            _gen.setTableScrollEspecial2(table,columns,response);
        })
    }
    
};
init = {
    validateProveedor:function (form) {
        _gen.validate(form,{
            rules:{
                nombre:{required:true},
                contacto:{required:true},
                telefono:{required:true},
            },
            messages:{
                nombre:{required:'Este campo es requerido'},
                contacto:{required:'Este campo es requerido'},
                telefono:{required:'Este campo es requerido'},
            }
        })
    },
    validateAddSaldo:function (form) {
        _gen.validate(form,{
            rules:{
                monto:{required:true},
                metodo_pago:{required:true},
            },
            messages:{
                monto:{required:'Este campo es requerido'},
                metodo_pago:{required:'Este campo es requerido'},
            }
        })
    } 
};
function money(n) {
    return new Intl.NumberFormat('es-MX',{
        style:'currency',
        currency:'MXN',
    }).format(n || 0 );
}
$(document).ready(function () {
    dao.getCatTiendas('tiendas');
    dao.getCuentasProveedores();
    $('#btnAddProveedor').on('click',function (e) {
        e.preventDefault();
        const modalAddProveedor = new bootstrap.Modal(document.getElementById('modalAddProveedor'));
        modalAddProveedor.show();
    });
    $('#btn_add_proveedor').on('click', function (e) {
        e.preventDefault();
        init.validateProveedor($('#frm_add_proveedor'));
        if ($('#frm_add_proveedor').valid()) {
            dao.postAddproveedor();
        }
    });
    $('#btn_update_proveedor').on('click',function (e) {
        e.preventDefault();
        init.validateProveedor($('#frm_update_proveedor'));
        if ($('#frm_update_proveedor')) {
            dao.postEditarProveedor();
        }
    });
    $('#btn_add_saldo').on('click', function (e) {
        e.preventDefault();
        init.validateAddSaldo($('#frm_add_saldo'));
        if ($('#frm_add_saldo').valid()) {
            
            dao.addSaldoProveedor();
        }
    });
    $('#tiendas').on('change', function (e) {
        e.preventDefault();
        dao.getCuentasProveedores();
    });
    
});