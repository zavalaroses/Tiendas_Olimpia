let dao = {
    getDataResumen : function () {
        const tienda = document.getElementById('tiendas');
        let idTienda = null;
        if (tienda) {
            idTienda = tienda.value;
        }
        const data = {
            inicio: $('#fecha_inicio').val(),
            fin: $('#fecha_fin').val(),
            tienda: idTienda,
        }
        $.ajax({
            url:'/reportes/resumen',
            type:'get',
            data:data,
            dataType:'JSON',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            console.log('response',response);
            $('#kpi_ventas').text(money(response.ventas));
            $('#kpi_gastos').text(money(response.gastos));
            $('#kpi_utilidad').text(money(response.utilidad));
            $('#kpi_inventario').text(money(response.inventario));
            $('#kpi_caja').text(money(response.caja));
            $('#kpi_cuenta').text(money(response.cuenta));
            $('#kpi_adeudo').text(money(response.adeudo));
        });
        
    },
    cargarTablaVentas : function () {
        const tienda = document.getElementById('tiendas');
        let idTienda = null;
        if (tienda) {
            idTienda = tienda.value;
        }
        const data = {
            inicio: $('#fecha_inicio').val(),
            fin: $('#fecha_fin').val(),
            tienda: idTienda,
        };
        $.ajax({
            url:'/get-data-tabla-ventas',
            type:'get',
            data:data,
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            console.log("🚀 ~ response:", response)
            const table = $('#tbl_apartados');
            const columns = [
                {"targets":[0],"mData":'created_at'},
                {"targets":[1],"mData":'descripcion'},
                {"targets":[2],"mData":'tipo_pago'},
                {"targets":[3],"mData":function (o) {
                    return money(o.cantidad);
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response);    
        });
    },
    cargarTablaGastos: function () {
        const tienda = document.getElementById('tiendas');
        let idTienda = null;
        if (tienda) {
            idTienda = tienda.value;
        }
        const data = {
            inicio: $('#fecha_inicio').val(),
            fin: $('#fecha_fin').val(),
            tienda: idTienda,
        };
        $.ajax({
            url:'/get-data-tabla-gastos',
            type:'get',
            data:data,
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            console.log("🚀 ~ response:", response)
            const table = $('#tbl_gastos');
            const columns = [
                {"targets":[0],"mData":'created_at'},
                {"targets":[1],"mData":'descripcion'},
                {"targets":[2],"mData":'tipo_pago'},
                {"targets":[3],"mData":function (o) {
                    return money(o.cantidad);
                }},
                {"targets":[4],"mData":'usuario'}
            ];
            _gen.setTableScrollEspecial2(table,columns,response);
        });
    },
    cargarTablaInventario: function () {
        const tienda = document.getElementById('tiendas');
        let idTienda = null;
        if (tienda) {
            idTienda = tienda.value;
        }
        const data = {
            inicio: $('#fecha_inicio').val(),
            fin: $('#fecha_fin').val(),
            tienda: idTienda,
        };
        $.ajax({
            url:'/get-data-tabla-inventario',
            type:'get',
            data:data,
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            console.log("🚀 ~ response:", response)
            const table = $('#tbl_inventario');
            const columns = [
                {"targets":[0],"mData":'mueble'},
                {"targets":[1],"mData":'stock'},
                {"targets":[2],"mData":function (o) {
                    return money(o.precio_compra);
                }},
                {"targets":[3],"mData":function (o) {
                    return money(o.cantidad);
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response);
        });
    }

};
let init = {

};
function money(n) {
    return new Intl.NumberFormat('es-MX',{
        style:'currency',
        currency:'MXN',
    }).format(n || 0 );
}

$(document).ready(function () {
    console.log('init resumen');
   dao.getDataResumen(); 
   $('button[data-bs-target="#tabVentas"]').on('shown.bs.tab', dao.cargarTablaVentas);
   $('button[data-bs-target="#tabGastos"]').on('shown.bs.tab', dao.cargarTablaGastos);
   $('button[data-bs-target="#tabInventario"]').on('shown.bs.tab', dao.cargarTablaInventario);
});