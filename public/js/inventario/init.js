dao = {
    getCatProveedores:function (field,id) {
        $.ajax({
            url:'/get-data-cat-proveedores',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            var select = $('#'+field);
            select.html('');
            select.append(new Option('Selecciona una opcion',''));
            response.map(function (val,i) {
                if (id !='' && id == val.id) {
                    select.append(new Option(response[i].nombre,response[i].id, true, true));
                }else{
                    select.append(new Option(response[i].nombre,response[i].id, false,false));
                }
            });
        })
    },
    getCatMuebles:function (field,id) {
        $.ajax({
            url:'/get-data-muebles',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            var select = $('#'+field);
            select.html('');
            select.append(new Option('Selecciona una opcion',''));
            response.map(function (val,i) {
                if (id !='' && id == val.id) {
                    select.append(new Option(response[i].nombre,response[i].id, true, true));
                }else{
                    select.append(new Option(response[i].nombre,response[i].id, false,false));
                }
            });
        })
    },
    registrarEntrada: function () {
        var form = $('#frm_add_entrada')[0];
        var data = new FormData(form);
        var tabla = document.getElementById('tbl_lista_entrada');
        // obtener el cuerpo de la tabla...
        var tbody = tabla.querySelector("tbody");
        // obtener todas las filas del cuerpo de la tabla...
        var filas = tbody.querySelectorAll("tr");
        // Validar que haya al menos un registro
        if (filas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Debes agregar al menos una entrada antes de guardar.',
            });
            return;
        }
        // recorrer todas las filas de la tabla...
        filas.forEach(function (fila) {
            var celdas = fila.querySelectorAll("td");
            // obtener los valores de las celdas
            var id = celdas[0].textContent;
            var nombre = celdas[1].textContent;
            var cantidad = celdas[2].textContent;
            // agregar los valores al formData...
            data.append("id[]",id);
            data.append("nombre[]",nombre);
            data.append("cantidad[]",cantidad);
        });
        $.ajax({
            url:'/post-add-entrada',
            type:'post',
            data:data,
            enctype:'multipart/form-data',
            processData:false,
            contentType:false,
            cache:false,
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function name(response) {
            Swal.fire({
                icon:response.icon,
                title:response.title,
                text:response.text,
            });
            if (response.icon == 'success') {
                closeModal('modalAddEntrada','frm_add_entrada');
                dao.gatData();
            }
        });
    },
    getData: function ($tiendaId) {
        $.ajax({
            url:'/get-data-inventario/'+$tiendaId,
            type:'get',
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-tiken"]').attr('content')},
        }).done(function (response) {
            const table = $('#tbl_inventarios');
            const columns = [
                {"targets": [0],"mData":'id'},
                {"targets": [1],"mData":'tienda'},
                {"targets": [2],"mData":'mueble'},
                {"targets": [3],"mData":'estatus'},
                {"targets": [4],"mData":'cantidad'},
                // {"aTargets": [5], "mData" : function(o){
                //     return '<div class="dropdown">'+
                //     '<button type="button" class="btn btn-light" data-bs-toggle="dropdown"  aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'+
                //         '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">'+
                //             '<li onclick="dao.editar(' + o.id + ')"><button class="dropdown-item"><i class="fas fa-pencil-alt" style="color: #1C85AA"></i>&nbsp;Editar</button></li>'+
                //             '<li onclick="dao.eliminar(' + o.id +','+o.area+')"><button class="dropdown-item"><i class="far fa-trash-alt" style="color: #7C0A20; opacity: 1;"></i>&nbsp;Eliminar</button></li>'+
                //         '</ul>'+
                //     '</div>';
                // }},
            ];
            _gen.setTableScrollEspecial2(table,columns,response)
        })
    }

};

init = {
    validateEntrada: function(form){
        _gen.validate(form,{
          rules:{
            proveedor : {required: true},
            fecha_ingreso : {required: true},
          },
          messages: {
            proveedor : {required: 'Este campo es requerido'},
            fecha_ingreso : {required: 'Este campo es requerido'},
          }
        })
    },
};
function addListaMubles() {
    
    let inputProducto = 'producto';
    let intputCAntidad = 'cantidad';

    let idTabla = 'tbl_lista_entrada';

    // obtener los valores para el set...
    let cantidad = document.getElementById(intputCAntidad).value;
    let select = document.getElementById(inputProducto);
    let producto = select.options[select.selectedIndex].text;
    // let precio = document.getElementById(inputPrecio).value;
    let idProducto = document.getElementById(inputProducto).value;
    // total = parseInt(cantidad) * parseFloat(precio);
    // crear una nueva fila y celdas...
    var fila = document.createElement('tr');
    var celdaId = document.createElement('td');
    var celdaProducto = document.createElement('td')
    var celdaCantidad = document.createElement('td');
    // var celdaPrecio = document.createElement('td');
    // var celdaTotal = document.createElement('td');
    var celdaEliminar = document.createElement('td');
    var iconoEliminar = document.createElement('i');
    iconoEliminar.className = "far fa-trash-alt";
    iconoEliminar.style.cursor = "pointer";
    iconoEliminar.addEventListener("click", function() {
        fila.remove(); // Elimina la fila al hacer clic en el icono de eliminar
    });
    celdaProducto.textContent = producto;
    celdaCantidad.textContent = cantidad;
    // celdaPrecio.textContent = precio;
    // celdaTotal.textContent  = total;
    celdaId.textContent = idProducto;
    celdaEliminar.appendChild(iconoEliminar);

    // agregar las celdas a la fila...
    fila.appendChild(celdaId);
    fila.appendChild(celdaProducto);
    fila.appendChild(celdaCantidad);
    // fila.appendChild(celdaPrecio);
    // fila.appendChild(celdaTotal);
    fila.appendChild(celdaEliminar);
    // agregar fila a la tabla...
    document.getElementById(idTabla).getElementsByTagName('tbody')[0].appendChild(fila);
    $('#'+idTabla).show(true);
    document.getElementById(intputCAntidad).value = '';
    document.getElementById(inputProducto).value = '';

}
$(document).ready(function () {
    dao.getData('');
    $('#btnAddInventario').on('click', function (e) {
        e.preventDefault();
        dao.getCatProveedores('proveedor','');
        dao.getCatMuebles('producto','')
        const modalAddEntrada = new bootstrap.Modal(document.getElementById('modalAddEntrada'));
        modalAddEntrada.show();
    });
    $('#btnAddListEntrada').on('click',function () {
        let mueble = document.getElementById('producto').value;
        let cantidad = document.getElementById('cantidad').value;
        if (mueble && mueble !== '' && cantidad && cantidad >0) {
         addListaMubles();
        }else{
         Swal.fire({
             icon: 'info',
             title: 'Datos incompletos',
             text: 'Elige un producto y una cantidad valida.',
             allowOutsideClick: true,
             confirmButtonText: "Listo",
         });
        }
        
     });
     $('#btn_add_entrada').on('click',function (e) {
        e.preventDefault();
        init.validateEntrada($('#frm_add_entrada'));
        if ($('#frm_add_entrada').valid()) {
            dao.registrarEntrada();   
        }
     });
    
});