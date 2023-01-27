function v_hacerventa() {
	//TablaReporteCompras();
	EstatusCaja();
	$('#FormAdmin').validate({
        rules: {
            correoAdmin: {
                required: true
            },
            contraAdmin: {
                required: true
            },
        },
        messages: {
            correoAdmin: {
                required: "El correo electrónico es obligatorio"
            },
            contraAdmin: {
                required: "La contraseña es obligatoria"
            },
        },
        submitHandler: function(form) { 
           	var data = "metodo=detalles&accion=hacerventa&tipo=ValidarAdministrador&correo="+$("#correoAdmin").val()+"&contra="+$("#contraAdmin").val();
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
                	$('#FormAdmin').trigger("reset");
                	var total = $("#RealizarVenta").attr("total");
					$("#ModalRealizarVenta").modal("show");
					$("#ModalPermisoAdministrador").modal("hide");
					$("#GuardarVenta").attr("tipo", "");
					$("#GuardarVenta").attr("idpedido", $("#CargaPedidosModalVentas").attr("attrid"));
					$("#GuardarVenta").attr("foliopedido", $("#CargaPedidosModalVentas").attr("folio"));
					$("#ImportePagadoVenta").val(total);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Los datos ingresados son incorrectos.'
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

    //CERRAR CAJA
    $('#FormCerrarCaja').validate({
        rules: {
            MontoCierreCaja: {
                required: true,
                min: 1,
            },
        },
        messages: {
            MontoCierreCaja: {
                required: "Ingresa el monto de cierre de la caja"
            },
        },
        submitHandler: function(form) { 

            var data = "metodo=detalles&accion=hacerventa&tipo=CerrarCaja&MontoCierre="+$("#MontoCierreCaja").val();
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
					$("#ModalCerrarCaja").modal("hide");
					$("#ModalBalanceCaja").modal("show");
					var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarBalanceCerrar&IDDetalleCaja="+datos[1];
					$.ajax({
						url: 'index.php',
						type: 'POST',
						data: data
					})
					.done(function(res) {
						var datos = JSON.parse($.trim(res));
						$("#spanMontoApertura").text(datos[0].Monto_Abrir);
						$("#spanMontoCierre").text(datos[0].Monto_Cierre);

						$("#spanFechaAbrir").text(datos[0].Fecha_Abrir);
						$("#spanFechaCerrar").text(datos[0].Fecha_Cerrar);

						$("#totalIngresosSpan").text(datos[0].Total_Ingresos);
						$("#totalEgresosSpan").text(datos[0].Total_Egresos);
						var utilidad = parseFloat(datos[0].Total_Ingresos) - parseFloat(datos[0].Total_Egresos);
						$("#totalUtilidadSpan").text(utilidad);
						//INGRESOS
						$("#spanTotalVentas").text(datos[0].Total_Ventas);
						$("#spanTotalImportes").text(datos[0].Total_Importes);
						//EGRESOS
						$("#spanTotalCompras").text(datos[0].Total_Compras);
						$("#spanTotalPagos").text(datos[0].Total_Pagos);
						$("#spanTotalDevoluciones").text(datos[0].Total_Devoluciones);

						if (datos[0].Total_Ventas_Efectivo > 0) {
							$("#DivMostrarVentasDesplegada").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Ventas en efectivo <span class="dinero">`+datos[0].Total_Ventas_Efectivo+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Ventas_Deposito > 0) {
							$("#DivMostrarVentasDesplegada").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Ventas en depósito <span class="dinero">`+datos[0].Total_Ventas_Deposito+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Ventas_Cheque > 0) {
							$("#DivMostrarVentasDesplegada").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Ventas en cheque <span class="dinero">`+datos[0].Total_Ventas_Cheque+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Ventas_TransferenciaBancaria > 0) {
							$("#DivMostrarVentasDesplegada").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Ventas en transferencia bancaria <span class="dinero">`+datos[0].Total_Ventas_TransferenciaBancaria+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Ventas_TarjetaCreditoDebito > 0) {
							$("#DivMostrarVentasDesplegada").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Ventas en tarjeta de crédito / debito <span class="dinero">`+datos[0].Total_Ventas_TarjetaCreditoDebito+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Ventas_PagoOnline > 0) {
							$("#DivMostrarVentasDesplegada").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Ventas en pago online <span class="dinero">`+datos[0].Total_Ventas_PagoOnline+`</span>
		                            </div>
		                        </div>`);
						}

						///PAGOS
						if (datos[0].Total_Pagos_Efectivo > 0) {
							$("#DivMostrarPagosDesplegado").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Pagos en efectivo <span class="dinero">`+datos[0].Total_Pagos_Efectivo+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Pagos_Deposito > 0) {
							$("#DivMostrarPagosDesplegado").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Pagos en deposito <span class="dinero">`+datos[0].Total_Pagos_Deposito+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Pagos_Cheque > 0) {
							$("#DivMostrarPagosDesplegado").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Pagos en cheques <span class="dinero">`+datos[0].Total_Pagos_Cheque+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Pagos_TransferenciaBancaria > 0) {
							$("#DivMostrarPagosDesplegado").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Pagos en transferencia bancaria <span class="dinero">`+datos[0].Total_Pagos_TransferenciaBancaria+`</span>
		                            </div>
		                        </div>`);
						}

						if (datos[0].Total_Pagos_TarjetaCreditoDebito > 0) {
							$("#DivMostrarPagosDesplegado").append(`
								<div class="row">
		                            <div class="col-md-12 col-sm-12 mb-3">
		                                Pagos en tarjeta de crédito / debito <span class="dinero">`+datos[0].Total_Pagos_TarjetaCreditoDebito+`</span>
		                            </div>
		                        </div>`);
						}

						$("#ImprimirBalance").attr("attrid", datos[0].ID_Detalle_Caja);
						moneda();
					})
					.fail(function() {
						console.log("Error ajax");
					})
				}else{
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Error inesperado al cerrar caja.'
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

jQuery(document).ready(function($) {

	$(document).on('hidden.bs.modal', '#ModalBalanceCaja',function(){
		$("#cargarVentas").trigger("click");
	});

	$(document).on('click', '#ImprimirBalance', function() {
		var iddetalle = $(this).attr("attrid");
		var idsucursal = $("#SucursalVenta").attr("attrid");
		var altura=50;
		var anchura=310;
		var y= parseInt((window.screen.height/2)-(altura/2));
		var x= parseInt((window.screen.width/2)-(anchura/2));
		window.open("controladores/ticketCaja.php?id="+iddetalle+"&idsucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
		$("#ModalBalanceCaja").modal("hide");
	});

	$(document).on('click', '#CargarClientesModalVentas', function() {
		TablaClienteVenta();
		$("#ModalVerClientesVenta").modal("show");
	});

	$(document).on('click', '#CargarClientesModalDirecciones', function() {
		var id = $("#CargarClientesModalVentas").attr("attrid");
		TablaDireccionesCliente(id);
		$("#ModalVerDireccionesCliente").modal("show");
	});

	$(document).on('click', '#TablaClienteVenta tbody tr', function() {
		var idcliente = $(this).attr("id");
		var nombre = $(this).children("td:eq(0)").text();
		var RFC = $(this).children("td:eq(2)").text();
		$("#ModalVerClientesVenta").modal("hide");
		$("#CargarClientesModalVentas").html("Cliente: "+nombre+"<br>RFC: "+RFC);
		$("#CargarClientesModalVentas").attr("attrid", idcliente);
		$(".BotonLimpiarCliente").removeClass("oculto");
	});

	$(document).on('click', '#TablaDireccionesClientes tbody tr', function() {
		var idDireccion = $(this).attr("id");
		if (idDireccion != "No") {
			var direccion = $(this).children("td:eq(0)").html();
			$("#ModalVerDireccionesCliente").modal("hide");
			$("#CargarClientesModalDirecciones").html(direccion);
			$("#CargarClientesModalDirecciones").attr("iddireccion", idDireccion);
		}
	});

	$(document).on('click', '#LimpiarClienteSeleccionado', function() {
		$(".BotonLimpiarCliente").addClass("oculto");
		$("#CargarClientesModalVentas").html('<i class="fas fa-user"></i> Seleccionar cliente');
		$("#CargarClientesModalVentas").attr("attrid", "");
		$("#CargarClientesModalDirecciones").attr("iddireccion", "");
		$("#CargarClientesModalDirecciones").html('<i class="fas fa-map-marker"></i> Dirección');
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
                	var campoImportes = ""; var precioimporte ="";
                	if (datos.ImportePresentacion > 0 || datos.ImporteGeneral > 0) {
                		campoImportes = "<br><span>Importes</span><input type='number' value='1' min='0' step='any' class='form-control form-control-sm campoCantidadImporte'>";
                		if (datos.ImportePresentacion > 0) {
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImportePresentacion+"</span>";
	                	}else{
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImporteGeneral+"</span>";
	                	}
                	}else{
                		campoImportes = "";
                	}

                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`"  importegeneral="`+datos.ImporteGeneral+`" importepresentacion="`+datos.ImportePresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br><button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Presentacion+`</button></td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.Precio_General+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Precio_General+`</button>`+precioimporte+`</td>
	                        <td><span>Productos</span><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto'>`+campoImportes+`</td>
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
	                        <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>Total de importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
	                        <td>
	                        	<button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button>
	                        </td>
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
        	//console.log($.trim(res));
            $("#CodigoProductoVenta").val("");
            if($.trim(res) == "No encontrado"){
                Swal.fire({
				  icon: 'error',
				  title: 'Producto no encontrado o sin existencia',
				  timer: 1200
				})
            }else{       
            	var datos = JSON.parse($.trim(res));
            	//console.log(datos);
                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').length > 0){                 
                	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
                 	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + 1);
                	$(".campoCantidadProducto").trigger("change");
                }else{
                	var campoImportes = ""; var precioimporte = "";
                	if (datos.ImportePresentacion > 0 || datos.ImporteGeneral > 0) {
                		campoImportes = "<br><span>Importes</span><input type='number' value='1' min='0' step='any' class='form-control form-control-sm campoCantidadImporte'>";
                		if (datos.ImportePresentacion > 0) {
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImportePresentacion+"</span>";
	                	}else{
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImporteGeneral+"</span>";
	                	}
                	}else{
                		campoImportes = "";
                	}
                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`" importegeneral="`+datos.ImporteGeneral+`" importepresentacion="`+datos.ImportePresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br> <button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Presentacion+`</button></td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.Precio_General+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Precio_General+`</button>`+precioimporte+`</td>
	                        <td><span>Productos</span><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto'>`+campoImportes+`</td>
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
	                        <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>Total de importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
	                        <td>
	                        	<button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
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

	$(document).on('change keyup', '.campoCantidadImporte', function() {
		if (parseFloat($(this).val()) > parseFloat($(this).parent().parent().children("td:eq(3)").find(".campoCantidadProducto").val())) {
			$(this).val(parseFloat($(this).parent().parent().children("td:eq(3)").find(".campoCantidadProducto").val()));
		}

		var cantidad = parseFloat($(this).val());
		var importe = 0;
		if ($(this).parent().parent().attr("importepresentacion") != 0) {
			importe = $(this).parent().parent().attr("importepresentacion");
		}else{
			importe = $(this).parent().parent().attr("importegeneral");	
		}
		var total = parseFloat(cantidad) * parseFloat(importe);
		$(this).parent().parent().children("td:eq(6)").find(".totalColumnaImporte").text(parseFloat(total) || 0);
		moneda();	
		CalcularSubtotalVenta();
	});

	$(document).on('change keyup', '.campoCantidadProducto', function() {
		if ($(this).val() == "") {
			$(this).val(1);
		}

		$(this).parent().find(".campoCantidadImporte").attr('max', parseFloat($(this).val()));
		$(this).parent().find(".campoCantidadImporte").val(parseFloat($(this).val()));
		
		var precio = $(this).parent().parent().children("td:eq(2)").find(".cambiarPrecio").attr("precio");
		//var cantidad = $(this).parent().parent().children("td:eq(3)").find(".campoCantidadProducto").val();
		var cantidad = $(this).val();
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
		var totalfinal = parseFloat(total) + parseFloat(totalImpuestos);
		/*console.log($(this).parent().find(".campoCantidadImporte").length);
		if ($(this).parent().find(".campoCantidadImporte").length > 0) {*/
		$(".campoCantidadImporte").trigger("keyup");

		$(this).parent().parent().children("td:eq(6)").find(".totalColumna").text(totalfinal);
		$(this).parent().parent().children("td:eq(6)").find(".totalColumna").attr("subtotal", total);
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
	            	var idsucursal = $("#SucursalVenta").attr("attrid"); var idDireccion = 0;
					if ($("#CargarClientesModalVentas").attr("attrid") == "") {
						var cliente = 1;
					}else{
						var cliente = $("#CargarClientesModalVentas").attr("attrid");
					}

					if ($("#CargarClientesModalDirecciones").attr("iddireccion") != "") {
						idDireccion = $("#CargarClientesModalDirecciones").attr("iddireccion");
					}
					var productos = new Array();
					var sumadescuento = 0;
					var total = $(this).attr("total");
					var totalventa = $(this).attr("totalventa");
					var totalimporte = $(this).attr("totalimportes");
					var fechaEntrega = $("#FechaEntregaPedido").val();
					const searchRegExp = new RegExp(',', 'g');
					$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
						var idProducto = $(this).attr("attrid");
						var Presentacion = $(this).attr("idpresentacion");
						var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
						var cantidadImportes = $(this).children("td:eq(3)").find(".campoCantidadImporte").val();
						var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
						var precioImporte = $(this).children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
						var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
						var totalproducto = $(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
						sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
						var impuestos = "";
						$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
							var impuesto = $(this).find(".seleccionarImpuesto");
							if (impuesto.prop("checked") == true) {
								impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
							}
						});
						productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto, cantidadImportes, precioImporte]);
					});

					var data = "metodo=modificar&accion=hacerventa&tipo=ModificarPedido&idsucursal="+idsucursal+"&cliente="+cliente+"&idDireccion="+idDireccion+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&fechaEntrega="+fechaEntrega+"&IDPedido="+$("#CargaPedidosModalVentas").attr("attrid")+"&totalventa="+totalventa+"&totalfinalimporte="+totalimporte;
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
				            	var idsucursal = $("#SucursalVenta").attr("attrid"); var idDireccion = 0;
								if ($("#CargarClientesModalVentas").attr("attrid") == "") {
									var cliente = 1;
								}else{
									var cliente = $("#CargarClientesModalVentas").attr("attrid");
								}

								if ($("#CargarClientesModalDirecciones").attr("iddireccion") != "") {
									idDireccion = $("#CargarClientesModalDirecciones").attr("iddireccion");
								}
								var productos = new Array();
								var sumadescuento = 0;
								var total = $(this).attr("total");
								var totalventa = $(this).attr("totalventa");
								var totalimporte = $(this).attr("totalimportes");
								var fechaEntrega = $("#FechaEntregaPedido").val();
								const searchRegExp = new RegExp(',', 'g');
								$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
									var idProducto = $(this).attr("attrid");
									var Presentacion = $(this).attr("idpresentacion");
									var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
									var cantidadImportes = $(this).children("td:eq(3)").find(".campoCantidadImporte").val();
									var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
									var precioImporte = $(this).children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
									var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
									var totalproducto = $(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
									sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());

									var impuestos = "";
									$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
										var impuesto = $(this).find(".seleccionarImpuesto");
										if (impuesto.prop("checked") == true) {
											impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
										}
									});
									productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto, cantidadImportes, precioImporte]);
								});

								var data = "metodo=insertar&accion=hacerventa&tipo=GuardarPedido&idsucursal="+idsucursal+"&cliente="+cliente+"&idDireccion="+idDireccion+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&fechaEntrega="+fechaEntrega+"&totalventa="+totalventa+"&totalfinalimporte="+totalimporte;
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
						var idsucursal = $("#SucursalVenta").attr("attrid"); var idDireccion = 0;
						if ($("#CargarClientesModalVentas").attr("attrid") == "") {
							var cliente = 1;
						}else{
							var cliente = $("#CargarClientesModalVentas").attr("attrid");
						}

						if ($("#CargarClientesModalDirecciones").attr("iddireccion") != "") {
							idDireccion = $("#CargarClientesModalDirecciones").attr("iddireccion");
						}
						var productos = new Array();
						var sumadescuento = 0;
						var total = $(this).attr("total");
						var totalventa = $(this).attr("totalventa");
						var totalimporte = $(this).attr("totalimportes");
						var fechaEntrega = $("#FechaEntregaPedido").val();
						const searchRegExp = new RegExp(',', 'g');
						$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
							var idProducto = $(this).attr("attrid");
							var Presentacion = $(this).attr("idpresentacion");
							var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
							var cantidadImportes = $(this).children("td:eq(3)").find(".campoCantidadImporte").val();
							var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
							var precioImporte = $(this).children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
							var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
							var totalproducto = $(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
							sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
							var impuestos = "";
							$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
								var impuesto = $(this).find(".seleccionarImpuesto");
								if (impuesto.prop("checked") == true) {
									impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
								}
							});
							productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto, cantidadImportes, precioImporte]);
						});

						var data = "metodo=insertar&accion=hacerventa&tipo=GuardarPedido&idsucursal="+idsucursal+"&cliente="+cliente+"&idDireccion="+idDireccion+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&fechaEntrega="+fechaEntrega+"&totalventa="+totalventa+"&totalfinalimporte="+totalimporte;
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
			//CONSULTAR SI ES ADMINISTRADOR
			var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarAdministrador";
			$.ajax({
				url: 'index.php',
			    type: 'POST',
			    data: data,
			})
			.done(function(res) {
				var sumadescuento = 0;
				$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
					sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
				});
				if ($.trim(res) != "Administrador" && sumadescuento > 0) {
					$("#ModalPermisoAdministrador").modal("show");
				}else{
					$("#ModalRealizarVenta").modal("show");
					$("#GuardarVenta").attr("tipo", "");
					$("#GuardarVenta").attr("idpedido", $("#CargaPedidosModalVentas").attr("attrid"));
					$("#GuardarVenta").attr("foliopedido", $("#CargaPedidosModalVentas").attr("folio"));
					$("#ImportePagadoVenta").val(total);
				}
			})
			.fail(function() {
				console.log("Error ajax");
			});
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
			var idDireccion = 0;
			var idsucursal = $("#SucursalVenta").attr("attrid");
			if ($("#CargarClientesModalVentas").attr("attrid") == "") {
				var cliente = 1;
			}else{
				var cliente = $("#CargarClientesModalVentas").attr("attrid");
			}

			if ($("#CargarClientesModalDirecciones").attr("iddireccion") != "") {
				idDireccion = $("#CargarClientesModalDirecciones").attr("iddireccion");
			}
			var productos = new Array();
			var sumadescuento = 0;
			var total = $("#RealizarVenta").attr("total");
			var totalventa = $("#RealizarVenta").attr("totalventa");
			var totalimporte = $("#RealizarVenta").attr("totalimportes");
			var tipopago = $("#TipoPagoVenta").val();
			var pago = $("#ImportePagadoVenta").val();
			const searchRegExp = new RegExp(',', 'g');
			$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
				var idProducto = $(this).attr("attrid");
				var Presentacion = $(this).attr("idpresentacion");
				var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
				var cantidadImportes = $(this).children("td:eq(3)").find(".campoCantidadImporte").val();
				var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
				var precioImporte = $(this).children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
				var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
				var totalproducto = $(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
				sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
				var Cobrarimporte = $(this).children("td:eq(7)").find("#CobrarImporteProducto").prop("checked");
				if (Cobrarimporte == true) {
					Cobrarimporte = 1;
				}else{
					Cobrarimporte = 0;
				}
				var impuestos = "";
				$(this).children("td:eq(4)").find(".impuesto").each(function(index, el) {
					var impuesto = $(this).find(".seleccionarImpuesto");
					if (impuesto.prop("checked") == true) {
						impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
					}
				});
				productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto, cantidadImportes, precioImporte]);
			});
			console.log(productos);
			var data = "metodo=insertar&accion=hacerventa&tipo=RealizarVenta&idsucursal="+idsucursal+"&cliente="+cliente+"&idDireccion="+idDireccion+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&TipoPago="+tipopago+"&Importe="+pago+"&totalventa="+totalventa+"&totalfinalimporte="+totalimporte;
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
		//CONSULTAR SI ES ADMINISTRADOR
		var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarAdministrador";
		$.ajax({
			url: 'index.php',
		    type: 'POST',
		    data: data,
		})
		.done(function(res) {
			var sumadescuento = 0;
			$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
				sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());
			});
			if ($.trim(res) != "Administrador" && sumadescuento > 0) {
				$("#ModalPermisoAdministrador").modal("show");
			}else{
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
			}
		})
		.fail(function() {
			console.log("Error ajax");
		});
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
			const searchRegExp = new RegExp(',', 'g');
			subtotal += parseFloat($(this).children("td:eq(6)").find(".totalColumna").attr("subtotal"))
			subtotal += parseFloat($(this).children("td:eq(6)").find(".totalColumnaImporte").text().replace("$","").replace(searchRegExp, ''));
		});
		$("#MostrarSubtotalVenta").text(subtotal);
		$("#cantidadProductosSpanVenta").text($("#TablaProductosAgregadoVenta tbody tr").length)
		moneda();
		CalcularTotal();
	}

	function CalcularTotal(){
		var total = 0;
		var totalventa = 0;
		var totalimportes = 0;
		$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
			const searchRegExp = new RegExp(',', 'g');
			total += parseFloat($(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, ''));
			total += parseFloat($(this).children("td:eq(6)").find(".totalColumnaImporte").text().replace("$","").replace(searchRegExp, ''));
			totalventa += parseFloat($(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, ''));
			totalimportes += parseFloat($(this).children("td:eq(6)").find(".totalColumnaImporte").text().replace("$","").replace(searchRegExp, ''));
		});
		$("#TotalVentaFinal").text(total);
		$("#GuardarPedido").attr("total", total);
		$("#GuardarPedido").attr("totalventa", totalventa);
		$("#GuardarPedido").attr("totalimportes", totalimportes);
		$("#RealizarVenta").attr("total", total);
		$("#RealizarVenta").attr("totalventa", totalventa);
		$("#RealizarVenta").attr("totalimportes", totalimportes);
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
		$("#CargarClientesModalDirecciones").html('<i class="fas fa-map-marker"></i> Dirección');


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
			$("#CargarClientesModalDirecciones").attr("iddireccion", "");
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

				var campoImportes = ""; var precioimporte = "";
                if (datos.data.Productos.data[i].ImportePresentacion > 0 || datos.data.Productos.data[i].ImporteGeneral > 0) {
                	campoImportes = "<br><span>Importes</span><input type='number' value='1' min='0' max='"+datos.data.Productos.data[i].Cantidad+"' step='any' class='form-control form-control-sm campoCantidadImporte'>";
                	if (datos.data.Productos.data[i].ImportePresentacion > 0) {
                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.data.Productos.data[i].ImportePresentacion+"</span>";
                	}else{
                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.data.Productos.data[i].ImporteGeneral+"</span>";
                	}
                }else{
                	campoImportes = "";
                }

				$('#TablaProductosAgregadoVenta tbody').append(`
		        <tr attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`" importegeneral="`+datos.data.Productos.data[i].ImporteGeneral+`" importepresentacion="`+datos.data.Productos.data[i].ImportePresentacion+`">
		        	<td>`+datos.data.Productos.data[i].Codigo+`</td>
		            <td>`+datos.data.Productos.data[i].Descripcion+` <br> <button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`">`+datos.data.Productos.data[i].NombrePresentacion+`</button>
		            <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.data.Productos.data[i].Precio+`" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+datos.data.Productos.data[i].FK_Presentacion+`">`+datos.data.Productos.data[i].Precio+`</button>`+precioimporte+`</td>
		            <td><span>Productos</span><input type='number' value='`+datos.data.Productos.data[i].Cantidad+`' min='1' step='any' class='form-control form-control-sm campoCantidadProducto'>`+campoImportes+`</td>
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
		            <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>Total de importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
		            <td>
						<button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
		        </tr>`);

				$(".campoDescuentoProductoCantidad").trigger("keyup");
				$(".BotonLimpiarCliente").removeClass("oculto");
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
		var importegeneral = $(this).attr("importegeneral");
		var importepresentacion = $(this).attr("importepresentacion");
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
        	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').attr("importegeneral", importegeneral);
        	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').attr("importepresentacion", importepresentacion);
	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').attr("idPresentacion", idpresentacion);
	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(1)").find(".CambiarPresentacion").attr("idpresentacion", idpresentacion);
	    	if (abreviatura == "") {
	    		$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(1)").find(".CambiarPresentacion").text(nombre);
	    	}else{
	    		$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(1)").find(".CambiarPresentacion").text(nombre+"("+abreviatura+")");
	    	}

	    	if (importepresentacion > 0) {
		    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".campoPrecioImporte").text(importepresentacion);
		    }else{
		    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".campoPrecioImporte").text(importegeneral);
		    }

	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".cambiarPrecio").attr("precio", precio);
	    	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".cambiarPrecio").text(precio);
	    }
	    $(".campoCantidadProducto").trigger("change");
		moneda();

	});

	$(document).on('change keyup', '#ImportePagadoVenta', function() {
		const searchRegExp = new RegExp(',', 'g');
		var pagado = parseFloat($(this).val()) || 0;
		$("#verCambio").html(pagado - parseFloat($("#TotalVentaFinal").text().replace('$', '').replace(searchRegExp, '')));
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
			"asc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"accion": "hacerventa",
			"tipo": "ConsultarCliente"
		}
	});
}

function TablaDireccionesCliente(id){ 
	ajaxMyDatatable({
		"table": $("#TablaDireccionesClientes"), 
		"colums": [
			"Domicilio",
			"Colonia",
			"Ubicación",
		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"accion": "hacerventa",
			"tipo": "ConsultarDireccionCliente",
			"idCliente": id
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
			0,
			"asc"
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
			"presentacion": presentacion,
			"sucursal": $("#SucursalVenta").attr("attrid"),
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


function EstatusCaja(){
	var data = "metodo=detalles&accion=ventas&tipo=ConsultarCaja";
	$.ajax({
		url: 'index.php',
		type: 'POST',
		data: data,
	})
	.done(function(res) {
		if ($.trim(res) == "Abierta") {
			$("#BotonCerrarCaja").removeClass("oculto");
		}else{
			$("#BotonCerrarCaja").addClass("oculto");
			$("#cargarVentas").trigger("click");
		}
	})
	.fail(function() {
		console.log("Error ajax");
	});
}