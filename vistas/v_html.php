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
    <title>SmartPoint</title>
    <meta name="description" content="" />
    <link rel="shortcut icon" href="vistas/assets/img/favicon/favicon.ico" /> 
    <link rel="stylesheet" href="vistas/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="vistas/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="vistas/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="vistas/assets/css/demo.css" />
    <link rel="stylesheet" href="vistas/assets/css/css.css" />
    <link  href="vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.css" rel="stylesheet">
    <link href="vistas/assets/plugins/fontawesome/css/all.css" rel="stylesheet">
    <link href="vistas/assets/plugins/sweetalert/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="vistas/assets/vendor/js/helpers.js"></script>
    <link rel="stylesheet" href="vistas/assets/plugins/myDataTable/css/myDataTable.css">
  </head>

  <body>
    <!-- Layout wrapper -->
    <div id="caja">
      <div class="container-fluid" style="height: 100vh; overflow-y: auto; ">
        <div class="row" style="height: 100vh;">
          <div class="col-12">
            <div class="row">
              <div class="col-12 text-end">
                <button type="button" class="btn" id="bCerrarVenCaja"><i class="fas fa-times"></i></button>
              </div>     
            </div>
            <div class="row" id="verCaja">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.php">
              <img src="vistas/assets/img/logos/icon.png" style="width:100%;">
            </a>

            <a href="index.php;" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>



          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item active cargarVista" aria-current="page" carga="v_inicio" titulo="Inicio" id="cargarInicio">
              <a class="menu-link" href="javascript:void(0)">
                <i class="menu-icon fas fa-home"></i>
                <div data-i18n="Inicio">Inicio </div>
              </a>
            </li>

            <li class="menu-item cargarVista" carga="v_sucursales" titulo="Sucursales" id="cargarSucursales">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-map-marker"></i>
                <div data-i18n="Sucursales">Sucursales</div>
              </a>
            </li>

            <li class="menu-item cargarVista" carga="v_proveedores" titulo="Proveedores" id="cargarProveedores">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-suitcase"></i>
                <div data-i18n="Proveedores">Proveedores</div>
              </a>
            </li>

            <li class="menu-item cargarVista" carga="v_clientes" titulo="Clientes" id="cargarClientes">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-face-grin"></i>
                <div data-i18n="Clientes">Clientes</div>
              </a>
            </li>

            <li class="menu-item cargarVista" carga="v_areas" titulo="Áreas" id="cargarAreas">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-building-user"></i>
                <div data-i18n="Áreas">Áreas</div>
              </a>
            </li>

            <li class="menu-item cargarVista" carga="v_categorias" titulo="Categorias / familias" id="cargarCategorias">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-project-diagram"></i>
                <div data-i18n="Categorias">Categorias / familias</div>
              </a>
            </li>

            <li class="menu-item cargarVista" carga="v_impuestos" titulo="Impuestos" id="cargarImpuestos">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-copy"></i>
                <div data-i18n="Impuestos">Impuestos</div>
              </a>
            </li>

            <li class="menu-item cargarVista" carga="v_personal" titulo="Personal" id="cargarPersonal">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-users"></i>
                <div data-i18n="Personal">Personal</div>
              </a>
            </li>

            <!-- Layouts -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Layouts</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="layouts-without-menu.html" class="menu-link">
                    <div data-i18n="Without menu">Without menu</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-without-navbar.html" class="menu-link">
                    <div data-i18n="Without navbar">Without navbar</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-container.html" class="menu-link">
                    <div data-i18n="Container">Container</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-fluid.html" class="menu-link">
                    <div data-i18n="Fluid">Fluid</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="layouts-blank.html" class="menu-link">
                    <div data-i18n="Blank">Blank</div>
                  </a>
                </li>
              </ul>
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
              <div id="DivPedidosPendientes">
                <a href="javascript:void(0)" id="cargarVenta" ><i class="fas fa-shopping-cart"></i></a>
              </div>
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
                      <a class="dropdown-item" href="javascript:void(0)" id="CambiarContra">
                        <i class="fas fa-key me-2"></i>
                        <span class="align-middle">Cambiar contraseña</span>
                      </a>
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
              <div class="row">
                <div class="col-12" id="verVista">
                  
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
    <script src="vistas/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vistas/assets/vendor/js/menu.js"></script>
    <script src="vistas/assets/js/main.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/jquery-validation/jquery.validate.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/jquery-validation/additional-methods.js" ></script>
    <script src="vistas/assets/plugins/jquery-validation/jquery-validation.init.js" type="text/javascript"></script>
    <script type="text/javascript" src="vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/sweetalert/dist/sweetalert2.min.js"></script>
    <!-- <script src="https://cdn.socket.io/4.5.0/socket.io.min.js" integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous"></script> -->
    <script async defer src="vistas/assets/vendor/js/buttons.js"></script>
    <script src="vistas/assets/plugins/myDataTable/js/myDataTable.js"></script>
    <script type="text/javascript" src="vistas/assets/js/script.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/general.js"></script>
    <script type="text/javascript" src="vistas/assets/js/sucursales.js"></script>
    <script type="text/javascript" src="vistas/assets/js/clientes.js"></script>
    <script type="text/javascript" src="vistas/assets/js/proveedores.js"></script>
    <script type="text/javascript" src="vistas/assets/js/areas.js"></script>
    <script type="text/javascript" src="vistas/assets/js/personal.js"></script>
    <script type="text/javascript" src="vistas/assets/js/hacerventa.js"></script>
    <script type="text/javascript" src="vistas/assets/js/categorias.js"></script>
    <script type="text/javascript" src="vistas/assets/js/impuestos.js"></script>
  </body>
</html>
