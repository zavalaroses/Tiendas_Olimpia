dao = {
    getCatalogoTiendas: function(field,id){
        $.ajax({
            url:'/get-catalogo-tiendas',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            console.log(response);
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
    getCatMuebles: function (id,field,tienda) {
        $.ajax({
            url:'/get-data-muebles-by-tienda/'+tienda,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            var select = $('#'+field);
            select.html('');
            select.append(new Option('Selecciona una opción'));
            response.map(function (val,i) {
                if (id != '' && id == val.id) {
                    select.append(new Option(response[i].nombre,response[i].id, true, true));
                }else{
                    select.append(new Option(response[i].nombre,response[i].id, false, false));
                }
            });
        })
    },
};

init = {

};
$(document).ready(function () {
    dao.getCatalogoTiendas('tiendas','');
    $('#btnAddGarantia').on('click', function (e) {
        e.preventDefault();
        const tienda = document.getElementById('tiendas');
        if (tienda) {
            if (tienda.value == '') {
                Swal.fire({
                    icon:'warning',
                    title:'Advertencia!',
                    text:'Selecciona una tienda',
                });
                return;
            }
            dao.getCatMuebles('','mueble',tienda.value);
        }else{
            dao.getCatMuebles('','mueble','');
        }
        const modalAddGarantia = new bootstrap.Modal(document.getElementById('modalAddGarantia'));
        modalAddGarantia.show();
    });
    
});