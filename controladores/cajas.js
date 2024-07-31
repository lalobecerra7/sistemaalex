function v_cajas() {
  tablaCajas();

  $('#formCajas').validate({
    rules: {
      nombreCaja: {
        required: true
      },
      sucursalCaja: {
        required: true
      }
    },
    messages: {
      nombreCaja: {
        required: 'Este campo es obligatorio'
      },
      sucursalCaja: {
        required: 'Este campo es obligatorio'
      }
    },
    submitHandler: function(form) {
      if ($('#bGuardarCaja').attr('tipo') === 'insertar'){
        guardarCaja();
      }else{
        Swal.fire({
          title: '¿Estás seguro de modificar esta caja?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Sí, modificar',
        }).then((result) => {
          if (result.value) {
            guardarCaja();
          }
        });
      }
    }
  });

  $('#formAbrirCaja').validate({
    rules: {
      montoCaja: {
        required: true,
        number: true,
        min: 0
      }
    },
    messages: {
      montoCaja: {
        required: 'Este campo es obligatorio',
        number: 'Este campo debe ser un número',
        min: 'Este campo debe ser mayor a 0'
      }
    },
    submitHandler: function(form) {
      var data = "metodo=insertar&accion=cajas&tipo=abrir&fkCaja="+$('#bAbrirMontoCaja').attr('attrID')+"&montoCaja="+$("#montoCaja").val();

      $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
          $('#carga').show();
        }
      }).done(function(res) {
        if ($.trim(res) == 'Correcto') {
          tablaCajas();
          $('#modalAbrirCaja').modal('hide');
          $('#bAbrirCaja').trigger('click');
        }else{
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error inesperado al abrir la caja.',
          });

          console.log($.trim(res));
        }
      })
      .fail(function() {
        console.error('Error ajax');
      }).always(function () {
        $('#carga').hide();
      });
    }
  });
}

function tablaReportes() {
  ajaxMyDatatable({
    table: $('#tablaReportes'),
    colums: [
      'Caja',
      'Fecha_Abrir',
      'Monto_Abrir',
      'Usuario_Abrir',
      'Fecha_Cierre',
      'Monto_Cierre',
      'Usuario_Cierre',
      'Acciones'
    ],
    sort: [1, 'desc'],
    url: 'index.php',
    params: {
      metodo: 'detalles',
      accion: 'cajas',
      tipo: 'cortes'
    }
  })
}

function tablaCajas() {
  ajaxMyDatatable({
    table: $('#tablaCajas'),
    colums: [
      'Nombre',
      'Estado',
      'Sucursal',
      'Detalles',
      'Acciones',
    ],
    sort: [0, 'asc'],
    url: 'index.php',
    params: {
      metodo: 'consultar',
      accion: 'cajas',
      filterSucursal: $("#sucursal").val()
    }
  });
}

function guardarCaja() {
  var data = "metodo="+$('#bGuardarCaja').attr('tipo')+"&accion=cajas&tipo=guardarCaja&idCaja="+$('#bGuardarCaja').attr('attrID')+"&nombreCaja="+$.trim($("#nombreCaja").val())+"&detallesCaja="+$.trim($("#detallesCaja").val())+"&sucursalCaja="+$.trim($("#sucursalCaja").val());

  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: data,
    beforeSend: function() {
      $('#carga').show();
    }
  })
 .done(function (res) {
    if ($.trim(res) == 'Correcto') {
      Swal.fire({
        icon: 'success',
        title: '¡Caja guardada correctamente!',
      });

      tablaCajas();
      $("#modalCajas").modal('hide');
    }else{
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Error inesperado al guardar la caja.',
      });

      console.log($.trim(res));
    }
  })
  .fail(function () {
    console.log("Error ajax");
  })
  .always(function () {
    $('#carga').hide();
  });
}

jQuery(document).ready($ => {
  $(document).on('click', '#bAgregarCaja', () => {
    $('#bGuardarCaja').attr('tipo', 'insertar');
    $('#formCajas')[0].reset();
    $('#modalCajas').modal('show');
  });

  $(document).on('click', '.bModificarCaja', function () {
    var padre = $(this).parent().parent();
    const id = $(this).attr('attrID');

    $('#nombreCaja').val(padre.children('td:eq(0)').text());
    $('#detallesCaja').val(padre.children('td:eq(3)').children('span:eq(0)').text());
    $('#sucursalCaja').val(padre.children('td:eq(2)').children('span:eq(0)').text());

    $('#bGuardarCaja').attr('tipo', 'modificar');
    $('#bGuardarCaja').attr('attrID', id);
    $('#modalCajas').modal('show');
  });

  $(document).on('click', '.bEliminarCaja', function () {
    const id = $(this).attr('attrID');

    Swal.fire({
      title: '¿Estás seguro de eliminar esta caja?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'No, cancelar',
      confirmButtonText: 'Sí, eliminar',
    }).then((result) => {
      if (result.value) {
        const data = `metodo=eliminar&accion=cajas&tipo=cajas&idCaja=${id}`;

        $.ajax({
          url: 'index.php',
          type: 'POST',
          data,
          beforeSend: function() {
            $('#carga').show();
          }
        })
        .done(function(res) {
          if ($.trim(res)=== 'Correcto') {
            Swal.fire({
              icon: 'success',
              title: '¡Caja eliminada correctamente!',
            });

            tablaCajas();
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error inesperado al eliminar la caja.',
            });

            console.log($.trim(res));
          }
        })
        .fail(function () {
          console.error('Error ajax');
        })
        .always(function() {
          $('#carga').hide();
        });
      }
    });
  });

  $(document).on('click', '.bAbrirCaja', function () {
    $('#montoCaja').val(0);
    $('#bAbrirMontoCaja').attr('attrID', $(this).attr('attrID'));
    $('#modalAbrirCaja').modal('show');
  });

  $(document).on('click', '#bReportes', function () {
    tablaReportes();
    $('#modalReportes').modal('show');
  });

  $(document).on('click', '.bTomarCaja', function() {
    var data = "metodo=modificar&accion=cajas&tipo=tomarCaja&idCaja="+$(this).attr('attrID');

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: data,
      beforeSend: function() {
        $('#carga').show();
      }
    })
   .done(function (res) {
      if ($.trim(res) == 'Correcto') {
        Swal.fire({
          icon: 'success',
          title: '¡Caja tomada correctamente!',
        });

        tablaCajas();
        $("#bAbrirCaja").trigger('click');
      }else{
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Error inesperado al tomar la caja.',
        });

        console.log($.trim(res));
      }
    })
    .fail(function () {
      console.log("Error ajax");
    })
    .always(function () {
      $('#carga').hide();
    });  
  });

  $(document).on('click', '.bVerBalance', function() {
    $("#tablaDetallesBalanceC").children('tbody').html('');
    $("#tablaTotalVentasC").children('tbody').html('');
    $("#tablaMovimientosC").children('tbody').html('');
    
    const data = `metodo=detalles&accion=cajas&tipo=corte&id=${$(this).attr('attrID')}`;

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data,
      beforeSend: function() {
        $('#carga').show();
      }
    })
    .done(function(res) {
      //console.log($.trim(res));
      const resA = JSON.parse($.trim(res));

      $("#fechaAperturaC").text(resA.detalles.Fecha_Abrir);
      $("#fechaCierreC").text(resA.detalles.Fecha_Cierre);
      $("#usuarioAperturaC").text(resA.detalles.Usuario_Abrio);
      $("#usuarioCierreC").text(resA.detalles.Usuario_Cerro);

      $("#tablaDetallesBalanceC").children('tbody').append(`<tr>
        <td>Monto de apertura</td>
        <td><span class="dinero" style="color: green;">`+resA.detalles.Monto_Abrir+`</span></td>
      </tr>`);

      var totalVentas = 0;
      if(resA.ventas.length > 0){
        resA.ventas.forEach(venta => {
          if(venta.Tipo_Pago == "Efectivo"){
            totalVentas += parseFloat(venta.Total);
          }

          $("#tablaTotalVentasC").children('tbody').append(`<tr>
            <td>`+venta.Tipo_Pago+`</td>
            <td><span class="dinero" style="color: green;">`+venta.Total+`</span></td>
          </tr>`);
        });

        $("#tablaDetallesBalanceC").children('tbody').append(`<tr>
          <td>Ventas Efectivo</td>
          <td><span class="dinero" style="color: green;">`+totalVentas+`</span></td>
        </tr>`);
      }else{
        $("#tablaTotalVentasC").children('tbody').append(`<tr>
          <td colspan="2">No existen registros.</td>
        </tr>`);
      }

      var totalEntradas = 0, totalSalidas = 0;
      if(resA.movimientos.length > 0){
        resA.movimientos.forEach(mov => {
          var color = '';
          if(mov.Tipo == "Entrada"){
            totalEntradas += parseFloat(mov.Monto);
            color = 'green'
          }else{
            totalSalidas += parseFloat(mov.Monto);
            color = 'red';
          }

          $("#tablaMovimientosC").children('tbody').append(`<tr>
            <td>`+mov.Fecha_Registro+`</td>
            <td>`+mov.Tipo+`</td>
            <td>`+mov.Descripcion+`</td>
            <td><span class="dinero" style="color: `+color+`;">`+mov.Monto+`</span></td>
          </tr>`);
        });

        $("#tablaDetallesBalanceC").children('tbody').append(`<tr>
          <td>Entradas</td>
          <td><span class="dinero" style="color: green;">`+totalEntradas+`</span></td>
        </tr>
        <tr>
          <td>Salidas</td>
          <td><span class="dinero" style="color: red;">`+totalSalidas+`</span></td>
        </tr>`);
      }else{
        $("#tablaMovimientosC").children('tbody').append(`<tr>
          <td colspan="4">No existen registros.</td>
        </tr>`);
      }

      var balance = parseFloat(resA.detalles.Monto_Abrir) + totalVentas + totalEntradas - totalSalidas;
      $("#balanceCajaC").text(balance);
      $("#montoCorteC").text(resA.detalles.Monto_Cierre);
      $("#restanteCajaC").text(parseFloat(resA.detalles.Monto_Cierre) - balance);

      moneda();
      $('#MDetallesCorte').modal('show');
    })
    .fail(function() {
      console.log("Error ajax");
    })
    .always(function() {
      $('#carga').hide();
    });
  });

  $(document).on('change', '#sucursal', () => {
    tablaCajas();
  });

  $(document).on('dblclick', '#tablaCajas tbody tr', (e) => {
    var botonModificar = $(e.currentTarget).find('td:eq(6) button.bModificarCaja');
    if (botonModificar.length > 0) {
      botonModificar.click();
    }
  });
});