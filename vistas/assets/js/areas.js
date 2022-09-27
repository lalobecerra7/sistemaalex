function v_areas() {
    $('#FormAreas').validate({
        rules: {
            NombreArea: {
                required: true
            },
        },
        messages: {
            NombreArea: {
                required: "El nombre del área es obligatorio"
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#GuardarArea").attr("tipo")+"&accion=areas&IDArea="+$("#GuardarArea").attr("attrid")+"&Nombre="+$("#NombreArea").val()+"&Descripcion="+$("#DescripcionArea").val()+"&Nivel="+$("#NivelArea").val();
            var btn = $('#GuardarArea');
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
                    if ($("#GuardarArea").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificada";
                    }else{
                        var tipoAlerta = "guardada";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Área '+tipoAlerta+' correctamente'
                    });
                    TablaAreas();
                    $("#ModalAreas").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarArea").attr("tipo")+' área.'
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

    TablaAreas();   
}

function TablaAreas(){
    ajaxMyDatatable({
        "table": $("#TablaAreas"), 
        "colums": [
            "Nombre",
            "Nivel",
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
            "accion": "areas"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevaArea', function() {
        $("#GuardarArea").attr('tipo', "insertar");
        $("#GuardarArea").attr('attrid', "");
        $("#FormAreas").trigger('reset');
        $("#TituloModalArea").text("Agregar nueva");
    });

    $(document).on('click', '#EliminarArea', function() {
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
    });

    $(document).on('click', '#ModificarArea', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=areas&IDArea="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            $("#GuardarArea").attr('tipo', 'modificar');
            $("#GuardarArea").attr('attrid', id);
            $("#TituloModalArea").text("Modificar");
            var datos = JSON.parse($.trim(res));
            $("#NombreArea").val(datos.Nombre);
            $("#DescripcionArea").val(datos.Descripcion);
            $("#NivelArea").val(datos.Nivel);
            $('#ModalAreas').modal('show');
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

});

