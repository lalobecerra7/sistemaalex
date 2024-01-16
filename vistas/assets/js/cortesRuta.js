function v_cortesRuta() {
	tablaCortesRuta();

	$('#formCorteDeRuta').validate({
        rules: {
            rutasCorte: {
                required: true
            },
            FechaInicioCorte: {
                required: true
            },
            FechaFinCorte: {
                required: true
            }
        },
        messages: {
            rutasCorte: {
                required: "La marca es requerida."
            },
            FechaInicioCorte: {
                required: "La fecha de inicio es requerida."
            },
            FechaFinCorte: {
                required: "La fecha de fin es requerida."
            }
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarCorte").attr('tipo')+"&accion=cortesRuta&tipo=insertar&rutasCorte="+$.trim($("#rutasCorte").val())+"&FechaInicioCorte="+$.trim($("#FechaInicioCorte").val())+"&FechaFinCorte="+$.trim($("#FechaFinCorte").val())+"&selectChofer="+$.trim($("#selectChofer").val())+"&selectVehiculo="+$.trim($("#selectVehiculo").val())+"&id="+$("#bGuardarCorte").attr('attrID');

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
                    if ($("#bGuardarCorte").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Corte '+tipoAlerta+' correctamente'
                    });

                    tablaCortesRuta(); 
                    $("#modalCorteRuta").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarCorte").attr("tipo")+' la ruta.'
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

function tablaCortesRuta() {
	ajaxMyDatatable({
        "table": $("#tablaCortesRuta"), 
        "colums": [
            "Fecha",
            "Ruta",
            "Fecha_Inicio",
            "Fecha_Fin",
            "Total",
            "Verificado",
            "Detalles",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "tipo": "cortesRutas",
            "accion": "cortesRuta"
        }
    });
}

function tablaDetallesClientes(idCliente){
    var data = `metodo=consultar&accion=cortesRuta&tipo=clientesDetalles&idCliente=${idCliente}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}`;

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    }).done(function(res){
        $('#lista-ventas-cliente').html($.trim(res));
        $("#agregarVentaACorte").attr('clienteId', idCliente);
    }).fail(function(){
        console.log('Error ajax');
    })
}

function tablaClientesRuta() {
    ajaxMyDatatable({
        "table": $("#tablaClientesRuta"), 
        "colums": [
            "Orden_Ruta",
            "Nombre",
            "Domicilio",
            "Total",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "tipo": "clientesRuta",
            "Ruta": $("#rutasCorte").val(),
            "FechaInicioCorte": $("#FechaInicioCorte").val(),
            "FechaFinCorte": $("#FechaFinCorte").val(),
            "accion": "cortesRuta"
        }
    });
}


jQuery(document).ready(function($) {
    $(document).on('click', '#bNuevoCorteRuta', function() {
        $("#modalCorteRuta").modal('show');
        $("#formCorteDeRuta")[0].reset();
        $("#bGuardarCorte").attr('tipo', 'insertar');
        $("#tablaClientesruta").addClass('d-none');
    });

	$(document).on('click', '#bGenerarClientes', function() {
        $("#tablaClientesruta").removeClass('d-none');
		tablaClientesRuta();
	});

    $(document).on('click', '.bDetallesCorteClientes', function() {
        $("#modalCorteClientes").modal('show');
        tablaDetallesClientes($(this).attr('attrID'));
    });

    $(document).on('click', '.borrarDeCorteDeRuta', function() {
        var btn = $(this);
        Swal.fire({
            title: 'Quitar venta del corte de ruta',
            text: '¿Estas seguro de quitar esta venta del corte de ruta actual?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            var data = "metodo=insertar&accion=cortesRuta&tipo=eliminarTemporal&id="+btn.attr('ID_Venta');

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
                        title: 'El detalle ha sido eliminado correctamente'
                    });

                    tablaDetallesClientes(btn.attr('ID_Venta'));
                    tablaClientesRuta();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar el detalle.'
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

    $(document).on('click', '#agregarVentaACorte', function(){
        console.log($(this).attr('clienteId'));

        var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${$(this).attr('clienteId')}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            $("#agregarVentasContainer").html($.trim(res));
        }).fail(function(){
            console.log('Error ajax')
        })
    });

    $(document).on('dblclick', '#tablaClientesruta tbody td', function() {
        if($(this).children('span.orden').text() != ""){
            const searchRegExp = new RegExp(',', 'g'); 

            $(this).html('<input type="number" style="width: 100px;" class="inputOrdenRuta" attrID="'+$(this).attr('attrID')+'" value="'+$(this).text().replace('$', '').replace(searchRegExp, '')+'">');
            $(this).children('input.inputOrdenRuta').focus();
        }
    });


    $(document).on('focusout', '.inputOrdenRuta', function() {
        var input = $(this);
        var padre = $(this).parent();
        

        var data = "metodo=modificar&accion=cortesRuta&tipo=ordenRuta&valor="+$(this).val()+"&id="+$(this).attr('attrID');
    
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
                padre.html('<span class="orden" attrID = "'+$(this).attr('attrID')+'">'+input.val()+'</span>');
                moneda();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inesperado al cambiar el orden.'
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


	/*$(document).on('click', '.bModificarVehiculo', function() {
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
	});*/
});