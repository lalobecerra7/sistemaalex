function v_compras() {
	TablaReporteCompras();
}

jQuery(document).ready(function($) {

	$(document).on('click', '#EliminarCompra', function() {
		var btn = $(this);
		Swal.fire({
	        title: '¿Estás seguro que quieres eliminar la compra con el folio '+$(this).attr("folio")+'?',
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        cancelButtonText: '¡No, cancelar!',
	        confirmButtonText: '¡Si, eliminar!'
	    }).then((result) => {
	        if (result.value) {
	        	var data = "metodo=eliminar&accion=compras&IDCompra="+$(this).attr('attrid');
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: data,
					beforeSend: function() {
					    progressBoton(btn);
					}
				})
				.done(function(res) {
					if ($.trim(res) == "Correcto") {
						Swal.fire({
							icon: 'success',
							title: 'Compra eliminada correctamente'
						});
						TablaReporteCompras();
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Error inesperado al eliminar la compra.'
						});
						console.log($.trim(res));
					}
				})
				.fail(function() {
					console.log("Error ajax");
				})
				.always(function() {
					unprogressBoton(btn);
				});
			}    
		});	  
	});

	$(document).on('click', '#CancelarVenta', function() {
		var btn = $(this);
		Swal.fire({
	        title: '¿Estás seguro que quieres cancelar la venta con el folio '+$(this).attr("folio")+'?',
	        icon: 'warning',
	        html: '<label>Motivo de cancelación</label><br><input type="text" id="MotivoCancelarVenta" class="swal2-input" placeholder="Motivo de cancelación">',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        cancelButtonText: '¡No, cancelar!',
	        confirmButtonText: '¡Si, continuar!'
	    }).then((result) => {
	        if (result.value) {
	        	var motivocancelar = $("#MotivoCancelarVenta").val();
	        	Swal.fire({
			        title: '¿Que deseas hacer con la existencia de los productos?',
			        icon: 'warning',
			        showCancelButton: true,
			        confirmButtonColor: '#3085d6',
			        cancelButtonColor: '#d33',
			        cancelButtonText: 'Nada',
			        confirmButtonText: 'Regresar a inventario'
			    }).then((result) => {
			    	var regresarInventario = "No";
			        if (result.value) {
			        	regresarInventario = "Si";
			        }else{
			        	regresarInventario = "No";
			        }
			        var data = "metodo=modificar&accion=ventas&IDVenta="+$(this).attr('attrid')+"&Regresar="+regresarInventario+"&Motivo="+motivocancelar;
					$.ajax({
						url: 'index.php',
						type: 'POST',
						data: data,
						beforeSend: function() {
						    progressBoton(btn);
						}
					})
					.done(function(res) {
						if ($.trim(res) == "Correcto") {
							Swal.fire({
								icon: 'success',
								title: 'Venta cancelada correctamente'
							});
							TablaReporteVentas();
						}else{
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Error inesperado al cancelar la venta.'
							});
							console.log($.trim(res));
						}
					})
					.fail(function() {
						console.log("Error ajax");
					})
					.always(function() {
						unprogressBoton(btn);
					});
			    });
			}    
		});	  
	});

	$(document).on('click', '#ImprimirTicketVenta', function() {
		var idVenta = $(this).attr("attrid");
		var altura=50;
        var anchura=310;

        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));

		window.open("controladores/ticket.php?id="+idVenta, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
	});

	$(document).on('click', '#VerProductosCompra', function() {
		var folio = $(this).attr("folio");
		var id = $(this).attr("attrid");
		$("#ModalVerProductosCompra").modal("show");
		$("#FolioCompraProductos").text(folio);

		var data = "metodo=detalles&accion=compras&tipo=productos&IDCompra="+id;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			$("#tbodyVerProductosCompra").html(res);
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});

	$(document).on('click', '#VerHistorialPagos', function() {
		var folio = $(this).attr("folio");
		var id = $(this).attr("attrid");
		$("#ModalVerHistorialPagos").modal("show");
		$("#FolioCompraPagos").text(folio);

		var data = "metodo=detalles&accion=compras&tipo=historialPagos&IDCompra="+id;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			$("#tbodyVerHistorialPagos").html(res);
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});
});

$(document).on('click', '#PagoCompra', function() {
	var id = $(this).attr("attrid");

	var data = "metodo=detalles&accion=compras&tipo=pago&IDCompra="+id;
	$.ajax({
		url: 'index.php',
		type: 'POST',
		data: data,
	})
	.done(function(res) {
		console.log(res);
		var datos = JSON.parse(res);
		var restante = (parseFloat(datos.Total)-parseFloat(datos.TotalPagos));
		$('#Proveedor').text(datos.Proveedor);
		$('#TotalCompra').text(new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(datos.Total));
		$('#Pagos').text(new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(datos.TotalPagos));
		$('#Restante').text(new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(restante));
		$('#ModalPagoCompra').modal('show');
		$('#GuardarPago').attr('attrid', id);
	})
	.fail(function() {
		console.log("Error ajax");
	});
});

$(document).on('click', '#GuardarPago', function() {
	var id = $(this).attr("attrid");
	console.log(id);
	$('#FormPagoCompra').validate({
        rules: {
            ImportePagoCompra: {
                required: true
            },
            ConceptoPago: {
                required: true
            },
            TipoDePago: {
                required: true
            }
        },
        messages: {
            ImportePagoCompra: {
                required: "El importe es obligatorio"
            },
            ConceptoPago: {
                required: "El concepto es obligatorio"
            },
            TipoDePago: {
                required: "El tipo de pago es obligatorio"
            }
        },
        submitHandler: function(form) { 
			console.log('pagooooo');
            if($('#ImportePagoCompra').val() == '' || $('#ImportePagoCompra').val() == 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El importe debe ser mayor a $0'
                });
            }else {

                var data = new FormData(document.getElementById('FormPagoCompra'));
                data.append('metodo', 'insertar');
                data.append('accion', 'compras');
                data.append('IDCompra', id);

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {
                        Swal.fire({
							icon: 'success',
							title: 'Pago registrado correctamente'
						});
						TablaReporteCompras();
                        $("#ModalPagoCompra").modal("hide");
                    }else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Error inesperado al registrar la compra.'
						});
						console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                })  
            }              
        }
	});
});

function TablaReporteCompras(){
	ajaxMyDatatable({
		"table": $("#TablaReporteCompras"), 
		"colums": [
			"Datos",
			"Proveedor",
			"Total",
			"Detalles",
			"Acciones"
		], 
		"totals":[
			"Datos",
			"Proveedor",
			"Total",
			"Detalles",
			"Acciones"
		],
		"sort": [
			2,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "compras"
		}
	});
}
