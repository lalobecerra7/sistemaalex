function v_recepcion() {
  tablaRecepcion();

  $('#formRecepcion').validate({
    /*rules: {
        fechaProgRece: {
            required: true
        }
    },
    messages: {
        fechaProgRece: {
            required: "La fecha es requerida."
        }
    },*/
    submitHandler: function(form) {
      var productos = [];
      var sumaCan = 0;
      $("#tablaConcentradoProdRece").children('tbody').children('tr').each(function(index, el) {
        productos.push(
          {
            'ID_Detalle_Recepcion': $(this).attr('attrRece'), 
            'FK_Detalle_Orden': $(this).attr('attrOrden'), 
            'Lote': $(this).find('td .inLoteRece').val(), 
            'Caducidad': $(this).find('td .inCadRece').val(),  
            'Cantidad_Orden': $(this).children('td:eq(4)').children('span').text().replaceAll(',', ''),   
            'Cantidad': $(this).children('td:eq(5)').children('span').text().replaceAll(',', ''),   
            'Estatus': $(this).children('td:eq(6)').text(),  
            'Observaciones': $(this).find('td .inObRece').val(),   
            'Copiada': $(this).attr('attrCopiada')
          }
        );

        sumaCan += parseFloat($(this).children('td:eq(5)').children('span').text().replaceAll(',', ''));
      });

      var estatus = 'Completada';
      for (var i = $("#tablaConcentradoProdRece").children('tbody').children('tr').length - 1; i >= 0; i--) {
        if($("#tablaConcentradoProdRece").children('tbody').children('tr:eq('+i+')').children('td:eq(6)').text() != 'Completada'){
          estatus = 'Pendiente';
          break;
        }
      }

      $('#carga').show();
      setTimeout(function() {
        if(sumaCan > 0 && estatus == 'Pendiente'){
          estatus = 'En Proceso';
        }

        var data = "metodo=modificar&accion=recepcion&fechaProg="+$("#fechaProgRece").val()
        +"&fechaRece="+$("#fechaRecepRece").val()+"&productos="+JSON.stringify(productos)
        +"&orden="+$("#bGuardarRecepcion").attr('attrID')+"&estatus="+estatus+"&id="+$("#bGuardarRecepcion").attr('attrRece');

        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res){
          if ($.trim(res) == 'Correcto') {  
            Swal.fire({
              icon: 'success',
              title: 'La recepción ha sido guardada correctamente',
            });

            tablaRecepcion();
            $('#modalRecepcion').modal('hide');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error inesperado al guardar la recepción'
            });

            console.log($.trim(res));
          }
        })
        .fail(function() {
           console.error('Error ajax');
        })
        .always(function() {
          $('#carga').hide();
        });
      }, 1000);
    }
  });
}

function tablaRecepcion(){
  ajaxMyDatatable({
    table: $('#tablaRecepcion'),
    colums: [
      'Fecha',
      'Orden',
      'Proveedor',
      'Fecha_Programada',
      'Fecha_Recepcion',
      'Estatus',
      'Estatus_Orden',
      'Acciones'
    ],
    sort: [0, 'desc'],
    url: 'index.php',
    params: {
      metodo: 'consultar',
      accion: 'recepcion',
    }
  });
}

jQuery(document).ready($ => {

  $(document).on('click', '.bConcentardoRece', function () {
    var btn = $(this);
    var padre = btn.parent().parent();
    $('#bGuardarRecepcion').attr('attrID', btn.attr('attrID'));
    $('#bGuardarRecepcion').attr('attrRece', btn.attr('attrRece'));
    $('#formRecepcion')[0].reset();
    var validator = $("#formRecepcion").validate();
    validator.resetForm();

    var data = 'metodo=detalles&accion=recepcion&tipo=verConcentrado&orden='+btn.attr('attrID');

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: data,
      beforeSend: function() {
        $('#carga').show();
      }
    })
    .done(function(res){
      //console.log($.trim(res));
      var respuesta = JSON.parse($.trim(res));
      console.log(respuesta);

      if(respuesta.Fecha_Programada != null){
        $("#fechaProgRece").val(respuesta.Fecha_Programada.replace(' ', 'T'));
      }

      if(respuesta.Fecha_Recepcion != null){
        $("#fechaRecepRece").val(respuesta.Fecha_Recepcion.replace(' ', 'T'));
      }

      $("#tablaConcentradoProdRece").children('tbody').html(respuesta.Detalles);
      
      $('#modalRecepcion').modal('show');
      setTimeout(function() {
        $('#codProdRece').focus();
      }, 500);
    })
    .fail(function() {
       console.error('Error ajax');
    })
    .always(() => {
      $('#carga').hide();
    });
  });

  $(document).on('click', '.bCopiarProdRece', function() {
    var fila = $(this).closest("tr"); // fila actual
    var cantidadSpan = fila.find("td .cantidad").first(); // primer <span class="cantidad">
    var cantidadSpan2 = fila.find("td .cantidad").eq(1); // segundo <span class="cantidad">
    var cantidadOriginal = parseFloat(cantidadSpan.text()) || 0;
    var cantidadOriginal2 = parseFloat(cantidadSpan2.text()) || 0;

    Swal.fire({
      title: 'Ingresar cantidad a separar',
      input: 'number',
      inputAttributes: {
        min: 1,
        max: cantidadOriginal - 1, // no puede ser igual ni mayor
        step: 1
      },
      inputLabel: 'Cantidad (máx ' + (cantidadOriginal - 1) + ')',
      showCancelButton: true,
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar',
      inputValidator: (value) => {
        if (!value || isNaN(value)) {
          return 'Debes ingresar un número';
        }
        if (parseInt(value) >= cantidadOriginal) {
          return 'La cantidad debe ser menor a ' + cantidadOriginal;
        }
        if (parseInt(value) <= 0) {
          return 'La cantidad debe ser mayor a 0';
        }
      }
    }).then((result) => {
        if (result.isConfirmed) {
            var cantidadNueva = parseInt(result.value);

            // Restar a la fila original
            cantidadSpan.text(cantidadOriginal - cantidadNueva);
            cantidadSpan2.text(cantidadOriginal2 - cantidadNueva);

            // Clonar la fila
            var nuevaFila = fila.clone();

            // Ajustar la cantidad en la nueva fila
            nuevaFila.find("td .cantidad").first().text(cantidadNueva);

            nuevaFila.find("td .cantidad").eq(1).text('0');

            nuevaFila.children('td:eq(8)').append('<button type="button" class="btn btn-outline-danger btn-sm bEliminarProdRece" attrOrden="'+nuevaFila.attr('attrOrden')+'" attrRece="'+nuevaFila.attr('attrRece')+'"><i class="fas fa-trash"></i></button>');

            nuevaFila.attr('attrCopiada', '1');

            nuevaFila.children('td:eq(6)').html('<span class="badge rounded-pill bg-warning">Pendiente</span>');

            // Insertar debajo
            fila.after(nuevaFila);
        }
    });
  });

  $(document).on('click', '.bEliminarProdRece', function() {
    var filaEliminar = $(this).closest('tr');
    var codigo = filaEliminar.find('td').first().text().trim();

    // Obtener las cantidades de la fila que vamos a eliminar
    var cantOrdenEliminar = parseInt(filaEliminar.find('td .cantidad').eq(0).text()) || 0;
    var cantRecibEliminar = parseInt(filaEliminar.find('td .cantidad').eq(1).text()) || 0;

    // Eliminar la fila
    filaEliminar.remove();

    // Buscar la primera fila que tenga el mismo código (después de eliminar)
    var filaDestino = null;
    $('#tablaConcentradoProdRece tbody tr').each(function() {
        var codigoFila = $(this).find('td').first().text().trim();
        if (codigoFila === codigo) {
            filaDestino = $(this);
            return false; // salir del each
        }
    });

    if (filaDestino) {
        // Sumar las cantidades a la fila destino
        var cantOrdenActual = parseInt(filaDestino.find('td .cantidad').eq(0).text()) || 0;
        var cantRecibActual = parseInt(filaDestino.find('td .cantidad').eq(1).text()) || 0;

        var nuevaCantOrden = cantOrdenActual + cantOrdenEliminar;
        var nuevaCantRecib = cantRecibActual + cantRecibEliminar;

        filaDestino.find('td .cantidad').eq(0).text(nuevaCantOrden);
        filaDestino.find('td .cantidad').eq(1).text(nuevaCantRecib);

        // Actualizar estatus si cantidad recibida alcanza la orden
        if (nuevaCantRecib >= nuevaCantOrden) {
            filaDestino.find('td .badge').removeClass('bg-warning').addClass('bg-success').text('Completada');
        } else {
            filaDestino.find('td .badge').removeClass('bg-success').addClass('bg-warning').text('Pendiente');
        }
    }
  });

  $(document).on('click', '.bCompletarProdRece', function() {
    var fila = $(this).closest('tr');
    var codigo = fila.find('td').first().text().trim();

    Swal.fire({
        title: '¿Deseas completar este producto?',
        text: "Se marcará como recibido completamente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, completar',
        cancelButtonText: 'Cancelar',
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Obtener la cantidad de orden
            var cantOrden = parseInt(fila.find('td .cantidad').eq(0).text()) || 0;

            // Actualizar cantidad recibida a la cantidad de la orden
            fila.find('td .cantidad').eq(1).text(cantOrden);

            // Cambiar estatus a completada
            fila.find('td .badge').removeClass('bg-warning').addClass('bg-success').text('Completada');

            // Opcional: mostrar un toast de éxito que desaparece solo
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Producto completado',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                focusConfirm: false,
                didOpen: () => {
                  // quitar foco si es que aún intenta capturarlo
                  document.activeElement.blur();
                }
            });
        }
    });
  });

  $(document).on('click', '#bGuardarRecepcion', function() {
    $("#formRecepcion").trigger('submit');
  });

  $(document).on('submit', '#formAgreProdRece', function(event) {
    event.preventDefault();
    var codigoBuscado = $('#codProdRece').val().trim();
    var filasEncontradas = [];

    // Buscar todas las filas con el código
    $('#tablaConcentradoProdRece tbody tr').each(function() {
      var codigoFila = $(this).find('td').first().text().trim();
      if (codigoFila === codigoBuscado) {
        filasEncontradas.push($(this));
      }
    });

    $('#codProdRece').val('');
    setTimeout(function() {
      $('#codProdRece').focus();
    }, 700);

    if (filasEncontradas.length === 0) {
      // No encontró ninguna fila
      Swal.fire({
        icon: 'error',
        title: 'No encontrado',
        text: 'El código ' + codigoBuscado + ' no se encuentra en la tabla.',
        timer: 500,
        showConfirmButton: false
      });
      return;
    }

    // Recorrer filas hasta encontrar una que no esté completa
    var filaActual = null;
    for (var i = 0; i < filasEncontradas.length; i++) {
      var cantOrden = parseFloat(filasEncontradas[i].find('td .cantidad').eq(0).text().replaceAll(',', '')) || 0;
      var cantRecib = parseFloat(filasEncontradas[i].find('td .cantidad').eq(1).text().replaceAll(',', '')) || 0;

      if (cantRecib < cantOrden) {
        filaActual = filasEncontradas[i];
        break;
      }
    }

    if (!filaActual) {
      // Todas las filas ya están completas
      Swal.fire({
        icon: 'info',
        title: 'Producto completo',
        text: 'Todas las filas de este producto ya alcanzaron la cantidad de la orden.',
        timer: 500,
        showConfirmButton: false
      });
      return;
    }

    // Incrementar cantidad recibida en 1
    var cantidadOrden = parseFloat(filaActual.find('td .cantidad').eq(0).text().replaceAll(',', '')) || 0;
    var cantidadRecibida = parseFloat(filaActual.find('td .cantidad').eq(1).text().replaceAll(',', '')) || 0;
    cantidadRecibida += 1;

    // Actualizar cantidad en la fila
    filaActual.find('td .cantidad').eq(1).text(cantidadRecibida);

    // Cambiar estatus si llegó al máximo
    if (cantidadRecibida >= cantidadOrden) {
      filaActual.find('td .badge').removeClass('bg-warning').addClass('bg-success').text('Completada');
    }

    moneda();

    // Mostrar alerta de incremento que se quita sola
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Producto agregado',
      timer: 500,
      showConfirmButton: false,
      focusConfirm: false,
      didOpen: () => {
        // quitar foco si es que aún intenta capturarlo
        document.activeElement.blur();
      },
      toast: true  
    });

    // Verificar si todas las filas están completas
    var todasCompletadas = true;
    $('#tablaConcentradoProdRece tbody tr').each(function() {
      var cantOrden = parseInt($(this).find('td .cantidad').eq(0).text()) || 0;
      var cantRecib = parseInt($(this).find('td .cantidad').eq(1).text()) || 0;
      if (cantRecib < cantOrden) {
        todasCompletadas = false;
        return false;
      }
    });

    if (todasCompletadas) {
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Recepción completa',
        text: 'Todos los productos han sido recibidos completamente.',
        timer: 500,
        showConfirmButton: false,
        focusConfirm: false,
        didOpen: () => {
          // quitar foco si es que aún intenta capturarlo
          document.activeElement.blur();
        },
        toast: true  
      });
    }
  });

  /*$(document).on('click', '.bImprimirLote', function() {
    window.open("./controladores/barras/imprimirCodigo.php?id="+$(this).attr('attrID'), "_blank");
  });*/
});