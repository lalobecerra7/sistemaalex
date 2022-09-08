
<?php
class categorias {

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
				$busqueda .= "CONCAT(ID_Categoria, Nombre, Descripcion) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Categoria, Nombre, Descripcion, (SELECT COUNT(*) FROM categorias $busqueda) AS Num FROM categorias $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Categoria'],
						'Nombre' => $row[$i]['Nombre'],
						'Descripcion' => $row[$i]['Descripcion'],
						'Acciones' => '<button class="btn btn-primary btn-sm" id="ModificarCategoria" attrid="'.$row[$i]['ID_Categoria'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm" id="EliminarCategoria" attrid="'.$row[$i]['ID_Categoria'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>',
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

		$query = "INSERT INTO categorias SET Nombre = '$Nombre', Descripcion = '$Descripcion'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$IDCategoria =  $omodelo->link->real_escape_string($IDCategoria);
		$Nombre =  $omodelo->link->real_escape_string($Nombre);
		$Descripcion =  $omodelo->link->real_escape_string($Descripcion);

		$query = "UPDATE categorias SET Nombre = '$Nombre', Descripcion = '$Descripcion' WHERE ID_categoria = '$IDCategoria'";
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
		$IDCategoria =  $omodelo->link->real_escape_string($IDCategoria);

		$query = "DELETE FROM categorias WHERE ID_Categoria='$IDCategoria'";
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
		$IDCategoria =  $omodelo->link->real_escape_string($IDCategoria);

		$query = "SELECT ID_Categoria, Nombre, Descripcion FROM categorias WHERE ID_categoria = '$IDCategoria'";
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
