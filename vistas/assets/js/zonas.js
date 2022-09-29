function v_zonas() {
    console.log("entro a la funcion");
    $('#FormZonas').validate({
        rules: {
            NombreZona: {
                required: true
            },
        },
        messages: {
            NombreZona: {
                required: "El nombre de la zona es obligatorio"
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#GuardarZona").attr("tipo")+"&accion=zonas&IDZona="+$("#GuardarZona").attr("attrid")+"&Nombre="+$("#NombreZona").val()+"&Descripcion="+$("#DescripcionZona").val()
            var btn = $('#GuardarZona');
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
                    if ($("#GuardarZona").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificada";
                    }else{
                        var tipoAlerta = "guardada";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Zona '+tipoAlerta+' correctamente'
                    });
                    TablaZonas();
                    $("#ModalZonas").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarZona").attr("tipo")+' zona.'
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

    TablaZonas();   
}

function TablaZonas(){
    ajaxMyDatatable({
        "table": $("#TablaZonas"), 
        "colums": [
            "Nombre",
            "Descripcion",
            "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "zonas"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevaZona', function() {
        $("#GuardarZona").attr('tipo', "insertar");
        $("#GuardarZona").attr('attrid', "");
        $("#FormZonas").trigger('reset');
        $("#TituloModalZona").text("Agregar nueva");
    });

    $(document).on('click', '#EliminarZona', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        Swal.fire({
          title: '¿Estás a punto de eliminar la zona '+nombre+'?',
          text: "Una vez eliminada ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=zonas&IDZona="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaZonas();
                    Swal.fire({
                        icon: 'success',
                        title: 'Zona eliminada correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar zona.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarZona', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=zonas&IDZona="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            $("#GuardarZona").attr('tipo', 'modificar');
            $("#GuardarZona").attr('attrid', id);
            $("#TituloModalZona").text("Modificar");
            var datos = JSON.parse($.trim(res));
            $("#NombreZona").val(datos.Nombre);
            $("#DescripcionZona").val(datos.Descripcion);
            $('#ModalZonas').modal('show');
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

});

