<?php
class choferes {

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
				$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), Nombre, Primer_Apellido, Segundo_Apellido) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Chofer, Nombre, Primer_Apellido, Segundo_Apellido, choferes.Fecha_Registro AS Fecha, DATE_FORMAT(choferes.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Marca, Modelo, Descripcion, (SELECT COUNT(*) FROM choferes $busqueda) AS Num FROM choferes LEFT JOIN vehiculos ON ID_Vehiculo = FK_Vehiculo $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$bModificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_choferes'][3] == '1') {
						$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarChofer" attrID="'.$row[$i]['ID_Chofer'].'"><i class="fas fa-pencil"></i></button>';
					}

					$bEliminar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_choferes'][4] == '1') {
						$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarChofer" attrID="'.$row[$i]['ID_Chofer'].'"><i class="fas fa-trash"></i></button>';
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Chofer'],
						'Fecha' => $row[$i]['Fecha_Registro'],
						'Nombre' => $row[$i]['Nombre'],
						'Primer_Apellido' => $row[$i]['Primer_Apellido'],
						'Segundo_Apellido' => $row[$i]['Segundo_Apellido'],
						'Vehiculo' => $row[$i]['Marca'] && $row[$i]['Modelo'] ? 'Marca: <b>'. $row[$i]['Marca'] .'</b></br>'.'Modelo: <b>'. $row[$i]['Modelo'] .'</b></br>'.'Descripcion: <b>'. $row[$i]['Descripcion'] .'</b></br>' : 'No cuenta con vehiculo asignado',
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
		$primerApellido = $omodelo->link->real_escape_string($primerApellido);
		$segundoApellido = $omodelo->link->real_escape_string($segundoApellido);
		$vehiculo = $omodelo->link->real_escape_string($vehiculosChofer);

		$query = "INSERT INTO choferes SET Nombre = '$nombre', Primer_Apellido = '$primerApellido', Segundo_Apellido = '$segundoApellido', FK_Vehiculo = '$vehiculo', Fecha_Registro = '$fecha'";
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
		$primerApellido = $omodelo->link->real_escape_string($primerApellido);
		$segundoApellido = $omodelo->link->real_escape_string($segundoApellido);
		$vehiculo = $omodelo->link->real_escape_string($vehiculosChofer);

		$query = "UPDATE choferes SET Nombre = '$nombre', Primer_Apellido = '$primerApellido', Segundo_Apellido = '$segundoApellido', FK_Vehiculo = '$vehiculo' WHERE ID_Chofer = '$id'";
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

		$query = "DELETE FROM choferes WHERE ID_Chofer= '$id'";
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
