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
            $("#GuardarConversionProducto").attr('attrPresentacion', btn.attr('attrPresentacion'));
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

    $(document).on('click', '#bVerTraslados', function() {
        TablaTraslados();
        $("#ModalTraslados").modal("show");
    });

    $(document).on('click', '#bAgregarTraslado', function() {
        $("#sucursalOrigenTraslado").val($("#sucursalOrigenTraslado").children('option:eq(0)').attr('value'));
        $("#sucursalDestinoTraslado").val('');
        $("#CodigoProductoTraslado").val('');
        $("#verProductosTras").html('');
        $("#estatusTraslado").val('Pendiente');

        var now = new Date();
        var day = now.getDate().toString().padStart(2, '0');
        var month = (now.getMonth() + 1).toString().padStart(2, '0');
        var today = now.getFullYear()+"-"+(month)+"-"+(day);

        $("#fechaTraslado").val(today);
        $("#bGuardarTraslado").attr('tipo', 'insertar');
        $("#ModalAgregarTraslado").modal('show');       
    });

    $(document).on('submit', '#FormAgregarProductoTraslado', function(event) {
        event.preventDefault();
        var data = "metodo=insertar&accion=inventario&tipo=traslados&sucursal="+$("#sucursalOrigenTraslado").val()+"&codigo="+$.trim($("#CodigoProductoTraslado").val());
        
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
            if($.trim(res) == "No encontrado"){
                Swal.fire({
                    icon: 'warning',
                    title: 'Producto no encontrado',
                    timer: 1000
                });
            }else{
                var datos = JSON.parse($.trim(res));

                if($("#verProductosTras").children('tr[attrID='+datos.ID_Producto+']').children('td:eq(2)[attrID=0]').length > 0){
                    var valor = parseFloat($("#verProductosTras").children('tr[attrID='+datos.ID_Producto+']').children('td:eq(2)[attrID=0]').parent().children('td:eq(4)').children('input').val());
                    $("#verProductosTras").children('tr[attrID='+datos.ID_Producto+']').children('td:eq(2)[attrID=0]').parent().children('td:eq(4)').children('input').val(valor + 1);
                }else{
                    $("#verProductosTras").append(`<tr attrID="`+datos.ID_Producto+`">
                        <td>`+$.trim($("#CodigoProductoTraslado").val())+`</td>
                        <td>`+datos.Descripcion+`</td>
                        <td attrID="0">`+datos.Presentacion+`<br></td>
                        <td><span class="cantidad">`+datos.Existencia+`</span></td>
                        <td><input type="number" step="any" class="form-control" value="1"></td>
                    </tr>`);
                    
                    moneda();
                }

                $("#CodigoProductoTraslado").val('');
            }
        })
        .fail(function() {
            console.log("Error ajax");
        }).always(function() {
            $("#carga").hide();
        });
    });

    var filaTraslado = null;
    $(document).on('click', '.bCambiarPresTras', function(event) {
        event.preventDefault();
        var btn = $(this);
        filaTraslado = btn.parent().parent();

        var data = "metodo=insertar&accion=inventario&tipo=consultarPrese&id="+btn.attr('attrID')+"&sucursal="+$("#sucursalOrigenTraslado").val();

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
            $("#verTablaPrese").html($.trim(res));
            moneda();
            $("#modalVerPresentaciones").modal('show');
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            $("#carga").hide();
        });        
    });

    $(document).on('click', '.bSeleCamPresTras', function() {
        var padre = $(this).parent().parent();
        filaTraslado.children('td:eq(2)').attr('attrID', $(this).attr('attrID'));
        
        var texto = 'Sin presentación';
        if($.trim(padre.children('td:eq(0)').text()) != ''){ 
            texto = $.trim(padre.children('td:eq(0)').text());
            
            if($.trim(padre.children('td:eq(1)').text()) != ''){
                texto += '('+padre.children('td:eq(1)').text()+')';
            }
        }

        filaTraslado.children('td:eq(2)').children('button').html(texto);
        filaTraslado.children('td:eq(3)').html(padre.children('td:eq(2)').html());
        $("#modalVerPresentaciones").modal('hide');
    });

    $(document).on('click', '#CargarProductosModalTraslado', function() {
        TablaProductosTraslados();
        $("#ModalVerProductosTraslados").modal('show');
    });

    $(document).on('click', '.bSelePresTras', function() {
        var padre = $(this).parent().parent();

        if($("#verProductosTras").children('tr[attrID='+padre.attr('id')+']').children('td:eq(2)[attrID='+$(this).attr('presentacion')+']').length > 0){
            var valor = parseFloat($("#verProductosTras").children('tr[attrID='+padre.attr('id')+']').children('td:eq(2)[attrID='+$(this).attr('presentacion')+']').parent().children('td:eq(4)').children('input').val());
            $("#verProductosTras").children('tr[attrID='+padre.attr('id')+']').children('td:eq(2)[attrID='+$(this).attr('presentacion')+']').parent().children('td:eq(4)').children('input').val(valor + 1);
        }else{
            $("#verProductosTras").append(`<tr attrID="`+padre.attr('id')+`">
                <td>`+padre.children('td:eq(0)').text()+`</td>
                <td>`+padre.children('td:eq(1)').text()+`</td>
                <td attrID="`+$(this).attr('presentacion')+`"><button type="button" class="btn btn-sm btn-secondary bCambiarPresTras" attrID="`+padre.attr('id')+`" title="Cambiar presentación">`+$(this).children('span:eq(1)').text()+`</button><br></td>
                <td><span class="cantidad">`+$(this).children('span:eq(0)').text()+`</span></td>
                <td><input type="number" step="any" class="form-control" value="1"></td>
            </tr>`);
            
            $("#ModalVerProductosTraslados").modal('hide');
            moneda();
        }
    });

    $(document).on('click', '#bGuardarTraslado', function() {
        var btn = $(this);

        if($("#sucursalDestinoTraslado").val() == ""){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Debes seleccionar una sucursal de destino.'
            });   
        }else if($("#sucursalOrigenTraslado").val() == $("#sucursalDestinoTraslado").val()){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'La sucursal de orige debe ser diferente a la de destino.'
            });   
        }else if($("#verProductosTras").children('tr').length == 0){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Debes agregar al menos un producto para realizar el traslado.'
            });
        }else if($("#fechaTraslado").val() == ""){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'La fecha es requerida.'
            });
        }else{
            var productos = [];
            $("#verProductosTras").children('tr').each(function(index, el) {
                productos.push({'ID_Producto': $(this).attr('attrID'), 'ID_Presentacion': $(this).children('td:eq(2)').attr('attrID'), 'Cantidad': $(this).children('td:eq(4)').children('input').val()});
            });

            var data = "metodo="+btn.attr('tipo')+"&accion=inventario&tipo=guardarTraslado&origen="+$("#sucursalOrigenTraslado").val()+"&destino="+$("#sucursalDestinoTraslado").val()+"&fechaTraslado="+$("#fechaTraslado").val()+"&estatus="+$("#estatusTraslado").val()+"&productos="+JSON.stringify(productos)+"&id="+btn.attr('attrID');

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
                        title: 'El trasaldo ha sido guardado correctamente'
                    });

                    TablaTraslados();
                    TablaInventario();
                    $("#ModalAgregarTraslado").modal('hide');  

                    var altura=50;
                    var anchura=310;
                    var y= parseInt((window.screen.height/2)-(altura/2));
                    var x= parseInt((window.screen.width/2)-(anchura/2));
                    window.open("controladores/ticketTraslado.php?id="+separa[1], '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");     
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al guardar el traslado.'
                    });

                    console.log($.trim(res));
                }    
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                $("#carga").hide();
            });         
        }
    });

    $(document).on('click', '.bDetalleTraslado', function() {
        var btn = $(this);
        var data = "metodo=detalles&accion=inventario&tipo=productosTraslado&id="+btn.attr('attrID');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            $("#verProductosTraslados").html($.trim(res)); 
            moneda();
            $("#modalDetallesTraslados").modal('show');  
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            $("#carga").hide();
        });         
    });

    $(document).on('click', '.bCompletarTraslado', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro que quieres completar el traslado?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '¡No, cancelar!',
            confirmButtonText: '¡Si, completar!'
        }).then((result) => {
            if (result.value) {
                var data = "metodo=modificar&accion=inventario&tipo=completarTraslado&id="+btn.attr('attrid');
                
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
                            title: 'El traslado ha sido completado correctamente'
                        });

                        TablaTraslados();
                        TablaInventario();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al completar el traslado.'
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
    });

    $(document).on('click', '.bCancelarTraslado', function() {
        var btn = $(this);

        Swal.fire({
            title: '¿Estas seguro que quieres cancelar el traslado?',
            text: 'Para cancelar el traslado debes ingresar el motivo.',
            icon: 'warning',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, cancelar',
            cancelButtonText: 'No, cerrar',
            inputValidator: (value) => {
                if (!value) {
                    return '¡Debes escribir un motivo!'
                }
            }
        }).then((result) => {
            if (result.value) {
                var regresar = "No";
                var motivo = result.value;

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
                        regresar = "Si";
                    }

                    var data = "metodo=modificar&accion=inventario&tipo=cancelarTraslado&id="+btn.attr('attrid')+"&regresar="+regresar+"&motivo="+$.trim(motivo);
                    
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
                                title: 'El traslado ha sido cancelado correctamente'
                            });

                            TablaTraslados();
                            TablaInventario();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al cancelar el traslado.'
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

    $(document).on('click', '.bEliminarTraslado', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro que quieres eliminar el traslado?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '¡No, cancelar!',
            confirmButtonText: '¡Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                var regresar = "No";

                if($.trim(btn.parent().parent().children('td:eq(4)').text()) == 'Completado') {
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
                            regresar = "Si";
                        }

                        eliminarTraslado(btn, regresar);
                    });    
                }else{
                    eliminarTraslado(btn, regresar);
                }
            }    
        });    
    });

    function eliminarTraslado(btn, regresar) {
        var data = "metodo=eliminar&accion=inventario&tipo=eliminarTraslado&id="+btn.attr('attrid')+"&regresar="+regresar;
                    
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
                    title: 'El traslado ha sido eliminado correctamente'
                });

                TablaTraslados();
                TablaInventario();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inesperado al eliminar el traslado.'
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

    $(document).on('click', '.bImprimirTraslado', function() {
        var idTraslado = $(this).attr("attrID");
        var altura=50;
        var anchura=310;

        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
        window.open("controladores/ticketTraslado.php?id="+idTraslado, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
    });
});

function TablaInventario() {
    ajaxMyDatatable({
        "table": $("#TablaInventario"),
        "colums": [
            "Descripcion",
            "Existencia",
            "Precios",
            "Merma",
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

function TablaTraslados() {
    ajaxMyDatatable({
        "table": $("#TablaTraslados"),
        "colums": [
            "Fecha",
            "FechaTraslado",
            "Origen",
            "Destino",
            "Estatus",
            "Detalles",
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
            "tipo": "TablaTraslados"
        }
    });
}

function TablaProductosTraslados(){
    ajaxMyDatatable({
        "table": $("#TablaProductosTraslados"), 
        "colums": [
            "Codigo",
            "Descripcion",
            "Presentacion"
        ], 
        "sort": [
            0,
            "asc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "tipo": "ConsultarProductos",
            "accion": "inventario",
            "sucursal": $("#sucursalOrigenTraslado").val()
        }
    });
}
