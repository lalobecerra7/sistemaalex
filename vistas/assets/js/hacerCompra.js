function v_hacerCompra() {
    $("#CodigoProducto").focus();
}

jQuery(document).ready(function ($) {
    $(document).on("submit", "#FormAgregarProductoC", function (event) {
        event.preventDefault();
        $("#AgregarProductoCodigoC").trigger("click");
    });

    $(document).on("click", "#CargarProductosModalC", function () {
        TablaProductosCompra();
        setTimeout(function () {
            $(".BuscadorTablaTablaProductosCompra").val("");
            $(".BuscadorTablaTablaProductosCompra").trigger("keyup");
            $(".BuscadorTablaTablaProductosCompra").focus();
        }, 500);
    });

    $(document).on("click", "#CargarProveedoresModalC", function () {
        TablaProveedoresCompra();
    });

    $(document).on("click", "#bFolioOrdenCompra", function () {
        $(this).addClass("oculto");
        $("#cargarHacerCompra").trigger("click");
    });

    $(document).on("click", "#LimpiarProveedorSeleccionado", function () {
        $("#MostrarCreditoProveedor").html("$0.00");
        $("#MostrarCreditoRestante").text("$0.00");
        $("#MostrarCreditoProveedor").attr("credito", "0.00");
        $(".BotonLimpiarProveedor").addClass("oculto");
        $("#CargarProveedoresModalC").html(
            '<i class="fas fa-user"></i> Proveedor'
        );
    });

    $(document).on("keyup change", ".campoCantidad", function () {
        var cantidad = parseFloat($(this).val()) || 0;
        var costo = parseFloat($(this).parent().parent().find(".campoCosto").val()) || 0;
        var total = 0;
        var descuento = parseFloat($(this).parent().parent().find(".campoDescuento").val()) || 0;
        var impuesto = parseFloat($(this).parent().parent().find(".campoImpuesto option:selected").attr("impuesto")) || 0;
        var regalado = parseFloat($(this).parent().find(".campoRegalado").val()) || 0;
        var real = cantidad + regalado;
        costo = (cantidad / real) * costo;
        cantidad = real;

        var cantidadDescuento = (costo * cantidad) * (descuento / 100);
        var cantidadImpuesto = ((costo * cantidad) - cantidadDescuento) * (impuesto / 100);

        if (costo != "") {
            total = (costo * cantidad) - cantidadDescuento + cantidadImpuesto;
        }
        
        var costoNeto = (total / cantidad) || 0;
        
        $(this)
            .parent()
            .parent()
            .find(".costoNeto")
            .html(
                '<span class="dinero">' +
                    Math.round(costoNeto * 100) / 100 +
                    "</span>"
            );
        $(this)
            .parent()
            .parent()
            .find(".totalP")
            .html(
                '<span class="dinero">' +
                    Math.round(total * 100) / 100 +
                    "</span>"
            );

        CalcularSubtotal();
        $("#DescuentoCompraDinero").trigger("change");
    });

    $(document).on("keyup change", ".campoRegalado", function () {
        var regalado = parseFloat($(this).val()) || 0;
        var costo = parseFloat($(this).parent().parent().find(".campoCosto").val()) || 0;
        var total = 0;
        var descuento = parseFloat($(this).parent().parent().find(".campoDescuento").val()) || 0;
        var impuesto = parseFloat($(this).parent().parent().find(".campoImpuesto option:selected").attr("impuesto")) || 0;
        var cantidad = parseFloat($(this).parent().find(".campoCantidad").val()) || 0;
        var real = cantidad + regalado;
        costo = (cantidad / real) * costo;
        cantidad = real;

        var cantidadDescuento = (costo * cantidad) * (descuento / 100);
        var cantidadImpuesto = ((costo * cantidad) - cantidadDescuento) * (impuesto / 100);

        if (costo != "") {
            total = (costo * cantidad) - cantidadDescuento + cantidadImpuesto;
        }
        
        var costoNeto = (total / cantidad) || 0;
        
        $(this)
            .parent()
            .parent()
            .find(".costoNeto")
            .html(
                '<span class="dinero">' +
                    Math.round(costoNeto * 100) / 100 +
                    "</span>"
            );
        $(this)
            .parent()
            .parent()
            .find(".totalP")
            .html(
                '<span class="dinero">' +
                    Math.round(total * 100) / 100 +
                    "</span>"
            );

        CalcularSubtotal();
        $("#DescuentoCompraDinero").trigger("change");
    });

    $(document).on("keyup change", ".campoCosto", function () {
        var costo = parseFloat($(this).val()) || 0;
        $(this).parent().parent().attr("costo", costo);
        var cantidad = parseFloat($(this).parent().parent().find(".campoCantidad").val()) || 0;
        var total = 0;
        var descuento = parseFloat($(this).parent().parent().find(".campoDescuento").val()) || 0;
        var impuesto = parseFloat($(this).parent().parent().find(".campoImpuesto option:selected").attr("impuesto")) || 0;
        var regalado = parseFloat($(this).parent().parent().find(".campoRegalado").val()) || 0;
        var real = cantidad + regalado;
        costo = (cantidad / real) * costo;
        cantidad = real;

        var cantidadDescuento = (costo * cantidad) * (descuento / 100);
        var cantidadImpuesto = ((costo * cantidad) - cantidadDescuento) * (impuesto / 100);

        if (costo != "") {
            total = (costo * cantidad) - cantidadDescuento + cantidadImpuesto;
        }

        var costoNeto = total / cantidad;

        $(this)
            .parent()
            .parent()
            .find(".costoNeto")
            .html(
                '<span class="dinero">' +
                    Math.round(costoNeto * 100) / 100 +
                    "</span>"
            );
        $(this)
            .parent()
            .parent()
            .find(".totalP")
            .html(
                '<span class="dinero">' +
                    Math.round(total * 100) / 100 +
                    "</span>"
            );
        CalcularSubtotal();
        $("#DescuentoCompraDinero").trigger("change");
    });

    $(document).on("keyup change", ".campoDescuento", function () {
        var descuento = parseFloat($(this).val()) || 0;
        var costo = parseFloat($(this).parent().parent().find(".campoCosto").val()) || 0;
        var cantidad = parseFloat($(this).parent().parent().find(".campoCantidad").val()) || 0;
        var total = 0;
        var impuesto = parseFloat($(this).parent().parent().find(".campoImpuesto option:selected").attr("impuesto")) || 0;
        var regalado = parseFloat($(this).parent().parent().find(".campoRegalado").val()) || 0;
        var real = cantidad + regalado;
        costo = (cantidad / real) * costo;
        cantidad = real;

        var cantidadDescuento = (costo * cantidad) * (descuento / 100);
        var cantidadImpuesto = ((costo * cantidad) - cantidadDescuento) * (impuesto / 100);

        if (costo != "") {
            total = (costo * cantidad) - cantidadDescuento + cantidadImpuesto;
        }
        
        var costoNeto = total / cantidad;

        $(this)
            .parent()
            .parent()
            .find(".costoNeto")
            .html(
                '<span class="dinero">' +
                    Math.round(costoNeto * 100) / 100 +
                    "</span>"
            );
        $(this)
            .parent()
            .parent()
            .find(".totalP")
            .html(
                '<span class="dinero">' +
                    Math.round(total * 100) / 100 +
                    "</span>"
            );

        CalcularSubtotal();
        $("#DescuentoCompraDinero").trigger("change");
    });

    $(document).on("change", ".campoImpuesto", function () {
        var impuesto = parseFloat($(this).find("option:selected").attr("impuesto")) || 0;
        var costo = parseFloat($(this).parent().parent().find(".campoCosto").val()) || 0;
        var cantidad = parseFloat($(this).parent().parent().find(".campoCantidad").val()) || 0;
        var total = 0;
        var descuento = parseFloat($(this).parent().parent().find(".campoDescuento").val()) || 0;
        var regalado = parseFloat($(this).parent().parent().find(".campoRegalado").val()) || 0;
        var real = cantidad + regalado;
        costo = (cantidad / real) * costo;
        cantidad = real;
        
        var cantidadDescuento = (costo * cantidad) * (descuento / 100);
        var cantidadImpuesto = ((costo * cantidad) - cantidadDescuento) * (impuesto / 100);

        if (costo != "") {
            total = (costo * cantidad) - cantidadDescuento + cantidadImpuesto;
        }

        var costoNeto = total / cantidad;
        
        $(this)
            .parent()
            .parent()
            .find(".costoNeto")
            .html(
                '<span class="dinero">' +
                    Math.round(costoNeto * 100) / 100 +
                    "</span>"
            );
        $(this)
            .parent()
            .parent()
            .find(".totalP")
            .html(
                '<span class="dinero">' +
                    Math.round(total * 100) / 100 +
                    "</span>"
            );

        CalcularSubtotal();
        $("#DescuentoCompraDinero").trigger("change");
    });

    $(document).on("hidden.bs.modal", "#ModalVerProductosVenta", function () {
        $("#CodigoProducto").focus();
    });

    $(document).on("hidden.bs.modal", "#ModalVerProveedoresC", function () {
        $("#CodigoProducto").focus();
    });

    $(document).on("keyup change", "#DescuentoCompraDinero", function () {
        const searchRegExp = new RegExp(",", "g");
        if ($("#RealizarCompra").attr("tipodescuento") == "Cantidad") {
            $("#RealizarCompra").attr("cantidad", $(this).val());
        }
        $("#DescuentoCompraPorcentaje").val(
            Math.round(
                (parseFloat($(this).val()) /
                    parseFloat(
                        $("#MostrarSubtotal")
                            .text()
                            .replace("$", "")
                            .replace(searchRegExp, "")
                    )) *
                    100 *
                    100
            ) / 100
        );
        CalcularTotal();
    });

    $(document).on("click", "#AgregarProductoCodigoC", function () {
        //$("#ModalVerProductosCompra").modal("show");
        var Codigo = $("#CodigoProductoC").val();
        var data =
            "metodo=consultar&accion=hacerCompra&tipo=ConsultarProductoCodigo&codigo=" +
            Codigo;
        $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
        })
            .done(function (res) {
                //console.log($.trim(res));
                var datos = JSON.parse(res);
                if (datos == null) {
                    Swal.fire({
                        icon: "error",
                        title: "Producto no encontrado",
                        timer: 1200,
                    });
                } else {
                    var idProducto = datos.producto.ID_Producto;
                    var codigo = datos.producto.Codigo;
                    var descripcion = datos.producto.Descripcion;
                    var costo = datos.producto.Costo;
                    var presentacion = "Sin presentación";
                    if (datos.producto.NombrePresentacion != " ()") {
                        presentacion = datos.producto.NombrePresentacion;
                    }
                    var descuento = datos.producto.Descuento;
                    var idPresentacion = '0';
                    
                    var fila =
                        "\
					<tr attrid='" +
                        idProducto +
                        "' idPresentacion='0' costo='" +
                        costo +
                        "'>\
						<td>" +
                        codigo +
                        "</td>\
						<td>" +
                        descripcion +
                        "<br><button type='button' class='btn btn-sm btn-primary bCambiarPre' attrID='" +
                        idProducto +
                        "'>" +
                        presentacion +
                        "</button></td>\
						<td class='costoP'><input type='number' value='" +
                        costo +
                        "' min='0.1' step='any' class='form-control campoCosto'></td>\
						<td><input type='number' value='1' min='0.1' step='any' class='form-control campoCantidad'><br>Regalado: <input type='number' value='0' min='0.1' step='any' class='form-control campoRegalado'></td>\
						<td><input type='number' value='"+descuento+"' min='0' max='100' step='any' class='form-control campoDescuento'></td>\
						<td>\
							<select class='form-control campoImpuesto'>\
							" +
                        datos.impuestos[0] +
                        "\
							<select/>\
						</td>\
						<td class='costoNeto'><span class='dinero'>0</span></td>\
						<td class='totalP'><span class='dinero'>" +
                        (costo - (costo * (descuento / 100))) +
                        "</span></td>\
						<td><button class='btn btn-danger btn-sm EliminarFila'><i class='fas fa-trash'></i></button></td>\
					</tr>\
				";

                    var encontrado = false;

                    $("#tbodyTablaProductosAgregados tr").each(function () {
                        if (
                            $(this).attr("attrid") == idProducto &&
                            $(this).attr("idpresentacion") == idPresentacion &&
                            $(this).attr("costo") == costo
                        ) {
                            var cantidadAnterior = $(this)
                                .children("td:eq(3)")
                                .find(".campoCantidad")
                                .val();
                            $(this)
                                .children("td:eq(3)")
                                .find(".campoCantidad")
                                .val(parseFloat(cantidadAnterior) + 1);
                            encontrado = true;
                            $(".campoCantidad").trigger("keyup");
                            return false;
                        }
                    });

                    if (encontrado == false) {
                        $("#tbodyTablaProductosAgregados").append(fila);
                    }
                    $("#CodigoProducto").val("");
                    $("#CodigoProducto").focus();
                    CalcularSubtotal();
                    $("#DescuentoCompraDinero").val(
                        $("#RealizarCompra").attr("cantidad")
                    );
                    $("#DescuentoCompraDinero").trigger("change");

                    $("#CodigoProductoC").val("");

                    moneda();
                }
            })
            .fail(function () {
                console.log("Error ajax");
            });
    });

    $(document).on("click", ".bSelePresCom", function () {
        const searchRegExp = new RegExp(",", "g");
        $("#ModalVerProductosCompra").modal("hide");
        var padre = $(this).parent().parent();
        var idProducto = padre.attr("id");
        var codigo = padre.children("td:eq(0)").find(".codigo").text();
        var descripcion = padre
            .children("td:eq(1)")
            .find(".NombreProducto")
            .text();
        var costo = $(this).attr("costo");
        var idPresentacion = $(this).attr("presentacion");
        var presentacion = $(this).children("span").html();
        var opciones = $(this).attr("opciones");
        var descuento = $(this).attr("descuento");

        var costoDes = 0;
        if (descuento > 0) {
            costoDes = costo - (costo * (descuento / 100));
        } else {
            costoDes = costo;
        }

        //var existencia = $(this).children("td:eq(3)").find(".ExistenciaProducto").text();
        var fila =
            "\
			<tr attrid='" +
            idProducto +
            "' idPresentacion='" +
            idPresentacion +
            "' costo='" +
            costo +
            "'>\
                <td>" +
            codigo +
            "</td>\
                <td>" +
            descripcion +
            "<br><button type='button' class='btn btn-sm btn-primary bCambiarPre' attrID='" +
            idProducto +
            "'>" +
            presentacion +
            "</button></td>\
                <td class='costoP'><input type='number' value='" +
            costo +
            "' min='0.1' step='any' class='form-control campoCosto'></td>\
                <td><input type='number' value='1' min='0.1' step='any' class='form-control campoCantidad'><br>Regalado: <input type='number' value='0' min='0.1' step='any' class='form-control campoRegalado'></td>\
				<td><input type='number' value='"+descuento+"' min='0' step='any' class='form-control campoDescuento'></td>\
				<td>\
					<select class='form-control campoImpuesto'>\
					" +
            opciones +
            "\
					<select/>\
				</td>\
				<td class='costoNeto'><span class='dinero'>0</span></td>\
                <td class='totalP'><span class='dinero'>" +
            costoDes +
            "</span></td>\
            	<td><button class='btn btn-danger btn-sm EliminarFila'><i class='fas fa-trash'></i></button></td>\
            </tr>\
		";

        var encontrado = false;

        $("#tbodyTablaProductosAgregados tr").each(function () {
            if (
                $(this).attr("attrid") == idProducto &&
                $(this).attr("idpresentacion") == idPresentacion &&
                $(this).attr("costo") == costo
            ) {
                var cantidadAnterior = $(this)
                    .children("td:eq(3)")
                    .find(".campoCantidad")
                    .val();
                $(this)
                    .children("td:eq(3)")
                    .find(".campoCantidad")
                    .val(parseFloat(cantidadAnterior) + 1);
                encontrado = true;
                $(".campoCantidad").trigger("keyup");
                return false;
            }
        });

        if (encontrado == false) {
            $("#tbodyTablaProductosAgregados").append(fila);
        }
        $("#CodigoProducto").val("");
        $("#CodigoProducto").focus();
        CalcularSubtotal();
        $("#DescuentoCompraDinero").val($("#RealizarCompra").attr("cantidad"));
        $("#DescuentoCompraDinero").trigger("change");
        /*$("#DescuentoVentaDinero").trigger("change");
		$("#DescuentoVentaPorcentaje").trigger("change");*/
    });

    $(document).on("click", ".EliminarFila", function () {
        $(this).parent().parent().remove();
        CalcularSubtotal();
        $("#DescuentoCompraDinero").val($("#RealizarCompra").attr("cantidad"));
        $("#DescuentoCompraDinero").trigger("change");
    });

    $(document).on("click", "#TablaProveedoresCompra tbody tr", function () {
        $(".BotonLimpiarProveedor").removeClass("oculto");
        var idProveedor = $(this).attr("id");
        var nombre = $(this)
            .children("td:eq(0)")
            .find(".NombreProveedor")
            .text();
        var razonSocial = $(this)
            .children("td:eq(2)")
            .find(".razonSocial")
            .text();

        $("#RealizarCompra").attr("idProveedor", idProveedor);
        $("#CargarProveedoresModalC").html(
            "Proveedor: " + nombre + "<br>Razon social: " + razonSocial
        );
        $("#ModalVerProveedoresC").modal("hide");
        if ($("#TipoCompra").val() == "Credito") {
            $("#TipoCompra").trigger("change");
        }
    });

    $(document).on("click", "#RealizarCompra", function () {
        const searchRegExp = new RegExp(",", "g");
        if ($(this).attr("idProveedor") == "") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Seleccione un proveedor para la compra",
            });
        } else if ($("#tbodyTablaProductosAgregados tr").length <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tienes que ingresar al menos un producto para realizar la compra",
            });
        } else if (
            $("#TotalCompra")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "") <= 0
        ) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se puede realizar una venta con un total de $0.00",
            });
        } else {
            const searchRegExp = new RegExp(",", "g");
            var fecha = new Date();

            if ($("#TipoCompra").val() == "Contado") {
                $("#CreditoCompra").attr("hidden", true);
                $("#Total").text($("#TotalCompra").text());
                $("#ImportePagadoCompra").val(
                    $("#TotalCompra")
                        .text()
                        .replace("$", "")
                        .replace(searchRegExp, "")
                );
                $("#ModalCobrarCompra").modal("show");
                $("#SubtotalCompra").attr("hidden", false);
                $("#DescuentoCompra").attr("hidden", false);
                $("#TotalDeCompra").attr("hidden", false);
                $("#Detalles").attr("hidden", false);

                $("#Subtotal").text($("#MostrarSubtotal").text());
                $("#Descuento").text(
                    new Intl.NumberFormat("es-MX", {
                        style: "currency",
                        currency: "MXN",
                    }).format($("#DescuentoCompraDinero").val())
                );
            } else {
                if ($("#fechaCredito").val() == "") {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Debes elegir una fecha como limite de pago.",
                    });
                } else if (new Date($("#fechaCredito").val()) <= fecha) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Debes elegir una fecha posterior al dia de hoy.",
                    });
                } else {
                    const searchRegExp = new RegExp(",", "g");
                    $("#CreditoCompra").attr("hidden", false);
                    $("#Credito").text($("#MostrarCreditoRestante").text());
                    var totalFinal = 0;
                    totalFinal = parseFloat(
                        $("#TotalCompra")
                            .text()
                            .replace("$", "")
                            .replace(searchRegExp, "")
                    );
                    $("#Total").text(
                        new Intl.NumberFormat("es-MX", {
                            style: "currency",
                            currency: "MXN",
                        }).format(totalFinal)
                    );
                    $("#ModalCobrarCompra").modal("show");
                    $("#SubtotalCompra").attr("hidden", false);
                    $("#DescuentoCompra").attr("hidden", false);
                    $("#TotalDeCompra").attr("hidden", false);
                    $("#Detalles").attr("hidden", false);
                    $("#ImportePagadoCompra").val("0");
                    $("#Subtotal").text($("#MostrarSubtotal").text());
                    $("#Descuento").text(
                        new Intl.NumberFormat("es-MX", {
                            style: "currency",
                            currency: "MXN",
                        }).format($("#DescuentoCompraDinero").val())
                    );
                }
            }
        }
    });

    function CalcularSubtotal() {
        const searchRegExp = new RegExp(",", "g");
        $("#cantidadProductosSpan").text(
            $("#tbodyTablaProductosAgregados tr").length
        );
        var total = 0;
        $("#tbodyTablaProductosAgregados tr").each(function () {
            var totalFilas = parseFloat(
                $(this)
                    .children("td:eq(7)")
                    .text()
                    .replace("$", "")
                    .replace(searchRegExp, "")
            );
            total += totalFilas;
        });

        $("#MostrarSubtotal").html(
            '<span class="dinero">' + Math.round(total * 100) / 100 + "</span>"
        );

        CalcularTotal();
    }

    function CalcularTotal() {
        const searchRegExp = new RegExp(",", "g");
        var total = parseFloat(
            $("#MostrarSubtotal")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
        );

        if (
            $("#DescuentoCompraDinero").val() != "" &&
            parseFloat($("#DescuentoCompraDinero").val()) > 0
        ) {
            total -= parseFloat($("#DescuentoCompraDinero").val());
        }
        var totalfinal = Math.round(total * 100) / 100;
        $("#TotalCompra").html(
            '<span class="dinero">' +
                Math.round(totalfinal * 100) / 100 +
                "</span>"
        );

        moneda();
    }

    $(document).on("change", "#TipoCompra", function () {
        if ($("#TipoCompra").val() == "Credito") {
            id = $("#RealizarCompra").attr("idproveedor");
            var data =
                "metodo=consultar&accion=hacerCompra&tipo=creditoProveedor&IDProveedor=" +
                id;
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data,
            })
                .done(function (res) {
                    //console.log(res);
                    var datos = JSON.parse(res);
                    var credito = "";
                    if (
                        datos.data.Credito == "NO" ||
                        datos.data.Credito == "" ||
                        datos.data.Credito == null
                    ) {
                        credito = "No ofrece crédito";
                    } else if (datos.data.Credito == "SI") {
                        credito = "$0";
                    } else {
                        credito = "$" + datos.data.Credito;
                    }
                    $("#MostrarCreditoProveedor").text(credito);
                    $("#MostrarCreditoRestante").text(
                        "$" + datos.data.RestanteCredito
                    );
                    $("#MostrarCreditoProveedor").attr(
                        "credito",
                        datos.data.Credito
                    );
                    $("#LimiteCredito").attr("hidden", false);
                    $("#FechaLimiteCredito").attr("hidden", false);
                    $("#LimiteCreditoRestante").attr("hidden", false);
                })
                .fail(function () {
                    console.log("Error ajax");
                });
        } else if ($("#TipoCompra").val() == "Contado") {
            $("#LimiteCredito").attr("hidden", true);
            $("#FechaLimiteCredito").attr("hidden", true);
        }
    });

    $(document).on("change", "#TipoCompra", function () {
        if ($("#TipoCompra").val() == "Credito") {
            id = $("#RealizarCompra").attr("idproveedor");
            var data =
                "metodo=consultar&accion=hacerCompra&tipo=creditoProveedor&IDProveedor=" +
                id;
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data,
            })
                .done(function (res) {
                    //console.log(res);
                    var datos = JSON.parse(res);
                    var credito = "";
                    if (
                        datos.data.Credito == "NO" ||
                        datos.data.Credito == "" ||
                        datos.data.Credito == null
                    ) {
                        credito = "No ofrece crédito";
                    } else if (datos.data.Credito == "SI") {
                        credito = "$0";
                    } else {
                        credito = "$" + datos.data.Credito;
                    }
                    $("#MostrarCreditoProveedor").text(credito);
                    $("#MostrarCreditoRestante").text(
                        "$" + datos.data.RestanteCredito
                    );
                    $("#MostrarCreditoProveedor").attr(
                        "credito",
                        datos.data.Credito
                    );
                    $("#LimiteCredito").attr("hidden", false);
                    $("#FechaLimiteCredito").attr("hidden", false);
                    $("#LimiteCreditoRestante").attr("hidden", false);
                })
                .fail(function () {
                    console.log("Error ajax");
                });
        } else if ($("#TipoCompra").val() == "Contado") {
            $("#LimiteCredito").attr("hidden", true);
            $("#FechaLimiteCredito").attr("hidden", true);
        }
    });

    // Evento para setear el id del select de la sucursal por el valor del option seleccionado en el mismo.
    $(document).on("change", "#SucursalHacerCompra", function () {
        let idSucursal = $(this).val();
        $("#SucursalHacerCompra").attr("attrID", idSucursal);
    });

    $(document).on("click", "#GuardarCompra", function () {
        var $btn = $(this);

        // Evitar múltiples clics
        if ($btn.prop("disabled")) {
            return; // Ya está en proceso
        }

        // Guardar texto original y mostrar cargando
        var originalText = $btn.html();
        $btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

        const searchRegExp = new RegExp(",", "g");
        var total = $("#TotalCompra").text().replace("$", "").replace(searchRegExp, "");
        var ImportePagadoCompra = parseFloat($("#ImportePagadoCompra").val());
        var idProveedor = $("#RealizarCompra").attr("idProveedor");
        var descuento = $("#Descuento").text().replace("$", "").replace(searchRegExp, "");
        var subtotal = $("#MostrarSubtotal").text().replace("$", "").replace(searchRegExp, "");
        var tipoPago = $("#TipoPago").val();
        var detalles = $("#DetallesPago").val();
        var tipoCompra = $("#TipoCompra").val();
        var fechaCredito = $("#fechaCredito").val();

        if (
            $("#ImportePagadoCompra").val() == "" ||
            (tipoCompra == "Contado" &&
                ImportePagadoCompra <
                    parseFloat(
                        $("#Total").text().replace("$", "").replace(searchRegExp, "")
                    ))
        ) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "El importe pagado dede cubrir la totalidad de la compra",
            });
            $btn.prop("disabled", false).html(originalText); // Rehabilita si hay error
        } else {
            var productos = [];
            $("#tbodyTablaProductosAgregados tr").each(function () {
                var idProducto = $(this).attr("attrid");
                var Sucursal = $("#SucursalHacerCompra").attr("attrID");
                var Presentacion = $(this).attr("idPresentacion");
                var Costo = parseFloat($(this).children("td:eq(2)").find(".campoCosto").val()) || 0;
                var Cantidad = parseFloat($(this).children("td:eq(3)").find(".campoCantidad").val()) || 0;
                var Regalado = $(this).children("td:eq(3)").find(".campoRegalado").val();
                var Descuento = $(this).children("td:eq(4)").find(".campoDescuento").val();
                var Impuesto = parseFloat($(this).children("td:eq(5)").find(".campoImpuesto option:selected").attr("impuesto")) || 0;
                var costoNeto = $(this).children("td:eq(6)").text().replace("$", "").replace(searchRegExp, "");
                var Subtotal = $(this).children("td:eq(7)").text().replace("$", "").replace(searchRegExp, "");

                var regalado = parseFloat($(this).children("td:eq(3)").find(".campoRegalado").val()) || 0;
                var descuento = parseFloat($(this).children("td:eq(4)").find(".campoDescuento").val()) || 0;

                var real = Cantidad + regalado;
                var costo = (Cantidad / real) * Costo;
                var cantidad = Cantidad + real;

                var cantidadDescuento = (costo * cantidad) * (descuento / 100);
                var cantidadImpuesto = ((costo * cantidad) - cantidadDescuento) * (Impuesto / 100);

                //var costoBruto = ((costo * cantidad) - (cantidadDescuento + cantidadImpuesto)) / cantidad;
                var costoBruto = ((costo * cantidad) - cantidadDescuento) / cantidad;
                costoBruto = Math.round(costoBruto * 100) / 100;

                productos.push([
                    idProducto,
                    Costo,
                    Cantidad,
                    Presentacion,
                    Sucursal,
                    Descuento,
                    Impuesto,
                    costoNeto,
                    Subtotal,
                    Regalado,
                    costoBruto
                ]);
            });

            if (tipoCompra == "Contado") {
                estatus = "1";
            } else if (tipoCompra == "Credito") {
                estatus = "0";
            }

            var data = new FormData(document.getElementById("FormCobrarCompra"));
            data.append("metodo", "insertar");
            data.append("accion", "hacerCompra");
            data.append("Importe", ImportePagadoCompra);
            data.append("idProveedor", idProveedor);
            data.append("FechaCredito", fechaCredito);
            data.append("Productos", JSON.stringify(productos));
            data.append("subtotal", subtotal);
            data.append("total", total);
            data.append("TipoCompra", tipoCompra);
            data.append("Descuento", descuento);
            data.append("TipoPago", tipoPago);
            data.append("Sucursal", $("#SucursalHacerCompra").attr("attrID"));
            data.append("idOrden", $.trim($("#bGuardarOrden").attr("attrID")));

            $.ajax({
                url: "index.php",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
            })
            .done(function (res) {
                var datos = res.split("~");
                if ($.trim(datos[0]) == "Correcto") {
                    Swal.fire({
                        icon: "success",
                        title: "Compra realizada correctamente",
                    });
                    $("#ModalCobrarCompra").modal("hide");
                    $("#cargarHacerCompra").trigger("click");
                    $("#RealizarCompra").attr("idProveedor", "1");

                    var idCompra = datos[1];
                    var sucursal = datos[2];
                    var altura = 50;
                    var anchura = 310;

                    var y = parseInt(window.screen.height / 2 - altura / 2);
                    var x = parseInt(window.screen.width / 2 - anchura / 2);

                    window.open("controladores/ticketCompra.php?id="+idCompra+"&idSucursal="+sucursal,
                    "_blank",
                    "width="+anchura+",height="+altura+", top="+y+", left="+x+"");
                    $("#bGuardarOrden").attr("attrID", "");
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
            })
            .always(function () {
                $btn.prop("disabled", false).html(originalText); 
            });
        }
    });


    var btnCambio = null;
    $(document).on("click", ".bCambiarPre", function () {
        var btn = $(this);
        btnCambio = $(this);
        var data =
            "metodo=detalles&accion=hacerCompra&tipo=consultarPrese&id=" +
            btn.attr("attrID");

        $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
            beforeSend: function () {
                $("#carga").show();
            },
        })
            .done(function (res) {
                //console.log($.trim(res));
                $("#verTablaPrese").html($.trim(res));
                moneda();
                $("#modalVerPresentaciones").modal("show");
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                $("#carga").hide();
            });
    });

    $(document).on("click", ".bSeleCamPres", function () {
        const searchRegExp = new RegExp(",", "g");
        var padre = $(this).parent().parent();
        var fila = btnCambio.parent().parent();
        fila.attr("idpresentacion", $(this).attr("attrID"));
        var texto = "Sin presentación";
        if (
            padre.children("td:eq(0)").text() != "" ||
            padre.children("td:eq(1)").text() != ""
        ) {
            texto =
                padre.children("td:eq(0)").text() +
                " (" +
                padre.children("td:eq(1)").text() +
                ")";
        }
        console.log(texto);
        fila.children("td:eq(1)").children("button").html(texto);
        fila.children("td:eq(2)")
            .children("input")
            .val(
                padre
                    .children("td:eq(2)")
                    .text()
                    .replace("$", "")
                    .replace(searchRegExp, "")
            );

        //var total = parseFloat(fila.children('td:eq(2)').children('input').val()) * parseFloat(fila.children('td:eq(3)').children('input').val());
		fila.children('td:eq(4)').children("input").val(padre
                    .children("td:eq(3)")
                    .text()
                    .replace("%", "")
                    .replace(searchRegExp, ""));
        fila.children("td:eq(2)").children("input").trigger("keyup");
        CalcularSubtotal();
        $("#modalVerPresentaciones").modal("hide");
    });

    document.onkeydown = function (evt) {
        evt = evt || window.event;
        //console.log("Key: "+evt.key+" Code: "+evt.keyCode);

        if (evt.key === "F2") {
            $("#CargarProductosModalC").trigger("click");
        }
    };

    $(document).on("click", "#bGuardarOrden", function () {
        var $btn = $(this);

        // Evitar múltiples clics
        if ($btn.prop("disabled")) {
            return; // Ya está en proceso
        }

        // Guardar texto original y mostrar cargando
        var originalText = $btn.html();
        $btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

        if ($("#tbodyTablaProductosAgregados tr").length <= 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tienes que ingresar al menos un producto para realizar la compra",
            });
            $btn.prop("disabled", false).html(originalText); // Rehabilita si hay error
            return;
        }

        const searchRegExp = new RegExp(",", "g");
        var total = $("#TotalCompra").text().replace("$", "").replace(searchRegExp, "");
        var ImportePagadoCompra = parseFloat($("#ImportePagadoCompra").val());
        var idProveedor = $("#RealizarCompra").attr("idProveedor");
        var descuento = $("#Descuento").text().replace("$", "").replace(searchRegExp, "");
        var subtotal = $("#MostrarSubtotal").text().replace("$", "").replace(searchRegExp, "");

        var productos = [];
        $("#tbodyTablaProductosAgregados tr").each(function () {
            var idProducto = $(this).attr("attrid");
            var Sucursal = $("#SucursalHacerCompra").attr("attrID");
            var Presentacion = $(this).attr("idPresentacion");
            var Costo = $(this).children("td:eq(2)").find(".campoCosto").val();
            var Cantidad = $(this).children("td:eq(3)").find(".campoCantidad").val();
            var Regalado = $(this).children("td:eq(3)").find(".campoRegalado").val();
            var Descuento = $(this).children("td:eq(4)").find(".campoDescuento").val();
            var Impuesto = $(this).children("td:eq(5)").find(".campoImpuesto option:selected").attr("impuesto");
            var costoNeto = $(this).children("td:eq(6)").text().replace("$", "").replace(searchRegExp, "");
            var Subtotal = $(this).children("td:eq(7)").text().replace("$", "").replace(searchRegExp, "");
            productos.push([
                idProducto,
                Costo,
                Cantidad,
                Presentacion,
                Sucursal,
                Descuento,
                Impuesto,
                costoNeto,
                Subtotal,
                Regalado
            ]);
        });

        var tipo = "insertarOrden";
        if ($.trim($btn.attr("attrID")) != "") {
            tipo = "modificarOrden";
        }

        var data = new FormData(document.getElementById("FormCobrarCompra"));
        data.append("metodo", "modificar");
        data.append("accion", "hacerCompra");
        data.append("tipo", tipo);
        data.append("idProveedor", idProveedor);
        data.append("Productos", JSON.stringify(productos));
        data.append("subtotal", subtotal);
        data.append("total", total);
        data.append("Descuento", descuento);
        data.append("Sucursal", $("#SucursalHacerCompra").attr("attrID"));
        data.append("id", $.trim($btn.attr("attrID")));

        $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
        })
        .done(function (res) {
            var datos = res.split("~");
            if ($.trim(datos[0]) == "Correcto") {
                Swal.fire({
                    icon: "success",
                    title: "Orden de compra realizada correctamente",
                });

                $("#cargarHacerCompra").trigger("click");
                $("#RealizarCompra").attr("idProveedor", "1");

                var idCompra = datos[1];
                var sucursal = datos[2];
                var altura = 50;
                var anchura = 310;

                var y = parseInt(window.screen.height / 2 - altura / 2);
                var x = parseInt(window.screen.width / 2 - anchura / 2);

                window.open(
                    "controladores/ticketOrden.php?id=" + idCompra + "&idSucursal=" + sucursal,
                    "_blank",
                    "width=" + anchura + ", height=" + altura + ", top=" + y + ", left=" + x + ""
                );
                $btn.attr("attrID", "");
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
        })
        .always(function () {
            $btn.prop("disabled", false).html(originalText); 
        });
    });


    $(document).on("click", "#bVerOrdenes", function () {
        tablaOrdenesCompra();
        $("#modalVerOrdenes").modal("show");
    });

    $(document).on("click", ".bVerProductosOrden", function () {
        var btn = $(this);
        var data =
            "metodo=detalles&accion=hacerCompra&tipo=consultarProductosOrden&id=" +
            btn.attr("attrID");

        $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
            beforeSend: function () {
                $("#carga").show();
            },
        })
        .done(function (res) {
            //console.log($.trim(res));
            $("#verProdOrden").html($.trim(res));
            moneda();
            $("#modalVerProductosOrden").modal("show");
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            $("#carga").hide();
        });
    });

    $(document).on("click", ".bImprimirTicketOrden", function () {
        var idCompra = $(this).attr("attrid");
        var sucursal = $(this).attr("idSucursal");
        var altura = 50;
        var anchura = 310;

        var y = parseInt(window.screen.height / 2 - altura / 2);
        var x = parseInt(window.screen.width / 2 - anchura / 2);

        window.open(
            "controladores/ticketOrden.php?id=" +
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

    $(document).on("click", ".bEliminarOrden", function () {
        var btn = $(this);
        Swal.fire({
            title: "¿Estás seguro que quieres eliminar la orden de compra?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "¡No, cancelar!",
            confirmButtonText: "¡Si, eliminar!",
        }).then((result) => {
            if (result.value) {
                var data =
                    "metodo=eliminar&accion=hacerCompra&tipo=ordenCompra&id=" +
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
                                title: "Orden de compra eliminada correctamente",
                            });

                            tablaOrdenesCompra();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Error inesperado al eliminar la orden de compra.",
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

    $(document).on("click", ".bCargarOrden", function () {
        var btn = $(this);
        var data =
            "metodo=detalles&accion=hacerCompra&tipo=consultarOrden&id=" +
            btn.attr("attrID");

        $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
            beforeSend: function () {
                $("#carga").show();
            },
        })
            .done(function (res) {
                //console.log($.trim(res));
                var datos = JSON.parse($.trim(res));

                if (datos != null) {
                    //console.log(datos);
                    $("#bFolioOrdenCompra").removeClass("oculto");
                    $("#folioOrdenCompra").html(
                        datos.ID_Orden_Compra.padStart(8, "0")
                    );
                    $("#SucursalHacerCompra").attr("attrID", datos.FK_Sucursal);
                    $(".BotonLimpiarProveedor").removeClass("oculto");
                    if (datos.FK_Proveedor != 1) {
                        $("#RealizarCompra").attr(
                            "idProveedor",
                            datos.FK_Proveedor
                        );
                        $("#CargarProveedoresModalC").html(
                            "Proveedor: " +
                                datos.Proveedor +
                                "<br>Razon social: " +
                                datos.RazonSocial
                        );
                        if ($("#TipoCompra").val() == "Credito") {
                            $("#TipoCompra").trigger("change");
                        }
                    }

                    var fila = "";
                    datos.Productos.forEach((producto) => {
                        //console.log(producto);
                        fila +=
                            "\
						<tr attrid='" +
                            producto.FK_Producto +
                            "' idPresentacion='" +
                            producto.FK_Presentacion +
                            "' costo='" +
                            producto.Costo +
                            "'>\
							<td>" +
                            producto.Codigo +
                            "</td>\
							<td>" +
                            producto.Descripcion +
                            "<br><button type='button' class='btn btn-sm btn-primary bCambiarPre' attrID='" +
                            producto.FK_Producto +
                            "'>" +
                            producto.Presentacion +
                            "</button></td>\
							<td class='costoP'><input type='number' value='" +
                            producto.Costo +
                            "' min='0.1' step='any' class='form-control campoCosto'></td>\
							<td><input type='number' value='" +
                            producto.Cantidad +
                            "' min='0.1' step='any' class='form-control campoCantidad'><br>Regalado: <input type='number' value='"+producto.Regalado+"' min='0.1' step='any' class='form-control campoRegalado'></td>\
							<td><input type='number' value='" +
                            producto.Descuento +
                            "' min='0' step='any' class='form-control campoDescuento'></td>\
							<td>\
								<select class='form-control campoImpuesto'>\
								" +
                            producto.Impuesto +
                            "\
								<select/>\
							</td>\
							<td class='costoNeto'><span class='dinero'>" +
                            producto.Costo_Neto +
                            "</span></td>\
							<td class='totalP'><span class='dinero'>" +
                            producto.Subtotal +
                            "</span></td>\
							<td><button class='btn btn-danger btn-sm EliminarFila'><i class='fas fa-trash'></i></button></td>\
						</tr>\
					";

                        /*
					<td>"+codigo+"</td>\
						<td>"+descripcion+"<br><button type='button' class='btn btn-sm btn-primary bCambiarPre' attrID='"+idProducto+"'>"+presentacion+"</button></td>\
						<td class='costoP'><input type='number' value='"+costo+"' min='0.1' step='any' class='form-control campoCosto'></td>\
						<td><input type='number' value='1' min='0.1' step='any' class='form-control campoCantidad'></td>\
						<td><input type='number' value='0' min='0' step='any' class='form-control campoDescuento'></td>\
						<td>\
							<select class='form-control campoImpuesto'>\
							"+datos.impuestos[0]+"\
							<select/>\
						</td>\
						<td class='costoNeto'><span class='dinero'>0</span></td>\
						<td class='totalP'><span class='dinero'>"+costo+"</span></td>\
						<td><button class='btn btn-danger btn-sm EliminarFila'><i class='fas fa-trash'></i></button></td>\
					*/
                    });

                    $("#tbodyTablaProductosAgregados").html(fila);
                    CalcularSubtotal();
                    $("#bGuardarOrden").attr("attrID", datos.ID_Orden_Compra);
                    $("#modalVerOrdenes").modal("hide");
                }
            })
            .fail(function () {
                console.log("Error ajax");
            })
            .always(function () {
                $("#carga").hide();
            });
    });
});

function tablaOrdenesCompra() {
    ajaxMyDatatable({
        table: $("#tablaOrdenesCompra"),
        colums: ["Datos", "Proveedor", "Total", "Detalles", "Acciones"],
        sort: [0, "desc"],
        url: "index.php",
        params: {
            metodo: "consultar",
            tipo: "consultarOrdenes",
            accion: "hacerCompra",
        },
    });
}

function TablaProductosCompra() {
    ajaxMyDatatable({
        table: $("#TablaProductosCompra"),
        colums: ["Codigo", "Descripcion", "Presentacion"],
        sort: [0, "asc"],
        url: "index.php",
        params: {
            metodo: "consultar",
            tipo: "ConsultarProductos",
            accion: "hacerCompra",
        },
    });
}

function TablaProveedoresCompra() {
    ajaxMyDatatable({
        table: $("#TablaProveedoresCompra"),
        colums: ["Nombre", "Direccion", "Empresa", "Credito"],
        sort: [0, "desc"],
        url: "index.php",
        params: {
            metodo: "consultar",
            tipo: "ConsultarProveedores",
            accion: "hacerCompra",
        },
    });
}
