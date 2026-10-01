function v_tokens() {
    
    $('#FormTokens').validate({
        rules: {
            CodigoToken: {
                required: true
            },
            CantidadToken:{
                required: true
            },
        },
        messages: {
            CodigoToken: {
                required: "El código del token es obligatorio."
            },
            CantidadToken:{
                required: "La cantidad del descuento es obligatoria."
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#GuardarToken").attr("tipo")+"&accion=tokens&Codigo="+$("#CodigoToken").val()+"&Cantidad="+$("#CantidadToken").val();
            
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    if ($("#GuardarToken").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Token '+tipoAlerta+' correctamente'
                    });

                    tablaTokens();
                    $("#ModalTokens").modal("hide");
                }else if($.trim(res) == "Registrado"){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Este token se encuentra actualmente disponible, intenta con otro.'
                    });
                    console.log($.trim(res));
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarToken").attr("tipo")+' token.'
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

    tablaTokens();
}

function tablaTokens(){
    ajaxMyDatatable({
        "table": $("#tablaTokens"), 
        "colums": [
            "Codigo",
            "Cantidad",
            "Estatus",
            "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "tokens"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevoToken', function() {
        $("#GuardarToken").attr('tipo', "insertar");
        $("#GuardarToken").attr('attrid', "");
        $("#FormTokens").trigger('reset');
    });

    $(document).on('click', '.EliminarToken', function() {
        var btn = $(this);

        Swal.fire({
          title: '¿Estás seguro que quieres eliminar el token?',
          text: "Una vez eliminado ya no podrá ser recuperado ni utilizado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.value) {
                var data = "metodo=eliminar&accion=tokens&id="+btn.attr('attrID');
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        $("#carga").show();
                    }
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Token eliminado correctamente'
                        });

                        tablaTokens();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el Token.'
                        });
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                }).always(function() {
                    $("#carga").hide();
                }); 
            }
        });
    });

});

