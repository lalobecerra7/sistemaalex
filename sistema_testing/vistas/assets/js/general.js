function v_general () {
  consultarGeneral();
}

function consultarGeneral () {
  var data = 'metodo=consultar&accion=general'
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: data
  })
    .done(function (res) {
      console.log(res)
      var datos = JSON.parse($.trim(res))
      $('#CalleGeneral').val(datos.Calle)
      $('#NoExtGeneral').val(datos.No_Exterior)
      $('#NoIntGeneral').val(datos.No_Interior)
      $('#ColoniaGeneral').val(datos.Colonia)
      $('#CPGeneral').val(datos.CP)
      $('#CiudadGeneral').val(datos.Ciudad)
      $('#EstadoGeneral').val(datos.Estado)
      $('#PaisGeneral').val(datos.Pais)
      $('#TelefonoGeneral').val(datos.Telefono)
      $('#EmailGeneral').val(datos.Email)
      $('#MonedaGeneral').val(datos.Moneda)
      $('#SimboloGeneral').val(datos.Simbolo)
      $('#OrigenGeneral').val(datos.Origen)
      $('#PonerImgGeneral').val(datos.Imagen)
      $('#PonerTodosGeneral').val(datos.Ticket)
      if (datos.Imagen_Ticket != '') {
        $('#verImagenGeneral img').attr(
          'src',
          'vistas/assets/archivos/imagenTicket/General/' + datos.Imagen_Ticket
        )
      } else {
        $('#verImagenGeneral img').attr(
          'src',
          'vistas/assets/archivos/fotosProductos/default.jpg'
        )
      }
      console.log(
        'vistas/assets/archivos/imagenTicket/General/' + datos.Imagen_Ticket
      )
    })
    .fail(function () {
      console.log('Error ajax')
    })
}

$(document).on('change', '#ImagenGeneral', function () {
  readURL(this, $('#verImagenGeneral'))
})

function readURL (input, ima) {
  if (input.files && input.files[0]) {
    var reader = new FileReader()
    reader.onload = function (e) {
      $(ima).html(
        "<img src='" +
          e.target.result +
          "' style='width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;' class='img-thumbnail'><br>"
      )
    }
    reader.readAsDataURL(input.files[0])
  }
}

$(document).on('click', '#GuardarGeneral', function () {
  console.log('Entro a la funcion')
  var data = new FormData(document.getElementById('formDatosGeneral'))
  data.append('metodo', 'modificar')
  data.append('accion', 'general')
  console.log('entro')
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: data,
    processData: false,
    contentType: false,
    beforeSend: function () {
      $('#carga').show()
    }
  })
    .done(function (res) {
      if ($.trim(res) == 'Correcto') {
        console.log($.trim(res))
        Swal.fire({
          icon: 'success',
          title: 'Los datos han sido guardados correctamente'
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Error inesperado al guardar los datos.'
        })
        console.log($.trim(res))
      }
    })
    .fail(function () {
      console.log('Error ajax')
    })
    .always(function () {
      $('#carga').hide()
    })
})
