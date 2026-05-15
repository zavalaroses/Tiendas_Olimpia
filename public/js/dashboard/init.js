let dao = {
    getDataDashboard: function () {
        $.ajax({
            url:'/get-data-dashboard',
            type:'GET',
            dataType:'json'
        }).done(function (response) {
            console.log('response', response);
            const { apartados,enCaja,inventario,porEntregar,vendido } = response;
            
            document.getElementById('ventas').innerHTML = vendido.toLocaleString('es-MX', { 
                style: 'currency', 
                currency: 'MXN' 
            })
            document.getElementById('inventario').innerHTML = inventario + ' artículos';
            document.getElementById('apartados').innerHTML = apartados;
            document.getElementById('entregas').innerHTML = porEntregar;
            document.getElementById('caja').innerHTML = enCaja.toLocaleString('es-MX', { 
                style: 'currency', 
                currency: 'MXN' 
            })
        });
    }
};