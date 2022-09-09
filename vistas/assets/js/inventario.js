function v_inventario() {
    TablaInventario();
    $('#FormTraslados').validate({
        rules: {
            FechaTraslado: {
                required: true
            },
            SucursalOrigen: {
                required: true
            },
            SucursalDestino: {
                required: true
            },
            Cantidad: {
                required: true
            },
        },
        messages: {
            FechaTraslado: {
                required: "La fecha de traslado es obligatoria"
            },
            SucursalOrigen: {
                required: "La sucursal de origen es obligatoria"
            },
            SucursalDestino: {
                required: "La sucursal de destino es obligatoria"
            },
            Cantidad: {
                required: "La cantidad es obligatoria"
            },
        },
        submitHandler: function(form) {
            if ($("#SucursalOrigen").val() == $("#SucursalDestino").val()){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La sucursal de origen y destino deben ser diferentes.'
                });
            }else{
                var data = "metodo=insertar&accion=inventario&tipo=traslados&IDProducto=" + $("#GuardarTraslado").attr("attrid") + "&FechaTraslado=" + $("#FechaTraslado").val() + "&SucursalOrigen=" + $("#SucursalOrigen").val() + "&SucursalDestino=" + $("#SucursalDestino").val() + "&Cantidad=" + $("#Cantidad").val();
                var btn = $('#GuardarTraslado');
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
                            Swal.fire({
                                icon: 'success',
                                title: 'El traslado se ha registrado correctamente'
                            });
                            TablaInventario();
                            $("#FormTraslados").trigger('reset');
                            $("#ModalTraslados").modal("hide");
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al insertar el traslado.'
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
                
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '.verDetallesMerma', function() {
        var idproducto = $(this).attr("attrid");
        TablaMerma(idproducto);
        $("#ModalDetalles").modal("show");
        $("#CerrarDetalle").attr("attrid", idproducto);
        $('#NombreProductoM').text($(this).attr("nombre"));
    });

    $(document).on('click', '#AgregarMerma', function() {
        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);

        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
       
        $('#FechaMerma').val(today);
        $('#NombreProductoAM').text($(this).attr("nombre"));
        $("#GuardarMerma").attr('attrid',$(this).attr('attrid'));
        $("#ModalMerma").modal("show");
    });

    $(document).on('click', '#EliminarMerma', function() {
        var id = $("#GuardarMerma").attr('attrid');
        Swal.fire({
	        title: '¿Estás seguro que quieres eliminar este registro de merma?',
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        cancelButtonText: '¡No, cancelar!',
	        confirmButtonText: '¡Si, eliminar!'
	    }).then((result) => {
	        if (result.value) {

	        	var regresarInventario = "";
	        	Swal.fire({
			        title: '¿Regresar los productos al inventario?',
			        icon: 'warning',
			        showCancelButton: true,
			        confirmButtonColor: '#3085d6',
			        cancelButtonColor: '#d33',
			        cancelButtonText: 'No, continuar',
			        confirmButtonText: 'Si, regresar'
			    }).then((result) => {

			        if (result.value) {
			        	regresarInventario = "Si";
			        }else{
			        	regresarInventario = "No";
			        }

			        var data = "metodo=eliminar&accion=inventario&tipo=EliminarMerma&IDMerma="+$(this).attr('attrid')+"&RegresarInventario="+regresarInventario;
					$.ajax({
						url: 'index.php',
						type: 'POST',
						data: data
					})
					.done(function(res) {
						if ($.trim(res) == "Correcto") {
							Swal.fire({
								icon: 'success',
								title: 'Merma eliminada correctamente'
							});
							TablaMerma(id);
							TablaInventario();
						}else{
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Error inesperado al eliminar la merma.'
							});
							console.log($.trim(res));
						}
					})
					.fail(function() {
						console.log("Error ajax");
					})
			    });
			}    
		});	  
    });

    $(document).on('click', '#Traslados', function() {
        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);

        var today = now.getFullYear()+"-"+(month)+"-"+(day);
       
        $('#FechaTraslado').val(today);
        $('#NombreProductoT').text($(this).attr("nombre"));
        $("#ModalTraslados").modal("show");
        $("#GuardarTraslado").attr('attrid',$(this).attr('attrid'));
    });

    $(document).on('click', '#ModificarMerma', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=inventario&tipo=consultarMerma&IDMerma="+id;
        $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
            })
            .done(function(res) {
                var datos = JSON.parse($.trim(res));
                console.log(datos);
                $("#FechaMermaE").val(datos.Fecha_Merma);
                $("#MotivoMermaE").val(datos.Motivo);
            })
            .fail(function() {
                console.log("Error ajax");
            });

        $("#ModalEditarMerma").modal("show");
        $("#GuardarMermaE").attr('attrid',$(this).attr('attrid'));
    });

    $(document).on('change', '#SucursalOrigen', function() {
        var id = $(this).val();
        var producto = $("#GuardarTraslado").attr('attrid');
        var data = "metodo=detalles&accion=inventario&tipo=cantidadTraslado&IDProducto="+producto+"&IDSucursal="+id;
        $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
            })
            .done(function(res) {
               $('#Cantidad').attr('max', $.trim(res));
               $('#Cantidad').val($.trim(res));
               $("#SucursalDestino").find('option').not(':first').remove();
               var data = "metodo=detalles&accion=inventario&tipo=sucursales&IDSucursal="+$('#SucursalOrigen').val();
               $.ajax({
                       url: 'index.php',
                       type: 'POST',
                       data: data,
                   })
                   .done(function(res) {
                    console.log(res);
                      $('#SucursalDestino').append(res);
                   })
                   .fail(function() {
                       console.log("Error ajax");
                   });
            })
            .fail(function() {
                console.log("Error ajax");
            });
    });


    $(document).on('click', '#GuardarMerma', function() {
        var $id = $('#GuardarMerma').attr("attrid");
        $('#FormMerma').validate({
            rules: {
                CantidadMerma: {
                    required: true
                },
                MotivoMerma: {
                    required: true
                },
                Sucursal: {
                    required: true
                },
                FechaMerma: {
                    required: true
                },
            },
            messages: {
                CantidadMerma: {
                    required: "La cantidad de merma es requerida"
                },
                MotivoMerma: {
                    required: "El motivo de la merma es requerido"
                },
                Sucursal: {
                    required: "La sucursal es requerida"
                },
                FechaMerma: {
                    required: "La fecha de merma es requerida"
                },
            },
            submitHandler: function(form) {
                var data = "metodo=insertar&accion=inventario&tipo=agregarMerma&IDProducto=" + $id + "&Cantidad=" + $("#CantidadMerma").val() + "&Motivo=" + $("#MotivoMerma").val() + "&IDSucursal=" + $("#Sucursal").val() + "&FechaMerma=" + $("#FechaMerma").val();
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
                            Swal.fire({
                                icon: 'success',
                                title: 'La merma se ha registrado correctamente'
                            });
                            TablaInventario()
                            $("#FormMerma").trigger('reset');
                            $("#ModalMerma").modal("hide");
                        } else if ($.trim(res) == "ErrorCantidad") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'La cantidad de la merma no debe ser mayor a la existencia.'
                            });
                            $("#Cantidad").focus();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al insertar la merma.'
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
    });

    $(document).on('click', '#GuardarMermaE', function() {
        $('#FormEditarMerma').validate({
            rules: {
                FechaMermaE: {
                    required: true
                },
                MotivoMermaE: {
                    required: true
                },
            },
            messages: {
                FechaMermaE: {
                    required: "La cantidad de producto es requerida"
                },
                MotivoMermaE: {
                    required: "El costo del producto es requerido"
                },
            },
            submitHandler: function(form) {
                var data = "metodo=detalles&accion=inventario&tipo=editarMerma&IDMerma=" + $('#GuardarMermaE').attr('attrid') + "&FechaMerma=" + $("#FechaMermaE").val() + "&MotivoMerma=" + $("#MotivoMermaE").val();
                $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: data
                    })
                    .done(function(res) {
                        console.log($.trim(res));
                        if ($.trim(res) == "Correcto") {
                            Swal.fire({
                                icon: 'success',
                                title: 'La merma se ha modificado correctamente'
                            });
                            
                            TablaMerma($('CerrarDetalle').attr('attrid'));
                            $("#ModalEditarMerma").modal("hide");
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al editar la merma.'
                            });

                            console.log($.trim(res));
                        }
                    })
                    .fail(function() {
                        console.log("Error ajax");
                    })
            }
        });
    });
});

function TablaInventario() {
    ajaxMyDatatable({
        "table": $("#TablaInventario"),
        "colums": [
            "Producto",
            "Descripcion",
            "Detalles",
            "Costo",
            "TotalCosto",
            "Precio",
            "TotalPrecio",
            "Merma",
            "Acciones"
        ],
        "sort": [
            1,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "consultar",
            "accion": "inventario"
        }
    });
}

function TablaMerma(idproducto) {
    ajaxMyDatatable({
        "table": $("#TablaMerma"),
        "colums": [
            "Fecha",
            "Motivo",
            "Sucursal",
            "Cantidad",
            "Costo",
            "Acciones"
        ],
        "sort": [
            1,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "accion": "inventario",
            "tipo": "merma",
            "id": idproducto
        }
    });
}