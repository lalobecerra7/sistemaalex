function v_hacerCompra() {
	$("#CodigoProducto").focus();
}

jQuery(document).ready(function($) {

	$(document).on('submit', '#FormAgregarProductoC', function(event) {
        event.preventDefault();
		$('#AgregarProductoCodigoC').trigger('click');
    });

	$(document).on('click', '#CargarProductosModalC', function() {
		TablaProductosCompra();
	});

	$(document).on('click', '#CargarProveedoresModalC', function() {
		TablaProveedoresCompra();
	});

	$(document).on('click', '#LimpiarProveedorSeleccionado', function() {
		$(".BotonLimpiarProveedor").addClass("oculto");
		$("#CargarProveedoresModalC").html('<i class="fas fa-user"></i> Proveedor');
	});


    $(document).on('keyup change', '.campoCantidad', function() {
    	var cantidad = $(this).val();
    	if (cantidad == "") {
    		cantidad = 0;
    	}
    	var costo = $(this).parent().parent().find(".campoCosto").val();
    	var total = 0;
		if(costo != ''){
			total = parseFloat(costo) * parseFloat(cantidad);
		}

    	$(this).parent().parent().find('.totalP').html('<span class="dinero">'+(Math.round(total * 100) / 100)+'</span>');
    	
    	CalcularSubtotal();
    	$("#DescuentoCompraDinero").trigger("change");
    });

	$(document).on('keyup change', '.campoCosto', function() {
    	var cantidad = $(this).parent().parent().find('.campoCantidad').val();
		
    	if (cantidad == "") {
    		cantidad = 0;
    	}
    	var costo = $(this).val();
    	var total = 0;
		if(costo != ''){
			total = parseFloat(costo) * parseFloat(cantidad);
		}
    	$(this).parent().parent().find('.totalP').html('<span class="dinero">'+(Math.round(total * 100) / 100)+'</span>');
    	CalcularSubtotal();
    	$("#DescuentoCompraDinero").trigger("change");
    });

    $(document).on('hidden.bs.modal', '#ModalVerProductosVenta', function(){
		$("#CodigoProducto").focus();
    });

    $(document).on('hidden.bs.modal', '#ModalVerProveedoresC',function(){
		$("#CodigoProducto").focus();
    });

    $(document).on('keyup change', '#DescuentoCompraDinero', function() {
    	const searchRegExp = new RegExp(',', 'g');
    	if ($("#RealizarCompra").attr("tipodescuento") == "Cantidad") {
    		$("#RealizarCompra").attr("cantidad", $(this).val());
    	}
        $("#DescuentoCompraPorcentaje").val(Math.round(((parseFloat($(this).val())/parseFloat($("#MostrarSubtotal").text().replace('$', '').replace(searchRegExp, '')))*100)*100)/100);
    	CalcularTotal();
    });

	$(document).on('click', '#AgregarProductoCodigoC', function() {
    	//$("#ModalVerProductosCompra").modal("show");
		var Codigo = $("#CodigoProductoC").val(); 
		var data = "metodo=consultar&accion=hacerCompra&tipo=ConsultarProductoCodigo&codigo="+Codigo;
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: data,
		})
		.done(function(res) {
			//console.log($.trim(res));
			var datos = JSON.parse(res);
			//console.log(datos);

			if(datos == null){
				Swal.fire({
				  icon: 'error',
				  title: 'Producto no encontrado',
				  timer: 1200
				});
			}else{
				var idProducto = datos.ID_Producto;
				var codigo = datos.Codigo;
				var descripcion = datos.Descripcion;
				var costo = datos.Costo;
				var presentacion = 'Sin presentación';
				if(datos.NombrePresentacion != null){
					presentacion = datos.NombrePresentacion;
				}
				var idPresentacion = datos.FK_Presentacion;
				//var existencia = $(this).children("td:eq(3)").find(".ExistenciaProducto").text();

				var fila = "\
					<tr attrid='"+idProducto+"' idPresentacion='"+idPresentacion+"'>\
						<td>"+codigo+"</td>\
						<td>"+descripcion+"</td>\
						<td>"+presentacion+"</td>\
						<td class='costoP'><input type='number' value='"+costo+"' min='1' step='any' class='form-control campoCosto'></td>\
						<td><input type='number' value='1' min='1' step='any' class='form-control campoCantidad'></td>\
						<td class='totalP'><span class='dinero'>"+costo+"</span></td>\
						<td><button class='btn btn-danger btn-sm EliminarFila'><i class='fas fa-trash'></i></button></td>\
					</tr>\
				";

				var encontrado = false;

				$("#tbodyTablaProductosAgregados tr").each(function(){
					if ($(this).attr("attrid") == idProducto && $(this).attr("idpresentacion") == idPresentacion) {
						var cantidadAnterior = $(this).children("td:eq(4)").find(".campoCantidad").val();
						$(this).children("td:eq(4)").find(".campoCantidad").val(parseFloat(cantidadAnterior)+1);
						encontrado = true;
						$(".campoCantidad").trigger("keyup");
						return false;
					}
				});

				if (encontrado == false) {
					$("#tbodyTablaProductosAgregados").append(fila);
				}
				$("#CodigoProducto").val("");
				$("#CodigoProducto").focus();
				CalcularSubtotal();
				$("#DescuentoCompraDinero").val($("#RealizarCompra").attr("cantidad"));
				$("#DescuentoCompraDinero").trigger("change");

				$("#CodigoProductoC").val("");

				moneda();
			}	
		})
		.fail(function() {
			console.log("Error ajax");
		});
    });

	$(document).on('click', '#TablaProductosCompra tbody tr', function() {
		const searchRegExp = new RegExp(',', 'g');
		$("#ModalVerProductosCompra").modal("hide");
		var idProducto = $(this).attr("id");
		var codigo = $(this).children("td:eq(0)").find(".codigo").text();
		var descripcion = $(this).children("td:eq(1)").find(".NombreProducto").text();
		var costo = $(this).children("td:eq(2)").find(".CostoProducto").text().replace("$","").replace(searchRegExp, '');
		var idPresentacion = $(this).children("td:eq(3)").find(".Presentacion").attr('presentacion');
		var presentacion = '';
		if ($(this).children("td:eq(3)").find(".Presentacion").text() != ''){
			presentacion = $(this).children("td:eq(3)").find(".Presentacion").text();
		}else{
			presentacion = 'Sin presentación';
		}
		//var existencia = $(this).children("td:eq(3)").find(".ExistenciaProducto").text();
		
		var fila = "\
			<tr attrid='"+idProducto+"' idPresentacion='"+idPresentacion+"'>\
                <td>"+codigo+"</td>\
                <td>"+descripcion+"</td>\
                <td>"+presentacion+"</td>\
                <td class='costoP'><input type='number' value='"+costo+"' min='1' step='any' class='form-control campoCosto'></td>\
                <td><input type='number' value='1' min='1' step='any' class='form-control campoCantidad'></td>\
                <td class='totalP'>"+(costo*1)+"</td>\
            	<td><button class='btn btn-danger btn-sm EliminarFila'><i class='fas fa-trash'></i></button></td>\
            </tr>\
		";

		var encontrado = false;

		$("#tbodyTablaProductosAgregados tr").each(function(){
			if ($(this).attr("attrid") == idProducto && $(this).attr("idpresentacion") == idPresentacion) {
				var cantidadAnterior = $(this).children("td:eq(4)").find(".campoCantidad").val();
				$(this).children("td:eq(4)").find(".campoCantidad").val(parseFloat(cantidadAnterior)+1);
				encontrado = true;
				$(".campoCantidad").trigger("keyup");
				return false;
			}
		});

		if (encontrado == false) {
			$("#tbodyTablaProductosAgregados").append(fila);
		}
		$("#CodigoProducto").val("");
		$("#CodigoProducto").focus();
		CalcularSubtotal();
    	$("#DescuentoCompraDinero").val($("#RealizarCompra").attr("cantidad"));
    	$("#DescuentoCompraDinero").trigger("change");
		/*$("#DescuentoVentaDinero").trigger("change");
		$("#DescuentoVentaPorcentaje").trigger("change");*/
	});

	$(document).on('click', '.EliminarFila', function() {
		$(this).parent().parent().remove();
		CalcularSubtotal();
    		$("#DescuentoCompraDinero").val($("#RealizarCompra").attr("cantidad"));
    		$("#DescuentoCompraDinero").trigger("change");
	});

	$(document).on('click', '#TablaProveedoresCompra tbody tr', function() {
		$(".BotonLimpiarProveedor").removeClass("oculto");
		var idProveedor = $(this).attr("id");
		var nombre = $(this).children("td:eq(0)").find(".NombreProveedor").text();
		var razonSocial = $(this).children("td:eq(2)").find(".razonSocial").text();
		//var tipodescuento = $(this).children("td:eq(3)").find(".tipoDescuentoCliente").text();
		//var cantidaddescuento = $(this).children("td:eq(3)").find(".cantidadDescuentoCliente").text(); 
		$("#RealizarCompra").attr("idProveedor", idProveedor);
		$("#CargarProveedoresModalC").html('Proveedor: '+nombre+'<br>Razon social: '+razonSocial);
		$("#ModalVerProveedoresC").modal("hide");
		if($('#TipoCompra').val() == 'Credito'){
			$("#TipoCompra").trigger('change');
		}
		//const searchRegExp = new RegExp(',', 'g');
		//$("#RealizarVenta").attr("tipodescuento", tipodescuento);
		//$("#RealizarVenta").attr("cantidad", parseFloat(cantidaddescuento.replace('%', '').replace('$', '').replace(searchRegExp, '')));
		// if ($.trim(tipodescuento) == "Porcentaje") {
		// 	var valor = cantidaddescuento.replace('%', '').replace(searchRegExp, '');
		// 	$("#DescuentoVentaPorcentaje").val(parseFloat(valor));
		// 	$("#DescuentoVentaPorcentaje").trigger("change");
		// }else if($.trim(tipodescuento) == "Cantidad"){
		// 	var valor = cantidaddescuento.replace('$', '').replace(searchRegExp, '');
		// 	$("#DescuentoVentaDinero").val(parseFloat(valor));
		// 	$("#DescuentoVentaDinero").trigger("change");
		// }else{
		// 	$("#DescuentoVentaPorcentaje").val(0);
		// 	$("#DescuentoVentaPorcentaje").trigger("change");
		// }
	});

	$(document).on('click', '#RealizarCompra', function() {
		const searchRegExp = new RegExp(',', 'g');
		if ($(this).attr("idProveedor") == "") {
			Swal.fire({
	            icon: 'error',
	            title: 'Oops...',
	        	text: 'Seleccione un proveedor para la compra'
	        });
		}else if($("#tbodyTablaProductosAgregados tr").length <= 0){
			Swal.fire({
	            icon: 'error',
	            title: 'Oops...',
	        	text: 'Tienes que ingresar al menos un producto para realizar la compra'
	        });
		}else if($("#TotalCompra").text().replace("$","").replace(searchRegExp, '') <= 0){
			Swal.fire({
	            icon: 'error',
	            title: 'Oops...',
	        	text: 'No se puede realizar una venta con un total de $0.00'
	        });
		}else{
			var fecha = new Date();
			var day = fecha.getDate();
			var month = fecha.getMonth() + 1;
			var year = fecha.getFullYear();
			var hoy = (year+'-'+month+'-'+day);
			if($('#TipoCompra').val() == 'Contado'){
				$("#CreditoCompra").attr("hidden", true);
				$("#Total").text($("#TotalCompra").text());
				$("#ModalCobrarCompra").modal("show");
				$("#SubtotalCompra").attr("hidden", false);
				$("#DescuentoCompra").attr("hidden", false);
				$("#TotalDeCompra").attr("hidden", false);
				$("#Detalles").attr("hidden", false);
				//$("#Archivo").attr("hidden", false);
				$("#Subtotal").text($("#MostrarSubtotal").text());
				$("#Descuento").text(new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format($("#DescuentoCompraDinero").val()));
			}else{
				if($('#fechaCredito').val() == ''){
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Debes elegir una fecha como limite de pago.'
					});
				}else if($('#fechaCredito').val() <= hoy){
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Debes elegir una fecha posterior al dia de hoy.'
					});
				}else{
					const searchRegExp = new RegExp(',', 'g');
					$("#CreditoCompra").attr("hidden", false);
					$("#Credito").text($('#MostrarCreditoRestante').text());
					var totalFinal=0;
					totalFinal = parseFloat($('#TotalCompra').text().replace('$', '').replace(searchRegExp, ''))-parseFloat($("#MostrarCreditoRestante").text().replace('$', '').replace(searchRegExp, ''))
					$("#Total").text(new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(totalFinal));
					$("#ModalCobrarCompra").modal("show");
					$("#SubtotalCompra").attr("hidden", false);
					$("#DescuentoCompra").attr("hidden", false);
					$("#TotalDeCompra").attr("hidden", false);
					$("#Detalles").attr("hidden", false);
					//$("#Archivo").attr("hidden", false);
					$("#Subtotal").text($("#MostrarSubtotal").text());
					$("#Descuento").text(new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format($("#DescuentoCompraDinero").val()));
				}
			}
		}
	});

	function CalcularSubtotal(){
		$("#cantidadProductosSpan").text($("#tbodyTablaProductosAgregados tr").length);
		var total = 0;
		$("#tbodyTablaProductosAgregados tr").each(function(){
			var totalFilas = parseFloat($(this).children("td:eq(5)").text());
			total += totalFilas;
		});

		$("#MostrarSubtotal").html('<span class="dinero">'+(Math.round(total * 100) / 100)+'</span>');

		CalcularTotal();
	}

	function CalcularTotal() {
		const searchRegExp = new RegExp(',', 'g');
	    var total = parseFloat($("#MostrarSubtotal").text().replace('$', '').replace(searchRegExp, ''));
	        
	    if($("#DescuentoCompraDinero").val() != "" && parseFloat($("#DescuentoCompraDinero").val()) > 0){
	    	total -= parseFloat($("#DescuentoCompraDinero").val());
	    }
	    var totalfinal = Math.round(total*100)/100;
	    $("#TotalCompra").html('<span class="dinero">'+(Math.round(totalfinal * 100) / 100)+'</span>');

	    moneda();
	}

	$(document).on('click', '#TipoCompra', function() {
		if ($("#TipoCompra").val() == "Credito") {
			id = $("#RealizarCompra").attr('idproveedor'); 
			var data = "metodo=consultar&accion=hacerCompra&tipo=creditoProveedor&IDProveedor="+id;
			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: data,
			})
			.done(function(res) {
				//console.log(res);
				var datos = JSON.parse(res)
				var credito = '';
				if (datos.data.Credito == 'NO' || datos.data.Credito == '' || datos.data.Credito == null){
					credito = 'No ofrece crédito';
				}else if (datos.data.Credito == 'SI'){
					credito = '$0';
				}else{
					credito = '$' + datos.data.Credito;
				}
				$("#MostrarCreditoProveedor").text(credito);
				$("#MostrarCreditoRestante").text('$' + datos.data.RestanteCredito);
				$("#MostrarCreditoProveedor").attr("credito", datos.data.Credito);
				$("#LimiteCredito").attr("hidden", false);
				$("#FechaLimiteCredito").attr("hidden", false);
				$("#LimiteCreditoRestante").attr("hidden", false);
				
			})
			.fail(function() {
				console.log("Error ajax");
			});
		}else if($("#TipoCompra").val() == "Contado"){
			console.log("contado");
			$("#LimiteCredito").attr("hidden", true);
			$("#FechaLimiteCredito").attr("hidden", true);
		}
	});

	$(document).on('change', '#TipoCompra', function() {
		if ($("#TipoCompra").val() == "Credito") {
			id = $("#RealizarCompra").attr('idproveedor'); 
			var data = "metodo=consultar&accion=hacerCompra&tipo=creditoProveedor&IDProveedor="+id;
			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: data,
			})
			.done(function(res) {
				//console.log(res);
				var datos = JSON.parse(res)
				var credito = '';
				if (datos.data.Credito == 'NO' || datos.data.Credito == '' || datos.data.Credito == null){
					credito = 'No ofrece crédito';
				}else if (datos.data.Credito == 'SI'){
					credito = '$0';
				}else{
					credito = '$' + datos.data.Credito;
				}
				$("#MostrarCreditoProveedor").text(credito);
				$("#MostrarCreditoRestante").text('$' + datos.data.RestanteCredito);
				$("#MostrarCreditoProveedor").attr("credito", datos.data.Credito);
				$("#LimiteCredito").attr("hidden", false);
				$("#FechaLimiteCredito").attr("hidden", false);
				$("#LimiteCreditoRestante").attr("hidden", false);
				
			})
			.fail(function() {
				console.log("Error ajax");
			});
		}else if($("#TipoCompra").val() == "Contado"){
			$("#LimiteCredito").attr("hidden", true);
			$("#FechaLimiteCredito").attr("hidden", true);
		}
	});

	$(document).on('click', '#GuardarCompra', function() {
		const searchRegExp = new RegExp(',', 'g');
		var total = $("#TotalCompra").text().replace("$", "").replace(searchRegExp, '');
		var ImportePagadoCompra = parseFloat($("#ImportePagadoCompra").val());
		var idProveedor = $("#RealizarCompra").attr("idProveedor");
		var descuento = $("#Descuento").text().replace("$", "").replace(searchRegExp, '');
		var subtotal = $("#MostrarSubtotal").text().replace("$", "").replace(searchRegExp, '');
		var tipoPago = $('#TipoPago').val();
		var detalles = $('#DetallesPago').val();
		var tipoCompra = $('#TipoCompra').val();
		var fechaCredito = $('#fechaCredito').val();
		
		if($("#ImportePagadoCompra").val() == "" || (tipoCompra == 'Contado' && ImportePagadoCompra < parseFloat($("#Total").text().replace("$", "").replace(searchRegExp, '')))){
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'El importe pagado dede cubrir la totalidad de la compra'
			});
		}else {
			var productos = [];
			$("#tbodyTablaProductosAgregados tr").each(function(){
				var idProducto = $(this).attr("attrid");
				var Sucursal = $('#Sucursales').val();
				var Presentacion = $(this).attr("idPresentacion");
				var Costo = $(this).children("td:eq(3)").find(".campoCosto").val();
				var Cantidad = $(this).children("td:eq(4)").find(".campoCantidad").val();
				productos.push([idProducto,Costo,Cantidad,Presentacion,Sucursal])
			});

			if(tipoCompra == 'Contado'){
				estatus = '1';
			}else if(tipoCompra == 'Credito'){
				estatus = '0';
			}

			var data = new FormData(document.getElementById('FormCobrarCompra'));
			data.append('metodo', 'insertar');
			data.append('accion', 'hacerCompra');
			data.append('Importe', ImportePagadoCompra);
			data.append('idProveedor', idProveedor);
			data.append('FechaCredito', fechaCredito);
			data.append('Productos', JSON.stringify(productos));
			data.append('subtotal', subtotal);
			data.append('total', total);
			data.append('TipoCompra', tipoCompra);
			data.append('Descuento', descuento);
			data.append('TipoPago', tipoPago);
			data.append('Sucursal', $('#Sucursales').val());

			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: data,
				processData: false,
				contentType: false
			})
			.done(function(res) {
				var datos = res.split("~");
				if ($.trim(datos[0]) == "Correcto") {
					Swal.fire({
						icon: 'success',
						title: 'Compra realizada correctamente'
					});
					$("#ModalCobrarCompra").modal("hide");
					$('#cargarHacerCompra').trigger('click');
					$("#RealizarCompra").attr("idProveedor", '1');
					
					var idCompra = datos[1];
					var sucursal = datos[2];
					var altura=50;
					var anchura=310;

					var y= parseInt((window.screen.height/2)-(altura/2));
					var x= parseInt((window.screen.width/2)-(anchura/2));

					window.open("controladores/ticketCompra.php?id="+idCompra+"&idSucursal="+sucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
					
				}else{
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Error inesperado al registrar la compra.'
					});

					console.log($.trim(res));
				}
			})
			.fail(function() {
				console.log("Error ajax");
			});
		}
	});
});



function TablaProductosCompra(){
	ajaxMyDatatable({
		"table": $("#TablaProductosCompra"), 
		"colums": [
			"Producto",
			"Descripcion",
			"Costo",
			"Presentacion"
		], 
		"sort": [
			1,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"tipo": "ConsultarProductos",
			"accion": "hacerCompra"
		}
	});
}

function TablaProveedoresCompra(){
	ajaxMyDatatable({
		"table": $("#TablaProveedoresCompra"), 
		"colums": [
			"Nombre",
			"Direccion",
			"Empresa",
			"Credito"
		], 
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"tipo": "ConsultarProveedores",
			"accion": "hacerCompra"
		}
	});
}