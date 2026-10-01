function v_lotes() {
  tablaLotes();

  $('#formLotes').validate({
    rules: {
      fechaCadLote: {
        required: true
      },
      cantidadLote: {
        required: true
      }
    },
    messages: {
      fechaCadLote: {
        required: "La fecha es requerida."
      },
      cantidadLote: {
        required: "La cantidad de producto es requerida."
      }
    },
    submitHandler: function(form) {
      Swal.fire({
        title: '¿Estas seguro que deseas modificar el lote?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: '¡No, cancelar!',
        confirmButtonText: '¡Si, modificar!'
      }).then(({ value }) => {
        if (value){
          guardarLote();
        }
      });
    }
  });
}

function guardarLote() {
  var data = "metodo=modificar&accion=lotes&nombre="+$.trim($("#nombreLote").val())
  +"&fecha="+$("#fechaCadLote").val()+"&cantidad="+$("#cantidadLote").val()
  +"&id="+$('#bGuardarLote').attr('attrID');
    
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: data,
    beforeSend: function() {
      $('#carga').show();
    }
  })
  .done(function(res){
    if ($.trim(res) == 'Correcto') {  
      Swal.fire({
        icon: 'success',
        title: 'El lote ha sido modificado correctamente',
      });

      tablaLotes();
      $('#modalLote').modal('hide');
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Error inesperado al modificar el lote'
      });

      console.log($.trim(res));
    }
  })
  .fail(function() {
     console.error('Error ajax');
  })
  .always(() => {
    $('#carga').hide();
  });
}

function tablaLotes(){
  ajaxMyDatatable({
    table: $('#tablaLotes'),
    colums: [
      'Fecha',
      'Lote',
      'Nombre',
      'Caducidad',
      'Producto',
      'Cantidad',
      'Sucursal',
      'Acciones'
    ],
    sort: [0, 'desc'],
    url: 'index.php',
    params: {
      metodo: 'consultar',
      accion: 'lotes',
    }
  });
}

jQuery(document).ready($ => {

  $(document).on('click', '.bEliminarLote', function () {
    const btn = $(this);
    Swal.fire({
      title: '¿Estás seguro de eliminar el lote?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: '¡No, cancelar!',
      confirmButtonText: '¡Si, eliminar!'
    }).then(({ value }) => {
      if (value) {
        const data = 'metodo=eliminar&accion=lotes&id='+btn.attr('attrID');
        
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data,
          beforeSend: function() { 
            $('#carga').show();
          }
        })
        .done(function(res) {
          if($.trim(res) === 'Correcto'){
            Swal.fire({
              icon: 'success',
              title: 'El lote ha sido eliminado correctamente'
            });

            tablaLotes();
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error inesperado al eliminar el lote.'
            });

            console.log($.trim(res));
          }
        })
        .fail(function() {
          console.error('Error ajax');
        })
        .always(function() { 
          $('#carga').hide() ;
        });
      }
    });
  });

  $(document).on('click', '.bModificarLote', function () {
    var btn = $(this);
    var padre = btn.parent().parent();
    $('#bGuardarLote').attr('attrID', btn.attr('attrID'));
    $('#formLotes')[0].reset();
    var validator = $("#formLotes").validate();
    validator.resetForm();

    var separa = padre.children('td:eq(3)').text().split('-');
    var fecha = separa[2]+'-'+separa[1]+'-'+separa[0];
    
    $("#fechaCadLote").val(fecha);
    $("#nombreLote").val(padre.children('td:eq(2)').text());
    $("#cantidadLote").val(padre.children('td:eq(5)').text().replaceAll(',', ''));

    $('#modalLote').modal('show');
  });

  $(document).on('click', '.bImprimirLote', function() {
    window.open("./controladores/barras/imprimirCodigo.php?id="+$(this).attr('attrID'), "_blank");
  });
});