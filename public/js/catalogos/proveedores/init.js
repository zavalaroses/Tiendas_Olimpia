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
                closeModal('modalAddtienda','frm_add_proveedor','');
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