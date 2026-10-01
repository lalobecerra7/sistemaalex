function v_hacerventa() {

    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalModificarVentas").val(today);
    
    now.setDate(now.getDate() - 7);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioModificarVentas").val(today);

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
        submitHandler: function(form, event) {
            event.preventDefault(); 
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
                    $("#CodigoProductoVenta").attr("disabled", true);
					$("#ModalRealizarVenta").modal("show");
                    $("#CodigoProductoVenta").attr("disabled", false);
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
                $("#spanVentasTotales").text(datos[0].Total_Ventas);
                $("#spanMontoApertura").text(datos[0].Monto_Abrir);
                $("#spanVentasEfectivo").text(datos[0].Total_Ventas_Efectivo);
                //$("#spanTotalImportes").text(datos[0].Total_Importes);
                $("#spanTotalPagoImportes").text(datos[0].Total_Importes_Egresos);
                $("#spanTotalCompras").text(datos[0].Total_Compras_Efectivo);
                $("#spanPagosEfectivo").text(datos[0].Total_Pagos_Efectivo);
                $("#spanTotalDevoluciones").text(datos[0].Total_Devoluciones);
                var ingresosefectivo = parseFloat(datos[0].Monto_Abrir) + parseFloat(datos[0].Total_Ventas_Efectivo);
                var egresosefectivo = parseFloat(datos[0].Total_Compras_Efectivo) + parseFloat(datos[0].Total_Pagos_Efectivo) + parseFloat(datos[0].Total_Devoluciones) + parseFloat(datos[0].Total_Importes_Egresos) + parseFloat(datos[0].Total_Depositos) + parseFloat(datos[0].Total_Gastos_Efectivo);
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
                $("#spanTotalDepositosVenta").text(datos[0].Total_Depositos)

                //var ventas = parseFloat(datos[0].Total_Ventas_Efectivo) + parseFloat(datos[0].Total_Ventas_Deposito) + parseFloat(datos[0].Total_Ventas_Cheque) + parseFloat(datos[0].Total_Ventas_TransferenciaBancaria) + parseFloat(datos[0].Total_Ventas_TarjetaCreditoDebito) + parseFloat(datos[0].Total_Ventas_PagoOnline); 
                var ventas = parseFloat(datos[0].Total_Ventas_Efectivo) + parseFloat(datos[0].Total_Ventas_Cheque) + parseFloat(datos[0].Total_Ventas_TransferenciaBancaria) + parseFloat(datos[0].Total_Ventas_TarjetaCredito) + parseFloat(datos[0].Total_Ventas_TarjetaDebito); 
                var totalventas= (ventas - parseFloat(datos[0].Total_Devoluciones)) - parseFloat(datos[0].Total_Depositos);
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

                $("#spanTotalDepositos").text(datos[0].Total_Depositos);
                $("#spanTotalGastos").text(datos[0].Total_Gastos_Efectivo); //en efectivo

                $("#spanGastosEnEfectivo").text(datos[0].Total_Gastos_Efectivo);
                $("#spanGastosDeposito").text(datos[0].Total_Gastos_Deposito);
                $("#spanGastosCheque").text(datos[0].Total_Gastos_Cheque);
                $("#spanGastosTransferencia").text(datos[0].Total_Gastos_TransferenciaBancaria);
                $("#spanGastosTarjeta").text(datos[0].Total_Gastos_TarjetaCreditoDebito);
                $("#spanGastosTotal").text(datos[0].Total_Gastos);

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
        submitHandler: function(form, event) {
            event.preventDefault(); 

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

    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if(evt.key === "F2"){
            $("#CargarProductosModalVentas").trigger("click");
        }else if(evt.key === "F8"){
            $("#RealizarVenta").trigger("click");
        }

        //METODO PARA DESPLAZARSE CON LAS FLECHAS EN EL MODAL DE PRODUCTOS AGREGADOS A LA VENTA
        if(!$("#ModalVerProductosVenta").hasClass('show') && !$("#ModalPreciosProductoVenta").hasClass('show') && !$("#ModalDescuentoProducto").hasClass('show') && !$("#ModalVerPedidosVenta").hasClass('show') && !$("#ModalVerClientesVenta").hasClass('show') && !$("#ModalPresentacionesProducto").hasClass('show')){
            var fila = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').index();
            if($("#TablaProductosAgregadoVenta").children('tbody').children('tr').length > 0){
                var cantidadActual = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').children("td:eq(3)").find(".campoCantidadProducto").val();
                if(evt.key === "ArrowUp"){
                    $('#CodigoProductoVenta').blur();
                    if((fila - 1) >= 0){
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr').removeClass('activa');
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                    }
                }else if(evt.key === "ArrowDown"){
                    $('#CodigoProductoVenta').blur();
                    if($("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq('+(fila + 1)+')').length > 0){
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr').removeClass('activa');
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq('+(fila + 1)+')').addClass('activa');
                    }
                }else if(evt.key === "Delete"){
                    if (!$("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').hasClass("Promocion")) {
                        $('#CodigoProductoVenta').blur();
                        //BUSCAR SI EL PRODUCTO TIENE PROMOCION APLICADA PARA ELIMINARLA DE LA TABLA
                        var idProducto = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').attr("attrid")
                        var idPresentacion = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').attr("idpresentacion")
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').remove()
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[idProductoCombo='+idProducto+'][idPresentacionCombo='+idPresentacion+']').remove()
                        ////////////////////////////////////////////////////////////////////////////

                        //OBTENER ATRIBUTOS DE LA FILA ACTIVA
                        //var id = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').attr("attrid");
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').remove();
                       
                        if((fila - 1) >= 0){
                            $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                        }else{
                            $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq(0)').addClass('activa');
                        }
                        $('#CodigoProductoVenta').focus();
                        CalcularSubtotalVenta();
                    }
                }else if(evt.key === " "){
                    if(!$("#ModalVerProductosVenta").hasClass('show') && !$("#ModalPreciosProductoVenta").hasClass('show') && !$("#ModalDescuentoProducto").hasClass('show') && !$("#ModalVerPedidosVenta").hasClass('show') && !$("#ModalVerClientesVenta").hasClass('show') && !$("#ModalPresentacionesProducto").hasClass('show')){
                        $('#CodigoProductoVenta').blur();
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').children("td:eq(2)").find(".cambiarPrecio").trigger("click");
                    }
                }else if((event.altKey && evt.key === "a") || (event.altKey && evt.key === "A")){
                    if(!$("#ModalVerProductosVenta").hasClass('show') && !$("#ModalPreciosProductoVenta").hasClass('show') && !$("#ModalDescuentoProducto").hasClass('show') && !$("#ModalVerPedidosVenta").hasClass('show') && !$("#ModalVerClientesVenta").hasClass('show') && !$("#ModalPresentacionesProducto").hasClass('show')){
                        $('#CodigoProductoVenta').blur();
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').children("td:eq(5)").find(".AbrirDescuentoProducto").trigger("click");
                    }
                }else if(evt.key === "+"){
                    $('#CodigoProductoVenta').blur();
                    if (!$("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').hasClass("Promocion")) {
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidadActual) + 1);
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').children("td:eq(3)").find(".campoCantidadProducto").trigger("keyup");   
                    }
                    //$(".campoCantidadProducto").trigger("keyup");
                }else if(evt.key === "-"){
                    if (!$("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').hasClass("Promocion")) {
                        $('#CodigoProductoVenta').blur();
                        if ((cantidadActual - 1) <= 0) {
                            var idProducto = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').attr("attrid")
                            var idPresentacion = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').attr("idpresentacion")
                            var CantidadActual =  0;

                            var arregloProductos = [];

                            $("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
                                let fila = $(this);
                                if(!$(this).hasClass("Promocion")){
                                    let idProducto = fila.attr("attrid");
                                    let Presentacion = fila.attr("idpresentacion");
                                    let cantidad = fila.children("td:eq(3)").find(".campoCantidadProducto").val();
                                    arregloProductos.push([idProducto, Presentacion, cantidad]);
                                }   
                            });

                            ConsultarPromocionesProductos(idProducto, idPresentacion, CantidadActual, arregloProductos);

                            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').remove()
                            $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').remove();

                            if((fila - 1) >= 0){
                                $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                            }else{
                                $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq(0)').addClass('activa');
                            }
                            $('#CodigoProductoVenta').focus();
                            CalcularSubtotalVenta();
                        }else{
                            $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidadActual) - 1);
                            $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').children("td:eq(3)").find(".campoCantidadProducto").trigger("keyup");
                            //$(".campoCantidadProducto").trigger("keyup");
                        }
                    }
                }else if(evt.key === "Escape"){
                    $('#CodigoProductoVenta').focus();
                }              
            }
        }


        var filaPrecios = $("#TablaPreciosProductosVenta").children('tbody').children('tr.activa').index();
        if($("#TablaPreciosProductosVenta").children('tbody').children('tr').length > 0){
            if(evt.key === "ArrowUp"){
                $(".BuscadorTablaTablaPreciosProductosVenta").blur();
                if((filaPrecios - 1) >= 0){
                    $("#TablaPreciosProductosVenta").children('tbody').children('tr').removeClass('activa');
                    $("#TablaPreciosProductosVenta").children('tbody').children('tr:eq('+(filaPrecios - 1)+')').addClass('activa');
                    if ($("#TablaPreciosProductosVenta").children('tbody').children('tr.activa').attr("id") == "Nada") {
                        $("#PrecioPersonalizadoPrecios").focus();
                    }else{
                        $("#PrecioPersonalizadoPrecios").blur();
                    }
                }
            }else if(evt.key === "ArrowDown"){
                $(".BuscadorTablaTablaPreciosProductosVenta").blur();
                if($("#TablaPreciosProductosVenta").children('tbody').children('tr:eq('+(filaPrecios + 1)+')').length > 0){
                    $("#TablaPreciosProductosVenta").children('tbody').children('tr').removeClass('activa');
                    $("#TablaPreciosProductosVenta").children('tbody').children('tr:eq('+(filaPrecios + 1)+')').addClass('activa');
                    if ($("#TablaPreciosProductosVenta").children('tbody').children('tr.activa').attr("id") == "Nada") {
                        $("#PrecioPersonalizadoPrecios").focus();
                    }else{
                        $("#PrecioPersonalizadoPrecios").blur();
                    }
                }
            }else if(evt.key === "Enter"){
                if($("#ModalPreciosProductoVenta").hasClass('show')){
                    if ($("#TablaPreciosProductosVenta").children('tbody').children('tr.activa').attr("id") == "Nada") {
                        $("#TablaPreciosProductosVenta").children('tbody').children('tr.activa').children("td:eq(1)").find(".SeleccionarPrecioPersonalizado").trigger("click")
                    }else{
                        $('#CodigoProductoVenta').blur();
                        $("#TablaPreciosProductosVenta").children('tbody').children('tr.activa').trigger("click");
                    }
                }
            }            
        }

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
        submitHandler: function(form, event) {
            event.preventDefault(); 
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
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(5)").find("#SpanTextoDescuento").attr("idtoken", datos[2]);
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(5)").find("#SpanTextoDescuento").attr("codigo", $("#TokenDescuento").val());
                        $("#ModalDescuentoProducto").modal("hide");
                        var descuento = parseFloat(datos[1]);
                        var subtotalActual = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(6)").find(".totalColumna").attr("subtotal");
                        var total = parseFloat(subtotalActual) - descuento;
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(6)").find(".totalColumna").attr("subtotal", total);
                        //$(".campoCantidadProducto").trigger("change");
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
                        moneda();
                    } 
                }else{
                    $("#divErrorToken").slideDown();
                    setTimeout(function(){
                        $("#divErrorToken").slideUp();
                    }, 2000);
                }
            })
            .fail(function() {
                console.log("Error ajax");
            }); 
        }
    }); 

    // $("#FormRealizarVenta").off().validate({
    //     submitHandler: function(form, event) {
    //         event.preventDefault(); 
    //         let efectivo = $("#PagoEfectivo").val() || 0;  
    //         let transferencia = $("#PagoTransferencia").val() || 0;  
    //         let cheque = $("#PagoCheque").val() || 0;  
    //         let tcredito = $("#PagoTCredito").val() || 0;  
    //         let tdebito = $("#PagoTDebito").val() || 0;  
    //         let pagado = parseFloat(efectivo) + parseFloat(transferencia) + parseFloat(cheque) + parseFloat(tcredito) + parseFloat(tdebito);
    //         let total = $("#RealizarVenta").attr("total");
    //         let IDVenta = '';
    //         if ($("#CargarModalModificarVentas").attr("attrid") == undefined) {
    //             IDVenta = '';
    //         }else{
    //             IDVenta = $("#CargarModalModificarVentas").attr("attrid");
    //         }
            
    //         if ($("#TablaProductosAgregadoVenta tbody tr").length == 0) {
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'No se puede realizar una venta sin productos',
    //                 timer: 1000
    //             });
    //         }else if ($("#PagoEfectivo").val() == "" && $("#PagoTransferencia").val() == "" && $("#PagoCheque").val() == "" && $("#PagoTCredito").val() == "" && $("#PagoTDebito").val() == "") {
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Ingresa al menos un metodo de pago',
    //                 timer: 1000
    //             });
    //         }else if (parseFloat(pagado) < parseFloat(total)) {
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Los pagos no cubren el total de la venta',
    //                 timer: 1000
    //             });
    //         }else{
    //             let idDireccion = 0;
    //             let idsucursal = $("#SucursalVenta").attr("attrid");
    //             let cliente;
    //             if ($("#CargarClientesModalVentas").attr("attrid") == "") {
    //                 cliente = 1;
    //             }else{
    //                 cliente = $("#CargarClientesModalVentas").attr("attrid");
    //             }

    //             if ($("#CargarClientesModalVentas").attr("attrid") != "") {
    //                 if ($("#CargarClientesModalDirecciones").attr("iddireccion") != "") {
    //                     idDireccion = $("#CargarClientesModalDirecciones").attr("iddireccion");
    //                 }
    //             }
    //             const searchRegExp = new RegExp(',', 'g');
    //             let productos = new Array();
    //             let sumadescuento = 0;
    //             let totalventa = $("#RealizarVenta").attr("totalventa");
    //             let totalimporte = $("#RealizarVenta").attr("totalimportes");
    //             //OBTENER EL TIPO DE PAGO 
    //             let tipopago = "";
    //             if ($("#PagoEfectivo").val() > 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
    //                 tipopago = "Efectivo";
    //             }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() > 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
    //                 tipopago = "Transferencia";
    //             }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() > 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
    //                 tipopago = "Cheque";
    //             }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() > 0 && $("#PagoTDebito").val() == 0) {
    //                 tipopago = "Tarjeta de crédito";
    //             }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() > 0) {
    //                 tipopago = "Tarjeta de débito";
    //             }else{
    //                 tipopago = "Mixto";
    //             }
    //             let pago = $("#ImportePagadoVenta").val();
    //             let cambio = $("#verCambio").text().replace("$","").replace(searchRegExp, '');
    //             //CAMPOS DE PAGO MIXTO//

    //             let pagoEfectivo = $("#PagoEfectivo").val();
    //             let pagoTransferencia = $("#PagoTransferencia").val();
    //             let pagoCheque = $("#PagoCheque").val();
    //             let pagoTCredito = $("#PagoTCredito").val();
    //             let pagoTDebito = $("#PagoTDebito").val();

    //             ////////////////////////
    //             $("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
    //                 let fila = $(this);
    //                 let precio = 0;
    //                 let cantidad = 0;
    //                 let cantidadImportes = 0;
    //                 let precioImporte = 0;
    //                 let descuento = 0;
    //                 let totalproducto = 0;
    //                 let idtoken = 0;
    //                 let idProducto = fila.attr("attrid");
    //                 let Presentacion = fila.attr("idpresentacion");
    //                 cantidad = fila.children("td:eq(3)").find(".campoCantidadProducto").val();
    //                 cantidadImportes = fila.children("td:eq(3)").find(".campoCantidadImporte").val();
    //                 precio = fila.children("td:eq(2)").find(".cambiarPrecio").attr("precio");
    //                 precioImporte = fila.children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
    //                 descuento = fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual");
    //                 let codigoDescuento = fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("codigo");
    //                 idtoken = fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("idtoken");
    //                 totalproducto = fila.children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
    //                 if (fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual") != "" && fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual") > 0) {
    //                     sumadescuento += parseFloat(fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual"));
    //                 }
    //                 let Cobrarimporte = fila.children("td:eq(7)").find("#CobrarImporteProducto").prop("checked");
    //                 if (Cobrarimporte == true) {
    //                     Cobrarimporte = 1;
    //                 }else{
    //                     Cobrarimporte = 0;
    //                 }
    //                 let impuestos = "";
    //                 fila.children("td:eq(4)").find(".impuesto").each(function(index, el) {
    //                     let impuesto = fila.find(".seleccionarImpuesto");
    //                     if (impuesto.prop("checked") == true) {
    //                         impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
    //                     }
    //                 });
    //                 productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto, cantidadImportes, precioImporte, codigoDescuento, idtoken]);
    //             });

    //             let contarVenta = 0;
    //             if ($("#ContarVenta").prop("checked")) {
    //                 contarVenta = 1;
    //             }

    //             let dataHacerVenta = "metodo=insertar&accion=hacerventa&tipo=RealizarVenta&idsucursal="+idsucursal+"&cliente="+cliente+"&idDireccion="+idDireccion+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&TipoPago="+tipopago+"&Importe="+pago+"&totalventa="+totalventa+"&totalfinalimporte="+totalimporte+"&contarVenta="+contarVenta+"&pagoEfectivo="+pagoEfectivo+"&pagoTransferencia="+pagoTransferencia+"&pagoCheque="+pagoCheque+"&pagoTCredito="+pagoTCredito+"&pagoTDebito="+pagoTDebito+"&cambio="+cambio+"&IDVenta="+IDVenta;
    //             console.log(dataHacerVenta);
    //             $.ajax({
    //                 url: 'index.php',
    //                 type: 'POST',
    //                 data: dataHacerVenta,
    //                 beforeSend: function() {
    //                     $("#carga").show();
    //                 }
    //             })
    //             .done(function(res) {
    //                 var datos = res.split("~");
    //                 if ($.trim(datos[0]) == "Correcto") {
    //                     if ($("#CargaPedidosModalVentas").attr("attrid") != "" && $("#CargaPedidosModalVentas").attr("attrid") != undefined) {
    //                         console.log("eS UN PEDIDO")
    //                     }
    //                     console.log($("#GuardarVenta").attr("idpedido"))
    //                     if ($("#GuardarVenta").attr("idpedido") != "" && $("#GuardarVenta").attr("idpedido") != undefined) {
    //                         $("#ModalRealizarVenta").modal("hide");
    //                         Swal.fire({
    //                             title: '¿Quieres eliminar el pedido con el folio '+$("#GuardarVenta").attr("foliopedido")+'?',
    //                             icon: 'warning',
    //                             showCancelButton: true,
    //                             confirmButtonColor: '#3085d6',
    //                             cancelButtonColor: '#d33',
    //                             cancelButtonText: '¡No, continuar!',
    //                             confirmButtonText: '¡Si, eliminar!'
    //                         }).then((result) => {
    //                             if (result.value) {
    //                                 var data = "metodo=eliminar&accion=hacerventa&IDPedido="+$("#GuardarVenta").attr("idpedido");
    //                                 $.ajax({
    //                                     url: 'index.php',
    //                                     type: 'POST',
    //                                     data: data,
    //                                 })
    //                                 .done(function(res) {
    //                                     if ($.trim(res) == "Correcto") {
    //                                         Swal.fire({
    //                                             icon: 'success',
    //                                             title: 'Venta realizada correctamente',
    //                                             timer: 700
    //                                         });
    //                                         $("#cargarHacerVenta").trigger("click");
    //                                         var idVenta = datos[1];
    //                                         var altura=50;
    //                                         var anchura=310;

    //                                         var y= parseInt((window.screen.height/2)-(altura/2));
    //                                         var x= parseInt((window.screen.width/2)-(anchura/2));
    //                                         window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
    //                                         if ($("#GuardarVenta").attr("tipo") == "facturar") {
    //                                             facturarVenta(idVenta);
    //                                         }
    //                                     }else{
    //                                         Swal.fire({
    //                                             icon: 'error',
    //                                             title: 'Oops...',
    //                                             text: 'Error inesperado al eliminar el pedido.'
    //                                         });
    //                                         console.log($.trim(res));
    //                                     }
    //                                 })
    //                                 .fail(function() {
    //                                     console.log("Error ajax");
    //                                 });
    //                             }else{
    //                                 Swal.fire({
    //                                     icon: 'success',
    //                                     title: 'Venta realizada correctamente',
    //                                     timer: 700
    //                                 });
    //                                 $("#cargarHacerVenta").trigger("click");
    //                                 var idVenta = datos[1];
    //                                 var altura=50;
    //                                 var anchura=310;

    //                                 var y= parseInt((window.screen.height/2)-(altura/2));
    //                                 var x= parseInt((window.screen.width/2)-(anchura/2));
    //                                 window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
    //                                    if ($("#GuardarVenta").attr("tipo") == "facturar") {
    //                                     facturarVenta(idVenta);
    //                                 }
    //                             }
    //                         });
    //                     }else{
    //                         $("#ModalRealizarVenta").modal("hide");
    //                         Swal.fire({
    //                             icon: 'success',
    //                             title: 'Venta realizada correctamente',
    //                             timer: 700
    //                         });
    //                         $("#cargarHacerVenta").trigger("click");
    //                         var idVenta = datos[1];
    //                         var altura=50;
    //                         var anchura=310;

    //                         var y= parseInt((window.screen.height/2)-(altura/2));
    //                         var x= parseInt((window.screen.width/2)-(anchura/2));
    //                         window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
    //                         if ($("#GuardarVenta").attr("tipo") == "facturar") {
    //                             facturarVenta(idVenta);
    //                         }
    //                     }                
    //                 }else if($.trim(datos[0]) == "Duplicada"){
    //                     console.log(res);
    //                 }else{
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'Oops...',
    //                         text: 'Error al guardar venta.'
    //                     });
    //                     console.log(res);
    //                 }    
    //             })
    //             .fail(function() {
    //                 console.log("Error ajax");
    //             })
    //             .always(function() {
    //                 $("#carga").hide();
    //             });      
    //             return false;
    //         } 
    //     }
    // }); 





    /*

    
    */ 
}


jQuery(document).ready(function($) {

    $(document).on('submit', '#FormRealizarVenta', function(event) {
        event.preventDefault();
        var botonGuardarVenta = $("#GuardarVenta");
        let efectivo = $("#PagoEfectivo").val() || 0;  
        let transferencia = $("#PagoTransferencia").val() || 0;  
        let cheque = $("#PagoCheque").val() || 0;  
        let tcredito = $("#PagoTCredito").val() || 0;  
        let tdebito = $("#PagoTDebito").val() || 0;  
        let pagado = parseFloat(efectivo) + parseFloat(transferencia) + parseFloat(cheque) + parseFloat(tcredito) + parseFloat(tdebito);
        let total = $("#RealizarVenta").attr("total");
        let IDVenta = '';
        if ($("#CargarModalModificarVentas").attr("attrid") == undefined) {
            IDVenta = '';
        }else{
            IDVenta = $("#CargarModalModificarVentas").attr("attrid");
        }
        
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
            let idDireccion = 0;
            let idsucursal = $("#SucursalVenta").attr("attrid");
            let cliente;
            if ($("#CargarClientesModalVentas").attr("attrid") == "") {
                cliente = 1;
            }else{
                cliente = $("#CargarClientesModalVentas").attr("attrid");
            }

            if ($("#CargarClientesModalVentas").attr("attrid") != "") {
                if ($("#CargarClientesModalDirecciones").attr("iddireccion") != "") {
                    idDireccion = $("#CargarClientesModalDirecciones").attr("iddireccion");
                }
            }
            const searchRegExp = new RegExp(',', 'g');
            let productos = new Array();
            let sumadescuento = 0;
            let totalventa = $("#RealizarVenta").attr("totalventa");
            let totalimporte = $("#RealizarVenta").attr("totalimportes");
            //OBTENER EL TIPO DE PAGO 
            let tipopago = "";
            if ($("#PagoEfectivo").val() > 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Efectivo";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() > 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Transferencia";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() > 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Cheque";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() > 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Tarjeta de crédito";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() > 0) {
                tipopago = "Tarjeta de débito";
            }else{
                tipopago = "Mixto";
            }
            let pago = $("#ImportePagadoVenta").val();
            let cambio = $("#verCambio").text().replace("$","").replace(searchRegExp, '');
            //CAMPOS DE PAGO MIXTO//

            let pagoEfectivo = $("#PagoEfectivo").val();
            let pagoTransferencia = $("#PagoTransferencia").val();
            let pagoCheque = $("#PagoCheque").val();
            let pagoTCredito = $("#PagoTCredito").val();
            let pagoTDebito = $("#PagoTDebito").val();

            ////////////////////////
            $("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
                let fila = $(this);
                let precio = 0;
                let cantidad = 0;
                let cantidadImportes = 0;
                let precioImporte = 0;
                let descuento = 0;
                let totalproducto = 0;
                let idtoken = 0;
                let idProducto = fila.attr("attrid");
                let Presentacion = fila.attr("idpresentacion");
                cantidad = fila.children("td:eq(3)").find(".campoCantidadProducto").val();
                cantidadImportes = fila.children("td:eq(3)").find(".campoCantidadImporte").val();
                precio = fila.children("td:eq(2)").find(".cambiarPrecio").attr("precio");
                precioImporte = fila.children("td:eq(2)").find(".campoPrecioImporte").text().replace("$","").replace(searchRegExp, '');
                descuento = fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual");
                let codigoDescuento = fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("codigo");
                idtoken = fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("idtoken");
                totalproducto = fila.children("td:eq(6)").find(".totalColumna").text().replace("$","").replace(searchRegExp, '');
                if (fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual") != "" && fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual") > 0) {
                    sumadescuento += parseFloat(fila.children("td:eq(5)").find("#SpanTextoDescuento").attr("actual"));
                }
                let Cobrarimporte = fila.children("td:eq(7)").find("#CobrarImporteProducto").prop("checked");
                if (Cobrarimporte == true) {
                    Cobrarimporte = 1;
                }else{
                    Cobrarimporte = 0;
                }
                let impuestos = "";
                fila.children("td:eq(4)").find(".impuesto").each(function(index, el) {
                    let impuesto = fila.find(".seleccionarImpuesto");
                    if (impuesto.prop("checked") == true) {
                        impuestos += impuesto.attr("attrid")+","+impuesto.attr("nombre")+","+impuesto.attr("porcentaje")+","+impuesto.attr("clavecfdi")+","+impuesto.attr("tipofactor")+","+impuesto.attr("clase")+"~";
                    }
                });

                //TOMAR LOS DATOS SI EL PRODUCTO ESTA EN PROMOCION
                let TipoPromocion = $(this).attr("tipopromocion") || '';
                let idPromocion = $(this).attr("IDPromocion") || 0;
                let idProductoRegalar = $(this).attr("idProductoRegalo") || 0;
                let idPresentacionRegalar = $(this).attr("idPresentacionRegalo")|| 0;
                let idProductoCombo = $(this).attr("idProductoCombo") || 0;
                let idPresentacionCombo = $(this).attr("idPresentacionCombo") || 0;

                productos.push([idProducto, Presentacion, cantidad, precio, descuento, impuestos, totalproducto, cantidadImportes, precioImporte, codigoDescuento, idtoken, TipoPromocion, idPromocion, idProductoCombo, idPresentacionCombo, idProductoRegalar, idPresentacionRegalar]);
            });
            
            let contarVenta = 0;
            if ($("#ContarVenta").prop("checked")) {
                contarVenta = 1;
            }

            let dataHacerVenta = "metodo=insertar&accion=hacerventa&tipo=RealizarVenta&idsucursal="+idsucursal+"&cliente="+cliente+"&idDireccion="+idDireccion+"&productos="+JSON.stringify(productos)+"&sumadescuento="+sumadescuento+"&total="+total+"&TipoPago="+tipopago+"&Importe="+pago+"&totalventa="+totalventa+"&totalfinalimporte="+totalimporte+"&contarVenta="+contarVenta+"&pagoEfectivo="+pagoEfectivo+"&pagoTransferencia="+pagoTransferencia+"&pagoCheque="+pagoCheque+"&pagoTCredito="+pagoTCredito+"&pagoTDebito="+pagoTDebito+"&cambio="+cambio+"&IDVenta="+IDVenta;
            console.log(dataHacerVenta);
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: dataHacerVenta,
                beforeSend: function() {
                    $("#carga").show();
                    botonGuardarVenta.prop("disabled", true)
                }
            })
            .done(function(res) {
                var datos = res.split("~");
                if ($.trim(datos[0]) == "Correcto") {
                    if ($("#CargaPedidosModalVentas").attr("attrid") != "" && $("#CargaPedidosModalVentas").attr("attrid") != undefined) {
                        // console.log("eS UN PEDIDO")
                    }
                    if ($("#GuardarVenta").attr("idpedido") != "" && $("#GuardarVenta").attr("idpedido") != undefined) {
                        $("#ModalRealizarVenta").modal("hide");
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
                                            timer: 700
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
                                    timer: 700
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
                        $("#ModalRealizarVenta").modal("hide");
                        Swal.fire({
                            icon: 'success',
                            title: 'Venta realizada correctamente',
                            timer: 700
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
                }else if($.trim(datos[0]) == "Duplicada"){
                    console.log(res);
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
                botonGuardarVenta.prop("disabled", false)
            });      
            return false;
        }
    });

    $(document).on('hidden.bs.modal', '#ModalVerProductosVenta',function(){
       $("#CodigoProductoVenta").focus();
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

    /*$(document).on('hidden.bs.modal', '#ModalPreciosProductoVenta',function(){
       $("#CodigoProductoVenta").focus();
    });*/

    $(document).on('hidden.bs.modal', '#ModalPresentacionesProducto',function(){
       $("#CodigoProductoVenta").focus();
    });

    $(document).on('hidden.bs.modal', '#ModalRealizarVenta',function(){
       $("#CodigoProductoVenta").focus();
    });

    $(document).on('shown.bs.modal', '#ModalVerProductosVenta',function(){
        //$(".BuscadorTablaVentaTablaProductos").focus();
        $(".BuscadorTablaVentaTablaProductos").val("");
        $(".BuscadorTablaVentaTablaProductos").trigger("keyup");
        $(".BuscadorTablaVentaTablaProductos").focus();
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
        $("#CodigoProductoVenta").attr("disabled", true);
		$("#ModalVerClientesVenta").modal("show");
        $("#CodigoProductoVenta").attr("disabled", false);
        setTimeout(function(){
            $(".BuscadorTablaTablaClienteVenta").val("")
            $(".BuscadorTablaTablaClienteVenta").trigger("keyup")
            $(".BuscadorTablaTablaClienteVenta").focus()

        }, 500)
	});

	$(document).on('click', '#CargarClientesModalDirecciones', function() {
		var id = $("#CargarClientesModalVentas").attr("attrid");
		TablaDireccionesCliente(id);
        $("#CodigoProductoVenta").attr("disabled", true);
		$("#ModalVerDireccionesCliente").modal("show");
        $("#CodigoProductoVenta").attr("disabled", false);
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
            $("#CargarClientesModalDirecciones").html('<i class="fas fa-map-marker"></i> Dirección');
			$("#CargarClientesModalDirecciones").attr("iddireccion", "");
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
        $("#CodigoProductoVenta").attr("disabled", true);//HACER ESTO CON LOS MODALES QUE CARGUEN TABLAS
		VentaTablaProductos();
        $("#CodigoProductoVenta").attr("disabled", false);//HACER ESTO CON LOS MODALES QUE CARGUEN TABLAS
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
				  timer: 500
				});
            }else{       
            	var datos = JSON.parse($.trim(res));
                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr').removeClass('activa');
                if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').length > 0){                 
                	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
                 	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + 1);
                    $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').addClass("activa");
                }else{
                	var campoImportes = ""; var precioimporte =""; var campoTotalImporte = "";
                	if (datos.ImportePresentacion > 0 || datos.ImporteGeneral > 0) {
                        campoImportes = `<span>Importes</span><input type='number' value='0' min='0' step='any' class='form-control form-control-sm campoCantidadImporte' onfocusout="$('#CodigoProductoVenta').focus()">`;
                		if (datos.ImportePresentacion > 0) {
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImportePresentacion+"</span>";
	                	}else{
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImporteGeneral+"</span>";
	                	}
                        campoTotalImporte = `Importes: <br><span class='totalColumnaImporte dinero'>0</span>`;
                	}else{
                        campoTotalImporte = `<span class="d-none">Importes: <br><span class='totalColumnaImporte dinero'>0</span></span>`;
                		campoImportes = "";
                	}

                    var precioFinal = datos.Precio_General;
                    if (datos.Precio_Bruto != "" && datos.Precio_Bruto > 0) {
                        precioFinal = datos.Precio_Bruto;
                    } 

                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr class="activa" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`"  importegeneral="`+datos.ImporteGeneral+`" importepresentacion="`+datos.ImportePresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br><button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Presentacion+`</button></td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+precioFinal+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+precioFinal+`</button>`+precioimporte+`</td>
	                        <td><span>Productos</span><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto CampoUnico`+datos.ID_Producto+datos.IDPresentacion+`' onfocusout="$('#CodigoProductoVenta').focus()">`+campoImportes+`</td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
                                <button class="btn btn-sm btn-primary AbrirDescuentoProducto" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`"><span id="SpanTextoDescuento" class="dinero" codigo actual>$0</span></button>
		                    </td>
	                        <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>`+campoTotalImporte+`</td>
	                        <td>
	                        	<button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button>
	                        </td>
	                    </tr>`);
                	//$(".campoCantidadProducto").trigger("change");
                    //CalcularSubtotalVenta();
                }
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
                //$("#CodigoProductoVenta").focus();
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

    	/*var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+codigo+"&sucursal="+$("#SucursalVenta").attr("attrid")+"&presentacion="+presentacion;
        
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
            //$("#CodigoProductoVenta").val("");

            if($.trim(res) == "No encontrado"){
                Swal.fire({
				  icon: 'error',
				  title: 'Producto no encontrado o sin existencia',
				  timer: 1200
				})
            }else{*/       
            	//var datos = JSON.parse($.trim(res));
            	//console.log(datos);

                var datos = {
                    ID_Producto: $(this).attr('id'),
                    Codigo: $(this).children('td:eq(0)').children('b').text(),
                    Descripcion: $(this).children('td:eq(0)').children('span:eq(0)').text(),
                    Presentacion: $(this).children('td:eq(1)').children('span:eq(1)').text(),
                    IDPresentacion: $(this).children('td:eq(1)').children('span:eq(0)').text(),
                    Precio_General: $(this).children('td:eq(3)').text(),
                    Precio_Bruto: $(this).children('td:eq(4)').text(),
                    Existencia: $(this).children('td:eq(5)').text(),
                    ImporteGeneral: $(this).children('td:eq(0)').children('span:eq(1)').text(),
                    ImportePresentacion: $(this).children('td:eq(1)').children('span:eq(2)').text(),
                    Impuestos: $(this).children('td:eq(5)').children('span:eq(0)').html()
                };

                console.log(datos);

                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                $("#TablaProductosAgregadoVenta").children('tbody').children('tr').removeClass('activa');

                if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').length > 0){                 
                	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
                 	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + 1);
                	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').addClass("activa");
                    //$(".campoCantidadProducto").trigger("change");
                }else{
                	var campoImportes = ""; var precioimporte = ""; var campoTotalImporte = "";
                	if (datos.ImportePresentacion > 0 || datos.ImporteGeneral > 0) {
                        campoImportes = `<span>Importes</span><input type='number' value='0' min='0' step='any' class='form-control form-control-sm campoCantidadImporte' onfocusout="$('#CodigoProductoVenta').focus()">`;
                		//campoImportes = "<span>Importes</span><input type='number' value='1' min='0' step='any' class='form-control form-control-sm campoCantidadImporte'>";
                		if (datos.ImportePresentacion > 0) {
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImportePresentacion+"</span>";
	                	}else{
	                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.ImporteGeneral+"</span>";
	                	}
                        campoTotalImporte = `Importes: <br><span class='totalColumnaImporte dinero'>0</span>`;
                	}else{
                        campoTotalImporte = `<span class="d-none">Importes: <br><span class='totalColumnaImporte dinero'>0</span></span>`; 
                		campoImportes = "";
                	}

                    var precioVenta = datos.Precio_General;
                    if (datos.Precio_Bruto != "" && datos.Precio_Bruto > 0) {
                        precioVenta = datos.Precio_Bruto;
                    }
                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr class="activa" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`" importegeneral="`+datos.ImporteGeneral+`" importepresentacion="`+datos.ImportePresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br> <button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Presentacion+`</button></td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+precioVenta+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+precioVenta+`</button>`+precioimporte+`</td>
	                        <td><span>Productos</span><input type='number' value='1' min='1' step='any' class='form-control form-control-sm campoCantidadProducto CampoUnico`+datos.ID_Producto+datos.IDPresentacion+`' onfocusout="$('#CodigoProductoVenta').focus()">`+campoImportes+`</td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
                                <button class="btn btn-sm btn-primary AbrirDescuentoProducto" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`"><span id="SpanTextoDescuento" class="dinero" codigo actual>$0</span></button>
	                        </td>
	                        <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>`+campoTotalImporte+`</td>
	                        <td>
	                        	<button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
	                    </tr>`);
                	//$(".campoCantidadProducto").trigger("change");
                }

                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
                moneda();

                $("#ModalVerProductosVenta").modal("hide");
                
                //$("#CodigoProductoVenta").focus();
            /*}
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        });*/
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
        //console.log(parseFloat(importe));
		$(this).parent().parent().children("td:eq(6)").find(".totalColumnaImporte").text(parseFloat(total) || 0);
        /*if (parseFloat(importe) <= 0) {
            $(this).parent().parent().children("td:eq(6)").find(".spanMostrarImportes").remove()
        }*/
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
                var porcentaje = (parseFloat(impuesto.attr("porcentaje")) / 100);
                if ((impuesto.attr("nombre") == "IVA" || impuesto.attr("nombre") == "IEPS") && impuesto.attr("clase") == "Trasladado") {
                    totalImpuestos += parseFloat(total) * parseFloat(porcentaje);
                }
            }
        });
            
        var totalfinal = parseFloat(total) + parseFloat(totalImpuestos);
        $(this).parent().find(".campoCantidadImporte").trigger("keyup");

        $(this).parent().parent().children("td:eq(6)").find(".totalColumna").text(totalfinal);
        $(this).parent().parent().children("td:eq(6)").find(".totalColumna").attr("subtotal", total);

        var idProductoActual =  $(this).parent().parent().attr("attrid");
        var idPresentacionActual = $(this).parent().parent().attr("idpresentacion");
        var idCantidadActual =  cantidad;

        var arregloProductos = [];

        $("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
            let fila = $(this);
            if(!$(this).hasClass("Promocion")){
                let idProducto = fila.attr("attrid");
                let Presentacion = fila.attr("idpresentacion");
                let cantidad = fila.children("td:eq(3)").find(".campoCantidadProducto").val();
                arregloProductos.push([idProducto, Presentacion, cantidad]);
            }   
        });

        ConsultarPromocionesProductos(idProductoActual, idPresentacionActual, idCantidadActual, arregloProductos);

        CalcularSubtotalVenta();
    });

    function roundNumber(num, scale) {
      if(!("" + num).includes("e")) {
        return +(Math.round(num + "e+" + scale)  + "e-" + scale);
      } else {
        var arr = ("" + num).split("e");
        var sig = ""
        if(+arr[1] + scale > 0) {
          sig = "+";
        }
        return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
      }
    }

	/*$(document).on('click', '.seleccionarImpuesto', function() {
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
	});*/

	$(document).on('click', '.eliminarFila', function() {
        //BUSCAR SI EL PRODUCTO TIENE PROMOCION APLICADA PARA ELIMINARLA DE LA TABLA
        /*
        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idProducto+'][idPresentacion='+idPresentacion+']').remove()*/
        ////////////////////////////////////////////////////////////////////////////

       var idProducto = $(this).parent().parent().attr("attrid")
       var idPresentacion = $(this).parent().parent().attr("idpresentacion")
       var CantidadActual =  0;

       var arregloProductos = [];

       $("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
           let fila = $(this);
           if(!$(this).hasClass("Promocion")){
               let idProducto = fila.attr("attrid");
               let Presentacion = fila.attr("idpresentacion");
               let cantidad = fila.children("td:eq(3)").find(".campoCantidadProducto").val();
               arregloProductos.push([idProducto, Presentacion, cantidad]);
           }   
       });

       console.log(idProducto+", "+idPresentacion+", "+CantidadActual+", "+arregloProductos);
       

       ConsultarPromocionesProductos(idProducto, idPresentacion, CantidadActual, arregloProductos);

       var fila = $("#TablaProductosAgregadoVenta").children('tbody').children('tr.activa').index();
       if((fila - 1) >= 0){
           $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
       }else{
           $("#TablaProductosAgregadoVenta").children('tbody').children('tr:eq(0)').addClass('activa');
       }


       $(this).parent().parent().remove();
       $("#CodigoProductoVenta").focus();
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
        $("#CodigoProductoVenta").attr("disabled", true);
        $("#CodigoProductoVenta").attr("disabled", false);
		$("#ModalPreciosProductoVenta").modal("show");
        setTimeout(function(){
            $("#PrecioPersonalizadoPrecios").focus();
        }, 500);
	});

	$(document).on('click', '#TablaPreciosProductosVenta tbody tr', function() {
        if ($(this).attr("id") != "Nada") {
            var precio = $(this).children("td:eq(1)").text() || 0;
            var precioBruto = $(this).children("td:eq(2)").text() || 0;
            
            
            var producto = $(".BotonDatosPrecio").attr("producto");
            var presentacion = $(".BotonDatosPrecio").attr("presentacion");
            if (presentacion == null) {
                presentacion = 0;
            }
            console.log(producto+" y "+presentacion);
            var precioFinal = 0;
            if (precioBruto == 0) {
                precioFinal = precio;
            }else{
                precioFinal = precioBruto;
            }
            console.log($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').html());
            
            if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').length > 0){    
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").html(precioFinal);
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").attr("precio", precioFinal);
            }         
            //$(".campoCantidadProducto").trigger("change");
            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
            moneda();
            $("#ModalPreciosProductoVenta").modal("hide"); 
        }
	});

    $(document).on('click', '.SeleccionarPrecioPersonalizado', function() {
        if ($("#PrecioPersonalizadoPrecios").val() != "" && $("#PrecioPersonalizadoPrecios").val() > 0) {
            var precio = $("#PrecioPersonalizadoPrecios").val();
            var producto = $(".BotonDatosPrecio").attr("producto");
            var presentacion = $(".BotonDatosPrecio").attr("presentacion");
            if (presentacion == null) {
                presentacion = 0;
            }
            if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').length > 0){    
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").html(precio);
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(2)").find(".cambiarPrecio").attr("precio", precio);
            }         
            //$(".campoCantidadProducto").trigger("change");
            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+producto+'][idPresentacion='+presentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
            moneda();
            $("#ModalPreciosProductoVenta").modal("hide"); 
        }
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
                        if (!$(this).hasClass("Promocion")) {
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
                        }
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

                                    if (!$(this).hasClass("Promocion")) {
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
                                    }
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
				            }else{
                                $("#CodigoProductoVenta").focus();
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
                            if (!$(this).hasClass("Promocion")) {
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
                            }
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
				    }else{
                        $("#CodigoProductoVenta").focus();
                    }
				});	
			}
		}
	});

	$(document).on('click', '#RealizarVenta', function() {
		var total = $(this).attr("total");
        $("#PagoEfectivo").val("");
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
            setTimeout(function(){
                $("#PagoEfectivo").focus();
                $("#PagoEfectivo").val(total);
                $("#PagoEfectivo").trigger("keyup");
            }, 500);

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

	/*$(document).on('click', '#GuardarVenta', function() {
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
            //OBTENER EL TIPO DE PAGO 
            var tipopago = "";
            if ($("#PagoEfectivo").val() > 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Efectivo";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() > 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Transferencia";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() > 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Cheque";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() > 0 && $("#PagoTDebito").val() == 0) {
                tipopago = "Tarjeta de crédito";
            }else if ($("#PagoEfectivo").val() == 0 && $("#PagoTransferencia").val() == 0 && $("#PagoCheque").val() == 0 && $("#PagoTCredito").val() == 0 && $("#PagoTDebito").val() > 0) {
                tipopago = "Tarjeta de débito";
            }else{
                tipopago = "Mixto";
            }
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
                                            timer: 700
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
                                    timer: 700
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
                            timer: 700
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
	});*/

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

    $(document).on('click', '#CargarModalModificarVentas', function() {
        Swal.fire({
          title: "Ingresa la contraseña de administrador",
          input: "password",
          inputAttributes: {
            autocapitalize: "off"
          },
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          confirmButtonText: "Continuar",
          showLoaderOnConfirm: true,
          preConfirm: async (login) => {
            try {
                contra = login;
            } catch (error) {
              Swal.showValidationMessage(`
                Request failed: ${error}
              `);
            }
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarContraAdmin&contrasena="+contra;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    $("#CodigoProductoVenta").attr("disabled", true);
                    TablaVerVentasModificar();
                    $("#ModalVerVentasModificar").modal("show");
                    $("#CodigoProductoVenta").attr("disabled", false);
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'No tienes permiso de acceder a esta función'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });
          }
        });
    });

    $(document).on('click', '#CargaPedidosModalVentas', function() {
        $("#CodigoProductoVenta").attr("disabled", true);
    	TablaVerPedidosGuardados();
        $("#ModalVerPedidosVenta").modal("show");
        $("#CodigoProductoVenta").attr("disabled", false);
    });

    $(document).on('click', '#VerProductosPedido', function() {
		var id = $(this).attr("attrid");
		var folio = $(this).attr("attrid");
        $("#CodigoProductoVenta").attr("disabled", true);
		$("#ModalVerProductosReportePedido").modal("show");
        $("#CodigoProductoVenta").attr("disabled", false);
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

    $(document).on('click', '#VerProductosHacerVenta', function() {
        var id = $(this).attr("attrid");
        var folio = $(this).attr("attrid");
        $("#CodigoProductoVenta").attr("disabled", true);
        $("#ModalVerProductosReporteVentas").modal("show");
        $("#CodigoProductoVenta").attr("disabled", false);
        $("#FolioCargarVentasProductos").text(folio);

        var data = "metodo=detalles&accion=hacerventa&tipo=productosVentas&IDVenta="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#tbodyVerProductosVentas").html(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

	$(document).on('click', '.verImpuestosProductoPedido', function() {
		var id = $(this).attr("attrid");
		var nombre = $(this).attr("nombre");
        $("#CodigoProductoVenta").attr("disabled", true);
		$("#ModalVerImpuestosProductoPedido").modal("show");
        $("#CodigoProductoVenta").attr("disabled", false);
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

        var btn = $(this);
		var id = $(this).attr("attrid");
		var folio = $(this).attr("folio");
		var data = "metodo=detalles&accion=hacerventa&tipo=AgregarPedido&IDPedido="+id;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
            beforeSend: function() {
                progressBoton(btn);
            }
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

				var campoImportes = ""; var precioimporte = ""; var campoTotalImporte = "";
                if (datos.data.Productos.data[i].ImportePresentacion > 0 || datos.data.Productos.data[i].ImporteGeneral > 0) {
                	//campoImportes = `<span>Importes</span><input type='number' value='1' min='0' step='any' class='form-control form-control-sm campoCantidadImporte' onfocusout="$('#CodigoProductoVenta').focus()">`;
                    campoImportes = `<span>Importes</span><input type='number' value='0' min='0' max='"+datos.data.Productos.data[i].Cantidad+"' step='any' class='form-control form-control-sm campoCantidadImporte' onfocusout="$('#CodigoProductoVenta').focus()">`;
                	if (datos.data.Productos.data[i].ImportePresentacion > 0) {
                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.data.Productos.data[i].ImportePresentacion+"</span>";
                	}else{
                		precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.data.Productos.data[i].ImporteGeneral+"</span>";
                	}
                    campoTotalImporte = `Importes: <br><span class='totalColumnaImporte dinero'>0</span>`;
                }else{
                    campoTotalImporte = `<span class="d-none">Importes: <br><span class='totalColumnaImporte dinero'>0</span></span>`;
                	campoImportes = "";
                }

				$('#TablaProductosAgregadoVenta tbody').append(`
		        <tr attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`" importegeneral="`+datos.data.Productos.data[i].ImporteGeneral+`" importepresentacion="`+datos.data.Productos.data[i].ImportePresentacion+`">
		        	<td>`+datos.data.Productos.data[i].Codigo+`</td>
		            <td>`+datos.data.Productos.data[i].Descripcion+` <br> <button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`">`+datos.data.Productos.data[i].NombrePresentacion+`</button>
		            <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.data.Productos.data[i].Precio+`" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+datos.data.Productos.data[i].FK_Presentacion+`">`+datos.data.Productos.data[i].Precio+`</button>`+precioimporte+`</td>
		            <td><span>Productos</span><input type='number' value='`+datos.data.Productos.data[i].Cantidad+`' min='1' step='any' class='form-control form-control-sm campoCantidadProducto CampoUnico`+datos.data.Productos.data[i].FK_Producto+presentacion+`'>`+campoImportes+`</td>
		            <td>`+datos.data.Productos.data[i].Impuestos+`</td>
		            <td>
			        	<button class="btn btn-sm btn-primary AbrirDescuentoProducto" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`"><span id="SpanTextoDescuento" class="dinero" codigo actual>$0</span></button>
		            </td>
		            <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>`+campoTotalImporte+`</td>
		            <td>
						<button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
		        </tr>`);
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.data.Productos.data[i].FK_Producto+'][idPresentacion='+presentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
				//$(".campoCantidadProducto").trigger("keyup");
				$(".BotonLimpiarCliente").removeClass("oculto");
				$("#CargaPedidosModalVentas").text("Pedido: "+folio);
				$("#CargaPedidosModalVentas").attr("attrid", id);
				$("#CargaPedidosModalVentas").attr("folio", folio);
			}
		})
		.fail(function() {
			console.log("Error ajax");
		})
        .always(function() {
            unprogressBoton(btn);
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

    //MODIFICAR VENTAS
    $(document).on('click', '.SeleccionarVenta', function() {
        $("#CargarClientesModalVentas").html('<i class="fas fa-user"></i> Seleccionar cliente');
        $("#CargarClientesModalVentas").attr("attrid", "");
        $('#TablaProductosAgregadoVenta tbody').html("");
        $("#CargarModalModificarVentas").html('<i class="fas fa-arrow-down"></i> Ventas');
        $("#CargarModalModificarVentas").attr("attrid", "");
        $("#CargarModalModificarVentas").attr("folio", "");
        var id = $(this).attr("attrid");
        var folio = $(this).attr("folio");
        var data = "metodo=detalles&accion=hacerventa&tipo=AgregarVenta&IDVenta="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            console.log(res);
            var datos = JSON.parse($.trim(res));
            //$("#bQuitarLimpiarPedido").removeClass("oculto");
            if (datos.data.FK_Cliente != 1) {
                $("#CargarClientesModalDirecciones").html('<i class="fas fa-map-marker"></i> Dirección');
                $("#CargarClientesModalDirecciones").attr("iddireccion", "");
                $("#CargarClientesModalVentas").html("<span style='font-size: 10px;'>Cliente: "+datos.data.NombreCliente+"<br>RFC: "+datos.data.RFCCliente+"</span>");
                $("#CargarClientesModalVentas").attr("attrid", datos.data.FK_Cliente);
                $(".BotonLimpiarCliente").removeClass("oculto");
            }
            $("#ModalVerVentasModificar").modal("hide");
            $(".IconoSeleccionarVenta").removeClass("fa-retweet");
            $(".IconoSeleccionarVenta").addClass("fa-times");
            $(".BotonActualizarHacerVenta").removeClass("btn-light");
            $(".BotonActualizarHacerVenta").addClass("btn-danger");
            for (var i = 0; i < datos.data.Productos.data.length; i++) {
                var presentacion = null;

                if (datos.data.Productos.data[i].FK_Presentacion != 0) {
                    presentacion = datos.data.Productos.data[i].FK_Presentacion;
                }else{
                    presentacion = null;
                }

                var campoImportes = ""; var precioimporte = ""; var campoTotalImporte = "";
                if (datos.data.Productos.data[i].ImportePresentacion > 0 || datos.data.Productos.data[i].ImporteGeneral > 0) {
                    campoImportes = `<span>Importes</span><input type='number' value='0' min='0' max='"+datos.data.Productos.data[i].Cantidad+"' step='any' class='form-control form-control-sm campoCantidadImporte' onfocusout="$('#CodigoProductoVenta').focus()">`;
                    if (datos.data.Productos.data[i].ImportePresentacion > 0) {
                        precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.data.Productos.data[i].ImportePresentacion+"</span>";
                    }else{
                        precioimporte = "<br>Importe: <br><span class='dinero campoPrecioImporte'>"+datos.data.Productos.data[i].ImporteGeneral+"</span>";
                    }
                    campoTotalImporte = `Importes: <br><span class='totalColumnaImporte dinero'>0</span>`;
                }else{
                    campoTotalImporte = `<span class="d-none">Importes: <br><span class='totalColumnaImporte dinero'>0</span></span>`;
                    campoImportes = "";
                }    
                var campoDescuento = '';
                if (datos.data.Productos.data[i].Descuento > 0) {
                    campoDescuento = '<span id="SpanTextoDescuento" class="dinero" codigo="'+datos.data.Productos.data[i].CodigoToken+'" actual="'+datos.data.Productos.data[i].Descuento+'" idtoken="'+datos.data.Productos.data[i].ID_Token+'">'+datos.data.Productos.data[i].Descuento+'</span>';
                }else{
                    campoDescuento = '<span id="SpanTextoDescuento" class="dinero" codigo actual>$0</span>';
                }

                $('#TablaProductosAgregadoVenta tbody').append(`
                     <tr attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`"  importegeneral="`+datos.data.Productos.data[i].ImporteGeneral+`" importepresentacion="`+datos.data.Productos.data[i].ImportePresentacion+`">>
                        <td>`+datos.data.Productos.data[i].Codigo+`</td>
                        <td>`+datos.data.Productos.data[i].Descripcion+` <br><button class="btn btn-secondary btn-sm CambiarPresentacion" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`">`+datos.data.Productos.data[i].NombrePresentacion+`</button></td>
                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.data.Productos.data[i].Precio+`" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+datos.data.Productos.data[i].FK_Presentacion+`">`+datos.data.Productos.data[i].Precio+`</button>`+precioimporte+`</td>
                        <td><span>Productos</span><input type='number' value='`+datos.data.Productos.data[i].Cantidad+`' min='1' step='any' class='form-control form-control-sm campoCantidadProducto CampoUnico`+datos.data.Productos.data[i].FK_Producto+presentacion+`' onfocusout="$('#CodigoProductoVenta').focus()">`+campoImportes+`</td>
                        <td>`+datos.data.Productos.data[i].Impuestos+`</td>
                        <td>
                            <button class="btn btn-sm btn-primary AbrirDescuentoProducto" attrid="`+datos.data.Productos.data[i].FK_Producto+`" idPresentacion="`+presentacion+`">`+campoDescuento+`</button>
                        </td>
                        <td><span class='totalColumna dinero' style="font-weight: bold; font-size: 15px;"></span><br>`+campoTotalImporte+`</td>
                        <td>
                            <button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`);
                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.data.Productos.data[i].FK_Producto+'][idPresentacion='+presentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
                //$(".campoCantidadProducto").trigger("keyup");
                $("#CargarModalModificarVentas").text(folio);
                $("#CargarModalModificarVentas").attr("attrid", id);
                $("#CargarModalModificarVentas").attr("folio", folio);
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
    });

	$(document).on('click', '.CambiarPresentacion', function() {
		var id = $(this).attr("attrid");
		var idsucursal = $("#SucursalVenta").attr("attrid");
		var idpresentacion = $(this).attr("idPresentacion");
		TablaPresentacionesProducto(id, idsucursal, idpresentacion);
        $("#CodigoProductoVenta").attr("disabled", true);
		$("#ModalPresentacionesProducto").modal("show");
        $("#CodigoProductoVenta").attr("disabled", false);
	});

	$(document).on('click', '.SeleccionarPresentacion', function() {
        var idpresentacion = $(this).parent().parent().attr("id");
        var idproducto = $(this).attr("producto");
        var presentacionanterior = $(this).attr("presentacionactual");
        var nombre = $(this).attr("nombre");
        var abreviatura = $(this).attr("abreviatura");
        var precio = $(this).attr("precio");
        var precioBruto = $(this).attr("precioBruto");
        var importegeneral = $(this).attr("importegeneral");
        var importepresentacion = $(this).attr("importepresentacion");
        if (idpresentacion == 0) {
            idpresentacion = null;
        }
        $("#ModalPresentacionesProducto").modal("hide");

        var precioFinal = precio;
        if (precioBruto != undefined && precioBruto != "" && precioBruto > 0) {
            precioFinal = precioBruto;
        }
        
        if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').length > 0){                 
            var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').children("td:eq(3)").find(".campoCantidadProducto").val();
            var cantidadactual = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + parseFloat(cantidadactual));
            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').remove();
            //$(".campoCantidadProducto").trigger("change");
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

            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".cambiarPrecio").attr("precio", precioFinal);
            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(2)").find(".cambiarPrecio").text(precioFinal);
        }
        // console.log($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+presentacionanterior+']').html())
        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+idproducto+'][idPresentacion='+idpresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").trigger("change");
        //$(".campoCantidadProducto").trigger("change");
        moneda();

    });

    $(document).on('click', '.AbrirDescuentoProducto', function() {
        const searchRegExp = new RegExp(',', 'g');
        var codigoUsado = $(this).children("span").attr("codigo");
        var actual = $(this).children("span").text().replace('$', '').replace(searchRegExp, '');
        var idProducto = $(this).attr("attrid");
        var idPresentacion = $(this).attr("idPresentacion");
        $("#ModalDescuentoProducto").modal("show");
        setTimeout(function(){
            $("#TokenDescuento").focus();
        }, 400);
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

    $(document).on('change', '#FechaFinalModificarVentas', function() {
        TablaVerVentasModificar();
    });
    
    $(document).on('change', '#FechaInicioModificarVentas', function() {
        TablaVerVentasModificar();
    });



});

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
    $("#cantidadProductosSpanVenta").text($("#TablaProductosAgregadoVenta tbody tr").length);
    $('#CodigoProductoVenta').val("");
    moneda();
    CalcularTotal();
}

function CalcularCambio(){
    const searchRegExp = new RegExp(',', 'g');
    var efectivo = $("#PagoEfectivo").val() || 0;  
    var transferencia = $("#PagoTransferencia").val() || 0;  
    var cheque = $("#PagoCheque").val() || 0;  
    var tcredito = $("#PagoTCredito").val() || 0;  
    var tdebito = $("#PagoTDebito").val() || 0;  
    var pagado = parseFloat(efectivo) + parseFloat(transferencia) + parseFloat(cheque) + parseFloat(tcredito) + parseFloat(tdebito);
    $("#verCambio").html(pagado - parseFloat($("#TotalVentaFinal").text().replace('$', '').replace(searchRegExp, '')));
    moneda();
}

function TablaVerVentasModificar(){
   
	ajaxMyDatatable({
		"table": $("#TablaCargarVentas"), 
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
			"tipo": "CargarVentas",
            "fechaInicio": $("#FechaInicioModificarVentas").val(),
            "fechaFin": $("#FechaFinalModificarVentas").val(),
			"sucursal": $("#SucursalVenta").attr("attrid")
		}
	});
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
            "PrecioBruto",
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
    if (presentacion == "null") {
        presentacion = 0;
    }
	ajaxMyDatatable({
		"table": $("#TablaPreciosProductosVenta"), 
		"colums": [
			"Nombre",
			"Precio",
            "PrecioBruto",
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

function ConsultarPromocionesProductos(IDProducto, IDPresentacion, Cantidad, ArregloProductos){
    //QUITAMOS EL REGISTRO DEL PRODUCTO ACTUAL PARA SOLO TENER LOS DEMAS PRODUCTOS
    var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarPromocionesProductos&ProductosVenta="+JSON.stringify(ArregloProductos)+"&sucursal="+$("#SucursalVenta").attr("attrid")+"&IDProducto="+IDProducto+"&IDPresentacion="+IDPresentacion+"&Cantidad="+Cantidad;
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
    })
    .done(function(res) {
        if (res != null) {
            var datos = JSON.parse(res);
            for (let i = 0; i < datos.length; i++) {
                console.log(datos[i]);
                
                if (datos[i] != null && datos[i].Eliminar == "No") {
                    var codigo = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+IDProducto+'][idPresentacion='+IDPresentacion+']').children("td:eq(0)").text();
                    if(datos[i].TipoPromocion == "CantidadRegalo"){
                        if ($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+IDProducto+'][idPresentacion='+IDPresentacion+'][tipoPromocion=CantidadRegalo]').length > 0) {
                             $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+IDProducto+'][idPresentacion='+IDPresentacion+'][tipoPromocion=CantidadRegalo]').children("td:eq(3)").find(".campoCantidadProducto").val(datos[i].CantidadRegalo)
                        }else{
                            $('#TablaProductosAgregadoVenta tbody').append(`
                                <tr class="bg-dark Promocion" style='color: white;' IDPromocion="`+datos[i].IDPromocion+`" tipoPromocion="CantidadRegalo" attrid="`+IDProducto+`" idPresentacion="`+IDPresentacion+`"  importegeneral="`+0+`" importepresentacion="`+0+`" idProductoRegalo="`+IDProducto+`" idPresentacionRegalo="`+datos[i].IDPresentacionRegalo+`">
                                    <td>`+codigo+`</td>
                                    <td>`+datos[i].NombreProductoRegalo+`<br>`+datos[i].NombrePresentacionPromocion+`</td>
                                    <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="0" attrid="`+IDProducto+`" idPresentacion="`+IDPresentacion+`">0</button></td>
                                    <td><span>Piezas de regalo</span><input type='number' disabled value='`+datos[i].CantidadRegalo+`' min='1' step='any' class='form-control form-control-sm campoCantidadProducto CampoUnico`+IDProducto+IDPresentacion+`' onfocusout="$('#CodigoProductoVenta').focus()"></td>
                                    <td>
                                        <b>Cada `+datos[i].CantidadPromocion+` piezas recibe `+datos[i].CantidadRegalarPromocion+` pieza de regalo</b>
                                    </td>
                                    <td>`+datos[i].NombreProductoRegalo+`<br>`+datos[i].PresentacionRegalo+`</td>
                                    <td><span class='totalColumna dinero' subtotal="0" style="font-weight: bold; font-size: 15px;">0</span><br>Importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
                                    <td>
                                    </td>
                                </tr>`
                            );
                        }
                    }else if(datos[i].TipoPromocion == "ProductoRegalo"){
                        if ($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+IDProducto+'][idPresentacion='+IDPresentacion+'][tipoPromocion=ProductoRegalo]').length > 0) {
                                $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+IDProducto+'][idPresentacion='+IDPresentacion+'][tipoPromocion=ProductoRegalo]').children("td:eq(3)").find(".campoCantidadProducto").val(datos[i].CantidadRegalo)
                        }else{
                            $('#TablaProductosAgregadoVenta tbody').append(`
                                <tr class="bg-dark Promocion" style='color: white;' IDPromocion="`+datos[i].IDPromocion+`" tipoPromocion="ProductoRegalo" attrid="`+IDProducto+`" idPresentacion="`+IDPresentacion+`"  importegeneral="`+0+`" importepresentacion="`+0+`" idProductoRegalo="`+datos[i].IDProductoRegalo+`" idPresentacionRegalo="`+datos[i].IDPresentacionRegalo+`">
                                    <td>`+codigo+`</td>
                                    <td>`+datos[i].NombreProductoPromocion+`<br>`+datos[i].NombrePresentacionPromocion+`</td>
                                    <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="0" attrid="`+IDProducto+`" idPresentacion="`+IDPresentacion+`">0</button></td>
                                    <td><span>Productos de regalo</span><input type='number' disabled value='`+datos[i].CantidadRegalo+`' min='1' step='any' class='form-control form-control-sm campoCantidadProducto CampoUnico`+IDProducto+IDPresentacion+`' onfocusout="$('#CodigoProductoVenta').focus()"></td>
                                    <td>
                                        <b>Cada `+datos[i].CantidadPromocion+` piezas recibe 1 producto de regalo</b>
                                    </td>
                                    <td>`+datos[i].ProductoRegalo+`<br>`+datos[i].PresentacionRegalo+`</td>
                                    <td><span class='totalColumna dinero' subtotal="0" style="font-weight: bold; font-size: 15px;">0</span><br>Importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
                                    <td></td>
                                </tr>`
                            );
                        }
                    }else if(datos[i].TipoPromocion == "ComboProductoRegalo"){
                        if ($("#TablaProductosAgregadoVenta").children('tbody').children('tr[IDPromocion='+datos[i].IDPromocion+']').length > 0) {
                            $("#TablaProductosAgregadoVenta").children('tbody').children('tr[IDPromocion='+datos[i].IDPromocion+']').children("td:eq(3)").find(".campoCantidadProducto").val(datos[i].CantidadProductoRegalado)
                        }else{
                            $('#TablaProductosAgregadoVenta tbody').append(`
                                <tr class="bg-dark Promocion" style='color: white;' IDPromocion="`+datos[i].IDPromocion+`" idProductoCombo="`+datos[i].IDProductoCombo+`" idPresentacionCombo="`+datos[i].IDPresentacionCombo+`" IDPromocion="`+datos[i].IDPromocion+`" tipoPromocion="ComboProductoRegalo" attrid="`+IDProducto+`" idPresentacion="`+IDPresentacion+`"  importegeneral="`+0+`" importepresentacion="`+0+`" idProductoRegalo="`+datos[i].IDProductoRegalar+`" idPresentacionRegalo="`+datos[i].IDPresentacionRegalar+`">
                                    <td>`+codigo+`</td>
                                    <td>`+datos[i].NombreProductoPromocion+`<br>`+datos[i].NombrePresentacionPromocion+`<br>`+datos[i].NombreProductoCombo+`<br>`+datos[i].NombrePresentacionCombo+`</td>
                                    <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="0" attrid="`+IDProducto+`" idPresentacion="`+IDPresentacion+`">0</button></td>
                                    <td><span>Productos de regalo</span><input type='number' disabled value='`+Cantidad+`' min='1' step='any' class='form-control form-control-sm campoCantidadProducto CampoUnico`+IDProducto+IDPresentacion+`' onfocusout="$('#CodigoProductoVenta').focus()"></td>
                                    <td>
                                        <b>Recibe un producto de regalo</b>
                                    </td>
                                    <td>`+datos[i].NombreProductoRegalar+`<br>`+datos[i].NombrePresentacionRegalar+`</td>
                                    <td><span class='totalColumna dinero' subtotal="0" style="font-weight: bold; font-size: 15px;">0</span><br>Importes: <br><span class='totalColumnaImporte dinero'>0</span></td>
                                    <td></td>
                                </tr>`
                            );
                        }
                    }else if(datos[i].TipoPromocion == "ComboPrecioEspecial"){
                        console.log("ENTRO PROMOCION PRECIO ESPECIAL");
                    }   
    
                }else{
                    console.log("entro "+datos[i].TipoPromocion+" y "+datos[i].IDPromocion);
                    $("#TablaProductosAgregadoVenta").children('tbody').children('tr[IDPromocion='+datos[i].IDPromocion+']').remove()
                    /*if(datos[i].TipoPromocion == "ComboProductoRegalo"){
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[IDPromocion='+datos[i].IDPromocion+']').remove()
                    }else{
                        $("#TablaProductosAgregadoVenta").children('tbody').children('tr[idProductoRegalo='+datos[i].IDProductoRegalar+'][idPresentacionRegalo='+datos[i].IDPresentacionRegalar+'][tipoPromocion='+datos[i].TipoPromocion+']').remove()                    
                    }*/
                }   
            }
        }
    })
    .fail(function() {
        console.log("Error ajax");
    });
}