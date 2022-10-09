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
            PresentacionProductoTraslado: {
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
            PresentacionProductoTraslado: {
                required: "La presentación del producto es requerida"
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
                var data = "metodo=insertar&accion=inventario&tipo=traslados&IDProducto=" + $("#GuardarTraslado").attr("attrid") + "&FechaTraslado=" + $("#FechaTraslado").val() + "&SucursalOrigen=" + $("#SucursalOrigen").val() + "&SucursalDestino=" + $("#SucursalDestino").val() + "&Cantidad=" + $("#Cantidad").val()+"&Presentacion="+$("#PresentacionProductoTraslado").val();
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
                            TablaTraslados($("#GuardarTraslado").attr("attrid"));
                            //$("#ModalTraslados").modal("hide");
                        } else if($.trim(res) == "ErrorExistencia"){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'No hay existencias suficientes para realizar el traslado.'
                            });
                            console.log($.trim(res));
                        }else {
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


    $('#FormConversionProducto').validate({
        rules: {
            PresentacionesProductoOrigen: {
                required: true
            },
            CantidadPresentacionOrigen: {
                required: true
            },
            PresentacionesProductoDestino: {
                required: true
            },
            CantidadPresentacionDestino: {
                required: true
            },
        },
        messages: {
            PresentacionesProductoOrigen: {
                required: "La presentación de origen es obligatoria"
            },
            CantidadPresentacionOrigen: {
                required: "La cantidad es obligatoria"
            },
            PresentacionesProductoDestino: {
                required: "La presentación de destino es obligatoria"
            },
            CantidadPresentacionDestino: {
                required: "La cantidad es obligatoria"
            },
        },
        submitHandler: function(form) {
            var idProducto = $("#GuardarConversionProducto").attr("attrid");
            var SucursalOrigen = $("#SucursalOrigenPresentacion").val();
            var SucursalDestino = $("#SucursalDestinoPresentacion").val();
            var PresentacionesProductoOrigen = $("#PresentacionesProductoOrigen").val();
            var CantidadPresentacionOrigen = $("#CantidadPresentacionOrigen").val();
            var PresentacionesProductoDestino = $("#PresentacionesProductoDestino").val(); 
            var CantidadPresentacionDestino = $("#CantidadPresentacionDestino").val();
            var data = "metodo=insertar&accion=inventario&tipo=GuardarConversion&IDProducto="+idProducto+"&Origen="+PresentacionesProductoOrigen+"&CantidadOrigen="+CantidadPresentacionOrigen+"&Destino="+PresentacionesProductoDestino+"&CantidadDestino="+CantidadPresentacionDestino+"&SucursalOrigen="+SucursalOrigen+"&SucursalDestino="+SucursalDestino;
            var btn = $('#GuardarConversionProducto');
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
                        title: 'Conversión registrada correctamente'
                    });
                    TablaInventario();
                    $("#FormConversionProducto").trigger('reset');
                    $("#ModalConversionProducto").modal("hide");
                    $("#PresentacionesProductoOrigen").html("");
                    $("#PresentacionesProductoDestino").html("");
                }else if($.trim(res) == "ErrorExistencia"){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No hay existencias suficientes para realizar la conversión.'
                    });
                    console.log($.trim(res));
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al insertar el conversion.'
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
        $("#verFotoMerma img").attr('src', 'vistas/assets/archivos/defaultImagen.jpg');
        $("#ModalMerma").modal("show");
        $("#FormMerma").trigger("reset");
        $("#PresentacionProductoMerma").html('<option value="">- Seleccione una opción -</option>');
    });

    $(document).on('click', '#verFotoMerma', function() {
        $("#FotoMerma").trigger("click");
    });

    $(document).on('change', '#FotoMerma', function() {
        readURL(this, $("#verFotoMerma"));
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
        var idProducto = $(this).attr('attrid');
        $('#NombreProductoT').text($(this).attr("nombre"));
        $("#ModalTraslados").modal("show");
        TablaTraslados(idProducto);
        $("#GuardarTraslado").attr('attrid',$(this).attr('attrid'));
        $("#FormTraslados").trigger("reset");
        $("#PresentacionProductoTraslado").html('<option value="">- Seleccione una opción -</option>');
        $('#FechaTraslado').val(today);
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

        var data = "metodo=detalles&accion=inventario&tipo=ConsultarPresentacionOrigen&IDProducto="+producto+"&IDSucursal="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionProductoTraslado").html(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '#ConvertirProducto', function() {
        $("#FormConversionProducto").trigger("reset");
        $("#PresentacionesProductoOrigen").html("<option value=''> Seleccione una opción </option>");
        $("#PresentacionesProductoDestino").html("<option value=''> Seleccione una opción </option>");

        var id = $(this).attr("attrid");

        var data = "metodo=detalles&accion=inventario&tipo=ConsultarSucursalesConversion&IDProducto="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#GuardarConversionProducto").attr("attrid", id);
            $("#SucursalOrigenPresentacion").html(res);
            $("#SucursalDestinoPresentacion").html(res);
            $("#ModalConversionProducto").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
        
    });

    $(document).on('change', '#SucursalOrigenPresentacion', function() {
        var idSucursal = $(this).val();
        var idProducto = $("#GuardarConversionProducto").attr("attrid");
        var data = "metodo=detalles&accion=inventario&tipo=ConsultarPresentacionOrigen&IDProducto="+idProducto+"&IDSucursal="+idSucursal;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionesProductoOrigen").html(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('change', '#SucursalDestinoPresentacion', function() {
        var idSucursal = $(this).val();
        var idProducto = $("#GuardarConversionProducto").attr("attrid");
        var idPresentacion = $("#PresentacionesProductoOrigen").val(); 
        var data = "metodo=detalles&accion=inventario&tipo=ConsultarPresentacionDestino&IDProducto="+idProducto+"&IDSucursal="+idSucursal+"&IDPresentacion="+idPresentacion;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionesProductoDestino").html(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('change','#PresentacionesProductoOrigen', function() {
        var id = $('#GuardarConversionProducto').attr("attrid");
        var idPresentacion = $(this).val();
        var idSucursal = $('#SucursalDestinoPresentacion').val();

        var data = "metodo=detalles&accion=inventario&tipo=ConsultarPresentacionDestino&IDProducto="+id+"&IDPresentacion="+idPresentacion+"&IDSucursal="+idSucursal;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionesProductoDestino").html(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '#GuardarMerma', function() {
        var id = $('#GuardarMerma').attr("attrid");
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
                //var data = "metodo=insertar&accion=inventario&tipo=agregarMerma&IDProducto=" + id + "&Cantidad=" + $("#CantidadMerma").val() + "&Motivo=" + $("#MotivoMerma").val() + "&IDSucursal=" + $("#Sucursal").val() + "&FechaMerma=" + $("#FechaMerma").val();
                var data = new FormData(document.getElementById("FormMerma"));
                data.append("tipo", "agregarMerma");
                data.append("metodo", "insertar");
                data.append("accion", "inventario");
                data.append("IDProducto", id);
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
                            
                            TablaMerma($('#CerrarDetalle').attr('attrid'));
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


    $(document).on('click', '#botonVerTraslados', function() {
        var fecha = new Date();
        var dia = ("0" + (fecha.getDate() - 15)).slice(-2);
        var mes = ("0" + (fecha.getMonth() + 1)).slice(-2);
        var inicio = fecha.getFullYear()+"-"+(mes)+"-"+(dia);

        var fecha = new Date();
        var dia = ("0" + fecha.getDate()).slice(-2);
        var mes = ("0" + (fecha.getMonth() + 1)).slice(-2);
        var final = fecha.getFullYear()+"-"+(mes)+"-"+(dia);


        $('#FechaInicioTraslado').val(inicio);
        $('#FechaFinalTraslado').val(final);
        TablaTrasladosPrincipal();
    });

    $(document).on('change', '#FechaInicioTraslado', function() {
        TablaTrasladosPrincipal();
    });

    $(document).on('change', '#FechaFinalTraslado', function() {
        TablaTrasladosPrincipal();
    });

    $(document).on('click', '#verDistribucionSucursal', function() {
        $("#carga").show();
        $("#TablaPresentacionesSucursal tbody").html("");
        var idsucursal = $(this).attr("attrid");
        var idproducto = $(this).attr("producto");
        TablaPresentaciones(idproducto, idsucursal);
        $("#NombreSucursalModalPresentacion").text($(this).attr("nombre"));
        $("#NombreProductoPresentaciones").text($(this).attr("nombreProducto"));
        $("#ModalVerDistribucionPresentacion").modal("show");
    });

    $(document).on('click', '#VerConversionesProducto', function() {
        $("#ModalVerConversiones").modal("show");
        var idproducto = $(this).attr("producto");
        var nombreProducto = $(this).attr("nombreproducto");
        $(".TituloConversionesModal").text(nombreProducto);
        TablaConversiones(idproducto);
    });

    $(document).on('click', '#SucursalMerma', function() {
        var idSucursal = $(this).val();
        var idProducto = $("#GuardarMerma").attr("attrid");
        var data = "metodo=detalles&accion=inventario&tipo=ConsultarPresentacionMerma&IDProducto="+idProducto+"&IDSucursal="+idSucursal;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionProductoMerma").html(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });
});

function TablaInventario() {
    ajaxMyDatatable({
        "table": $("#TablaInventario"),
        "colums": [
            "Descripcion",
            "Distribucion",
            "Precios",
            "Merma",
            "Acciones"
        ],
        "sort": [
            0,
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
            "Presentacion",
            "Cantidad",
            "Imagen",
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

function TablaTraslados(idproducto) {
    console.log("el id es"+idproducto);
    ajaxMyDatatable({
        "table": $("#TablaTraslados"),
        "colums": [
            "Fecha",
            "Origen",
            "Destino",
            "Cantidad",
            "Usuario",
        ],
        "sort": [
            1,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "accion": "inventario",
            "tipo": "TablaTraslados",
            "id": idproducto
        }
    });
}

function TablaTrasladosPrincipal() {
    console.log($('#FechaInicioTraslado').val()+" "+$('#FechaFinalTraslado').val());
    ajaxMyDatatable({
        "table": $("#TablaImprimirTraslados"),
        "colums": [
            "Fecha",
            "Detalles",
            "Acciones",
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "accion": "inventario",
            "tipo": "TablaTrasladosPrincipal",
            "fechainicio": $('#FechaInicioTraslado').val(),
            "fechafin": $('#FechaFinalTraslado').val()
        }
    });
}

function TablaPresentaciones(idproducto, idsucursal) {
    ajaxMyDatatable({
        "table": $("#TablaPresentacionesSucursal"),
        "colums": [
            "Presentacion",
            "Abreviatura",
            "Cantidad",
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "accion": "inventario",
            "tipo": "PresentacionesProducto",
            "idProducto": idproducto,
            "idSucursal": idsucursal
        }
    });
    $("#carga").hide();
}

function TablaConversiones(idproducto) {
    ajaxMyDatatable({
        "table": $("#TablaConversiones"),
        "colums": [
            "Origen",
            "Destino",
            "Usuario",
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "accion": "inventario",
            "tipo": "ConversionesProducto",
            "idProducto": idproducto
        }
    });
    $("#carga").hide();
}
