dao = {
};

init = {

};
$(document).ready(function () {
    console.log('init.js');
    $('#btnAddAchofer').on('click', function (e) {
        e.preventDefault();
        console.log('accion del boton')
        $('#modalAddChofer').modal('show');
    });
    
});