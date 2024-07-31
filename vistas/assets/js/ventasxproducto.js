var sucursalesSeleccionadasProducto = [];
function v_ventasxproducto() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalReporteProducto").val(today);
    
    now.setDate(now.getDate() - 10);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioReporteProducto").val(today);

    TablaReporteVentasxProducto();
    CalcularTotalesVentasxProducto();
    sucursalesSeleccionadasProducto = [];
    SucursalesReporteProductos();
}

jQuery(document).ready(function($) {
	$(document).on('change', '#FechaFinalReporteProducto', function() {
		TablaReporteVentasxProducto();
		CalcularTotalesVentasxProducto();
	});
	
	$(document).on('change', '#FechaInicioReporteProducto', function() {
		TablaReporteVentasxProducto();
		CalcularTotalesVentasxProducto();
	});

	/*$(document).on('change', '#SucursalVentasXProducto', function() {
		TablaReporteVentasxProducto();
		CalcularTotalesVentasxProducto();
	});*/

	$(document).on('click', '#bExcelVentasxProducto', function() {
		var palabra = $(".buscadorMyDataTable[tabla=TablaReporteVentasxProducto]").val();
        window.open("controladores/excel/excelVentasxProducto.php?palabra="+palabra+"&fechaInicio="+$("#FechaInicioReporteProducto").val()+"&fechaFin="+$("#FechaFinalReporteProducto").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasProducto));
	});


	$(document).on('click', '#CargarSucursalesModalProductos', function() {
        $("#ModalSucursalesProductos").modal("show");
	});

	$(document).on('click', '.CheckInputSucursalProductos', function() {
		var boton = $(this);
        if (boton.prop("checked") == true) {
        	sucursalesSeleccionadasProducto.push({
        		"ID" : boton.attr("attrid"),
        		"Nombre" : boton.attr("nombre"),
        	})
        }else{
        	const nuevoarreglo = sucursalesSeleccionadasProducto.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
        	sucursalesSeleccionadasProducto = nuevoarreglo;
        }
	});

	$(document).on('click', '#SeleccionarSucursalesMarcadasProductos', function() {
    	TablaReporteVentasxProducto();
    	var textoSucursales = "";
    	for (var i = 0; i < sucursalesSeleccionadasProducto.length; i++) {
    		textoSucursales += sucursalesSeleccionadasProducto[i]["Nombre"]+", ";
    	}
    	var str = textoSucursales.replace(/,\s*$/, "");
    	if (sucursalesSeleccionadasProducto.length <= 0) {
    		str = "No has seleccionado sucursales";
    	}
    	$("#MostrarSucursalesSeleccionadasProductos").text(str);
    	$("#ModalSucursalesProductos").modal("hide")
	});


});

function CalcularTotalesVentasxProducto(){
	var data = "metodo=detalles&accion=ventasxproducto&tipo=CalcularTotales&fechaInicio="+$("#FechaInicioReporteProducto").val()+"&fechaFin="+$("#FechaFinalReporteProducto").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasProducto);
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

function SucursalesReporteProductos(){
	ajaxMyDatatable({
		"table": $("#TablaSucursalesProductos"), 
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
			"accion": "ventasxproducto"
		}
	});
}

function TablaReporteVentasxProducto(){
	ajaxMyDatatable({
		"table": $("#TablaReporteVentasxProducto"), 
		"colums": [
			"Fecha",
			"Codigo",
			"Descripcion",
			"Cantidad",
			"Total",
			"Devuelto",
			"Sucursal"
		], 
		"totals":[
			"Fecha",
			"Codigo",
			"Descripcion",
			"Cantidad",
			"Total",
			"Devuelto",
			"Sucursal"

		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "ventasxproducto",
			"fechaInicio": $("#FechaInicioReporteProducto").val(),
			"fechaFin": $("#FechaFinalReporteProducto").val(),
			"Sucursales": JSON.stringify(sucursalesSeleccionadasProducto) 
		}
	});
}
