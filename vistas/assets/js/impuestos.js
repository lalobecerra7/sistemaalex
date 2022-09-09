function v_impuestos() {
    
    $('#FormImpuestos').validate({
        rules: {
            NombreImpuesto: {
                required: true
            },
            PorcentajeImpuesto: {
                required: true,
                min: 0
            },
        },
        messages: {
            NombreImpuesto: {
                required: "El nombre del impuesto es requerido"
            },
            PorcentajeImpuesto: {
                required: "El porcentaje del impuesto es requerido",
                min: "Ingrese un valor mayor a 0"
            },
        },
        submitHandler: function(form) { 
            var ticket = 0;
            if ($("#ImpuestoTicket").prop("checked") == true) {
                ticket = 1;
            }
            var producto = 0;
            if ($("#ImpuestoProducto").prop("checked") == true) {
                producto = 1;
            }


            var data = "metodo="+$("#GuardarImpuesto").attr("tipo")+"&accion=impuestos&IDImpuesto="+$("#GuardarImpuesto").attr("attrid")+"&Porcentaje="+$("#PorcentajeImpuesto").val()+"&Nombre="+$("#NombreImpuesto").val()+"&Clave="+$("#ClaveImpuesto").val()+"&Clase="+$("#ClaseImpuesto").val()+"&Tipo="+$("#TipoFactorImpuesto").val()+"&Ticket="+ticket+"&Producto="+producto;
            var btn = $('#GuardarImpuesto');
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
                    if ($("#GuardarImpuesto").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Impuesto '+tipoAlerta+' correctamente'
                    });
                    TablaImpuestos();
                    $("#ModalImpuestos").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarImpuesto").attr("tipo")+' impuesto.'
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

    TablaImpuestos();
}

function TablaImpuestos(){
    ajaxMyDatatable({
        "table": $("#TablaImpuestos"), 
        "colums": [
            "Nombre",
            "Porcentaje",
            "Detalles",
            "Tipo de Impuesto",
            "Predeterminado",
            "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "impuestos"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevoImpuesto', function() {
        $("#GuardarImpuesto").attr('tipo', "insertar");
        $("#GuardarImpuesto").attr('attrid', "");
        $("#FormImpuestos").trigger('reset');
        $("#TituloModalImpuestos").text("Agregar nuevo");
    });

    /*$(document).on('click', '#EliminarArea', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        Swal.fire({
          title: '¿Estás a punto de eliminar el área '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=areas&IDArea="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaAreas();
                    Swal.fire({
                        icon: 'success',
                        title: 'Área eliminada correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar área.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });*/

    $(document).on('click', '#ModificarImpuesto', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=impuestos&IDImpuesto="+id+"&tipo=ConsultarImpuesto";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            $("#GuardarImpuesto").attr('tipo', 'modificar');
            $("#GuardarImpuesto").attr('attrid', id);
            $("#TituloModalImpuestos").text("Modificar");
            var datos = JSON.parse($.trim(res));
            $("#NombreImpuesto").val(datos.Nombre);
            $("#ClaveImpuesto").val(datos.Clave_CFDI);
            $("#ClaseImpuesto").val(datos.Clase);
            $("#TipoFactorImpuesto").val(datos.Tipo_Factor);
            $("#PorcentajeImpuesto").val(datos.Porcentaje);
            if (datos.Ticket == 1) {
                $("#ImpuestoTicket").prop("checked", true);
            }
            if (datos.Producto == 1) {
                $("#ImpuestoProducto").prop("checked", true);
            }
            $("#ModalImpuestos").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('change', '#ImpuestoPredeterminado', function() {
        var id = $(this).attr('attrid');
        var valor = 0;
        if ($(this).prop("checked") == true) {
            valor = 1;
        }
        var data = "metodo=detalles&accion=impuestos&tipo=ImpuestoPredeterminado&IDImpuesto="+id+"&Valor="+valor;
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
                    title: 'Impuesto modificado correctamente'
                });
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inesperado al modificar impuesto.'
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
    });

});

