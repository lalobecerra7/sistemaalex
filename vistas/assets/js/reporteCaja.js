function v_reporteCaja() {
	var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalModuloCorteCaja").val(today);
    
    now.setDate(now.getDate() - 7);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioModuloCorteCaja").val(today);

	TablaReporteCajas();

	$(document).on('click', '.VerCorteCaja', function() {
        $("#ModalCerrarCaja").modal("show");
    	$("#MontoCierreCaja").focus();
		var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarBalanceCerrar&IDDetalleCaja="+$(this).attr("attrid")+"&sucursal="+$(this).attr("idSucursal");
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

      
    });

	$(document).on('change', '#FechaFinalModuloCorteCaja', function() {
		TablaReporteCajas();
	});
	
	$(document).on('change', '#FechaInicioModuloCorteCaja', function() {
		TablaReporteCajas();
	});

	$(document).on('change', '#SucursalCorteCaja', function() {
		TablaReporteCajas();
	});
}

jQuery(document).ready(function($) {
	
	$(document).on('click', '#ReimprimirTicketCaja', function() {
		var iddetalle = $(this).attr("attrid");
		var sucursal = $(this).attr("sucursal");
		var altura=50;
        var anchura=310;
        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
		window.open("controladores/ticketCaja.php?id="+iddetalle+"&idsucursal="+sucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
	});

});

function TablaReporteCajas(){
	ajaxMyDatatable({
		"table": $("#TablaReporteCajas"), 
		"colums": [
			"Abrir",
			"Caja",
			"MontoAbrir",
			"Cerrar",
			"MontoCerrar",
			"Acciones"
		], 
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "reporteCaja",
			"fechaInicio": $("#FechaInicioModuloCorteCaja").val(),
			"fechaFin": $("#FechaFinalModuloCorteCaja").val(),
			"sucursal": $("#SucursalCorteCaja").val()
		}
	});
}
