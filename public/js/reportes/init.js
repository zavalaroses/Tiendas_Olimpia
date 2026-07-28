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
            $('#kpi_ventas').text(money(response.ventas));
            $('#kpi_gastos').text(money(response.gastos));
            $('#kpi_balance').text(money(response.balance));
            $('#kpi_inventario').text(money(response.inventario));
            $('#kpi_caja').text(money(response.caja));
            $('#kpi_cuenta').text(money(response.cuenta));
            $('#kpi_adeudo').text(money(response.adeudo));
            $('#kpi_saldo_f').text(money(response.saldoFavor));
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
            const table = $('#tbl_apartados');
            const columns = [
                {"targets":[0],"mData": function(o){
                    if(!o.created_at) return '-';
            
                    const fecha = new Date(o.created_at);
                    return fecha.toLocaleDateString('es-MX'); 
                }},
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
            const table = $('#tbl_gastos');
            const columns = [
                {"targets":[0],"mData": function(o){
                    if(!o.created_at) return '-';
            
                    const fecha = new Date(o.created_at);
                    return fecha.toLocaleDateString('es-MX'); 
                }},
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
            const table = $('#tbl_inventario');
            const columns = [
                {"targets":[0],"mData":'mueble'},
                {"targets":[1],"mData":'stock'},
                {"targets":[2],"mData":function (o) {
                    return money(o.precio_compra);
                }},
                {"targets":[3],"mData":function (o) {
                    return money(o.valor);
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response);
        });
    },
    cargarTablaProveedores: function () {
        const tienda = document.getElementById('tiendas');
        let idTienda = null;
        if (tienda) {
            idTienda = tienda.value;
        }
        const data = {
            inicio: $('#fecha_inicio').val(),
            fin: $('#fecha_fin').val(),
            tienda: idTienda
        };
        $.ajax({
            url:'/get-data-resumen-proveedores',
            type:'get',
            data:data,
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            const table = $('#tbl_proveedores');
            const columns = [
                {"targets":[0],"mData":'proveedor'},
                {"targets":[1],"mData":function (o) {
                    return money(o.total_compra)
                }},
                {"targets":[2],"mData":function (o) {
                    return money(o.total_pagado);
                }},
                {"targets":[3],"mData":function (o) {
                    return money(o.adeudo);
                }},
                {"targets":[4],"mData":'estatus_pago'}
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
    getKpisPrincipales: function () {
        let tienda = $('#tiendas').val() ?? null;
        let inicio = $('#fecha_inicio').val() ?? null;
        let fin = $('#fecha_fin').val() ?? null;
        $.ajax({
            url:'/get-data-kpis-ventas',
            type:'get',
            data: {'tienda':tienda,'inicio':inicio,'fin':fin},
            contentType: 'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const {
                nApartados,
                nApartadosAnt,
                nVentas,
                nVentasAnt,
                ticketPromedio,
                variacion,
                vendido,
                vendidoAnt
            } = response;
            $('#totalVentas').text(money(vendido));
            $('#nVentas').text(nVentas);
            $('#nApartados').text(nApartados);
            $('#notaPromedio').text(money(ticketPromedio));
            $('#totalVentasAnt').text(money(vendidoAnt));
            $('#nVentasAnt').text(nVentasAnt);
            $('#nApartadosAnt').text(nApartadosAnt);
            $('#variacion').text('% ' + variacion);
            if (variacion < 0 ) {
                $('#variacionKpi').removeClass('bg-purple-soft');
                $('#variacionKpi').removeClass('bg-success-soft');
                $('#variacionKpi').addClass('bg-danger-soft');
                $('#variacionArrow').removeClass('fa-arrow-trend-up');
                $('#variacionArrow').addClass('fa-arrow-trend-down');
            }else{
                $('#variacionKpi').removeClass('bg-purple-soft');
                $('#variacionKpi').removeClass('bg-danger-soft');
                $('#variacionKpi').addClass('bg-success-soft');
                $('#variacionArrow').removeClass('fa-arrow-trend-down');
                $('#variacionArrow').addClass('fa-arrow-trend-up');
            }
        });
    },
    getTablasTops: function (response) {
        let tienda = $('#tiendas').val() ?? null;
        let inicio = $('#fecha_inicio').val() ?? null;
        let fin = $('#fecha_fin').val() ?? null;
        $.ajax({
            url:'/get-data-tablas-tops',
            type:'get',
            data:{'tienda':tienda,'inicio':inicio,'fin':fin},
            contentType: 'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const {tblVentas,tblVendedores,tblFormasPago,tblMuebles} = response;
            const tVentas = $('#tbl_ventas');
            const columnsVentas = [
                {"targets":[0],"mData":'tienda'},
                {"targets":[1],"mData":function (o) {
                    return money(o.vendido);
                }},
                {"targets":[2],"mData":function (o) {
                    return money(o.costo);
                }},
                {"targets":[3],"mData":function (o) {
                    return money(o.utilidad);
                    
                }},
            ];
            const tVendedores = $('#tbl_vendedores');
            const columnsVendedores = [
                {"targets":[0],"mData":'usuario'},
                {"targets":[1],"mData":'tienda'},
                {"targets":[2],"mData":function (o) {
                    return money(o.vendido);
                }},
                {"targets":[2],"mData":function (o) {
                    return money(o.costo);
                }},
                {"targets":[3],"mData":'notas'},
            ];
            const tPagos = $('#tbl_pagos');
            const columnsPagos = [
                {"targets":[0],"mData":'tipo_pago'},
                {"targets":[1],"mData":function (o) {
                    return money(o.vendido);
                }},
                
            ];
            const tMuebles = $('#tbl_muebles');
            const columnsMuebles = [
                {"targets":[0],"mData":'tienda'},
                {"targets":[1],"mData":'mueble'},
                {"targets":[2],"mData":'vendidos'},
                {"targets":[3],"mData":function (o) {
                    return money(o.recaudado);
                }},
                
            ];

            _gen.setTableScrollEspecial3(tVentas,columnsVentas,tblVentas);
            _gen.setTableScrollEspecial3(tVendedores,columnsVendedores,tblVendedores);
            _gen.setTableScrollEspecial3(tPagos,columnsPagos,tblFormasPago);
            _gen.setTableScrollEspecial3(tMuebles,columnsMuebles,tblMuebles);
            
        }); 
    },
    getKpis2: function () {
        let tienda = $('#tiendas').val() ?? null;
        let inicio = $('#fecha_inicio').val() ?? null;
        let fin =  $('#fecha_fin').val() ?? null;
        $.ajax({
            url:'/get-data-kpis2-cobranza',
            type:'get',
            data:{'tienda':tienda,'inicio':inicio,'fin':fin},
            contentType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const {apartadosActivos, saldoPendiente, entregas, entregasPendientes } = response;
            $('#apartadosPendientes').text(apartadosActivos);
            $('#saldoPendiente').text(money(saldoPendiente));
            $('#entregas').text(entregas);
            $('#entregasPendientes').text(entregasPendientes);
        });
    },
    getDataBalances: function () {
        let tienda = $('#tiendas').val() ?? null;
        let inicio = $('#fecha_inicio').val() ?? null;
        let fin = $('#fecha_fin').val() ?? null;
        $.ajax({
            url:'/get-data-balances',
            type:'get',
            data:{'tienda':tienda,'inicio':inicio,'fin':fin},
            contentType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            const {balanceActual, balanceAnterior } = response;
             $('#fechaActual').text(balanceActual.fecha);
             $('#inventarioActual').text(money(balanceActual.inventario));
             $('#cajaActual').text(money(balanceActual.caja));
             $('#cuentaActual').text(money(balanceActual.bancos));
             $('#apartadosActual').text(money(balanceActual.apartados));
             $('#saldoFavorActual').text(money(balanceActual.saldo_favor));
             $('#adeudosActual').text(money(balanceActual.adeudos));
             $('#balanceActual').text(money(balanceActual.balance));

             $('#fechaAnterior').text(balanceAnterior.fecha);
             $('#inventarioAnterior').text(money(balanceAnterior.inventario));
             $('#cajaAnterior').text(money(balanceAnterior.caja));
             $('#cuentaAnterior').text(money(balanceAnterior.bancos));
             $('#apartadosAnterior').text(money(balanceAnterior.apartados));
             $('#saldoFavorAnterior').text(money(balanceAnterior.saldo_favor));
             $('#adeudosAnterior').text(money(balanceAnterior.adeudos));
             $('#balanceAnterior').text(money(balanceAnterior.balance));
               
        })

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
    const tienda = document.getElementById('tiendas');
    dao.getDataResumen(); 
    dao.cargarTablaVentas();
    if (tienda) {
        dao.getCatTiendas('tiendas');    
    }
    $('button[data-bs-target="#tabVentas"]').on('shown.bs.tab', dao.cargarTablaVentas);
    $('button[data-bs-target="#tabGastos"]').on('shown.bs.tab', dao.cargarTablaGastos);
    $('button[data-bs-target="#tabInventario"]').on('shown.bs.tab', dao.cargarTablaInventario);
    $('button[data-bs-target="#tabProveedores"]').on('shown.bs.tab', dao.cargarTablaProveedores);

    $('#tiendas').on('change', function (e) {
        e.preventDefault();
        dao.getKpisPrincipales();
        dao.getTablasTops();
        dao.getKpis2();
        dao.getDataBalances();
    });
    
    $('#fecha_inicio').on('change',function (e) {
        const finInput = document.getElementById('fecha_fin');
        if(this.value){
            finInput.min = this.value; // fin nunca menor que inicio
        }
        e.preventDefault();
        dao.getKpisPrincipales();
        dao.getTablasTops();
        dao.getKpis2();
        dao.getDataBalances();
    });
    $('#fecha_fin').on('change',function (e) {
        const inicioInput = document.getElementById('fecha_inicio');
        if(this.value){
            inicioInput.max = this.value; // inicio nunca mayor que fin
        }
        e.preventDefault();
        dao.getTablasTops();
        dao.getKpis2();
        dao.getDataBalances();
    });
    $('#btnGeneraReporte').on('click', function (e) {
        form = document.getElementById('formReporte');
        form.method = "POST";
        form.action = rutaPruebaPDF;
        form.target = "_blank"; // abre en nueva pestaña
        console.log(form.action);
        form.submit(); 
    });

});