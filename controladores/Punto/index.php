<html>
    <head>
		<link rel="shortcut icon" type="image/x-icon" href="img/logos/logo.png">

            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/flaticon.css">
            <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
            <link rel="stylesheet" href="css/style.css">
        
    	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
   </head>

   <body>
        <br>
        <div class="row text-center justify-content-md-center">
            <div class="col-lg-6">
                <img src="imagenes/logo01.png" width="40%">
                <h2 class="mt-3">¡Regístrate fácilmente y obtén tu primer mes de prueba gratis!</h2>
            </div>
        </div>
        <br>
        <div class="row justify-content-md-center">
            <div class="col-lg-6">
                <form id="formRegistro" class="row form-contact contact_form" novalidate="novalidate">
                    <div class="form-group col-md-6 mb-2 mt-2">
                        <input type="text" class="form-control w-100" name="nombres" id="nombres" placeholder="Nombre(s)" maxlength="60" required>
                    </div>
                    <div class="form-group col-md-6 mb-2 mt-2">
                        <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellido(s)" maxlength="60" required>
                    </div>
                    <div class="form-group col-md-12 mb-2 mt-2">
                        <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo Electrónico" maxlength="300" required>
                    </div>
                    <div class="form-group col-md-5 mb-2 mt-2">
                        <input type="password" class="form-control contras" name="contrasena" id="contrasena" placeholder="Contraseña" maxlength="60" required>
                    </div>
                    <div class="form-group col-md-5 mb-2 mt-2">
                        <input type="password" class="form-control contras" name="confirmacion" id="confirmacion" placeholder="Contraseña" maxlength="60" required>
                    </div>
                    <div class="form-group col-2 text-center mb-2 mt-2">
                        <button type="button" class="btn btn-primary" id="verContrasena"><i id="verPass" class="fa-solid fa-eye"></i></button>
                    </div>
                    <div class="form-group col-md-5 mb-2 mt-2">
                        <input type="hidden" name="accion" id="accion" value="registro">
                        <div class="g-recaptcha" data-sitekey="6LeCoZYpAAAAAN5m3aK_HkUyhT-C99R_NHHJGQnB"></div>
                    </div>
                    <div class="form-group col-md-7 text-justify mb-2 mt-2">
                        <p>Al hacer click en "Registrarse", aceptas nuestros <a href="javascript:void(0)" data-toggle="modal" data-target="#Mterminos"><b>términos y condiciones</b></a>. Es posible también que recibas correos electrónicos de las notificaciones sobre bigtool.</p>
                    </div>
                    <div class="form-group col-md-12 text-center"> 
                        <button class="btn btn-primary btn-lg btn-block" type="submit" id="bBotonRegistar">Registrarse</button>
                    </div>
                </form>
                <div id="form-messages" class="mt-3"></div>
            </div>
        </div>  
    </body>
    <script src="js/jquery-3.6.1.min.js"></script>
    <script src="js/main.js"></script>
    <!-----claves reCHAP------>
    <!-----6LeCoZYpAAAAAN5m3aK_HkUyhT-C99R_NHHJGQnB----->
    <!-----6LeCoZYpAAAAAPe24zETaw4y28gYkrq61_vfqmWd----->
</html>