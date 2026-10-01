function v_vehiculos() {
	tablaVehiculos();

	$('#formVehiculos').validate({
        rules: {
            marcaVehiculo: {
                required: true
            },
            modeloVehiculo: {
                required: true
            }
        },
        messages: {
            marcaVehiculo: {
                required: "La marca es requerida."
            },
            modeloVehiculo: {
                required: "El modelo es requerido."
            }
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarVehiculo").attr('tipo')+"&accion=vehiculos&marca="+$.trim($("#marcaVehiculo").val())+"&modelo="+$.trim($("#modeloVehiculo").val())+"&matricula="+$.trim($("#matriculaVehiculo").val())+"&descripcion="+$.trim($("#descripcionVehiculo").val())+"&id="+$("#bGuardarVehiculo").attr('attrID');

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
                    if ($("#bGuardarVehiculo").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Vehículo '+tipoAlerta+' correctamente'
                    });

                    tablaVehiculos(); 
                    $("#modalVehiculo").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarVehiculo").attr("tipo")+' el vehículo.'
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

function tablaVehiculos() {
	ajaxMyDatatable({
        "table": $("#tablaVehiculos"), 
        "colums": [
            "Fecha",
            "Marca",
            "Modelo",
            "Matricula",
            "Descripcion",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "vehiculos"
        }
    });
}

jQuery(document).ready(function($) {
	$(document).on('click', '#bNuevoVehiculo', function() {
		$("#formVehiculos")[0].reset();

		$("#bGuardarVehiculo").attr('tipo', 'insertar');
		$("#modalVehiculo").modal('show');
	});

	$(document).on('click', '.bModificarVehiculo', function() {
		var padre = $(this).parent().parent();
		$("#formVehiculos")[0].reset();
		$("#marcaVehiculo").val(padre.children('td:eq(1)').text());
        $("#modeloVehiculo").val(padre.children('td:eq(2)').text());
        $("#matriculaVehiculo").val(padre.children('td:eq(3)').text());
		$("#descripcionVehiculo").val(padre.children('td:eq(4)').text());

		$("#bGuardarVehiculo").attr('attrID', $(this).attr('attrID'));
		$("#bGuardarVehiculo").attr('tipo', 'modificar');
		$("#modalVehiculo").modal('show');
	});

	$(document).on('click', '.bEliminarVehiculo', function() {
		var btn = $(this);
		Swal.fire({
			title: '¿Estás seguro de eliminar el vehículo?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
          	cancelButtonColor: '#d33',
			cancelButtonText: 'No, cancelar',
			confirmButtonText: 'Si, eliminar'
        }).then((result) => {
        	var data = "metodo=eliminar&accion=vehiculos&id="+btn.attr('attrID');

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
                        title: 'El vehículo ha sido eliminado correctamente'
                    });

                    tablaVehiculos(); 
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar el vehículo.'
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