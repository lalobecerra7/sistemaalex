<?php
class rutas {

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
				$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), Nombre, Descripcion) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Ruta, Nombre, Descripcion, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, (SELECT COUNT(*) FROM rutas $busqueda) AS Num FROM rutas $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$bModificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_rutas'][3] == '1') {
						$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarRuta" attrID="'.$row[$i]['ID_Ruta'].'"><i class="fas fa-pencil"></i></button>';
					}

					$bEliminar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_rutas'][4] == '1') {
						$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarRuta" attrID="'.$row[$i]['ID_Ruta'].'"><i class="fas fa-trash"></i></button>';
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Ruta'],
						'Fecha' => $row[$i]['Fecha_Registro'],
						'Nombre' => $row[$i]['Nombre'],
						'Descripcion' => $row[$i]['Descripcion'],
						'Acciones' => $bModificar.' '.$bEliminar
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
		$nombre = $omodelo->link->real_escape_string($nombre);
		$descripcion = $omodelo->link->real_escape_string($descripcion);

		$query = "INSERT INTO rutas SET Nombre = '$nombre', Descripcion = '$descripcion', Fecha_Registro = '$fecha'";
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
		$id = $omodelo->link->real_escape_string($id);
		$nombre = $omodelo->link->real_escape_string($nombre);
		$descripcion = $omodelo->link->real_escape_string($descripcion);

		$query = "UPDATE rutas SET Nombre = '$nombre', Descripcion = '$descripcion' WHERE ID_Ruta = '$id'";
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
		$id =  $omodelo->link->real_escape_string($id);

		$query = "DELETE FROM rutas WHERE ID_Ruta = '$id'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);			
		}
	}
}
?>
