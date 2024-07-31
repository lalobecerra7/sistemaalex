function v_ventasxdia() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalReporteDia").val(today);
    
    now.setDate(now.getDate() - 30);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioReporteDia").val(today);

    TablaReporteVentasxDia();
    CalcularTotalesVentasxDia();
}

jQuery(document).ready(function($) {
	$(document).on('change', '#FechaFinalReporteDia', function() {
		TablaReporteVentasxDia();
		CalcularTotalesVentasxDia();
	});
	
	$(document).on('change', '#FechaInicioReporteDia', function() {
		TablaReporteVentasxDia();
		CalcularTotalesVentasxDia();
	});

	$(document).on('change', '#SucursalVentasXDia', function() {
		TablaReporteVentasxDia();
		CalcularTotalesVentasxDia();
	});

	$(document).on('click', '#bExcelVentasxDia', function() {
        window.open("controladores/excel/excelVentasxDia.php?fechaInicio="+$("#FechaInicioReporteDia").val()+"&fechaFin="+$("#FechaFinalReporteDia").val()+"&sucursal="+$("#SucursalVentasXDia").val());
	});
});

function CalcularTotalesVentasxDia(){
	var data = "metodo=detalles&accion=ventasxdia&tipo=CalcularTotales&fechaInicio="+$("#FechaInicioReporteDia").val()+"&fechaFin="+$("#FechaFinalReporteDia").val()+"&sucursal="+$("#SucursalVentasXDia").val();
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
		var SumaImportes = datos[1];
		var SumaDescuentos = datos[2];
		var SumaSubtotal = datos[3];
		var SumaDevoluciones = datos[4];
		$("#SpanTotalImportes").text(SumaImportes || 0);
		$("#SpanTotalDescuentos").text(SumaDescuentos || 0);
		$("#SpanTotalSubtotal").text(SumaSubtotal || 0);
		$("#SpanTotalImpuestos").text(0);
		$("#SpanTotalVentas").text(SumaTotales || 0);
		$("#SpanTotalDevoluciones").text(SumaDevoluciones || 0);
		moneda();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		$("#carga").hide();
	});
}

function TablaReporteVentasxDia(){
	ajaxMyDatatable({
		"table": $("#TablaReporteVentasxDia"), 
		"colums": [
			"Fecha",
			"Sucursal",
			"Importes",
			"Descuento",
			"Subtotal",
			"Impuestos",
			"Total",
			"Devoluciones"
		], 
		"totals":[
			"Fecha",
			"Sucursal",
			"Importes",
			"Descuento",
			"Subtotal",
			"Impuestos",
			"Total",
			"Devoluciones"
		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "ventasxdia",
			"fechaInicio": $("#FechaInicioReporteDia").val(),
			"fechaFin": $("#FechaFinalReporteDia").val(),
			"sucursal": $("#SucursalVentasXDia").val()
		}
	});
}
