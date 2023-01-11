function v_usuarios() {
    TablaUsuarios();

    $('#FormUsuarios').validate({
        rules: {
            NombreUsuario: {
                required: true
            },
            PrimerApellidoUsuario:{
                required: true
            },
            CorreoUsuario:{
                required: true
            },
            NuevaContrasena:{
                required: true
            },
            RepetirNuevaContrasena:{
                equalTo: "#NuevaContrasena"
            }
        },
        messages: {
            NombreUsuario: {
                required: "El nombre del usuario es obligatorio"
            },
            PrimerApellidoUsuario:{
                required: "El primer apellido del usuario es obligatorio"
            },
            NuevaContrasena:{
                required: "La contraseña es obligatoria"
            },
            CorreoUsuario:{
                required: "El correo electrónico del usuario es obligatorio"
            },
        },
        submitHandler: function(form) { 
            console.log("entrp");
           
            var data = new FormData(document.getElementById("FormUsuarios"));
            data.append("metodo", $("#GuardarUsuario").attr("tipo"));
            data.append("accion", "usuarios");
            data.append("idUsuario", $("#GuardarUsuario").attr("attrid"));

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
                var datos = $.trim(res).split("~");
                if ($.trim(datos[0]) == "Correcto") {
                    $("#ModalUsuario").modal("hide");
                    var footer = "";
                    if ($("#GuardarUsuario").attr("tipo") == "modificar") {
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
                        title: 'Usuario '+tipoAlerta+' correctamente',
                        footer: footer
                    });
                    TablaUsuarios();
                }else if ($.trim(datos[0]) == "ErrorInsertar: Duplicate entry '"+$('#CorreoUsuario').val()+"' for key 'usuarios.Correo'") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'El correo ingresado ya fue registrado con anterioridad intenta con otro.'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarUsuario").attr("tipo")+' usuario.'
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

    $(document).on('click', '.VerContrasenas', function() {
        console.log( $(this).parent().parent().html());
        if($(this).children('i').hasClass('fa-eye')){
            $(this).children('i').removeClass('fa-eye');
            $(this).children('i').addClass('fa-eye-slash');
            $('.contra').attr('type', 'text');
        }else{
            $(this).children('i').removeClass('fa-eye-slash');
            $(this).children('i').addClass('fa-eye');
            $('.contra').attr('type', 'password');
        }
    });

    $(document).on('click', '#botonNuevoUsuario', function() {
        $("#GuardarUsuario").attr('tipo', "insertar");
        $("#GuardarUsuario").attr('attrid', "");
        $("#verfotoUsuario img").attr('src', 'vistas/assets/archivos/default.jpg');
        $("#FormUsuarios").trigger('reset');
        $("#TituloModalUsuario").text("Agregar nuevo");
        $(".camposContrasena").css("display", "inline");
        $("#NuevaContrasena").removeAttr("disabled");
        $("#RepetirNuevaContrasena").removeAttr("disabled");
        $(".campoMostrarContrasena").css("display", "none");
        $("#NuevaContrasena").val("");
        $("#RepetirNuevaContrasena").val("");
    });



    $(document).on('click', '#verfotoUsuario', function() {
        $("#FotoUsuario").trigger("click");
    });

    $(document).on('change', '#FotoUsuario', function() {
        readURL(this, $("#verfotoUsuario"));
    });

    $(document).on('click', '#EliminarUsuario', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        Swal.fire({
          title: '¿Estás a punto de eliminar al usuario '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=usuarios&IDUsuario="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaUsuarios();
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario eliminado correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar usuario.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarUsuario', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=usuarios&IDUsuario="+id+"&tipo=ConsultarUsuario";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            $('#mostrarContrasena').prop('checked', false);
            $("#GuardarUsuario").attr('tipo', 'modificar');
            $("#GuardarUsuario").attr('attrid', id);
            $("#TituloModalUsuario").text("Modificar");
            var datos = JSON.parse($.trim(res));
            $("#NombreUsuario").val(datos.Nombre);
            $("#PrimerApellidoUsuario").val(datos.Primer_Apellido);
            $("#SegundoApellidoUsuario").val(datos.Segundo_Apellido);
            $("#CorreoUsuario").val(datos.Correo);
            $("#TipoUsuario").val(datos.Tipo_Usuario);
            $("#EstatusUsuario").val(datos.Estatus);
            $("#SucursalUsuario").val(datos.FK_Sucursal);
            $("#EstatusCuenta").val(datos.Activo);
            $("#ContraTemporal").val(datos.Temporal);
            $(".campoMostrarContrasena").css("display", "inline");
            $(".camposContrasena").css("display", "none");
            $("#NuevaContrasena").attr("disabled", true);
            $("#RepetirNuevaContrasena").attr("disabled", true);
            if (datos.Foto != "") {
                $("#verfotoUsuario img").attr('src', 'vistas/assets/archivos/fotosUsuarios/'+datos.Foto);
            }else{
                $("#verfotoUsuario img").attr('src', 'vistas/assets/archivos/default.jpg');
            }
            $("#NuevaContrasena").val("");
            $("#RepetirNuevaContrasena").val("");
            $("#ModalUsuario").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '#mostrarContrasena', function() {
        if ($(this).prop("checked") == true) {     
            $(".camposContrasena").css("display", "inline");
            $("#NuevaContrasena").removeAttr("disabled");
            $("#RepetirNuevaContrasena").removeAttr("disabled");
        }else{
            $(".camposContrasena").css("display", "none");
            $("#NuevaContrasena").attr("disabled", true);
            $("#RepetirNuevaContrasena").attr("disabled", true);
        }
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
                console.log(separa[(separa.length-1)]);
                
                if($.trim(separa[(separa.length-1)]) == "Capturista"){
                    $("#perfil1").prop('checked', true);
                }else if($.trim(separa[(separa.length-1)]) == "Vendedor"){
                    $("#perfil2").prop('checked', true)
                }else if($.trim(separa[(separa.length-1)]) == "Supervisor"){
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
                    cadena = "v_sucursales,1,1,1,0~v_proveedores,1,1,1,0~v_clientes,1,1,1,0~v_compras,0,0,0,0,0~v_ventas,0,0,0,0,0,0,0~v_importes,1,1,0~v_productos,1,1,1,0,0~v_inventario,1,1,1,0,0,0~v_categorias,1,1,1,0~v_zonas,0,0,0,0~v_areas,1,1,1,0~v_precios,0~v_impuestos,1,1,0,0~v_tickets,0~v_facturacion,0~v_usuarios,1,1,1,0,0~Capturista";
                }else if(btn.attr('id') == "perfil2"){
                    cadena = "v_sucursales,1,0,0,0~v_proveedores,1,0,0,0~v_clientes,1,0,0,0~v_compras,0,0,0,0,0~v_ventas,1,1,1,1,1,1,1~v_importes,1,1,1~v_productos,1,0,0,0,0~v_inventario,1,0,0,0,0,0~v_categorias,1,1,1,0~v_zonas,1,1,0,0~v_areas,1,1,0,0~v_precios,1~v_impuestos,1,1,1,1~v_tickets,1~v_facturacion,1~v_usuarios,0,0,0,0,0~Vendedor";
                    perfil = "Vendedor";
                }else if(btn.attr('id') == "perfil3"){
                    cadena = "v_sucursales,1,1,1,1~v_proveedores,1,1,1,1~v_clientes,1,1,1,1~v_compras,1,1,1,1,1~v_ventas,1,1,1,1,1,1,1~v_importes,1,1,1~v_productos,1,1,1,1,1~v_inventario,1,1,1,1,1,1~v_categorias,1,1,1,1~v_zonas,1,1,1,1~v_areas,1,1,1,1~v_precios,1~v_impuestos,1,1,1,1~v_tickets,1~v_facturacion,1~v_usuarios,1,1,0,0,0~Supervisor";
                    perfil = "Supervisor";
                }

                var separa = $.trim(cadena).split("~");

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
                var cadena = 'v_sucursales,0,0,0,0~v_proveedores,0,0,0,0~v_clientes,0,0,0,0~v_compras,0,0,0,0,0~v_ventas,0,0,0,0,0,0,0~v_importes,0,0,0~v_productos,0,0,0,0,0~v_inventario,0,0,0,0,0,0~v_categorias,0,0,0,0~v_zonas,0,0,0,0~v_areas,0,0,0,0~v_precios,0~v_impuestos,0,0,0,0~v_tickets,0~v_facturacion,0~v_usuarios,0,0,0,0,0~';
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

function TablaUsuarios(){
    ajaxMyDatatable({
        "table": $("#TablaUsuarios"), 
        "colums": [
            "Foto",
            "Nombre",
            "Usuario",
            "Estatus",
            "Permisos",
            "Acciones"
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "usuarios"
        }
    });
}