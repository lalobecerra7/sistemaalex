function v_clientes() {
    TablaClientes();

    $('#FormClientes').validate({
        rules: {
            NombreCliente: {
                required: true
            },
            SucursalCliente: {
                required: true
            },
            RFCCliente: {
                required: true
            }
        },
        messages: {
            NombreCliente: {
                required: "El nombre es requerido."
            },
            SucursalCliente: {
                required: "La sucursal es requerdia."
            },
            RFCCliente: {
                required: "El RFC es obligatorio"
            }
        },
        submitHandler: function(form) { 
            if ($("#TipoPersona").val() == "Fisica" && $("#primerApellidoCliente").val() == "") {
                Swal.fire({
                    icon: 'info',
                    title: 'Ingresa el primer apellido del cliente',
                });
                $("#primerApellidoCliente").focus();
            }else{
                var direcciones = '';
                $("#TablaUbicacionClientes tbody tr").each(function(index, el){
                    direcciones += $(this).find("#CalleCliente").val()+"~"+$(this).find("#NoExteriorCliente").val()+"~"+$(this).find("#NoInteriorCliente").val()+"~"+$(this).find("#CPCliente").val()+"~"+$(this).find("#ColoniaCliente").val()+"~"+$(this).find("#CiudadCliente").val()+"~"+$(this).find("#EstadoCliente").val()+"~"+$(this).find("#PaisCliente").val()+"~"+$(this).find("#NombreContactoCliente").val()+"~"+$(this).find("#PuestoContactoCliente").val()+"~"+$(this).find("#CorreoContactoCliente").val()+"~"+$(this).find("#TelefonoContactoCliente").val()+"~"+$(this).find("#ReferenciaCliente").val()+"~"+$(this).find("#LatitudCliente").val()+"~"+$(this).find("#LongitudCliente").val()+"~"+$(this).find("#EntreQueCalles").val()+",";
                });

                var sucursales = [];
                if($("#verSucursalesCliente").children('tr').length > 0){
                    $("#verSucursalesCliente").children('tr').each(function(index, el) {
                        sucursales.push({'ID_Sucursal': $.trim($(this).children('td:eq(0)').attr('attrID'))});
                    });
                }

                var data = new FormData(document.getElementById("FormClientes"));
                data.append("metodo", $("#GuardarCliente").attr("tipo"));
                data.append("accion", "clientes");
                data.append("direcciones", direcciones);
                data.append("IDCliente", $("#GuardarCliente").attr("attrid"));
                data.append('sucursales', JSON.stringify(sucursales));

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
                    var str = $.trim(res);
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
                    }else if ((str.includes("Duplicate entry")) == true){
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
        }
    });       
}

jQuery(document).ready(function($) {

    $(document).on('change', '#TipoPersona', function() {
        if ($(this).val() == "Fisica") {
            $(".camposFisica").removeClass("oculto");
        }else if ($(this).val() == "Moral") {
            $(".camposFisica").addClass("oculto");
            $(".camposFisica").find("input").val("");
        }
    });                

    $(document).on('click', '#botonNuevoCliente', function() {
        $("#GuardarCliente").attr('tipo', "insertar");
        $("#GuardarCliente").attr('attrid', "");
        $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/default.jpg');
        $("#FormClientes").trigger('reset');
        $("#TituloModalCliente").text("Agregar nuevo");
        $("#TipoDescuentoCliente").trigger("change");
        $("#DescuentoCliente").val("");
        $("#TipoPersona").trigger("change");
        $("#TablaUbicacionClientes tbody").html("");
        $("#verSucursalesCliente").html("");
    });

    $(document).on('hidden.bs.modal', '#ModalNuevaDireccionCliente', function() {
        $("#ModalCliente").modal("show");
    });

    $(document).on('click', '#EliminarDireccion', function() {
        if ($(this).attr("attrid") != "" || $(this).attr("attrid") != null) {
            var boton = $(this);
            var id = $(this).attr("attrid");
            var nombre = $(this).attr("nombre");
            console.log(id);
            Swal.fire({
              title: '¿Estás a punto de eliminar la dirección '+nombre+'?',
              text: "Una vez eliminado ya no podrá ser recuperado",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              cancelButtonText: 'No, cancelar',
              confirmButtonText: 'Si, eliminar'
            }).then((result) => {
              if (result.value) {
                var data = "metodo=eliminar&accion=clientes&tipo=EliminarDireccion&IDDireccion="+id;
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data
                })
                .done(function(res) {
                    console.log(res);
                    if ($.trim(res) == "Correcto") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dirección eliminada correctamente'
                        });
                        TablaClientes();
                        $(boton).parent().parent().remove();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar Dirección.'
                        });
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                });  
              }
            });
        }
    });

    $(document).on('click', '#AgregarDireccionCliente', function() {
        //$("#FormDireccion").trigger("reset");
        var tabla = `\
        <tr>
            <td>
                <label for="CalleCliente">Calle</label>
                <input type="text" class="form-control" id="CalleCliente" name="CalleCliente" placeholder="Ingresa la calle del cliente">
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label for="NoExteriorCliente">No. Exterior</label>
                        <input type="text" class="form-control" id="NoExteriorCliente" name="NoExteriorCliente" placeholder="Ingresa el número exterior">
                    </div>
                    <div class="col-md-6">
                        <label for="NoInteriorCliente">No. Interior</label>
                        <input type="text" class="form-control" id="NoInteriorCliente" name="NoInteriorCliente" placeholder="Ingresa el número interior">
                     </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label for="CPCliente">Codigo postal</label>
                        <input type="text" class="form-control" id="CPCliente" name="CPCliente" placeholder="Ingresa el codigo postal del cliente">
                    </div>
                    <div class="col-md-6">
                        <label for="ColoniaCliente">Colonia</label>
                        <input type="text" class="form-control" id="ColoniaCliente" name="ColoniaCliente" placeholder="Ingresa la colonia del cliente">
                     </div>
                </div> 
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label for="LatitudCliente">Latitud</label>
                        <input type="text" class="form-control" id="LatitudCliente" name="LatitudCliente" placeholder="Latitud de la ubicación">
                    </div>
                    <div class="col-md-6">
                        <label for="LongitudCliente">Longitud</label>
                        <input type="text" class="form-control" id="LongitudCliente" name="LongitudCliente" placeholder="Longitud de la ubicación">
                    </div>
                </div>
            </td>
            <td>
                <label for="CiudadCliente">Ciudad</label>
                <input type="text" class="form-control" id="CiudadCliente" name="CiudadCliente" placeholder="Ingresa la ciudad del cliente">
                <br>
                <label for="EstadoCliente">Estado</label>
                <input type="text" class="form-control" id="EstadoCliente" name="EstadoCliente" placeholder="Ingresa el estado del cliente">
                <br>
                <label for="PaisCliente">País</label>
                <input type="text" class="form-control" id="PaisCliente" name="PaisCliente" placeholder="Ingresa el país del cliente">
                <br>
                <label for="ReferenciaCliente">Referencia visual / Detalles</label>
                <input type="text" class="form-control" id="ReferenciaCliente" name="ReferenciaCliente" placeholder="Ingresa una referencia visual o detalles de la ubicación">
                <br>
                <label for="EntreQueCalles">Entre que calles se encuentra</label>
                <input type="text" class="form-control" id="EntreQueCalles" name="EntreQueCalles" placeholder="Ingresa que calles colindan con la ubicación">
            </td>
            <td>
                <label for="NombreContactoCliente">Nombre</label>
                <input type="text" class="form-control" id="NombreContactoCliente" name="NombreContactoCliente" placeholder="Ingresa nombre del contacto">
                <br>
                <label for="PuestoContactoCliente">Puesto</label>
                <input type="text" class="form-control" id="PuestoContactoCliente" name="PuestoContactoCliente" placeholder="Ingresa el puesto del contacto">
                <br>
                <label for="CorreoContactoCliente">Correo electrónico</label>
                <input type="text" class="form-control" id="CorreoContactoCliente" name="CorreoContactoCliente" placeholder="Ingresa el correo electrónico">
                <br>
                <label for="TelefonoContactoCliente">Teléfono</label>
                <input type="text" class="form-control" id="TelefonoContactoCliente" name="TelefonoContactoCliente" placeholder="Ingresa el teléfono del contacto">
            </td>
            <td><button type="button" class="btn btn-sm btn-danger" id="EliminarDireccion" attrid nombre><i class="fas fa-trash"></i></button></td>
        </tr>`;
        $("#TablaUbicacionClientes tbody").append(tabla);
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
            var data = "metodo=eliminar&accion=clientes&IDCliente="+id+"&tipo=EliminarCliente";
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
        $("#TablaUbicacionClientes tbody").html("");
        $("#verSucursalesCliente").html("");
        var data = "metodo=detalles&accion=clientes&IDCliente="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log($.trim(res));
            
            $("#GuardarCliente").attr('tipo', 'modificar');
            $("#GuardarCliente").attr('attrid', id);
            $("#TituloModalCliente").text("Modificar");
            var datos = JSON.parse($.trim(res));

            $("#NombreCliente").val(datos.Nombre);
            $("#primerApellidoCliente").val(datos.Primer_Apellido);
            $("#segundoApellidoCliente").val(datos.Segundo_Apellido);
            $("#TelefonoCliente").val(datos.Telefono);
            $("#CelularCliente").val(datos.Celular);
            $("#CorreoCliente").val(datos.Correo);
            $("#FechaNacimientoCliente").val(datos.Fecha_Nacimiento);
            $("#SexoCliente").val(datos.Sexo);
            $("#DescuentoCliente").val(datos.Descuento);
            $("#RFCCliente").val(datos.RFC);
            $("#FacturarCliente").val(datos.Facturar);
            $("#TitularBancoCliente").val(datos.Titular);
            $("#BancoCliente").val(datos.Banco);
            $("#CuentaBancoCliente").val(datos.No_Cuenta);
            $("#SucursalCliente").val(datos.FK_Sucursal);
            $("#razonCliente").val(datos.Razon_CFDI);
            $("#regimenCliente").val(datos.Regimen_CFDI);
            $("#CalleClienteGeneral").val(datos.Calle);
            $("#NoExteriorClienteGeneral").val(datos.No_Exterior);
            $("#NoInteriorClienteGeneral").val(datos.No_Interior);
            $("#CPClienteGeneral").val(datos.Codigo_Postal);
            $("#ColoniaClienteGeneral").val(datos.Colonia);
            $("#CiudadClienteGeneral").val(datos.Ciudad);
            $("#EstadoClienteGeneral").val(datos.Estado);
            $("#PaisClienteGeneral").val(datos.Pais);
            $("#contactoCliente").val(datos.Nombre_Contacto);
            $("#puestoContactoCliente").val(datos.Puesto_Contacto);
            $("#correoContactoCliente").val(datos.Email_Contacto);
            $("#telefonoContactoCliente").val(datos.Tel_Contacto);
            $("#INECliente").val(datos.INE);
            $("#rutasCliente").val(datos.FK_Ruta);
            $("#ordenRuta").val(datos.Orden_Ruta);
            if (datos.Foto != "") {
                $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/fotosClientes/'+datos.Foto);
            }else{
                $("#verfotoCliente img").attr('src', 'vistas/assets/archivos/default.jpg');
            }

            if (datos.Tipo_Persona == "Fisica") {
                $("#TipoPersona").val("Fisica");
            }else if (datos.Tipo_Persona == "Moral") {
                $("#TipoPersona").val("Moral");
            }else{
                $("#TipoPersona").val("Fisica");
            }
            $("#TipoPersona").trigger("change");
            $("#TablaUbicacionClientes tbody").html("");
            if (datos.Extras != null && datos.Extras.length > 0) {
                for (var i = 0; i < datos.Extras.length; i++) {
                    var CalleCliente = datos.Extras[i].Calle;
                    var NoExteriorCliente = datos.Extras[i].No_Exterior;
                    var NoInteriorCliente = datos.Extras[i].No_Interior;
                    var CPCliente = datos.Extras[i].Codigo_Postal;
                    var ColoniaCliente = datos.Extras[i].Colonia;
                    var CiudadCliente = datos.Extras[i].Ciudad;
                    var EstadoCliente = datos.Extras[i].Estado;
                    var PaisCliente = datos.Extras[i].Pais;
                    var ReferenciaCliente = datos.Extras[i].Detalles;
                    var NombreContactoCliente = datos.Extras[i].Nombre_Contacto;
                    var PuestoContactoCliente = datos.Extras[i].Puesto_Contacto;
                    var CorreoContactoCliente = datos.Extras[i].Email_Contacto;
                    var TelefonoContactoCliente = datos.Extras[i].Telefono_Contacto;
                    var Latitud = datos.Extras[i].Latitud;
                    var Longitud = datos.Extras[i].Longitud;
                    var Entre_Calles = datos.Extras[i].Entre_Calles;
                    var tabla = `\
                    <tr>
                        <td>
                            <label for="CalleCliente">Calle</label>
                            <input type="text" class="form-control" value="`+CalleCliente+`" id="CalleCliente" name="CalleCliente" placeholder="Ingresa la calle del cliente">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="NoExteriorCliente">No. Exterior</label>
                                    <input type="text" class="form-control" value="`+NoExteriorCliente+`" id="NoExteriorCliente" name="NoExteriorCliente" placeholder="Ingresa el número exterior">
                                </div>
                                <div class="col-md-6">
                                    <label for="NoInteriorCliente">No. Interior</label>
                                    <input type="text" class="form-control" value="`+NoInteriorCliente+`" id="NoInteriorCliente" name="NoInteriorCliente" placeholder="Ingresa el número interior">
                                 </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="CPCliente">Codigo postal</label>
                                    <input type="text" class="form-control" value="`+CPCliente+`" id="CPCliente" name="CPCliente" placeholder="Ingresa el codigo postal del cliente">
                                </div>
                                <div class="col-md-6">
                                    <label for="ColoniaCliente">Colonia</label>
                                    <input type="text" class="form-control" value="`+ColoniaCliente+`" id="ColoniaCliente" name="ColoniaCliente" placeholder="Ingresa la colonia del cliente">
                                 </div>
                            </div> 
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="LatitudCliente">Latitud</label>
                                    <input type="text" class="form-control" value="`+Latitud+`" id="LatitudCliente" name="LatitudCliente" placeholder="Latitud de la ubicación">
                                </div>
                                <div class="col-md-6">
                                    <label for="LongitudCliente">Longitud</label>
                                    <input type="text" class="form-control" value="`+Longitud+`" id="LongitudCliente" name="LongitudCliente" placeholder="Longitud de la ubicación">
                                </div>
                            </div>
                        </td>
                        <td>
                            <label for="CiudadCliente">Ciudad</label>
                            <input type="text" class="form-control" value="`+CiudadCliente+`" id="CiudadCliente" name="CiudadCliente" placeholder="Ingresa la ciudad del cliente">
                            <br>
                            <label for="EstadoCliente">Estado</label>
                            <input type="text" class="form-control" value="`+EstadoCliente+`" id="EstadoCliente" name="EstadoCliente" placeholder="Ingresa el estado del cliente">
                            <br>
                            <label for="PaisCliente">País</label>
                            <input type="text" class="form-control" value="`+PaisCliente+`" id="PaisCliente" name="PaisCliente" placeholder="Ingresa el país del cliente">
                            <br>
                            <label for="ReferenciaCliente">Referencia visual / Detalles</label>
                            <input type="text" class="form-control" value="`+ReferenciaCliente+`" id="ReferenciaCliente" name="ReferenciaCliente" placeholder="Ingresa una referencia visual o detalles">
                            <br>
                            <label for="EntreQueCalles">Entre que calles se encuentra</label>
                            <input type="text" class="form-control" value="`+Entre_Calles+`" id="EntreQueCalles" name="EntreQueCalles" placeholder="Ingresa que calles colindan con la dirección">
                        </td>
                        <td>
                            <label for="NombreContactoCliente">Nombre</label>
                            <input type="text" class="form-control" value="`+NombreContactoCliente+`" id="NombreContactoCliente" name="NombreContactoCliente" placeholder="Ingresa nombre del contacto">
                            <br>
                            <label for="PuestoContactoCliente">Puesto</label>
                            <input type="text" class="form-control" value="`+PuestoContactoCliente+`" id="PuestoContactoCliente" name="PuestoContactoCliente" placeholder="Ingresa el puesto del contacto">
                            <br>
                            <label for="CorreoContactoCliente">Correo electrónico</label>
                            <input type="text" class="form-control" value="`+CorreoContactoCliente+`" id="CorreoContactoCliente" name="CorreoContactoCliente" placeholder="Ingresa el correo electrónico">
                            <br>
                            <label for="TelefonoContactoCliente">Teléfono</label>
                            <input type="text" class="form-control" value="`+TelefonoContactoCliente+`" id="TelefonoContactoCliente" name="TelefonoContactoCliente" placeholder="Ingresa el teléfono del contacto">
                        </td>
                        <td><button type="button" class="btn btn-sm btn-danger" id="EliminarDireccion" attrid="`+datos.Extras[i].ID_Detalle_Cliente+`" nombre="`+datos.Extras[i].Calle+`"><i class="fas fa-trash"></i></button></td>
                    </tr>`;
                    $("#TablaUbicacionClientes tbody").append(tabla);
                }
            }


            if(datos.Sucursales != null){
                datos.Sucursales.forEach(sucursal => {
                    $("#verSucursalesCliente").append(`<tr id="`+sucursal.FK_Sucursal+`">
                        <td attrID="`+sucursal.FK_Sucursal+`">`+sucursal.Nombre+`</td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarSucursal"><i class="fas fa-trash"></i></button></td>
                    </tr>`);
                });
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
            $("#DatosColonia").text($(this).attr("Colonia"));
        }else{  
            $("#DatosColonia").text("No hay datos registrados");
        }
        if ($(this).attr("Codigo_Postal") != "") {
            $("#DatosCP").text($(this).attr("Codigo_Postal"));
        }else{  
            $("#DatosCP").text("No hay datos registrados");
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
        if ($(this).attr("Detalles") != "") {
            $("#DatosReferencia").text($(this).attr("Detalles"));
        }else{  
            $("#DatosReferencia").text("No hay datos registrados");
        }

        if ($(this).attr("Latitud") != "") {
            $("#DatosLatitud").text($(this).attr("Latitud"));
        }else{  
            $("#DatosLatitud").text("No hay datos registrados");
        }

        if ($(this).attr("Longitud") != "") {
            $("#DatosLongitud").text($(this).attr("Longitud"));
        }else{  
            $("#DatosLongitud").text("No hay datos registrados");
        }

        if ($(this).attr("EntreCalles") != "") {
            $("#DatosEntreCalles").text($(this).attr("EntreCalles"));
        }else{  
            $("#DatosEntreCalles").text("No hay datos registrados");
        }

        
        $("#ModalDetallesDireccion").modal("show");

    });

    $(document).on('click', '#bAgregarSucursal', function() {
        $("#bGuardarSucursal").trigger('click');
    });

    $(document).on('submit', '#formSucursalesCliente', function(event) {
        event.preventDefault();

        if($("#verSucursalesCliente").children('tr[id="'+$.trim($("#SucursalCliente").val())+'"]').length == 0 && $("#SucursalCliente").val() != ""){
            $("#verSucursalesCliente").append(`<tr id="`+$.trim($("#SucursalCliente").val())+`">
                <td attrID="`+$.trim($("#SucursalCliente").val())+`">`+$.trim($('#SucursalCliente option:selected').text())+`</td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarSucursal"><i class="fas fa-trash"></i></button></td>
            </tr>`);

            document.getElementById('formSucursalesCliente').reset();
        }else{
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'La sucursal ya existe, por favor agrega otra.'
            });  
        }
    });

    $(document).on('click', '.bQuitarSucursal', function() {
        $(this).parent().parent().remove();
    });

});

function TablaClientes(){
    ajaxMyDatatable({
        "table": $("#TablaClientes"), 
        "colums": [
            "IDCliente",
            "Fecha",
            "Nombre",
            "Direcciones",
            "Detalles",
            "Acciones"
        ], 
        "sort": [
            0,
            "asc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "clientes"
        }
    });
}