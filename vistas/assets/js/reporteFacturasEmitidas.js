function v_reporteFacturasEmitidas() {
    let now = new Date();
    let day = now.getDate().toString().padStart(2, "0");
    let month = (now.getMonth() + 1).toString().padStart(2, "0");
    let today = now.getFullYear() + "-" + month + "-" + day;

    $("#FechaFinalReporteFacturasEmitidas").val(today);

    now.setDate(now.getDate() - 7);
    day = now.getDate().toString().padStart(2, "0");
    month = (now.getMonth() + 1).toString().padStart(2, "0");
    today = now.getFullYear() + "-" + month + "-" + day;

    $("#FechaInicioReporteFacturasEmitidas").val(today);

    tablaReporteFacturasEmitidas();
    // CalcularTotalesVentasxDia();
}

jQuery(document).ready(function ($) {
    $(document).on("change", "#FechaFinalReporteFacturasEmitidas", function () {
        tablaReporteFacturasEmitidas();
        // CalcularTotalesVentasxDia();
    });

    $(document).on(
        "change",
        "#FechaInicioReporteFacturasEmitidas",
        function () {
            tablaReporteFacturasEmitidas();
            // CalcularTotalesVentasxDia();
        }
    );

    $(document).on("change", "#SucursalFacturasEmitidas", function () {
        tablaReporteFacturasEmitidas();
        // CalcularTotalesVentasxDia();
    });

    $(document).on("click", "#bExcelReporteFacturasEmitidas", function () {
        let palabra = $(
            ".buscadorMyDataTable[tabla=TablaReporteFacturasEmitidas]"
        ).val();
        window.open(
            "controladores/excel/excelReporteFacturasEmitidas.php?palabra=" +
                palabra +
                "&fechaInicio=" +
                $("#FechaInicioReporteFacturasEmitidas").val() +
                "&fechaFin=" +
                $("#FechaFinalReporteFacturasEmitidas").val() +
                "&sucursal=" +
                $("#SucursalFacturasEmitidas").val()
        );
    });
});

// function CalcularTotalesVentasxDia() {
//     let data =
//         "metodo=detalles&accion=ventasxdia&tipo=CalcularTotales&fechaInicio=" +
//         $("#FechaInicioReporteDia").val() +
//         "&fechaFin=" +
//         $("#FechaFinalReporteDia").val() +
//         "&sucursal=" +
//         $("#SucursalVentasXDia").val();
//     $.ajax({
//         url: "index.php",
//         type: "POST",
//         data: data,
//         beforeSend: function () {
//             $("#carga").show();
//         },
//     })
//         .done(function (res) {
//             let datos = res.split("~");
//             let SumaTotales = datos[0];
//             let SumaImportes = datos[1];
//             let SumaDescuentos = datos[2];
//             let SumaSubtotal = datos[3];
//             let SumaDevoluciones = datos[4];
//             $("#SpanTotalImportes").text(SumaImportes || 0);
//             $("#SpanTotalDescuentos").text(SumaDescuentos || 0);
//             $("#SpanTotalSubtotal").text(SumaSubtotal || 0);
//             $("#SpanTotalImpuestos").text(0);
//             $("#SpanTotalVentas").text(SumaTotales || 0);
//             $("#SpanTotalDevoluciones").text(SumaDevoluciones || 0);
//             moneda();
//         })
//         .fail(function () {
//             console.log("error");
//         })
//         .always(function () {
//             $("#carga").hide();
//         });
// }

function tablaReporteFacturasEmitidas() {
    ajaxMyDatatable({
        table: $("#TablaReporteFacturasEmitidas"),
        colums: [
            "Folio",
            "UUID",
            "Fecha",
            "RFC",
            "Cliente",
            "Importe",
            "Divisa",
            "Estatus",
        ],
        totals: [
            "Folio",
            "UUID",
            "Fecha",
            "RFC",
            "Cliente",
            "Importe",
            "Divisa",
            "Estatus",
        ],
        sort: [0, "desc"],
        url: "index.php",
        params: {
            metodo: "consultar",
            accion: "reporteFacturasEmitidas",
            fechaInicio: $("#FechaInicioReporteFacturasEmitidas").val(),
            fechaFin: $("#FechaFinalReporteFacturasEmitidas").val(),
            sucursal: $("#SucursalFacturasEmitidas").val(),
        },
    });
}
