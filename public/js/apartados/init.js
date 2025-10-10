dao = {
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
            console.log("🚀 ~ response:", response)
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalAddApartados','frm_add_apartado');
                // dao.getData();
            }
        });
    }
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
          },
          messages: {
            nombre : {required: 'Este campo es requerido'},
            apellidos : {required: 'Este campo es requerido'},
            telefono : {required: 'Este campo es requerido'},
            direccion : {required: 'Este campo es requerido'},
            anticipo : {required: 'Este campo es requerido'},
            total : {required: 'Este campo es requerido'},
            fecha : {required: 'Este campo es requerido'},
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
            dao.postAddApartado();
        }
    });
    
});