function v_reporteCaja() {
	TablaReporteCajas();
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
			"Totales",
			"Acciones"
		], 
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "reporteCaja"
		}
	});
}
