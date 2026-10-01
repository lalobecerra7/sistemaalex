function v_inventario() {
    TablaInventario();

    $('#FormMerma').validate({
        rules: {
            fechaMerma: {
                required: true
            },
            cantidadMerma: {
                required: true
            },
            motivoMerma: {
                required: true
            }
        },
        messages: {
            fechaMerma: {
                required: "La fecha es requerida"
            },
            cantidadMerma: {
                required: "La cantidad es requerida"
            },
            motivoMerma: {
                required: "El motivo es requerido"
            }
        },
        submitHandler: function(form) {
            const searchRegExp = new RegExp(',', 'g');
            var data = new FormData(document.getElementById("FormMerma"));
            data.append("metodo", $("#GuardarMerma").attr('tipo'));
            data.append("accion", "inventario");
            data.append("tipo", "agregarMerma");
            data.append("inventario", $("#GuardarMerma").attr('attrID'));
            data.append("costo", $("#costoMerma").text().replace('$', '').replace(searchRegExp, '')); 
            data.append("id", $("#GuardarMerma").attr('attrID'));
            
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
                    if($("#GuardarMerma").attr('tipo') == 'insertar'){
                        Swal.fire({
                            icon: 'success',
                            title: 'La merma se ha registrado correctamente'
                        });
                    }else{
                        Swal.fire({
                            icon: 'success',
                            title: 'La merma ha sido modificada correctamente'
                        });

                        TablaMerma();
                    }
                    
                    TablaInventario();
                    $("#ModalMerma").modal("hide");
                }else if(res == 'Error 2 Formato'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El formato del archivo no está permitido, los formatos permitidos son pdf, .png o .jpg'
                    });
                }else if(res == 'Error 3 Peso') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El tamaño de la foto excedió el peso máximo permitido, el peso máximo es de 10MB.'
                    });
                }else{
                    if($("#GuardarMerma").attr('tipo') == 'insertar'){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al insertar la merma.'
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al modificar la merma.'
                        });
                    }

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

    $('#formLoteInve').validate({
        rules: {
            fechaCadLoteInve: {
                required: true
            },
            cantidadLoteInve: {
                required: true
            }
        },
        messages: {
            fechaCadLoteInve: {
                required: "La fecha es requerida."
            },
            cantidadLoteInve: {
                required: "La cantidad de producto es requerida."
            }
        },
        submitHandler: function(form) {
            if ($('#bGuardarLoteInve').attr('tipo') === 'insertar'){
                guardarLoteInve();
            }else{
                Swal.fire({
                    title: '¿Estas seguro que deseas modificar el lote?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: '¡No, cancelar!',
                    confirmButtonText: '¡Si, modificar!'
                }).then(({ value }) => {
                    if (value){
                        guardarLoteInve();
                    }
                });
            }
        }
    });
}

function TablaInventario() {
    ajaxMyDatatable({
        "table": $("#TablaInventario"),
        "colums": [
            "Descripcion",
            "Existencia",
            "Precios",
            "Merma",
            "Lotes",
            "Acciones"
        ],
        "sort": [
            0,
            "asc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "consultar",
            "accion": "inventario"
        }
    });
}

function TablaMerma() {
    ajaxMyDatatable({
        "table": $("#TablaMerma"),
        "colums": [
            "Fecha",
            "Fecha_Merma",
            "Costo",
            "Cantidad",
            "Total",
            "Motivo",
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
            "inventario": $("#CerrarDetalle").attr("attrID")
        }
    });
}

function TablaConversiones() {
    ajaxMyDatatable({
        "table": $("#TablaConversiones"),
        "colums": [
            "Fecha",
            "Cantidad",
            "Conversion",
            "Acciones"
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
            "producto": $("#CerrarDetalleConversiones").attr("attrProducto"),
            "presentacion":$("#CerrarDetalleConversiones").attr("attrPresentacion"),
            "sucursal": $("#CerrarDetalleConversiones").attr("attrSucursal")
        }
    });
}

function guardarLoteInve() {
    var data = "metodo="+$("#bGuardarLoteInve").attr('tipo')+"&accion=inventario&tipo=agregarLote&nombre="+$.trim($("#nombreLoteInve").val())
    +"&fecha="+$("#fechaCadLoteInve").val()+"&cantidad="+$("#cantidadLoteInve").val()+"&producto="+$('#bGuardarLoteInve').attr('producto')
    +"&presentacion="+$('#bGuardarLoteInve').attr('presentacion')+"&sucursal="+$('#bGuardarLoteInve').attr('sucursal')+"&id="+$('#bGuardarLoteInve').attr('attrID');
    
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
          $('#carga').show();
        }
    })
    .done(function(res){
        if ($.trim(res) == 'Correcto') {
            var title = $("#bGuardarLoteInve").attr('tipo') === 'insertar' ? 
            'El lote ha sido guardado correctamente' : 'El lote ha sido modificado correctamente'
          
            Swal.fire({
                icon: 'success',
                title: title,
            });

            TablaInventario();
            $('#modalLoteInve').modal('hide');
        } else {
            var text = $("#bGuardarLoteInve").attr('tipo') === 'insertar' ? 
            'Error inesperado al guardar el lote' : 'Error inesperado al modificar el lote'
          
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: text
            });

            console.log($.trim(res));
        }
    })
    .fail(function() {
        console.error('Error ajax');
    })
    .always(() => {
        $('#carga').hide();
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '.verDetallesMerma', function() {
        $("#CerrarDetalle").attr("attrID", $(this).attr("attrID"));

        TablaMerma();
        $("#ModalDetalles").modal("show");
        $('#NombreProductoM').text($(this).attr("nombre"));
    });

    $(document).on('click', '.AgregarMerma', function() {
        $("#FormMerma").trigger("reset");
        $("#verFotoMerma img").attr('src', 'vistas/assets/archivos/defaultImagen.jpg');

        var now = new Date();
        var day = now.getDate().toString().padStart(2, '0');
        var month = (now.getMonth() + 1).toString().padStart(2, '0');
        var today = now.getFullYear()+"-"+(month)+"-"+(day);

        $('#fechaMerma').val(today);
        $('#costoMerma').html($(this).attr('attrCosto'));
        $('#totalMerma').html(parseFloat($(this).attr('attrCosto')) * (parseFloat($("#cantidadMerma").val()) || 0));

        $("#GuardarMerma").attr('tipo', 'insertar');
        $("#GuardarMerma").attr('attrID', $(this).attr('attrID'));
        moneda();
        $("#ModalMerma").modal("show");
    });

    $(document).on('change keyup', '#cantidadMerma', function() {
        const searchRegExp = new RegExp(',', 'g');
        $('#totalMerma').html(parseFloat($('#costoMerma').text().replace('$', '').replace(searchRegExp, '')) * (parseFloat($(this).val()) || 0));           
        moneda();
    });

    $(document).on('click', '#verFotoMerma', function() {
        $("#FotoMerma").trigger("click");
    });

    $(document).on('change', '#FotoMerma', function() {
        readURL(this, $("#verFotoMerma"));
    });

    $(document).on('click', '.EliminarMerma', function() {
        var btn = $(this);
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

			        var data = "metodo=eliminar&accion=inventario&tipo=EliminarMerma&IDMerma="+btn.attr('attrid')+"&RegresarInventario="+regresarInventario;
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

							TablaMerma();
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

    $(document).on('click', '.ModificarMerma', function() {
        var btn = $(this);
        var data = "metodo=detalles&accion=inventario&tipo=consultarMerma&IDMerma="+btn.attr('attrID');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            //console.log($.trim(res));
            var datos = JSON.parse($.trim(res));

            $("#verFotoMerma").children('img').attr('src', 'vistas/assets/archivos/defaultImagen.jpg');
            if($.trim(datos.Foto) != ''){
                $("#verFotoMerma").children('img').attr('src', 'vistas/assets/archivos/fotosMerma/'+$.trim(datos.Foto));        
            }

            $("#cantidadMerma").val(datos.Cantidad);
            $("#motivoMerma").val(datos.Motivo);

            $("#costoMerma").html(datos.Costo);
            $("#totalMerma").html(parseFloat(datos.Cantidad) * parseFloat(datos.Costo));
            $("#fechaMerma").val(datos.Fecha_Merma);
            $("#motivoMerma").val(datos.Motivo);

            moneda();
            $("#GuardarMerma").attr('tipo', "modificar");
            $("#GuardarMerma").attr('attrID', btn.attr('attrID'));
            $("#ModalMerma").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        }).always(function() {
            $("#carga").hide();
        });
    });

    $(document).on('click', '.ConvertirProducto', function() {
        var btn = $(this);
        var padre = $(this).parent().parent();
        const searchRegExp = new RegExp(',', 'g');
        $("#conversionProducto").html(padre.children('td:eq(0)').html());
        $("#conversionSucursal").html(padre.children('td:eq(1)').children('div').html());
        $("#cantidadConversion").val('0');
        $("#cantidadConversion").attr('max', parseFloat(padre.children('td:eq(1)').children('b:eq(0)').text().replace(searchRegExp, '')));

        var data = "metodo=detalles&accion=inventario&tipo=consultarPresentaciones&producto="+btn.attr('attrProducto')+"&presentacion="+btn.attr('attrPresentacion');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            $("#verPresentaciones").html($.trim(res));
            $("#GuardarConversionProducto").attr('tipo', 'insertar');
            $("#GuardarConversionProducto").attr('attrProducto', btn.attr('attrProducto'));
            $("#GuardarConversionProducto").attr('attrPresentacion', (btn.attr('attrPresentacion')));
            $("#GuardarConversionProducto").attr('attrSucursal', btn.attr('attrSucursal'));
            $("#ModalConversionProducto").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        }).always(function() {
            $("#carga").hide();
        });
    });

    $(document).on('submit', '#FormConversionProducto', function() {
        event.preventDefault();
        var conversiones = [];
        var suma = 0;
        $("#verPresentaciones").children('tr').each(function(index, el) {
            if(parseFloat($(this).children('td:eq(1)').children('input').val()) > 0){
                conversiones.push({'ID_Presentacion': $(this).attr('attrID'), 'Cantidad': $(this).children('td:eq(1)').children('input').val(), 'Modificada': $(this).attr('modificada')});
                suma += parseFloat($(this).children('td:eq(1)').children('input').val());
            }
        });

        if(suma == 0){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La cantidad al menos una presentación debe ser mayor a 0.'
            });
        }else{
            var btn = $("#GuardarConversionProducto");
            var data = "metodo="+btn.attr('tipo')+"&accion=inventario&tipo=conversion&producto="+btn.attr('attrProducto')+"&presentacion="+btn.attr('attrPresentacion')+"&sucursal="+btn.attr('attrSucursal')+"&cantidad="+$("#cantidadConversion").val()+"&conversiones="+JSON.stringify(conversiones)+"&id="+btn.attr('attrID');

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                var separa = $.trim(res).split('~');
                if (separa[0] == "Correcto") {
                    Swal.fire({
                        icon: 'success',
                        title: 'La coversión se ha realizado correctamente'
                    });
                                
                    TablaInventario();
                    $("#ModalConversionProducto").modal("hide");

                    var altura=50;
                    var anchura=310;
                    var y= parseInt((window.screen.height/2)-(altura/2));
                    var x= parseInt((window.screen.width/2)-(anchura/2));
                    window.open("controladores/ticketConversion.php?id="+separa[1], '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");     
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al realizar la conversión.'
                    });
                    
                    console.log($.trim(res));
                }
            })
            .fail(function() {
                console.log("Error ajax");
            }).always(function() {
                $("#carga").hide();
            });
        }
    });

    $(document).on('click', '.verDetallesConversiones', function() {
        var padre = $(this).parent().parent();
        $("#verDetalleProducto").html(padre.children('td:eq(0)').html()+'<br><br>');
        $("#verDetalleProducto").append(padre.children('td:eq(1)').children('div').html());
        $("#CerrarDetalleConversiones").attr("attrProducto", $(this).attr('attrProducto'));
        $("#CerrarDetalleConversiones").attr("attrPresentacion", $(this).attr('attrPresentacion'));
        $("#CerrarDetalleConversiones").attr("attrSucursal", $(this).attr('attrSucursal'));
        TablaConversiones();
        $("#ModalConversiones").modal("show");
    });

    $(document).on('click', '.bImpririConversion', function() {
        var idConversion = $(this).attr("attrID");
        var altura=50;
        var anchura=310;

        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
        window.open("controladores/ticketConversion.php?id="+idConversion, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
    });

    $(document).on('click', '.bEliminarConversion', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro que quieres eliminar la conversión?',
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

                    var data = "metodo=eliminar&accion=inventario&tipo=EliminarConversion&id="+btn.attr('attrid')+"&regresar="+regresarInventario;
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
                                title: 'La conversión ha sido eliminada correctamente'
                            });

                            TablaConversiones();
                            TablaInventario();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al eliminar la conversión.'
                            });

                            console.log($.trim(res));
                        }
                    })
                    .fail(function() {
                        console.log("Error ajax");
                    }).always(function() {
                        $("#carga").hide();
                    });
                });
            }    
        });       
    });

    $(document).on('click', '.bModificarConversion', function() {
        var btn = $(this);
        var padre = $(this).parent().parent();
        $("#conversionSucursal").html('');
        $("#conversionProducto").html($("#verDetalleProducto").html());
        const searchRegExp = new RegExp(',', 'g');
        $("#cantidadConversion").val(padre.children('td:eq(1)').text().replace(searchRegExp, ''));
        $("#cantidadConversion").attr('max', parseFloat($("#verDetalleProducto").children('b.cantidad').text().replace(searchRegExp, '')) + parseFloat(padre.children('td:eq(1)').text().replace(searchRegExp, '')));
        
        var presentaciones = [];
        padre.children('td:eq(2)').children('p').each(function(index, el) {
            presentaciones.push({'id': $(this).attr('attrID'), 'cantidad': $(this).children('span').text().replace(searchRegExp, '')});
        });

        var data = "metodo=detalles&accion=inventario&tipo=consultarPresentaciones&producto="+btn.attr('attrProducto')+"&presentacion="+btn.attr('attrPresentacion');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            $("#verPresentaciones").html($.trim(res));
            $("#GuardarConversionProducto").attr('tipo', 'modificar');
            $("#GuardarConversionProducto").attr('attrID', btn.attr('attrID'));
            $("#GuardarConversionProducto").attr('attrProducto', btn.attr('attrProducto'));
            $("#GuardarConversionProducto").attr('attrPresentacion', btn.attr('attrPresentacion'));
            $("#GuardarConversionProducto").attr('attrSucursal', btn.attr('attrSucursal'));
            
            $("#verPresentaciones").children('tr').each(function(index, el) {
                for (var i = presentaciones.length - 1; i >= 0; i--) {
                    if(presentaciones[i].id == $(this).attr('attrID')){
                        $(this).children('td:eq(1)').children('input').val(presentaciones[i].cantidad);
                        $(this).attr('modificada', true);
                        break;
                    }
                }
            });

            $("#ModalConversiones").modal("hide");
            $("#ModalConversionProducto").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        }).always(function() {
            $("#carga").hide();
        });
    });

    $(document).on('click', '.bLoteInve', function() {
        $('#formLoteInve')[0].reset();
        var validator = $("#formLoteInve").validate();
        validator.resetForm();

        $('#bGuardarLoteInve').attr('tipo', 'insertar');
        $('#bGuardarLoteInve').attr('producto', $(this).attr('attrProducto'));
        $('#bGuardarLoteInve').attr('presentacion', $(this).attr('attrPresentacion'));
        $('#bGuardarLoteInve').attr('sucursal', $(this).attr('attrSucursal'));
        $("#bEliminarLoteInve").attr('attrID', '');
        $("#bImprimirLoteInve").attr('attrID', '');
        $("#bEliminarLoteInve").addClass('oculto');
        $("#bImprimirLoteInve").addClass('oculto');
        $('#modalLoteInve').modal('show');
    });

    $(document).on('click', '.bListaLoteInve', function() {
        $('#formLoteInve')[0].reset();
        var validator = $("#formLoteInve").validate();
        validator.resetForm();

        $("#fechaCadLoteInve").val($(this).attr('attrFecha'));
        $("#nombreLoteInve").val($(this).attr('attrNombre'));
        $("#cantidadLoteInve").val($(this).attr('attrCantidad'));

        $('#bGuardarLoteInve').attr('tipo', 'modificar');
        $('#bGuardarLoteInve').attr('attrID', $(this).attr('attrID'));
        if($('.bLoteInve').length > 0){
            $("#bEliminarLoteInve").attr('attrID', $(this).attr('attrID'));
            $("#bImprimirLoteInve").attr('attrID', $(this).attr('attrID'));
            $("#bEliminarLoteInve").removeClass('oculto');
            $("#bImprimirLoteInve").removeClass('oculto');
        }
        $('#modalLoteInve').modal('show');
    });

    $(document).on('click', '#bEliminarLoteInve', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro que quieres eliminar el lote?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '¡No, cancelar!',
            confirmButtonText: '¡Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                var data = "metodo=eliminar&accion=inventario&tipo=lote&id="+btn.attr('attrID');
                
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                      $('#carga').show();
                    }
                })
                .done(function(res){
                    if ($.trim(res) == 'Correcto') {
                        Swal.fire({
                            icon: 'success',
                            title: 'El lote ha sido eliminado correctamente',
                        });

                        TablaInventario();
                        $('#modalLoteInve').modal('hide');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el lote'
                        });

                        console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.error('Error ajax');
                })
                .always(() => {
                    $('#carga').hide();
                });
            }    
        }); 
    });

    $(document).on('click', '#bImprimirLoteInve', function() {
        window.open("./controladores/barras/imprimirCodigo.php?id="+$(this).attr('attrID'), "_blank");
    });
});