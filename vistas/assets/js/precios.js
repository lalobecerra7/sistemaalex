function v_precios() {
	tablaPrecios();
	tablaProductosPrecios();
}

function tablaProductosPrecios() {
	ajaxMyDatatable({
        "table": $("#tablaProductosPrecios"), 
        "colums": [
            "Imagen",
			"Codigo",
			"Descripcion",
			"Acciones"
        ],
        "sort": [
            1,
            "asc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "precios",
            "tipo": "productos"
        }
    });
}

function tablaPrecios() {
	ajaxMyDatatable({
        "table": $("#tablaPrecios"), 
        "colums": [
            "Producto",
			"Presentacion",
			"Proveedores",
			"Nombre",
			"Precio",
			"Mayoreo"
        ],
        "sort": [
            0,
            "asc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "precios",
            "zona": $("#zonaPrecio").val(),
            "tipoProd": $("#tipoProdPrecio").val(),
            "producto": $("#productoPrecio").attr('attrID')
        }
    });
}

jQuery(document).ready(function($) {
	$(document).on('change', '#tipoProdPrecio', function() {
		if($(this).val() == "Todos"){
			$("#buscarProductoPrecio").addClass('oculto');
			tablaPrecios();
		}else{
			$("#productoPrecios").val("");
			$("#buscarProductoPrecio").removeClass('oculto');
		}
	});

	$(document).on('dblclick', '#tablaPrecios tbody td', function() {
		if($(this).children('span.dinero').text() != ""){
			const searchRegExp = new RegExp(',', 'g'); 

			$(this).html('<input type="number" style="width: 100px;" class="inputPrecio" value="'+$(this).text().replace('$', '').replace(searchRegExp, '')+'">');
			$(this).children('input.inputPrecio').focus();
		}
	});

	$(document).on('focusout', '.inputPrecio', function() {
		var input = $(this);
		var padre = $(this).parent();
		
		var tipoPrecio = '';
		if($(this).parent().index() == 4){
			tipoPrecio = 'Precio';
		}else if($(this).parent().index() == 5){
			tipoPrecio = 'Precio_Mayoreo';
		}

		var data = "metodo=modificar&accion=precios&tipo=precio&valor="+$(this).val()+"&tipoPrecio="+tipoPrecio+"&id="+padre.parent().attr('id');
	
		$.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            if ($.trim(res) == "Correcto") {
                padre.html('<span class="dinero">'+input.val()+'</span>');
				moneda();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inesperado al cambiar el precio.'
                });

                console.log($.trim(res));
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
	});

	$(document).on('change', '#zonaPrecio', function() {
		tablaPrecios();
	});

	$(document).on('click', '#bBuscarProductoPrecio', function() {
		$("#modalProductosPrecios").modal("show");
	});

	$(document).on('click', '.bSeleccionarProdPrecio', function() {
		var padre = $(this).parent().parent();
		$("#productoPrecio").val(padre.children('td:eq(2)').text());
		$("#productoPrecio").attr('attrID', $(this).attr('attrID'));
		$("#modalProductosPrecios").modal("hide");
		tablaPrecios();
	});
});