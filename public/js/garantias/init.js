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
    getDataGarantias: function (tienda) {
        $.ajax({
            url:'/get-data-garantias/'+tienda,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tblGarantias');
            const columns = [
                {"targets":[0],"mData":'id'},
                {"targets":[1],"mData":'tienda'},
                {"targets":[2],"mData":'mueble'},
                {"targets":[3],"mData":'motivo'},
                {"targets":[4],"mData":'cantidad'},
                {"targets":[5],"mData":function (o) {
                    return o.name + ' ' + o.apellidos;
                }},
                {"targets":[6],"mData":'fecha'},
                {"targets":[7],"mData":function (o) {
                    return o.cliente ? o.cliente : 'Tienda';
                }},
                {"targets":[8],"mData":function (o) {
                    return `<button onclick="dao.terminarGarantia(${o.id})"><i class="fa-solid fa-screwdriver-wrench" style="color:#4caf50"></i></button>`;
                }}
            ];
            _gen.setTableScrollEspecial2(table,columns,response);
            
        })
    },
    terminarGarantia:function (id) {
        const tienda = document.getElementById('tiendas');
        Swal.fire({
            title: "¿Estas seguro que deseas marcar como repuesto?",
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
                    url: '/terminar-garantia',
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
                        let idTienda = tienda ? tienda.value : '';
                        dao.getDataGarantias(idTienda);
                    }
                })
            
            }
        });
    }
};

init = {

};
$(document).ready(function () {
    dao.getCatalogoTiendas('tiendas','');
    dao.getDataGarantias('');
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
    $('#tiendas').on('change',function (e) {
        e.preventDefault();
        dao.getDataGarantias(this.value);
    });
    
});