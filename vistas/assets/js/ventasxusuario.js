function v_ventasxusuario() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalReporteUsuario").val(today);
    
    now.setDate(now.getDate() - 30);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioReporteUsuario").val(today);

    TablaReporteVentasxUsuario();
    CalcularTotalesVentasxUsuario();
}

jQuery(document).ready(function($) {
	$(document).on('change', '#FechaFinalReporteUsuario', function() {
		TablaReporteVentasxUsuario();
		CalcularTotalesVentasxUsuario();
	});
	
	$(document).on('change', '#FechaInicioReporteUsuario', function() {
		TablaReporteVentasxUsuario();
		CalcularTotalesVentasxUsuario();
	});

	$(document).on('change', '#SucursalVentasXUsuario', function() {
		TablaReporteVentasxUsuario();
		CalcularTotalesVentasxUsuario();
	});

	$(document).on('click', '#bExcelVentasxUsuario', function() {
		var palabra = $(".buscadorMyDataTable[tabla=TablaReporteVentasxUsuario]").val();
        console.log(palabra);
        window.open("controladores/excel/excelVentasxUsuario.php?palabra="+palabra+"&fechaInicio="+$("#FechaInicioReporteUsuario").val()+"&fechaFin="+$("#FechaFinalReporteUsuari").val()+"&sucursal="+$("#SucursalVentasXUsuario").val());
	});
});

function CalcularTotalesVentasxUsuario(){
	var data = "metodo=detalles&accion=ventasxusuario&tipo=CalcularTotales&fechaInicio="+$("#FechaInicioReporteUsuario").val()+"&fechaFin="+$("#FechaFinalReporteUsuario").val()+"&sucursal="+$("#SucursalVentasXUsuario").val();
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

		$("#SpanCantidadVentasXUsuario").text(SumarCantidad || 0);
		$("#SpanTotalVentasXUsuario").text(SumaTotales || 0);
		$("#SpanDevueltoVentasXUsuario").text(SumaDevoluciones || 0);
		moneda();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		$("#carga").hide();
	});
}

function TablaReporteVentasxUsuario(){
	ajaxMyDatatable({
		"table": $("#TablaReporteVentasxUsuario"), 
		"colums": [
			"Usuario",
			"Cantidad",
			"Total",
			"Devuelto"
		], 
		"totals":[
			"Usuario",
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
			"accion": "ventasxusuario",
			"fechaInicio": $("#FechaInicioReporteUsuario").val(),
			"fechaFin": $("#FechaFinalReporteUsuario").val(),
			"sucursal": $("#SucursalVentasXUsuario").val()
		}
	});
}
