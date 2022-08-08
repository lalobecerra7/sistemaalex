jQuery(document).ready(function () {

	$(document).on('click', '#IniciarSesion', function (e) {
		e.preventDefault();

		var btn = $(this);
		var form = $('#FormLogin');

		form.validate({
			rules: {
				username: {
					required: true
				},
				password: {
					required: true
				}
			}
		});

		if (!form.valid()) {
			return;
		}else{
			var data = "usuario="+$.trim($("#usr").val())+"&contrasena="+$("#pwd").val();

			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: data,
				beforeSend: function() {
				    progressBoton(btn);
				}
			})
			.done(function(res) {
				setTimeout(function () {
					if($.trim(res) == "Correcto"){
						$("#mostrarMensaje").html('<div class="alert alert-primary alert-dismissible fade show" role="alert"><h6 class="alert-heading"><b>Acceso correcto</b></h6><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						setTimeout(function () {
							window.location.reload();
						}, 500);
					}else if($.trim(res) == "0"){
						$("#mostrarMensaje").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><h6 class="alert-heading">Usuario o contraseña incorrectos</h6><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
					}else if($.trim(res) == "Supero Intentos"){
						$("#mostrarMensaje").html('<div class="alert alert-warning alert-dismissible fade show" role="alert"><h6 class="alert-heading">Superaste el número de intentos, <b>debes esperar 15 minutos para volver a intentar</b></h6><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
					}else{
						$("#mostrarMensaje").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><h6 class="alert-heading">Error Inesperado</h6><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

						console.log($.trim(res));
					}
				}, 1000);	
			})
			.fail(function() {
				console.log("Error de ajax");
			})
			.always(function() {
				unprogressBoton(btn);
			});
		}
	});
});
