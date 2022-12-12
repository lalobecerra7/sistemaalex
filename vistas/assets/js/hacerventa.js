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
	                        <td>`+datos.Descripcion+` <br>`+datos.Presentacion+`</td>
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
	//NOS QUEDAMOS EN VER PORQUE NO AGREGA LOS PRODUCTOS EN VENTAS Y LAS CORRECCIONES DE JUANCHO
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
	                        <td>`+datos.Descripcion+` <br>`+datos.Presentacion+`</td>
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
				totalImpuestos += parseFloat(total) * parseFloat(porcentaje);
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
		var idproducto = $(this).attr("attrid");
		var presentacion = $(this).attr("idpresentacion");
		var precio = $(this).attr("precio");
		VentaTablaPreciosProducto(idproducto, presentacion);
		$(".BotonDatosPrecio").attr("producto", idproducto);
		$(".BotonDatosPrecio").attr("presentacion", presentacion);
		$("#ModalPreciosProductoVenta").modal("show");
	});

	$(document).on('click', '#TablaPreciosProductosVenta tbody tr', function() {
		var precio = $(this).children("td:eq(1)").text();
		console.log(precio);
		var producto = $(".BotonDatosPrecio").attr("producto");
		var presentacion = $(".BotonDatosPrecio").attr("presentacion");
		console.log($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').length);
		if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').length > 0){    
			$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").html(precio);
			$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").attr("precio", precio);
		}         
		$(".campoCantidadProducto").trigger("change");
		moneda();
		$("#ModalPreciosProductoVenta").modal("hide"); 
	});

	$(document).on('click', '#GuardarPedido', function() {
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
					$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
						var idProducto = $(this).attr("attrid");
						var Presentacion = $(this).attr("idpresentacion");
						var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
						var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
						var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProducto").val();
						var totalproducto = $(this).children("td:eq(6)").text().replace("$","").replace(",","")
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
	});

	$(document).on('click', '#RealizarVenta', function() {
		if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
			Swal.fire({
			    icon: 'error',
			    title: 'No se puede ralizar una venta sin productos',
			    timer: 1000
			});
		}else{
			$("#ModalRealizarVenta").modal("show");
		}
	});

	$(document).on('click', '#GuardarVenta', function() {
		if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
			Swal.fire({
			    icon: 'error',
			    title: 'No se puede ralizar una venta sin productos',
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
			$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
				var idProducto = $(this).attr("attrid");
				var Presentacion = $(this).attr("idpresentacion");
				var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
				var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
				var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProducto").val();
				var totalproducto = $(this).children("td:eq(6)").text().replace("$","").replace(",","")
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
				if ($.trim(res) == "Correcto") {
					$("#ModalRealizarVenta").modal("hide");
			    	Swal.fire({
						icon: 'success',
						title: 'Venta realizada correctamente',
					});
					$("#cargarHacerVenta").trigger("click");
					
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

	function CalcularSubtotalVenta(){
		if ($("#TablaProductosAgregadoVenta tbody tr").length > 0) {
			$("#SucursalVenta").attr("disabled", true);
		}else{
			$("#SucursalVenta").attr("disabled", false);	
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
			total += parseFloat($(this).children("td:eq(6)").text().replace("$","").replace(",",""));
		});
		console.log(total);
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


	
});

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
			"Producto",
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
