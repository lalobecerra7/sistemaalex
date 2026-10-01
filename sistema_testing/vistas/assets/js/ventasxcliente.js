function v_ventasxcliente() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalReporteCliente").val(today);
    
    now.setDate(now.getDate() - 30);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioReporteCliente").val(today);

    TablaReporteVentasxCliente();
    CalcularTotalesVentasxCliente();
}

jQuery(document).ready(function($) {
	$(document).on('change', '#FechaFinalReporteCliente', function() {
		TablaReporteVentasxCliente();
		CalcularTotalesVentasxCliente();
	});
	
	$(document).on('change', '#FechaInicioReporteCliente', function() {
		TablaReporteVentasxCliente();
		CalcularTotalesVentasxCliente();
	});

	$(document).on('change', '#SucursalVentasXCliente', function() {
		TablaReporteVentasxCliente();
		CalcularTotalesVentasxCliente();
	});

	$(document).on('click', '#bExcelVentasxCliente', function() {
		var palabra = $(".buscadorMyDataTable[tabla=TablaReporteVentasxCliente]").val();
        console.log(palabra);
        window.open("controladores/excel/excelVentasxCliente.php?palabra="+palabra+"&fechaInicio="+$("#FechaInicioReporteCliente").val()+"&fechaFin="+$("#FechaFinalReporteCliente").val()+"&sucursal="+$("#SucursalVentasXCliente").val());
	});
});

function CalcularTotalesVentasxCliente(){
	var data = "metodo=detalles&accion=ventasxcliente&tipo=CalcularTotales&fechaInicio="+$("#FechaInicioReporteCliente").val()+"&fechaFin="+$("#FechaFinalReporteCliente").val()+"&sucursal="+$("#SucursalVentasXCliente").val();
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
		var SumaTotales = datos[0];
		var SumarCantidad = datos[1];
		var SumaDevoluciones = datos[2];

		$("#SpanCantidadVentasXCliente").text(SumarCantidad || 0);
		$("#SpanTotalVentasXCliente").text(SumaTotales || 0);
		$("#SpanDevueltoVentasXCliente").text(SumaDevoluciones || 0);
		moneda();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		$("#carga").hide();
	});
}

function TablaReporteVentasxCliente(){
	ajaxMyDatatable({
		"table": $("#TablaReporteVentasxCliente"), 
		"colums": [
			"Cliente",
			"Cantidad",
			"Total",
			"Devuelto"
		], 
		"totals":[
			"Cliente",
			"Cantidad",
			"Total",
			"Devuelto"

		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "ventasxcliente",
			"fechaInicio": $("#FechaInicioReporteCliente").val(),
			"fechaFin": $("#FechaFinalReporteCliente").val(),
			"sucursal": $("#SucursalVentasXCliente").val()
		}
	});
}
