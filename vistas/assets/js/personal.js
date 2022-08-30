function v_personal() {
    TablaPersonal();

    $('#FormEmpleados').validate({
        rules: {
            NombreEmpleado: {
                required: true
            },
            CelularEmpleado:{
                required: true
            },
        },
        messages: {
            NombreEmpleado: {
                required: "El nombre del empleado es obligatorio"
            },
            CelularEmpleado:{
                required: "El celular del empleado es obligatorio"
            },
        },
        submitHandler: function(form) { 
            var data = new FormData(document.getElementById("FormEmpleados"));
            data.append("metodo", $("#GuardarEmpleado").attr("tipo"));
            data.append("accion", "personal");
            data.append("idEmpleado", $("#GuardarEmpleado").attr("attrid"));

            var btn = $('#GuardarEmpleado');
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    progressBoton(btn);
                }
            })
            .done(function(res) {
                var datos = $.trim(res).split("~");
                if ($.trim(datos[0]) == "Correcto") {
                    $("#ModalEmpleados").modal("hide");
                    var footer = "";
                    if ($("#GuardarEmpleado").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }

                    if ($.trim(datos[1]) == "Error 2 Formato") {
                        footer = "El formato de la imagen es incorrecto";
                    }else if ($.trim(datos[1]) == "Error 3 Peso") {
                        footer = "La imagen debe de pesar menos de 10MB";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Empleado '+tipoAlerta+' correctamente',
                        footer: footer
                    });
                    TablaPersonal();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarEmpleado").attr("tipo")+' empleado.'
                    });
                    console.log($.trim(res));
                }
            })
            .fail(function() {
                console.log("Error ajax");
            })
            .always(function() {
                unprogressBoton(btn);
            });            
        }
    });    
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevoEmpleado', function() {
        ConsultarPuestos();
        ConsultarAreas();
        $("#GuardarEmpleado").attr('tipo', "insertar");
        $("#GuardarEmpleado").attr('attrid', "");
        $("#verfotoEmpleado img").attr('src', 'vistas/assets/archivos/fotosClientes/default.jpg');
        $("#FormEmpleados").trigger('reset');
        $("#TituloModalEmpleados").text("Agregar nuevo");
    });

    $(document).on('click', '#verfotoEmpleado', function() {
        $("#FotoEmpleado").trigger("click");
    });

    $(document).on('change', '#FotoEmpleado', function() {
        readURL(this, $("#verfotoEmpleado"));
    });

    $(document).on('click', '.bVerDetallesCliente', function() {
        var nombre = $(this).attr('nombre');
        var data = "metodo=consultar&accion=clientes&id="+$(this).attr('attrid')+"&tipo=DetallesCliente";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            Swal.fire({
                title: 'Detalles del cliente '+nombre,
                html: $.trim(res)
            });
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '#EliminarCliente', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        Swal.fire({
          title: '¿Estás a punto de eliminar al cliente '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=clientes&IDCliente="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaPersonal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente eliminado correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar cliente.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarCliente', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=clientes&IDCliente="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            $("#GuardarEmpleado").attr('tipo', 'modificar');
            $("#GuardarEmpleado").attr('attrid', id);
            var datos = JSON.parse($.trim(res));
            $("#NombreCliente").val(datos.Nombre);
            $("#TelefonoCliente").val(datos.Telefono);
            $("#CelularCliente").val(datos.Celular);
            $("#CorreoCliente").val(datos.Correo);
            $("#FechaNacimientoCliente").val(datos.Fecha_Nacimiento);
            $("#SexoCliente").val(datos.Sexo);
            $("#DireccionCliente").val(datos.Direccion);
            $("#CPCliente").val(datos.Codigo_Postal);
            $("#ColoniaCliente").val(datos.Colonia);
            $("#CiudadCliente").val(datos.Ciudad);
            $("#EstadoCliente").val(datos.Estado);
            $("#PaisCliente").val(datos.Pais);
            $("#TipoDescuentoCliente").val(datos.Tipo_Descuento);
            $("#TipoDescuentoCliente").trigger("change");
            $("#DescuentoCliente").val(datos.Descuento);
            $("#DescuentoCliente").trigger("keyup");
            $("#RFCCliente").val(datos.RFC);
            $("#NombreEmpresaCliente").val(datos.Empresa);
            $("#TitularBancoCliente").val(datos.Titular);
            $("#BancoCliente").val(datos.Banco);
            $("#CuentaBancoCliente").val(datos.No_Cuenta);

            if (datos.Foto != "") {
                $("#verfotoEmpleado img").attr('src', 'vistas/assets/archivos/fotosClientes/'+datos.Foto);
            }else{
                $("#verfotoEmpleado img").attr('src', 'vistas/assets/archivos/fotosClientes/default.jpg');
            }
            $("#ModalEmpleados").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

});

function TablaPersonal(){
    ajaxMyDatatable({
        "table": $("#TablaPersonal"), 
        "colums": [
            "Fecha",
            "Empleado",
            "Direccion",
            "Contacto",
            "Acciones",
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "personal"
        }
    });
}

function ConsultarPuestos(){
    var data = "metodo=detalles&accion=personal&tipo=ConsultarPuestos";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        $("#PuestoEmpleado").html(res);
    })
    .fail(function() {
        console.log("Error ajax");
    });
}

function ConsultarAreas(){
    var data = "metodo=detalles&accion=personal&tipo=ConsultarAreas";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        $("#AreasEmpleado").html(res);
    })
    .fail(function() {
        console.log("Error ajax");
    });
}