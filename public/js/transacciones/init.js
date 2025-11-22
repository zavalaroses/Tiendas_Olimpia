var dao = {
    getData : function (tienda) {
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
    getResumenCorte: function (tienda) {
        $.ajax({
            url:'/get-resumen-corte/'+tienda,
            dataType:'json',
            type:'get',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            document.getElementById('efectivoI').textContent = formatoMoneda(response.efectivo);
            document.getElementById('efectivoC').textContent = formatoMoneda(response.cuenta);

            document.getElementById('egresoE').textContent = formatoMoneda(response.egresosEfectivo);
            document.getElementById('egresoC').textContent = formatoMoneda(response.egresosCuenta);

            document.getElementById('ingresoT').textContent = formatoMoneda(response.ingresoTotal);
            document.getElementById('egresoT').textContent = formatoMoneda(response.egresoTotal);

            document.getElementById('totalG').textContent = formatoMoneda(response.totalGeneral);
        });
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
        dao.getData(this.value);
        dao.getResumenCorte(this.value);
    });
});