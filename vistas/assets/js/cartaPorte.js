function formatearFechaLocal(fecha) {
  var pad = function (n) { return n < 10 ? '0' + n : n; };
  return fecha.getFullYear() + '-' + pad(fecha.getMonth() + 1) + '-' + pad(fecha.getDate()) +
    'T' + pad(fecha.getHours()) + ':' + pad(fecha.getMinutes());
}

function tablaClavesSAT(subtipo) {
  ajaxMyDatatable({
    "table": $("#tablaClavesSAT"),
    "colums": ["Descripcion", "Clave"],
    "sort": [0, "asc"],
    "url": "index.php",
    "params": {
      "metodo": "detalles",
      "accion": "cartaPorte",
      "tipo": "listaClaves",
      "subtipo": subtipo
    }
  });
}

window.claveSATTarget = null;

function abrirBuscadorClaveSAT(subtipo, claveInput, textoInput) {
  window.claveSATTarget = { claveInput: claveInput, textoInput: textoInput };

  var titulos = { estado: 'Buscar Clave Estado', municipio: 'Buscar Clave Municipio', colonia: 'Buscar Clave Colonia' };
  $("#tituloBuscarClaveSAT").text(titulos[subtipo]);

  tablaClavesSAT(subtipo);

  $("#modalBuscarClaveSAT").modal('show');
}

function buscarClaveMunicipioFila(fila, textoMunicipio, claveEstado) {
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: "metodo=detalles&accion=cartaPorte&tipo=buscarClave&subtipo=municipio&texto=" + encodeURIComponent(textoMunicipio) + "&extra=" + encodeURIComponent(claveEstado)
  })
    .done(function (resMunicipio) {
      var claveMunicipio = $.trim(resMunicipio);
      fila.find('.campoDestino[attrCampo="ClaveMunicipio"]').val((claveMunicipio == 'null') ? '' : claveMunicipio);
    });
}

jQuery(document).ready(function ($) {

  $(document).on('click', '.bCartaPore', function () {
    var idCorte = $(this).attr('attrID');
    $("#modalCartaPorte").attr('attrIDCorte', idCorte);

    var data = "metodo=consultar&accion=cartaPorte&idCorte=" + idCorte;

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: data,
      beforeSend: function () {
        $("#carga").show();
      }
    })
      .done(function (res) {
        var respuesta;
        try {
          respuesta = JSON.parse(res);
          console.log(respuesta);
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Oops...', text: 'Error inesperado al cargar el corte.' });
          console.log(res);
          return;
        }

        if (respuesta.origen === undefined) {
          Swal.fire({ icon: 'error', title: 'Oops...', text: $.trim(res) });
          return;
        }

        // ORIGEN
        $("#origenNombre").text(respuesta.origen.Nombre);
        $("#origenRFC").text(respuesta.origen.RFC);
        $("#origenCP").text(respuesta.origen.CodigoPostal);
        $("#origenDomicilio").text(respuesta.origen.Domicilio);
        $("#modalCartaPorte").data('origen', respuesta.origen);

        // Precargar textos de Estado/Municipio/Colonia
        $("#origenClaveEstado").val(respuesta.origen.Estado || '');
        $("#origenClaveMunicipio").val(respuesta.origen.Municipio || '');
        $("#origenClaveColonia").val(respuesta.origen.Colonia || '');

        // Buscar Clave Estado, y al terminar, encadenar la búsqueda de Municipio (depende de la Clave Estado)
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: "metodo=detalles&accion=cartaPorte&tipo=buscarClave&subtipo=estado&texto=" + encodeURIComponent(respuesta.origen.Estado || '') + "&extra="
        })
          .done(function (resEstado) {
            var claveEstado = $.trim(resEstado);
            claveEstado = (claveEstado == 'null') ? '' : claveEstado;
            $("#origenClaveEstado").val(claveEstado);

            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: "metodo=detalles&accion=cartaPorte&tipo=buscarClave&subtipo=municipio&texto=" + encodeURIComponent(respuesta.origen.Municipio || '') + "&extra=" + encodeURIComponent(claveEstado)
            })
              .done(function (resMunicipio) {
                var claveMunicipio = $.trim(resMunicipio);
                $("#origenClaveMunicipio").val((claveMunicipio == 'null') ? '' : claveMunicipio);

                // Buscar Clave Colonia (independiente, solo por texto)
                $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: "metodo=detalles&accion=cartaPorte&tipo=buscarClave&subtipo=colonia&texto=" + encodeURIComponent(respuesta.origen.CodigoPostal || '') + "&extra="
                })
                  .done(function (resColonia) {
                    var claveColonia = $.trim(resColonia);
                    $("#origenClaveColonia").val((claveColonia == 'null') ? '' : claveColonia);
                  });
              });
          });

        var ahora = new Date();
        $("#origenFechaSalida").val(formatearFechaLocal(ahora));

        // CHOFER
        $("#choferNombre").val(respuesta.chofer.Nombre);
        $("#choferRFC").val(respuesta.chofer.RFC);
        $("#choferLicencia").val(respuesta.chofer.No_Licencia);
        $("#choferTipo").val(respuesta.chofer.Tipo);

        // DESTINOS
        var filasDestinos = '';
        $.each(respuesta.destinos, function (i, d) {
          var fechaLlegada = new Date(ahora.getTime() + (30 * (i + 1)) * 60000);

          filasDestinos += '<tr attrIndice="' + i + '" attrIDCliente="' + d.FK_Cliente + '" attrIDDireccion="' + d.FK_Direccion + '">' +
            '<td>' + d.Nombre + '</td>' +
            '<td><input type="text" class="form-control form-control-sm campoDestino" attrCampo="RFC" value="' + (d.RFC || '') + '"></td>' +
            '<td><input type="text" class="form-control form-control-sm campoDestino" attrCampo="Calle" value="' + (d.Calle || '') + '"></td>' +
            '<td><input type="text" class="form-control form-control-sm campoDestino" attrCampo="NumeroExterior" value="' + (d.NumeroExterior || '') + '"></td>' +
            '<td><input type="text" class="form-control form-control-sm campoDestino" attrCampo="NumeroInterior" value="' + (d.NumeroInterior || '') + '"></td>' +
            '<td>' +
            '<input type="text" class="form-control form-control-sm campoDestino mb-1" attrCampo="Colonia" value="' + (d.Colonia || '') + '">' +
            '<div class="input-group input-group-sm">' +
            '<span class="input-group-text">Clave</span>' +
            '<input type="text" class="form-control campoDestino" attrCampo="ClaveColonia" value="' + (d.ClaveColonia || '') + '">' +
            '<button type="button" class="btn btn-outline-primary bBuscarClaveColoniaDestino" title="Buscar Clave Colonia"><i class="fas fa-search"></i></button>' +
            '</div>' +
            '</td>' +
            '<td><input type="text" class="form-control form-control-sm campoDestino" attrCampo="CodigoPostal" value="' + (d.CodigoPostal || '') + '"></td>' +
            '<td>' +
            '<input type="text" class="form-control form-control-sm campoDestino mb-1" attrCampo="Municipio" value="' + (d.Municipio || '') + '">' +
            '<div class="input-group input-group-sm">' +
            '<span class="input-group-text">Clave</span>' +
            '<input type="text" class="form-control campoDestino" attrCampo="ClaveMunicipio" value="' + (d.ClaveMunicipio || '') + '">' +
            '<button type="button" class="btn btn-outline-primary bBuscarClaveMunicipioDestino" title="Buscar Clave Municipio"><i class="fas fa-search"></i></button>' +
            '</div>' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control form-control-sm campoDestino mb-1" attrCampo="Estado" value="' + (d.Estado || '') + '">' +
            '<div class="input-group input-group-sm">' +
            '<span class="input-group-text">Clave</span>' +
            '<input type="text" class="form-control campoDestino" attrCampo="ClaveEstado" value="' + (d.ClaveEstado || '') + '">' +
            '<button type="button" class="btn btn-outline-primary bBuscarClaveEstadoDestino" title="Buscar Clave Estado"><i class="fas fa-search"></i></button>' +
            '</div>' +
            '</td>' +
            '<td><input type="number" step="0.01" class="form-control form-control-sm campoDestino" attrCampo="DistanciaRecorrida" value="' + d.DistanciaRecorrida + '"></td>' +
            '<td><input type="datetime-local" class="form-control form-control-sm campoDestino" attrCampo="FechaLlegada" value="' + formatearFechaLocal(fechaLlegada) + '"></td>' +
            '<td class="text-center"><input type="checkbox" class="form-check-input actualizarDestino" checked></td>' +
            '</tr>';
        });
        $("#tablaDestinos tbody").html(filasDestinos);
        $("#modalCartaPorte").data('destinos', respuesta.destinos);

        // Autobúsqueda de claves por cada fila de Destinos (solo si falta alguna clave)
        $("#tablaDestinos tbody tr").each(function () {
          var fila = $(this);

          var claveEstadoActual = fila.find('.campoDestino[attrCampo="ClaveEstado"]').val();
          var claveMunicipioActual = fila.find('.campoDestino[attrCampo="ClaveMunicipio"]').val();
          var claveColoniaActual = fila.find('.campoDestino[attrCampo="ClaveColonia"]').val();

          var textoEstado = fila.find('.campoDestino[attrCampo="Estado"]').val();
          var textoMunicipio = fila.find('.campoDestino[attrCampo="Municipio"]').val();
          var textoCP = fila.find('.campoDestino[attrCampo="CodigoPostal"]').val();

          // Si ya tiene las 3 claves guardadas, no busca nada
          if (claveEstadoActual != '' && claveMunicipioActual != '' && claveColoniaActual != '') {
            return true; // continue al siguiente .each
          }

          // Estado (encadena Municipio)
          if (claveEstadoActual == '') {
            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: "metodo=detalles&accion=cartaPorte&tipo=buscarClave&subtipo=estado&texto=" + encodeURIComponent(textoEstado) + "&extra="
            })
              .done(function (resEstado) {
                var claveEstado = $.trim(resEstado);
                claveEstado = (claveEstado == 'null') ? '' : claveEstado;
                fila.find('.campoDestino[attrCampo="ClaveEstado"]').val(claveEstado);

                if (claveMunicipioActual == '') {
                  buscarClaveMunicipioFila(fila, textoMunicipio, claveEstado);
                }
              });
          } else if (claveMunicipioActual == '') {
            // Ya tiene ClaveEstado pero no ClaveMunicipio, busca directo con la clave existente
            buscarClaveMunicipioFila(fila, textoMunicipio, claveEstadoActual);
          }

          // Colonia (independiente)
          if (claveColoniaActual == '') {
            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: "metodo=detalles&accion=cartaPorte&tipo=buscarClave&subtipo=colonia&texto=" + encodeURIComponent(textoCP) + "&extra="
            })
              .done(function (resColonia) {
                var claveColonia = $.trim(resColonia);
                fila.find('.campoDestino[attrCampo="ClaveColonia"]').val((claveColonia == 'null') ? '' : claveColonia);
              });
          }
        });

        // VEHICULO
        $("#vehiculoPlaca").val(respuesta.vehiculo.Placa);
        $("#vehiculoConfig").val(respuesta.vehiculo.Tipo);
        $("#vehiculoAnio").val(respuesta.vehiculo.Ano);
        $("#vehiculoPesoBruto").val(respuesta.vehiculo.Peso);
        $("#vehiculoSICT").val(respuesta.vehiculo.SICT);
        $("#vehiculoAseguradora").val(respuesta.vehiculo.Aseguradora);
        $("#vehiculoPoliza").val(respuesta.vehiculo.Poliza);

        // MERCANCIAS
        var filasMercancias = '';
        var pesoTotal = 0;
        $.each(respuesta.mercancias, function (i, m) {
          pesoTotal += parseFloat(m.PesoTotal);
          filasMercancias += '<tr>' +
            '<td>' + m.ClaveProdServ + '</td>' +
            '<td>' + m.Descripcion + '</td>' +
            '<td>' + m.ClaveUnidad + '</td>' +
            '<td>' + m.Cantidad + '</td>' +
            '<td class="celdaPeso" attrIDProducto="' + m.FK_Producto + '" attrIDPresentacion="' + (m.FK_Presentacion || '') + '" attrCantidad="' + m.Cantidad + '">' + m.PesoUnitario + '</td>' +
            '<td class="celdaPesoTotal">' + m.PesoTotal + '</td>' +
            '<td>' + m.Destino + '</td>' +
            '</tr>';
        });
        $("#tablaConceptos tbody").html(filasMercancias);
        $("#pesoTotalMercancia").html('<b>' + pesoTotal.toFixed(2) + '</b>');
        $("#modalCartaPorte").data('mercancias', respuesta.mercancias);

        $("#modalCartaPorte").modal('show');
      })
      .fail(function () {
        console.log("Error ajax");
      })
      .always(function () {
        $("#carga").hide();
      });
  });

  $(document).on('dblclick', '.celdaPeso', function () {
    var celda = $(this);
    if (celda.find('input').length > 0) return;

    var pesoActual = celda.text().trim();
    var idProducto = celda.attr('attrIDProducto');
    var idPresentacion = celda.attr('attrIDPresentacion');

    celda.html('<input type="number" step="0.001" class="form-control form-control-sm inputPeso">');
    celda.find('input').val(pesoActual).focus().select();

    celda.find('input').on('blur keypress', function (event) {
      if (event.type === 'keypress' && event.which !== 13) return;

      var nuevoPeso = $(this).val();
      if (nuevoPeso == '' || isNaN(nuevoPeso) || nuevoPeso <= 0) {
        celda.text(pesoActual);
        return;
      }

      var data = "metodo=detalles&accion=cartaPorte&tipo=peso&idProducto=" + idProducto + "&idPresentacion=" + idPresentacion + "&peso=" + nuevoPeso;

      $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function () {
          $("#carga").show();
        }
      })
        .done(function (res) {
          if ($.trim(res) == "Correcto") {
            celda.attr('attrValor', nuevoPeso);
            celda.text(nuevoPeso);

            var fila = celda.closest('tr');
            var cantidad = parseFloat(celda.attr('attrCantidad'));
            var pesoTotalFila = (parseFloat(nuevoPeso) * cantidad).toFixed(3);
            fila.find('.celdaPesoTotal').text(pesoTotalFila);

            var sumaTotal = 0;
            $('.celdaPesoTotal').each(function () {
              sumaTotal += parseFloat($(this).text());
            });
            $("#pesoTotalMercancia").html('<b>' + sumaTotal.toFixed(2) + '</b>');
          } else {
            celda.text(pesoActual);

            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error inesperado al actualizar el peso.'
            });

            console.log($.trim(res));
          }
        })
        .fail(function () {
          celda.text(pesoActual);
          console.log("Error ajax");
        })
        .always(function () {
          $("#carga").hide();
        });
    });
  });

  $(document).on('click', '#bTimbrarCartaPorte', function () {
    $(".is-invalid").removeClass('is-invalid');

    var errores = [];

    // ORIGEN
    if ($("#origenFechaSalida").val() == '') {
      errores.push({ campo: $("#origenFechaSalida"), mensaje: 'Falta la fecha y hora de salida del Origen.' });
    }

    var camposClaveOrigen = [
      { id: '#origenClaveEstado', nombre: 'Clave Estado del Origen' },
      { id: '#origenClaveMunicipio', nombre: 'Clave Municipio del Origen' },
      { id: '#origenClaveColonia', nombre: 'Clave Colonia del Origen' }
    ];
    $.each(camposClaveOrigen, function (i, c) {
      if ($.trim($(c.id).val()) == '') {
        errores.push({ campo: $(c.id), mensaje: 'Falta la "' + c.nombre + '". Usa el botón de búsqueda o captúrala manualmente.' });
      }
    });

    // VEHICULO
    var camposVehiculo = [
      { id: '#vehiculoPlaca', nombre: 'Placa del vehículo' },
      { id: '#vehiculoConfig', nombre: 'Configuración vehicular' },
      { id: '#vehiculoAnio', nombre: 'Año del vehículo' },
      { id: '#vehiculoPesoBruto', nombre: 'Peso bruto vehicular' },
      { id: '#vehiculoSICT', nombre: 'No. de permiso SICT' },
      { id: '#vehiculoAseguradora', nombre: 'Aseguradora del vehículo' },
      { id: '#vehiculoPoliza', nombre: 'No. de póliza del vehículo' }
    ];
    $.each(camposVehiculo, function (i, c) {
      if ($.trim($(c.id).val()) == '') {
        errores.push({ campo: $(c.id), mensaje: 'Falta el campo "' + c.nombre + '" en Vehículo.' });
      }
    });

    // CHOFER
    var camposChofer = [
      { id: '#choferNombre', nombre: 'Nombre del chofer' },
      { id: '#choferRFC', nombre: 'RFC del chofer' },
      { id: '#choferLicencia', nombre: 'No. de licencia del chofer' },
      { id: '#choferTipo', nombre: 'Tipo de figura del chofer' }
    ];
    $.each(camposChofer, function (i, c) {
      if ($.trim($(c.id).val()) == '') {
        errores.push({ campo: $(c.id), mensaje: 'Falta el campo "' + c.nombre + '" en Operador/Chofer.' });
      }
    });

    // DESTINOS
    $("#tablaDestinos tbody tr").each(function () {
      var fila = $(this);
      var nombreCliente = fila.find('td').eq(0).text();

      fila.find('.campoDestino').each(function () {
        var campo = $(this);
        var nombreCampo = campo.attr('attrCampo');

        if (nombreCampo == 'NumeroInterior') return true; // opcional, se salta

        if ($.trim(campo.val()) == '') {
          errores.push({ campo: campo, mensaje: 'Falta el campo "' + nombreCampo + '" del destino "' + nombreCliente + '".' });
          return true;
        }

        if (nombreCampo == 'DistanciaRecorrida' && parseFloat(campo.val()) <= 0) {
          errores.push({ campo: campo, mensaje: 'La distancia recorrida del destino "' + nombreCliente + '" no puede ser 0. Captúrala manualmente.' });
        }
      });
    });

    // MERCANCIAS (clave ProdServ, unidad y peso unitario no pueden faltar)
    $(".celdaPeso").each(function () {
      var celda = $(this);
      var fila = celda.closest('tr');
      var descripcion = fila.find('td').eq(1).text();

      var claveProdServ = $.trim(fila.find('td').eq(0).text());
      if (claveProdServ == '') {
        errores.push({ campo: fila.find('td').eq(0), mensaje: 'Falta la Clave ProdServ de "' + descripcion + '".' });
      }

      var claveUnidad = $.trim(fila.find('td').eq(2).text());
      if (claveUnidad == '') {
        errores.push({ campo: fila.find('td').eq(2), mensaje: 'Falta la Unidad de "' + descripcion + '".' });
      }

      var peso = parseFloat(celda.text());
      if (isNaN(peso) || peso <= 0) {
        errores.push({ campo: celda, mensaje: 'Falta capturar el peso de "' + descripcion + '" (doble clic sobre la celda para editarlo).' });
      }
    });

    if (errores.length > 0) {
      var primerError = errores[0];
      primerError.campo.addClass('is-invalid');

      if (primerError.campo.is('input, select')) {
        primerError.campo.focus();
      } else {
        $('html, body').animate({ scrollTop: primerError.campo.offset().top - 150 }, 300);
      }

      Swal.fire({
        icon: 'warning',
        title: 'Faltan datos',
        text: primerError.mensaje,
        footer: errores.length > 1 ? ('Hay ' + errores.length + ' campos pendientes en total.') : ''
      });

      return;
    }

    var idCorte = $("#modalCartaPorte").attr('attrIDCorte');

    var vehiculo = {
      Placa: $.trim($("#vehiculoPlaca").val()),
      Tipo: $("#vehiculoConfig").val(),
      Ano: $.trim($("#vehiculoAnio").val()),
      Peso: $.trim($("#vehiculoPesoBruto").val()),
      SICT: $.trim($("#vehiculoSICT").val()),
      Aseguradora: $.trim($("#vehiculoAseguradora").val()),
      Poliza: $.trim($("#vehiculoPoliza").val()),
      Actualizar: $("#vehiculoActualizar").is(':checked') ? 1 : 0
    };

    var chofer = {
      Nombre: $.trim($("#choferNombre").val()),
      RFC: $.trim($("#choferRFC").val()),
      No_Licencia: $.trim($("#choferLicencia").val()),
      Tipo: $("#choferTipo").val(),
      Actualizar: $("#choferActualizar").is(':checked') ? 1 : 0
    };

    var destinos = [];
    $("#tablaDestinos tbody tr").each(function () {
      var fila = $(this);
      var destino = {
        FK_Cliente: fila.attr('attrIDCliente'),
        FK_Direccion: fila.attr('attrIDDireccion'),
        Nombre: fila.children('td').eq(0).text(),
        Actualizar: fila.find('.actualizarDestino').is(':checked') ? 1 : 0
      };

      fila.find('.campoDestino').each(function () {
        var campo = $(this);
        destino[campo.attr('attrCampo')] = $.trim(campo.val());
      });

      destinos.push(destino);
    });

    var data = {
      metodo: 'insertar',
      accion: 'cartaPorte',
      idCorte: idCorte,
      origenFechaSalida: $("#origenFechaSalida").val(),
      origenClaveEstado: $("#origenClaveEstado").val(),
      origenClaveMunicipio: $("#origenClaveMunicipio").val(),
      origenClaveColonia: $("#origenClaveColonia").val(),
      vehiculo: JSON.stringify(vehiculo),
      chofer: JSON.stringify(chofer),
      destinos: JSON.stringify(destinos)
    };

    console.log(data);

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: data,
      beforeSend: function () {
        $("#carga").show();
      }
    })
      .done(function (res) {
        if ($.trim(res) == 'Correcto') {
          Swal.fire({
            icon: 'success',
            title: 'Timbrado correctamente',
            text: 'El traslado se generó y timbró correctamente.'
          });

          tablaCortesRuta();
          $("#modalCartaPorte").modal('hide');
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: $.trim(res)
          });

          console.log($.trim(res));
        }
      })
      .fail(function () {
        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Error de conexión al intentar timbrar.' });
      })
      .always(function () {
        $("#carga").hide();
      });
  });

  $(document).on('input change', '.campoDestino, #origenFechaSalida, #vehiculoPlaca, #vehiculoConfig, #vehiculoAnio, #vehiculoPesoBruto, #vehiculoSICT, #vehiculoAseguradora, #vehiculoPoliza, #choferRFC, #choferLicencia, #choferTipo', function () {
    $(this).removeClass('is-invalid');
  });

  // ===== ORIGEN =====
  $(document).on('click', '.bBuscarClaveEstadoOrigen', function () {
    abrirBuscadorClaveSAT('estado', $("#origenClaveEstado"), $("#origenEstadoTexto"));
  });

  $(document).on('click', '.bBuscarClaveMunicipioOrigen', function () {
    abrirBuscadorClaveSAT('municipio', $("#origenClaveMunicipio"), $("#origenMunicipioTexto"));
  });

  $(document).on('click', '.bBuscarClaveColoniaOrigen', function () {
    abrirBuscadorClaveSAT('colonia', $("#origenClaveColonia"), $("#origenColoniaTexto"));
  });

  // ===== DESTINOS (delegado, funciona para cualquier fila) =====
  $(document).on('click', '.bBuscarClaveEstadoDestino', function () {
    var fila = $(this).closest('tr');
    abrirBuscadorClaveSAT('estado', fila.find('.campoDestino[attrCampo="ClaveEstado"]'), fila.find('.campoDestino[attrCampo="Estado"]'));
  });

  $(document).on('click', '.bBuscarClaveMunicipioDestino', function () {
    var fila = $(this).closest('tr');
    abrirBuscadorClaveSAT('municipio', fila.find('.campoDestino[attrCampo="ClaveMunicipio"]'), fila.find('.campoDestino[attrCampo="Municipio"]'));
  });

  $(document).on('click', '.bBuscarClaveColoniaDestino', function () {
    var fila = $(this).closest('tr');
    abrirBuscadorClaveSAT('colonia', fila.find('.campoDestino[attrCampo="ClaveColonia"]'), fila.find('.campoDestino[attrCampo="Colonia"]'));
  });

  // ===== SELECCIÓN DE FILA EN EL BUSCADOR =====
  $(document).on('click', '#tablaClavesSAT tbody tr', function () {
    if (!window.claveSATTarget) return;

    var descripcionCompleta = $.trim($(this).children('td:eq(0)').text());
    var clave = $.trim($(this).children('td:eq(1)').text());
    var descripcionLimpia = descripcionCompleta.split(' (')[0]; // quita el sufijo "(CP ...)" o "(ZAC)" etc.

    window.claveSATTarget.claveInput.val(clave);
    window.claveSATTarget.textoInput.val(descripcionLimpia);
    window.claveSATTarget = null;

    $("#modalBuscarClaveSAT").modal('hide');
    setTimeout(function () {
      $("#modalCartaPorte").modal('show');
    }, 300);
  });
});