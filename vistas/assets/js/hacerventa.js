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
                 	console.log("no");
                 }else{
                 	 $('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+`</td>
	                        <td>`+datos.Presentacion+`</td>
	                        <td><button class="btn btn-sm btn-primary cambiarPrecio dinero" attrid="`+datos.ID_Producto+`" idPresentacion="`+datos.IDPresentacion+`">`+datos.Precio_General+`</button></td>
	                        <td><input type='number' value='1' min='1' step='any' class='form-control campoCantidadProducto'></td>
	                        <td>`+datos.Impuestos+`</td>
	                        <td></td>
	                        <td></td>
	                    </tr>`);
                 }
                 moneda();


               
               /* console.log($('#TablaProductosAgregadoVenta tbody tr').length);
            	$('#TablaProductosAgregadoVenta tbody tr').each(function(index, el) {
            		console.log($(this));
            		/*if ($(this).attr("attrid") == datos.ID_Producto && $(this).attr("idPresentacion") == datos.IDPresentacion) {

	             	}else{
	             		console.log("entro 2");
	             		$('#TablaProductosAgregadoVenta tbody').append(`
	             		<tr attrid="`+datos.ID_Producto+`" precio="`+precio+`"  precioMayoreo="`+precioMayoreo+`" impuestos="`+impuestos+`" class="activa normal"  idPresentacion="`+datos.IDPresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+`</td>
	                        <td>`+datos.Presentacion+`</td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                    </tr>`);

	                    /*
						$('#TablaProductosAgregados tbody tr').append(`
	             		<tr attrid="`+datos.ID_Producto+`" precio="`+precio+`"  precioMayoreo="`+precioMayoreo+`" impuestos="`+impuestos+`" class="activa normal"  idPresentacion="`+datos.IDPresentacion+`">
	                        <td>`+datos.Codigo+`</td>
	                        <td>`+datos.Descripcion+`</td>
	                        <td>`+datos.Presentacion+`</td>
	                        <td><span class="dinero">`+precio+`</span></td>
	                        <td><span class="cantidad">1</span></td>
	                        <td><span class="dinero">0</span>(<span class="porcentaje">0</span>)</td>
	                        <td><span class="dinero">`+precio+`</span></td>
	                        <td><span class="cantidad">`+existencia+`</span></td>
	                    </tr>`);
	                    */
	             	//}
            	//});
             	
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
    });


	
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
