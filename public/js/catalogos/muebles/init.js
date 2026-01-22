dao = {
    postAddMueble:function () {
        var form = $('#frm_add_mueble')[0];
        var data = new FormData(form);
        $.ajax({
            type:'post',
            url:'/post-add-mueble',
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
                closeModal('modalAddMueble','frm_add_mueble','');
                dao.getData();
            }
        })
    },
    getData:function () {
        $.ajax({
            url:'/get-data-muebles',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            let {muebles,rol} = response;
            const table = $('#tbl_muebles');
            let columns = [];
            if (rol == 1) {
                columns = [
                    {"targets": [0],"mData":'id'},
                    {"targets": [1],"mData":'nombre'},
                    {"targets": [2],"mData":'codigo'},
                    {"targets": [3],"mData":'descripcion'},
                    {"targets": [4],"mData":'precio'},
                    {"targets": [5],"mData":'precio_compra'},
                    {"aTargets": [6], "mData" : function(o){
                        return '<button class="btn" onclick="dao.getDataEditar(' + o.id + ')"><i class="fas fa-pencil-alt" style="color: #1C85AA"></i></button>'+
                            '<button class="btn" onclick="dao.eliminar(' + o.id +')"><i class="far fa-trash-alt" style="color: #7C0A20; opacity: 1;"></i></button></li>';
                    }},
                ];
            }else{
                columns = [
                    {"targets": [0],"mData":'id'},
                    {"targets": [1],"mData":'nombre'},
                    {"targets": [2],"mData":'codigo'},
                    {"targets": [3],"mData":'descripcion'},
                    {"targets": [4],"mData":'precio'},
                ];
            }
            
            _gen.setTableScrollEspecial2(table,columns,muebles)
        });
        
    },
    getDataEditar: function (id) {
        $.ajax({
            type:'get',
            url:'/get-mueble-by-id/'+id,
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            Object.entries(response).forEach(([key,value])=>{
                document.getElementById(key+'_ed').value = value;
            })
            const modalUpdateMueble = new bootstrap.Modal(document.getElementById('modalUpdateMueble'));
            modalUpdateMueble.show(); 
        });
    },
    postUpdateMueble: function () {
        var form = $('#frm_update_mueble')[0];
        var data = new FormData(form);
        $.ajax({
            url:'/post-update-mueble',
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
                closeModal('modalUpdateMueble','frm_update_mueble','');
                dao.getData();
            }
        }).fail(function (error){
            _gen.error(error);
        });
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
                      url: '/delete-cat-mueble',
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
                            dao.getData();
                        }
                  })
              
              }
          })  
    }
};

init = {
    validateMueble:function (form) {
        _gen.validate(form,{
            rules:{
                nombre:{required:true},
                codigo:{required:true},
                // descripcion:{required:true},
                precio:{required:true},
            },
            messages:{
                nombre:{required:'Este campo es requerido'},
                codigo:{required:'Este campo es requerido'},
                // descripcion:{required:'Este campo es requerido'},
                precio:{required:'Este campo es requerido'},
            }
        })
    }
};
$(document).ready(function () {
    $('#btnAddMueble').on('click',function (e) {
        e.preventDefault();
        const modalAddMueble = new bootstrap.Modal(document.getElementById('modalAddMueble'));
        modalAddMueble.show();
    });
    $('#btn_add_mueble').on('click',function (e) {
        e.preventDefault();
        init.validateMueble($('#frm_add_mueble'));
        if ($('#frm_add_mueble').valid()) {
            dao.postAddMueble();
        }
    });
    $('#btn_update_mueble').on('click',function name(e) {
        e.preventDefault();
        init.validateMueble($('#frm_update_mueble'));
        if ($('#frm_update_mueble').valid()) {
            dao.postUpdateMueble();
        }
    })
})