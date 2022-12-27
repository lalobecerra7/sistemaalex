function v_hacerventa() {
	//TablaReporteCompras();
}

jQuery(document).ready(function($) {

	$(document).on('click', '#CargarClientesModalVentas', function() {
		TablaClienteVenta();
		$("#ModalVerClientesVenta").modal("show");
	});

	$(document).on('click', '#TablaClienteVenta tbody tr', function() {
		var idcliente = $(this).attr("id");
		var nombre = $(this).children("td:eq(0)").text();
		var RFC = $(this).children("td:eq(2)").text();
		$("#ModalVerClientesVenta").modal("hide");
		$("#CargarClientesModalVentas").html("Cliente: "+nombre+"<br>RFC: "+RFC);
		$("#CargarClientesModalVentas").attr("attrid", idcliente);
		$(".BotonLimpiarCliente").removeClass("oculto");
		$(".BotonSeleccionarPedido").addClass("offset-md-2");
		$(".BotonSeleccionarPedido").removeClass("offset-md-3");
	});

	$(document).on('click', '#LimpiarClienteSeleccionado', function() {
		$(".BotonLimpiarCliente").addClass("oculto");
		$(".BotonSeleccionarPedido").removeClass("offset-md-2");
		$(".BotonSeleccionarPedido").addClass("offset-md-3");
		$("#CargarClientesModalVentas").html('<i class="fas fa-user"></i> Seleccionar cliente');
		$("#CargarClientesModalVentas").attr("attrid", "");
	});

	$(document).on('click', '#CargarProductosModalVentas', function() {
		VentaTablaProductos();
	});


	$(document).on('submit', '#FormAgregarProductoVenta', function(event) {
        event.preventDefault();

        var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+$("#CodigoProductoVenta").val()+"&sucursal="+$("#SucursalVenta").attr("attrid");
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#CodigoProductoVenta").val("");
            if($.trim(res) == "No encontrado"){
              	Swal.fire({
				  icon: 'error',
				  title: 'Producto no encontrado o sin existencia',
				  timer: 1200
				});
            }else{       
            	var datos = JSON.parse($.trim(res));
                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').length > 0){                 
                	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
                 	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + 1);
                	$(".campoCantidadProducto").trigger("change");
                }else{
                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br><button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Presentacion+`</button></td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.Precio_General+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Precio_General+`</button></td>
	                        <td><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto'></td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
		                        <div class="input-group">
		                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-percentage"></i></span>
		                            <input type="number" value="0" min="0" max="100" step="any" class="form-control form-control-sm campoDescuentoProducto">
		                        </div>
		                        <br>
		                        <div class="input-group">
		                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-dollar-sign"></i></span>
		                            <input type="number" value="0" min="0" max="100" step="any" class="form-control form-control-sm campoDescuentoProductoCantidad">
		                        </div>
		                    </td>
	                        <td class="dinero"></td>
	                        <td><button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
	                    </tr>`);
                	$(".campoCantidadProducto").trigger("change");
                }
                moneda();
            }
        })
        .fail(function() {
            console.log("Error ajax");
        }); 
    });

    $(document).on('click', '#VentaTablaProductos tbody tr', function() {
    	var codigo = $(this).children("td:eq(0)").find("#CodigoProducto").text();
    	var presentacion = $(this).children("td:eq(1)").find("#IdPresentacionProd").text();
    	var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+codigo+"&sucursal="+$("#SucursalVenta").attr("attrid")+"&presentacion="+presentacion;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            $("#CodigoProductoVenta").val("");
            if($.trim(res) == "No encontrado"){
                Swal.fire({
				  icon: 'error',
				  title: 'Producto no encontrado o sin existencia',
				  timer: 1200
				})
            }else{       
            	var datos = JSON.parse($.trim(res));
            	console.log(datos);
                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').length > 0){                 
                	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
                 	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + 1);
                	$(".campoCantidadProducto").trigger("change");
                }else{
                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br> <button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Presentacion+`</button></td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.Precio_General+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Precio_General+`</button></td>
	                        <td><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto'></td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
		                        <div class="input-group">
		                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-percentage"></i></span>
		                            <input type="number" value="0" min="0" max="100" step="any" class="form-control form-control-sm campoDescuentoProducto">
		                        </div>
		                        <br>
		                        <div class="input-group">
		                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-dollar-sign"></i></span>
		                            <input type="number" value="0" min="0" max="100" step="any" class="form-control form-control-sm campoDescuentoProductoCantidad">
		                        </div>
	                        </td>
	                        <td class="dinero"></td>
	                        <td><button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
	                    </tr>`);
                	$(".campoCantidadProducto").trigger("change");
                }
                moneda();
                $("#ModalVerProductosVenta").modal("hide");
                $("#CodigoProductoVenta").focus();
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        });
    });

	$(document).on('keyup change', '.campoCantidadProducto', function() {
		if ($(this).val() == "") {
			$(this).val(0);
		}
		var precio = $(this).parent().parent().children("td:eq(2)").find(".cambiarPrecio").attr("precio");
		var cantidad = $(this).parent().parent().children("td:eq(3)").find(".campoCantidadProducto").val();
		var subtotal = parseFloat(precio) * parseFloat(cantidad);
		var descuento = parseFloat($(this).parent().parent().children("td:eq(5)").find(".campoDescuentoProducto").val()) / 100;
		if (isNaN(descuento)) {
			descuento = 0;
		}
		var montoDescuento = parseFloat(descuento) * parseFloat(subtotal);
		$(this).parent().parent().children("td:eq(5)").find(".campoDescuentoProductoCantidad").val(montoDescuento)
		var total =(parseFloat(subtotal) - parseFloat(montoDescuento));
		var totalImpuestos = 0;
		$(this).parent().parent().children("td:eq(4)").find(".impuesto").each(function(index, el) {
			var impuesto = $(this).find(".seleccionarImpuesto");
			if (impuesto.prop("checked") == true) {
				var porcentaje = parseFloat(impuesto.attr("porcentaje")) / 100;

				if (impuesto.attr("clase") == "Trasladado") { //Se suma al total
					totalImpuestos += parseFloat(total) * parseFloat(porcentaje);
				}else if(impuesto.attr("clase") == "Retenido" && impuesto.attr("tipofactor") != "Exento"){ //Se resta al total
					totalImpuestos -= parseFloat(total) * parseFloat(porcentaje);
				}else if(impuesto.attr("clase") == "Retenido" && impuesto.attr("tipofactor") == "Exento"){ //No se suma ni se resta
					totalImpuestos += parseFloat(0);
				}else{
					totalImpuestos += parseFloat(total) * parseFloat(porcentaje);
				}
			}
		});
		
		$(this).parent().parent().children("td:eq(6)").text(parseFloat(total) + parseFloat(totalImpuestos));
		$(this).parent().parent().children("td:eq(6)").attr("subtotal", total);
		CalcularSubtotalVenta();
	});

	$(document).on('click', '.seleccionarImpuesto', function() {
		$(".campoCantidadProducto").trigger("change");
	});

	$(document).on('change keyup', '.campoDescuentoProducto', function() {
		$(".campoCantidadProducto").trigger("change");
	});

	$(document).on('change keyup', '.campoDescuentoProductoCantidad', function() {
		var cantidadDescuento = $(this).val();
		var precio = $(this).parent().parent().parent().children("td:eq(2)").find(".cambiarPrecio").attr("precio")
		var cantidad = $(this).parent().parent().parent().children("td:eq(3)").find(".campoCantidadProducto").val();
		var total = parseFloat(precio) * parseFloat(cantidad);
		var totalFinal = (parseFloat(cantidadDescuento) * 100) / total;
		$(this).parent().parent().parent().children("td:eq(5)").find(".campoDescuentoProducto").val(totalFinal);
		$(".campoCantidadProducto").trigger("change");
	});

	$(document).on('click', '.eliminarFila', function() {
		$(this).parent().parent().remove();
		CalcularSubtotalVenta();
    		/*$("#DescuentoCompraDinero").val($("#RealizarCompra").attr("cantidad"));
    		$("#DescuentoCompraDinero").trigger("change");*/
	});

	$(document).on('click', '.cambiarPrecio', function() {
		var idproducto = $(this).parent().parent().attr("attrid");
		var presentacion = $(this).parent().parent().attr("idpresentacion");
		var precio = $(this).attr("precio");
		VentaTablaPreciosProducto(idproducto, presentacion);
		$(".BotonDatosPrecio").attr("producto", idproducto);
		$(".BotonDatosPrecio").attr("presentacion", presentacion);
		$("#ModalPreciosProductoVenta").modal("show");
	});

	$(document).on('click', '#TablaPreciosProductosVenta tbody tr', function() {
		var precio = $(this).children("td:eq(1)").text();
		var producto = $(".BotonDatosPrecio").attr("producto");
		var presentacion = $(".BotonDatosPrecio").attr("presentacion");
		if (presentacion == 0) {
			presentacion = null;
		}
		if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').length > 0){    
			$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").html(precio);
			$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").attr("precio", precio);
		}         
		$(".campoCantidadProducto").trigger("change");
		moneda();
		$("#ModalPreciosProductoVenta").modal("hide"); 
	});

	$(document).on('click', '#GuardarPedido', function() {
		if ($("#CargaPedidosModalVentas").attr("attrid") != "") {
			Swal.fire({
	            title: 'Tienes cargado el pedido con el folio '+$("#CargaPedidosModalVentas").attr("folio")+', los datos de este pedido se actualizarán',
	            icon:  'info',
	            showCancelButton: true,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Modificar pedido',
	            cancelButtonText: 'Guardar como nuevo pedido',
	        }).then((result) => {
	            if (result.value) {
	            	var idsucursal = $("#SucursalVenta").attr("attrid");
					if ($("#CargarClientesModalVentas").attr("attrid") == "") {
						var cliente = 1;
					}else{
						var cliente = $("#CargarClientesModalVentas").attr("attrid");
					}
					var productos = new Array();
					var sumadescuento = 0;
					var total = $(this).attr("total");
					var fechaEntrega = $("#FechaEntregaPedido").val();
					const searchRegExp = new RegExp(',', 'g');
					$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
						var idProducto = $(this).attr("attrid");
						var Presentacion = $(this).attr("idpresentacion");
						var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
						var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
						var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
						var totalproducto = $(this).children("td:eq(6)").text().replace("$","").replace(searchRegExp, '');
						sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
						var impuestos = "";
						$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
							var impuesto = $(this).find(".seleccionarImpuesto");
							if (impuesto.prop("checked") == true) {
								impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
							}
						});
						productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto]);
					});

					var data = "metodo=modificar&accion=hacerventa&tipo=ModificarPedido&idsucursal="+idsucursal+"&cliente="+cliente+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&fechaEntrega="+fechaEntrega+"&IDPedido="+$("#CargaPedidosModalVentas").attr("attrid");
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
								title: 'Pedido modificado correctamente',
							});
							$("#cargarHacerVenta").trigger("click");
						}else{
							Swal.fire({
				            	icon: 'error',
				                title: 'Oops...',
				                text: 'Error al guardar pedido.'
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
	            }else{
	            	if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
						Swal.fire({
						    icon: 'error',
						    title: 'No se puede guardar un pedido sin productos',
						    timer: 1000
						});
					}else{
						Swal.fire({
				            title: 'Selecciona la fecha de entrega del pedido',
				            html: '<input type="date" class="form-control" id="FechaEntregaPedido">',
				            icon:  'info',
				            showCancelButton: true,
				            confirmButtonColor: '#3085d6',
				            cancelButtonColor: '#d33',
				            confirmButtonText: 'Continuar',
				            cancelButtonText: 'Cancelar',
				        }).then((result) => {
				            if (result.value) {
				            	var idsucursal = $("#SucursalVenta").attr("attrid");
								if ($("#CargarClientesModalVentas").attr("attrid") == "") {
									var cliente = 1;
								}else{
									var cliente = $("#CargarClientesModalVentas").attr("attrid");
								}
								var productos = new Array();
								var sumadescuento = 0;
								var total = $(this).attr("total");
								var fechaEntrega = $("#FechaEntregaPedido").val();
								const searchRegExp = new RegExp(',', 'g');
								$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
									var idProducto = $(this).attr("attrid");
									var Presentacion = $(this).attr("idpresentacion");
									var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
									var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
									var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
									var totalproducto = $(this).children("td:eq(6)").text().replace("$","").replace(searchRegExp, '');
									sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
									var impuestos = "";
									$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
										var impuesto = $(this).find(".seleccionarImpuesto");
										if (impuesto.prop("checked") == true) {
											impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
										}
									});
									productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto]);
								});

								var data = "metodo=insertar&accion=hacerventa&tipo=GuardarPedido&idsucursal="+idsucursal+"&cliente="+cliente+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&fechaEntrega="+fechaEntrega;
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
										  title: 'Pedido guardado correctamente',
										});
										$("#cargarHacerVenta").trigger("click");
						        	}else{
						        		Swal.fire({
				                            icon: 'error',
				                            title: 'Oops...',
				                            text: 'Error al guardar pedido.'
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
					}
	            }
	        });
		}else{
			if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
				Swal.fire({
				    icon: 'error',
				    title: 'No se puede guardar un pedido sin productos',
				    timer: 1000
				});
			}else{
				Swal.fire({
			        title: 'Selecciona la fecha de entrega del pedido',
			        html: '<input type="date" class="form-control" id="FechaEntregaPedido">',
			        icon:  'info',
				    showCancelButton: true,
				    confirmButtonColor: '#3085d6',
				    cancelButtonColor: '#d33',
				    confirmButtonText: 'Continuar',
				    cancelButtonText: 'Cancelar',
				}).then((result) => {
					if (result.value) {
						var idsucursal = $("#SucursalVenta").attr("attrid");
						if ($("#CargarClientesModalVentas").attr("attrid") == "") {
							var cliente = 1;
						}else{
							var cliente = $("#CargarClientesModalVentas").attr("attrid");
						}
						var productos = new Array();
						var sumadescuento = 0;
						var total = $(this).attr("total");
						var fechaEntrega = $("#FechaEntregaPedido").val();
						const searchRegExp = new RegExp(',', 'g');
						$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
							var idProducto = $(this).attr("attrid");
							var Presentacion = $(this).attr("idpresentacion");
							var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
							var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
							var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
							var totalproducto = $(this).children("td:eq(6)").text().replace("$","").replace(searchRegExp, '');
							sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
							var impuestos = "";
							$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
								var impuesto = $(this).find(".seleccionarImpuesto");
								if (impuesto.prop("checked") == true) {
									impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
								}
							});
							productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto]);
						});

						var data = "metodo=insertar&accion=hacerventa&tipo=GuardarPedido&idsucursal="+idsucursal+"&cliente="+cliente+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&fechaEntrega="+fechaEntrega;
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
									title: 'Pedido guardado correctamente',
								});
								$("#cargarHacerVenta").trigger("click");
						    }else{
						    	Swal.fire({
				                	icon: 'error',
				                    title: 'Oops...',
				                    text: 'Error al guardar pedido.'
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
			}
		}
	});

	$(document).on('click', '#RealizarVenta', function() {
		var total = $(this).attr("total");
		if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
			Swal.fire({
			    icon: 'error',
			    title: 'No se puede realizar una venta sin productos',
			    timer: 1000
			});
		}else{
			$("#ModalRealizarVenta").modal("show");
			$("#GuardarVenta").attr("tipo", "");
			$("#GuardarVenta").attr("idpedido", $("#CargaPedidosModalVentas").attr("attrid"));
			$("#GuardarVenta").attr("foliopedido", $("#CargaPedidosModalVentas").attr("folio"));
			$("#ImportePagadoVenta").val(total);

		}
	});

	$(document).on('click', '#GuardarVenta', function() {
		if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
			Swal.fire({
			    icon: 'error',
			    title: 'No se puede realizar una venta sin productos',
			    timer: 1000
			});
		}else{
			var idsucursal = $("#SucursalVenta").attr("attrid");
			if ($("#CargarClientesModalVentas").attr("attrid") == "") {
				var cliente = 1;
			}else{
				var cliente = $("#CargarClientesModalVentas").attr("attrid");
			}
			var productos = new Array();
			var sumadescuento = 0;
			var total = $("#RealizarVenta").attr("total");
			var tipopago = $("#TipoPagoVenta").val();
			var pago = $("#ImportePagadoVenta").val();
			const searchRegExp = new RegExp(',', 'g');
			$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
				var idProducto = $(this).attr("attrid");
				var Presentacion = $(this).attr("idpresentacion");
				var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
				var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
				var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
				var totalproducto = $(this).children("td:eq(6)").text().replace("$","").replace(searchRegExp, '');
				sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
				var impuestos = "";
				$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
					var impuesto = $(this).find(".seleccionarImpuesto");
					if (impuesto.prop("checked") == true) {
						impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
					}
				});
				productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto]);
			});

			var data = "metodo=insertar&accion=hacerventa&tipo=RealizarVenta&idsucursal="+idsucursal+"&cliente="+cliente+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&TipoPago="+tipopago+"&Importe="+pago;
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
					$("#ModalRealizarVenta").modal("hide");

					if ($("#GuardarVenta").attr("idpedido") != "") {
						Swal.fire({
					        title: '¿Quieres eliminar el pedido con el folio '+$("#GuardarVenta").attr("foliopedido")+'?',
					        icon: 'warning',
					        showCancelButton: true,
					        confirmButtonColor: '#3085d6',
					        cancelButtonColor: '#d33',
					        cancelButtonText: '¡No, continuar!',
					        confirmButtonText: '¡Si, eliminar!'
					    }).then((result) => {
					        if (result.value) {
					        	var data = "metodo=eliminar&accion=hacerventa&IDPedido="+$("#GuardarVenta").attr("idpedido");
								$.ajax({
									url: 'index.php',
									type: 'POST',
									data: data,
								})
								.done(function(res) {
									if ($.trim(res) == "Correcto") {
										Swal.fire({
											icon: 'success',
											title: 'Venta realizada correctamente',
										});
										$("#cargarHacerVenta").trigger("click");
										var idVenta = datos[1];
										var altura=50;
									    var anchura=310;

									    var y= parseInt((window.screen.height/2)-(altura/2));
									    var x= parseInt((window.screen.width/2)-(anchura/2));
									    window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
										if ($("#GuardarVenta").attr("tipo") == "facturar") {
											facturarVenta(idVenta);
										}
									}else{
										Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Error inesperado al eliminar el pedido.'
										});
										console.log($.trim(res));
									}
								})
								.fail(function() {
									console.log("Error ajax");
								});
					        }else{
					        	Swal.fire({
									icon: 'success',
									title: 'Venta realizada correctamente',
								});
								$("#cargarHacerVenta").trigger("click");
								var idVenta = datos[1];
								var altura=50;
							    var anchura=310;

							    var y= parseInt((window.screen.height/2)-(altura/2));
							    var x= parseInt((window.screen.width/2)-(anchura/2));
							    window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
					       		if ($("#GuardarVenta").attr("tipo") == "facturar") {
									facturarVenta(idVenta);
								}
					        }
					    });
					}else{
						Swal.fire({
							icon: 'success',
							title: 'Venta realizada correctamente',
						});
						$("#cargarHacerVenta").trigger("click");
						var idVenta = datos[1];
						var altura=50;
						var anchura=310;

						var y= parseInt((window.screen.height/2)-(altura/2));
						var x= parseInt((window.screen.width/2)-(anchura/2));
						window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
						if ($("#GuardarVenta").attr("tipo") == "facturar") {
							facturarVenta(idVenta);
						}
					}		    	
			    }else{
			    	Swal.fire({
	                	icon: 'error',
	                    title: 'Oops...',
	                    text: 'Error al guardar venta.'
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

	$(document).on('click', '#CobrarFacturar', function() {
		if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
			Swal.fire({
			    icon: 'error',
			    title: 'No se puede realizar una venta sin productos',
			    timer: 1000
			});
		}else{
			$("#ModalRealizarVenta").modal("show");
			$("#GuardarVenta").attr("idpedido", $("#CargaPedidosModalVentas").attr("attrid"));
			$("#GuardarVenta").attr("tipo", "facturar");
			$("#GuardarVenta").attr("foliopedido", $("#CargaPedidosModalVentas").attr("folio"));
		}
	});


	function CalcularSubtotalVenta(){
		if ($("#TablaProductosAgregadoVenta tbody tr").length > 0) {
			$("#SucursalVenta").attr("disabled", true);
		}else{
			$("#SucursalVenta").attr("disabled", false);
			$("#CargaPedidosModalVentas").html('<i class="fas fa-arrow-down"></i> Seleccionar pedido');
			$("#CargaPedidosModalVentas").attr("attrid", "");
			$("#CargaPedidosModalVentas").attr("folio", "");	
		}
		var subtotal = 0;
		$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
			subtotal += parseFloat($(this).children("td:eq(6)").attr("subtotal"))
		});
		$("#MostrarSubtotalVenta").text(subtotal);
		$("#cantidadProductosSpanVenta").text($("#TablaProductosAgregadoVenta tbody tr").length)
		moneda();
		CalcularTotal();
	}

	function CalcularTotal(){
		var total = 0;
		$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
			const searchRegExp = new RegExp(',', 'g');
			total += parseFloat($(this).children("td:eq(6)").text().replace("$","").replace(searchRegExp, ''));
		});
		$("#TotalVentaFinal").text(total);
		$("#GuardarPedido").attr("total", total);
		$("#RealizarVenta").attr("total", total);
		moneda();
	}
	/*function TotalFinalVenta() {
        var suma = 0, contador = 0;
        $("#nav-tabContent .active #tablaCaja").children('tbody').children('tr').each(function(index, el) {
            suma += parseFloat($(this).children('td:eq(5)').children('span.dinero').text().replace('$', '').replace(',', ''));
            contador ++;
        });

        $("#nav-tabContent .active #totalCaja").html(suma);
        $("#nav-tabContent .active #cantidadCajaProd").html(contador);

        moneda();
    }*/

    $(document).on('click', '#CargaPedidosModalVentas', function() {
    	TablaVerPedidosGuardados();
    });

    $(document).on('click', '#VerProductosPedido', function() {
		var id = $(this).attr("attrid");
		var folio = $(this).attr("attrid");
		$("#ModalVerProductosReportePedido").modal("show");
		$("#FolioPedidoProductos").text(folio);

		var data = "metodo=detalles&accion=hacerventa&tipo=productos&IDPedido="+id;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			$("#tbodyVerProductosPedido").html(res);
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});

	$(document).on('click', '.verImpuestosProductoPedido', function() {
		var id = $(this).attr("attrid");
		var nombre = $(this).attr("nombre");
		$("#ModalVerImpuestosProductoPedido").modal("show");
		$("#NombreProductoImpuestoPedido").text(nombre);
		var data = "metodo=detalles&accion=hacerventa&tipo=impuestos&IDDetalle="+id;
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

	$(document).on('change', '#SucursalVenta', function() {
		if ($(this).val() !="") {
			$(this).attr("attrid", $(this).val());
		}
	});

	$(document).on('click', '.SeleccionarPedido', function() {
		$("#CargarClientesModalVentas").html('<i class="fas fa-user"></i> Seleccionar cliente');
		$("#CargarClientesModalVentas").attr("attrid", "");
		$('#TablaProductosAgregadoVenta tbody').html("");
		$("#CargaPedidosModalVentas").html('<i class="fas fa-arrow-down"></i> Seleccionar pedido');
		$("#CargaPedidosModalVentas").attr("attrid", "");
		$("#CargaPedidosModalVentas").attr("folio", "");



		var id = $(this).attr("attrid");
		var folio = $(this).attr("folio");
		var data = "metodo=detalles&accion=hacerventa&tipo=AgregarPedido&IDPedido="+id;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			console.log(res);
			var datos = JSON.parse($.trim(res));
			$("#CargarClientesModalVentas").html("Cliente: "+datos.data.NombreCliente+"<br>RFC: "+datos.data.RFCCliente);
			$("#CargarClientesModalVentas").attr("attrid", datos.data.FK_Cliente);
			$("#ModalVerPedidosVenta").modal("hide");
			for (var i = 0; i < datos.data.Productos.data.length; i++) {
				/*var impuestos = "";
				for (var x = 0; x < datos.data.Productos.data[i].Impuestos.data.length; x++) {
					impuestos += '\
					<div class="form-check impuesto">\
						<input class="form-check-input seleccionarImpuesto" checked type="checkbox" nombre="'+datos.data.Productos.data[i].Impuestos.data[x].Impuesto_CFDI+'" porcentaje="'+datos.data.Productos.data[i].Impuestos.data[x].Tasa_Cuota_CFDI+'" attrid="'+datos.data.Productos.data[i].Impuestos.data[x].ID_Impuesto+'" clavecfdi="'+datos.data.Productos.data[i].Impuestos.data[x].Clave_CFDI+'" tipofactor="'+datos.data.Productos.data[i].Impuestos.data[x].Tipo_Factor_CFDI+'" clase="'+datos.data.Productos.data[i].Impuestos.data[x].Tipo_Impuesto_CFDI+'">\
						<label class="form-check-label" for="flexCheckDefault">\
							'+datos.data.Productos.data[i].Impuestos.data[x].Impuesto_CFDI+' ('+datos.data.Productos.data[i].Impuestos.data[x].Tasa_Cuota_CFDI+'%)\
						</label>\
					</div>'
				}*/
				var presentacion = null;

				if (datos.data.Productos.data[i].FK_Presentacion != 0) {
					presentacion = datos.data.Productos.data[i].FK_Presentacion;
				}else{
					presentacion = null;
				}
				$('#TablaProductosAgregadoVenta tbody').append(`
		        <tr attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`">
		        	<td>`+datos.data.Productos.data[i].Codigo+`</td>
		            <td>`+datos.data.Productos.data[i].Descripcion+` <br> <button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`">`+datos.data.Productos.data[i].NombrePresentacion+`</button>
		            <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.data.Productos.data[i].Precio+`" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+datos.data.Productos.data[i].FK_Presentacion+`">`+datos.data.Productos.data[i].Precio+`</button></td>
		            <td><input type='number' value='`+datos.data.Productos.data[i].Cantidad+`' min='1' step='any' class='form-control form-control-sm campoCantidadProducto'></td>
		            <td>`+datos.data.Productos.data[i].Impuestos+`</td>
		            <td>
			        	<div class="input-group">
			            	<span class="input-group-text" id="basic-addon1"><i class="fas fa-percentage"></i></span>
			                <input type="number" value="0" min="0" max="100" step="any" class="form-control form-control-sm campoDescuentoProducto">
			            </div>
			            <br>
			            <div class="input-group">
			            	<span class="input-group-text" id="basic-addon1"><i class="fas fa-dollar-sign"></i></span>
			                <input type="number" value="`+datos.data.Productos.data[i].Descuento+`" min="0" max="100" step="any" class="form-control form-control-sm campoDescuentoProductoCantidad">
			            </div>
		            </td>
		            <td class="dinero"></td>
		            <td><button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
		        </tr>`);

				$(".campoDescuentoProductoCantidad").trigger("keyup");

				$("#CargaPedidosModalVentas").text("Pedido: "+folio);
				$("#CargaPedidosModalVentas").attr("attrid", id);
				$("#CargaPedidosModalVentas").attr("folio", folio);
			}
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});

	$(document).on('click', '.EliminarPedido', function() {
		var btn = $(this);
		Swal.fire({
	        title: '¿Estás seguro que quieres eliminar el pedido con el folio '+$(this).attr("folio")+'?',
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        cancelButtonText: '¡No, cancelar!',
	        confirmButtonText: '¡Si, eliminar!'
	    }).then((result) => {
	        if (result.value) {
	        	var data = "metodo=eliminar&accion=hacerventa&IDPedido="+$(this).attr('attrid');
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
							title: 'Pedido eliminado correctamente'
						});
						TablaVerPedidosGuardados();
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Error inesperado al eliminar el pedido.'
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

	$(document).on('click', '.CambiarPresentacion', function() {
		var id = $(this).attr("attrid");
		var idsucursal = $("#SucursalVenta").attr("attrid");
		var idpresentacion = $(this).attr("idPresentacion");
		TablaPresentacionesProducto(id, idsucursal, idpresentacion);
		$("#ModalPresentacionesProducto").modal("show");
	});

	$(document).on('click', '.SeleccionarPresentacion', function() {
		var idpresentacion = $(this).parent().parent().attr("id");
		var idproducto = $(this).attr("producto");
		var presentacionanterior = $(this).attr("presentacionactual");
		var nombre = $(this).attr("nombre");
		var abreviatura = $(this).attr("abreviatura");
		var precio = $(this).attr("precio");
		if (idpresentacion == 0) {
			idpresentacion = null;
		}
		$("#ModalPresentacionesProducto").modal("hide");
		
		if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').length > 0){                 
          	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').children("td:eq(3)").find(".campoCantidadProducto").val();
           	var cantidadactual = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + parseFloat(cantidadactual));
            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').remove();
            $(".campoCantidadProducto").trigger("change");
        }else{
	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').attr("idPresentacion", idpresentacion);
	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(1)").find(".CambiarPresentacion").attr("idpresentacion", idpresentacion);
	    	if (abreviatura == "") {
	    		$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(1)").find(".CambiarPresentacion").text(nombre);
	    	}else{
	    		$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(1)").find(".CambiarPresentacion").text(nombre+"("+abreviatura+")");
	    	}

	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".cambiarPrecio").attr("precio", precio);
	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".cambiarPrecio").text(precio);
	    }
	    $(".campoCantidadProducto").trigger("change");
		moneda();

	});

	
});

function TablaVerPedidosGuardados(){
	ajaxMyDatatable({
		"table": $("#TablaCargarPedidos"), 
		"colums": [
			"Datos",
			"Cliente",
			"Total",
			"Detalles",
			"Acciones"
		], 
		"totals":[
			"Datos",
			"Cliente",
			"Total",
			"Detalles",
			"Acciones"
		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"accion": "hacerventa",
			"tipo": "CargarPedidos",
			"sucursal": $("#SucursalVenta").attr("attrid")
		}
	});
}

function TablaClienteVenta(){ 
	ajaxMyDatatable({
		"table": $("#TablaClienteVenta"), 
		"colums": [
			"Nombre",
			"Direccion",
			"RFC",
			"Contacto",
			"Facturar"
		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"accion": "hacerventa",
			"tipo": "ConsultarCliente"
		}
	});
}

function VentaTablaProductos(){
	ajaxMyDatatable({
		"table": $("#VentaTablaProductos"), 
		"colums": [
			"Descripcion",
			"Presentacion",
			"Nombre",
			"Precio",
			"Mayoreo",
			"Existencia"
		], 
		"sort": [
			1,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"tipo": "ConsultarProductos",
			"accion": "hacerventa",
			"sucursal": $("#SucursalVenta").attr("attrid")
		}
	});
}

function VentaTablaPreciosProducto(idproducto, presentacion){
	console.log(idproducto+" "+presentacion);
	ajaxMyDatatable({
		"table": $("#TablaPreciosProductosVenta"), 
		"colums": [
			"Nombre",
			"Precio",
		], 
		"sort": [
			1,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"tipo": "ConsultarPrecios",
			"accion": "hacerventa",
			"idproducto": idproducto,
			"presentacion": presentacion
		}
	});
}

function TablaPresentacionesProducto(idproducto, idsucursal, idpresentacion){
	ajaxMyDatatable({
		"table": $("#TablaPresentacionesProducto"), 
		"colums": [
			"Nombre", 
			"Abreviatura", 
			"Existencia",
			"Accion"
		], 
		"sort": [
			1,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"tipo": "ConsultarPresentacionesProducto",
			"accion": "hacerventa",
			"idproducto": idproducto,
			"sucursal": idsucursal,
			"presentacion": idpresentacion
		}
	});
}


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
