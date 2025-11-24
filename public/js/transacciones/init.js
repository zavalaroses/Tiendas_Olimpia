
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
    getDataCerrarCorte: function (tienda) {
        $.ajax({
            url:'/get-resumen-corte/'+tienda,
            dataType:'json',
            type:'get',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            console.log("🚀 ~ response:", response)
            // Asegurar que todo llegue como número
            const efectivo      = Number(response.efectivo) || 0;
            const cuenta        = Number(response.cuenta) || 0;
            const egresosEf     = Number(response.egresosEfectivo) || 0;
            const egresosCt     = Number(response.egresosCuenta) || 0;
            const totalEf       = Number(response.totalEfectivo) || 0;
            const apertura       = Number(response.apertura) || 0;

            // Llenamos el modal
            $("#corte_apertura").text(`$ ${apertura.toFixed(2)}`);
            $("#corte_ingresos_efectivo").text(`$ ${efectivo.toFixed(2)}`);
            $("#corte_ingresos_tarjeta").text(`$ ${cuenta.toFixed(2)}`);
            $("#corte_salidas").text(`$ ${(egresosEf).toFixed(2)}`);
            $("#corte_efectivo_esperado").text(`$ ${totalEf.toFixed(2)}`);
            $('#input_total').val(totalEf);

            // Reiniciar campos del usuario
            $("#efectivo_contado").val("");
            $("#observaciones_corte").val("");
            $("#corte_diferencia").text("$ 0.00");
            const modalCerrarCorte = new bootstrap.Modal(document.getElementById('modalCerrarCorte'));
            modalCerrarCorte.show();
        });
    },
    cerrarCorte: function (values) {
        var form = $('#frm_cierre_corte')[0];
        var data = new FormData(form);
        Object.keys(values).forEach(key =>{
            data.append(key,values[key]);
        });

        $.ajax({
            url:'/cerrar-corte',
            type:'post',
            data:data,
            enctype:'multipart/form-data',
            contentType:false,
            processData:false,
            cache:false,
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const tienda = document.getElementById('tiendas');
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalCerrarCorte','frm_cierre_corte','');
                let idT = tienda ? tienda.value : '';
                dao.getData(idT);
                dao.getResumenCorte(idT);
            }
        });

    }


};
var init = {
    validateCerrarCorte: function(form){
        _gen.validate(form,{
          rules:{
            efectivo_contado : {required: true},
          },
          messages: {
            chofer : {required: 'Este campo es requerido'},
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
        dao.getData(this.value);
        dao.getResumenCorte(this.value);
    });
    $('#btnCerrarCorte').on('click',function (e) {
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
            dao.getDataCerrarCorte(tienda.value);
        }else{
            dao.getDataCerrarCorte();
        }
    });
    $("#efectivo_contado").on("input", function () {
        const contado = Number($(this).val()) || 0;
        let totalEfectivo = Number($('#input_total').val()) || 0;
        const diferencia = contado - totalEfectivo;

        $("#corte_diferencia").text("$ " + diferencia.toFixed(2));

        if (diferencia === 0) {
            $("#corte_diferencia").removeClass("text-danger").addClass("text-success");
        } else {
            $("#corte_diferencia").removeClass("text-success").addClass("text-danger");
        }
    });
    $('#btn_cerrar_corte').on('click',function (e) {
        e.preventDefault();
        init.validateCerrarCorte($('#frm_cierre_corte'));
        if ($('#frm_cierre_corte').valid()) {
            
            let dataValues = {
                tienda_id: $("#tiendas") ? $("#tiendas").val() : '',
                apertura: Number($("#corte_apertura").text().replace(/[$,\s]/g, "")) || 0,
                ingresos_efectivo: Number($("#corte_ingresos_efectivo").text().replace(/[$,\s]/g, "")) || 0,
                ingresos_tarjeta: Number($("#corte_ingresos_tarjeta").text().replace(/[$,\s]/g, "")) || 0,
                salidas: Number($("#corte_salidas").text().replace(/[$,\s]/g, "")) || 0,
                efectivo_esperado: Number($('#input_total').val()),
                corte_diferencia: Number($("#corte_diferencia").text().replace(/[$,\s]/g, "")) || 0,
                observaciones: $("#observaciones_corte").val()
            };
            dao.cerrarCorte(dataValues);
        }
    });

    
});