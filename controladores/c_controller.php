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
		}
		$pagina = str_replace('#MenuCompras#', $botonCompras, $pagina);

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

		$botonConfiguracion = '';
		if ($omodelo->permisos() == 'Administrador') {
			$botonConfiguracion = '<li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon fas fa-cogs"></i>
                <div data-i18n="Layouts">Configuración</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_tickets" titulo="Ticket" id="cargarTicket">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Tickets">Ticket</div>
                  </a>
                </li>
              </ul>

              <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_facturacion" titulo="Facturación 4.0" id="cargarFacturacion">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Facturación">Facturación 4.0</div>
                  </a>
                </li>
              </ul>
            </li>';
		}
		$pagina = str_replace('#MenuConfiguracion#', $botonConfiguracion, $pagina);

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
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales, usuarios WHERE ID_Sucursal = FK_Sucursal AND ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$sucursal = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas == 1) {
					$sucursal = '<h5 id="Sucursales" value="'.$row[0]['ID_Sucursal'].'">'.$row[0]['Nombre'].'</h5>';
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
							$sucursal = '<select class="form-select" id="Sucursales" name="Sucursales">
											<option value="" selected>-Seleccione una opción-</option>
											'.$opciones.'
										</select>';
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
		}
		
		return $pagina;
	}
}
?>
