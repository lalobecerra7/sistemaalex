function v_hacerventa() {
	//TablaReporteCompras();
    //SI HAY MODALES ABIERTOS QUE NO SE EJECUTE ESTE CODIGO
    /*var inputElement = document.getElementById("CodigoProductoVenta");
    inputElement.focus();
    inputElement.addEventListener("blur", function(event){
        inputElement.focus();
    }); */

    $(document).on("mousedown", function(e) {
      clicked = $(e.target)
    })

    $("input").on("blur", function() {
      if (!clicked.is(".campoCantidadProducto") && !clicked.is("#CodigoProductoVenta")  && !clicked.is("#DescuentoDineroProducto")) {
        $(this).focus()
      }
    })

	EstatusCaja();
    $("#CodigoProductoVenta").focus();

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

    $(document).on('change keyup', '#MontoCierreCaja', function() {
    	const searchRegExp = new RegExp(',', 'g');
    	var montocierre = $(this).val() || 0;
    	var montoTotal = $("#spanTotalEfectivo").text().replace("$","").replace(searchRegExp, '');
    	var diferencia = parseFloat(montocierre) - parseFloat(montoTotal);
    	console.log("cierre: "+montocierre);
    	console.log("total: "+montoTotal);
    	console.log("diferencia: "+diferencia);
        $("#spanTotalDiferencia").text(diferencia.toFixed(2));
        if (diferencia < 0) {
        	$("#spanTotalDiferencia").css("color", "red");
        }else{
        	$("#spanTotalDiferencia").css("color", "green");
        }
        moneda();
    });

    $(document).on('click', '#BotonCerrarCaja', function() {
        $("#ModalCerrarCaja").modal("show");
    	$("#MontoCierreCaja").focus();
   		var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarDetalleCaja&sucursal="+$("#SucursalVenta").attr("attrid");
        $.ajax({
        	url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
            	$("#carga").show();
            }
        })
        .done(function(res) {
        	var iddetallecaja = $.trim(res);
        	$("#CerrarCajaVentas").attr("iddetallecaja", iddetallecaja);
        	var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarBalanceCerrar&IDDetalleCaja="+iddetallecaja+"&sucursal="+$("#SucursalVenta").attr("attrid");
            $.ajax({
            	url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                var datos = JSON.parse($.trim(res));
                console.log(datos);
                $("#spanVentasTotales").text(datos[0].Total_Ventas);
                $("#spanMontoApertura").text(datos[0].Monto_Abrir);
                $("#spanVentasEfectivo").text(datos[0].Total_Ventas_Efectivo);
                //$("#spanTotalImportes").text(datos[0].Total_Importes);
                $("#spanTotalPagoImportes").text(datos[0].Total_Importes_Egresos);
                $("#spanTotalCompras").text(datos[0].Total_Compras_Efectivo);
                $("#spanPagosEfectivo").text(datos[0].Total_Pagos_Efectivo);
                $("#spanTotalDevoluciones").text(datos[0].Total_Devoluciones);
                var ingresosefectivo = parseFloat(datos[0].Monto_Abrir) + parseFloat(datos[0].Total_Ventas_Efectivo);
                var egresosefectivo = parseFloat(datos[0].Total_Compras_Efectivo) + parseFloat(datos[0].Total_Pagos_Efectivo) + parseFloat(datos[0].Total_Devoluciones) + parseFloat(datos[0].Total_Importes_Egresos);
                var total = ingresosefectivo - egresosefectivo;
                $("#spanTotalEfectivo").text(total.toFixed(2));
                if (total < 0) {
                	$("#spanTotalEfectivo").css("color", "red");
                }else{
                	$("#spanTotalEfectivo").css("color", "green");
                }
                $("#MontoCierreCaja").val(total);
                $("#spanVentasEnEfectivo").text(datos[0].Total_Ventas_Efectivo);
                $("#spanVentasDeposito").text(datos[0].Total_Ventas_Deposito);
                $("#spanVentasCheque").text(datos[0].Total_Ventas_Cheque);
                $("#spanVentasTransferencia").text(datos[0].Total_Ventas_TransferenciaBancaria);
                $("#spanVentasTarjeta").text(datos[0].Total_Ventas_TarjetaCredito);
                $("#spanVentasTarjetaDebito").text(datos[0].Total_Ventas_TarjetaDebito);
                $("#spanVentasPagoOnline").text(datos[0].Total_Ventas_PagoOnline);
                $("#spanTotalDevolucionesVenta").text(datos[0].Total_Devoluciones);

                //var ventas = parseFloat(datos[0].Total_Ventas_Efectivo) + parseFloat(datos[0].Total_Ventas_Deposito) + parseFloat(datos[0].Total_Ventas_Cheque) + parseFloat(datos[0].Total_Ventas_TransferenciaBancaria) + parseFloat(datos[0].Total_Ventas_TarjetaCreditoDebito) + parseFloat(datos[0].Total_Ventas_PagoOnline); 
                var ventas = parseFloat(datos[0].Total_Ventas_Efectivo) + parseFloat(datos[0].Total_Ventas_Cheque) + parseFloat(datos[0].Total_Ventas_TransferenciaBancaria) + parseFloat(datos[0].Total_Ventas_TarjetaCredito) + parseFloat(datos[0].Total_Ventas_TarjetaDebito); 
                var totalventas= ventas - parseFloat(datos[0].Total_Devoluciones);
                $("#spanTotalVentas").text(totalventas.toFixed(2));
                if (totalventas < 0) {
                	$("#spanTotalVentas").css("color", "red");
                }else{
                	$("#spanTotalVentas").css("color", "green");
                }

                $("#spanComprasEnEfectivo").text(datos[0].Total_Compras_Efectivo);
                $("#spanComprasDeposito").text(datos[0].Total_Compras_Cheque);
                $("#spanComprasCheque").text(datos[0].Total_Compras_Deposito);
                $("#spanComprasTransferencia").text(datos[0].Total_Compras_Tarjeta);
                $("#spanComprasTarjeta").text(datos[0].Total_Compras_Transferencia);
                $("#spanComprasTotal").text(datos[0].Total_Compras);

                $("#spanPagosEnEfectivo").text(datos[0].Total_Pagos_Efectivo);
                $("#spanPagosDeposito").text(datos[0].Total_Pagos_Deposito);
                $("#spanPagosCheque").text(datos[0].Total_Pagos_Cheque);
                $("#spanPagosTransferencia").text(datos[0].Total_Pagos_TransferenciaBancaria);
                $("#spanPagosTarjeta").text(datos[0].Total_Pagos_TarjetaCreditoDebito);
                $("#spanPagosTotal").text(datos[0].Total_Pagos);

                $("#ImprimirBalance").attr("attrid", datos[0].ID_Detalle_Caja);
                $("#MontoCierreCaja").trigger("keyup");
            	moneda();
            })
            .fail(function() {
            	console.log("Error ajax");
            })

        })
        .fail(function() {
        	console.log("Error ajax");
        }) 
        .always(function() {
	    	$("#carga").hide();
	    }); 
    });

    //CERRAR CAJA
    $('#FormCerrarCaja').validate({
        rules: {
            MontoCierreCaja: {
                required: true,
                min: 0,
            },
        },
        messages: {
            MontoCierreCaja: {
                required: "Ingresa el monto de cierre de la caja"
            },
        },
        submitHandler: function(form) { 

            var data = "metodo=detalles&accion=hacerventa&tipo=CerrarCaja&MontoCierre="+$("#MontoCierreCaja").val()+"&sucursal="+$("#SucursalVenta").attr("attrid")+"&iddetallecaja="+$("#CerrarCajaVentas").attr("iddetallecaja");
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Corte de caja realizado correctamente'
                    });
                    var iddetalle = $("#CerrarCajaVentas").attr("iddetallecaja");
			        var idsucursal = $("#SucursalVenta").attr("attrid");
			        if (idsucursal == undefined) {
			            idsucursal = "";
			        }
			        var altura=50;
			        var anchura=310;
			        var y= parseInt((window.screen.height/2)-(altura/2));
			        var x= parseInt((window.screen.width/2)-(anchura/2));
			        window.open("controladores/ticketCaja.php?id="+iddetalle+"&idsucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
                   
                  	$("#cargarVentas").trigger("click");
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

    

    /*   */

    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if(evt.key === "F2"){
            $("#CargarProductosModalVentas").trigger("click");
        }else if(evt.key === "F8"){
            $("#RealizarVenta").trigger("click");
        }

        //METODO PARA DESPLAZARSE CON LAS FLECHAS EN EL MODAL DE PRODUCTOS
        /*var fila = $("#VentaTablaProductos").children('tbody').children('tr.activa').index();

        if($("#VentaTablaProductos").children('tbody').children('tr').length > 1){
            if(evt.key === "ArrowUp"){
                if((fila - 1) >= 0){
                    $("#VentaTablaProductos").children('tbody').children('tr').removeClass('activa');
                    $("#VentaTablaProductos").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                }
            }else if(evt.key === "ArrowDown"){
                if($("#VentaTablaProductos").children('tbody').children('tr:eq('+(fila + 1)+')').length > 0){
                    $("#VentaTablaProductos").children('tbody').children('tr').removeClass('activa');
                    $("#VentaTablaProductos").children('tbody').children('tr:eq('+(fila + 1)+')').addClass('activa');
                }
            }
        }*/
    }


    $('#FormDescuentoProducto').validate({
        rules: {
            TokenDescuento: {
                required: true,
            },
        },
        messages: {
            TokenDescuento: {
                required: "El código de descuento es obligatorio",
            },
        },
        submitHandler: function(form) { 
            var idProducto = $("#GuardarDescuentoProducto").attr("attrid");
            var idPresentacion = $("#GuardarDescuentoProducto").attr("presentacion");
            //CONSULTAR VALIDEZ CODIGO
            var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarToken&codigo="+$("#TokenDescuento").val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
            })
            .done(function(res) {
                var datos = $.trim(res).split("~");
                if ($.trim(datos[0]) == "Correcto") {
                    if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').length > 0){  
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(5)").find("#SpanTextoDescuento").text(datos[1]);
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(5)").find("#SpanTextoDescuento").attr("actual", datos[1]);
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(5)").find("#SpanTextoDescuento").attr("codigo", $("#TokenDescuento").val());
                        $("#ModalDescuentoProducto").modal("hide");

                        var descuento = parseFloat(datos[1]);
                        var subtotalActual = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(6)").find(".totalColumna").attr("subtotal");
                        var total = parseFloat(subtotalActual) - descuento;
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(6)").find(".totalColumna").attr("subtotal", total);
                        //CalcularSubtotalVenta();
                        $(".campoCantidadProducto").trigger("change");
                        console.log(total);
                        /*var cantidad = parseFloat($("#DescuentoDineroProducto").val());
                        var importe = 0;
                        if ($(this).parent().parent().attr("importepresentacion") != 0) {
                            importe = $(this).parent().parent().attr("importepresentacion");
                        }else{
                            importe = $(this).parent().parent().attr("importegeneral");    
                        }
                        var total = parseFloat(cantidad) * parseFloat(importe);
                        $(this).parent().parent().children("td:eq(6)").find(".totalColumnaImporte").text(parseFloat(total) || 0);
                        moneda();    
                        CalcularSubtotalVenta();*/

                        
                        moneda();
                    } 
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Este token no está disponible, intenta con otro.'
                    });    
                }
            })
            .fail(function() {
                console.log("Error ajax");
            }); 
        }
    });  
}


jQuery(document).ready(function($) {

    $(document).on('hidden.bs.modal', '#ModalVerProductosVenta',function(){
       $("#CodigoProductoVenta").focus();
       //$("#AgregarProductoVenta").trigger("click");
    });

    $(document).on('hidden.bs.modal', '#ModalCerrarCaja',function(){
       $("#CodigoProductoVenta").focus();
    });

    $(document).on('hidden.bs.modal', '#ModalVerClientesVenta',function(){
       $("#CodigoProductoVenta").focus();
    });

    $(document).on('hidden.bs.modal', '#ModalVerDireccionesCliente',function(){
       $("#CodigoProductoVenta").focus();
    });

    $(document).on('hidden.bs.modal', '#ModalVerPedidosVenta',function(){
       $("#CodigoProductoVenta").focus();
    });

    $(document).on('hidden.bs.modal', '#ModalDescuentoProducto',function(){
       $("#CodigoProductoVenta").focus();
    });

    

	/*$(document).on('hidden.bs.modal', '#ModalBalanceCaja',function(){
        $("#cargarVentas").trigger("click");
    });

    $(document).on('click', '#ImprimirBalance', function() {
        var iddetalle = $(this).attr("attrid");
        var idsucursal = $("#SucursalVenta").attr("attrid");
        console.log(idsucursal);
        if (idsucursal == undefined) {
            idsucursal = "";
        }
        var altura=50;
        var anchura=310;
        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
        window.open("controladores/ticketCaja.php?id="+iddetalle+"&idsucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
        $("#ModalBalanceCaja").modal("hide");
    });*/

	$(document).on('click', '#CargarClientesModalVentas', function() {
		TablaClienteVenta();
		$("#ModalVerClientesVenta").modal("show");
	});

	$(document).on('click', '#CargarClientesModalDirecciones', function() {
		var id = $("#CargarClientesModalVentas").attr("attrid");
		TablaDireccionesCliente(id);
		$("#ModalVerDireccionesCliente").modal("show");
	});

    /*$('#CodigoProductoVenta').on('blur',function () { 
        var blurEl = $(this); 
        setTimeout(function() {
            blurEl.focus()
        }, 10);
    });*/

	$(document).on('click', '#TablaClienteVenta tbody tr', function() {
        if ($(this).attr("id") != undefined) {
            var idcliente = $(this).attr("id");
            var nombre = $(this).children("td:eq(0)").text();
            var RFC = $(this).children("td:eq(2)").text();
            $("#ModalVerClientesVenta").modal("hide");
            $("#CargarClientesModalVentas").html("<span style='font-size: 10px;'>Cliente: "+nombre+"<br>RFC: "+RFC+"</span>");
            $("#CargarClientesModalVentas").attr("attrid", idcliente);
            $(".BotonLimpiarCliente").removeClass("oculto");
        }
	});

	$(document).on('click', '#TablaDireccionesClientes tbody tr', function() {
		var idDireccion = $(this).attr("id");
		if (idDireccion != "No") {
			var direccion = $(this).children("td:eq(0)").html();
			$("#ModalVerDireccionesCliente").modal("hide");
			$("#CargarClientesModalDirecciones").html("<span style='font-size: 10px;'>"+direccion+"</span>");
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
        $("#ModalVerProductosVenta").modal("show");
		VentaTablaProductos();
        $(".BuscadorTablaVentaTablaProductos").val("");
        $(".BuscadorTablaVentaTablaProductos").trigger("keyup");
        $(".BuscadorTablaVentaTablaProductos").focus();
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
                		campoImportes = "<span>Importes</span><input type='number' value='1' min='0' step='any' class='form-control form-control-sm campoCantidadImporte'>";
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
	                        <td><span>Productos</span><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto' onfocusout="$('#CodigoProductoVenta').focus()">`+campoImportes+`</td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
                                <button class="btn btn-sm btn-primary AbrirDescuentoProducto" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`"><span id="SpanTextoDescuento" class="dinero" codigo actual>$0</span></button>
		                    </td>
	                        <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>Importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
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
        console.log(data);
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
                		campoImportes = "<span>Importes</span><input type='number' value='1' min='0' step='any' class='form-control form-control-sm campoCantidadImporte'>";
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
	                        <td><span>Productos</span><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto' onfocusout="$('#CodigoProductoVenta').focus()">`+campoImportes+`</td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
                                <button class="btn btn-sm btn-primary AbrirDescuentoProducto" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`"><span id="SpanTextoDescuento" class="dinero" codigo actual>$0</span></button>
	                        </td>
	                        <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>Importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
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
		//$(this).parent().find(".campoCantidadImporte").val(0);
		
		var precio = $(this).parent().parent().children("td:eq(2)").find(".cambiarPrecio").attr("precio");
        var descuento = parseFloat($(this).parent().parent().children("td:eq(5)").find("#SpanTextoDescuento").attr("actual")) || 0;
		//var cantidad = $(this).parent().parent().children("td:eq(3)").find(".campoCantidadProducto").val();
		var cantidad = $(this).val();
		var subtotal = (parseFloat(precio) * parseFloat(cantidad)) - descuento;
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
						var totalproducto = parseFloat(cantidad * precio) + parseFloat(cantidadImportes * precioImporte);
                        var descuento = 0;
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
                            footer: '<b style="color: red;">Los descuentos no se guardan en los pedidos</b>',
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
                                //SI SE GUARDA COMO PEDIDO NO SE GUARDA EL DESCUENTO
								$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
									var idProducto = $(this).attr("attrid");
									var Presentacion = $(this).attr("idpresentacion");
									var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
									var cantidadImportes = $(this).children("td:eq(3)").find(".campoCantidadImporte").val();
									var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
									var precioImporte = $(this).children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
									var totalproducto = parseFloat(cantidad * precio) + parseFloat(cantidadImportes * precioImporte);
                                    var descuento = 0;
                                    //var descuento = $(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val();
									//var totalproducto = $(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
									//sumadescuento += parseFloat($(this).children("td:eq(5)").find(".campoDescuentoProductoCantidad").val());

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
            $("#ModalRealizarVenta").modal("show");
            $("#GuardarVenta").attr("tipo", "");
            $("#GuardarVenta").attr("idpedido", $("#CargaPedidosModalVentas").attr("attrid"));
            $("#GuardarVenta").attr("foliopedido", $("#CargaPedidosModalVentas").attr("folio"));
            $("#ImportePagadoVenta").val(total);
            $("#PagoEfectivo").trigger("keyup");

			//CONSULTAR SI ES ADMINISTRADOR
			/*var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarAdministrador";
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
                    $("#PagoEfectivo").trigger("keyup");
				}
			})
			.fail(function() {
				console.log("Error ajax");
			});*/
		}
	});

	$(document).on('click', '#GuardarVenta', function() {
        var efectivo = $("#PagoEfectivo").val() || 0;  
        var transferencia = $("#PagoTransferencia").val() || 0;  
        var cheque = $("#PagoCheque").val() || 0;  
        var tcredito = $("#PagoTCredito").val() || 0;  
        var tdebito = $("#PagoTDebito").val() || 0;  
        var pagado = parseFloat(efectivo) + parseFloat(transferencia) + parseFloat(cheque) + parseFloat(tcredito) + parseFloat(tdebito);
        var total = $("#RealizarVenta").attr("total");

		if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
			Swal.fire({
			    icon: 'error',
			    title: 'No se puede realizar una venta sin productos',
			    timer: 1000
			});
		}else if ($("#PagoEfectivo").val() == "" && $("#PagoTransferencia").val() == "" && $("#PagoCheque").val() == "" && $("#PagoTCredito").val() == "" && $("#PagoTDebito").val() == "") {
            Swal.fire({
                icon: 'error',
                title: 'Ingresa al menos un metodo de pago',
                timer: 1000
            });
        }else if (parseFloat(pagado) < parseFloat(total)) {
            Swal.fire({
                icon: 'error',
                title: 'Los pagos no cubren el total de la venta',
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
            const searchRegExp = new RegExp(',', 'g');
			var productos = new Array();
			var sumadescuento = 0;
			var totalventa = $("#RealizarVenta").attr("totalventa");
			var totalimporte = $("#RealizarVenta").attr("totalimportes");
			var tipopago = $("#TipoPagoVenta").val();
			var pago = $("#ImportePagadoVenta").val();
            var cambio = $("#verCambio").text().replace("$","").replace(searchRegExp, '');
            //CAMPOS DE PAGO MIXTO//

            var pagoEfectivo = $("#PagoEfectivo").val();
            var pagoTransferencia = $("#PagoTransferencia").val();
            var pagoCheque = $("#PagoCheque").val();
            var pagoTCredito = $("#PagoTCredito").val();
            var pagoTDebito = $("#PagoTDebito").val();

            ////////////////////////
			$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
				var idProducto = $(this).attr("attrid");
				var Presentacion = $(this).attr("idpresentacion");
				var cantidad = $(this).children("td:eq(3)").find(".campoCantidadProducto").val();
				var cantidadImportes = $(this).children("td:eq(3)").find(".campoCantidadImporte").val();
				var precio = $(this).children("td:eq(2)").find(".cambiarPrecio").attr("precio");
				var precioImporte = $(this).children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
				var descuento = $(this).children("td:eq(5)").find("#SpanTextoDescuento").attr("actual");
                var codigoDescuento = $(this).children("td:eq(5)").find("#SpanTextoDescuento").attr("codigo");
				var totalproducto = $(this).children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
				sumadescuento += parseFloat($(this).children("td:eq(5)").find("#SpanTextoDescuento").attr("actual"));
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
				productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto, cantidadImportes, precioImporte, codigoDescuento]);
			});

			var contarVenta = 0;
			if ($("#ContarVenta").prop("checked")) {
				contarVenta = 1;
			}

			var data = "metodo=insertar&accion=hacerventa&tipo=RealizarVenta&idsucursal="+idsucursal+"&cliente="+cliente+"&idDireccion="+idDireccion+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&TipoPago="+tipopago+"&Importe="+pago+"&totalventa="+totalventa+"&totalfinalimporte="+totalimporte+"&contarVenta="+contarVenta+"&pagoEfectivo="+pagoEfectivo+"&pagoTransferencia="+pagoTransferencia+"&pagoCheque="+pagoCheque+"&pagoTCredito="+pagoTCredito+"&pagoTDebito="+pagoTDebito+"&cambio="+cambio;
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
                    var total = $("#RealizarVenta").attr("total");
					$("#ModalRealizarVenta").modal("show");
					$("#GuardarVenta").attr("idpedido", $("#CargaPedidosModalVentas").attr("attrid"));
					$("#GuardarVenta").attr("tipo", "facturar");
					$("#GuardarVenta").attr("foliopedido", $("#CargaPedidosModalVentas").attr("folio"));
                    $("#ImportePagadoVenta").val(total);
				}
			}
		})
		.fail(function() {
			console.log("Error ajax");
		});
	});

	$(document).on('click', '#bQuitarLimpiarPedido', function() {
		$("#cargarHacerVenta").trigger("click");
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
        $("#ModalVerPedidosVenta").modal("show");
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
			$("#bQuitarLimpiarPedido").removeClass("oculto");
			$("#CargarClientesModalDirecciones").attr("iddireccion", "");
             $("#CargarClientesModalVentas").html("<span style='font-size: 10px;'>Cliente: "+datos.data.NombreCliente+"<br>RFC: "+datos.data.RFCCliente+"</span>");
			$("#CargarClientesModalVentas").attr("attrid", datos.data.FK_Cliente);
			$("#ModalVerPedidosVenta").modal("hide");
			for (var i = 0; i < datos.data.Productos.data.length; i++) {
				var presentacion = null;

				if (datos.data.Productos.data[i].FK_Presentacion != 0) {
					presentacion = datos.data.Productos.data[i].FK_Presentacion;
				}else{
					presentacion = null;
				}

				var campoImportes = ""; var precioimporte = "";
                if (datos.data.Productos.data[i].ImportePresentacion > 0 || datos.data.Productos.data[i].ImporteGeneral > 0) {
                	campoImportes = "<span>Importes</span><input type='number' value='1' min='0' max='"+datos.data.Productos.data[i].Cantidad+"' step='any' class='form-control form-control-sm campoCantidadImporte'>";
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
			        	<button class="btn btn-sm btn-primary AbrirDescuentoProducto" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`"><span id="SpanTextoDescuento" class="dinero" codigo actual>$0</span></button>
		            </td>
		            <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>Importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
		            <td>
						<button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
		        </tr>`);

				$(".campoCantidadProducto").trigger("keyup");
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

    $(document).on('click', '.AbrirDescuentoProducto', function() {
        const searchRegExp = new RegExp(',', 'g');
        var codigoUsado = $(this).children("span").attr("codigo");
        var actual = $(this).children("span").text().replace('$', '').replace(searchRegExp, '');
        var idProducto = $(this).attr("attrid");
        var idPresentacion = $(this).attr("idPresentacion");
        $("#DescuentoDineroProducto").focus();
        $("#ModalDescuentoProducto").modal("show");
        $("#GuardarDescuentoProducto").attr("attrid", idProducto);
        $("#GuardarDescuentoProducto").attr("presentacion", idPresentacion);
        $("#GuardarDescuentoProducto").attr("actual", actual);
        $("#GuardarDescuentoProducto").attr("codigo", codigoUsado);
    });

    $(document).on('click', '#GuardarDescuentoProducto', function() {
        $("#FormDescuentoProducto").submit();
    });
	/*$(document).on('change keyup', '#ImportePagadoVenta', function() {
		const searchRegExp = new RegExp(',', 'g');
		var pagado = parseFloat($(this).val()) || 0;
		$("#verCambio").html(pagado - parseFloat($("#TotalVentaFinal").text().replace('$', '').replace(searchRegExp, '')));
		moneda();
	});*/

    $(document).on('change keyup', '#PagoEfectivo', function() {
        CalcularCambio();
    });

    $(document).on('change keyup', '#PagoTransferencia', function() {
        CalcularCambio();
    });

    $(document).on('change keyup', '#PagoCheque', function() {
        CalcularCambio();
    });

    $(document).on('change keyup', '#PagoTCredito', function() {
        CalcularCambio();
    });

    $(document).on('change keyup', '#PagoTDebito', function() {
        CalcularCambio();
    });



});

function CalcularCambio(){
    const searchRegExp = new RegExp(',', 'g');
    var efectivo = $("#PagoEfectivo").val() || 0;  
    var transferencia = $("#PagoTransferencia").val() || 0;  
    var cheque = $("#PagoCheque").val() || 0;  
    var tcredito = $("#PagoTCredito").val() || 0;  
    var tdebito = $("#PagoTDebito").val() || 0;  
    var pagado = parseFloat(efectivo) + parseFloat(transferencia) + parseFloat(cheque) + parseFloat(tcredito) + parseFloat(tdebito);
    console.log(pagado);
    $("#verCambio").html(pagado - parseFloat($("#TotalVentaFinal").text().replace('$', '').replace(searchRegExp, '')));
    moneda();
}

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
			"tipo": "ConsultarCliente",
			"sucursal": $("#SucursalVenta").attr("attrid")
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
	/*var data = "metodo=detalles&accion=ventas&tipo=ConsultarCaja";
	$.ajax({
		url: 'index.php',
		type: 'POST',
		data: data,
	})
	.done(function(res) {
		var separa = $.trim(res).split('~');

		if (separa[0] == "Abierta") {*/
			$("#BotonCerrarCaja").removeClass("oculto");
		/*}else{
			$("#BotonCerrarCaja").addClass("oculto");
			$("#cargarVentas").trigger("click");
		}
	})
	.fail(function() {
		console.log("Error ajax");
	});*/
}