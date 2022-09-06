function v_usuarios() {
    console.log("entro a la funcion");
    TablaUsuarios();

    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#uploadForm + img').remove();
                $('#divImg').html('<img src="' + e.target.result + '" style="width:100%; height:170px; overflow:hidden; cursor:pointer;border-radius:4px;border:2px solid grey;">');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#foto").change(function() {
        filePreview(this);
    });


    $('#FormUsuario').validate({
        rules: {
            firstname: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            contrasena: {
                required: true
            },
            repetirContrasena: {
                required: true,
            },
        },
        messages: {
            firstname: {
                required: "Por favor ingresa el nombre.",
            },
            email: {
                required: "Por favor ingresa un correo electrónico.",
                email: "Por favor ingresa un correo electrónico válido."
            },
            contrasena: {
                required: "Por favor ingresa una contraseña.",
            },
            repetirContrasena: {
                required: "Por favor repite la contraseña.",
            },
        },
        submitHandler: function(form) {
            if ($('#contrasena').val() == $('#repetirContrasena').val()) {
                var data = new FormData(document.getElementById("FormUsuario"));
                data.append('metodo', $("#GuardarUsuario").attr("tipo"));
                data.append('accion', 'usuarios');
                data.append('IDUsuario', $("#GuardarUsuario").attr("attrid"));
                data.append('estatus', $("#swBD").val());
                data.append('modCo', $("#swMC").val());
                var btn = $('#GuardarUsuario');
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
                        console.log(res);
                        if ($.trim(res) == "Correcto") {
                            if ($("#GuardarUsuario").attr("tipo") == "modificar") {
                                var tipoAlerta = "modificado";
                            } else {
                                var tipoAlerta = "guardado";
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Usuario ' + tipoAlerta + ' correctamente'
                            });
                            TablaUsuarios();
                            $('#labelBD').html('<i class="fa fa-unlock" aria-hidden="true"></i> Desbloqueado');
                            $('#swBD').val('Desbloqueado')
                            $('#labelMC').html('No modificar contraseña');
                            $('#swMC').val('0')
                            $('#FormUsuario')[0].reset();
                            $("#modalAgregarUsuario").modal("hide");
                        } else if ($.trim(res) == "Error 1: Duplicate entry '" + $('#email').val() + "' for key 'Correo'") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El correo electrónico ya se encuentra registrado'
                            });
                            $('#email').addClass("form-control is-invalid");
                        } else if ($.trim(res) == "Error1") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error:',
                                text: 'El formato de la imagen no esta permitido. Los formatos permitidos son jpg, jpeg, png o svg.',
                            });
                        } else if ($.trim(res) == "Error2") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error:',
                                text: 'El tamaño máximo permitido para la imagen de perfil es de 10MB.',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error inesperado al ' + $("#GuardarUsuario").attr("tipo") + ' el usuario.'
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
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ambas contraseñas deben ser iguales'
                });

                $('#contrasena').addClass("form-control is-invalid");
                $('#repetirContrasena').addClass("form-control is-invalid");
            }
        }
    });
}

function TablaUsuarios() {
    ajaxMyDatatable({
        "table": $("#TablaUsuarios"),
        "colums": [
            "Foto",
            "Nombre",
            "Correo",
            "Estatus",
            "Permisos",
            "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "consultar",
            "accion": "usuarios"
        }
    });
}

jQuery(document).ready(function($) {

    $("#contrasena").on("keyup", function() {
        $('#contrasena').removeClass("form-control is-invalid");
    });

    $("#repetirContrasena").on("keyup", function() {
        $('#repetirContrasena').removeClass("form-control is-invalid");
    });

    $("#email").on("keyup", function() {
        $('#email').removeClass("form-control is-invalid");
    });

    $(document).on('click', '.cerrarmodal', function() {
        $('#labelBD').html('<i class="fa fa-unlock" aria-hidden="true"></i> Desbloqueado');
        $('#swBD').val('Desbloqueado');
        $('#FormUsuario')[0].reset();
    });

    $(document).on('click', '#toggle-password', function() {
        $(this).children('i').toggleClass('fa-eye fa-eye-slash');
        let input = $('#contrasena');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
    });

    $(document).on('click', '#toggle-password2', function() {
        $(this).children('i').toggleClass('fa-eye fa-eye-slash');
        let input = $('#repetirContrasena');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
    });

    $(document).on('click', '#divImg', function() {
        $("#foto").trigger("click");
    });

    $(document).on('click', '#swBD', function() {
        console.log($('#swBD').val())
        if ($('#labelBD').html() == '<i class="fa fa-unlock" aria-hidden="true"></i> Desbloqueado') {
            $('#labelBD').html('<i class="fa fa-lock" aria-hidden="true"></i> Bloqueado')
            $('#swBD').val('Bloqueado')
        } else {
            $('#labelBD').html('<i class="fa fa-unlock" aria-hidden="true"></i> Desbloqueado')
            $('#swBD').val('Desbloqueado')

        }
    });

    $(document).on('click', '#swMC', function() {
        console.log($('#swMC').val())
        if ($('#labelMC').html() == 'No modificar contraseña') {
            $('#labelMC').html('Modificar contraseña');
            $('#swMC').val('1');
            $('#contrasenia').show();
            $("#contrasena").val("");
            $("#repetirContrasena").val("");
        } else {
            $('#labelMC').html('No modificar contraseña');
            $('#swMC').val('0');
            $('#contrasenia').hide();
            $("#contrasena").val("aei");
            $("#repetirContrasena").val("aei");
        }
    });

    $(document).on('click', '#botonNuevoUsuario', function() {
        $("#GuardarUsuario").attr("tipo", "insertar");
        $("#GuardarUsuario").attr("attrid", "");
        $("#TituloModalUsuarios").html('Nuevo&nbsp;');
        $('#swBD').attr('checked', false);
        $("#contrasenia").show();
        $('#divImg').html('');
        $('#divImg').html('<img src="./vistas/assets/img/user.png" style="width:100%; height:170px; overflow:hidden; cursor:pointer;border-radius:4px;border:2px solid grey;"><br>');
        $('#modCon').hide();
    });


    $(document).on('click', '#ModificarUsuario', function() {
        $("#modalAgregarUsuario").modal("show");
        $("#GuardarUsuario").attr("tipo", "modificar");
        $("#GuardarUsuario").attr("attrid", $(this).attr("attrid"));
        $("#TipoTituloUsuario").html('Modificar&nbsp;');
        $("#modCon").show();
        $("#contrasenia").hide();
        $("#contrasena").val("aei");
        $("#repetirContrasena").val("aei");
        var data = "metodo=detalles&accion=usuarios&tipo=ConsultarDatosUsuario&IDUsuario=" + $(this).attr("attrid");

        $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
            })
            .done(function(res) {
                var datos = JSON.parse(res);
                console.log(datos);
                $("#ID_Usuario").val(datos.ID_Usuario);
                $("#firstname").val(datos.Nombre);
                $("#email").val(datos.Correo);
                if (datos.Foto == "") {
                    $('#divImg').html('<img src="./vistas/assets/img/default.jpg" style="width:100%; height:170px; overflow:hidden; cursor:pointer;border-radius:4px;border:2px solid grey;">');
                } else {
                    $('#divImg').html('<img src="./vistas/assets/img/usuarios/' + datos.Foto + '" style="width:100%; height:170px; overflow:hidden; cursor:pointer;border-radius:4px;border:2px solid grey;">');
                }
                $("#Tipo_usuario").val(datos.Tipo);
                if (datos.Estatus == "Bloqueado") {
                    $('#swBD').attr('checked', true);
                    $('#labelBD').html('<i class="fa fa-lock" aria-hidden="true"></i> Bloqueado')
                    $('#swBD').val("Bloqueado")
                } else {
                    $('#swBD').attr('checked', false);
                    $('#labelBD').html('<i class="fa fa-unlock" aria-hidden="true"></i> Desbloqueado')
                    $('#swBD').val("Desbloqueado")
                }
                $('img').each(function() {
                    if ($(this)[0].naturalHeight == 0) {
                        $(this).attr('src', './vistas/assets/img/default.jpg');
                    }
                });

            })
            .fail(function() {
                console.log("Error ajax");
            })
    });

    $(document).on('click', '#EliminarUsuario', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro que quieres eliminar el usuario "' + $(this).attr("nombre") + '"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                var data = "metodo=eliminar&accion=usuarios&IDUsuario=" + $(this).attr('attrid');

                $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: data,
                        beforeSend: function() {
                            progressBoton(btn);
                        }
                    })
                    .done(function(res) {
                        console.log($.trim(res));
                        if ($.trim(res) == "Correcto") {
                            Swal.fire({
                                icon: 'success',
                                title: 'El usuario fue eliminado correctamente'
                            });

                            TablaUsuarios();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error inesperado al eliminar el usuario.'
                            });


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
    });
    var botonPerP = null;
    $(document).on('click', '#VerPermisosUsuario', function() {
        botonPerP = $(this);
        var id = $(this).attr("attrid");
        $("#idUsuPer").val(id);
        $("#bResetearPer").attr("attrid", id);
        if (botonPerP.attr('cadena') == "") {
            $(".checkPermisos").prop("checked", false);
            $("#perfil1").prop('checked', false);
            $("#perfil2").prop('checked', false);
            $("#perfil3").prop('checked', false);
            $('#ModalPermisos').modal('show');
        }else{
            setTimeout(function() {     
                var separa = botonPerP.attr('cadena').split("~");
                console.log(separa);
                
                if(separa[(separa.length-1)] == "Capturista"){
                    $("#perfil1").prop('checked', true);
                }else if(separa[(separa.length-1)] == "Vendedor"){
                    $("#perfil2").prop('checked', true)
                }else if(separa[(separa.length-1)] == "Supervisor"){
                    $("#perfil3").prop('checked', true)
                }else{
                    $("#perfil1").prop('checked', false);
                    $("#perfil2").prop('checked', false);
                    $("#perfil3").prop('checked', false);
                }


                for (var i = 0; i < (separa.length-1); i++) {
                    var modulos = separa[i].split(",");
                    //console.log(modulos);

                     $("#"+modulos[0]).parent().children('td:eq(0)').children('table').children('tbody').children('tr:eq(1)').children('td').each(function(index) {
                        if (modulos[index+1] != undefined && modulos[index+1] == '1') {
                            $(this).children('div').children('input').prop('checked', true);
                        }
                    });
                }

                $('#ModalPermisos').modal('show');
            }, 100);   
        }
    });

    $(document).on('click', '.checkPerfil', function() {
        var btn = $(this);
        if(btn.prop('checked')){
            var cadena = "", perfil = "";

            setTimeout(function() {
                if(btn.attr('id') == "perfil1"){
                    perfil = "Capturista";
                    cadena = "v_inventario,1,1,0~v_ventas,0,0,0,0,0~v_salidas,0,0,0~v_clientes,1,1,1,1~v_etiquetas,1,1~v_productos,1,1,1,1~v_merma,1,1~v_reportesalidas,0~v_vendedores,1,1,1,1~v_vehiculos,1,1,1,1~v_usuarios,1,1,0,0,0~";
                }else if(btn.attr('id') == "perfil2"){
                    cadena = "v_inventario,1,1,1~v_ventas,1,1,1,1,1~v_salidas,1,0,0~v_clientes,1,1,0,0~v_etiquetas,0,0~v_productos,0,0,0,0~v_merma,0,0~v_reportesalidas,1~v_vendedores,1,1,1,1~v_vehiculos,0,0,0,0~v_usuarios,0,0,0,0,0~";
                    perfil = "Vendedor";
                }else if(btn.attr('id') == "perfil3"){
                    cadena = "v_inventario,1,1,1~v_ventas,1,1,1,1,1~v_salidas,1,1,1~v_clientes,1,1,1,1~v_etiquetas,1,1~v_productos,1,1,1,1~v_merma,1,1~v_reportesalidas,1~v_vendedores,1,1,1,1~v_vehiculos,1,1,1,1~v_usuarios,1,0,0,0,0~";
                    perfil = "Supervisor";
                }

                var separa = cadena.split("~");

                for (var i = 0; i < (separa.length-1); i++) {
                    var modulos = separa[i].split(",");

                    $("#"+modulos[0]).parent().children('td:eq(0)').children('table').children('tbody').children('tr:eq(1)').children('td').each(function(index) {
                        if (modulos[index+1] != undefined && modulos[index+1] == '1') {
                            $(this).children('div').children('input').prop('checked', true);
                        }else{
                            $(this).children('div').children('input').prop('checked', false);
                        }
                    });
                }

                var data = "metodo=detalles&accion=usuarios&tipo=ModificarPermisos&id="+$("#idUsuPer").val()+"&cadena="+cadena+perfil;

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data
                })
                .done(function(res) {
                    if($.trim(res) == "Correcto"){
                        botonPerP.attr('cadena', cadena+perfil);
                        TablaUsuarios();
                        /*var separa = botonPerP.parent().parent().children('td:eq(4)').text().split(':');
                        botonPerP.parent().parent().children('td:eq(4)').html(separa[0]+': '+perfil);*/
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error inesperado al cambiar los permisos'
                        });
                        console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.log("Error de ajax");
                })
                .always(function() {
                    $("#carga").hide();
                });      
            }, 1000);
        }
    });

    $(document).on('click', '#bResetearPer', function() {
        var boton = $(this);
        Swal.fire({
            icon: 'warning',
            title: '¿Estás seguro que quieres resetearle los permisos al usuario?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, resetear',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.value) {
                //Cada que se agreguen o modifiquen permisos modificar esta cadena
                var cadena = 'v_inventario,0,0,0,0,0~v_ventas,0,0,0,0,0~v_salidas,0,0,0~v_clientes,0,0,0,0~v_etiquetas,0,0~v_productos,0,0,0,0~v_reportesalidas,0~v_vendedores,0,0,0,0~v_vehiculos,0,0,0,0~v_usuarios,0,0,0,0,0~';
                var data = "metodo=detalles&accion=usuarios&tipo=ModificarPermisos&id="+boton.attr('attrid')+"&cadena="+cadena;

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                       //$("#carga").show();
                    }
                })
                .done(function(res) {
                    if($.trim(res) == "Correcto"){
                        Swal.fire({
                            icon: 'success',
                            title: 'Los permisos del usuario han sido reseteados correctamente'
                        });
                        
                        $("#perfil1").prop('checked', false);
                        $("#perfil2").prop('checked', false);
                        $("#perfil3").prop('checked', false);
                        $(".checkPermisos").prop('checked', false);
                        botonPerP.attr('cadena', '');
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error inesperado al resetear permisos'
                        });
                        console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.log("Error de ajax");
                })
                .always(function() {
                    //$("#carga").hide();
                });        
            }
        });    
    });

    $(document).on('click', '.checkPermisos', function() {


        if ($(this).parent().parent().index() > 0 && $(this).parent().parent().parent().children('td:eq(0)').children('div').children('input').prop('checked') == false) {
            $(this).parent().parent().parent().children('td:eq(0)').children('div').children('input').prop('checked', true);    
        }else if ($(this).parent().parent().index() == 0 && $(this).prop('checked') == false) {
            $(this).parent().parent().parent().children('td').each(function(index) {
                if ($(this).children('div').children('input').prop('checked')) {
                    $(this).children('div').children('input').prop('checked', false);
                }
            });
        }

        var cadena = "";
        $(".permisoMo").each(function(index) {
            cadena += $(this).attr('id');
            $(this).parent().children('td:eq(0)').children('table').children('tbody').children('tr:eq(1)').children('td').each(function(index) {
                if ($(this).children('div').children('input').prop('checked')) {
                    cadena += ",1";
                }else{
                    cadena += ",0";
                }        
            });
            cadena += "~";
        });

        var perfil = "";
        if($("#perfil1").prop('checked')){
            perfil = "Capturista";
        }else if($("#perfil2").prop('checked')){
            perfil = "Vendedor";
        }else if($("#perfil3").prop('checked')){
            perfil = "Supervisor";
        }

        var data = "metodo=detalles&accion=usuarios&tipo=ModificarPermisos&id="+$("#idUsuPer").val()+"&cadena="+cadena+perfil;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
               // $("#carga").show();
            }
        })
        .done(function(res) {
            if($.trim(res) == "Correcto"){
                botonPerP.attr('cadena', cadena);
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error inesperado al cambiar los permisos'
                });
                console.log($.trim(res));
            }
        })
        .fail(function() {
            console.log("Error de ajax");
        })
        .always(function() {
           //$("#carga").hide();
        });      
    });

});



