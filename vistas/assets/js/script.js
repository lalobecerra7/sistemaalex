

jQuery(document).ready(function($) {

    var idVista = "cargarInicio";

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

});

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