function v_hacerventa() {
	//TablaReporteCompras();
}

jQuery(document).ready(function($) {

	$(document).on('click', '#CargarClientesModalVentas', function() {
		TablaClienteVenta();
		$("#ModalVerClientesVenta").modal("show");
	});

	$(document).on('click', '#TablaClienteVenta tbody tr', function() {
		var idcliente = $(this).attr("id");
		var nombre = $(this).children("td:eq(0)").text();
		var RFC = $(this).children("td:eq(2)").text();
		$("#ModalVerClientesVenta").modal("hide");
		$("#CargarClientesModalVentas").html("Cliente: "+nombre+"<br>RFC: "+RFC);
		$("#CargarClientesModalVentas").attr("attrid", idcliente);
	});

	$(document).on('click', '#CargarProductosModalVentas', function() {
		VentaTablaProductos();
	});


	$(document).on('submit', '#FormAgregarProductoVenta', function(event) {
        event.preventDefault();

        var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+$("#CodigoProductoVenta").val()+"&sucursal="+$("#SucursalVenta").val();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            $("#CodigoProductoVenta").val("");
            if($.trim(res) == "No encontrado"){
               alert("No encontrado");
            }else{       
            	var datos = JSON.parse($.trim(res));
            	console.log(datos);
                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').length > 0){                 
                	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
                 	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + 1);
                	$(".campoCantidadProducto").trigger("change");
                }else{
                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br>`+datos.Presentacion+`</td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.Precio_General+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Precio_General+`</button></td>
	                        <td><input type='number' value='1' min='1' step='any' class='form-control campoCantidadProducto'></td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
		                        <input type="number" value="0" min="0" max="100" step="any" class="form-control campoDescuentoProducto">
	                        </td>
	                        <td class="dinero"></td>
	                        <td><button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
	                    </tr>`);
                	$(".campoCantidadProducto").trigger("change");
                }
                moneda();
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
    });

    $(document).on('click', '#VentaTablaProductos tbody tr', function() {
    	var codigo = $(this).children("td:eq(0)").find("#CodigoProducto").text();
    	var presentacion = $(this).children("td:eq(1)").find("#IdPresentacionProd").text();
    	var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+codigo+"&sucursal="+$("#SucursalVenta").val()+"&presentacion="+presentacion;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            $("#CodigoProductoVenta").val("");
            if($.trim(res) == "No encontrado"){
               alert("No encontrado");
            }else{       
            	var datos = JSON.parse($.trim(res));
            	console.log(datos);
                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                if($("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').length > 0){                 
                	var cantidad = $("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val();
                 	$("#TablaProductosAgregadoVenta").children('tbody').children('tr[attrID='+datos.ID_Producto+'][idPresentacion='+datos.IDPresentacion+']').children("td:eq(3)").find(".campoCantidadProducto").val(parseFloat(cantidad) + 1);
                	$(".campoCantidadProducto").trigger("change");
                }else{
                    $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+` <br>`+datos.Presentacion+`</td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" precio="`+datos.Precio_General+`" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Precio_General+`</button></td>
	                        <td><input type='number' value='1' min='1' step='any' class='form-control campoCantidadProducto'></td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td>
		                        <input type="number" value="0" min="0" max="100" step="any" class="form-control campoDescuentoProducto">
	                        </td>
	                        <td class="dinero"></td>
	                        <td><button class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-trash"></i></button></td>
	                    </tr>`);
                	$(".campoCantidadProducto").trigger("change");
                }
                moneda();
                $("#ModalVerProductosVenta").modal("hide");
                $("#CodigoProductoVenta").focus();
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        });
    });

	$(document).on('keyup change', '.campoCantidadProducto', function() {
		var precio = $(this).parent().parent().children("td:eq(2)").find(".cambiarPrecio").attr("precio");
		var cantidad = $(this).parent().parent().children("td:eq(3)").find(".campoCantidadProducto").val();
		var subtotal = parseFloat(precio) * parseFloat(cantidad);
		var totalImpuestos = 0;
		$(this).parent().parent().children("td:eq(4)").find(".impuesto").each(function(index, el) {
			var impuesto = $(this).find(".seleccionarImpuesto");
			if (impuesto.prop("checked") == true) {
				var porcentaje = parseFloat(impuesto.attr("porcentaje")) / 100;
				totalImpuestos += parseFloat(subtotal) * parseFloat(porcentaje);
			}
		});

		var descuento = parseFloat($(this).parent().parent().children("td:eq(5)").find(".campoDescuentoProducto").val()) / 100;
		if (isNaN(descuento)) {
			descuento = 0;
		}
		var total =(parseFloat(subtotal) + parseFloat(totalImpuestos));
		var montoDescuento = parseFloat(descuento) * parseFloat(total);
		$(this).parent().parent().children("td:eq(6)").text(parseFloat(total) - parseFloat(montoDescuento));
		CalcularSubtotalVenta();
	});

	$(document).on('click', '.seleccionarImpuesto', function() {
		$(".campoCantidadProducto").trigger("change");
	});

	$(document).on('change keyup', '.campoDescuentoProducto', function() {
		$(".campoCantidadProducto").trigger("change");
	});

	$(document).on('click', '.eliminarFila', function() {
		$(this).parent().parent().remove();
		CalcularSubtotalVenta();
    		/*$("#DescuentoCompraDinero").val($("#RealizarCompra").attr("cantidad"));
    		$("#DescuentoCompraDinero").trigger("change");*/
	});

	function CalcularSubtotalVenta(){
		if ($("#TablaProductosAgregadoVenta tbody tr").length > 0) {
			$("#SucursalVenta").attr("disabled", true);
		}else{
			$("#SucursalVenta").attr("disabled", false);	
		}
		var subtotal = 0;
		$("#TablaProductosAgregadoVenta tbody tr").each(function(index, el) {
			subtotal += parseFloat($(this).children("td:eq(6)").text().replace("$","").replace(",",""));
		});
		$("#MostrarSubtotalVenta").text(subtotal);
		$("#cantidadProductosSpanVenta").text($("#TablaProductosAgregadoVenta tbody tr").length)
		moneda();
	}

	/*function TotalFinalVenta() {
        var suma = 0, contador = 0;
        $("#nav-tabContent .active #tablaCaja").children('tbody').children('tr').each(function(index, el) {
            suma += parseFloat($(this).children('td:eq(5)').children('span.dinero').text().replace('$', '').replace(',', ''));
            contador ++;
        });

        $("#nav-tabContent .active #totalCaja").html(suma);
        $("#nav-tabContent .active #cantidadCajaProd").html(contador);

        moneda();
    }*/


	
});

function TablaClienteVenta(){ 
	console.log("entro");
	ajaxMyDatatable({
		"table": $("#TablaClienteVenta"), 
		"colums": [
			"Nombre",
			"Direccion",
			"RFC",
			"Contacto"
		],
		"sort": [
			0,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"accion": "hacerventa",
			"tipo": "ConsultarCliente"
		}
	});
}

function VentaTablaProductos(){
	ajaxMyDatatable({
		"table": $("#VentaTablaProductos"), 
		"colums": [
			"Producto",
			"Presentacion",
			"Precio",
			"Mayoreo",
			"Existencia"
		], 
		"sort": [
			1,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "detalles",
			"tipo": "ConsultarProductos",
			"accion": "hacerventa",
			"sucursal": $("#SucursalVenta").val()
		}
	});
}


function TablaReporteCompras(){
	ajaxMyDatatable({
		"table": $("#TablaReporteCompras"), 
		"colums": [
			"Datos",
			"Proveedor",
			"Total",
			"Detalles",
			"Acciones"
		], 
		"totals":[
			"Datos",
			"Proveedor",
			"Total",
			"Detalles",
			"Acciones"
		],
		"sort": [
			2,
			"desc"
		],
		"url": "index.php", 
		"params":{
			"metodo": "consultar",
			"accion": "compras"
		}
	});
}
