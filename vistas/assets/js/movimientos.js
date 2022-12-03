function v_categorias() {
    TablaCategorias(); 
    $('#FormCategorias').validate({
        rules: {
            NombreCategoria: {
                required: true
            },
        },
        messages: {
            NombreCategoria: {
                required: "El nombre de la categoria o familia es obligatorio"
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#GuardarCategoria").attr("tipo")+"&accion=categorias&IDCategoria="+$("#GuardarCategoria").attr("attrid")+"&Nombre="+$("#NombreCategoria").val()+"&Descripcion="+$("#DescripcionCategoria").val()
            var btn = $('#GuardarCategoria');
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
                    if ($("#GuardarCategoria").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificada";
                    }else{
                        var tipoAlerta = "guardada";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Categoria / familia '+tipoAlerta+' correctamente'
                    });
                    TablaCategorias(); 
                    $("#ModalCategorias").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarCategoria").attr("tipo")+' categoria / familia.'
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
  
}

function TablaCategorias(){
    ajaxMyDatatable({
        "table": $("#TablaCategorias"), 
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
            "accion": "categorias"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevaCategoria', function() {
        $("#GuardarCategoria").attr('tipo', "insertar");
        $("#GuardarCategoria").attr('attrid', "");
        $("#FormCategorias").trigger('reset');
        $("#TituloModalCategorias").text("Agregar nueva");
    });

    $(document).on('click', '#EliminarCategoria', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        console.log(id);
        Swal.fire({
          title: '¿Estás a punto de eliminar la categoria / familia '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=categorias&IDCategoria="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                console.log(res);
                if ($.trim(res) == "Correcto") {
                    TablaCategorias();
                    Swal.fire({
                        icon: 'success',
                        title: 'Categoria / familia eliminada correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar categoria / familia.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarCategoria', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=categorias&IDCategoria="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log(res);
            $("#GuardarCategoria").attr('tipo', 'modificar');
            $("#GuardarCategoria").attr('attrid', id);
            $("#TituloModalCategoria").text("Modificar");
            var datos = JSON.parse($.trim(res));
            $("#NombreCategoria").val(datos.Nombre);
            $("#DescripcionCategoria").val(datos.Descripcion);
            $('#ModalCategorias').modal('show');
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

});

