dao = {
    getData: function () {
        $.ajax({
            url: "get-data-usuarios",
            type: "get",
            dataType: "JSON",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        }).done(function (response) {
            const table = $("#tbl_users");
            const columns = [
                { targets: [0], mData: "id" },
                {
                    targets: [1],
                    mData: function (o) {
                        let nombre = o.name + " " + o.apellidos;
                        return nombre;
                    },
                },
                {
                    targets: [2],
                    mData: function (o) {
                        let tienda = o.tienda ? o.tienda : "";
                        return tienda;
                    },
                },
                {
                    targets: [3],
                    mData: function (o) {
                        let rol = o.rol ? o.rol : "";
                        return rol;
                    },
                },
                { targets: [4], mData: "email" },
                { targets: [5], mData: "ingreso" },
                {
                    targets: [6],
                    mData: function (o) {
                        return "Sin acciones";
                    },
                },
            ];
            _gen.setTableScrollEspecial2(table, columns, response);
        });
    },
    getCatTiendas: function (field, id) {
        $.ajax({
            url: "/get-catalogo-tiendas",
            type: "get",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        }).done(function (response) {
            var select = $("#" + field);
            select.html("");
            select.append(new Option("Selecciona una tienda", ""));
            response.map(function (val, i) {
                if (id != "" && id == val.id) {
                    select.append(
                        new Option(
                            response[i].nombre,
                            response[i].id,
                            true,
                            true,
                        ),
                    );
                } else {
                    select.append(
                        new Option(
                            response[i].nombre,
                            response[i].id,
                            false,
                            false,
                        ),
                    );
                }
            });
        });
    },
    getRoles: function (field, id) {
        $.ajax({
            url: "/get-catalogo-roles",
            type: "get",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        }).done(function (response) {
            var select = $("#" + field);
            select.html("");
            select.append(new Option("Selecciona un rol", ""));
            response.map(function (val, i) {
                if (id != "" && id == val.id) {
                    select.append(
                        new Option(
                            response[i].nombre,
                            response[i].id,
                            true,
                            true,
                        ),
                    );
                } else {
                    select.append(
                        new Option(
                            response[i].nombre,
                            response[i].id,
                            false,
                            false,
                        ),
                    );
                }
            });
        });
    },
    registrarUsuario: function (pass) {
        var form = $("#frm_add_user")[0];
        var data = new FormData(form);
        data.append("password", pass);
        var urlRegistro = "/register-user";
        $.ajax({
            type: "post",
            url: urlRegistro,
            data: data,
            enctype: "multipart/form-data",
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        }).done(function (params) {
            Swal.fire({
                icon: "success",
                title: "Usuario creado con exito",
                text: "Guarda esta contraseña: " + params + " para ingresar",
                allowOutsideClick: false,
                showDenyButton: false,
                showCancelButton: false,
                confirmButtonText: "Listo",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    closeModal("modalAddUser", "frm_add_user", "");
                    dao.getData();
                }
            });
        });
    },
    getDataComisionesActivas: function () {
        $.ajax({
            url: "/get-data-comisiones-activas",
            type: "get",
            dataType: "JSON",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        }).done(function (response) {
            const table = $("#tbl_comisiones");
            const columns = [
                { targets: [0], mData: "usuario" },
                { targets: [1], mData: "tienda" },
                { targets: [2], mData: "entregas" },
                { targets: [3], mData: "vendido" },
                { targets: [4], mData: "comision" },
                {
                    targets: [5],
                    mData: function (o) {
                        if (o.estatus === "Pagado") {
                            return `<span class="badge-premium badge-pagado"><i class="fa-solid fa-circle-check me-1"></i> Pagado </span>`;
                        } else {
                            return `<span class="badge-premium badge-pendiente"><i class="fa-solid fa-clock me-1"></i> Pendiente </span>`;
                            // return `<span class="badge bg-danger">${o.estatus}</span>`;
                        }
                    },
                },
                {
                    targets: [6],
                    mData: function (o) {
                        if (o.estatus === "Pagado") {
                            return `
            <button class="btn" onclick="dao.verDetalle(${o.usuario_id})">
              <i class="fa fa-eye" style="color: #D48D8D"></i>
            </button>`;
                        } else {
                            return `
            <button class="btn" onclick="dao.verDetalle(${o.usuario_id})">
              <i class="fa fa-eye" style="color: #D48D8D"></i>
            </button>
            <button class="btn" onclick="dao.marcarComoPagado(${o.usuario_id})">
              <i class="fa-solid fa-comment-dollar" style="color:#7C0A20"></i>
            </button>`;
                        }
                    },
                },
            ];
            _gen.setTableScrollEspecial2(table, columns, response.data);
        });
    },
    getResumenComisiones: function () {
        $.ajax({
            url: "/get-resumen-comisiones",
            type: "get",
            dataType: "JSON",
            beforeSend: function () {
                $("#totalComisionSemana").html(
                    '<span class="spinner-border spinner-border-sm"></span>',
                );
                $("#vendedorasActivas").html(
                    '<span class="spinner-border spinner-border-sm"></span>',
                );
                $("#totalVendido").html(
                    '<span class="spinner-border spinner-border-sm"></span>',
                );
                $("#pendientePago").html(
                    '<span class="spinner-border spinner-border-sm"></span>',
                );
            },
        })
            .done(function (response) {
                console.log("🚀 ~ response:", response);
                const {
                    comision_total,
                    vendedores_activos,
                    total_vendido,
                    pago_pendiente,
                    inicio_semana,
                    fin_semana,
                } = response;
                const moneyFormatter = new Intl.NumberFormat("es-MX", {
                    style: "currency",
                    currency: "MXN",
                });

                $("#totalComisionSemana").text(
                    moneyFormatter.format(comision_total ?? 0),
                );
                $("#vendedorasActivas").text(vendedores_activos ?? 0);
                $("#totalVendido").text(
                    moneyFormatter.format(total_vendido ?? 0),
                );
                $("#pendientePago").text(
                    moneyFormatter.format(pago_pendiente ?? 0),
                );
                // Semana bonita
                if (inicio_semana && fin_semana) {
                    const inicio = new Date(inicio_semana);
                    const fin = new Date(fin_semana);
                    const options = { day: "numeric", month: "long" };
                    const rangoSemana = `${inicio.toLocaleDateString("es-MX", options)} - ${fin.toLocaleDateString("es-MX", options)}`;
                    $("#lblSemanaComision").text(rangoSemana);
                }
            })
            .fail(function (xhr) {
                console.error(xhr);
                $("#lblSemanaComision").text("Error al cargar");
                $("#totalComisionSemana").text("$0.00");
                $("#vendedorasActivas").text("0");
                $("#totalVendido").text("$0.00");
                $("#pendientePago").text("$0.00");
            });
    },
    marcarComoPagado: function (id) {
        Swal.fire({
            icon: "question",
            title: "¿Marcar comisión como pagada?",
            text: "Se marcarán como pagadas todas las comisiones pendientes de esta semana para este vendedor.",
            showCancelButton: true,
            confirmButtonColor: "#786666",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sí, pagar",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/pagar-comision-semanal",
                    type: "post",
                    data: { id: id },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },
                }).done(function (response) {
                    Swal.fire({
                        icon: response.icon,
                        title: response.title,
                        text: response.text,
                    });
                    if (response.icon === "success") {
                        dao.getDataComisionesActivas();
                        dao.getResumenComisiones();
                    }
                });
            }
        });
    },
    verDetalle: function (usuarioId) {
        $.ajax({
            url: `/get-detalle-comision/${usuarioId}`,
            type: "GET",
            dataType: "JSON",
            beforeSend: function () {
              $("#dc_detalle").html(`
                <tr>
                    <td colspan="6"
                        class="text-center py-4">

                        <div class="spinner-border
                            text-secondary">
                        </div>

                    </td>
                </tr>
              `);
              const modal = new bootstrap.Modal(document.getElementById('modalVerComisiones'));
              modal.show();
            },
        }).done(function (response) {
          console.log("detalle comisión", response);
          let rows = "";
          let totalComision = 0;
          const formatter = new Intl.NumberFormat("es-MX", {style: "currency",currency: "MXN",});

          const detalle = response.data ?? [];

          if (detalle.length > 0) {
              $("#dc_usuario").text(detalle[0].usuario ?? "Sin usuario");
              $("#dc_usuario_resumen").text(detalle[0].usuario ?? "-");
              $("#dc_tienda").text(detalle[0].tienda ?? "-");
              $("#dc_entregas").text(detalle.length);
              detalle.forEach((item) => {
                  totalComision += parseFloat(item.monto_comision);

                  rows += `
              <tr>

                  <td>
                      ${item.fecha_entrega}
                  </td>

                  <td>
                      ${item.folio ?? "-"}
                  </td>

                  <td>
                      ${item.cliente ?? "-"}
                  </td>

                  <td class="fw-bold">
                      ${formatter.format(item.monto_venta)}
                  </td>

                  <td class="
                      text-success
                      fw-bold
                  ">
                      ${formatter.format(item.monto_comision)}
                  </td>
              </tr>
          `;
              });
          } else {
              rows = `
          <tr>
              <td colspan="6"
                  class="text-center
                  text-muted py-4">

                  Sin ventas registradas

              </td>
          </tr>
      `;
          }
          $("#dc_detalle").html(rows);
          $("#dc_total_comision").text(formatter.format(totalComision));
          $("#dc_footer_total").text(formatter.format(totalComision));
      })
      .fail(function (xhr) {
          console.error(xhr);

          Swal.fire({
              icon: "error",
              title: "Error",
              text: "No fue posible cargar el detalle.",
          });
      });
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