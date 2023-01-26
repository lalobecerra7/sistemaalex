function v_ventas() {
	TablaReporteVentas();

	$('#FormAbrirCaja').validate({
        rules: {
            MontoInicialCaja: {
                required: true,
                min: 0,
            },
        },
        messages: {
            MontoInicialCaja: {
                required: "Ingresa el monto inicial de la caja"
            },
        },
        submitHandler: function(form) { 

            var data = "metodo=detalles&accion=ventas&tipo=AbrirCaja&MontoAbrir="+$("#MontoInicialCaja").val();
			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: data,
				beforeSend: function() {
				    $("#carga").show();
				}
			})
			.done(function(res) {
				var datos = res.split("~");
				if ($.trim(datos[0]) == "Correcto") {
					$("#cargarHacerVenta").trigger("click");	
					$("#ModalAbrirCaja").modal("hide");
				}else{
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Error inesperado al abrir caja.'
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

    $('#formCancelarFactura').validate({
        rules: {
            motivoCancelarFactura: {
                required: true
            }
        },
        messages: {
            motivoCancelarFactura: {
                required: 'El motivo es requerido'
            },
            folioSustituye: {
                required: 'El folio es requerido'            
            }
        },
        submitHandler: function(form) { 
        	var btn = $("#bFormCancelarFactura");
			
			Swal.fire({
		        title: '¿Estás seguro que quieres cancelar la venta y la factura?',
		        icon: 'warning',
		        showCancelButton: true,
		        confirmButtonColor: '#3085d6',
		        cancelButtonColor: '#d33',
		        cancelButtonText: '¡No, cancelar!',
		        confirmButtonText: '¡Si, continuar!'
		    }).then((result) => {
		        if (result.value) {
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
				        }

				        var data = "metodo=detalles&accion=facturacion&id="+$(btn).attr('attrid')+"&regresar="+regresarInventario+"&motivo="+$("#motivoCancelarFactura").val()+"&folio="+$("#folioSustituye").val();
						
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
									title: 'Venta y factura cancelada correctamente'
								});

								TablaReporteVentas();
							}else{
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Error inesperado al cancelar la venta y la factura.'
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
        }
    });  
}

jQuery(document).ready(function($) {

	$(document).on('click', '#BotonNuevaVenta', function() {
		var data = "metodo=detalles&accion=ventas&tipo=ConsultarCaja";
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			if ($.trim(res) == "Abierta") {
				$("#cargarHacerVenta").trigger("click");	
			}else{
				$("#ModalAbrirCaja").modal("show");
			}
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});

	$(document).on('change', '#motivoCancelarFactura', function() {
		if($(this).val() == '01'){
			$("#folioSustituye").prop('required', true);
		}else{
			$("#folioSustituye").prop('required', false);
		}
	});

	$(document).on('click', '#EliminarVenta', function() {
		var btn = $(this);
		Swal.fire({
	        title: '¿Estás seguro que quieres eliminar la venta con el folio '+$(this).attr("folio")+'?',
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        cancelButtonText: '¡No, cancelar!',
	        confirmButtonText: '¡Si, eliminar!'
	    }).then((result) => {
	        if (result.value) {
	        	var data = "metodo=eliminar&accion=ventas&IDVenta="+$(this).attr('attrid');
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
							title: 'Venta eliminada correctamente'
						});
						TablaReporteVentas();
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Error inesperado al eliminar la venta.'
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

	$(document).on('click', '.CancelarVenta', function() {
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
			        var data = "metodo=modificar&accion=ventas&IDVenta="+$(btn).attr('attrid')+"&Regresar="+regresarInventario+"&Motivo="+motivocancelar+"&IDSucursal="+btn.attr("sucursal");
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

	$(document).on('click', '.CancelarFactura', function() {
		$("#folioSustituye").prop('required', false);
		$("#bFormCancelarFactura").attr('attrID', $(this).attr('attrID'));
		document.getElementById('formCancelarFactura').reset();
		$("#modalCancelarFactura").modal('show');
	});

	$(document).on('click', '#ImprimirTicketVentaSinCaja', function() {
		var idVenta = $(this).attr("attrid");
		var sucursal = $(this).attr("sucursal");
		var altura=50;
        var anchura=310;

        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
        console.log(sucursal);
		window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+sucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
	});

	$(document).on('click', '#VerProductosVenta', function() {
		var folio = $(this).attr("folio");
		var id = $(this).attr("attrid");
		$("#ModalVerProductosReporteVenta").modal("show");
		$("#FolioVentasProductos").text(folio);

		var data = "metodo=detalles&accion=ventas&tipo=productos&IDVenta="+id;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			$("#tbodyVerProductosVenta").html(res);
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});

	$(document).on('click', '.verImpuestosProducto', function() {
		var id = $(this).attr("attrid");
		var nombre = $(this).attr("nombre");
		$("#ModalVerImpuestosProducto").modal("show");
		$("#NombreProductoImpuesto").text(nombre);
		var data = "metodo=detalles&accion=ventas&tipo=impuestos&IDDetalle="+id;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			$("#tbodyVerImpuestosProducto").html(res);
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});

	$(document).on('click', '#DevolverVenta', function() {
		var idventa = $(this).attr("attrid");
		$("#GuardarDevolucion").attr("attrid", idventa);
		$("#GuardarDevolucion").attr("sucursal", $(this).attr("sucursal"));
		$("#DevolverTodaVenta").attr("attrid", idventa);
		$("#DevolverTodaVenta").attr("sucursal", $(this).attr("sucursal"));
		$("#FolioVentaDevolucion").text($(this).attr("folio"));
		TablaProductosDevolucion(idventa);
		moneda();
	});

	$(document).on('click', '#GuardarDevolucion', function() {
		var productos = new Array();
		var idventa = $(this).attr("attrid");
		var idsucursal = $(this).attr("sucursal");
		$("#TablaProductosDevolucion tbody tr").each(function(index, el) {
			var iddetalle = $(this).children("td:eq(6)").find(".devolverProducto").attr("attrid");
			var cantidad = $(this).children("td:eq(6)").find(".devolverProducto").val();
			var maximo = $(this).children("td:eq(1)").text();
			if (parseFloat(cantidad) > 0 && parseFloat(cantidad) <= parseFloat(maximo)) {
				productos.push([iddetalle, cantidad]);
			}else{
				$(this).children("td:eq(6)").find(".devolverProducto").val(0);
			}
		});
		if (productos.length > 0) {
			Swal.fire({
			    title: '¿Que quieres hacer con estos productos?',
			    html: `
			    <select name="AccionesDevolucion" id="AccionesDevolucion" class="form-control">
			    	<option value="Nada">Nada</option>
	                <option value="Inventario">Devolver a inventario</option>
	                <option value="Merma">Registrar como merma</option>
            	</select>`,
				icon:  'info',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Continuar',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.value) {
					var acciones = $("#AccionesDevolucion").val(); 
					var data = "metodo=detalles&accion=ventas&tipo=GuardarDevolucionProductos&idventa="+idventa+"&producto="+JSON.stringify(productos)+"&acciones="+acciones+"&sucursal="+idsucursal;
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
								title: 'Devolución registrada correctamente',
							});
							$("#ModalDevolucionVenta").modal("hide");
							ComprobarDevuelta(idventa);
							TablaReporteVentas();

						}else{
							Swal.fire({
								icon: 'error',
						        title: 'Oops...',
							    text: 'Error al guardar devolución.'
							});
							console.log(res);
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
		}else{
			Swal.fire({
				icon: 'error',
				title: 'No se puede guardar una devolución sin productos',
				timer: 1300
			});
		}
	});


	$(document).on('click', '#DevolverTodaVenta', function() {
		var productos = new Array();
		var idventa = $(this).attr("attrid");
		var idsucursal = $(this).attr("sucursal");
		$("#TablaProductosDevolucion tbody tr").each(function(index, el) {
			var iddetalle = $(this).children("td:eq(6)").find(".devolverProducto").attr("attrid");
			var cantidad = $(this).children("td:eq(6)").find(".devolverProducto").attr("max");
			var maximo = $(this).children("td:eq(1)").text();
			if (parseFloat(cantidad) > 0 && parseFloat(cantidad) <= parseFloat(maximo)) {
				productos.push([iddetalle, cantidad]);
			}else{
				$(this).children("td:eq(6)").find(".devolverProducto").val(0);
			}
		});
		if (productos.length > 0) {
			Swal.fire({
			    title: '¿Que quieres hacer con estos productos?',
			    html: `
			    <select name="AccionesDevolucion" id="AccionesDevolucion" class="form-control">
			    	<option value="Nada">Nada</option>
	                <option value="Inventario">Devolver a inventario</option>
	                <option value="Merma">Registrar como merma</option>
            	</select>`,
				icon:  'info',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Continuar',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.value) {
					var acciones = $("#AccionesDevolucion").val(); 
					var data = "metodo=detalles&accion=ventas&tipo=GuardarDevolucionProductos&idventa="+idventa+"&producto="+JSON.stringify(productos)+"&acciones="+acciones+"&sucursal="+idsucursal;
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
								title: 'Devolución registrada correctamente',
							});
							$("#ModalDevolucionVenta").modal("hide");
							ComprobarDevuelta(idventa);
							TablaReporteVentas();
						}else{
							Swal.fire({
								icon: 'error',
						        title: 'Oops...',
							    text: 'Error al guardar devolución.'
							});
							console.log(res);
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
		}else{
			Swal.fire({
				icon: 'error',
				title: 'No se puede guardar una devolución sin productos',
				timer: 1300
			});
		}
	});


});

function ComprobarDevuelta(idventa){
	var data = "metodo=detalles&accion=ventas&tipo=ComprobarDevolucionProducto&idventa="+idventa;
	$.ajax({
		url: 'index.php',
		type: 'POST',
		data: data,
	})
	.done(function(res) {
		console.log(res);
	})
	.fail(function() {
		console.log("Error ajax");
	})	
}

function TablaReporteVentas(){
	ajaxMyDatatable({
		"table": $("#TablaReporteVentas"), 
		"colums": [
			"Datos",
			"Cliente",
			"Total",
			"Facturada",
			"Detalles",
			"Acciones"
		], 
		"totals":[
			"Datos",
			"Cliente",
			"Total"
		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "ventas"
		}
	});
}

function TablaProductosDevolucion(idventa){
	ajaxMyDatatable({
		"table": $("#TablaProductosDevolucion"), 
		"colums": [
			"Producto",
			"Cantidad",
			"Precio",
			"TotalVenta",
			"Devuelto",
			"Total",
			"Devolver",
		], 
		"sort": [
			0,
			"asc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"accion": "ventas",
			"tipo": "ConsultarProductosVentaDevolucion",
			"idventa": idventa
		}
	});
}

