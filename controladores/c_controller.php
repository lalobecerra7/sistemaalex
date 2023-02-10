<?php
date_default_timezone_set('America/Mexico_City');
include 'modelo/m_modelo.php';
include "controladores/c_login.php";
include "controladores/c_sucursales.php";
include "controladores/c_clientes.php";
include "controladores/c_proveedores.php";
include "controladores/c_areas.php";
include "controladores/c_hacerventa.php";
include "controladores/c_hacerventacaja.php";
include "controladores/c_categorias.php";
include "controladores/c_usuarios.php";
include "controladores/c_productos.php";
include "controladores/c_inventario.php";
include "controladores/c_cajas.php";
include "controladores/c_perfil.php";
include "controladores/c_impuestos.php";
include "controladores/c_tickets.php";
include "controladores/c_general.php";
include "controladores/c_zonas.php";
include "controladores/c_compras.php";
include "controladores/c_hacerCompra.php";
include "controladores/c_ventas.php";
include "controladores/c_facturacion.php";
include "controladores/c_importes.php";
include "controladores/c_precios.php";
include "controladores/c_reportes.php";
include "controladores/c_reporteCaja.php";
include "controladores/c_reporteVentas.php";

class controller {

	function _layouts(){
			$omodelo = new m_modelo();
			$_SESSION['user_admin']['FechaFin'] = date('Y-m-d');
			$_SESSION['user_admin']['FechaIni'] = date("Y-m-d", strtotime($_SESSION['user_admin']['FechaFin']."- 30 days")); 

			$fechahoy = date('Y-m-d H:i:s');
			$pagina = file_get_contents('vistas/v_html.php');
			$pagina = str_replace('#NombreUsuario#',$_SESSION['user_admin']['Nombre'],$pagina);
			$pagina = str_replace('#PermisosUsuario#',$_SESSION['user_admin']['Tipo_Usuario'],$pagina);
			$pagina = str_replace('#IDUsuario#',$_SESSION['user_admin']['ID_Usuario'],$pagina);
			$pagina = str_replace('#usuario#',$_SESSION['user_admin']['Nombre'],$pagina);
			$pagina = str_replace('#prl#',substr($_SESSION['user_admin']['Nombre'], 0, 1),$pagina);
			$pagina = str_replace('#fechahoy#',$fechahoy,$pagina);
			$pagina = str_replace('#fechaIni#',$_SESSION['user_admin']['FechaIni'],$pagina);
			$pagina = str_replace('#fechaFin#',$_SESSION['user_admin']['FechaFin'],$pagina);
			
			if ($_SESSION['user_admin']['Foto'] == "") {
					$pagina = str_replace('#ImagenPerfil#', 'vistas/assets/archivos/default.jpg', $pagina);
			}else{
					$pagina = str_replace('#ImagenPerfil#', 'vistas/assets/archivos/fotosUsuarios/'.$_SESSION['user_admin']['Foto'], $pagina);
			}     

			$alertas = '';
			//QUERY ORIGINAL, MANDARLO A JUANCHO PARA QUE LO CHEQUE MAÑANA, SE AGREGO EL SUM
			//$query = "SELECT ID_Stock, stock_productos.FK_Producto, Descripcion, Nombre_Unidad, FK_Presentacion, FK_Sucursal, Minimo, Maximo, Nombre, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Producto = stock_productos.FK_Producto AND FK_Presentacion = FK_Presentacion AND FK_Sucursal = FK_Sucursal), 0) AS Cantidad FROM stock_productos INNER JOIN productos ON stock_productos.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion";
			$query = "SELECT ID_Stock, stock_productos.FK_Producto, Descripcion, Nombre_Unidad, FK_Presentacion, FK_Sucursal, Minimo, Maximo, Nombre, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Producto = stock_productos.FK_Producto AND FK_Presentacion = stock_productos.FK_Presentacion AND FK_Sucursal = stock_productos.FK_Sucursal), 0) AS Cantidad FROM stock_productos INNER JOIN productos ON stock_productos.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$presentacion = 'Sin presentación';
						if($row[$i]['FK_Presentacion'] == 0){
							if(trim($row[$i]['Nombre_Unidad']) != ''){
								$presentacion = trim($row[$i]['Nombre_Unidad']);
							}
						}else{
							$presentacion = trim($row[$i]['Nombre']);
						}

						if($row[$i]['Cantidad'] > $row[$i]['Maximo']){
							$alertas .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
							  <strong>Se alcanzó el stock máximo de '.$row[$i]['Descripcion'].' '.$presentacion.'</strong>,la cantidad actual es '.$row[$i]['Cantidad'].', el mínimo es '.$row[$i]['Minimo'].'.
							  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>';
						}else if($row[$i]['Cantidad'] < $row[$i]['Minimo']){
							$alertas .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
							  <strong>Se alcanzó el stock mínimo de '.$row[$i]['Descripcion'].' '.$presentacion.'</strong>, la cantidad actual es '.$row[$i]['Cantidad'].', el mínimo es '.$row[$i]['Minimo'].'.
							  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>';
						}	
					}
				}
			}

			$pagina = str_replace('#alertas#', $alertas, $pagina);

		///************************* PERMISOS DEL MENU **************************///
		$botonSucursales = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_sucursales'][1] == '1') {
			$botonSucursales = '<li class="menu-item cargarVista" carga="v_sucursales" titulo="Sucursales" id="cargarSucursales">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-map-marker"></i>
                <div data-i18n="Sucursales">Sucursales</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuSucursales#', $botonSucursales, $pagina);

		$botonProveedores = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_proveedores'][1] == '1') {
			$botonProveedores = '<li class="menu-item cargarVista" carga="v_proveedores" titulo="Proveedores" id="cargarProveedores">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-truck-fast"></i>
                <div data-i18n="Proveedores">Proveedores</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuProveedores#', $botonProveedores, $pagina);

		$botonClientes = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][1] == '1') {
			$botonClientes = '<li class="menu-item cargarVista" carga="v_clientes" titulo="Clientes" id="cargarClientes">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-face-grin"></i>
                <div data-i18n="Clientes">Clientes</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuClientes#', $botonClientes, $pagina);

		$botonAreas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_areas'][1] == '1') {
			$botonAreas = '<li class="menu-item cargarVista" carga="v_areas" titulo="Áreas" id="cargarAreas">
              <a href="javascript:void(0)"  class="menu-link">
                <div data-i18n="Áreas">Áreas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuAreas#', $botonAreas, $pagina);

		$botonZonas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_zonas'][1] == '1') {
			$botonZonas = '<li class="menu-item cargarVista" carga="v_zonas" titulo="Zonas" id="cargarZonas">
              <a href="javascript:void(0)"  class="menu-link">
                <div data-i18n="Zonas">Zonas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuZonas#', $botonZonas, $pagina);
		

		$botonProductos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][1] == '1') {
			$botonProductos = '<li class="menu-item cargarVista" carga="v_productos" titulo="Productos" id="cargarProductos">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Productos">Productos</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuProductos#', $botonProductos, $pagina);

		$botonInventario = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][1] == '1') {
			$botonInventario = '<li class="menu-item cargarVista" carga="v_inventario" titulo="Inventario" id="cargarInventario">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Inventario">Inventario</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuInventario#', $botonInventario, $pagina);

		$botonCompras = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_compras'][1] == '1') {
			$botonCompras = '<li class="menu-item cargarVista" carga="v_compras" titulo="Compras" id="cargarCompras">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-basket-shopping"></i>
                <div data-i18n="Compras">Compras</div>
              </a>
            </li>';
		}else if(@$omodelo->permisos()['v_orden_compra'][1] == '1'){
				$botonCompras = '<li class="menu-item cargarVista" carga="v_hacerCompra" titulo="Hacer Compra" id="cargarHacerCompra">
            <a href="javascript:void(0)"  class="menu-link">
              <i class="menu-icon fas fa-basket-shopping"></i>
              <div data-i18n="Compras">Compras</div>
            </a>
       	</li>';
		}

		$pagina = str_replace('#MenuCompras#', $botonCompras, $pagina);

		$botonVentas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][1] == '1') {
			$botonVentas = '<li class="menu-item cargarVista" carga="v_ventas" titulo="Ventas" id="cargarVentas">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-shopping-cart"></i>
                <div data-i18n="Ventas">Ventas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuVentas#', $botonVentas, $pagina);

		$botonImportes = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_importes'][1] == '1') {
			$botonImportes = '<li class="menu-item cargarVista" carga="v_importes" titulo="Importes" id="cargarImportes">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-money-check"></i>
                <div data-i18n="Importes">Importes</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuImportes#', $botonImportes, $pagina);

		$botonPrecios = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_precios'][1] == '1') {
			$botonPrecios = '<li class="menu-item cargarVista" carga="v_precios" titulo="Precios" id="cargarPrecios">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Precios">Precios</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuPrecios#', $botonPrecios, $pagina);

		$botonCategorias = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_categorias'][1] == '1') {
			$botonCategorias = '<li class="menu-item cargarVista" carga="v_categorias" titulo="Familias" id="cargarCategorias">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Categorias">Familias (Categorias)</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuCategorias#', $botonCategorias, $pagina);

		$botonCajas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cajas'][1] == '1') {
			$botonCajas = '<li class="menu-item cargarVista" carga="v_cajas" titulo="Cajas" id="cargarCajas">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-cash-register"></i>
                <div data-i18n="Cajas">Cajas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuCajas#', $botonCajas, $pagina);

		$botonImpuestos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_impuestos'][1] == '1') {
			$botonImpuestos = '<li class="menu-item cargarVista" carga="v_impuestos" titulo="Impuestos" id="cargarImpuestos">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fa-solid fa-money-bill-transfer"></i>
                <div data-i18n="Impuestos">Impuestos</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuImpuestos#', $botonImpuestos, $pagina);

		$botonUsuarios = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][1] == '1') {
			$botonUsuarios = '<li class="menu-item cargarVista" carga="v_usuarios" titulo="Usuarios" id="cargarUsuarios">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-user"></i>
                <div data-i18n="Usuarios">Usuarios</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuUsuarios#', $botonUsuarios, $pagina);

		$botonTicket = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_tickets'][1] == '1') {
			$botonTicket = '<ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_tickets" titulo="Ticket" id="cargarTicket">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Tickets">Ticket</div>
                  </a>
                </li>
              </ul>';
		}
		$pagina = str_replace('#MenuTicket#', $botonTicket, $pagina);

		$botonFacturacion = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_facturacion'][1] == '1') {
			$botonFacturacion = ' <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_facturacion" titulo="Facturación 4.0" id="cargarFacturacion">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Facturación">Facturación 4.0</div>
                  </a>
                </li>
              </ul>';
		}
		$pagina = str_replace('#MenuFacturacion#', $botonFacturacion, $pagina);

		$botonProd = '';
		if($botonProductos != '' || $botonInventario != '' || $botonCategorias != '' || $botonAreas != '' || $botonPrecios != ''){
			  $botonProd = '<a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon fas fa-boxes-stacked"></i>
            <div data-i18n="Layouts">Productos</div>
        </a>';
    }
    $pagina = str_replace('#menuProd#', $botonProd, $pagina);

		$botonConfi = '';
		if($botonFacturacion != '' || $botonTicket != ''){
			  $botonConfi = '<a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon fas fa-cogs"></i>
            <div data-i18n="Layouts">Configuración</div>
        </a>';
    }
    $pagina = str_replace('#menuConfi#', $botonConfi, $pagina);

    $botonReporteCaja = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][1] == '1') {
			$botonReporteCaja = '<li class="menu-item cargarVista" carga="v_reporteCaja" titulo="Reporte de caja" id="cargaReporteCaja">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Reporte de caja">Reporte de caja</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesCaja#', $botonReporteCaja, $pagina);
    
		$botonReporteProductos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][2] == '1') {
			$botonReporteProductos = '<li class="menu-item cargarVista" carga="v_reporteProductos" titulo="Reporte productos" id="cargarReporteProductos">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Productos">Productos</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesProductos#', $botonReporteProductos, $pagina);

    $botonReporteClientes = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][3] == '1') {
			$botonReporteClientes = '<li class="menu-item cargarVista" carga="v_reporteClientes" titulo="Reporte clientes" id="cargarReporteClientes">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Clientes">Clientes</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesClientes#', $botonReporteClientes, $pagina);

		$botonReporteVentas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][4] == '1') {
			$botonReporteVentas = '<li class="menu-item cargarVista" carga="v_reporteVentas" titulo="Reporte ventas" id="cargarReporteVentas">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Ventas">Vendedores</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesVentas#', $botonReporteVentas, $pagina);

		$botonReporteCompras = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][5] == '1') {
			$botonReporteCompras = '<li class="menu-item cargarVista" carga="v_reporteCompras" titulo="Reporte compras" id="cargarReporteCompras">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Compras">Compras</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesCompras#', $botonReporteCompras, $pagina);

		$botonReporteFinanzas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][6] == '1') {
			$botonReporteFinanzas = '<li class="menu-item cargarVista" carga="v_reporteFinanzas" titulo="Reporte finanzas" id="cargarReporteFinanzas">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Finanzas">Finanzas</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesFinanzas#', $botonReporteFinanzas, $pagina);

		$botonReportes = '';
		if($botonReporteCaja != '' || $botonReporteProductos != '' || $botonReporteClientes != '' || $botonReporteVentas != '' || $botonReporteCompras != '' || $botonReporteFinanzas != ''){
    		$botonReportes = '<a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon fas fa-chart-line"></i>
                  <div data-i18n="Layouts">Reportes</div>
              </a>';
    }
    $pagina = str_replace('#menuReportes#', $botonReportes, $pagina);

    $botonCorteCaja = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][8] == '1') {
    		$botonCorteCaja = '<button type="button" class="btn btn-outline-danger oculto" data-bs-toggle="modal" data-bs-target="#ModalCerrarCaja" id="BotonCerrarCaja" attrid="">
                  <i class="fas fa-times"></i> Hacer corte de caja
                </button>';
    }

    $pagina = str_replace('#BotonCorteCaja#', $botonCorteCaja, $pagina);

    



    $venta = '';
    /*if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][2] == '1') {
    	$venta = file_get_contents('vistas/v_hacerventa.php');
    	$venta = $this->remplazar($venta, 'v_hacerventa');
    }*/

    $pagina = str_replace('#verVista#', $venta, $pagina);

		return $pagina;
	}

	function _contenido($vista){
		$pagina = file_get_contents("vistas/$vista.php");
		$pagina = $this->remplazar($pagina, $vista);

		return $pagina;
	}

	function _consultar($metodo){
		$objeto = new $metodo();
		$objeto->_consultar();
	}

	function _insertar($metodo){
		$objeto = new $metodo();
		$objeto->_insertar();
	}

	function _modificar($metodo){
		$objeto = new $metodo();
		$objeto->_modificar();
	}

	function _eliminar($metodo){
		$objeto = new $metodo();
		$objeto->_eliminar();
	}

	function _detalles($metodo){
		$objeto = new $metodo();
		$objeto->_detalles();
	}

	function _validar($pagina,$campo,$valo){
		$oobjeto = new $pagina();
		$oobjeto->_consultaValidar($campo,$valo);
	}

	function remplazar($pagina, $nombre){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d');

		if($nombre == "v_inicio"){

		}else if($nombre == "v_productos"){

			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_productos'][2] == '0') {
				echo '<script>$("#botonNuevoProductos").remove();</script>';
			}

			$query = "SELECT ID_Categoria, Nombre FROM categorias";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Categoria'] . '">' . $row[$i]['Nombre'] . '</option>';
					}
				}
			}

			$pagina = str_replace('#categorias#', $opciones, $pagina);

			$query = "SELECT ID_Area, Nombre FROM areas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Area'] . '">' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#areas#', $opciones, $pagina);

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#sucursales#', $opciones, $pagina);

			$query = "SELECT ID_Zona, Nombre FROM zonas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Zona'].'">'.$row[$i]['Nombre'].'</option>';
					}
				}
			}

			$pagina = str_replace('#zonas#', $opciones, $pagina);

			$query = "SELECT ID_Proveedor, Nombre, Empresa FROM proveedores WHERE ID_Proveedor != '1'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Proveedor'].'">'.$row[$i]['Empresa'].'/'.$row[$i]['Nombre'].'</option>';
					}
				}
			}

			$pagina = str_replace('#proveedores#', $opciones, $pagina);

		}else if($nombre == "v_inventario"){
			$botonPermisosTraslados = "";
			if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][10] == '1') {
					$botonPermisosTraslados = '<button type="button" class="btn btn-primary" id="bVerTraslados"><i class="fa-solid fa-arrows-left-right"></i> Traslados</button>';
			}

			$pagina = str_replace('#botonTraslados#', $botonPermisosTraslados, $pagina);

			$botonAgregarTras = "";
			if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][11] == '1') {
					$botonAgregarTras = '<button type="button" class="btn btn-primary" id="bAgregarTraslado"><i class="fas fa-plus"></i> Agregar</button>';
			}

			$pagina = str_replace('#bAgregarTraslado#', $botonAgregarTras, $pagina);
					
			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#sucursales#', $opciones, $pagina);

		}else if($nombre == "v_perfil"){
			if ($_SESSION['user_admin']['Foto'] == "") {
				$pagina = str_replace('#RutaImagenPerfil#', 'vistas/assets/archivos/default.jpg', $pagina);
			}else{
				$pagina = str_replace('#RutaImagenPerfil#', 'vistas/assets/archivos/fotosUsuarios/'.$_SESSION['user_admin']['Foto'], $pagina);
			}

			$pagina = str_replace('#CorreoActual#', $_SESSION['user_admin']['Correo'], $pagina);

		}else if($nombre == "v_sucursales"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_sucursales'][2] == '0') {
				echo '<script>$("#bontonNuevoSu").remove();</script>';
			}

			$query = "SELECT ID_Usuario, Nombre, Primer_Apellido, Segundo_Apellido FROM usuarios";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Usuario'] . '" >' . $row[$i]['Nombre'].' '. $row[$i]['Primer_Apellido'].' '. $row[$i]['Segundo_Apellido']. '</option>';
					}
				}
			}

			$pagina = str_replace('#usuarios#', $opciones, $pagina);

			$query = "SELECT ID_Zona, Nombre FROM zonas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Zona'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#ZonasSucursales#', $opciones, $pagina);

		}else if($nombre == "v_proveedores"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_proveedores'][2] == '0') {
				echo '<script>$("#bontonNuevoProve").remove();</script>';
			}
		}else if($nombre == "v_clientes"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_clientes'][2] == '0') {
				echo '<script>$("#botonNuevoCliente").remove();</script>';
			}

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesCliente#', $opciones, $pagina);
		}else if($nombre == "v_areas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_areas'][2] == '0') {
				echo '<script>$("#botonNuevaArea").remove();</script>';
			}
			
		}else if($nombre == "v_ventas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_ventas'][2] == '0') {
				echo '<script>$("#BotonNuevaVenta").remove();</script>';
			}
			
		}else if($nombre == "v_zonas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_zonas'][2] == '0') {
				echo '<script>$("#botonNuevaZona").remove();</script>';
			}
			
		}else if($nombre == "v_compras"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_compras'][2] == '0') {
				echo '<script>$(".botonNuevaCompra").remove();</script>';
			}
			
		}else if($nombre == "v_categorias"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_categorias'][2] == '0') {
				echo '<script>$("#botonNuevaCategoria").remove();</script>';
			}
		}else if($nombre == "v_cajas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_cajas'][2] == '0') {
				echo '<script>$("#botonNuevaCaja").remove();</script>';
			}
		}else if($nombre == "v_impuestos"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_impuestos'][2] == '0') {
				echo '<script>$("#botonNuevoImpuesto").remove();</script>';
			}
		}else if($nombre == "v_usuarios"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_usuarios'][2] == '0') {
				echo '<script>$("#botonNuevoUsuario").remove();</script>';
			}

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesUsuarios#', $opciones, $pagina);
		}else if($nombre == "v_tickets"){

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#sucursales#', $opciones, $pagina);
		}else if($nombre == "v_hacerCompra"){
			if ($omodelo->permisos() != 'Administrador'){
				if(@$omodelo->permisos()['v_compras'][2] == '0'){
					echo '<script>
						$("#RealizarCompra").addClass("oculto");
						$("#cambiarTipoCompra").addClass("oculto");
						$("#ponerDescuento").addClass("oculto");
						$("#verTotal").addClass("oculto");
						$("#verSubtotal").addClass("oculto");
					</script>';
				}

				if(@$omodelo->permisos()['v_compras'][2] == '0' || @$omodelo->permisos()['v_ordenes_compra'][5] == '0'){
					echo '<script>$(".costoOculto").addClass("oculto")</script>
					<style>
						.costoP, .totalP{
							display: none;
						}
					</style>';
				}
			}

			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales, usuarios WHERE ID_Sucursal = FK_Sucursal AND ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$sucursal = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas == 1) {
					$sucursal = 'Sucursal: <br><b id="Sucursales" value="'.$row[0]["ID_Sucursal"].'">'.$row[0]["Nombre"].'</b>';
				}else if($numerofilas == 0){
					$query2 = "SELECT ID_Sucursal, Nombre FROM sucursales";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;
					$opciones = '';

					if ($row2 == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
					} else {
						if ($numerofilas2 > 0) {
							for ($i = 0; $i < $numerofilas2; $i++) {
								$opciones .='<option value="' . $row2[$i]['ID_Sucursal'] . '" >' . $row2[$i]['Nombre']. '</option>';
							}
							$sucursal = '<div class="form-floating">
										<select class="form-select" id="Sucursales" name="Sucursales">
											'.$opciones.'
										</select>
										<label for="Sucursales">Sucursal</label>
										</div>';
						}
					}
				}
			}
			$pagina = str_replace('#sucursal#', $sucursal, $pagina);
                    
		}else if($nombre == "v_hacerventa"){
			$query = "SELECT FK_Sucursal, sucursales.Nombre AS NombreSucursal FROM usuarios INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal  WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$sucursal = '';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					$sucursal = 'Sucursal: <br><b id="SucursalVenta" attrid="'.$row[0]["FK_Sucursal"].'">'.$row[0]["NombreSucursal"].'</b>';
				}else{
					$query2 = "SELECT ID_Sucursal, Nombre FROM sucursales";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;
					$opciones = "";
					if ($row2 == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
					} else {
						for ($i=0; $i < $numerofilas2; $i++) { 
							$opciones .= '<option value="'.$row2[$i]["ID_Sucursal"].'">'.$row2[$i]["Nombre"].'</option>';
						}
						$sucursal = '
						<div class="form-floating">
		         	<select class="form-select" id="SucursalVenta" name="SucursalVenta" attrid="'.$row2[0]["ID_Sucursal"].'">
		          	'.$opciones.'
		          </select>
		        	<label for="SucursalVenta">Sucursal</label>
						</div>';
					}
				}
			}
			$pagina = str_replace('#MostrarSucursal#', $sucursal, $pagina);            
		}else if($nombre == "v_facturacion"){
				$query = "SELECT RFC, Nombre, Regimen FROM general WHERE ID_General = '1'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
					
				if ($row == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
				}else{
						if($numerofilas > 0){ 
								$pagina = str_replace('#rfc#', $row[0]['RFC'], $pagina);
								$pagina = str_replace('#nombre#', $row[0]['Nombre'], $pagina);
								echo '<script>$("#regimenFacturacion").val('.$row[0]['Regimen'].');</script>';
						}
				}		
		}else if($nombre == "v_precios"){
				$query = "SELECT ID_Zona, Nombre, Descripcion FROM zonas";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
				
				$zonas = '';	
				if ($row == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
				}else{
						if($numerofilas > 0){ 
								for ($i=0; $i < $numerofilas; $i++) { 
										$zonas .= '<option value="'.$row[$i]['ID_Zona'].'">'.$row[$i]['Nombre'].'</option>';
								}
						}
				}			

				$pagina = str_replace('#zonas#', $zonas, $pagina);
		}
		
		return $pagina;
	}
}
?>
