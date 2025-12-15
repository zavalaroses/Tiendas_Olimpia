dao = {
    getData: function (tienda) {
        $.ajax({
            url:'/get-data-apartados/'+tienda,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            const table = $('#tbl_apartados');
            const columns = [
                {"targets":[0],"mData":'id'},
                {"targets":[1],"mData":'cliente'},
                {"targets":[2],"mData":'mueble'},
                {"targets":[3],"mData":'cantidad'},
                {"targets":[4],"mData":'anticipo'},
                {"targets":[5],"mData":'restante'},
                {"targets":[6],"mData":'fecha_apartado'},
                {"aTargets": [7], "mData" : function(o){
                    return '<div class="dropdown">'+
                    '<button type="button" class="btn btn-light" data-bs-toggle="dropdown"  aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'+
                        '<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2">'+
                            '<li onclick="dao.pagar(' + o.id + ')"><button class="dropdown-item"><i class="fa-solid fa-cash-register" style="color: #1C85AA"></i>&nbsp;Abonar</button></li>'+
                            // '<li onclick="dao.eliminar(' + o.id +','+o.area+')"><button class="dropdown-item"><i class="far fa-trash-alt" style="color: #7C0A20; opacity: 1;"></i>&nbsp;Eliminar</button></li>'+
                        '</ul>'+
                    '</div>';
                }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response);
        });
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
    postAddApartado:function (){
        var form = $('#frm_add_apartado')[0];
        let data = new FormData(form);
        var tabla = document.getElementById('tbl_add_list_apartados');
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
            url:'/post-add-apartado',
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
                closeModal('modalAddApartados','frm_add_apartado','tbl_add_list_apartados');
                let idT = tienda ? tienda.value : '';
                dao.getData(idT);
            }
        });
    },
    pagar: function (id) {
        $.ajax({
            url:'/get-cantidad-restante/'+id,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
        }).done(function (response) {
            document.getElementById('restante').value = response.monto_restante;
            document.getElementById('id_apartado').value = id;
            const modalPagarAdelanto = new bootstrap.Modal(document.getElementById('modalPagarAdelanto'));
            modalPagarAdelanto.show();    
        });
    },
    postAbonar: function () {
      var form = $('#frm_pagar_adelanto')[0];
      var data = new FormData(form);
      const tienda = document.getElementById('tiendas');
        if (tienda) {
            data.append("id_tienda", tienda.value);
        }
      $.ajax({
        url:'/post-pagar-adelanto',
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
            dao.getData(idTienda);
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
    validateApartado: function(form){
        _gen.validate(form,{
          rules:{
            nombre : {required: true},
            apellidos : {required: true},
            telefono:{required:true},
            direccion:{required:true},
            anticipo:{required:true},
            total:{required:true},
            fecha:{required:true},
            forma_pago:{required:true},
          },
          messages: {
            nombre : {required: 'Este campo es requerido'},
            apellidos : {required: 'Este campo es requerido'},
            telefono : {required: 'Este campo es requerido'},
            direccion : {required: 'Este campo es requerido'},
            anticipo : {required: 'Este campo es requerido'},
            total : {required: 'Este campo es requerido'},
            fecha : {required: 'Este campo es requerido'},
            forma_pago : {required: 'Este campo es requerido'},
          }
        })
    },
    validateAdelanto: function (form) {
        _gen.validate(form,{
            rules:{
                adelanto: {required:true},
                forma_pago: {required:true}
            },
            messages: {
                adelanto: {required:'Este campo es requerido'},
                forma_pago: {required:'Este campo es requerido'}
            }
        })
    },
};
function addListaApartados() {
    const inputProducto = 'mueble';
    const inputCantidad = 'cantidad';
    const idTabla = 'tbl_add_list_apartados';
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
function actualizarTotalAlEliminar(subTotal) {
    let totalActual = parseFloat(document.getElementById('total').value) || 0;
    let newTotal = totalActual - subTotal;
    if (newTotal < 0) newTotal = 0;
    document.getElementById('total').value = newTotal.toFixed(2); 
}
function calcularTotal(subTotal) {
    let sub = parseFloat(subTotal);
    let tot = document.getElementById('total').value && parseFloat(document.getElementById('total').value) > 0 ? parseFloat(document.getElementById('total').value) : 0;
    let total = sub + parseFloat(tot);
    document.getElementById('total').value = total;
};
function adelantoIsValid() {
    const total = parseFloat(document.getElementById('total')?.value || 0);
    const adelanto = parseFloat(document.getElementById('anticipo')?.value || 0);
    if (adelanto >= total) {
        return false;
    }else{
        return true;
    }
}
$(document).ready(function () {
    dao.getData('');
    dao.getCatTiendas('tiendas','');
    $('#btnAddApartado').on('click', function (e) {
        e.preventDefault();
        dao.getCatMuebles('mueble','');
        let today = new Date().toLocaleDateString();
        document.getElementById('fecha').value = today;
        const modalAddApartados = new bootstrap.Modal(document.getElementById('modalAddApartados'));
        modalAddApartados.show();
    });
    $('#btn_add_garantia').on('click',function () {
        const mueble = document.getElementById('mueble').value;
        const cantidad = document.getElementById('cantidad').value;
        if (mueble && mueble !=='' && cantidad && cantidad >0) {
            addListaApartados();
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
    $('#btn_add_apartado').on('click', function (e) {
        e.preventDefault();
        init.validateApartado($('#frm_add_apartado'));
        if ($('#frm_add_apartado').valid()) {
            let valid = adelantoIsValid();
            if (valid) {
                dao.postAddApartado();    
            }else{
                Swal.fire({
                icon:'info',
                title:'Montos erroneos',
                text:'El anticipo no puede ser igual o mayor al total.',
                allowOutsideClick:true,
                confirmButtonText:'Listo',
            })
            }
            
        }
    });
    $('#btn_add_pago').on('click',function (e) {
        e.preventDefault();
        init.validateAdelanto($('#frm_pagar_adelanto'));
        if ($('#frm_pagar_adelanto').valid()) {
            dao.postAbonar();
        }
    });
    $('#tiendas').on('change', function (e) {
        e.preventDefault();
        const tienda = this.options[this.selectedIndex].text;
        document.getElementById('tituto_tienda').innerText = tienda;
        dao.getData(this.value);
    });
    
});