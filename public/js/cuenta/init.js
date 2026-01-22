
var dao = {
    getDataCuenta : function (tienda) {
        $.ajax({
            url:'/get-data-cuenta/'+tienda,
            dataType:'json',
            type:'get',
        }).done(function (response) {
            const {data, entradas,salidas, saldoCuenta} = response;
            const table = $('#tbl_transacciones_cuenta');
            const columns = [
                {"targets":[0],"mData":function (o) {
                    const soloFecha = o.fecha.split(" ")[0]; // "2025-11-18"
                    const [year, month, day] = soloFecha.split("-");
                    return `${day}-${month}-${year}`;
                },'sClass':'muted'},
                {"targets":[1],"mData":'tienda'},
                {"targets":[2],"mData":function(o){
                    if (o.tipo_movimiento == 'entrada') {
                        return '<span class="badge bg-success">Ingreso</span>';
                    }
                    if (o.tipo_movimiento == 'salida') {
                        return '<span class="badge bg-danger">Salida</span>';
                    }else{
                        return '<span class="badge bg-secondary">'+ o.tipo_movimiento +'</span>';
                    }
                }},
                {"targets":[3],"mData":'concepto'},
                {"targets":[4],"mData":'monto'},
                {"targets":[5],"mData":'descripcion'},
                {"targets":[6],"mData":'usuario'},
            ];
            _gen.setTableScrollEspecial3(table,columns,data,350,100);
            document.getElementById('ingresos').textContent = formatoMoneda(entradas);
            document.getElementById('salidas').textContent = formatoMoneda(salidas);
            document.getElementById('saldoCuenta').textContent = formatoMoneda(saldoCuenta);
            
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
    postAddIngreso: function (tienda) {
        var form = $('#frm_add_ingreso')[0];
        var data = new FormData(form);
        data.append('tienda',tienda);
        $.ajax({
            url:'/post-add-ingresos',
            type:'post',
            data:data,
            enctype:'multipart/form-data',
            contentType:false,
            processData:false,
            cache:false,
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalAddIngreso','frm_add_ingreso','');
                dao.getDataCuenta(tienda);
            }
            
        })
    }
};
var init = {
    validateIngreso: function(form){
        _gen.validate(form,{
          rules:{
            cantidad : {required: true},
            descripcion : {required: true},
          },
          messages: {
            cantidad : {required: 'Este campo es requerido'},
            descripcion : {required: 'Este campo es requerido'},
          }
        })
    },
   
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
    $('#btnNewIngreso').on('click', function (e) {
        e.preventDefault();
        const modalAddIngreso = new bootstrap.Modal(document.getElementById('modalAddIngreso'));
        modalAddIngreso.show();
    });
    $('#btn_add_ingreso').on('click', function (e) {
        e.preventDefault();
        const tienda = document.getElementById('tiendas');
        init.validateIngreso($('#frm_add_ingreso'));
        if ($('#frm_add_ingreso').valid()) {
            if (tienda && tienda.value == '') {
                Swal.fire({
                    icon:'warning',
                    title:'Advertencia!',
                    text:'Selecciona una tienda',
                });
                return;
            }
            let idT = tienda ? tienda.value : '';
            dao.postAddIngreso(idT);
        }
    });
});