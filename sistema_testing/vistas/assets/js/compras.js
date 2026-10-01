function v_compras() {
    TablaReporteCompras();
}

jQuery(document).ready(function ($) {
    $(document).on("click", "#EliminarCompra", function () {
        var btn = $(this);
        Swal.fire({
            title:
                "¿Estás seguro que quieres eliminar la compra con el folio " +
                btn.attr("folio") +
                "?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "¡No, cancelar!",
            confirmButtonText: "¡Si, eliminar!",
        }).then((result) => {
            if (result.value) {
                var data =
                    "metodo=eliminar&accion=compras&IDCompra=" +
                    btn.attr("attrid");
                $.ajax({
                    url: "index.php",
                    type: "POST",
                    data: data,
                    beforeSend: function () {
                        progressBoton(btn);
                    },
                })
                    .done(function (res) {
                        if ($.trim(res) == "Correcto") {
                            Swal.fire({
                                icon: "success",
                                title: "Compra eliminada correctamente",
                            });

                            TablaReporteCompras();

                            Swal.fire({
                                title: "¿Quieres restar los productos del inventario?",
                                icon: "warning",
                                html: "",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                cancelButtonText: "¡No, cancelar!",
                                confirmButtonText: "¡Si, continuar!",
                            }).then((result) => {
                                if (result.value) {
                                    var data =
                                        "metodo=detalles&accion=compras&tipo=inventario&id=" +
                                        btn.attr("attrid");

                                    $.ajax({
                                        url: "index.php",
                                        type: "POST",
                                        data: data,
                                        beforeSend: function () {
                                            progressBoton(btn);
                                        },
                                    })
                                        .done(function (res) {
                                            if ($.trim(res) == "Correcto") {
                                                Swal.fire({
                                                    icon: "success",
                                                    title: "Los productos han sido restados del inventario",
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Oops...",
                                                    text: "Error inesperado al realizar la resta.",
                                                });

                                                console.log($.trim(res));
                                            }
                                        })
                                        .fail(function () {
                                            console.log("Error ajax");
                                        })
                                        .always(function () {
                                            unprogressBoton(btn);
                                        });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Error inesperado al eliminar la compra.",
                            });
                            console.log($.trim(res));
                        }
                    })
                    .fail(function () {
                        console.log("Error ajax");
                    })
                    .always(function () {
                        unprogressBoton(btn);
                    });
            }
        });
    });

    // Boton para generar el excel de 'Exportar compras'.
    $(document).on("click", "#bExcelExportarCompras", function () {
        let palabra = $(
            ".buscadorMyDataTable[tabla=TablaReporteCompras]"
        ).val();
        window.open(
            "controladores/excel/excelExportarCompras.php?palabra=" + palabra
        );
    });
    // Fin del boton para generar el excel de 'Exportar compras'.

    $(document).on("click", "#VerProductosCompra", function () {
        var folio = $(this).attr("folio");
        var id = $(this).attr("attrid");
        $("#ModalVerProductosCompra").modal("show");
        $("#FolioCompraProductos").text(folio);

        var data =
            "metodo=detalles&accion=compras&tipo=productos&IDCompra=" + id;
        $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
        })
            .done(function (res) {
                $("#tbodyVerProductosCompra").html(res);
            })
            .fail(function () {
                console.log("Error ajax");
            });
    });

    $(document).on("click", "#VerHistorialPagos", function () {
        var id = $(this).attr("attrid");
        var folio = $(this).attr("folio");
        $("#ModalVerHistorialPagos").modal("show");
        $("#FolioCompraPagos").text(folio);
        TablaVerHistorialPagos(id);
    });
});

$(document).on("click", "#ImprimirTicketCompra", function () {
    var idCompra = $(this).attr("attrid");
    var sucursal = $(this).attr("idSucursal");
    var altura = 50;
    var anchura = 310;

    var y = parseInt(window.screen.height / 2 - altura / 2);
    var x = parseInt(window.screen.width / 2 - anchura / 2);

    window.open(
        "controladores/ticketCompra.php?id=" +
            idCompra +
            "&idSucursal=" +
            sucursal,
        "_blank",
        "width=" +
            anchura +
            ", height=" +
            altura +
            ", top=" +
            y +
            ", left=" +
            x +
            ""
    );
});

$(document).on("click", "#EliminarPago", function () {
    var btn = $(this);
    Swal.fire({
        title: "¿Estás seguro que quieres eliminar el pago?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "¡No, cancelar!",
        confirmButtonText: "¡Si, eliminar!",
    }).then((result) => {
        if (result.value) {
            var id = $(this).attr("idCompra");
            var data =
                "metodo=detalles&accion=compras&tipo=eliminarPago&IDPago=" +
                $(this).attr("attrid");
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data,
                beforeSend: function () {
                    progressBoton(btn);
                },
            })
                .done(function (res) {
                    if ($.trim(res) == "Correcto") {
                        Swal.fire({
                            icon: "success",
                            title: "Pago eliminado correctamente",
                        });
                        TablaVerHistorialPagos(id);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error inesperado al eliminar el pago.",
                        });
                        console.log($.trim(res));
                    }
                })
                .fail(function () {
                    console.log("Error ajax");
                })
                .always(function () {
                    unprogressBoton(btn);
                });
        }
    });
});

$(document).on("click", ".CancelarCompra", function () {
    var btn = $(this);
    Swal.fire({
        title: "¿Estás seguro que quieres cancelar la compra?",
        icon: "warning",
        html: "",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "¡No, cancelar!",
        confirmButtonText: "¡Si, continuar!",
    }).then((result) => {
        if (result.value) {
            var data =
                "metodo=modificar&accion=compras&IDCompra=" +
                btn.attr("attrid");

            $.ajax({
                url: "index.php",
                type: "POST",
                data: data,
                beforeSend: function () {
                    progressBoton(btn);
                },
            })
                .done(function (res) {
                    if ($.trim(res) == "Correcto") {
                        Swal.fire({
                            icon: "success",
                            title: "Compra cancelada correctamente",
                        });

                        TablaReporteCompras();

                        Swal.fire({
                            title: "¿Quieres restar los productos del inventario?",
                            icon: "warning",
                            html: "",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            cancelButtonText: "¡No, cancelar!",
                            confirmButtonText: "¡Si, continuar!",
                        }).then((result) => {
                            if (result.value) {
                                var data =
                                    "metodo=detalles&accion=compras&tipo=inventario&id=" +
                                    btn.attr("attrid");

                                $.ajax({
                                    url: "index.php",
                                    type: "POST",
                                    data: data,
                                    beforeSend: function () {
                                        progressBoton(btn);
                                    },
                                })
                                    .done(function (res) {
                                        if ($.trim(res) == "Correcto") {
                                            Swal.fire({
                                                icon: "success",
                                                title: "Los productos han sido restados del inventario",
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Oops...",
                                                text: "Error inesperado al realizar la resta.",
                                            });

                                            console.log($.trim(res));
                                        }
                                    })
                                    .fail(function () {
                                        console.log("Error ajax");
                                    })
                                    .always(function () {
                                        unprogressBoton(btn);
                                    });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error inesperado al cancelar la compra.",
                        });
                        console.log($.trim(res));
                    }
                })
                .fail(function () {
                    console.log("Error ajax");
                })
                .always(function () {
                    unprogressBoton(btn);
                });
        }
    });
});

$(document).on("click", ".PagoCom", function () {
    document.getElementById("FormPagoCompra").reset();
    var id = $(this).attr("attrid");
    var data = "metodo=detalles&accion=compras&tipo=pago&IDCompra=" + id;

    $.ajax({
        url: "index.php",
        type: "POST",
        data: data,
    })
        .done(function (res) {
            //console.log($.trim(res));
            var datos = JSON.parse($.trim(res));

            var restante =
                parseFloat(datos.Total) - parseFloat(datos.TotalPagos);
            $("#Proveedor").text(datos.Proveedor);
            $("#TotalCompra").text(
                new Intl.NumberFormat("es-MX", {
                    style: "currency",
                    currency: "MXN",
                }).format(datos.Total)
            );
            $("#Pagos").text(
                new Intl.NumberFormat("es-MX", {
                    style: "currency",
                    currency: "MXN",
                }).format(datos.TotalPagos)
            );
            $("#Restante").text(
                new Intl.NumberFormat("es-MX", {
                    style: "currency",
                    currency: "MXN",
                }).format(restante)
            );
            $("#ModalPagoCompra").modal("show");
            $("#GuardarPago").attr("attrid", id);
        })
        .fail(function () {
            console.log("Error ajax");
        });
});

$(document).on("click", "#GuardarPago", function () {
    var id = $(this).attr("attrid");

    $("#FormPagoCompra").validate({
        rules: {
            ImportePagoCompra: {
                required: true,
            },
            ConceptoPago: {
                required: true,
            },
            TipoDePago: {
                required: true,
            },
        },
        messages: {
            ImportePagoCompra: {
                required: "El importe es obligatorio",
            },
            ConceptoPago: {
                required: "El concepto es obligatorio",
            },
            TipoDePago: {
                required: "El tipo de pago es obligatorio",
            },
        },
        submitHandler: function (form) {
            if (
                $("#ImportePagoCompra").val() == "" ||
                $("#ImportePagoCompra").val() == 0
            ) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "El importe debe ser mayor a $0",
                });
            } else {
                var data = new FormData(
                    document.getElementById("FormPagoCompra")
                );
                data.append("metodo", "insertar");
                data.append("accion", "compras");
                data.append("IDCompra", id);

                $.ajax({
                    url: "index.php",
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                })
                    .done(function (res) {
                        if ($.trim(res) == "Correcto") {
                            Swal.fire({
                                icon: "success",
                                title: "Pago registrado correctamente",
                            });
                            TablaReporteCompras();
                            $("#ModalPagoCompra").modal("hide");
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Error inesperado al registrar la compra.",
                            });
                            console.log($.trim(res));
                        }
                    })
                    .fail(function () {
                        console.log("Error ajax");
                    });
            }
        },
    });
});

function TablaReporteCompras() {
    ajaxMyDatatable({
        table: $("#TablaReporteCompras"),
        colums: ["Datos", "Proveedor", "Total", "Detalles", "Acciones"],
        totals: ["Datos", "Proveedor", "Total", "Detalles", "Acciones"],
        sort: [0, "desc"],
        url: "index.php",
        params: {
            metodo: "consultar",
            accion: "compras",
        },
    });
}

function TablaVerHistorialPagos(id) {
    ajaxMyDatatable({
        table: $("#TablaVerHistorialPagos"),
        colums: [
            "Fecha",
            "Concepto",
            "TipoPago",
            "Monto",
            "Detalles",
            "Comprobante",
            "Accion",
        ],
        sort: [0, "desc"],
        url: "index.php",
        params: {
            metodo: "detalles",
            accion: "compras",
            tipo: "historialPagos",
            IDCompra: id,
        },
    });
}
