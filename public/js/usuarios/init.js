dao = {
  getData: function () {
    $.ajax({
      url:'get-data-usuarios',
      type:'get',
      dataType:'JSON',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    }).done(function (response) {
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
  getCatTiendas: function (field,id) {
      $.ajax({
          url:'/get-catalogo-tiendas',
          type:'get',
          dataType:'json',
          headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      }).done(function (response) {
          var select = $('#'+field);
          select.html('');
          select.append(new Option('Selecciona una tienda',''));
          response.map(function (val,i) {
              if (id !='' && id == val.id) {
                  select.append(new Option(response[i].nombre,response[i].id, true, true));
              }else{
                  select.append(new Option(response[i].nombre,response[i].id, false,false));
              }
          });
      })
  },
  getRoles: function (field,id) {
      $.ajax({
          url:'/get-catalogo-roles',
          type:'get',
          dataType:'json',
          headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      }).done(function (response) {
          var select = $('#'+field);
          select.html('');
          select.append(new Option('Selecciona un rol',''));
          response.map(function (val,i) {
              if (id !='' && id == val.id) {
                  select.append(new Option(response[i].nombre,response[i].id, true, true));
              }else{
                  select.append(new Option(response[i].nombre,response[i].id, false,false));
              }
          });
      })
  },
  registrarUsuario: function (pass) {
    var form = $('#frm_add_user')[0];
    var data = new FormData(form);
    data.append('password', pass);
    var urlRegistro = "/register-user";
    $.ajax({
      type:'post',
      url:urlRegistro,
      data:data,
      enctype:'multipart/form-data',
      processData:false,
      contentType:false,
      cache:false,
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done( function (params) {
      Swal.fire({
        icon: 'success',
        title: 'Usuario creado con exito',
        text: 'Guarda esta contraseña: '+params+' para ingresar',
        allowOutsideClick: false,
        showDenyButton: false,
        showCancelButton: false,
        confirmButtonText: "Listo",
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            closeModal('modalAddUser','frm_add_user','');
            dao.getData();
        } 
      });
    })
  },
};
function generarPassword(longitud) {
    // Define los caracteres permitidos en la contraseña /get-users
    var caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+";

    var password = "";
    for (var i = 0; i < longitud; i++) {
        // Selecciona un carácter aleatorio del conjunto de caracteres
        var caracterAleatorio = caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        // Agrega el carácter aleatorio a la contraseña
        password += caracterAleatorio;
    }
    return password;
}

init = {
  validateUsuario: function (form) {
        _gen.validate(form,{
          rules:{
            name : {required: true},
            apellidos : {required: true},
            tienda : {required:true},
            email : {required:true},
            rol : {required:true},
          
          },
          messages: {
            name : {required: 'Este campo es requerido'},
            apellidos : {required: 'Este campo es requerido'},
            tienda: {required:'Este campo es requerido'},
            email: {required:'Este campo es requerido'},
            rol: {required:'Este campo es requerido'},
            
          }
        })
    }

};
$(document).ready(function () {
    console.log('init.js');
    $('#btnAddUser').on('click', function (e) {
        e.preventDefault();
        dao.getCatTiendas('tienda','');
        dao.getRoles('rol','');
        const modalAddUser = new bootstrap.Modal(document.getElementById('modalAddUser'));
        modalAddUser.show();
        // $('#modalAddUser').modal('show');
    });
    $('#btn_add_user').on('click', function (e) {
      e.preventDefault();
      init.validateUsuario($('#frm_add_user'));
      if ($('#frm_add_user').valid()) {
        var nuevaPassword = generarPassword(10);
        if (nuevaPassword) {
          dao.registrarUsuario(nuevaPassword);   
        }
          
      }
    })
    
});