dao = {
    getDataSalidas: function () {
        $.ajax({
            url:'/get-data-salidas-all',
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
                    const fecha_entrega = new Date(o.fecha_entrega);
                    const today = new Date();
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
        $.ajax({
            url:'/get-chofer-info-salida/'+id,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            let {chofer,data} = response;
            document.getElementById('titular').innerText = data.nombre +' '+data.apellidos;
            document.getElementById('telefono').innerText = data.telefono;
            var field = $('#chofer');
            field.html('');
            field.append(new Option('Selecciona una opcion'));
            chofer.map(function(val,i) {
                field.append(new Option(chofer[i].chofer,chofer[i].id,false,false));
            });
            const modalDarSalida = new bootstrap.Modal(document.getElementById('modalDarSalida'));
            modalDarSalida.show();  
        });
    },
    getChoferes: function (id,field) {
        $.ajax({
            url:'/get-choferes-catalogo',
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            console.log("🚀 ~ response:", response)
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
};
init = {

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
    iconoEliminar.addEventListener("click", function () {
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
$(document).ready(function () {
    dao.getDataSalidas();
    $('#btn_agendar_salida').on('click',function (e) {
        e.preventDefault();
        init.validateDarSalida($('#frm_dar_salida'));
        if ($('#frm_dar_salida').valid()) {
            dao.postDarSalida();
        }
    });
    $('#btnNuevaVenta').on('click', function (e) {
        e.preventDefault();
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

});