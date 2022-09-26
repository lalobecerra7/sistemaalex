function v_clientes() {
    TablaClientes();

    $('#FormClientes').validate({
        rules: {
            NombreCliente: {
                required: true
            },
            RFCCliente: {
                required: true
            },
            SucursalCliente: {
                required: true
            },
        },
        messages: {
            NombreCliente: {
                required: "El nombre del cliente es obligatorio"
            },
            RFCCliente: {
                required: "El RFC del cliente es obligatorio"
            },
            SucursalCliente: {
                required: "La sucursal es obligatoria"
            },
        },
        submitHandler: function(form) { 
            var direcciones = '';
            $("#TablaUbicacionClientes tbody tr").each(function(index, el){
                direcciones += $(this).attr("calle")+"~"+$(this).attr("noexterior")+"~"+$(this).attr("nointerior")+"~"+$(this).attr("cp")+"~"+$(this).attr("colonia")+"~"+$(this).attr("ciudad")+"~"+$(this).attr("estado")+"~"+$(this).attr("pais")+"~"+$(this).attr("nombre")+"~"+$(this).attr("puesto")+"~"+$(this).attr("correo")+"~"+$(this).attr("telefono")+",";
            });
            var data = new FormData(document.getElementById("FormClientes"));
            data.append("metodo", $("#GuardarCliente").attr("tipo"));
            data.append("accion", "clientes");
            data.append("direcciones", direcciones);
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
                }else if ($.trim(res) == "ErrorInsertar: Duplicate entry '"+$("#RFCCliente").val()+"' for key 'RFC'"){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Este RFC ya se encuentra registrado, intenta con otro.'
                    });
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
    }); 

    $('#FormDireccion').validate({
        rules: {
            CalleCliente: {
                required: true
            },
            NoExteriorCliente: {
                required: true
            },
            NombreContactoCliente: {
                required: true
            },
        },
        messages: {
            CalleCliente: {
                required: "El nombre de la calle es obligatorio"
            },
            NoExteriorCliente: {
                required: "El número de la calle es obligatorio"
            },
            NombreContactoCliente: {
                required: "El nombre del contacto es obligatorio"
            },
        },
        submitHandler: function(form) { 
            var CalleCliente = $("#CalleCliente").val();
            var NoExteriorCliente = $("#NoExteriorCliente").val();
            var NoInteriorCliente = $("#NoInteriorCliente").val();
            var CPCliente = $("#CPCliente").val();
            var ColoniaCliente = $("#ColoniaCliente").val();
            var CiudadCliente = $("#CiudadCliente").val();
            var EstadoCliente = $("#EstadoCliente").val();
            var PaisCliente = $("#PaisCliente").val();
            var NombreContactoCliente = $("#NombreContactoCliente").val();
            var PuestoContactoCliente = $("#PuestoContactoCliente").val();
            var CorreoContactoCliente = $("#CorreoContactoCliente").val();
            var TelefonoContactoCliente = $("#TelefonoContactoCliente").val();
            var tabla = '\
            <tr calle="'+CalleCliente+'" noexterior="'+NoExteriorCliente+'" nointerior="'+NoInteriorCliente+'" cp="'+CPCliente+'" colonia="'+ColoniaCliente+'" ciudad="'+CiudadCliente+'" estado="'+EstadoCliente+'" pais="'+PaisCliente+'" nombre="'+NombreContactoCliente+'" puesto="'+PuestoContactoCliente+'" correo="'+CorreoContactoCliente+'" telefono="'+TelefonoContactoCliente+'">\
                <td>Calle: '+CalleCliente+', No. Ext: '+NoExteriorCliente+', No. Int: '+NoInteriorCliente+'<br>Codigo Postal: '+CPCliente+'<br>Colonia: '+ColoniaCliente+'</td>\
                <td>Ciudad: '+CiudadCliente+'<br>Estado: '+EstadoCliente+'<br>País: '+PaisCliente+'</td>\
                <td>Nombre: '+NombreContactoCliente+'<br>Puesto: '+PuestoContactoCliente+'<br>Correo electrónico: '+CorreoContactoCliente+'<br>Teléfono: '+TelefonoContactoCliente+'</td>\
                <td><button class="btn btn-sm btn-danger" id="EliminarDireccion"><i class="fas fa-trash"></i></button></td>\
            </tr>';
            $("#TablaUbicacionClientes tbody").append(tabla);
            $('#FormDireccion').trigger("reset");
            $("#ModalNuevaDireccionCliente").modal("hide");
            $("#ModalCliente").modal("show");
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

    $(document).on('hidden.bs.modal', '#ModalNuevaDireccionCliente', function() {
        $("#ModalCliente").modal("show");
    });

    $(document).on('click', '#EliminarDireccion', function() {
        $(this).parent().parent().remove();
    });

    /*$(document).on('click', '#ModificarDireccion', function() {
        var boton = $(this);
        $('#FormDireccion').trigger("reset");
        $("#ModalCliente").modal("hide");
        $("#CalleCliente").val($(this).parent().parent().attr("calle"));
        $("#NoExteriorCliente").val($(this).parent().parent().attr("noexterior"));
        $("#NoInteriorCliente").val($(this).parent().parent().attr("nointerior"));
        $("#CPCliente").val($(this).parent().parent().attr("cp"));
        $("#ColoniaCliente").val($(this).parent().parent().attr("colonia"));
        $("#CiudadCliente").val($(this).parent().parent().attr("ciudad"));
        $("#EstadoCliente").val($(this).parent().parent().attr("estado"));
        $("#PaisCliente").val($(this).parent().parent().attr("pais"));
        $("#NombreContactoCliente").val($(this).parent().parent().attr("nombre"));
        $("#PuestoContactoCliente").val($(this).parent().parent().attr("puesto"));
        $("#CorreoContactoCliente").val($(this).parent().parent().attr("correo"));
        $("#TelefonoContactoCliente").val($(this).parent().parent().attr("telefono"));
        $("#ModalNuevaDireccionCliente").modal("show");
    });*/

    $(document).on('click', '#AgregarDireccionCliente', function() {
        $("#FormDireccion").trigger("reset");
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
            console.log(res);
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
            $("#DescuentoCliente").val(datos.Descuento);
            $("#RFCCliente").val(datos.RFC);
            $("#TitularBancoCliente").val(datos.Titular);
            $("#BancoCliente").val(datos.Banco);
            $("#CuentaBancoCliente").val(datos.No_Cuenta);
            $("#SucursalCliente").val(datos.FK_Sucursal);
            if (datos.Foto != "") {
                $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/fotosClientes/'+datos.Foto);
            }else{
                $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/default.jpg');
            }
            $("#TablaUbicacionClientes tbody").html("");
            if (datos.Extras.length > 0) {
                for (var i = 0; i < datos.Extras.length; i++) {
                    var CalleCliente = datos.Extras[i].Calle;
                    var NoExteriorCliente = datos.Extras[i].No_Exterior;
                    var NoInteriorCliente = datos.Extras[i].No_Interior;
                    var CPCliente = datos.Extras[i].Codigo_Postal;
                    var ColoniaCliente = datos.Extras[i].Colonia;
                    var CiudadCliente = datos.Extras[i].Ciudad;
                    var EstadoCliente = datos.Extras[i].Estado;
                    var PaisCliente = datos.Extras[i].Pais;
                    var NombreContactoCliente = datos.Extras[i].Nombre_Contacto;
                    var PuestoContactoCliente = datos.Extras[i].Puesto_Contacto;
                    var CorreoContactoCliente = datos.Extras[i].Email_Contacto;
                    var TelefonoContactoCliente = datos.Extras[i].Telefono_Contacto;
                    var tabla = '\
                    <tr calle="'+CalleCliente+'" noexterior="'+NoExteriorCliente+'" nointerior="'+NoInteriorCliente+'" cp="'+CPCliente+'" colonia="'+ColoniaCliente+'" ciudad="'+CiudadCliente+'" estado="'+EstadoCliente+'" pais="'+PaisCliente+'" nombre="'+NombreContactoCliente+'" puesto="'+PuestoContactoCliente+'" correo="'+CorreoContactoCliente+'" telefono="'+TelefonoContactoCliente+'">\
                        <td>Calle: '+CalleCliente+', No. Ext: '+NoExteriorCliente+', No. Int: '+NoInteriorCliente+'<br>Codigo Postal: '+CPCliente+'<br>Colonia: '+ColoniaCliente+'</td>\
                        <td>Ciudad: '+CiudadCliente+'<br>Estado: '+EstadoCliente+'<br>País: '+PaisCliente+'</td>\
                        <td>Nombre: '+NombreContactoCliente+'<br>Puesto: '+PuestoContactoCliente+'<br>Correo electrónico: '+CorreoContactoCliente+'<br>Teléfono: '+TelefonoContactoCliente+'</td>\
                        <td><button type="button" class="btn btn-sm btn-danger" id="EliminarDireccion"><i class="fas fa-trash"></i></button></td>\
                    </tr>';
                    $("#TablaUbicacionClientes tbody").append(tabla);
                }
            }
            $("#ModalCliente").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '.verDatosDireccion', function() {
        if ($(this).attr("Calle") != "") {
            $("#DatosCalle").text($(this).attr("Calle"));
        }else{  
            $("#DatosCalle").text("No hay datos registrados");
        }

        if ($(this).attr("No_Exterior") != "") {
            $("#DatosExt").text($(this).attr("No_Exterior"));
        }else{  
            $("#DatosExt").text("No hay datos registrados");
        }
        if ($(this).attr("No_Interior") != "") {
            $("#DatosInt").text($(this).attr("No_Interior"));
        }else{  
            $("#DatosInt").text("No hay datos registrados");
        }
        if ($(this).attr("Colonia") != "") {
            $("#DatosCP").text($(this).attr("Colonia"));
        }else{  
            $("#DatosCP").text("No hay datos registrados");
        }
        if ($(this).attr("Codigo_Postal") != "") {
            $("#DatosColonia").text($(this).attr("Codigo_Postal"));
        }else{  
            $("#DatosColonia").text("No hay datos registrados");
        }
        if ($(this).attr("Ciudad") != "") {
            $("#DatosCiudad").text($(this).attr("Ciudad"));
        }else{  
            $("#DatosCiudad").text("No hay datos registrados");
        }
        if ($(this).attr("Estado") != "") {
            $("#DatosEstado").text($(this).attr("Estado"));
        }else{  
            $("#DatosEstado").text("No hay datos registrados");
        }
        if ($(this).attr("Pais") != "") {
            $("#DatosPais").text($(this).attr("Pais"));
        }else{  
            $("#DatosPais").text("No hay datos registrados");
        }

        if ($(this).attr("Nombre_Contacto") != "") {
            $("#DatosNombre").text($(this).attr("Nombre_Contacto"));
        }else{  
            $("#DatosNombre").text("No hay datos registrados");
        }
        if ($(this).attr("Puesto_Contacto") != "") {
            $("#DatosPuesto").text($(this).attr("Puesto_Contacto"));
        }else{  
            $("#DatosPuesto").text("No hay datos registrados");
        }
        if ($(this).attr("Email_Contacto") != "") {
            $("#DatosCorreo").text($(this).attr("Email_Contacto"));
        }else{  
            $("#DatosCorreo").text("No hay datos registrados");
        }
        if ($(this).attr("Telefono_Contacto") != "") {
            $("#DatosTelefono").text($(this).attr("Telefono_Contacto"));
        }else{  
            $("#DatosTelefono").text("No hay datos registrados");
        }
        $("#ModalDetallesDireccion").modal("show");

    });

});

function TablaClientes(){
    ajaxMyDatatable({
        "table": $("#TablaClientes"), 
        "colums": [
            "Fecha",
            "Nombre",
            "Direcciones",
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