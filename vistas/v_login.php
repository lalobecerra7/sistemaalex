<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="vistas/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Iniciar sesión</title>

    <meta name="description" content="" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="vistas/assets/img/favicon/favicon.ico" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="vistas/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="vistas/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="vistas/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="vistas/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="vistas/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="vistas/assets/vendor/css/pages/page-auth.css" />
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center" style="margin: auto;">
                <a href="javascript:void(0)">
                  <img src="vistas/assets/img/logos/icon.png" style="width:100%;">
                </a>
              </div>
              <br>
              <!-- /Logo -->
              <form id="FormLogin" class="mb-3">
                <div class="mb-3" id="mostrarMensaje">
              
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Correo electrónico</label>
                  <input
                    type="email"
                    class="form-control"
                    id="usr"
                    name="usr"
                    autocomplete="on"
                    placeholder="Ingresa tu correo electrónico"
                    autofocus
                    required
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Contraseña</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="pwd"
                      class="form-control contra"
                      name="pwd"
                      placeholder="********"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer verPass1"><i class="bx bx-show"></i></span>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" id="IniciarSesion" class="btn btn-lg w-100 btn-primary">Iniciar sesión</button>
                </div>
              </form>

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="vistas/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="vistas/assets/vendor/libs/popper/popper.js"></script>
    <script src="vistas/assets/vendor/js/bootstrap.js"></script>
    <script src="vistas/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script async defer src="vistas/assets/vendor/js/buttons.js"></script>

    <script type="text/javascript" src="vistas/assets/plugins/jquery-validation/jquery.validate.js"></script>
  	<script type="text/javascript" src="vistas/assets/plugins/jquery-validation/additional-methods.js" ></script>
  	<script src="vistas/assets/plugins/jquery-validation/jquery-validation.init.js" type="text/javascript"></script>
  	<script type="text/javascript" src="vistas/assets/plugins/general.js"></script>
  	<script type="text/javascript" src="vistas/assets/js/login.js"></script>

  </body>
</html>
