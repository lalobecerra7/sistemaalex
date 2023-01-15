function v_importes() {
	TablaReporteImportes();
}

jQuery(document).ready(function($) {

	$(document).on('click', '#ImprimirTicketVentaSinCajaImporte', function() {
		var idVenta = $(this).attr("attrid");
		var sucursal = $(this).attr("sucursal");
		var altura=50;
        var anchura=310;

        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
        console.log(sucursal);
		window.open("controladores/ticket.php?id="+idVenta+"&idSucursal="+sucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
	});

	$(document).on('click', '#VerProductosImporte', function() {
		var folio = $(this).attr("folio");
		var id = $(this).attr("attrid");
		$("#ModalVerProductosImporte").modal("show");
		$("#FolioImporteVenta").text(folio);
		TablaProductosImporte(id);
	});

	$(document).on('click', '.MarcarPagadoImporte', function() {
		var btn = $(this);
		var idventa = $(this).attr("idventa");
		Swal.fire({
	        title: '¿Estás seguro que quieres marcar este importe como pagado?',
	        icon: 'info',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        cancelButtonText: '¡No, cancelar!',
	        confirmButtonText: '¡Si, continuar!'
	    }).then((result) => {
	        if (result.value) {
	        	var data = "metodo=modificar&accion=importes&IDImporte="+$(btn).attr('attrid');
				$.ajax({
					url: 'index.php',
					type: 'POST',
					data: data,
					beforeSend: function() {
					    progressBoton(btn);
					}
				})
				.done(function(res) {
					if ($.trim(res) == "Correcto") {
						Swal.fire({
							icon: 'success',
							title: 'Importe pagado correctamente'
						});
						TablaProductosImporte(idventa);
						TablaReporteImportes();
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Error inesperado al pagar importe.'
						});
						console.log($.trim(res));
					}
				})
				.fail(function() {
					console.log("Error ajax");
				})
				.always(function() {
					unprogressBoton(btn);
				});
			}    
		});	  
	});

});



function TablaReporteImportes(){
	ajaxMyDatatable({
		"table": $("#TablaReporteImportes"), 
		"colums": [
			"Cliente",
			"Importes",
			"Estatus",
			"Acciones",
		], 
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "importes"
		}
	});
}


function TablaProductosImporte(idventa){
	ajaxMyDatatable({
		"table": $("#TablaCargarProductosImporte"), 
		"colums": [
			'Producto',
			'Cantidad',
			'Importe',
			'Total',
			'Estatus',
			'Acciones',
		], 
		"sort": [
			1,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"accion": "importes",
			"tipo": "ConsultarProductosImporte",
			"idventa": idventa
		}
	});
}