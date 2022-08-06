<?php
date_default_timezone_set('America/Mexico_City');
include 'modelo/m_modelo.php';
include "controladores/c_login.php";
include "controladores/c_principal.php";
include "controladores/c_pedidos.php";
include "controladores/c_pedidosAceptados.php";
include "controladores/c_negocios.php";
include "controladores/c_historialPedidos.php";


class controller {


	function _layouts(){
		$omodelo = new m_modelo();
		$_SESSION['user_admin']['FechaFin'] = date('Y-m-d');
		$_SESSION['user_admin']['FechaIni'] = date("Y-m-d", strtotime($_SESSION['user_admin']['FechaFin']."- 30 days")); 


		$fechahoy = date('Y-m-d H:i:s');
		$pagina = file_get_contents('vistas/v_html.php');
		$pagina = str_replace('#CorreoAdmin#',"Administrador",$pagina);
		$pagina = str_replace('#usuario#',$_SESSION['user_admin']['Nombre'],$pagina);
		$pagina = str_replace('#prl#',substr($_SESSION['user_admin']['Nombre'], 0, 1),$pagina);
		$pagina = str_replace('#fechahoy#',$fechahoy,$pagina);
		$pagina = str_replace('#fechaIni#',$_SESSION['user_admin']['FechaIni'],$pagina);
		$pagina = str_replace('#fechaFin#',$_SESSION['user_admin']['FechaFin'],$pagina);

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

		if($nombre == "v_pedidos"){

			$query = "SELECT ID_Repartidor, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS Nombre FROM usuarios_repartidor";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				$opciones = "<option value='' selected> - Seleccione una opción - </option>";
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= "<option value='".$row[$i]['ID_Repartidor']."'> ".$row[$i]['Nombre']." </option>";
					}
				}
			}

			$pagina = str_replace('#RepartidoresSelect#',$opciones,$pagina);
			
		}else if($nombre == "v_pedidosAceptados"){
			
			$query = "SELECT ID_Repartidor, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS Nombre FROM usuarios_repartidor";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				$opciones = "<option value='' selected> - Seleccione una opción - </option>";
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= "<option value='".$row[$i]['ID_Repartidor']."'> ".$row[$i]['Nombre']." </option>";
					}
				}
			}

			$pagina = str_replace('#RepartidoresSelect#',$opciones,$pagina);
			
		}else if($nombre == "v_negocios"){
			
			$query = "SELECT ID_Clasificacion, Nombre FROM clasificaciones";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$listaClasificaciones = '';
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) {
						$listaClasificaciones .= '<option value="'.$row[$i]['ID_Clasificacion'].'">'.$row[$i]['Nombre'].'</option>';
					}
				}
			}
			$pagina = str_replace('#listaClasificaciones#',$listaClasificaciones,$pagina);
			$pagina = str_replace('#listaClasificacionesModificar#',$listaClasificaciones,$pagina);


		}

		
		return $pagina;
	}
}
?>
