var sucursalesSeleccionadasImporte = [];
function v_reporteImportes() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalReporteImportes").val(today);
    
    now.setDate(now.getDate() - 10);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioReporteImportes").val(today);

    TablaReporteDeImportes();
    CalcularTotalesImportes();
    sucursalesSeleccionadasImporte = [];
    SucursalesReporteImportes();
}

jQuery(document).ready(function($) {
	$(document).on('change', '#FechaFinalReporteImportes', function() {
		TablaReporteDeImportes();
		CalcularTotalesImportes();
	});
	
	$(document).on('change', '#FechaInicioReporteImportes', function() {
		TablaReporteDeImportes();
		CalcularTotalesImportes();
	});

	$(document).on('click', '#bExcelReporteImportes', function() {
		var palabra = $(".buscadorMyDataTable[tabla=TablaReporteDeImportes]").val();
        window.open("controladores/excel/excelreporteImportes.php?palabra="+palabra+"&fechaInicio="+$("#FechaInicioReporteImportes").val()+"&fechaFin="+$("#FechaFinalReporteImportes").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasImporte));
	});


	$(document).on('click', '#CargarSucursalesModalImportes', function() {
        $("#ModalSucursalesImportes").modal("show");
	});

	$(document).on('click', '.CheckInputSucursalImportes', function() {
		var boton = $(this);
        if (boton.prop("checked") == true) {
        	sucursalesSeleccionadasImporte.push({
        		"ID" : boton.attr("attrid"),
        		"Nombre" : boton.attr("nombre"),
        	})
        }else{
        	const nuevoarreglo = sucursalesSeleccionadasImporte.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
        	sucursalesSeleccionadasImporte = nuevoarreglo;
        }
	});

	$(document).on('click', '#SeleccionarSucursalesMarcadasImportes', function() {
    	var textoSucursales = "";
    	for (var i = 0; i < sucursalesSeleccionadasImporte.length; i++) {
    		textoSucursales += sucursalesSeleccionadasImporte[i]["Nombre"]+", ";
    	}
    	var str = textoSucursales.replace(/,\s*$/, "");
    	if (sucursalesSeleccionadasImporte.length <= 0) {
    		str = "No has seleccionado sucursales";
    	}
    	TablaReporteDeImportes();
    	CalcularTotalesImportes();
    	$("#MostrarSucursalesSeleccionadasImportes").text(str);
    	$("#ModalSucursalesImportes").modal("hide")
	});


});

function CalcularTotalesImportes(){
	var data = "metodo=detalles&accion=reporteImportes&tipo=CalcularTotales&fechaInicio="+$("#FechaInicioReporteImportes").val()+"&fechaFin="+$("#FechaFinalReporteImportes").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasImporte);
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
		var Importes = datos[0];
		var Pagados = datos[1];
		var Pendientes = datos[2];
		var ImportesPVC = datos[3];
		var PagadosPVC = datos[4];
		var PendientesPVC = datos[5];

		$("#SpanTotalImportes").text(Importes || 0);
		$("#SpanTotalImpoPagados").text(Pagados || 0);
		$("#SpanTotalImpoPendientes").text(Pendientes || 0);
		$("#SpanTotalImportesPVC").text(ImportesPVC || 0);
		$("#SpanTotalImpoPagadosPVC").text(PagadosPVC || 0);
		$("#SpanTotalImpoPendientesPVC").text(PendientesPVC || 0);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		$("#carga").hide();
	});
}

function SucursalesReporteImportes(){
	ajaxMyDatatable({
		"table": $("#TablaSucursalesImportes"), 
		"colums": [
			"Seleccionar",
			"Sucursal",
			"Direccion",
		], 
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"tipo": "ConsultarSucursales",
			"accion": "reporteImportes"
		}
	});
}

function TablaReporteDeImportes(){
	ajaxMyDatatable({
		"table": $("#TablaReporteDeImportes"), 
		"colums": [
			"Cliente",
			"Importes",
			"Pagados",
			"Pendientes",
			"CantidadPVC",
			"PagadosPVC",
			"PendientesPVC",
		], 
		"totals":[
			"Cliente",
			"Importes",
			"Pagados",
			"Pendientes",
			"CantidadPVC",
			"PagadosPVC",
			"PendientesPVC",
		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "reporteImportes",
			"fechaInicio": $("#FechaInicioReporteImportes").val(),
			"fechaFin": $("#FechaFinalReporteImportes").val(),
			"Sucursales": JSON.stringify(sucursalesSeleccionadasImporte) 
		}
	});
}
