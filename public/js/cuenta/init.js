
var dao = {
    getDataCuenta : function (tienda) {
        $.ajax({
            url:'/get-data-transacciones/'+tienda,
            dataType:'json',
            type:'get',
        }).done(function (response) {
            const table = $('#tbl_transacciones');
            const columns = [
                {"targets":[0],"mData":function (o) {
                    const soloFecha = o.fecha.split(" ")[0]; // "2025-11-18"
                    const [year, month, day] = soloFecha.split("-");
                    return `${day}-${month}-${year}`;
                },'sClass':'muted'},
                {"targets":[1],"mData":'venta_id'},
                {"targets":[2],"mData":'tipo_pago'},
                {"targets":[3],"mData":'descripcion'},
                {"targets":[4],"mData":'cantidad'},
                {"targets":[5],"mData":'usuario'},
            ];
            _gen.setTableScrollEspecial3(table,columns,response,350,100);
            
        });
    },
    getCatTiendas: function (field,id) {
        $.ajax({
            url:'/get-catalogo-tiendas',
            dataType:'json',
            type:'get',
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
};
var init = {
   
};
function formatoMoneda(valor) {
    return '$ ' + Number(valor).toLocaleString('es-MX', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

$(document).ready(function () {
    $('#tiendas').on('change', function (e) {
        e.preventDefault();
        dao.getDataCuenta(this.value);
    });
});