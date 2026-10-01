<?php
class recepcion {

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
				$busqueda .= "CONCAT(DATE_FORMAT(ordenes_compra.Fecha_Registro, '%d-%m-%Y %r'), IFNULL(proveedores.Nombre, 'Proveedor General'), ID_Orden_Compra, IFNULL(DATE_FORMAT(Fecha_Programada, '%d-%m-%Y %r'), ''), IFNULL(DATE_FORMAT(Fecha_Recepcion, '%d-%m-%Y %r'), ''), IFNULL(recepcion.Estatus, 'Pendiente'), IFNULL(Razon_Social, ''), ordenes_compra.Estatus) REGEXP '" . $separa[$i] . "'";
				if ($i < (count($separa) - 1)) {
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Orden_Compra, ID_Recepcion, IFNULL(Razon_Social, '') AS Razon_Social, IFNULL(proveedores.Nombre, 'Proveedor General') AS Proveedor, ID_Orden_Compra AS Orden, ordenes_compra.Fecha_Registro AS Fecha, DATE_FORMAT(ordenes_compra.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Fecha_Programada, IFNULL(DATE_FORMAT(Fecha_Programada, '%d-%m-%Y %r'), '') AS FechaProg, Fecha_Recepcion, IFNULL(DATE_FORMAT(Fecha_Recepcion, '%d-%m-%Y %r'), '') AS FechaRece, IFNULL(recepcion.Estatus, 'Pendiente') AS Estatus, ordenes_compra.Estatus AS Estatus_Orden, (SELECT COUNT(*) FROM ordenes_compra LEFT JOIN recepcion ON ID_Orden_Compra = FK_Orden LEFT JOIN proveedores ON FK_Proveedor = ID_Proveedor $busqueda) AS Num FROM ordenes_compra LEFT JOIN recepcion ON ID_Orden_Compra = FK_Orden LEFT JOIN proveedores ON FK_Proveedor = ID_Proveedor $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if ($row == 'si') {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			if ($numerofilas > 0) {
				for ($i = 0; $i < $numerofilas; $i++) {
					$bVerificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_recepcion'][2] == '1') {
						$bVerificar = '<button type="button" class="btn btn-sm btn-primary bConcentardoRece" title="Concentrado" attrRece="'.$row[$i]['ID_Recepcion'].'" attrID="'.$row[$i]['ID_Orden_Compra'].'"><i class="fas fa-check"></i></button>';
					}

					$estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
          			if($row[$i]['Estatus'] == 'Completada'){
          				$estatus = '<span class="badge rounded-pill bg-success">Completada</span>';
          			}else if($row[$i]['Estatus'] == 'En Proceso'){
          				$estatus = '<span class="badge rounded-pill bg-primary">En Proceso</span>';
          			}

          			$estatusOrden = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
          			if($row[$i]['Estatus_Orden'] == 'Completada'){
          				$estatusOrden = '<span class="badge rounded-pill bg-success">Completada</span>';
          			}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Orden_Compra'],
						'Fecha' => $row[$i]['Fecha_Registro'],
					    'Orden' => $row[$i]['Orden'],
					    'Proveedor' => '<b>Nombre:</b> '.$row[$i]['Proveedor'].'<br><b>Razón Social:</b> '.$row[$i]['Razon_Social'],
					    'Fecha_Programada' => $row[$i]['FechaProg'],
					    'Fecha_Recepcion' => $row[$i]['FechaRece'],
					    'Estatus' => $estatus,
					    'Estatus_Orden' => $estatusOrden,
					    'Acciones' => $bVerificar
					);
				}
					
				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);
			}
		}

		echo json_encode($arreglo);
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == 'verConcentrado'){
			$orden = $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$query = "SELECT ID_Orden_Compra, FK_Proveedor, ID_Recepcion, ordenes_compra.Fecha_Registro AS Fecha_Registro, Fecha_Programada, Fecha_Recepcion, IFNULL(recepcion.Estatus, 'Pendiente') AS Estatus, ordenes_compra.Estatus AS Estatus_Orden FROM ordenes_compra LEFT JOIN recepcion ON ID_Orden_Compra = FK_Orden LEFT JOIN proveedores ON FK_Proveedor = ID_Proveedor WHERE FK_Orden = '$orden'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == 'si') {
				echo "Error 1: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$detalles = '<tr>
							<td colspan="0">No existen productos en la orden</td>
						</tr>';
						$query1 = "SELECT ID_Detalle_Orden, Copiada, FK_Presentacion, ID_Detalle_Recepcion, Lote, Caducidad, IFNULL(detalles_recepcion.Cantidad, 0) AS Cantidad, Estatus, Observaciones, IF(FK_Presentacion = 0, 'Sin Presentacion', presentaciones.Nombre) AS Presentacion, productos.Descripcion AS Producto, IF(ID_Detalle_Recepcion IS NULL, (detalles_orden.Cantidad + Regalado), detalles_recepcion.Cantidad_Orden) AS CantidadOrden, IF(FK_Presentacion = 0, productos.Codigo, presentaciones.Codigo) AS Codigo FROM detalles_orden INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN detalles_recepcion ON FK_Detalle_Orden = ID_Detalle_Orden WHERE FK_Orden = '$orden' ORDER BY Producto";
						$row1 = $omodelo->_consultar($query1);
						$numerofilas1 = $omodelo->numerofilas;	

						if ($row1 == 'si') {
							echo "Error 2: " . mysqli_error($omodelo->link);
						} else {
							if ($numerofilas1 > 0) {
								$detalles = '';
								for ($x = 0; $x < $numerofilas1; $x++) {
									$estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
									if($row1[$x]['Estatus'] == 'Completada'){
										$estatus = '<span class="badge rounded-pill bg-success">Completada</span>';
									}

									$bCopiar = '';
									if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_recepcion'][3] == '1') {
										$bCopiar = '<button type="button" class="btn btn-primary btn-sm bCopiarProdRece" attrOrden="'.$row1[$x]['ID_Detalle_Orden'].'" attrRece="'.$row1[$x]['ID_Detalle_Recepcion'].'"><i class="fas fa-plus"></i></button>'; 
									}

									$bVerificar = '';
									if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_recepcion'][4] == '1') {
										$bVerificar = '<button type="button" class="btn btn-outline-success btn-sm bCompletarProdRece" attrOrden="'.$row1[$x]['ID_Detalle_Orden'].'" attrRece="'.$row1[$x]['ID_Detalle_Recepcion'].'"><i class="fas fa-check"></i></button>';
									}

									$bEliminar = '';
									if (($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_recepcion'][5] == '1') && $row1[$x]['Copiada'] == '1') {
										$bEliminar = '<button type="button" class="btn btn-outline-danger btn-sm bEliminarProdRece" attrOrden="'.$row1[$x]['ID_Detalle_Orden'].'" attrRece="'.$row1[$x]['ID_Detalle_Recepcion'].'"><i class="fas fa-trash"></i></button>';
									}

									$detalles .= '<tr attrCopiada="'.$row1[$x]['Copiada'].'" attrOrden="'.$row1[$x]['ID_Detalle_Orden'].'" attrRece="'.$row1[$x]['ID_Detalle_Recepcion'].'">
										<td>'.$row1[$x]['Codigo'].'</td>
										<td>'.$row1[$x]['Producto'].' ('.$row1[$x]['Presentacion'].')</td>
										<td><input type="text" class="form-control form-control-sm inLoteRece" value="'.$row1[$x]['Lote'].'"></td>
										<td><input type="date" class="form-control form-control-sm inCadRece" value="'.$row1[$x]['Caducidad'].'"></td>
										<td><span class="cantidad">'.$row1[$x]['CantidadOrden'].'</span></td>
										<td><span class="cantidad">'.$row1[$x]['Cantidad'].'</span></td>
										<td>'.$estatus.'</td>
										<td><input type="text" class="form-control form-control-sm inObRece" value="'.$row1[$x]['Observaciones'].'"></td>
										<td>'.$bCopiar.' '.$bVerificar.' '.$bEliminar.'</td>
									</tr>';
								}
							}
						}

						$arreglo = array(
							'ID' => $row[$i]['ID_Orden_Compra'],
							'ID_Recepcion' => $row[$i]['ID_Recepcion'],
							'Fecha_Registro' => $row[$i]['Fecha_Registro'],
						    'Orden' => $row[$i]['Orden'],
						    'Fecha_Programada' => $row[$i]['Fecha_Programada'],
						    'Fecha_Recepcion' => $row[$i]['Fecha_Recepcion'],
						    'Estatus' => $row[$i]['Estatus'],
						    'Estatus_Orden' => $row[$i]['Estatus_Orden'],
						    'FK_Proveedor' => $row[$i]['FK_Proveedor'],
						    'Detalles' => $detalles
						);
					}
				}
			}

			echo json_encode($arreglo);
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id = $omodelo->link->real_escape_string($id);
		$fechaProg = str_replace('T', ' ', $omodelo->link->real_escape_string($fechaProg));
		$fechaRece = str_replace('T', ' ', $omodelo->link->real_escape_string($fechaRece));
		$estatus = $omodelo->link->real_escape_string($estatus);
		$orden = $omodelo->link->real_escape_string($orden);

		if(isset($id) && $id != ''){	
			$query = "UPDATE recepcion SET Fecha_Programada = '$fechaProg', Fecha_Recepcion = '$fechaRece', Estatus = '$estatus' WHERE FK_Orden = '$orden' AND ID_Recepcion = '$id'";
		}else{
			$query = "INSERT INTO recepcion SET FK_Orden = '$orden', Fecha_Programada = '$fechaProg', Fecha_Recepcion = '$fechaRece', Estatus = '$estatus'";
		}

		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if(!isset($id) || $id == ''){	
				$id = $omodelo->link->insert_id;
			}

			$status = 0;
			$query1 = "DELETE FROM detalles_recepcion WHERE FK_Recepcion = '$id'";
			$error1 = $omodelo->_insertar($query1);

			if ($error1 == "si") {
				echo "Error 2: ".mysqli_error($omodelo->link);
				$status = 1;
			}else{
				$productos = json_decode($productos, true);
				foreach ($productos as $producto) {
					$ID_Detalle_Recepcion = $omodelo->link->real_escape_string($producto['ID_Detalle_Recepcion']);
					$FK_Detalle_Orden = $omodelo->link->real_escape_string($producto['FK_Detalle_Orden']);
					$Lote = $omodelo->link->real_escape_string($producto['Lote']);
					$Caducidad = $omodelo->link->real_escape_string($producto['Caducidad']);
					$Cantidad_Orden = $omodelo->link->real_escape_string($producto['Cantidad_Orden']);
					$Cantidad = $omodelo->link->real_escape_string($producto['Cantidad']);
					$Estatus = $omodelo->link->real_escape_string($producto['Estatus']);
					$Observaciones = $omodelo->link->real_escape_string($producto['Observaciones']);
					$Copiada = $omodelo->link->real_escape_string($producto['Copiada']);

					$query2 = "INSERT INTO detalles_recepcion SET Lote = '$Lote', Caducidad = '$Caducidad',	Cantidad_Orden = '$Cantidad_Orden', Cantidad = '$Cantidad', Estatus = '$Estatus', Observaciones = '$Observaciones', Copiada = '$Copiada', FK_Detalle_Orden = '$FK_Detalle_Orden', FK_Recepcion = '$id'";
					$error2 = $omodelo->_insertar($query2);
				
					if ($error2 == "si") {
						echo "Error 3: ".mysqli_error($omodelo->link);
						$status = 1;
						break;
					}
				}
			}

			if($status == 0){
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
	}
}
?>