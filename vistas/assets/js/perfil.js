function v_perfil() {

    $('#FormDatosPerfil').validate({
        rules: {
            CorreoActual:{
                required: true
            },
        },
        messages: {
            CorreoActual:{
                required: "El correo electrónico es obligatorio"
            },
        },
        submitHandler: function(form) {
            var btn = $("#GuardarDatosPerfil");
            var data = new FormData(document.getElementById("FormDatosPerfil"));
            data.append('metodo', 'insertar');
            data.append('accion', 'perfil');
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    progressBoton(btn);
                }
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Datos modificados correctamente'
                    });
                       $("#cargarPerfil").trigger("click");
                       ConsultarImagen();
                       //$("#imagenPerfilChica").attr("src", "vistas/assets/img/usuarios/")
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al modificar datos del perfil'
                    });
                    console.log($.trim(res));
                }
            })
            .fail(function() {
                console.log("Error ajax");
            })
            .always(function() {
                unprogressBoton(btn);
            });         
        }
    });
}


jQuery(document).ready(function($) {
    $(document).on('click', '#verFotoPerfil', function () {
        $("#FotoPerfil").trigger("click");
    });

    $(document).on('change', '#FotoPerfil', function() {
        readURL(this, $("#verFotoPerfil"));
    });
   
});
