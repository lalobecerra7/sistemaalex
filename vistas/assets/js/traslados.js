const audioOk = new Audio('vistas/assets/sounds/addBip.mp3');
const audioErr = new Audio('vistas/assets/sounds/notBip.mp3');

// ─────────────────────────────────────────────────────────────────────────────
// INIT
// ─────────────────────────────────────────────────────────────────────────────
function v_traslados() {
    TablaTraslados();
}

// ─────────────────────────────────────────────────────────────────────────────
// TABLAS DATATABLE
// ─────────────────────────────────────────────────────────────────────────────
function TablaTraslados() {
    ajaxMyDatatable({
        "table": $("#TablaTraslados"),
        "colums": [
            "Fecha",
            "FechaTraslado",
            "Origen",
            "Destino",
            "Concentrado",
            "Estatus",
            "Detalles",
            "Acciones"
        ],
        "sort": [0, "desc"],
        "url": "index.php",
        "params": {
            "metodo": "consultar",
            "accion": "traslados"
        }
    });
}

function tablaProductosTraslado(id) {
    ajaxMyDatatable({
        "table": $("#tablaDetallesTraslados"),
        "colums": [
            "Codigo",
            "Descripcion",
            "Presentacion",
            "Cantidad"
        ],
        "sort": [0, "asc"],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "accion": "traslados",
            "tipo": "productosTraslado",
            "id": id
        }
    });
}

function TablaProductosBuscarTraslado() {
    ajaxMyDatatable({
        "table": $("#tablaProductosBuscarTraslado"),
        "colums": [
            "Codigo",
            "Descripcion",
            "Presentacion",
            "Existencia"
        ],
        "sort": [1, "asc"],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "accion": "traslados",
            "tipo": "ProductosSucursal",
            "sucursal": $("#sucursalOrigenTraslado").val()
        }
    });
}

function TablaMovimientosTraslados() {
    ajaxMyDatatable({
        "table": $("#TablaMovimientosTraslados"),
        "colums": [
            "Traslado",
            "Producto",
            "Cantidad",
            "Origen",
            "Destino"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php",
        "params": {
            "metodo": "detalles",
            "tipo": "ConsultarMovimientosTraslados",
            "accion": "inventario",
            "sucursal": $("#sucursalOrigenTraslado").val()
        }
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────────────────────

// Parsea la respuesta JSON sin romper la cadena de callbacks.
// Si el servidor manda un warning o texto antes del JSON, muestra un error
// en lugar de dejar el loader colgado.
function parseRespuesta(res) {
    var texto = $.trim(res);
    try {
        var datos = JSON.parse(texto);
        if (datos && !Array.isArray(datos) && datos.error) {
            Swal.fire({ icon: 'warning', title: datos.error });
            return null;
        }
        return datos;
    } catch (e) {
        console.log('Respuesta no válida del servidor:', texto);
        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Respuesta inesperada del servidor. Revisa la consola para más detalle.' });
        return null;
    }
}

function badgeEstatus(estatus) {
    estatus = estatus != '' ? estatus : 'Pendiente';

    const map = {
        'Solicitud': 'bg-secondary',
        'En transito': 'bg-warning text-dark',
        'Completado': 'bg-success',
        'Cancelado': 'bg-danger',
        'Pendiente': 'bg-warning text-dark',
        'Enviada': 'bg-info text-dark',
        'Aceptada': 'bg-success',
        'Rechazada': 'bg-danger',
        'Procesada': 'bg-success'
    };
    return `<span class="badge rounded-pill ${map[estatus] || 'bg-secondary'}">${estatus}</span>`;
}

function agregarFilaProducto(codigo, descripcion, presentacion, presentacionID, productoID, existencia, cantidad = '') {
    var fila = `<tr data-producto="${productoID}" data-presentacion="${presentacionID}" data-codigo="${codigo}">
        <td>${codigo}</td>
        <td>${descripcion}</td>
        <td>${presentacion}</td>
        <td class="cantidad">${existencia}</td>
        <td><input type="number" min="0.01" step="any" class="form-control form-control-sm inputCantidadTraslado" style="width:90px;" value="${cantidad}" placeholder="0"></td>
        <td><button type="button" class="btn btn-danger btn-sm bEliminarFilaTraslado"><i class="fas fa-trash"></i></button></td>
    </tr>`;
    $('#tbodyProductosTraslado').append(fila);
}

function badgeAccion(accion) {
    const map = { 'Merma': 'bg-danger', 'Devolucion': 'bg-warning text-dark', 'Reposicion': 'bg-info text-dark' };
    const labels = { 'Merma': 'Merma', 'Devolucion': 'Devolución', 'Reposicion': 'Reposición' };
    return `<span class="badge rounded-pill ${map[accion] || 'bg-secondary'}">${labels[accion] || accion}</span>`;
}

function btnImprimirAccion(btn) {
    return `<button type="button" class="btn btn-sm btn-info bImprimirAccion mb-1"
        data-traslado="${btn.attr('data-traslado')}"
        data-producto="${btn.attr('data-producto')}"
        data-presentacion="${btn.attr('data-presentacion')}">
        <i class="fas fa-print"></i>
    </button>`;
}

// ─────────────────────────────────────────────────────────────────────────────
// EVENTS
// ─────────────────────────────────────────────────────────────────────────────
jQuery(document).ready(function ($) {

    $(document).on('click', '.bDetalleTraslado', function () {
        var btn = $(this);
        tablaProductosTraslado(btn.attr('attrid'));
        $("#modalDetallesTraslados").modal('show');
    });

    // ── Nuevo traslado ──────────────────────────────────────────────────────
    $(document).on('click', '#bNuevoTraslado', function () {
        $('#formTraslado')[0].reset();
        $('#tbodyProductosTraslado').empty();
        $('#bGuardarTraslado').attr('tipo', 'insertar').removeAttr('attrid');
        $('#tituloModalTraslado').text('Agregar Traslado');
        var hoy = new Date().toISOString().split('T')[0];
        $('#fechaTraslado').val(hoy);
        $('#modalTraslado').modal('show');
    });

    // ── Buscar producto (lupa) ──────────────────────────────────────────────
    $(document).on('click', '#bBuscarProductoTraslado', function () {
        if (!$('#sucursalOrigenTraslado').val()) {
            Swal.fire({ icon: 'warning', title: 'Selecciona la sucursal origen primero' });
            return;
        }
        TablaProductosBuscarTraslado();
        $('#modalBuscarProductoTraslado').modal('show');
    });

    // ── Seleccionar producto de la tabla de búsqueda ────────────────────────
    $(document).on('click', '#tablaProductosBuscarTraslado tbody tr', function () {
        var span = $(this).closest('tr').children('td:eq(0)').children('span:eq(0)');
        if (!span.length) return;
        agregarFilaProducto(
            span.attr('data-codigo'),
            span.attr('data-descripcion'),
            span.attr('data-presentacion'),
            span.attr('data-presentacionid'),
            span.attr('data-productoid'),
            span.attr('data-existencia')
        );
        $('#modalBuscarProductoTraslado').modal('hide');
    });

    // ── Agregar producto por código ─────────────────────────────────────────
    $(document).on('click', '#bAgregarProductoTraslado', function () {
        var codigo = $.trim($('#codigoProductoTraslado').val());
        if (!codigo) return;
        if (!$('#sucursalOrigenTraslado').val()) {
            Swal.fire({ icon: 'warning', title: 'Selecciona la sucursal origen primero' });
            return;
        }

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                metodo: 'detalles',
                accion: 'traslados',
                tipo: 'BuscarProductoCodigo',
                codigo: codigo,
                sucursal: $('#sucursalOrigenTraslado').val()
            },
            beforeSend: function () { $('#carga').show(); }
        })
            .done(function (res) {
                var r = parseRespuesta(res);
                if (!r) return;
                agregarFilaProducto(r.Codigo, r.Descripcion, r.Presentacion, r.FK_Presentacion, r.FK_Producto, r.Existencia);
                $('#codigoProductoTraslado').val('').focus();
            })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // Enter en el campo de código
    $(document).on('keypress', '#codigoProductoTraslado', function (e) {
        if (e.which === 13) { e.preventDefault(); $('#bAgregarProductoTraslado').trigger('click'); }
    });

    // ── Eliminar fila de producto ───────────────────────────────────────────
    $(document).on('click', '.bEliminarFilaTraslado', function () {
        $(this).closest('tr').remove();
    });

    // ── Pedido sugerido ─────────────────────────────────────────────────────
    $(document).on('click', '#bGenerarPedidoSugerido', function () {
        var fi = $('#fechaInicioSugerido').val();
        var ff = $('#fechaFinSugerido').val();
        var destino = $('#sucursalDestinoTraslado').val();

        if (!fi || !ff || !destino) {
            Swal.fire({ icon: 'warning', title: 'Completa las fechas y la sucursal destino para generar el pedido sugerido' });
            return;
        }

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                metodo: 'detalles',
                accion: 'traslados',
                tipo: 'PedidoSugerido',
                fechaInicio: fi,
                fechaFin: ff,
                sucursal: destino
            },
            beforeSend: function () { $('#carga').show(); }
        })
            .done(function (res) {
                var datos = parseRespuesta(res);
                if (!datos) return;
                if (!datos.length) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No se encontraron ventas en ese período para la sucursal destino'
                    });
                    return;
                }
                $('#tbodyProductosTraslado').empty();

                $.each(datos, function (i, p) {
                    agregarFilaProducto(p.Codigo, p.Descripcion, p.Presentacion, p.FK_Presentacion, p.FK_Producto, p.Existencia, p.Cantidad);
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Pedido sugerido generado',
                    timer: 1200,
                    showConfirmButton: false
                });
            })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // ── Guardar traslado ────────────────────────────────────────────────────
    $(document).on('click', '#bGuardarTraslado', function () {
        var origen = $('#sucursalOrigenTraslado').val();
        var destino = $('#sucursalDestinoTraslado').val();
        var fecha = $('#fechaTraslado').val();
        var estatus = $('#estatusTraslado').val() || 'Solicitud';

        if (!origen || !destino || !fecha) {
            Swal.fire({ icon: 'warning', title: 'Completa origen, destino y fecha' });
            return;
        }
        if (origen === destino) {
            Swal.fire({ icon: 'warning', title: 'El origen y destino no pueden ser iguales' });
            return;
        }

        var productos = [];
        $('#tbodyProductosTraslado tr').each(function () {
            var cantidad = parseFloat($(this).find('.inputCantidadTraslado').val()) || 0;
            if (cantidad > 0) {
                productos.push({
                    producto: $(this).attr('data-producto'),
                    presentacion: $(this).attr('data-presentacion'),
                    cantidad: cantidad
                });
            }
        });

        if (!productos.length) {
            Swal.fire({ icon: 'warning', title: 'Agrega al menos un producto con cantidad' });
            return;
        }

        var tipo = $('#bGuardarTraslado').attr('tipo') || 'insertar';
        var id = $('#bGuardarTraslado').attr('attrid') || '';

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                metodo: tipo,
                accion: 'traslados',
                tipo: 'GuardarTraslado',
                origen: origen,
                destino: destino,
                fecha: fecha,
                estatus: estatus,
                productos: JSON.stringify(productos),
                id: id
            },
            beforeSend: function () { $('#carga').show(); }
        })
            .done(function (res) {
                if ($.trim(res) === 'Correcto') {
                    Swal.fire({ icon: 'success', title: tipo === 'insertar' ? 'Traslado guardado correctamente' : 'Traslado actualizado correctamente' });
                    TablaTraslados();
                    $('#modalTraslado').modal('hide');
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Error inesperado al guardar el traslado.' });
                    console.log($.trim(res));
                }
            })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // ── Editar traslado (solo Solicitud) ────────────────────────────────────
    $(document).on('click', '.bEditarTraslado', function () {
        var id = $(this).attr('attrid');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: { metodo: 'detalles', accion: 'traslados', tipo: 'DatosTraslado', id: id },
            beforeSend: function () { $('#carga').show(); }
        })
            .done(function (res) {
                var d = parseRespuesta(res);
                if (!d) return;
                $('#formTraslado')[0].reset();
                $('#tbodyProductosTraslado').empty();
                $('#tituloModalTraslado').text('Editar Traslado');
                $('#sucursalOrigenTraslado').val(d.FK_Sucursal_Origen);
                $('#sucursalDestinoTraslado').val(d.FK_Sucursal_Destino);
                $('#fechaTraslado').val(d.Fecha_Traslado);
                $('#estatusTraslado').val(d.Estatus);
                $('#bGuardarTraslado').attr('tipo', 'modificar').attr('attrid', d.ID_Traslado);

                $.each(d.productos, function (i, p) {
                    agregarFilaProducto(p.Codigo, p.Descripcion, p.Presentacion, p.FK_Presentacion, p.FK_Producto, p.Existencia, p.Cantidad_Solicitada);
                });

                $('#modalTraslado').modal('show');
            })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // ── Concentrado / Salida (origen verifica lo que va a enviar, por lote) ─
    $(document).on('click', '.bConcentradoTraslado', function () {
        var id = $(this).attr('attrid');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: { metodo: 'detalles', accion: 'traslados', tipo: 'Concentrado', id: id },
            beforeSend: function () { $('#carga').show(); }
        })
            .done(function (res) {
                var d = parseRespuesta(res);
                if (!d) return;

                // Se asigna hasta que la respuesta es válida, para que el
                // autoguardado al cerrar no use un ID de un concentrado que no cargó.
                $('#footerConcentrado').attr('attrid', id);

                $('#folioConcentrado').text(String(d.ID_Traslado).padStart(8, '0'));
                $('#origenConcentrado').text(d.Origen);
                $('#destinoConcentrado').text(d.Destino);

                var tbody = '';
                $.each(d.productos, function (i, p) {
                    var optLotes = '';
                    $.each(p.lotes_origen, function (j, l) {
                        optLotes += `<p class="m-0" attrID="${l.ID_Lote}" data-existencia="${l.Cantidad}">Lote - ${l.ID_Lote} ${l.Nombre} (<span class="cantidad">${l.Cantidad}</span>)</p>`;
                    });

                    let clase = '';
                    if (parseFloat(p.Cantidad_Verificada) < parseFloat(p.Cantidad_Solicitada) && parseFloat(p.Cantidad_Verificada) > 0) {
                        clase = 'table-warning';
                    } else if (parseFloat(p.Cantidad_Verificada) >= parseFloat(p.Cantidad_Solicitada)) {
                        clase = 'table-success';
                    }

                    let estatus = 'Pendiente';
                    if (parseFloat(p.Cantidad_Verificada) >= parseFloat(p.Cantidad_Solicitada)) {
                        estatus = 'Completado';
                    }

                    tbody += `<tr class="filaConcentrado ${clase}"
                    data-detalle="${p.ID_Detalle_Traslado}"
                    data-producto="${p.FK_Producto}"
                    data-presentacion="${p.FK_Presentacion}"
                    data-codigo="${p.Codigo}">
                    <td>${p.Codigo}</td>
                    <td>${p.Descripcion}<br><small class="text-muted">${p.Presentacion}</small></td>
                    <td class="cantidad">${p.Cantidad_Solicitada}</td>
                    <td class="existenciaConcentrado"><span class="cantidad">${p.Existencia}</span>${optLotes}</td>
                    <td class="verificadosConcentrado"><span class="cantidad">${p.Cantidad_Verificada}</span>${p.Lotes_Salida}</td>
                    <td>${badgeEstatus(estatus)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success bVerificarConcentrado" title="Verificar"><i class="fas fa-check"></i></button>
                    </td>
                </tr>`;
                });

                $('#tbodyConcentradoTraslado').html(tbody || '<tr><td colspan="7" class="text-center">Sin productos</td></tr>');

                moneda();
                $('#modalConcentradoTraslado').modal('show');
            })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // ── Verificar producto en concentrado (click en ✓) ─────────────────────
    $(document).on('click', '.bVerificarConcentrado', function () {
        var fila = $(this).closest('tr');
        var cantidad = parseFloat(fila.children('td:eq(2)').text().replaceAll(',', '')) || 0;
        var enviado = parseFloat(fila.children('td:eq(4)').children('span.cantidad').text().replaceAll(',', '')) || 0;

        if (enviado < cantidad) {
            fila.children('td:eq(4)').children('span.cantidad').text(cantidad);
        }

        fila.addClass('table-success').removeClass('table-warning');
        fila.children('td:eq(5)').html(badgeEstatus('Completado'));

        moneda();
        audioOk.play();
    });

    // ── Verificar por código en concentrado ────────────────────────────────
    $(document).on('submit', '#formVerificarTrasladoSalida', function (e) {
        e.preventDefault();
        var separa = $.trim($('#codigoVeridicarSalida').val()).split('~');
        var codigo = separa[0];
        var loteEsc = separa[1] || '';
        var encontro = false;
        let filaActual = null;

        $('#tbodyConcentradoTraslado tr.filaConcentrado').each(function () {
            if ($(this).attr('data-codigo') === codigo) {
                filaActual = $(this);
                var enviado = parseFloat($(this).children('td:eq(2)').text().replaceAll(',', '')) || 0;
                var verificado = parseFloat($(this).children('td:eq(4)').children('span.cantidad').text().replaceAll(',', '')) || 0;

                if (loteEsc) {
                    let cantidadLote = parseFloat($(this).find('.verificadosConcentrado').children('p[attrLote="' + loteEsc + '"]').children('span.cantidad').text().replaceAll(',', '')) || 0;

                    if (cantidadLote > 0) {
                        cantidadLote++;
                        $(this).children('td:eq(4)').children('p[attrLote="' + loteEsc + '"]').children('span.cantidad').text(cantidadLote);
                    } else {
                        $(this).children('td:eq(4)').append(
                            `<p class="m-0" attrLote="${loteEsc}">Lote - ${loteEsc} (<span class="cantidad">1</span>)</p>`
                        );
                    }
                }

                verificado++;
                $(this).children('td:eq(4)').children('span.cantidad').text(verificado);

                if (verificado >= enviado) {
                    $(this).addClass('table-success').removeClass('table-warning');
                    $(this).children('td:eq(5)').html(badgeEstatus('Completado'));
                } else {
                    $(this).addClass('table-warning').removeClass('table-success');
                    $(this).children('td:eq(5)').html(badgeEstatus('Pendiente'));
                }

                encontro = true;
                audioOk.play();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: codigo + ': ' + verificado,
                    showConfirmButton: false,
                    timer: 300,
                    didClose: () => {
                        $("#codigoVeridicarSalida").val('').focus();
                        setTimeout(() => {
                            if (filaActual && filaActual.length > 0) {
                                const container = filaActual.closest('.modal');
                                const offsetFila = filaActual.position().top;
                                const scrollActual = container.scrollTop();
                                container.scrollTop(scrollActual + offsetFila - 50);
                            }
                        }, 0);
                    }
                });

                moneda();
                return false;
            }
        });

        if (!encontro) {
            audioErr.play();
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'Producto no encontrado o ya verificado',
                showConfirmButton: false,
                timer: 400,
                didClose: () => {
                    $("#codigoVeridicarSalida").val('').focus();
                }
            });
        }
    });

    // ── Recepción ───────────────────────────────────────────────────────────
    $(document).on('click', '.bEntradaTraslado', function () {
        var id = $(this).attr('attrid');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: { metodo: 'detalles', accion: 'traslados', tipo: 'Concentrado', id: id },
            beforeSend: function () { $('#carga').show(); }
        })
            .done(function (res) {
                var d = parseRespuesta(res);
                if (!d) return;

                $('#footerRecepcion').attr('attrid', id);

                $('#folioRecepcion').text(String(d.ID_Traslado).padStart(8, '0'));
                $('#origenRecepcion').text(d.Origen);
                $('#destinoRecepcion').text(d.Destino);

                var tbody = '';
                $.each(d.productos, function (i, p) {
                    var optLotes = '';
                    $.each(p.lotes_destino, function (j, l) {
                        optLotes += `<p class="m-0" attrID="${l.ID_Lote}" data-existencia="${l.Cantidad}">Lote - ${l.ID_Lote} ${l.Nombre} (<span class="cantidad">${l.Cantidad}</span>)</p>`;
                    });

                    let clase = '';
                    if (parseFloat(p.Cantidad_Entrada) < parseFloat(p.Cantidad_Solicitada) && parseFloat(p.Cantidad_Entrada) > 0) {
                        clase = 'table-warning';
                    } else if (parseFloat(p.Cantidad_Entrada) >= parseFloat(p.Cantidad_Solicitada)) {
                        clase = 'table-success';
                    }

                    let estatus = 'Pendiente';
                    if (parseFloat(p.Cantidad_Entrada) >= parseFloat(p.Cantidad_Solicitada)) {
                        estatus = 'Completado';
                    }

                    tbody += `<tr class="filaRecepcion ${clase}"
                    data-detalle="${p.ID_Detalle_Traslado}"
                    data-producto="${p.FK_Producto}"
                    data-presentacion="${p.FK_Presentacion}"
                    data-codigo="${p.Codigo}">
                    <td>${p.Codigo}</td>
                    <td>${p.Descripcion}<br><small class="text-muted">${p.Presentacion}</small></td>
                    <td class="cantidad">${p.Cantidad_Solicitada}</td>
                    <td class="existenciaRecepcion"><span class="cantidad">${p.Existencia_Destino}</span>${optLotes}</td>
                    <td class="verificadosRecepcion"><span class="cantidad">${p.Cantidad_Entrada}</span>${p.Lotes_Entrada}</td>
                    <td>${badgeEstatus(estatus)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success bVerificarRecepcion" title="Verificar"><i class="fas fa-check"></i></button>
                    </td>
                </tr>`;
                });

                $('#tbodyConcentradoRecepcion').html(tbody || '<tr><td colspan="7" class="text-center">Sin productos</td></tr>');

                moneda();
                $('#modalConcentradoRecepcion').modal('show');
            })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // ── Verificar producto en recepción (click en ✓) ───────────────────────
    $(document).on('click', '.bVerificarRecepcion', function () {
        var fila = $(this).closest('tr');
        var cantidad = parseFloat(fila.children('td:eq(2)').text().replaceAll(',', '')) || 0;
        var enviado = parseFloat(fila.children('td:eq(4)').children('span.cantidad').text().replaceAll(',', '')) || 0;

        if (enviado < cantidad) {
            fila.children('td:eq(4)').children('span.cantidad').text(cantidad);
        }

        fila.addClass('table-success').removeClass('table-warning');
        fila.children('td:eq(5)').html(badgeEstatus('Completado'));

        moneda();
        audioOk.play();
    });

    // ── Verificar por código en recepción ──────────────────────────────────
    $(document).on('submit', '#formVerificarTrasladoRecepcion', function (e) {
        e.preventDefault();
        var separa = $.trim($('#codigoVeridicarRecepcion').val()).split('~');
        var codigo = separa[0];
        var loteEsc = separa[1] || '';
        var encontro = false;
        let filaActual = null;

        $('#tbodyConcentradoRecepcion tr.filaRecepcion').each(function () {
            if ($(this).attr('data-codigo') === codigo) {
                filaActual = $(this);
                var enviado = parseFloat($(this).children('td:eq(2)').text().replaceAll(',', '')) || 0;
                var verificado = parseFloat($(this).children('td:eq(4)').children('span.cantidad').text().replaceAll(',', '')) || 0;

                if (loteEsc) {
                    let cantidadLote = parseFloat($(this).find('.verificadosRecepcion').children('p[attrLote="' + loteEsc + '"]').children('span.cantidad').text().replaceAll(',', '')) || 0;

                    if (cantidadLote > 0) {
                        cantidadLote++;
                        $(this).children('td:eq(4)').children('p[attrLote="' + loteEsc + '"]').children('span.cantidad').text(cantidadLote);
                    } else {
                        $(this).children('td:eq(4)').append(
                            `<p class="m-0" attrLote="${loteEsc}">Lote - ${loteEsc} (<span class="cantidad">1</span>)</p>`
                        );
                    }
                }

                verificado++;
                $(this).children('td:eq(4)').children('span.cantidad').text(verificado);

                if (verificado >= enviado) {
                    $(this).addClass('table-success').removeClass('table-warning');
                    $(this).children('td:eq(5)').html(badgeEstatus('Completado'));
                } else {
                    $(this).addClass('table-warning').removeClass('table-success');
                    $(this).children('td:eq(5)').html(badgeEstatus('Pendiente'));
                }

                encontro = true;
                audioOk.play();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: codigo + ': ' + verificado,
                    showConfirmButton: false,
                    timer: 300,
                    didClose: () => {
                        $("#codigoVeridicarRecepcion").val('').focus();
                        setTimeout(() => {
                            if (filaActual && filaActual.length > 0) {
                                const container = filaActual.closest('.modal');
                                const offsetFila = filaActual.position().top;
                                const scrollActual = container.scrollTop();
                                container.scrollTop(scrollActual + offsetFila - 50);
                            }
                        }, 0);
                    }
                });

                moneda();
                return false;
            }
        });

        if (!encontro) {
            audioErr.play();
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'Producto no encontrado o ya verificado',
                showConfirmButton: false,
                timer: 400,
                didClose: () => {
                    $("#codigoVeridicarRecepcion").val('').focus();
                }
            });
        }
    });

    // ── Al cerrar modal de salida → guarda automático ──────────────────────
    $(document).on('hide.bs.modal', '#modalConcentradoTraslado', function () {
        var id = $('#footerConcentrado').attr('attrid');
        if (!id) return;

        var productos = [];
        $('#tbodyConcentradoTraslado tr.filaConcentrado').each(function () {
            var fila = $(this);
            var cantidad = parseFloat(fila.children('td:eq(4)').children('span.cantidad').text().replaceAll(',', '')) || 0;
            var lotes = [];

            fila.children('td:eq(4)').children('p[attrLote]').each(function () {
                var cantLote = parseFloat($(this).children('span.cantidad').text().replaceAll(',', '')) || 0;
                if (cantLote > 0) {
                    lotes.push({ lote: $(this).attr('attrLote'), cantidad: cantLote });
                }
            });

            if (cantidad > 0) {
                productos.push({ producto: fila.attr('data-producto'), presentacion: fila.attr('data-presentacion'), cantidad: cantidad, lotes: lotes });
            }
        });

        if (!productos.length) return;

        $.ajax({
            url: 'index.php', type: 'POST',
            data: {
                metodo: 'detalles',
                accion: 'traslados',
                tipo: 'GuardarVerificacion',
                id: id,
                tipo_mov: 'Salida',
                productos: JSON.stringify(productos)
            }
        }).done(function (res) {
            if ($.trim(res) === 'Correcto') {
                TablaTraslados();
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Error inesperado al guardar la salida.' });
                console.log('Error salida:', $.trim(res));
            }
        });
    });

    // ── Al cerrar modal de recepción → guarda automático ───────────────────
    $(document).on('hide.bs.modal', '#modalConcentradoRecepcion', function () {
        var id = $('#footerRecepcion').attr('attrid');
        if (!id) return;

        var productos = [];
        $('#tbodyConcentradoRecepcion tr.filaRecepcion').each(function () {
            var fila = $(this);
            var cantidad = parseFloat(fila.children('td:eq(4)').children('span.cantidad').text().replaceAll(',', '')) || 0;
            var lotes = [];

            fila.children('td:eq(4)').children('p[attrLote]').each(function () {
                var cantLote = parseFloat($(this).children('span.cantidad').text().replaceAll(',', '')) || 0;
                if (cantLote > 0) {
                    lotes.push({ lote: $(this).attr('attrLote'), cantidad: cantLote });
                }
            });

            if (cantidad > 0) {
                productos.push({ producto: fila.attr('data-producto'), presentacion: fila.attr('data-presentacion'), cantidad: cantidad, lotes: lotes });
            }
        });

        if (!productos.length) return;

        $.ajax({
            url: 'index.php', type: 'POST',
            data: {
                metodo: 'detalles',
                accion: 'traslados',
                tipo: 'GuardarVerificacion',
                id: id,
                tipo_mov: 'Entrada',
                productos: JSON.stringify(productos)
            }
        }).done(function (res) {
            if ($.trim(res) === 'Correcto') {
                TablaTraslados();
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Error inesperado al guardar la recepción.' });
                console.log('Error entrada:', $.trim(res));
            }
        });
    });

    // ── Cancelar traslado ───────────────────────────────────────────────────
    $(document).on('click', '.bCancelarTraslado', function () {
        var btn = $(this);
        Swal.fire({
            title: '¿Motivo de cancelación?',
            input: 'textarea',
            inputPlaceholder: 'Escribe el motivo...',
            showCancelButton: true,
            confirmButtonText: 'Cancelar traslado',
            cancelButtonText: 'No, volver',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: { metodo: 'modificar', accion: 'traslados', tipo: 'CancelarTraslado', id: btn.attr('attrid'), motivo: result.value || '' },
                    beforeSend: function () { $('#carga').show(); }
                })
                    .done(function (res) {
                        if ($.trim(res) === 'Correcto') {
                            Swal.fire({ icon: 'success', title: 'Traslado cancelado' });
                            TablaTraslados();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error al cancelar el traslado', text: $.trim(res) });
                            console.log($.trim(res));
                        }
                    })
                    .fail(function () { console.log('Error ajax'); })
                    .always(function () { $('#carga').hide(); });
            }
        });
    });

    // ── Eliminar traslado ───────────────────────────────────────────────────
    $(document).on('click', '.bEliminarTraslado', function () {
        var btn = $(this);
        Swal.fire({
            title: '¿Eliminar este traslado?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: { metodo: 'eliminar', accion: 'traslados', tipo: 'EliminarTraslado', id: btn.attr('attrid') },
                    beforeSend: function () { $('#carga').show(); }
                })
                    .done(function (res) {
                        if ($.trim(res) === 'Correcto') {
                            Swal.fire({ icon: 'success', title: 'Traslado eliminado correctamente' });
                            TablaTraslados();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error al eliminar el traslado' });
                            console.log($.trim(res));
                        }
                    })
                    .fail(function () { console.log('Error ajax'); })
                    .always(function () { $('#carga').hide(); });
            }
        });
    });

    // ── Imprimir ticket del traslado (botón de la tabla) ───────────────────
    // CORRECCIÓN: antes había dos handlers para .bImprimirTraslado y se abrían
    // dos ventanas (PDF + ticket). El PDF del concentrado sigue disponible con
    // el botón "Imprimir concentrado" dentro del modal de concentrado general.
    $(document).on('click', '.bImprimirTraslado', function () {
        var idTraslado = $(this).attr("attrID");
        var altura = 50;
        var anchura = 310;

        var y = parseInt((window.screen.height / 2) - (altura / 2));
        var x = parseInt((window.screen.width / 2) - (anchura / 2));
        window.open("controladores/ticketTraslado.php?id=" + idTraslado, '_blank', "width=" + anchura + ", height=" + altura + ", top=" + y + ", left=" + x + "");
    });

    // ── Abrir concentrado general ───────────────────────────────────────────
    $(document).on('click', '.bVerConcentradoTraslado', function () {
        var id = $(this).attr('attrid');
        $('#bImprimirConcentradoGeneral').attr('data-id', id);

        $.ajax({
            url: 'index.php', type: 'POST',
            data: { metodo: 'detalles', accion: 'traslados', tipo: 'Concentrado', id: id },
            beforeSend: function () { $('#carga').show(); }
        }).done(function (res) {
            var d = parseRespuesta(res);
            if (!d) return;

            $('#folioConcentradoGeneral').text(String(d.ID_Traslado).padStart(8, '0'));
            $('#origenConcentradoGeneral').text(d.Origen);
            $('#destinoConcentradoGeneral').text(d.Destino);

            var tbody = '';
            $.each(d.productos, function (i, p) {
                var solicitada = parseFloat(p.Cantidad_Solicitada) || 0;
                var salida = parseFloat(p.Cantidad_Verificada) || 0;
                var entrada = parseFloat(p.Cantidad_Entrada) || 0;
                var diferencia = entrada - solicitada; // negativo = falta, positivo = excedente
                var faltante = (salida - entrada) < 0 ? 0 : (salida - entrada);
                var tieneDif = entrada < solicitada;

                var claseRow = '';
                if (salida == 0 && entrada == 0) {
                    claseRow = '';
                } else if (entrada >= solicitada) {
                    claseRow = 'table-success';
                } else if (entrada > 0) {
                    claseRow = 'table-warning';
                } else if (salida > 0) {
                    claseRow = 'table-info';
                }

                var badgeAccionExist = p.Accion ? badgeAccion(p.Accion) : '';
                if (p.Accion && p.Observaciones) {
                    badgeAccionExist += '<br><small class="text-muted">' + p.Observaciones + '</small>';
                }

                var btnAccion = '';
                if (tieneDif && (p.Estatus_Accion == '' || p.Estatus_Accion == 'Pendiente') && (p.Tipo_Usuario == 'Administrador' || d.FK_Sucursal_Destino == p.Sucursal_Usuario)) {
                    btnAccion = `<button type="button" class="btn btn-sm btn-warning bRegistrarAccion mb-1"
                        data-traslado="${d.ID_Traslado}"
                        data-producto="${p.FK_Producto}"
                        data-presentacion="${p.FK_Presentacion}"
                        data-solicitada="${solicitada}"
                        data-salida="${salida}"
                        data-entrada="${entrada}"
                        data-faltante="${faltante}"
                        data-descripcion="${p.Descripcion}"
                        data-presnombre="${p.Presentacion}"
                        data-usuario="${p.Tipo_Usuario}"
                        data-origen="${d.FK_Sucursal_Origen}"
                        data-sucursal="${p.Sucursal_Usuario}">
                        <i class="fas fa-exclamation-triangle"></i> Acción
                    </button>`;
                }

                var btnDevolver = '';
                var btnMerma = '';
                if (p.Accion == 'Devolucion' && p.Estatus_Accion == 'Enviada' && (p.Tipo_Usuario == 'Administrador' || d.FK_Sucursal_Origen == p.Sucursal_Usuario)) {
                    btnDevolver = `<button type="button" class="btn btn-sm btn-primary bDevolverInveTraslado mb-1"
                        data-traslado="${d.ID_Traslado}"
                        data-producto="${p.FK_Producto}"
                        data-presentacion="${p.FK_Presentacion}"
                        data-solicitada="${solicitada}"
                        data-salida="${salida}"
                        data-entrada="${entrada}"
                        data-faltante="${faltante}"
                        data-descripcion="${p.Descripcion}"
                        data-presnombre="${p.Presentacion}"
                        title="Devolver a Inventario">
                        <i class="fas fa-backward"></i> Devolver
                    </button>`;

                    btnMerma = `<button type="button" class="btn btn-sm btn-danger bMermaInveTraslado mb-1"
                        data-traslado="${d.ID_Traslado}"
                        data-producto="${p.FK_Producto}"
                        data-presentacion="${p.FK_Presentacion}"
                        data-solicitada="${solicitada}"
                        data-salida="${salida}"
                        data-entrada="${entrada}"
                        data-faltante="${faltante}"
                        data-descripcion="${p.Descripcion}"
                        data-presnombre="${p.Presentacion}"
                        title="Registar Merma">
                        <i class="fa-solid fa-minus"></i> Merma
                    </button>`;
                }

                var btnAceptar = '';
                var btnRechazar = '';
                if (p.Accion == 'Reposicion' && p.Estatus_Accion == 'Enviada' && (p.Tipo_Usuario == 'Administrador' || d.FK_Sucursal_Origen == p.Sucursal_Usuario)) {
                    btnAceptar = `<button type="button" class="btn btn-sm btn-success bAceptarReposicion mb-1"
                        data-traslado="${d.ID_Traslado}"
                        data-producto="${p.FK_Producto}"
                        data-presentacion="${p.FK_Presentacion}"
                        data-solicitada="${solicitada}"
                        data-salida="${salida}"
                        data-entrada="${entrada}"
                        data-faltante="${faltante}"
                        data-descripcion="${p.Descripcion}"
                        data-presnombre="${p.Presentacion}"
                        title="Aceptar reposición">
                        <i class="fas fa-check"></i> Aceptar
                    </button>`;

                    btnRechazar = `<button type="button" class="btn btn-sm btn-danger bRechazarReposicion mb-1"
                        data-traslado="${d.ID_Traslado}"
                        data-producto="${p.FK_Producto}"
                        data-presentacion="${p.FK_Presentacion}"
                        data-solicitada="${solicitada}"
                        data-salida="${salida}"
                        data-entrada="${entrada}"
                        data-faltante="${faltante}"
                        data-descripcion="${p.Descripcion}"
                        data-presnombre="${p.Presentacion}"
                        title="Rechazar reposición">
                        <i class="fas fa-times"></i> Rechazar
                    </button>`;
                }

                var btnImprimir = '';
                if (p.Accion) {
                    btnImprimir = `<button type="button" class="btn btn-sm btn-info bImprimirAccion mb-1"
                        data-traslado="${d.ID_Traslado}"
                        data-producto="${p.FK_Producto}"
                        data-presentacion="${p.FK_Presentacion}">
                        <i class="fas fa-print"></i>
                    </button>`;
                }

                var difTexto = diferencia >= 0
                    ? `<span class="text-success">+${diferencia}</span>`
                    : `<span class="text-danger fw-bold">${diferencia}</span>`;

                tbody += `<tr class="filaConcentradoGeneral ${claseRow}"
                data-producto="${p.FK_Producto}"
                data-presentacion="${p.FK_Presentacion}">
                <td>${p.Codigo}</td>
                <td>${p.Descripcion}<br><small class="text-muted">${p.Presentacion}</small></td>
                <td class="cantidad">${solicitada}</td>
                <td>
                    <span class="cantidad fw-bold">${salida}</span>
                    ${p.Lotes_Salida}
                </td>
                <td>
                    <span class="cantidad fw-bold">${entrada}</span>
                    ${p.Lotes_Entrada}
                </td>
                <td>${difTexto}</td>
                <td class="tdEstatusGeneral">${badgeEstatus(p.Estatus_Accion)}</td>
                <td class="tdAccionRegistrada">${badgeAccionExist}</td>
                <td>${btnAccion}${btnImprimir}${btnDevolver}${btnMerma}${btnAceptar}${btnRechazar}</td>
            </tr>`;
            });

            $('#tbodyConcentradoGeneral').html(tbody || '<tr><td colspan="9" class="text-center">Sin productos</td></tr>');
            moneda();
            $('#modalConcentradoGeneral').modal('show');
        })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // ── Abrir modal de acción ───────────────────────────────────────────────
    $(document).on('click', '.bRegistrarAccion', function () {
        var btn = $(this);
        $('#bGuardarAccionProducto')
            .attr('data-traslado', btn.attr('data-traslado'))
            .attr('data-producto', btn.attr('data-producto'))
            .attr('data-presentacion', btn.attr('data-presentacion'))
            .attr('data-solicitada', btn.attr('data-solicitada'))
            .attr('data-salida', btn.attr('data-salida'))
            .attr('data-entrada', btn.attr('data-entrada'))
            .attr('data-faltante', btn.attr('data-faltante'))
            .attr('data-usuario', btn.attr('data-usuario'))
            .attr('data-origen', btn.attr('data-origen'))
            .attr('data-sucursal', btn.attr('data-sucursal'))
            .attr('data-descripcion', btn.attr('data-descripcion'))
            .attr('data-presnombre', btn.attr('data-presnombre'));

        var desc = btn.attr('data-descripcion') + ' ' + btn.attr('data-presnombre');
        $('#textoAccionProducto').html(
            `<i class="fas fa-exclamation-triangle text-warning"></i> 
        Se enviaron <strong>${btn.attr('data-salida')}</strong> y se recibieron <strong>${btn.attr('data-entrada')}</strong> 
        de <strong>'${desc}'</strong>. Faltan <strong class="text-danger">${btn.attr('data-faltante')}</strong>.`
        );
        $('input[name="accionProducto"]').prop('checked', false);
        $('#accionDevolverG').prop('checked', true);
        $('#observacionAccion').val('');
        $('#modalAccionProducto').modal('show');
    });

    // ── Guardar acción ──────────────────────────────────────────────────────
    $(document).on('click', '#bGuardarAccionProducto', function () {
        var btn = $(this);
        var accion = $('input[name="accionProducto"]:checked').val();
        var observacion = $.trim($('#observacionAccion').val());

        if (!accion) { Swal.fire({ icon: 'warning', title: 'Selecciona una acción' }); return; }

        $.ajax({
            url: 'index.php', type: 'POST',
            data: {
                metodo: 'insertar',
                accion: 'traslados',
                tipo: 'GuardarAccion',
                traslado: btn.attr('data-traslado'),
                producto: btn.attr('data-producto'),
                presentacion: btn.attr('data-presentacion'),
                solicitada: btn.attr('data-solicitada'),
                salida: btn.attr('data-salida'),
                entrada: btn.attr('data-entrada'),
                faltante: btn.attr('data-faltante'),
                accion_tipo: accion,
                observacion: observacion
            },
            beforeSend: function () { $('#carga').show(); }
        }).done(function (res) {
            if ($.trim(res) === 'Correcto') {
                var fila = $('#tbodyConcentradoGeneral tr[data-producto="' + btn.attr('data-producto') + '"][data-presentacion="' + btn.attr('data-presentacion') + '"]');
                var badgeHtml = badgeAccion(accion);
                if (observacion) badgeHtml += '<br><small class="text-muted">' + observacion + '</small>';
                fila.find('.tdAccionRegistrada').html(badgeHtml);

                var btnImprimir = `<button type="button" class="btn btn-sm btn-info bImprimirAccion mb-1"
                    data-traslado="${btn.attr('data-traslado')}"
                    data-producto="${btn.attr('data-producto')}"
                    data-presentacion="${btn.attr('data-presentacion')}">
                    <i class="fas fa-print"></i>
                </button>`;
                fila.children('td:last').html(btnImprimir);
                fila.children('td:eq(6)').html(badgeEstatus('Enviada'));

                var btnAceptar = '';
                var btnRechazar = '';
                if (accion == 'Reposicion' && (btn.attr('data-usuario') == 'Administrador' || btn.attr('data-origen') == btn.attr('data-sucursal'))) {
                    btnAceptar = `<button type="button" class="btn btn-sm btn-success bAceptarReposicion mb-1"
                        data-traslado="${btn.attr('data-traslado')}"
                        data-producto="${btn.attr('data-producto')}"
                        data-presentacion="${btn.attr('data-presentacion')}"
                        data-solicitada="${btn.attr('data-solicitada')}"
                        data-salida="${btn.attr('data-salida')}"
                        data-entrada="${btn.attr('data-entrada')}"
                        data-faltante="${btn.attr('data-faltante')}"
                        data-descripcion="${btn.attr('data-descripcion')}"
                        data-presnombre="${btn.attr('data-presnombre')}"
                        title="Aceptar reposición">
                        <i class="fas fa-check"></i> Aceptar
                    </button>`;

                    btnRechazar = `<button type="button" class="btn btn-sm btn-danger bRechazarReposicion mb-1"
                        data-traslado="${btn.attr('data-traslado')}"
                        data-producto="${btn.attr('data-producto')}"
                        data-presentacion="${btn.attr('data-presentacion')}"
                        data-solicitada="${btn.attr('data-solicitada')}"
                        data-salida="${btn.attr('data-salida')}"
                        data-entrada="${btn.attr('data-entrada')}"
                        data-faltante="${btn.attr('data-faltante')}"
                        data-descripcion="${btn.attr('data-descripcion')}"
                        data-presnombre="${btn.attr('data-presnombre')}"
                        title="Rechazar reposición">
                        <i class="fas fa-times"></i> Rechazar
                    </button>`;
                }

                fila.children('td:last').append(btnAceptar + btnRechazar);

                Swal.fire({ icon: 'success', title: 'Acción registrada', timer: 1000, showConfirmButton: false });
                $('#modalAccionProducto').modal('hide');
            } else {
                Swal.fire({ icon: 'error', title: 'Error al guardar la acción' });
                console.log($.trim(res));
            }
        })
            .fail(function () { console.log('Error ajax'); })
            .always(function () { $('#carga').hide(); });
    });

    // ── Imprimir concentrado completo (PDF) ─────────────────────────────────
    $(document).on('click', '#bImprimirConcentradoGeneral', function () {
        window.open('controladores/pdf/pdfTraslado.php?id=' + $(this).attr('data-id') + '&tipo=concentrado', '_blank');
    });

    // ── Imprimir formato de acción individual ───────────────────────────────
    $(document).on('click', '.bImprimirAccion', function () {
        var url = 'controladores/pdf/pdfTraslado.php?id=' + $(this).attr('data-traslado')
            + '&tipo=accion'
            + '&producto=' + $(this).attr('data-producto')
            + '&presentacion=' + $(this).attr('data-presentacion');
        window.open(url, '_blank');
    });

    // ── Aceptar reposición ──────────────────────────────────────────────────
    $(document).on('click', '.bAceptarReposicion', function () {
        var btn = $(this);
        Swal.fire({
            title: '¿Aceptar reposición?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php', type: 'POST',
                    data: {
                        metodo: 'modificar',
                        accion: 'traslados',
                        tipo: 'EstatusAccion',
                        traslado: btn.attr('data-traslado'),
                        producto: btn.attr('data-producto'),
                        presentacion: btn.attr('data-presentacion'),
                        estatus: 'Aceptada'
                    },
                    beforeSend: function () { $('#carga').show(); }
                }).done(function (res) {
                    if ($.trim(res) === 'Correcto') {
                        var fila = $('.filaConcentradoGeneral[data-producto="' + btn.attr('data-producto') + '"][data-presentacion="' + btn.attr('data-presentacion') + '"]');
                        fila.find('.tdEstatusGeneral').html(badgeEstatus('Aceptada'));
                        fila.find('td:last').html(btnImprimirAccion(btn));
                        Swal.fire({ icon: 'success', title: 'Reposición aceptada', timer: 1000, showConfirmButton: false });
                    } else { Swal.fire({ icon: 'error', title: 'Error' }); console.log($.trim(res)); }
                })
                    .fail(function () { console.log('Error ajax'); })
                    .always(function () { $('#carga').hide(); });
            }
        });
    });

    // ── Rechazar reposición ─────────────────────────────────────────────────
    $(document).on('click', '.bRechazarReposicion', function () {
        var btn = $(this);
        Swal.fire({
            title: '¿Rechazar reposición?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, rechazar',
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php', type: 'POST',
                    data: {
                        metodo: 'modificar',
                        accion: 'traslados',
                        tipo: 'EstatusAccion',
                        traslado: btn.attr('data-traslado'),
                        producto: btn.attr('data-producto'),
                        presentacion: btn.attr('data-presentacion'),
                        estatus: 'Rechazada'
                    },
                    beforeSend: function () { $('#carga').show(); }
                }).done(function (res) {
                    if ($.trim(res) === 'Correcto') {
                        var fila = $('.filaConcentradoGeneral[data-producto="' + btn.attr('data-producto') + '"][data-presentacion="' + btn.attr('data-presentacion') + '"]');
                        fila.find('.tdEstatusGeneral').html(badgeEstatus('Rechazada'));
                        fila.find('td:last').html(btnImprimirAccion(btn));
                        Swal.fire({ icon: 'success', title: 'Reposición rechazada', timer: 1000, showConfirmButton: false });
                    } else { Swal.fire({ icon: 'error', title: 'Error' }); console.log($.trim(res)); }
                })
                    .fail(function () { console.log('Error ajax'); })
                    .always(function () { $('#carga').hide(); });
            }
        });
    });

    // ── Merma ───────────────────────────────────────────────────────────────
    $(document).on('click', '.bMermaInveTraslado', function () {
        var btn = $(this);
        Swal.fire({
            title: '¿Registrar como merma?',
            html: 'Se registrará una merma de <strong>' + btn.attr('data-faltante') + '</strong> unidades de <strong>' + btn.attr('data-descripcion') + ' ' + btn.attr('data-presnombre') + '</strong>.',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Motivo de la merma...',
            showCancelButton: true,
            confirmButtonText: 'Registrar merma',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php', type: 'POST',
                    data: {
                        metodo: 'modificar',
                        accion: 'traslados',
                        tipo: 'MermaTraslado',
                        traslado: btn.attr('data-traslado'),
                        producto: btn.attr('data-producto'),
                        presentacion: btn.attr('data-presentacion'),
                        faltante: btn.attr('data-faltante'),
                        motivo: result.value || ''
                    },
                    beforeSend: function () { $('#carga').show(); }
                }).done(function (res) {
                    if ($.trim(res) === 'Correcto') {
                        var fila = $('.filaConcentradoGeneral[data-producto="' + btn.attr('data-producto') + '"][data-presentacion="' + btn.attr('data-presentacion') + '"]');
                        fila.find('.tdEstatusGeneral').html(badgeEstatus('Procesada'));
                        fila.find('td:last').html(btnImprimirAccion(btn));
                        Swal.fire({ icon: 'success', title: 'Merma registrada correctamente', timer: 1200, showConfirmButton: false });
                    } else { Swal.fire({ icon: 'error', title: 'Error al registrar merma' }); console.log($.trim(res)); }
                })
                    .fail(function () { console.log('Error ajax'); })
                    .always(function () { $('#carga').hide(); });
            }
        });
    });

    // ── Devolver a inventario ───────────────────────────────────────────────
    $(document).on('click', '.bDevolverInveTraslado', function () {
        var btn = $(this);
        Swal.fire({
            title: '¿Devolver a inventario de origen?',
            html: 'Se devolverán <strong>' + btn.attr('data-faltante') + '</strong> unidades de <strong>' + btn.attr('data-descripcion') + ' ' + btn.attr('data-presnombre') + '</strong> al inventario de origen.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php', type: 'POST',
                    data: {
                        metodo: 'modificar',
                        accion: 'traslados',
                        tipo: 'DevolverInveTraslado',
                        traslado: btn.attr('data-traslado'),
                        producto: btn.attr('data-producto'),
                        presentacion: btn.attr('data-presentacion'),
                        faltante: btn.attr('data-faltante')
                    },
                    beforeSend: function () { $('#carga').show(); }
                }).done(function (res) {
                    if ($.trim(res) === 'Correcto') {
                        var fila = $('.filaConcentradoGeneral[data-producto="' + btn.attr('data-producto') + '"][data-presentacion="' + btn.attr('data-presentacion') + '"]');
                        fila.find('.tdEstatusGeneral').html(badgeEstatus('Procesada'));
                        fila.find('td:last').html(btnImprimirAccion(btn));
                        Swal.fire({ icon: 'success', title: 'Devuelto al inventario correctamente', timer: 1200, showConfirmButton: false });
                    } else { Swal.fire({ icon: 'error', title: 'Error al devolver' }); console.log($.trim(res)); }
                })
                    .fail(function () { console.log('Error ajax'); })
                    .always(function () { $('#carga').hide(); });
            }
        });
    });

    $(document).on('click', '#VerMovimientosTraslados', function () {
        TablaMovimientosTraslados();
        $("#ModalVerMovimientos").modal('show');
    });

    // ── Completar traslado ──────────────────────────────────────────────────
    $(document).on('click', '.bCompletarTraslado', function () {
        var btn = $(this);
        Swal.fire({
            title: '¿Completar el traslado?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, completar',
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php', type: 'POST',
                    data: {
                        metodo: 'modificar',
                        accion: 'traslados',
                        tipo: 'completarTraslado',
                        id: btn.attr('attrID')
                    },
                    beforeSend: function () { $('#carga').show(); }
                }).done(function (res) {
                    if ($.trim(res) === 'Correcto') {
                        Swal.fire({ icon: 'success', title: 'Traslado completado' });
                        TablaTraslados();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error inesperado al completar el traslado: ' + $.trim(res)
                        });
                        console.log($.trim(res));
                    }
                })
                    .fail(function () { console.log('Error ajax'); })
                    .always(function () { $('#carga').hide(); });
            }
        });
    });
});
