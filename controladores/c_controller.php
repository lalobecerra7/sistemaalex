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
