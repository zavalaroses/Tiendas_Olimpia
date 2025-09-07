dao = {
    postAddTienda: function () {
        var form = $('#frm_add_tienda')[0];
        var data = new FormData(form);
        $.ajax({
            type: 'POST',
            url: '/post-add-cat-tienda',
            data:data,
            enctype:'multipart/form-data',
            processData:false,
            contentType:false,
            cache:false,
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            console.log("🚀 ~ response:", response)
            
        });
    },
    getDataTiendas:function () {
        $.ajax({
            url:'/get-data-cat-tiendas',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            console.log("🚀 ~ response:", response)
            const table = $('#tbl_tiendas');
            const columns = [
                {"targets": [0],"mData":'id'},
                {"targets": [1],"mData":'nombre'},
                {"targets": [2],"mData":'ubicacion'},
                {"aTargets": [3], "mData" : function(o){
                    return '<div class="dropdown">'+
                    '<button type="button" class="btn btn-light" data-bs-toggle="dropdown"  aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'+
                        '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">'+
                            '<li onclick="dao.editar(' + o.id + ')"><button class="dropdown-item"><i class="fas fa-pencil-alt" style="color: #1C85AA"></i>&nbsp;Editar</button></li>'+
                            '<li onclick="dao.eliminar(' + o.id +','+o.area+')"><button class="dropdown-item"><i class="far fa-trash-alt" style="color: #7C0A20; opacity: 1;"></i>&nbsp;Eliminar</button></li>'+
                        '</ul>'+
                    '</div>';
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response)
        })
    }
};

init = {
    validateTienda:function (form) {
        _gen.validate(form,{
            rules:{
                nombre:{required:true},
                direccion:{required:true},
            },
            messages:{
                nombre:{required:'Este campo es requerido'},
                direccion:{required:'Este campo es requerido'},
            }
        })
    }
};
$(document).ready(function () {
    console.log('init.js tiendas');
    $('#btnAddTienda').on('click', function (e) {
        e.preventDefault();
        const modalAddtienda = new bootstrap.Modal(document.getElementById('modalAddtienda'));
        modalAddtienda.show();
    });
    $('#btn_add_tienda').on('click', function() {
        init.validateTienda($('#frm_add_tienda'));
        if ($('#frm_add_tienda').valid()) {
            dao.postAddTienda();
        }
    });
    
});