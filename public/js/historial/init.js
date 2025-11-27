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