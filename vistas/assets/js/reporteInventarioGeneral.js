let sucursalesSeleccionadasRInvenGeneral = [];
let proveedoresSeleccionados = [];
function v_reporteInventarioGeneral() {
    proveedoresReporteInventarioGeneral();
    sucursalesReporteInventarioGeneral();
    sucursalesSeleccionadasRInvenGeneral = [];
    proveedoresSeleccionados = [];
    tablaReporteInventarioGeneral();

    // $("#ProveedoresReporteInventarioGeneral").select2({
    //     placeholder: "-- Seleccione una opcion --",
    //     dropdownParent: $("#padreSelectProveedorRInventarioGral"),
    //     allowClear: true,
    //     language: {
    //         noResults: function () {
    //             return "No hay resultados";
    //         },
    //         searching: function () {
    //             return "Buscando..";
    //         },
    //     },
    // });
}

jQuery(document).ready(function ($) {
    /*$(document).on('change', '#FechaFinalReporteDia', function() {
		TablaReporteVentasxDia();
		CalcularTotalesVentasxDia();
	});
	
	$(document).on('change', '#FechaInicioReporteDia', function() {
		TablaReporteVentasxDia();
		CalcularTotalesVentasxDia();
	});

	$(document).on('change', '#SucursalVentasXDia', function() {
		TablaReporteVentasxDia();
		CalcularTotalesVentasxDia();
	});*/

    // $(document).on("click", "#bExcelReporteInventarioGeneral", function () {
    //     let palabra = $(
    //         ".buscadorMyDataTable[tabla=TablaReporteInventarioGeneral]"
    //     ).val();
    //     window.open(
    //         "controladores/excel/excelReporteInventarioGeneral.php?palabra=" +
    //             palabra +
    //             "&sucursales=" +
    //             JSON.stringify(sucursalesSeleccionadasRInvenGeneral) +
    //             "&proveedores=" +
    //             JSON.stringify(proveedoresSeleccionados)
    //     );
    // });

    // Boton para abrir el modal de seleccionar sucursales.
    $(document).on(
        "click",
        "#CargarSucursalesModalRInventarioGral",
        function () {
            $("#ModalSucursalesInventarioGeneral").modal("show");
        }
    );

    // Evento para obtener los checkbox seleccionados del modal de filtro de sucursales.
    $(document).on("click", ".CheckInputSucursal", function () {
        let boton = $(this);
        let idSucursal = $(this).attr("attrid");

        if (boton.prop("checked") == true) {
            if (
                !sucursalesSeleccionadasRInvenGeneral.some(
                    (item) => item.ID === idSucursal
                )
            ) {
                sucursalesSeleccionadasRInvenGeneral.push({
                    ID: idSucursal,
                    Nombre: boton.attr("nombre"),
                });
            }
        } else {
            const nuevoarreglo = sucursalesSeleccionadasRInvenGeneral.filter(
                (item) => item.ID !== boton.attr("attrid")
            ); //Si la desseleccionan se elimina del arreglo
            sucursalesSeleccionadasRInvenGeneral = nuevoarreglo;
        }
        // console.log(sucursalesSeleccionadasRInvenGeneral);
        sucursalesReporteInventarioGeneral();
    });

    // Evento del boton de seleccionar sucursales para filtrarlas a la tabla principal de inventario general.
    $(document).on(
        "click",
        "#SeleccionarSucursalesMarcadasRInventarioGral",
        function () {
            $("#ModalSucursalesInventarioGeneral").modal("hide");

            for (
                let i = 0;
                i < sucursalesSeleccionadasRInvenGeneral.length;
                i++
            ) {
                $(
                    `#TablaReporteInventarioGeneral thead tr th[attrID='${sucursalesSeleccionadasRInvenGeneral[i]["ID"]}']`
                ).remove();
            }

            tablaReporteInventarioGeneral();

            let textoSucursales = "";
            for (
                let i = 0;
                i < sucursalesSeleccionadasRInvenGeneral.length;
                i++
            ) {
                textoSucursales +=
                    sucursalesSeleccionadasRInvenGeneral[i]["Nombre"] + ", ";
                $("#TablaReporteInventarioGeneral thead tr").append(
                    `<th attrID='${sucursalesSeleccionadasRInvenGeneral[i]["ID"]}'>${sucursalesSeleccionadasRInvenGeneral[i]["Nombre"]}</th>`
                );
            }

            let str = textoSucursales.replace(/,\s*$/, "");
            if (sucursalesSeleccionadasRInvenGeneral.length <= 0) {
                str = "No has seleccionado sucursales";
            }
            $("#MostrarSucursalesSeleccionadasRInventarioGral").text(str);
        }
    );

    // Boton para abrir el modal de seleccionar proveedores.
    $(document).on(
        "click",
        "#CargarProveedoresModalRInventarioGral",
        function () {
            $("#ModalProveedoresInventarioGeneral").modal("show");
        }
    );

    // Evento para obtener los checkbox seleccionados del modal de filtro de proveedores.
    $(document).on("click", ".CheckInputProveedor", function () {
        let boton = $(this);
        let idProveedor = $(this).attr("attrid");

        if (boton.prop("checked") == true) {
            if (
                !proveedoresSeleccionados.some(
                    (item) => item.ID === idProveedor
                )
            ) {
                proveedoresSeleccionados.push({
                    ID: idProveedor,
                    Nombre: boton.attr("nombre"),
                });
            }
        } else {
            const nuevoarreglo = proveedoresSeleccionados.filter(
                (item) => item.ID !== idProveedor
            ); //Si la desseleccionan se elimina del arreglo
            proveedoresSeleccionados = nuevoarreglo;
        }
        // console.log({ proveedoresSeleccionados });
        proveedoresReporteInventarioGeneral();
    });

    // Evento en el boton de guardar seleccion de proveedores en el modal de filtro de proveedores para cargar la tabla principal con los datos de la seleccion.
    $(document).on(
        "click",
        "#SeleccionarProveedoresMarcadosRInventarioGral",
        function () {
            $("#ModalProveedoresInventarioGeneral").modal("hide");
            tablaReporteInventarioGeneral();
        }
    );
});

// Funcion para cargar la tabla de modal de sucursales.
function sucursalesReporteInventarioGeneral() {
    ajaxMyDatatable({
        table: $("#TablaSucursalesInventarioGeneral"),
        colums: ["Seleccionar", "Sucursal", "Direccion"],
        sort: [0, "desc"],
        url: "index.php",
        params: {
            metodo: "detalles",
            tipo: "ConsultarSucursalesRInventarioGral",
            accion: "reporteInventarioGeneral",
            SucursalesCheckeadas: JSON.stringify(
                sucursalesSeleccionadasRInvenGeneral
            ),
        },
        callback: function () {
            // Restaurar los checkboxes seleccionados despues de cargar la tabla.
            $(".CheckInputSucursal").each(function () {
                let checkbox = $(this);
                let idSucursal = checkbox.attr("attrid");

                if (
                    sucursalesSeleccionadasRInvenGeneral.some(
                        (item) => item.ID === idSucursal
                    )
                ) {
                    checkbox.prop("checked", true);
                }
            });
        },
    });
}

// Funcion para cargar la tabla de modal de proveedores.
function proveedoresReporteInventarioGeneral() {
    ajaxMyDatatable({
        table: $("#TablaProveedoresInventarioGeneral"),
        colums: ["Seleccionar", "Proveedor"],
        sort: [0, "desc"],
        url: "index.php",
        params: {
            metodo: "detalles",
            tipo: "ConsultarProveedoresRInventarioGral",
            accion: "reporteInventarioGeneral",
            ProveedoresCheckeados: JSON.stringify(proveedoresSeleccionados),
        },
        callback: function () {
            // Restaurar los checkboxes seleccionados despues de cargar la tabla.
            $(".CheckInputProveedor").each(function () {
                let checkbox = $(this);
                let idProveedor = checkbox.attr("attrid");

                if (
                    proveedoresSeleccionados.some(
                        (item) => item.ID === idProveedor
                    )
                ) {
                    checkbox.prop("checked", true);
                }
            });
        },
    });
}

// Funcion para cargar la tabla principal al inicio sin filtros.
const tablaReporteInventarioGeneral = () => {
    $("#TablaReporteInventarioGeneral").DataTable().destroy();
    let data =
        "metodo=consultar&accion=reporteInventarioGeneral&Proveedores=" +
        JSON.stringify(proveedoresSeleccionados) +
        "&Sucursales=" +
        JSON.stringify(sucursalesSeleccionadasRInvenGeneral);
    $.ajax({
        url: "index.php",
        type: "POST",
        data: data,
    })
        .done(function (res) {
            let datos = JSON.parse(res);
            $("#TablaReporteInventarioGeneral thead").html(
                datos.Sucursales.Sucursales
            );
            $("#TablaReporteInventarioGeneral tbody").html(datos.filas.Filas);

            if ($("#TablaReporteInventarioGeneral").length > 0) {
                $("#TablaReporteInventarioGeneral").DataTable({
                    dom:
                        '<"top d-flex justify-content-between align-items-center"lB>' + // Select a la izquierda y botones a la derecha
                        '<"mt-2 mb-5"f>' + // Espaciado para el buscador
                        "rt" + // Tabla
                        '<"bottom d-flex justify-content-between align-items-center mt-4"i p>', // Agrega la sección de botones
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "<i class='fas fa-copy'></i>",
                            exportOptions: { columns: ":visible" },
                        },
                        {
                            extend: "excelHtml5",
                            text: "<i class='fas fa-file-excel'></i>",
                            exportOptions: { columns: ":visible" },
                        },
                        {
                            extend: "pdfHtml5",
                            text: "<i class='fas fa-file-pdf'></i>",
                            exportOptions: { columns: ":visible" },
                        },
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/2.2.2/i18n/es-MX.json",
                    },
                });
            }
        })
        .fail(function () {
            console.log("Error ajax");
        });
};

// Funcion para la tabla principal del reporte sin uso de mydatatable.
// const tablaReporteInventarioGeneral = () => {
//     $.ajax({
//         url: "index.php",
//         type: "POST",
//         data: {
//             metodo: "consultar",
//             accion: "reporteInventarioGeneral",
//             Proveedores: JSON.stringify(proveedoresSeleccionados),
//             Sucursales: JSON.stringify(sucursalesSeleccionadasRInvenGeneral),
//         },
//         success: function (response) {
//             let data = JSON.parse(response);
//             console.log("sucursales esperadas: ", data.sucursales);
//             console.log("primer registro", data.data[0]);

//             if ($.fn.DataTable.isDataTable("#TablaReporteInventarioGeneral")) {
//                 $("#TablaReporteInventarioGeneral").DataTable().destroy();
//             }

//             let columns = [
//                 { data: "Codigo", title: "Código" },
//                 { data: "Descripcion", title: "Descripción" },
//                 { data: "Costo", title: "Costo" },
//                 { data: "Proveedor", title: "Proveedor" },
//                 { data: "Total_Existencias", title: "Total Existencias" },
//             ];

//             // Agregar las columnas de sucursales
//             data.sucursales.forEach((sucursal) => {
//                 columns.push({
//                     data: sucursal,
//                     title: sucursal,
//                     defaultContent: "",
//                 });
//             });

//             $("#TablaReporteInventarioGeneral").DataTable({
//                 data: data.data,
//                 columns: columns,
//             });
//         },
//     });
// };
// Fin de esta funcion.

// function tablaReporteInventarioGeneralFiltros() {
//     ajaxMyDatatable({
//         table: $("#TablaReporteInventarioGeneral"),
//         colums: [
//             "Codigo",
//             "Descripcion",
//             "Costo",
//             "Proveedor",
//             "Total_Existencias",
//             "Sucursal",
//         ],
//         sort: [0, "desc"],
//         url: "index.php",
//         params: {
//             metodo: "consultar",
//             accion: "reporteInventarioGeneral",
//             Proveedores: JSON.stringify(proveedoresSeleccionados),
//             Sucursales: JSON.stringify(sucursalesSeleccionadasRInvenGeneral),
//         },
//     });
// }
