dao = {
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
    postAddChofer: function () {
        var form = $('#frm_add_chofer')[0];
        var data = new FormData(form);
        $.ajax({
            url:'/post-add-chofer',
            type:'post',
            data:data,
            enctype:'multipart/form-data',
            processData:false,
            contentType:false,
            cache:false,
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function name(response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalAddChofer','frm_add_chofer','');
                dao.gatData();
            }
        });
    },
    gatData: function () {
        $.ajax({
            url:'/get-data-choferes',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tbl_choferes');
            const columns = [
                {"targets": [0],"mData":'id'},
                {"targets": [1],"mData":'tienda'},
                {"targets": [2],"mData":'nombre'},
                {"targets": [3],"mData":'apellidos'},
                {"targets": [4],"mData":'correo'},
                {"targets": [5],"mData":'telefono'},
                {"targets": [6],"mData":'direccion'},
                {"aTargets": [7], "mData" : function(o){
                    return '<button class="btn" onclick="dao.getDataEditar(' + o.id + ')"><i class="fas fa-pencil-alt" style="color: #1C85AA"></i></button>'+
                        '<button class="btn" onclick="dao.eliminar(' + o.id +')"><i class="far fa-trash-alt" style="color: #7C0A20; opacity: 1;"></i></button></li>';
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response)
        })
    },
    getDataEditar: function (id) {
        $.ajax({
            type:'get',
            url:'/get-chofer-to-edit',
            data:{'id':id},
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            Object.entries(response).forEach(([key, value]) => {
                if (key != 'tienda_id') {
                    console.log(`Llave: ${key} | Valor: ${value}`);
                    document.getElementById(key+'_ed').value = value;
                }else{
                    dao.getCatTiendas('tienda_ed',value);
                }
            });
            const modalUpdateChofer = new bootstrap.Modal(document.getElementById('modalUpdateChofer'));
            modalUpdateChofer.show();
            
        })
    },
    postUpdateChofer: function () {
        var form = $('#frm_update_chofer')[0];
        var data = new FormData(form);
        $.ajax({
            url:'/post-update-chofer',
            type:'post',
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
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalUpdateChofer','frm_update_chofer','');
                dao.getData();
            }
        })
    },
    eliminar: function (id) {
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
                      url: '/delete-cat-chofer',
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
                            dao.gatData();
                        }
                  })
              
              }
          })  
    }
};

init = {
    validateChofer:function (form) {
        _gen.validate(form,{
            rules:{
                nombre:{required:true},
                apellidos:{required:true},
                tienda:{required:true},
                correo:{required:true},
                telefono:{required:true},
                direccion:{required:true},
            },
            messages:{
                nombre:{required:'Este campo es requerido'},
                apellidos:{required:'Este campo es requerido'},
                tienda:{required:'Este campo es requerido'},
                correo:{required:'Este campo es requerido'},
                telefono:{required:'Este campo es requerido'},
                direccion:{required:'Este campo es requerido'},
            }
        })
    }
};
$(document).ready(function () {
    $('#btnAddAchofer').on('click', function (e) {
        e.preventDefault();
        dao.getCatTiendas('tienda','');
        const modalAddChofer = new bootstrap.Modal(document.getElementById('modalAddChofer'));
        modalAddChofer.show();
    });
    $('#btn_add_chofer').on('click',function (e) {
        e.preventDefault();
        init.validateChofer($('#frm_add_chofer'));
        if ($('#frm_add_chofer').valid()) {
            dao.postAddChofer();
        }
    });
    $('#btn_update_chofer').on('click',function (e) {
        e.preventDefault();
        init.validateChofer($('#frm_update_chofer'));
        if ($('#frm_update_chofer').valid()) {
            dao.postUpdateChofer();
        }

    });
});