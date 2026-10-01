function v_choferes() {
	tablaChoferes();

	$('#formChoferes').validate({
        rules: {
            nombreChofer: {
                required: true
            },
            primerApellidoChofer: {
                required: true
            }
        },
        messages: {
            nombreChofer: {
                required: "El nombre es requerido."
            },
            primerApellidoChofer: {
                required: "El primer apellido es requerido."
            }
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarChofer").attr('tipo')+"&accion=choferes&nombre="+$.trim($("#nombreChofer").val())
            +"&primerApellido="+$.trim($("#primerApellidoChofer").val())+"&segundoApellido="+$.trim($("#segundoApellidoChofer").val())
            +"&vehiculosChofer="+$("#vehiculosChofer").val()+"&rfcChofer="+$.trim($("#rfcChofer").val())+"&licenciaChofer="+$.trim($("#licenciaChofer").val())
            +"&tipoChofer="+$("#tipoChofer").val()+"&id="+$("#bGuardarChofer").attr('attrID');
            

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
                    if ($("#bGuardarChofer").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Chofer '+tipoAlerta+' correctamente'
                    });

                    tablaChoferes(); 
                    $("#modalChofer").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarChofer").attr("tipo")+' el chofer.'
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

function tablaChoferes() {
	ajaxMyDatatable({
        "table": $("#tablaChoferes"), 
        "colums": [
            "Fecha",
            "Nombre",
            "Primer_Apellido",
            "Segundo_Apellido",
            "Vehiculo",
            "Detalles",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "choferes"
        }
    });
}

jQuery(document).ready(function($) {
	$(document).on('click', '#bNuevoChofer', function() {
		$("#formChoferes")[0].reset();

		$("#bGuardarChofer").attr('tipo', 'insertar');
		$("#modalChofer").modal('show');
	});

	$(document).on('click', '.bModificarChofer', function() {
		var padre = $(this).parent().parent();
		$("#formChoferes")[0].reset();
		$("#nombreChofer").val(padre.children('td:eq(1)').text());
        $("#primerApellidoChofer").val(padre.children('td:eq(2)').text());
        $("#segundoApellidoChofer").val(padre.children('td:eq(3)').text());

        $("#rfcChofer").val(padre.children('td:eq(5)').children('b.rfcChofer').text());
        $("#licenciaChofer").val(padre.children('td:eq(5)').children('b.licenciaChofer').text()); 
        $("#tipoChofer").val(padre.children('td:eq(5)').children('b.tipoChofer').text()); 

		$("#bGuardarChofer").attr('attrID', $(this).attr('attrID'));
		$("#bGuardarChofer").attr('tipo', 'modificar');
		$("#modalChofer").modal('show');
	});

	$(document).on('click', '.bEliminarChofer', function() {
		var btn = $(this);
		Swal.fire({
			title: '¿Estás seguro de eliminar el chofer?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
          	cancelButtonColor: '#d33',
			cancelButtonText: 'No, cancelar',
			confirmButtonText: 'Si, eliminar'
        }).then((result) => {
        	var data = "metodo=eliminar&accion=choferes&id="+btn.attr('attrID');

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
                        title: 'El chofer ha sido eliminado correctamente'
                    });

                    tablaChoferes(); 
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar el chofer.'
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