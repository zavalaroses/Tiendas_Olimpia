dao = {
    getDataSalidas: function (tienda) {
        $.ajax({
            url:'/get-data-salidas-all/'+tienda,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tbl_ventas');
            const columns = [
                {"targets":[0],"mData":'id'},
                {"targets":[1],"mData":'tienda'},
                {"targets":[2],"mData":'mueble'},
                {"targets":[3],"mData":function (o) {
                    if (o.estatus == 'Por entregar') {
                        return `<span class="yellow" style="font-size:0.875rem !important; max-width: 100px;">${o.estatus}</span>`;
                    }else if (o.estatus == 'Entregado') {
                        return `<span class="green" style="font-size:0.875rem !important; max-width: 100px;">${o.estatus}</span>`;
                    } else {
                        return o.estatus;
                    }
                }},
                {"targets":[4],"mData":'cantidad'},
                {"targets":[5],"mData":'cliente'},
                {"targets":[6],"mData":function (o) {
                    const fechaStr = o.fecha_entrega; // "2025-11-11"
                    const [year, month, day] = fechaStr.split('-').map(Number);
                    const fecha_entrega = new Date(year, month - 1, day); // <-- crea fecha local correctamente

                    const today = new Date();

                    // Normalizar ambas fechas
                    fecha_entrega.setHours(0, 0, 0, 0);
                    today.setHours(0, 0, 0, 0);

                    const esMismoDia = (a,b)=>
                        a.getFullYear() === b.getFullYear() &&
                        a.getMonth() === b.getMonth() &&
                        a.getDate() === b.getDate();

                    if (fecha_entrega) {
                        if (esMismoDia(fecha_entrega,today)) {
                            return `<span class="yellow" style="font-size:0.875rem !important; max-width: 100px;">${o.fecha_entrega}</span>`;
                        }else if (fecha_entrega < today) {
                            return `<span class="red" style="font-size:0.875rem !important; max-width: 100px;">${o.fecha_entrega}</span>`;
                        } else {
                            return `<span class="blue" style="font-size:0.875rem !important; max-width: 100px;">${o.fecha_entrega}</span>`;
                        }
                    }
                }},
                {"aTargets": [7], "mData" : function(o){
                    return '<div class="dropdown">'+
                    '<button type="button" class="btn btn-light" data-bs-toggle="dropdown"  aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'+
                        '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">'+
                            '<li onclick="dao.darSalida(' + o.id + ')"><button class="dropdown-item"><i class="fas fa-shipping-fast" style="color: #D48D8D"></i>&nbsp;Dar salida</button></li>'+
                            // '<li onclick="dao.eliminar(' + o.id +','+o.area+')"><button class="dropdown-item"><i class="far fa-trash-alt" style="color: #7C0A20; opacity: 1;"></i>&nbsp;Eliminar</button></li>'+
                        '</ul>'+
                    '</div>';
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response);
        })
    },
    darSalida: function (id) {
        // dao.getChoferes('','chofer_salida');
        $.ajax({
            url:'/get-chofer-info-salida/'+id,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            let {chofer,data} = response;
            document.getElementById('id_salida').value = id;
            document.getElementById('titular').innerText = data.nombre +' '+data.apellidos;
            document.getElementById('contacto').innerText = data.telefono;
            var field = $('#chofer_salida');
            field.html('');
            field.append(new Option('Selecciona una opcion'));
            chofer.map(function(val,i) {
                field.append(new Option(chofer[i].chofer,chofer[i].id,false,false));
            });
            const modalDarSalida = new bootstrap.Modal(document.getElementById('modalDarSalida'));
            modalDarSalida.show();  
        });
    },
    getChoferes: function (id,field,tienda) {
        $.ajax({
            url:'/get-choferes-catalogo/'+tienda,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            if (response && response.icon) {
                Swal.fire({
                    icon:response.icon,
                    title:response.title,
                    text:response.text,
                });
            }
            let select = $('#'+field);
            select.html();
            select.append(new Option('Selecciona un chofer',''));
            response.map(function (val,i) {
                if (id != '' && id == val.id) {
                    select.append(new Option(response[i].chofer,response[i].id,true,true)); 
                }else{
                    select.append(new Option(response[i].chofer,response[i].id,false,false));
                }
                
            })
        })
    },
    getCatMuebles: function (field,id) {
        $.ajax({
            url:'/get-data-muebles',
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
    getPreciosMuebles: function (id) {
        $.ajax({
            url:'/get-precio-by-idMueble/'+id,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            document.getElementById('inpPrecioUnit').value = response;
            document.getElementById('precioUnit').innerHTML = '$ '+response;
        });
    },
    postDarSalida: function () {
        var form = $('#frm_dar_salida')[0];
        var data = new FormData(form);
        const tienda = document.getElementById('tiendas');
        if (tienda) {
            data.append("id_tienda", tienda.value);
        }
        $.ajax({
            url:'/post-agendar-salida',
            type:'post',
            data:data,
            enctype:"multipart/form-data",
            processData:false,
            contentType:false,
            cache:false,
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalPagarAdelanto','frm_pagar_adelanto','');
                let idTienda = tienda ? tienda.value : '';
                dao.getDataSalidas(idTienda);
            }
        })
    },
    addVenta: function () {
        var form = $('#frm_add_venta')[0];
        var data = new FormData(form);
        var tabla = document.getElementById('tbl_producto_venta');
        var tbody = tabla.querySelector('tbody');
        var filas = tbody.querySelectorAll('tr');
        if (filas.length === 0) {
            Swal.fire({
                icon:'warning',
                title:'Advertencia',
                text:'Debes agregar al menos un mueble antes de guardar.',
            });
            return
        }
        filas.forEach(function (fila) {
            var celdas = fila.querySelectorAll('td');
            var id = celdas[0].textContent;
            var nombre = celdas[1].textContent;
            var cantidad = celdas[2].textContent;

            data.append("id[]",id);
            data.append("producto[]",nombre);
            data.append("cantidad[]",cantidad);
        });
        const tienda = document.getElementById('tiendas');
        if (tienda) {
            data.append("id_tienda", tienda.value);
        }
        $.ajax({
            url:'/post-agregar-venta',
            type:'post',
            data:data,
            enctype:"multipart/form-data",
            processData:false,
            contentType:false,
            cache:false,
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalAddVenta','frm_add_venta','tbl_producto_venta');
                let idT = tienda ? tienda.value : '';
                dao.getDataSalidas(idT);
            }
        })
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
init = {
    validateDarSalida: function(form){
        _gen.validate(form,{
          rules:{
            chofer : {required: true},
            fechaSalida : {required: true},
          },
          messages: {
            chofer : {required: 'Este campo es requerido'},
            fechaSalida : {required: 'Este campo es requerido'},
          }
        })
    },
    validateVenta: function (form) {
        _gen.validate(form,{
          rules:{
            nombre : {required: true},
            apellidos : {required: true},
            telefono : {required:true},
            direccion : {required:true},
            chofer : {required:true},
            total : {required:true},
            fecha_envio : {required:true},
            forma_pago : {required:true},
          },
          messages: {
            nombre : {required: 'Este campo es requerido'},
            apellidos : {required: 'Este campo es requerido'},
            telefono: {required:'Este campo es requerido'},
            direccion: {required:'Este campo es requerido'},
            chofer: {required:'Este campo es requerido'},
            total: {required:'Este campo es requerido'},
            fecha_envio: {required:'Este campo es requerido'},
            forma_pago: {required:'Este campo es requerido'},
          }
        })
    }

};
function addListaMuebles() {
    const inputProducto = 'mueble';
    const inputCantidad = 'cantidad';
    const idTabla = 'tbl_producto_venta';
    const precioUnit = 'inpPrecioUnit';
    let total = 0;

    let cantidad = document.getElementById(inputCantidad).value;
    let select = document.getElementById(inputProducto);
    let producto = select.options[select.selectedIndex].text;
    let precio = document.getElementById(precioUnit).value;
    let idProducto = document.getElementById(inputProducto).value;
    total = parseInt(cantidad) * parseFloat(precio);

    var fila = document.createElement('tr');
    var celdaId = document.createElement('td');
    var celdaProducto = document.createElement('td');
    var celdaCantidad = document.createElement('td');
    var celdaPrecio = document.createElement('td');
    var celdaTotal = document.createElement('td');
    var celdaEliminar = document.createElement('td');
    var iconoEliminar = document.createElement('i');
    iconoEliminar.className = "far fa-trash-alt";
    iconoEliminar.style.cursor = "pointer";
    iconoEliminar.dataset.total = total;
    iconoEliminar.addEventListener("click", function () {
        let subTotalFila = parseFloat(this.dataset.total);
        actualizarTotalAlEliminar(subTotalFila);
        fila.remove();
    });
    celdaProducto.textContent = producto;
    celdaCantidad.textContent = cantidad;
    celdaPrecio.textContent = precio;
    celdaTotal.textContent = total;
    celdaId.textContent = idProducto;
    celdaEliminar.appendChild(iconoEliminar);

    fila.appendChild(celdaId);
    fila.appendChild(celdaProducto);
    fila.appendChild(celdaCantidad);
    fila.appendChild(celdaPrecio);
    fila.appendChild(celdaTotal);
    fila.appendChild(celdaEliminar);

    document.getElementById(idTabla).getElementsByTagName('tbody')[0].appendChild(fila);
    $('#'+idTabla).show(true);
    document.getElementById(inputCantidad).value = '';
    document.getElementById(inputProducto).value = '';
    document.getElementById('precioUnit').innerHTML = '';
    calcularTotal(total);

};
function calcularTotal(subTotal) {
    let sub = parseFloat(subTotal);
    let tot = document.getElementById('total').value && parseFloat(document.getElementById('total').value) > 0 ? parseFloat(document.getElementById('total').value) : 0;
    let total = sub + parseFloat(tot);
    document.getElementById('total').value = total;
};
function actualizarTotalAlEliminar(subTotal) {
    let totalActual = parseFloat(document.getElementById('total').value) || 0;
    let newTotal = totalActual - subTotal;
    if (newTotal < 0) newTotal = 0;
    document.getElementById('total').value = newTotal.toFixed(2); 
}
$(document).ready(function () {
    dao.getDataSalidas('');
    $('#btn_agendar_salida').on('click',function (e) {
        e.preventDefault();
        init.validateDarSalida($('#frm_dar_salida'));
        if ($('#frm_dar_salida').valid()) {
            dao.postDarSalida();
        }
    });
    $('#btnNuevaVenta').on('click', function (e) {
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
            dao.getChoferes('','chofer',tienda.value);
        }else{
            dao.getChoferes('','chofer','');
        }
        const modalAddVenta = new bootstrap.Modal(document.getElementById('modalAddVenta'));
        modalAddVenta.show();
    });
    $('#btn_add_mueble').on('click', function (e) {
        e.preventDefault();
        const mueble = document.getElementById('mueble').value;
        const cantidad = document.getElementById('cantidad').value;
        if (mueble && mueble !=='' && cantidad && cantidad >0) {
            addListaMuebles();
        }else{
            Swal.fire({
                icon:'info',
                title:'Datos incompletos',
                text:'Elige un producto y una cantidad valida.',
                allowOutsideClick:true,
                confirmButtonText:'Listo',
            })
        }
    });
    $('#mueble').on('change',function (e) {
        e.preventDefault();
        dao.getPreciosMuebles($(this).val())
    });
    $('#btn_add_venta').on('click', function (e) {
        e.preventDefault();
        init.validateVenta($('#frm_add_venta'));
        if ($('#frm_add_venta').valid()) {
            dao.addVenta();
        }
    });
    $('#tiendas').on('change', function (e) {
        e.preventDefault();
        const tienda = this.options[this.selectedIndex].text;
        document.getElementById('tituto_tienda').innerText = tienda;
        dao.getDataSalidas(this.value);
    });

});