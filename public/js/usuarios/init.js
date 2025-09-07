dao = {
    getData: function () {
      $.ajax({
        url:'get-data-usuarios',
        type:'get',
        dataType:'JSON',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      }).done(function (response) {
        console.log('response',response);
        const table = $('#tbl_users');
        const columns = [
          {"targets": [0],"mData":'id'},
          {"targets": [1],"mData":function (o) {
            let nombre = o.name+' '+o.apellidos
            return nombre;
          }},
          {"targets": [2],"mData":function (o) {
            let tienda = o.tienda ? o.tienda : '';
            return tienda;
          }},
          {"targets": [3],"mData":function (o) {
            let rol = o.rol ? o.rol : '';
            return rol;
          }},
          {"targets": [4],"mData":'email'},
          {"targets": [5],"mData":'ingreso'},
          {"targets": [6],"mData":function (o) {
            return 'Sin acciones';
          }},
        ];
        _gen.setTableScrollEspecial2(table,columns,response)
      });
    },
};

init = {

};
$(document).ready(function () {
    console.log('init.js');
    $('#btnAddUser').on('click', function (e) {
        e.preventDefault();
        $('#modalAddUser').modal('show');
    });
    
});