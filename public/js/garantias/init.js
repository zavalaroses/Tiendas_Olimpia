dao = {
};

init = {

};
$(document).ready(function () {
    console.log('init.js');
    $('#btnAddGarantia').on('click', function (e) {
        e.preventDefault();
        console.log('accion del boton')
        $('#modalAddGarantia').modal('show');
    });
    
});