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
        dao.getDataResumen();
        dao.cargarTablaGastos();
        dao.cargarTablaInventario();
        dao.cargarTablaProveedores();
        dao.cargarTablaVentas();
    });
    
    $('#fecha_inicio').on('change',function (e) {
        const finInput = document.getElementById('fecha_fin');
        if(this.value){
            finInput.min = this.value; // fin nunca menor que inicio
        }
        e.preventDefault();
        dao.getDataResumen();
        dao.cargarTablaGastos();
        dao.cargarTablaInventario();
        dao.cargarTablaProveedores();
        dao.cargarTablaVentas();
    });
    $('#fecha_fin').on('change',function (e) {
        const inicioInput = document.getElementById('fecha_inicio');
        if(this.value){
            inicioInput.max = this.value; // inicio nunca mayor que fin
        }
        e.preventDefault();
        dao.getDataResumen();
        dao.cargarTablaGastos();
        dao.cargarTablaInventario();
        dao.cargarTablaProveedores();
        dao.cargarTablaVentas();
    });

});