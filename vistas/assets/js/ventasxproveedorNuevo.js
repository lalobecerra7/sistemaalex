var sucursalesSeleccionadasProveedorNueva = [];
function v_ventasxproveedorNuevo() {
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
    $('#TablaReporteVentasxProveedorNuevo').DataTable().destroy()
    $("#ProveedoresReporteProducto").val(1).change()
    ////CalcularTotalesVentasxProveedor()
    sucursalesSeleccionadasProveedorNueva = [];
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
		//CalcularTotalesVentasxProveedor()
	});
	
	$(document).on('change', '#FechaInicioReporteProveedor', function() {
		//CalcularTotalesVentasxProveedor()
	});

	$(document).on('change', '#ProveedoresReporteProducto', function() {
		//CalcularTotalesVentasxProveedor()
	});

	$(document).on('click', '#bExcelVentasxProveedor', function() {
		var palabra = $(".buscadorMyDataTable[tabla=TablaReporteVentasxProveedorNuevo]").val();
        window.open("controladores/excel/excelVentasxProveedor.php?palabra="+palabra+"&fechaInicio="+$("#FechaInicioReporteProveedor").val()+"&fechaFin="+$("#FechaFinalReporteProveedor").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasProveedorNueva)+"&Proveedor="+$("#ProveedoresReporteProducto").val());
	});

	$(document).on('click', '#CargarSucursalesModalProveedores', function() {
        $("#ModalSucursalesProveedores").modal("show");
	});

	$(document).on('click', '.CheckInputSucursalProveedores', function() {
		var boton = $(this);
        if (boton.prop("checked") == true) {
        	sucursalesSeleccionadasProveedorNueva.push({
        		"ID" : boton.attr("attrid"),
        		"Nombre" : boton.attr("nombre"),
        	})
        }else{
        	const nuevoarreglo = sucursalesSeleccionadasProveedorNueva.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
        	sucursalesSeleccionadasProveedorNueva = nuevoarreglo;
        }
	});

	$(document).on('click', '#SeleccionarSucursalesMarcadasProveedor', function() {
    	var textoSucursales = "";
    	for (var i = 0; i < sucursalesSeleccionadasProveedorNueva.length; i++) {
    		textoSucursales += sucursalesSeleccionadasProveedorNueva[i]["Nombre"]+", ";
    	}
    	var str = textoSucursales.replace(/,\s*$/, "");
    	if (sucursalesSeleccionadasProveedorNueva.length <= 0) {
    		str = "No has seleccionado sucursales";
    	}
    	$("#MostrarSucursalesSeleccionadasProveedores").text(str);
    	$("#ModalSucursalesProveedores").modal("hide")
		//CalcularTotalesVentasxProveedor()
	});

    $(document).on('click', '#BotonRealizarBusqueda', function() {
        CalcularTotalesVentasxProveedor()
    });

});

function CalcularTotalesVentasxProveedor(){
	$('#TablaReporteVentasxProveedorNuevo').DataTable().destroy()
	var data = "metodo=detalles&accion=ventasxproveedorNuevo&tipo=ObtenerColumnas&fechaInicio="+$("#FechaInicioReporteProveedor").val()+"&fechaFin="+$("#FechaFinalReporteProveedor").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasProveedorNueva)+"&Proveedor="+$("#ProveedoresReporteProducto").val();
	$.ajax({
		url: 'index.php',
		type: 'POST',
		data: data,
		beforeSend: function() {
			$("#carga").show();
		}
	})
	.done(function(res) {
        console.log($.trim(res));
        var datos = JSON.parse(res);

        $("#TablaReporteVentasxProveedorNuevo thead").html(datos.head)
		$("#TablaReporteVentasxProveedorNuevo tbody").html(datos.body)

		$('#TablaReporteVentasxProveedorNuevo').DataTable({
			"deferRender": true,
            "pageLength": 100,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "",
                "searchPlaceholder": "Buscar . . .",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                buttons: {
                    copy: 'Copiar',
                    copySuccess: {
                        1: "Se ha copiado una fila",
                        _: "Se han copiado %d filas"
                    },
                    copyTitle: 'Elementos copiados'
                }
            },
            dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                "<'row mb-3'<'col-sm-12'tr>>" +
                "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-secondary btn-sm',
                    text: "<i class='fas fa-copy'></i>",
                    titleAttr: 'Copiar',
                    footer: true
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-success btn-sm',
                    text: "<i class='fas fa-file-excel'></i>",
                    titleAttr: 'Excel',
                    filename: 'VentasProveedor',
                    title: 'VentasProveedor',
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-danger btn-sm',
                    text: "<i class='fas fa-file-pdf'></i>",
                    titleAttr: 'PDF',
                    filename: 'VentasProveedor',
                    title: 'VentasProveedor',
                    orientation: 'portrait',
                    pageSize: 'LETTER',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 11;
                        doc.styles.tableHeader.fontSize = 14;
                        doc.defaultStyle.alignment = 'center';
                    },
                    footer: true
                }
            ]
		}).draw('page');
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
			"accion": "ventasxproveedorNuevo"
		}
	});
}


