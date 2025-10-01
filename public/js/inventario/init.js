dao = {
};

init = {

};
$(document).ready(function () {
    
    $('#btnAddInventario').on('click', function (e) {
        e.preventDefault();
        const modalAddEntrada = new bootstrap.Modal(document.getElementById('modalAddEntrada'));
        modalAddEntrada.show();
    });
    
});