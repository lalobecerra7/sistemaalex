var sucursalesSeleccionadas = [];
function v_reporteinventario() {
    SucursalesReporteInventario();
    sucursalesSeleccionadas = [];
    TablaReporteInventario();

    $("#ProveedoresReporteInventario").select2({
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
	/*$(document).on('change', '#FechaFinalReporteDia', function() {
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
	});*/

	$(document).on('click', '#bExcelReporteInventario', function() {
		var palabra = $(".buscadorMyDataTable[tabla=TablaReporteInventario]").val();
        window.open("controladores/excel/excelReporteInventario.php?palabra="+palabra+"&sucursales="+JSON.stringify(sucursalesSeleccionadas)+"&proveedor="+$("#ProveedoresReporteInventario").val());
	});

	$(document).on('click', '#CargarSucursalesModal', function() {
        $("#ModalSucursalesInventario").modal("show");
	});

	$(document).on('click', '.CheckInputSucursal', function() {
		var boton = $(this);
        if (boton.prop("checked") == true) {
        	sucursalesSeleccionadas.push({
        		"ID" : boton.attr("attrid"),
        		"Nombre" : boton.attr("nombre"),
        	})
        }else{
        	const nuevoarreglo = sucursalesSeleccionadas.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
        	sucursalesSeleccionadas = nuevoarreglo;
        }
        console.log(sucursalesSeleccionadas)
	});

	$(document).on('click', '#SeleccionarSucursalesMarcadas', function() {
		$("#ModalSucursalesInventario").modal("hide")
    	TablaReporteInventario();
    	var textoSucursales = "";
    	for (var i = 0; i < sucursalesSeleccionadas.length; i++) {
    		textoSucursales += sucursalesSeleccionadas[i]["Nombre"]+", ";
    	}
    	var str = textoSucursales.replace(/,\s*$/, "");
    	if (sucursalesSeleccionadas.length <= 0) {
    		str = "No has seleccionado sucursales";
    	}
    	$("#MostrarSucursalesSeleccionadas").text(str);
	});

	$(document).on('change', '#ProveedoresReporteInventario', function() {
    	TablaReporteInventario();
	});
});

function SucursalesReporteInventario(){
	ajaxMyDatatable({
		"table": $("#TablaSucursalesInventario"), 
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
			"accion": "reporteinventario"
		}
	});
}

function TablaReporteInventario(){
	console.log($("#ProveedoresReporteInventario").val())
	ajaxMyDatatable({
		"table": $("#TablaReporteInventario"), 
		"colums": [
			"Codigo",
			"Descripcion",
			"Existencia",
			"Proveedor",
			"Sucursal",
		], 
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "reporteinventario",
			"Proveedor" : $("#ProveedoresReporteInventario").val(),
			"Sucursales": JSON.stringify(sucursalesSeleccionadas)
		}
	});
}
