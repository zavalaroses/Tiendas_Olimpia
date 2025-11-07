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
            console.log("🚀 ~ response:", response)
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
    }
};
init = {

};
$(document).ready(function () {
   dao.getDataSalidas();

});