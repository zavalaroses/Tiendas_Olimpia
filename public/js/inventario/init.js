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
    }

};

init = {

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
    
});