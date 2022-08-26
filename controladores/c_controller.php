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

		if($nombre == "v_inicio"){

		}
		
		return $pagina;
	}
}
?>
