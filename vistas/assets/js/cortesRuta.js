var ventas_cliente = [];

function check_and_push(idCliente, data){
    const existingObject = ventas_cliente.find((indice) => indice.cliente === idCliente);
    if(!existingObject){
        ventas_cliente.push(data);
    }
}

function remove_venta(idCliente, idVenta){
    ventas_cliente.forEach(function(obj){
        if(obj.cliente === idCliente){
            var indexToRemove = obj.ventas.indexOf(idVenta);

            if(indexToRemove !== -1){
                obj.ventas.splice(indexToRemove, 1);
            }
        }
    })
}

function add_venta(idCliente, idVenta){
    const clientRow = ventas_cliente.find(obj => obj.cliente === idCliente);

    if(clientRow){
        if(!clientRow.ventas.includes(idVenta)){
            clientRow.ventas.push(idVenta);
        }
    }
}

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
            "accion": "cortesRuta",
            "ventas": JSON.stringify([1,3]),
        }
    });
}

function tablaDetallesClientes(idCliente, ventas_obj){
    if(ventas_obj.length > 0){
        var data = `metodo=consultar&accion=cortesRuta&tipo=clientesDetalles&idCliente=${idCliente}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}&ventas=${JSON.stringify(ventas_obj)}`;

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
    }else{
        $('#lista-ventas-cliente').html('<h1>No hay nada we</h1>');
        $("#agregarVentaACorte").attr('clienteId', idCliente);
    }
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
        ventas_cliente = [];
    });

	$(document).on('click', '#bGenerarClientes', function() {
        $("#tablaClientesruta").removeClass('d-none');
		tablaClientesRuta();
	});

    $(document).on('click', '.bDetallesCorteClientes', function() {
        var clientID = $(this).attr('attrID');
        var data = `metodo=consultar&accion=cortesRuta&tipo=ventas_cliente&idCliente=${$(this).attr('attrID')}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}`;
        var jsonData;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            jsonData = JSON.parse(res);
            check_and_push(jsonData.cliente, jsonData);
            
            const ventas_val = ventas_cliente.find(obj => obj.cliente === clientID);

            $("#modalCorteClientes").modal('show');
            tablaDetallesClientes(clientID, ventas_val ? ventas_val.ventas : jsonData.ventas);
        })
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
            remove_venta(btn.attr('ID_Cliente'), btn.attr('ID_Venta'))

            const ventas_val = ventas_cliente.find(obj => obj.cliente === btn.attr('ID_Cliente'));
            tablaDetallesClientes(btn.attr('ID_Cliente'), ventas_val.ventas);
            if(ventas_val.ventas.length > 0){
            }else{
                //TODO: mostrar que no hay nada
            }
        });
    });

    $(document).on('click', '#agregarVentaACorte', function(){
        $("#modalAgregarVenta").modal('show');
        var clientId = $(this).attr('clienteId');
        var clientVentas = ventas_cliente.find(obj => obj.cliente === clientId).ventas;
        
        var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${$(this).attr('clienteId')}&ventas=${JSON.stringify(clientVentas)}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            if($.trim(res).length > 0){
                $("#lista-ventas-cliente-excluidas").html($.trim(res));
            }else{
                $("#lista-ventas-cliente-excluidas").html('<h1>No hay nada we</h1>');
            }
        }).fail(function(){
            console.log('Error ajax')
        })
        
    });

    $(document).on('click', '.agregarDeCorteDeRuta', function(){
        var btn = $(this);
        Swal.fire({
            title: 'Agregar venta a corte',
            text: '¿Estas seguro de agregar esta venta al corte de ruta actual?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, agergar'
        }).then((result) => {
            add_venta(btn.attr('ID_Cliente'), btn.attr('ID_Venta'))

            const ventas_val = ventas_cliente.find(obj => obj.cliente === btn.attr('ID_Cliente'));
            var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${btn.attr('ID_Cliente')}&ventas=${JSON.stringify(ventas_val.ventas)}`;
            tablaDetallesClientes(btn.attr('ID_Cliente'), ventas_val.ventas);

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            }).done(function(res){
                if($.trim(res).length > 0){
                    $("#lista-ventas-cliente-excluidas").html($.trim(res));
                }else{
                    $("#lista-ventas-cliente-excluidas").html('<h1>No hay nada we</h1>');
                }
            }).fail(function(){
                console.log('Error ajax')
            })
        });
    })


    $(document).on('dblclick', '#tablaClientesruta tbody td', function() {
        if($(this).children('span.orden').text() != ""){
            const searchRegExp = new RegExp(',', 'g'); 

            var idClienteVar = $(this).children('span.orden').attr('attrID');
            $(this).html('<input type="number" style="width: 100px;" class="inputOrdenRuta" attrID="'+idClienteVar+'" value="'+$(this).text().replace('$', '').replace(searchRegExp, '')+'">');
            $(this).children('input.inputOrdenRuta').focus();
        }

    });


    $(document).on('focusout', '.inputOrdenRuta', function() {
        var input = $(this);
        var padre = $(this).parent();
        
        console.log($(this).val())
        console.log($(this).attr('attrID'))
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
                padre.html('<span class="orden" attrID = "'+input.attr('attrID')+'">'+input.val()+'</span>');
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