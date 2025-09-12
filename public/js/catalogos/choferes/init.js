dao = {
    getCatTiendas: function () {
        $.ajax({
            url:'/get-catalogo-tiendas',
            type:'get',
            dataType:'json',
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function (response) {
            var select = $('#tienda');
            select.html('');
            select.append(new Option('Selecciona una tienda',''));
            response.map(function (val,i) {
                console.log(response[i]);
                select.append(new Option(response[i].nombre,response[i].id));
            });
        })
    }
};

init = {

};
$(document).ready(function () {
    $('#btnAddAchofer').on('click', function (e) {
        e.preventDefault();
        dao.getCatTiendas();
        const modalAddChofer = new bootstrap.Modal(document.getElementById('modalAddChofer'));
        modalAddChofer.show();
    });
    
});