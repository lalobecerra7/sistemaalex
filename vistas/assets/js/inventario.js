function v_inventario() {
    TablaInventario();
    $('#FormCliente').validate({
        rules: {
            NombreNegocio: {
                required: true
            },
            NombreContacto: {
                required: true
            },
            CelularCliente: {
                required: true
            },
        },
        messages: {
            NombreNegocio: {
                required: "El nombre del negocio es requerido"
            },
            NombreContacto: {
                required: "El nombre del contacto es requerido"
            },
            CelularCliente: {
                required: "El celular del contacto es requerido"
            },
        },
        submitHandler: function(form) {
            if ($("#TipoDescuento").val() != "" && ($("#DescuentoCliente").val() == "" || $("#DescuentoCliente").val() < 0)) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Ingresa la cantidad del descuento'
                });
                $("#DescuentoCliente").focus();
            } else {
                var data = "metodo=" + $("#GuardarCliente").attr("tipo") + "&accion=clientes&IDCliente=" + $("#GuardarCliente").attr("attrid") + "&NombreNegocio=" + $("#NombreNegocio").val() + "&CalleNegocio=" + $("#CalleNegocio").val() + "&ColoniaNegocio=" + $("#ColoniaNegocio").val() + "&NoInterior=" + $("#NumeroInteriorCliente").val() + "&NoExterior=" + $("#NumeroExteriorCliente").val() + "&NombreContacto=" + $("#NombreContacto").val() + "&CelularContacto=" + $("#CelularCliente").val() + "&TelefonoContacto=" + $("#TelefonoCliente").val() + "&TipoDescuento=" + $("#TipoDescuento").val() + "&CantidadDescuento=" + $("#DescuentoCliente").val();
                var btn = $('#GuardarCliente');
                $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: data,
                        beforeSend: function() {
                            progressBoton(btn);
                        }
                    })
                    .done(function(res) {
                        if ($.trim(res) == "Correcto") {
                            if ($("#GuardarCliente").attr("tipo") == "modificar") {
                                var tipoAlerta = "modificado";
                            } else {
                                var tipoAlerta = "guardado";
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Cliente ' + tipoAlerta + ' correctamente'
                            });
                            TablaClientes();
                            $("#ModalNuevoCliente").modal("hide");
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al ' + $("#GuardarCliente").attr("tipo") + ' el cliente.'
                            });

                            console.log($.trim(res));
                        }
                    })
                    .fail(function() {
                        console.log("Error ajax");
                    })
                    .always(function() {
                        unprogressBoton(btn);
                    });
            }
        }
    });
}

jQuery(document).ready(function($) {
    $(document).on('click', '#Detalles', function() {
        var id = $(this).attr("attrid");

        var data = "metodo=detalles&accion=inventario&tipo=detalles&IDProducto=" + id;
        $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
            })
            .done(function(res) {
                if ($.trim(res) == "SinResultados") {
                    Swal.fire({
                        icon: 'info',
                        title: 'No se encontraron registros'
                    });
                } else {
                    $("#ModalDetalles").modal("show");
                    $("#NombreProducto").text($(this).attr('nombre'));
                    $("#tbodyDetallesInventario").html(res);
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });
    });

    $(document).on('click', '.verDetallesMerma', function() {
        var id = $(this).attr("attrid");

        var data = "metodo=detalles&accion=inventario&tipo=merma&IDProducto=" + id;
        $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
            })
            .done(function(res) {
                if ($.trim(res) == "SinResultados") {
                    Swal.fire({
                        icon: 'info',
                        title: 'No se encontraron registros'
                    });
                } else {
                    $("#ModalMerma").modal("show");
                    $("#NombreProductoM").text($(this).attr('nombre'));
                    $("#tbodyMerma").html(res);
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });
    });

    $(document).on('click', '#Salidas', function() {
        var id = $(this).attr("attrid");

        var data = "metodo=detalles&accion=inventario&tipo=salidas&IDProducto=" + id;
        $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
            })
            .done(function(res) {
                if ($.trim(res) == "SinResultados") {
                    Swal.fire({
                        icon: 'info',
                        title: 'No se encontraron registros'
                    });
                } else {
                    $("#ModalSalidas").modal("show");
                    $("#NombreProductoS").text($(this).attr('nombre'));
                    $("#tbodySalidas").html(res);
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });
    });

    $(document).on('click', '#AgregarMerma', function() {
        $("#idProducto").val($(this).attr('attrid'));
        $("#ModalAgregarMerma").modal("show");
        $("#NombreProductoAM").text($(this).attr('nombre'));
    });

    $(document).on('click', '#AgregarExistencia', function() {
        $("#idProductoE").val($(this).attr('attrid'));
        $("#ModalAgregarExistencia").modal("show");
        $("#NombreProductoAE").text($(this).attr('nombre'));
    });

    $(document).on('click', '#GuardarMerma', function() {
        var $id = $('#AgregarMerma').attr("attrid");
        $('#FormMerma').validate({
            rules: {
                Cantidad: {
                    required: true
                },
                Motivo: {
                    required: true
                },
            },
            messages: {
                Cantidad: {
                    required: "La cantidad de merma es requerida"
                },
                Motivo: {
                    required: "El motivo de la merma es requerido"
                },
            },
            submitHandler: function(form) {
                var data = "metodo=insertar&accion=inventario&tipo=agregarMerma&IDProducto=" + $("#idProducto").val() + "&Cantidad=" + $("#Cantidad").val() + "&Motivo=" + $("#Motivo").val();
                $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: data
                    })
                    .done(function(res) {
                        if ($.trim(res) == "Correcto") {
                            Swal.fire({
                                icon: 'success',
                                title: 'La merma se ha registrado correctamente'
                            });
                            $('#cargarInicio').trigger('click');
                            $("#ModalAgregarMerma").modal("hide");
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
            }
        });
    });

    $(document).on('click', '#GuardarExistencia', function() {
        $('#FormExistencia').validate({
            rules: {
                CantidadE: {
                    required: true
                },
                CostoE: {
                    required: true
                },
            },
            messages: {
                CantidadE: {
                    required: "La cantidad de producto es requerida"
                },
                CostoE: {
                    required: "El costo del producto es requerido"
                },
            },
            submitHandler: function(form) {
                var data = "metodo=insertar&accion=inventario&tipo=agregarExistencia&IDProducto=" + $('#idProductoE').val() + "&Cantidad=" + $("#CantidadE").val() + "&Costo=" + $("#CostoE").val();
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
                                title: 'El aumento de existencia se ha registrado correctamente'
                            });
                            $('#cargarInicio').trigger('click');
                            $("#ModalAgregarExistencia").modal("hide");
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al insertar el aumento de existencia.'
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
            "Cantidad",
            "Costo",
            "TotalCosto",
            "Precio",
            "TotalPrecio",
            "Merma",
            "Detalles",
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