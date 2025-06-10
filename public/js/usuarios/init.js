dao = {
};

init = {

};
$(document).ready(function () {
    console.log('init.js');
    $('#btnAddUser').on('click', function (e) {
        e.preventDefault();
        console.log('entro en la funcion');
        $('#modalAddUser').modal('show');
    });
    
});