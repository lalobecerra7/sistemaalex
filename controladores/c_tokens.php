
<?php
class tokens {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$buscar =  $omodelo->link->real_escape_string($buscar);
		$limit =  $omodelo->link->real_escape_string($limit);
		$pagina =  $omodelo->link->real_escape_string($pagina);
		$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
		$orden =  $omodelo->link->real_escape_string($orden);
		$arreglo = array();

		$busqueda = '';
		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));
			$busqueda = 'WHERE ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(Codigo, Cantidad, IF(Activo = 0, 'Disponible', 'Usado')) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Token, Codigo, Cantidad, IF(Activo = 0, 'Disponible', 'Usado') AS Estatus, (SELECT COUNT(*) FROM tokens_descuentos $busqueda) AS Num FROM tokens_descuentos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_tokens'][2] == '1') {
						$botonPermisosEliminar = '<button class="btn btn-danger btn-sm EliminarToken" attrid="'.$row[$i]['ID_Token'].'" codigo="'.$row[$i]['Codigo'].'"><i class="fas fa-trash"></i></button>';
					}
					
					if ($row[$i]['Estatus'] == 'Disponible') {
						$estatus='<span class="badge rounded-pill bg-success"><b>Disponible</b></span>';
					}else{
						$estatus='<span class="badge rounded-pill bg-danger"><b>Usado</b></span>';	
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Token'],
						'Codigo' => $row[$i]['Codigo'],
						'Cantidad' => "$".number_format($row[$i]['Cantidad'], 2),
						'Estatus' => $estatus,
						'Acciones' => $botonPermisosEliminar,
					);
					
				}

				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
			}
		}

		echo json_encode($arreglo);
	}

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);

		$fecha = date('Y-m-d H:i:s'); 
		$Codigo = $omodelo->link->real_escape_string($Codigo);
		$Cantidad = $omodelo->link->real_escape_string($Cantidad);

		$query = "SELECT Codigo FROM tokens_descuentos WHERE Codigo = '$Codigo' AND Activo = 0";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				echo "Registrado";
			}else{
				$query = "INSERT INTO tokens_descuentos SET Codigo = '$Codigo', Cantidad = '$Cantidad', Activo = 0, Fecha_Registro = '$fecha', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
				$row = $omodelo->_insertar($query);

				if ($row == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
					$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
				}
			}
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id =  $omodelo->link->real_escape_string($id);

		$query = "DELETE FROM tokens_descuentos WHERE ID_Token = '$id'";
		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

}
?>
