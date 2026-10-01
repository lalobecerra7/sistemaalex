var sucursalesSeleccionadasProveedor = [];
function v_ventasxproveedor() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalReporteProveedor").val(today);
    
    now.setDate(now.getDate() - 10);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioReporteProveedor").val(today);
    $("#ProveedoresReporteProducto").val(1).change()
    //TablaReporteVentasxProveedor();
    sucursalesSeleccionadasProveedor = [];
    SucursalesReporteProveedores();

    $("#ProveedoresReporteProducto").select2({
        placeholder: "-- Seleccione una opcion --",
        dropdownParent: $('#Padre'),
        allowClear: true,
        language: { 
            noResults: function() {
                return "No hay resultados";
            },
            searching: function() {
                return "Buscando..";
            }
        }
    });
}

jQuery(document).ready(function($) {
	$(document).on('change', '#FechaFinalReporteProveedor', function() {
		TablaReporteVentasxProveedor();
	});
	
	$(document).on('change', '#FechaInicioReporteProveedor', function() {
		TablaReporteVentasxProveedor();
	});

	$(document).on('change', '#ProveedoresReporteProducto', function() {
    	TablaReporteVentasxProveedor();
	});

	/*$(document).on('change', '#SucursalVentasXProducto', function() {
		TablaReporteVentasxProveedor();
	});*/

	$(document).on('click', '#bExcelVentasxProveedor', function() {
		var palabra = $(".buscadorMyDataTable[tabla=TablaReporteVentasxProveedor]").val();
        window.open("controladores/excel/excelVentasxProveedor.php?palabra="+palabra+"&fechaInicio="+$("#FechaInicioReporteProveedor").val()+"&fechaFin="+$("#FechaFinalReporteProveedor").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasProveedor)+"&Proveedor="+$("#ProveedoresReporteProducto").val());
	});


	$(document).on('click', '#CargarSucursalesModalProveedores', function() {
        $("#ModalSucursalesProveedores").modal("show");
	});

	$(document).on('click', '.CheckInputSucursalProveedores', function() {
		var boton = $(this);
        if (boton.prop("checked") == true) {
        	sucursalesSeleccionadasProveedor.push({
        		"ID" : boton.attr("attrid"),
        		"Nombre" : boton.attr("nombre"),
        	})
        }else{
        	const nuevoarreglo = sucursalesSeleccionadasProveedor.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
        	sucursalesSeleccionadasProveedor = nuevoarreglo;
        }
        console.log(sucursalesSeleccionadasProveedor)
	});

	$(document).on('click', '#SeleccionarSucursalesMarcadasProveedor', function() {
    	TablaReporteVentasxProveedor();
    	var textoSucursales = "";
    	for (var i = 0; i < sucursalesSeleccionadasProveedor.length; i++) {
    		textoSucursales += sucursalesSeleccionadasProveedor[i]["Nombre"]+", ";
    	}
    	var str = textoSucursales.replace(/,\s*$/, "");
    	if (sucursalesSeleccionadasProveedor.length <= 0) {
    		str = "No has seleccionado sucursales";
    	}
    	$("#MostrarSucursalesSeleccionadasProveedores").text(str);
    	$("#ModalSucursalesProveedores").modal("hide")
	});


});

function CalcularTotalesVentasxProveedor(){
	var data = "metodo=detalles&accion=ventasxproveedor&tipo=CalcularTotales&fechaInicio="+$("#FechaInicioReporteProveedor").val()+"&fechaFin="+$("#FechaFinalReporteProveedor").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasProveedor)+"&Proveedor="+$("#ProveedoresReporteProducto").val();
	$.ajax({
		url: 'index.php',
		type: 'POST',
		data: data,
		beforeSend: function() {
			$("#carga").show();
		}
	})
	.done(function(res) {
		console.log($.trim(res))
		var total = $.trim(res)
		$("#SpanTotalVentasXProveedor").text(parseFloat(total));
		moneda();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		$("#carga").hide();
	});
}

function SucursalesReporteProveedores(){
	ajaxMyDatatable({
		"table": $("#TablaSucursalesProveedores"), 
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
			"accion": "ventasxproveedor"
		}
	});
}

function TablaReporteVentasxProveedor(){
	CalcularTotalesVentasxProveedor()
	ajaxMyDatatable({
		"table": $("#TablaReporteVentasxProveedor"), 
		"colums": [
			"Fecha",
			"Codigo",
			"Descripcion",
			"Total",
			"Proveedor",
			"Sucursal"
		], 
		"totals":[
			"Fecha",
			"Codigo",
			"Descripcion",
			"Total",
			"Proveedor",
			"Sucursal"

		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "ventasxproveedor",
			"Proveedor" : $("#ProveedoresReporteProducto").val(),
			"fechaInicio": $("#FechaInicioReporteProveedor").val(),
			"fechaFin": $("#FechaFinalReporteProveedor").val(),
			"Sucursales": JSON.stringify(sucursalesSeleccionadasProveedor) 
		}
	});
}
