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
                {"targets":[4],"mData":function (o) {
                   return `<button class="btn btn-sm btnAgregar" onClick="dao.verProveedor(${o.id})">
                        <i class="fa fa-eye"></i>
                    </button>`
                }},
                {"aTargets": [5], "mData" : function(o){
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
        $.ajax({
            url:'/get-estado-cuenta-proveedor/'+id,
            type:'GET',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (res) {
            // 🟢 HEADER
            $('#prov_nombre').text(res.proveedor.nombre);
            $('#prov_info').text(res.proveedor.telefono ?? '');

            $('#adeudo').text(money(res.resumen.adeudo));
            $('#saldo_favor').text(money(res.resumen.saldo_favor));

            let balance = res.resumen.balance;
            $('#balance').text(money(balance));

            // 🔥 COLOR
            if (balance >= 0) {
                $('#balance').removeClass().addClass('text-success fw-bold');
            } else {
                $('#balance').removeClass().addClass('text-danger fw-bold');
            }
            // 🟣 TABLA
            let html = '';

            res.movimientos.forEach(m => {

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
    }
};
function money(n) {
    return new Intl.NumberFormat('es-MX',{
        style:'currency',
        currency:'MXN',
    }).format(n || 0 );
}
$(document).ready(function () {
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
    })
});