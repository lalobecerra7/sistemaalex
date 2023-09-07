function v_rutas() {
	tablaRutas();

	$('#formRutas').validate({
        rules: {
            nombreRuta: {
                required: true
            }
        },
        messages: {
            nombreRuta: {
                required: "El nombre es requerido."
            }
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarRuta").attr('tipo')+"&accion=rutas&nombre="+$.trim($("#nombreRuta").val())+"&descripcion="+$.trim($("#descripcionRuta").val())+"&id="+$("#bGuardarRuta").attr('attrID');

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
                    if ($("#bGuardarRuta").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificada";
                    }else{
                        var tipoAlerta = "guardada";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Ruta '+tipoAlerta+' correctamente'
                    });

                    tablaRutas(); 
                    $("#modalRuta").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarRuta").attr("tipo")+' la ruta.'
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

function tablaRutas() {
	ajaxMyDatatable({
        "table": $("#tablaRutas"), 
        "colums": [
            "Fecha",
            "Nombre",
            "Descripcion",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "rutas"
        }
    });
}

jQuery(document).ready(function($) {
	$(document).on('click', '#bontonNuevaRuta', function() {
		$("#formRutas")[0].reset();

		$("#bGuardarRuta").attr('tipo', 'insertar');
		$("#modalRuta").modal('show');
	});

	$(document).on('click', '.bModificarRuta', function() {
		var padre = $(this).parent().parent();
		$("#formRutas")[0].reset();
		$("#nombreRuta").val(padre.children('td:eq(1)').text());
		$("#descripcionRuta").val(padre.children('td:eq(2)').text());

		$("#bGuardarRuta").attr('attrID', $(this).attr('attrID'));
		$("#bGuardarRuta").attr('tipo', 'modificar');
		$("#modalRuta").modal('show');
	});

	$(document).on('click', '.bEliminarRuta', function() {
		var btn = $(this);
		Swal.fire({
			title: '¿Estás seguro de eliminar la ruta?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
          	cancelButtonColor: '#d33',
			cancelButtonText: 'No, cancelar',
			confirmButtonText: 'Si, eliminar'
        }).then((result) => {
        	var data = "metodo=eliminar&accion=rutas&id="+btn.attr('attrID');

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
                        title: 'La ruta ha sido eliminada correctamente'
                    });

                    tablaRutas(); 
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar la ruta.'
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
});