function v_recibos() {
    
    $('#FormRecibos').validate({
        rules: {
            FechaRecibo: {
                required: true
            },
            MontoRecibo:{
                required: true
            },
            DescripcionRecibo:{
                required: true
            },
        },
        messages: {
            FechaRecibo: {
                required: "La fecha del recibo es obligatoria."
            },
            MontoRecibo:{
                required: "El monto del recibo es obligatorio."
            },
            DescripcionRecibo:{
                required: "La descripción del recibo es obligatoria."
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#GuardarRecibo").attr("tipo")+"&IDRecibo="+$("#GuardarRecibo").attr("attrid")+"&accion=recibos&FechaRecibo="+$("#FechaRecibo").val()+"&MontoRecibo="+$("#MontoRecibo").val()+"&DescripcionRecibo="+$("#DescripcionRecibo").val()+"&NombreProveedorRecibo="+$("#NombreProveedorRecibo").val();
            
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
                    if ($("#GuardarRecibo").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Recibo '+tipoAlerta+' correctamente'
                    });

                    tablaRecibos();
                    $("#ModalRecibos").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarRecibo").attr("tipo")+' recibo.'
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

    tablaRecibos();
}

function tablaRecibos(){
    ajaxMyDatatable({
        "table": $("#tablaRecibos"), 
        "colums": [
            "Folio",
            "Fecha",
            "Monto",
            "Descripcion",
            "Proveedor",
            "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "recibos"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevoRecibo', function() {
        $("#GuardarRecibo").attr('tipo', "insertar");
        $("#GuardarRecibo").attr('attrid', "");
        $("#FormRecibos").trigger('reset');
    });

    $(document).on('click', '.EliminarRecibo', function() {
        var btn = $(this);

        Swal.fire({
          title: '¿Estás seguro que quieres eliminar el recibo?',
          text: "Una vez eliminado ya no podrá ser recuperado ni utilizado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.value) {
                var data = "metodo=eliminar&accion=recibos&id="+btn.attr('attrID');
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
                            title: 'Recibo eliminado correctamente'
                        });

                        tablaRecibos();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el Recibo.'
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

    $(document).on('click', '.ModificarRecibo', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=recibos&IDRecibo="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log($.trim(res));
            
            $("#GuardarRecibo").attr('tipo', 'modificar');
            $("#GuardarRecibo").attr('attrid', id);
            var datos = JSON.parse($.trim(res));

            $("#FechaRecibo").val(datos.Fecha);
            $("#MontoRecibo").val(datos.Monto);
            $("#DescripcionRecibo").val(datos.Descripcion);
            $("#ModalRecibos").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '.ImprimirRecibo', function() {
        var IDRecibo = $(this).attr("attrid");
        window.open("controladores/recibos.php?id="+IDRecibo+"", '_blank');
    });


});

