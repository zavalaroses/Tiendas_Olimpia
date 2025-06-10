dao = {
};

init = {

};
$(document).ready(function () {
    console.log('init.js apartados');
    $('#btnAddApartado').on('click', function (e) {
        e.preventDefault();
        $('#modalAddApartados').modal('show');
    });
    
});