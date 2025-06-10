dao = {
};

init = {

};
$(document).ready(function () {
    console.log('init.js');
    $('#btnAddInventario').on('click', function (e) {
        e.preventDefault();
        console.log('accion del boton')
        $('#modalAddEntrada').modal('show');
    });
    
});