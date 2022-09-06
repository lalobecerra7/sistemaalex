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
        $("#verfotoEmpleado img").attr('src', 'vistas/assets/archivos/default.jpg');
        $("#FormEmpleados").trigger('reset');
        $("#TituloModalEmpleados").text("Agregar nuevo");
    });

    $(document).on('click', '#verfotoEmpleado', function() {
        $("#FotoEmpleado").trigger("click");
    });

    $(document).on('change', '#FotoEmpleado', function() {
        readURL(this, $("#verfotoEmpleado"));
    });

    $(document).on('click', '#EliminarEmpleado', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        Swal.fire({
          title: '¿Estás a punto de eliminar al empleado '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=personal&idEmpleado="+id;
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
                        title: 'Empleado eliminado correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar empleado.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarEmpleado', function() {
        ConsultarAreas();
        ConsultarPuestos();
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=personal&idEmpleado="+id+"&tipo=ConsultarEmpleado";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            $("#GuardarEmpleado").attr('tipo', 'modificar');
            $("#GuardarEmpleado").attr('attrid', id);
            var datos = JSON.parse($.trim(res));
            $("#NombreEmpleado").val(datos.Nombre);
            $("#DireccionEmpleado").val(datos.Direccion);
            $("#ColoniaEmpleado").val(datos.Colonia);
            $("#TelefonoEmpleado").val(datos.Telefono);
            $("#CelularEmpleado").val(datos.Celular);
            $("#CiudadEmpleado").val(datos.Ciudad);
            $("#CorreoEmpleado").val(datos.Correo);
            $("#FechaNacimientoEmpleado").val(datos.Fecha_Nacimiento);
            $("#FechaIngreso").val(datos.Fecha_Entrada);
            $("#SueldoEmpleado").val(datos.Sueldo);
            $("#SDIEmpleado").val(datos.SDI);
            $("#SPHEmpleado").val(datos.SPH);
            $("#TipoSueldo").val(datos.Tipo_Sueldo);
            $("#HoraEntrada").val(datos.Horario_Entrada);
            $("#HorarioSalida").val(datos.Horario_Salida);
            $("#HoraEntradaSabado").val(datos.Horario_Entrada_Sabado);
            $("#HorarioSalidaSabado").val(datos.Horario_Salida_Sabado);
            $("#SexoEmpleado").val(datos.Sexo);
            $("#LugarNacimientoEmpleado").val(datos.Lugar_Nacimiento);
            $("#EstadoCivilEmpleado").val(datos.Estado_Civil);
            $("#PaisEmpleado").val(datos.Pais);
            $("#EstadoEmpleado").val(datos.Estado);
            $("#RFCEmpleado").val(datos.RFC);
            $("#NoSeguroSocialEmpleado").val(datos.Numero_Seguro);
            $("#CURPEmpleado").val(datos.CURP);
            $("#TipoSangreEmpleado").val(datos.Tipo_Sangre);
            $("#AlergiasEmpleado").val(datos.Alergias);
            $("#ContactoEmergencia").val(datos.Contacto_Emergencias);
            $("#TelefonoEmergencia").val(datos.Numero_Emergencias);
            $("#EstatusEmpleado").val(datos.Estado_Empleado );
            $("#FechaBajaEmpleado").val(datos.Fecha_Baja);
            $("#MotivoBajaEmpleado").val(datos.Motivo_Baja);
            $("#FechaTerminoContrato").val(datos.Fecha_Termino_Contrato);
            $("#FechaReingresoEmpleado").val(datos.Fecha_Reingreso);
            $("#CPEmpleado").val(datos.Codigo_Postal);
            $("#PuestoEmpleado").val(datos.FK_Puesto);
            $("#AreasEmpleado").val(datos.FK_Area);
            
            if (datos.Foto != "") {
                $("#verfotoEmpleado img").attr('src', 'vistas/assets/archivos/fotosEmpleados/'+datos.Foto);
            }else{
                $("#verfotoEmpleado img").attr('src', 'vistas/assets/archivos/default.jpg');
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