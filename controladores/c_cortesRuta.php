<?php
class cortesRuta {

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
				$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), Ruta, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y'), DATE_FORMAT(Fecha_Fin, '%d-%m-%Y')) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Corte, Ruta, Fecha_Inicio, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y') AS FechaI, Fecha_Fin,  DATE_FORMAT(Fecha_Fin, '%d-%m-%Y') AS FechaF, Verificado, Ventas, Imagen, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, (SELECT COUNT(*) FROM cortes_ruta $busqueda) AS Num FROM cortes_ruta $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$bModificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1') {
						$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-pencil"></i></button>';
					}

					$bEliminar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
						$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-trash"></i></button>';
					}

					$bVerificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1') {
						$bVerificar = '<button type="button" class="btn btn-sm btn-success bVerificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Verificar"><i class="fas fa-check"></i></button>';
					}

					$bImagen = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
						$bImagen = '<button type="button" class="btn btn-sm btn-secondary bImagenCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Imagen"><i class="fas fa-image"></i></button>';
					}

					$verificado = '<span class="badge text-bg-warning">No</span>';
					if($row[$i]['Verificado'] == '1'){
						$verificado = '<span class="badge text-bg-success">Si</span>';
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Corte'],
						'Fecha' => $row[$i]['Fecha_Registro'],
						'Ruta' => $row[$i]['Ruta'],
						'Fecha_Inicio' => $row[$i]['FechaI'],
						'Fecha_Fin' => $row[$i]['FechaF'],
						'Total' => '',
						'Verificado' => $verificado,
						'Acciones' => $bModificar.' '.$bEliminar
					);
				}

				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
			}
		}

		echo json_encode($arreglo);
	}

	/*public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$marca = $omodelo->link->real_escape_string($marca);
		$modelo = $omodelo->link->real_escape_string($modelo);
		$matricula = $omodelo->link->real_escape_string($matricula);
		$descripcion = $omodelo->link->real_escape_string($descripcion);

		$query = "INSERT INTO vehiculos SET Marca = '$marca', Modelo = '$modelo', Matricula = '$matricula', Descripcion = '$descripcion', Fecha_Registro = '$fecha'";
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
		$marca = $omodelo->link->real_escape_string($marca);
		$modelo = $omodelo->link->real_escape_string($modelo);
		$matricula = $omodelo->link->real_escape_string($matricula);
		$descripcion = $omodelo->link->real_escape_string($descripcion);

		$query = "UPDATE vehiculos SET Marca = '$marca', Modelo = '$modelo', Matricula = '$matricula', Descripcion = '$descripcion' WHERE ID_Vehiculo = '$id'";
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

		$query = "DELETE FROM vehiculos WHERE ID_Vehiculo = '$id'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);			
		}
	}*/
}
?>
