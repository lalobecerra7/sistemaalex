function v_clientes() {
    TablaClientes();

    $('#FormClientes').validate({
        rules: {
            NombreCliente: {
                required: true
            },
            TelefonoCliente:{
                required: true
            },
            DireccionCliente:{
                required: true
            },
        },
        messages: {
            NombreCliente: {
                required: "El nombre del cliente es obligatorio"
            },
            TelefonoCliente:{
                required: "El teléfono del cliente es obligatorio"
            },
            DireccionCliente:{
                required: "La dirección del cliente es obligatoria"
            },
        },
        submitHandler: function(form) { 
            var descuentoCliente = 0;
            if ($("#TipoDescuentoCliente").val() != "" && ($("#DescuentoCliente").val() == "" || $("#DescuentoCliente").val() < 0)) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Ingresa la cantidad del descuento'
                });
                $("#DescuentoCliente").focus();
            }else{
                if ($("#DescuentoCliente").val() == "" || $("#DescuentoCliente").val() < 0) {
                    descuentoCliente = 0;
                }else{
                    descuentoCliente = $("#DescuentoCliente").val();
                }
                var data = new FormData(document.getElementById("FormClientes"));
                data.append("metodo", $("#GuardarCliente").attr("tipo"));
                data.append("accion", "clientes");
                data.append("DescuentoCliente", descuentoCliente);
                data.append("IDCliente", $("#GuardarCliente").attr("attrid"));

                var btn = $('#GuardarCliente');
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#carga").show();
                    }
                })
                .done(function(res) {
                    var datos = $.trim(res).split("~");
                    if ($.trim(datos[0]) == "Correcto") {
                        $("#ModalCliente").modal("hide");
                        var footer = "";
                        if ($("#GuardarCliente").attr("tipo") == "modificar") {
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
                            title: 'Cliente '+tipoAlerta+' correctamente',
                            footer: footer
                        });
                        TablaClientes();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al '+$("#GuardarCliente").attr("tipo")+' cliente.'
                        });

                        console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                })
                .always(function() {
                    $("#carga").hide();
                }); 
            }           
        }
    });    
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevoCliente', function() {
        $("#GuardarCliente").attr('tipo', "insertar");
        $("#GuardarCliente").attr('attrid', "");
        $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/default.jpg');
        $("#FormClientes").trigger('reset');
        $("#TituloModalCliente").text("Agregar nuevo");
        $("#TipoDescuentoCliente").trigger("change");
        $("#DescuentoCliente").val("");
    });


    $(document).on('change', '#TipoDescuentoCliente', function() {
        var tipo = $(this).val();
        if (tipo != "") {
            $("#TituloTipoDescuento").text(tipo);
            $("#DescuentoCliente").attr("disabled", false);
            $("#DescuentoCliente").val("");
        }else{
            $("#TituloTipoDescuento").text("No aplica descuento");
            $("#DescuentoCliente").attr("disabled", true);
            $("#DescuentoCliente").val("");
        }
        $("#DescuentoCliente").trigger("keyup");
    });

    $(document).on('keyup', '#DescuentoCliente', function() {
        var tipo = $('#TipoDescuentoCliente').val();
        if (tipo == "Porcentaje") {
            $("#LabelDescuentoCliente").text($(this).val()+" %");
        }else if (tipo == "Cantidad") {
            $("#LabelDescuentoCliente").text("$"+$(this).val());
        }else{
            $("#LabelDescuentoCliente").text("No aplica");
        }
    });

    $(document).on('click', '#verfotoCliente', function() {
        $("#FotoCliente").trigger("click");
    });

    $(document).on('change', '#FotoCliente', function() {
        readURL(this, $("#verfotoCliente"));
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
                    TablaClientes();
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
            $("#GuardarCliente").attr('tipo', 'modificar');
            $("#GuardarCliente").attr('attrid', id);
            $("#TituloModalCliente").text("Modificar");
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
            $("#SucursalCliente").val(datos.FK_Sucursal);
            if (datos.Foto != "") {
                $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/fotosClientes/'+datos.Foto);
            }else{
                $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/default.jpg');
            }
            $("#ModalCliente").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

});

function TablaClientes(){
    ajaxMyDatatable({
        "table": $("#TablaClientes"), 
        "colums": [
            "Fecha",
            "Nombre",
            "Direccion",
            "Contacto",
            "Detalles",
            "Acciones"
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "clientes"
        }
    });
}