
<?php
class recibos {

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
				$busqueda .= "CONCAT(ID_Recibo_Dinero, Fecha, Monto, Descripcion, Nombre_Proveedor, Fecha_Registro, FK_Usuario, LPAD(ID_Recibo_Dinero, 8, '0')) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Recibo_Dinero, Fecha, Monto, Descripcion, Nombre_Proveedor, Fecha_Registro, FK_Usuario, (SELECT COUNT(*) FROM recibos_dinero $busqueda) AS Num, LPAD(ID_Recibo_Dinero, 8, '0') AS Folio FROM recibos_dinero $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador'|| @$omodelo->permisos()['v_recibos'][3] == '1') {
						$botonPermisosEliminar = '<button class="btn btn-danger btn-sm EliminarRecibo" attrid="'.$row[$i]['ID_Recibo_Dinero'].'" Folio="'.$row[$i]['Folio'].'"><i class="fas fa-trash"></i></button>';
					}

					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador'|| @$omodelo->permisos()['v_recibos'][4] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm ModificarRecibo" attrid="'.$row[$i]['ID_Recibo_Dinero'].'" Folio="'.$row[$i]['Folio'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonImprimirRecibo = "";
					if ($omodelo->permisos() == 'Administrador'|| @$omodelo->permisos()['v_recibos'][5] == '1') {
						$botonImprimirRecibo = '<button title="Imprimir recibo" class="btn btn-success btn-sm ImprimirRecibo" attrid="'.$row[$i]['ID_Recibo_Dinero'].'" Folio="'.$row[$i]['Folio'].'"><i class="fas fa-file"></i></button>';
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Recibo_Dinero'],
						'Folio' => $row[$i]['Folio'],
						'Fecha' => $row[$i]['Fecha'],
						'Monto' => $row[$i]['Monto'],
						'Descripcion' => $row[$i]['Descripcion'],
						'Proveedor' => $row[$i]['Nombre_Proveedor'],
						'Acciones' => $botonPermisosModificar." ".$botonPermisosEliminar." ".$botonImprimirRecibo,
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
		$FechaRecibo = $omodelo->link->real_escape_string($FechaRecibo);
		$MontoRecibo = $omodelo->link->real_escape_string($MontoRecibo);
		$DescripcionRecibo = $omodelo->link->real_escape_string($DescripcionRecibo);
		$NombreProveedorRecibo = $omodelo->link->real_escape_string($NombreProveedorRecibo);

		$query = "INSERT INTO recibos_dinero SET Fecha = '$FechaRecibo', Monto = '$MontoRecibo', Descripcion = '$DescripcionRecibo', Fecha_Registro = '$fecha', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', Nombre_Proveedor = '$NombreProveedorRecibo'";
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
		$IDRecibo = $omodelo->link->real_escape_string($IDRecibo);
		$FechaRecibo = $omodelo->link->real_escape_string($FechaRecibo);
		$MontoRecibo = $omodelo->link->real_escape_string($MontoRecibo);
		$DescripcionRecibo = $omodelo->link->real_escape_string($DescripcionRecibo);
		$NombreProveedorRecibo = $omodelo->link->real_escape_string($NombreProveedorRecibo);

		$query = "UPDATE recibos_dinero SET Fecha = '$FechaRecibo', Monto = '$MontoRecibo', Descripcion = '$DescripcionRecibo', Nombre_Proveedor = '$NombreProveedorRecibo' WHERE ID_Recibo_Dinero = '$IDRecibo'";
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

		$query = "DELETE FROM recibos_dinero WHERE ID_Recibo_Dinero = '$id'";
		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$IDRecibo =  $omodelo->link->real_escape_string($IDRecibo);

		$query = "SELECT Fecha, Monto, Descripcion, Fecha_Registro FROM recibos_dinero WHERE ID_Recibo_Dinero = '$IDRecibo'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$arreglo = array(
						'Fecha' => $row[$i]['Fecha'],
						'Monto' => $row[$i]['Monto'],
						'Descripcion' => $row[$i]['Descripcion'],
					);
					
				}
			}
		}

		echo json_encode($arreglo);
	}

}
?>
