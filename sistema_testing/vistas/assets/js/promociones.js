var sucursalesSeleccionadasPromociones = [];
function v_promociones() {
    tablaPromociones();
    sucursalesSeleccionadasPromociones = [];

    $("#ProductosPromocionRegalo").select2({
        placeholder: "-- Seleccione una opcion --",
        dropdownParent: $('#Padre'),
        allowClear: true,
        language: { 
            noResults: function() {
                return "No hay resultados";
            },
            searching: function() {
                return "Buscando..";
            }
        }
    });

	$('#formPromociones').validate({
        rules: {
            FechaPromocion: {
                required: true
            },
            TipoPromocion: {
                required: true
            },
            NombrePromocion: {
                required: true
            },
            DescripcionPromocion: {
                required: true
            },
            ProductosPromocion: {
                required: true
            },
            PresentacionesProductoPromocion: {
                required: true
            },
            ProductosPromocion2: {
                required: true
            },
            PresentacionesProductoPromocion2: {
                required: true
            },
            CantidadCondicionPromocion: {
                required: true
            },
            CantidadRegalarPromocion: {
                required: true
            },
            PresentacionProductoCantidadRegalar: {
                required: true
            },
            ProductosPromocionRegalo: {
                required: true
            },
            PresentacionesProductoPromocionRegalo: {
                required: true
            },
            PrecioPromocionCombo: {
                required: true
            },
        },
        messages: {
            FechaPromocion: {
                required: "La fecha de la promoción es obligatoria"
            },
            TipoPromocion: {
                required: "El tipo de promoción es obligatorio"
            },
            NombrePromocion: {
                required: "El nombre de promoción es obligatorio"
            },
            DescripcionPromocion: {
                required: "La descripción de la promoción es obligatoria"
            },
            ProductosPromocion: {
                required: "El producto para la promoción es obligatorio"
            },
            PresentacionesProductoPromocion: {
                required: "La presentación del producto es obligatoria"
            },
            ProductosPromocion2: {
                required: "El producto para la promoción es obligatorio"
            },
            PresentacionesProductoPromocion2: {
                required: "La presentación del producto es obligatoria"
            },
            CantidadCondicionPromocion: {
                required: "La cantidad es obligatoria"
            },
            CantidadRegalarPromocion: {
                required: "La cantidad a regalar es obligatoria"
            },
            PresentacionProductoCantidadRegalar: {
                required: "La presentación a regalar obligatoria"
            },
            ProductosPromocionRegalo: {
                required: "El producto para regalar es obligatorio"
            },
            PresentacionesProductoPromocionRegalo: {
                required: "La presentación del producto es obligatoria"
            },
            PrecioPromocionCombo: {
                required: "El precio de la promoción es obligatoria"
            },
        },
        ignore: ":hidden",
        submitHandler: function(form) { 

            if ($("#TipoPromocion").val() == "ComboProductoRegalo" || $("#TipoPromocion").val() == "ComboPrecioEspecial") {
                if (($("#ProductosPromocion").val() == $("#ProductosPromocion2").val()) && $("#PresentacionesProductoPromocion").val() == $("#PresentacionesProductoPromocion2").val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El producto de promoción y el combo deben de ser diferentes'
                    });
                    return false;
                }
            }

            if (sucursalesSeleccionadasPromociones.length <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Tienes que seleccionar una sucursal'
                });
                return false;
            }
            
            var data = "metodo=insertar&accion=promociones&FechaPromocion="+$("#FechaPromocion").val()+"&TipoPromocion="+$("#TipoPromocion").val()+"&NombrePromocion="+$("#NombrePromocion").val()+"&DescripcionPromocion="+$("#DescripcionPromocion").val()+"&ProductosPromocion="+$("#ProductosPromocion").val()+"&PresentacionesProductoPromocion="+$("#PresentacionesProductoPromocion").val()+"&ProductosPromocion2="+$("#ProductosPromocion2").val()+"&PresentacionesProductoPromocion2="+$("#PresentacionesProductoPromocion2").val()+"&CantidadCondicionPromocion="+$("#CantidadCondicionPromocion").val()+"&CantidadRegalarPromocion="+$("#CantidadRegalarPromocion").val()+"&ProductosPromocionRegalo="+$("#ProductosPromocionRegalo").val()+"&PresentacionesProductoPromocionRegalo="+$("#PresentacionesProductoPromocionRegalo").val()+"&PrecioPromocionCombo="+$("#PrecioPromocionCombo").val()+"&sucursales="+JSON.stringify(sucursalesSeleccionadasPromociones)+"&PresentacionProductoCantidadRegalar="+$("#PresentacionProductoCantidadRegalar").val();

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
                        title: 'Promoción guardada correctamente'
                    });

                    tablaPromociones(); 
                    sucursalesSeleccionadasPromociones = [];
                    $("#modalPromociones").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al guardar la promoción'
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

function tablaPromociones() {
	ajaxMyDatatable({
        "table": $("#tablaPromociones"), 
        "colums": [
            "Fecha",
            "Nombre",
            "Tipo",
            "Promocion",
            "Sucursales",
            "Estatus",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "promociones"
        }
    });
}

jQuery(document).ready(function($) {
	$(document).on('click', '#bNuevaPromocion', function() {
        SucursalesReportePromociones();
        $("#MostrarSucursalesPromociones").text("No has seleccionado sucursales")
		$("#formPromociones")[0].reset();
        var now = new Date();
        var day = now.getDate().toString().padStart(2, '0');
        var month = (now.getMonth() + 1).toString().padStart(2, '0');
        var today = now.getFullYear()+"-"+(month)+"-"+(day);
        sucursalesSeleccionadasPromociones=[]
        $("#FechaPromocion").val(today);
		$("#bGuardarPromocion").attr('tipo', 'insertar');
        $("#TipoPromocion").trigger("change")
		$("#modalPromociones").modal('show');
        
    });

    $(document).on('change', '#TipoPromocion', function() {
        $(".columnas").addClass("oculto")
        if ($(this).val() == "CantidadRegalo") {
            $(".CantidadRegalo").removeClass("oculto");
        }else if($(this).val() == "ProductoRegalo") {
            $(".ProductoRegalo").removeClass("oculto");
        }else if($(this).val() == "ComboProductoRegalo") {
            $(".ComboProductoRegalo").removeClass("oculto");
        }else if($(this).val() == "ComboPrecioEspecial") {
            $(".ComboPrecioEspecial").removeClass("oculto");
        }
    });

	/*$(document).on('click', '.bModificarChofer', function() {
		var padre = $(this).parent().parent();
		$("#formPromociones")[0].reset();
		$("#nombreChofer").val(padre.children('td:eq(1)').text());
        $("#primerApellidoChofer").val(padre.children('td:eq(2)').text());
        $("#segundoApellidoChofer").val(padre.children('td:eq(3)').text());

		$("#bGuardarPromocion").attr('attrID', $(this).attr('attrID'));
		$("#bGuardarPromocion").attr('tipo', 'modificar');
		$("#modalPromociones").modal('show');
	});

	*/

    $(document).on('click', '.bEliminarPromocion', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro de eliminar la promoción '+$(this).attr("nombre")+'?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.value) {
                var data = "metodo=eliminar&accion=promociones&id="+btn.attr('attrID');
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
                            title: 'Promoción eliminada correctamente'
                        });

                        tablaPromociones(); 
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar la promoción.'
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

    $(document).on('click', '.bTerminarPromocion', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro de finalizar la promoción '+$(this).attr("nombre")+'?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, finalizar'
        }).then((result) => {
            
            if (result.value) {
                var data = "metodo=detalles&accion=promociones&tipo=FinalizarPromocion&id="+btn.attr('attrID');

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
                            title: 'Promoción finalizada correctamente'
                        });

                        tablaPromociones(); 
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al finalizar la promoción.'
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


    $(document).on('click', '#CargarSucursalesModalPromociones', function() {
        $("#ModalSucursalesPromociones").modal("show");
    });

    $(document).on('click', '.CheckInputSucursalPromociones', function() {
        var boton = $(this);
        if (boton.prop("checked") == true) {
            sucursalesSeleccionadasPromociones.push({
                "ID" : boton.attr("attrid"),
                "Nombre" : boton.attr("nombre"),
            })
        }else{
            const nuevoarreglo = sucursalesSeleccionadasPromociones.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
            sucursalesSeleccionadasPromociones = nuevoarreglo;
        }
    });

    $(document).on('click', '#SeleccionarSucursalesMarcadasPromociones', function() {
        tablaPromociones();
        var textoSucursales = "";
        for (var i = 0; i < sucursalesSeleccionadasPromociones.length; i++) {
            console.log(sucursalesSeleccionadasPromociones[i]);
            textoSucursales += sucursalesSeleccionadasPromociones[i]["Nombre"]+", ";
        }
        var str = textoSucursales.replace(/,\s*$/, "");
        
        if (sucursalesSeleccionadasPromociones.length <= 0) {
            str = "No has seleccionado sucursales";
        }
        $("#MostrarSucursalesPromociones").text(str)
        ConsultarProductosPromocion()
        $("#PresentacionesProductoPromocion").val("");
        $("#PresentacionProductoCantidadRegalar").val("");
        $("#PresentacionesProductoPromocionRegalo").val("");
        $("#PresentacionesProductoPromocion2").val("");
        $("#ModalSucursalesPromociones").modal("hide")
    });

    $(document).on('change', '#ProductosPromocion', function() {
        var data = "metodo=detalles&accion=promociones&tipo=ConsultarPresentaciones&IDProducto="+$(this).val();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionesProductoPromocion").html(res)
            $("#PresentacionProductoCantidadRegalar").html(res)
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('change', '#ProductosPromocion2', function() {
        var data = "metodo=detalles&accion=promociones&tipo=ConsultarPresentaciones&IDProducto="+$(this).val();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionesProductoPromocion2").html(res)
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('change', '#ProductosPromocionRegalo', function() {
        var data = "metodo=detalles&accion=promociones&tipo=ConsultarPresentaciones&IDProducto="+$(this).val();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#PresentacionesProductoPromocionRegalo").html(res)
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });


});

function SucursalesReportePromociones(){
    ajaxMyDatatable({
        "table": $("#TablaSucursalesPromociones"), 
        "colums": [
            "Seleccionar",
            "Sucursal",
            "Direccion",
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "tipo": "ConsultarSucursales",
            "accion": "promociones"
        }
    });
}

function ConsultarProductosPromocion(){
    var data = "metodo=detalles&accion=promociones&tipo=ConsultarProductosPromocion&Sucursales="+JSON.stringify(sucursalesSeleccionadasPromociones);
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
    })
    .done(function(res) {
        
        $("#ProductosPromocion").html(res)
        $("#ProductosPromocion2").html(res)
        $("#ProductosPromocionRegalo").html(res)

        
        $("#ProductosPromocion").select2({
            placeholder: "-- Seleccione una opcion --",
            dropdownParent: $('#Padre'),
            allowClear: true,
            language: { 
                noResults: function() {
                    return "No hay resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });

        $("#ProductosPromocion2").select2({
            placeholder: "-- Seleccione una opcion --",
            dropdownParent: $('#Padre'),
            allowClear: true,
            language: { 
                noResults: function() {
                    return "No hay resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });

        $("#ProductosPromocionRegalo").select2({
            placeholder: "-- Seleccione una opcion --",
            dropdownParent: $('#Padre'),
            allowClear: true,
            language: { 
                noResults: function() {
                    return "No hay resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
        
    })
    .fail(function() {
        console.log("Error ajax");
    });
}


