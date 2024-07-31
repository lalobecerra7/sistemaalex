function v_precios() {
	tablaPrecios();
	tablaProductosPrecios();

  $('#FormPreciosExcel').validate({
        rules: {
            ZonaCargarPrecioProducto: {
                required: true
            },
            ExcelPrecios: {
                required: true
            },
        },
        messages: {
            ZonaCargarPrecioProducto: {
                required: "La zona es requerida."
            },
            ExcelPrecios: {
                required: "El excel es requerido."
            },
        },
        submitHandler: function(form) { 
          Swal.fire({
            title: '¿Estas a punto de subir un excel de precios?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, guardar',
            cancelButtonText: 'Cancelar',
          }).then((result) => {
            if (result.value) {
              var data = new FormData(document.getElementById("FormPreciosExcel"));
              data.append("Zonas", $("#ZonaCargarPrecioProducto").val());
              $.ajax({
                url: 'controladores/excel/readExcel.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                  $("#carga").show();
                }
              })
              .done(function(res) {
                console.log(res);
                if ($.trim(res).endsWith('Correcto')) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Excel de precios cargado correctamente'
                  });
                  $("#ModalCargarPreciosDeiman").modal("hide");
                  $("#FormPreciosExcel").trigger("reset");
                }else if ($.trim(res) == "Error 2 Formato") {
                  Swal.fire({
                    icon: 'warning',
                    title: 'El formato del archivo no es correcto, el formato permitido es .xlsx',
                  });
                }else if ($.trim(res) == "Error 3 Peso") {
                  Swal.fire({
                    icon: 'warning',
                    title: 'El tamaño del archivo excedio el peso maximo permitido, el peso maximo es de 10MB.',
                  });
                }else{
                  Swal.fire({
                    icon: 'warning',
                    title: '¡Error al subir el excel de precios!',
                  });
                  console.log(res);
                }
              })
              .fail(function() {
                console.log("Error ajax");
                console.log(res);
              })
              .always(function() {
                $("#carga").hide();
              });
            }
         });
        }
  });  

    $('#FormAgregarPrecioNuevo').validate({
        rules: {
            ReferenciaPrecioNuevo: {
                required: true
            },
            Precio3PrecioNuevo: {
                required: true
            },
            AumentoPrecioNuevo: {
                required: true
            },
            ZonaPrecioNuevo: {
                required: true
            },
        },
        messages: {
            ReferenciaPrecioNuevo: {
                required: "La referencia es requerida."
            },
            Precio3PrecioNuevo: {
                required: "El precio 3 es requerido."
            },
            AumentoPrecioNuevo: {
                required: "El porcentaje de aumento es requerido."
            },
            ZonaPrecioNuevo: {
                required: "La zona del precio es requerida."
            },
        },
        submitHandler: function(form) { 
            if ($("#ImpuestosPrecioNuevo").val() == "") {
                Swal.fire({
                    icon: 'info',
                    title: 'Nuevo precio agregado correctamente'
                });
            }
            var data = new FormData(document.getElementById('FormAgregarPrecioNuevo'));
            data.append('metodo', "detalles");
            data.append('accion', 'precios');
            data.append('tipo', 'InsertarNuevoPrecio3');

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                console.log(res);
                if ($.trim(res) == "Correcto") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Precios agregados correctamente'
                    });
                    $("#FormAgregarPrecioNuevo").trigger("reset");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al agregar el nuevo precio.'
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
        }              
    });


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
            "Margen",
            "Costo"
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

    $(document).on('click', '#CargarPreciosDeiman', function() {
        $("#ModalCargarPreciosDeiman").modal("show");
    });
});