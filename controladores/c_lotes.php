<?php
class lotes {

	public function _consultar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		
		$buscar = $omodelo->link->real_escape_string($buscar);
		$limit = $omodelo->link->real_escape_string($limit);
		$pagina = $omodelo->link->real_escape_string($pagina);
		$ordenColumna = $omodelo->link->real_escape_string($ordenColumna);
		$orden = $omodelo->link->real_escape_string($orden);
		$arreglo = array();

		$busqueda = '';
		if (trim($buscar) != '') {
			$separa = explode(' ', trim($buscar));
			$busqueda = 'WHERE ';
			for ($i = 0; $i < count($separa); $i++) {
				$busqueda .= "CONCAT(DATE_FORMAT(lotes.Fecha_Registro, '%d-%m-%Y %r'), ID_Lote, IF(lotes.FK_Presentacion = 0, productos.Codigo, (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion)), Descripcion, IFNULL((SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), ''), lotes.Nombre, Cantidad, DATE_FORMAT(lotes.Fecha_Caducidad, '%d-%m-%Y'), IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal), '')) REGEXP '" . $separa[$i] . "'";
				if ($i < (count($separa) - 1)) {
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Lote, ID_Lote AS Lote, lotes.Fecha_Registro AS Fecha, DATE_FORMAT(lotes.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, IF(lotes.FK_Presentacion = 0, productos.Codigo, (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion)) AS Codigo, lotes.FK_Producto AS FK_Producto, FK_Presentacion, Descripcion AS Producto, IFNULL((SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), '') AS Presentacion, FK_Sucursal, IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal), '') AS Sucursal, lotes.Nombre AS Nombre, Cantidad, Fecha_Caducidad AS Caducidad, DATE_FORMAT(lotes.Fecha_Caducidad, '%d-%m-%Y') AS Fecha_Caducidad, (SELECT COUNT(*) FROM lotes INNER JOIN productos ON ID_Producto = FK_Producto $busqueda) AS Num FROM lotes INNER JOIN productos ON ID_Producto = FK_Producto $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if ($row == 'si') {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			if ($numerofilas > 0) {
				for ($i = 0; $i < $numerofilas; $i++) {
					$bModificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_lotes'][2] == '1') {
						$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarLote" title="Modificar" attrID="'.$row[$i]['ID_Lote'].'""><i class="fas fa-pencil"></i></button>';
					}

					$bEliminar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_lotes'][3] == '1') {
						$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarLote" title="Eliminar" attrID="'.$row[$i]['ID_Lote'].'"><i class="fas fa-trash"></i></button>';
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Lote'],
						'Fecha' => $row[$i]['Fecha_Registro'],
						'Lote' => $row[$i]['Lote'],
						'Nombre' => $row[$i]['Nombre'],
			      'Caducidad' => $row[$i]['Fecha_Caducidad'],
			      'Producto' => $row[$i]['Codigo'].'<br>'.$row[$i]['Producto'].($row[0]['Presentacion'] != '' ? ' ('.$row[0]['Presentacion'].')' : ''),
			      'Cantidad' => '<span class="cantidad">'.$row[$i]['Cantidad'].'</span>',    
			      'Sucursal' => $row[$i]['Sucursal'],
						'Acciones' => $bModificar.' '.$bEliminar.' <button class="btn btn-outline-info btn-sm mb-1 bImprimirLote" attrID="'.$row[$i]['ID_Lote'].'"><i class="fas fa-barcode"></i></button>'
					);
				}
					
				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);
			}
		}

		echo json_encode($arreglo);
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$nombre = $omodelo->link->real_escape_string($nombre);
		$fecha = $omodelo->link->real_escape_string($fecha);
		$cantidad = $omodelo->link->real_escape_string($cantidad);
		$id = $omodelo->link->real_escape_string($id);

		$query = "UPDATE lotes SET Nombre = '$nombre', Cantidad = '$cantidad', Fecha_Caducidad = '$fecha' WHERE ID_Lote = '$id'";
		$error = $omodelo->_insertar($query);
			
			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
	}

	public function _eliminar() {
	  $omodelo = new m_modelo();
	  extract($_POST);
	  $id = $omodelo->link->real_escape_string($id);

	  $query = "DELETE FROM lotes WHERE ID_Lote = '$id'";
	  $error = $omodelo->_insertar($query);

	  if ($error == 'si') {
	    echo "Error 3: " . mysqli_error($omodelo->link);
	  } else {
	    echo 'Correcto';

	    $omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
	  }
  }
}
?>