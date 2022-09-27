
<?php
class areas {

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
				$busqueda .= "CONCAT(ID_Area, areas.Nombre, areas.Descripcion) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Area, areas.Nombre, areas.Descripcion, Nivel, (SELECT COUNT(*) FROM areas $busqueda) AS Num FROM areas $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$descripcion = "No hay datos registrados"; 

					if ($row[$i]['Descripcion'] != "") {
						$descripcion = $row[$i]['Descripcion']; 
					}

					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_areas'][3] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm" id="ModificarArea" attrid="'.$row[$i]['ID_Area'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_areas'][4] == '1') {
						$botonPermisosEliminar = '<button class="btn btn-danger btn-sm" id="EliminarArea" attrid="'.$row[$i]['ID_Area'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>';
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Area'],
						'Nombre' => $row[$i]['Nombre'],
						'Nivel' => $row[$i]['Nivel'],
						'Descripcion' => $row[$i]['Descripcion'],
						'Acciones' => $botonPermisosModificar.' '.$botonPermisosEliminar,
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
		$Nombre =  $omodelo->link->real_escape_string($Nombre);
		$Descripcion =  $omodelo->link->real_escape_string($Descripcion);
		$Nivel =  $omodelo->link->real_escape_string($Nivel);

		$query = "INSERT INTO areas SET Nombre = '$Nombre', Descripcion = '$Descripcion', Nivel = '$Nivel'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$IDArea =  $omodelo->link->real_escape_string($IDArea);
		$Nombre =  $omodelo->link->real_escape_string($Nombre);
		$Descripcion =  $omodelo->link->real_escape_string($Descripcion);
		$Nivel =  $omodelo->link->real_escape_string($Nivel);


		$query = "UPDATE areas SET Nombre = '$Nombre', Descripcion = '$Descripcion', Nivel = '$Nivel' WHERE ID_Area = '$IDArea'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$IDArea =  $omodelo->link->real_escape_string($IDArea);

		$query = "DELETE FROM areas WHERE ID_Area='$IDArea'";
		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$IDArea =  $omodelo->link->real_escape_string($IDArea);

		$query = "SELECT ID_Area, Nombre, Descripcion, Nivel FROM areas WHERE ID_Area = '$IDArea'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				echo json_encode($row[0]);
			}
		}
	}

}
?>
