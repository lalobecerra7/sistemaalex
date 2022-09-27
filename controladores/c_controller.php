<?php
date_default_timezone_set('America/Mexico_City');
include 'modelo/m_modelo.php';
include "controladores/c_login.php";
include "controladores/c_sucursales.php";
include "controladores/c_clientes.php";
include "controladores/c_proveedores.php";
include "controladores/c_areas.php";
include "controladores/c_personal.php";
include "controladores/c_hacerventa.php";
include "controladores/c_categorias.php";
include "controladores/c_usuarios.php";
include "controladores/c_productos.php";
include "controladores/c_inventario.php";
include "controladores/c_cajas.php";
include "controladores/c_perfil.php";
include "controladores/c_impuestos.php";
include "controladores/c_tickets.php";
include "controladores/c_zonas.php";

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
                <i class="menu-icon fas fa-suitcase"></i>
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

		$botonCategorias = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_categorias'][1] == '1') {
			$botonCategorias = '<li class="menu-item cargarVista" carga="v_categorias" titulo="Categorias / familias" id="cargarCategorias">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Categorias">Categorias(familias)</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuCategorias#', $botonCategorias, $pagina);

		$botonCajas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cajas'][1] == '1') {
			$botonCajas = '<li class="menu-item cargarVista" carga="v_cajas" titulo="Cajas" id="cargarCajas">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Cajas">Cajas</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuCajas#', $botonCajas, $pagina);

		$botonImpuestos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_impuestos'][1] == '1') {
			$botonImpuestos = '<li class="menu-item cargarVista" carga="v_impuestos" titulo="Impuestos" id="cargarImpuestos">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Impuestos">Impuestos</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuImpuestos#', $botonImpuestos, $pagina);

		$botonTickets = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_tickets'][1] == '1') {
			$botonTickets = '<li class="menu-item cargarVista" carga="v_tickets" titulo="Tickets" id="cargarTickets">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Tickets">Tickets</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuTickets#', $botonTickets, $pagina);

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

			$query = "SELECT ID_Unidad, Nombre FROM unidades";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Unidad'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#unidades#', $opciones, $pagina);

		}else if($nombre == "v_inventario"){

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

			$query = "SELECT ID_Zona, Nombre FROM zona";
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
		}
		
		return $pagina;
	}
}
?>
