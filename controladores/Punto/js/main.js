jQuery(document).ready(function ($) {
  $('#formRegistro').submit(function(event) {
        // Evitar el envío del formulario por defecto
        event.preventDefault();
        
        // Realizar la verificación del reCAPTCHA antes de enviar el formulario
        var response = grecaptcha.getResponse();
        if(response.length == 0) {
            alert('Por favor, completa el reCAPTCHA.');
            return false;
        }

        // Obtener los datos del formulario
        var formData = $(this).serialize();
        
        // Enviar los datos del formulario mediante AJAX
        $.ajax({
            type: 'POST',
            url: 'metodos.php', // Ruta al script PHP que procesará los datos
            data: formData,
            success: function(response) {
                // Manejar la respuesta del servidor
                $('#form-messages').html(response);
                $('#formRegistro')[0].reset(); // Limpiar el formulario después de enviarlo
            }
        });
    });

  $(document).on('click', '#verContrasena', function() {
        var contrasenaInput = $('#contrasena');
        var confirmacionInput = $('#confirmacion');

        if (contrasenaInput.attr('type') === 'password') {
            contrasenaInput.attr('type', 'text');
            confirmacionInput.attr('type', 'text');
            $('#verPass').removeClass('fa-eye');
            $('#verPass').addClass('fa-eye-slash');
        } else {
            contrasenaInput.attr('type', 'password');
            confirmacionInput.attr('type', 'password');
            $('#verPass').removeClass('fa-eye-slash');
            $('#verPass').addClass('fa-eye');
        }
  });
});