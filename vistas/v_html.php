<!DOCTYPE html>

<html
  lang="es"
  class="light-style layout-menu-fixed"
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
    <title>Admin | Spidi</title>
    <meta name="description" content="" />
    <link rel="shortcut icon" href="vistas/assets/img/favicon/favicon.ico" />
    <link rel="stylesheet" href="vistas/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="vistas/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="vistas/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="vistas/assets/css/demo.css" />
    <link rel="stylesheet" href="vistas/assets/css/css.css" />
    <link href="vistas/assets/plugins/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="vistas/assets/plugins/datatables/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link  href="vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.css" rel="stylesheet">
    <link href="vistas/assets/plugins/fontawesome/css/all.css" rel="stylesheet">
    <link href="vistas/assets/plugins/sweetalert/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="vistas/assets/vendor/js/helpers.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="https://spidi.smartpoint.com.mx/">
              <img src="vistas/assets/img/logos/icon.png" style="width:25%; margin: 30px 70px;">
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item active nvpagina" aria-current="page" carga="v_inicio" titulo="Pedidos" id="cargarInicio">
              <a class="menu-link" href="javascript:void(0)">
                <i class="menu-icon tf-icons bx bx-menu"></i>
                <div data-i18n="Pendientes">Pendientes </div>
              </a>
            </li>

            <li class="menu-item nvpagina" carga="v_pedidosAceptados" titulo="Pedidos aceptados" id="cargaPedidosAceptados">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon tf-icons bx bx-check"></i>
                <div data-i18n="Aceptados">Aceptados</div>
              </a>
            </li>

            <li class="menu-item nvpagina" carga="v_pedidos" titulo="Pedidos en curso" id="cargaPedidos">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bx-stopwatch"></i>
                <div data-i18n="En curso">En curso</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Repartidores</span>
            </li>
            <li class="menu-item nvpagina" carga="v_mapa" titulo="Mapa de repartidores" id="cargaMapa">
              <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Mapa">Mapa</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Negocios</span>
            </li>
            <li class="menu-item nvpagina" href="javascript:void(0)" carga="v_negocios" titulo="Negocios" id="cargaNegocios">
              <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Ver negocios">Ver negocios</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Pedidos</span>
            </li>
            <li class="menu-item nvpagina" carga="v_historialPedidos" titulo="Historial de pedidos" id="cargaPedidos">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Historial de pedidos">Historial de pedidos</div>
              </a>
            </li>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <div id="DivPedidosPendientes"></div>
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="vistas/assets/img/logos/icon.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="vistas/assets/img/logos/icon.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">#CorreoAdmin#</span>
                            <small class="text-muted">Administrador</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0)" id="CerrarSesion">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Cerrar sesión</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>

          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Cargar las vistas -->


            <div class="container-fluid" >
              <div class="row row-cols-auto" style="margin: 18px 0px;">
                <div class="col">
                  <h6 id="titleVista" class="gris">Pedidos</h6>
                </div>
              </div>
              <div class="row">
                <div class="col-12" id="main">
                  
                </div>
              </div>
            </div>

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="vistas/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="vistas/assets/vendor/libs/popper/popper.js"></script>
    <script src="vistas/assets/vendor/js/bootstrap.js"></script>
    <script src="vistas/assets/vendor/js/menu.js"></script>
    <script src="vistas/assets/js/main.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/jquery-validation/jquery.validate.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/jquery-validation/additional-methods.js" ></script>
    <script src="vistas/assets/plugins/jquery-validation/jquery-validation.init.js" type="text/javascript"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/jszip.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/vfs_fonts.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/datatables/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/sweetalert/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.socket.io/4.5.0/socket.io.min.js" integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous"></script>
    <script type="text/javascript" src="vistas/assets/js/script.js"></script>
    <script type="text/javascript" src="vistas/assets/js/pedidosAceptados.js"></script>
    <script type="text/javascript" src="vistas/assets/js/pedidosEnCurso.js"></script>
    <script type="text/javascript" src="vistas/assets/js/historialPedidos.js"></script>
    <script type="text/javascript" src="vistas/assets/js/negocios.js"></script>
    <script type="text/javascript" src="vistas/assets/js/mapa.js"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC3V5UxT22-Yzc-Z47OOeWcl7b7OLrqn0&v=weekly" defer>
    </script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="vistas/assets/vendor/js/buttons.js"></script>
  </body>
</html>
