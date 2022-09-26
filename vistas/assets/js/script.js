//formato de modeda a la clase .dinero
function moneda() {
    $(".dinero").each(function(index, el) {
        if(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) < 0){
            $(this).html(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * -1);
            $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
            $(this).html('-'+$(this).html());
        }else{
            $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
        }
    });

    $(".porcentaje").each(function(index, el) {
        if(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) < 0){
            $(this).html(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * -1);
            $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * 100) / 100)+'%');
            $(this).html('-'+$(this).html());
        }else{
            $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * 100) / 100)+'%');
        }
    });

    $(".cantidad").each(function(index, el) {
        $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
    });
}

jQuery(document).ready(function($) {
    var idVista = "cargarInicio";
    setTimeout(function(){
      $("#cargarInicio").trigger("click");
    },100);
    //permisos();
    $(document).on('click', '.cargarVista', function() {

        var nombre = $(this).attr('carga'), titulo = $(this).attr('titulo'), id = $(this).attr('id'), atri = $(this).attr('atri'), pesta = $(this).attr('pesta'); 
        var data = "metodo=cambiar&accion="+nombre+"&atri="+atri+"&pesta="+pesta;
        idVista = $(this).attr('id');
        var itemVista = $(this);
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data,
          beforeSend: function() {
            //$("#carga").show();
          }
        })
        .done(function(res) {
          $("#verVista").html(res);
          $("#vistaTitulo").html(titulo);
          $(".cargarVista").removeClass("active");
          itemVista.addClass("active");
          if(nombre == "v_inicio"){
           
          }
          crearDataTable();
          

          if(typeof window[nombre] === 'function') {
            window[nombre]();
            console.log(nombre);
          }

        })
        .fail(function() {
          console.log("Error ajax");
        }).always(function() {
          //$("#carga").hide();
        }); 
    });

    $(document).on('click', '#CerrarSesion', function() {
        cerrarSesion();
    });

    $(document).on('click', '#VerContrasenas', function() {
        console.log( $(this).parent().parent().html());
        if($(this).children('i').hasClass('fa-eye')){
            $(this).children('i').removeClass('fa-eye');
            $(this).children('i').addClass('fa-eye-slash');
            $('.contraCampo').attr('type', 'text');
        }else{
            $(this).children('i').removeClass('fa-eye-slash');
            $(this).children('i').addClass('fa-eye');
            $('.contraCampo').attr('type', 'password');
        }
    });

    $(document).on('click', '#GuardarNuevaContrasena', function(event) {
      event.preventDefault();
      var boton = $(this);
      var form = $('#FormNuevaContrasena');

      form.validate({
        rules: {
          ContrasenaActual: {
            required: true,
          },
          ContrasenaRepetir: {
            required: true,
            equalTo: "#ContrasenaNueva"
          },

        },
        messages: {
            ContrasenaActual: {
                required: "La contraseña actual es obligatoria"
            },
            ContrasenaRepetir:{
                required: "La nueva contraseña es obligatoria"
            },
        },
      });

      if (!form.valid()) {
        return;
      }else{
        Swal.fire({
          title: '¿Estas a punto de cambiar tu contraseña actual?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, guardar',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {
            var data = "metodo=detalles&accion=usuarios&tipo=CambiarContrasena&ContraActual="+$("#ContrasenaActual").val()+"&ContraNueva="+$("#ContrasenaNueva").val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
              if ($.trim(res) == "Correcto") {
                Swal.fire({
                  icon: 'success',
                  title: 'Contraseña cambiada correctamente'
                });
                $("#FormNuevaContrasena").trigger("reset");
                $("#ModalCambiarContrasena").modal("hide");
              }else{
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error inesperado al cambiar la contraseña.'
                });
                console.log(res);
              }
            })
            .fail(function(){
              console.log("error ajax");
            });
          }
        });
      }
    });  

});

function permisos() {
    var data = "metodo=detalles&accion=usuarios&tipo=ConsultarPermisosUsuario";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        console.log($.trim(res));
        var resA = JSON.parse(res);
        if(resA.Tipo != "Administrador"){
          //$(".cargarVista").hide();
          //$(".cargarVista[carga='v_usuarios']").remove();
          var cadena = resA.Cadena.split('~');
          var permisos = "";
          //console.log(cadena);

          for (var i = cadena.length - 1; i >= 0; i--) {
            permisos = cadena[i].split(',');
            if(permisos[0] == "v_ventas"){
              if(permisos[2] == '0'){
                $("#cargarHacerVenta").remove();
              }
            }

            if(permisos[0] == "v_inicio"){
              if(permisos[1] == '1'){
                setTimeout(function(){
                    $("#cargarInicio").trigger("click");
                }, 10);
              }else{
                $(".cargarVista[carga='"+permisos[0]+"']").remove();
              }
            }else{
              if(permisos[1] == '0'){
                $(".cargarVista[carga='"+permisos[0]+"']").remove();
              }       
            }                
          }
        }else{
          setTimeout(function(){
            $("#cargarInicio").trigger("click");
          }, 10);
        }
    })
    .fail(function() {
        console.log("Error ajax");
    })
    .always(function() {
        //console.log("complete");
    });
}

function cerrarSesion(){
    var data="metodo=eliminar&accion=login";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
    })
    .done(function(res) {
        console.log(res);
        window.location.reload();
    })
    .fail(function() {
        console.log("Error ajax");
    });
}

function readURL(input,ima) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $(ima).html("<img src='"+e.target.result+"' style='width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;' class='img-thumbnail'><br>");
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function ConsultarImagen(){
  var data="metodo=detalles&accion=perfil";
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: data,
  })
  .done(function(res) {
    if ($.trim(res) != "") {
      $(".imagenPerfilChica").attr("src", "vistas/assets/archivos/fotosUsuarios/"+$.trim(res));
    }else{
      $(".imagenPerfilChica").attr("src", "vistas/assets/archivos/default.jpg");
    }
    
  })
  .fail(function() {
    console.log("Error ajax");
  });
}