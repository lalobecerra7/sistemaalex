<?php
class cortesRuta {

	public function _consultar() 
	{
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
				$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), IFNULL((SELECT SUM(Total) FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= Fecha_Inicio AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= Fecha_Fin) AND FK_Ruta = cr.FK_Ruta), 0), DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y'), DATE_FORMAT(Fecha_Fin, '%d-%m-%Y'), IFNULL((SELECT CONCAT(Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) FROM choferes WHERE ID_Chofer = FK_Chofer), ''), IFNULL((SELECT Descripcion FROM vehiculos WHERE ID_Vehiculo = FK_Vehiculo), ''), IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal), ''), IFNULL((SELECT Nombre FROM rutas WHERE ID_Ruta = FK_Ruta), '')) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Corte, IFNULL((SELECT SUM(Cantidad) FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= Fecha_Inicio AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= Fecha_Fin) AND ventas.FK_Sucursal = cr.FK_Sucursal AND (FK_Ruta = cr.FK_Ruta OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = cr.FK_Ruta AND FK_Corte = ID_Corte) > 0) AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = ID_Corte) = 0), 0) AS Cantidad, IFNULL((SELECT SUM(Cantidad) FROM verificar_ruta INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= Fecha_Inicio AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= Fecha_Fin) AND ventas.FK_Sucursal = cr.FK_Sucursal AND (FK_Ruta = cr.FK_Ruta OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = cr.FK_Ruta AND FK_Corte = ID_Corte) > 0) AND FK_Corte = ID_Corte AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = ID_Corte) = 0), 0) AS Verificados, IFNULL((SELECT SUM(Total) FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= Fecha_Inicio AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= Fecha_Fin) AND ventas.FK_Sucursal = cr.FK_Sucursal AND (FK_Ruta = cr.FK_Ruta OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = cr.FK_Ruta AND FK_Corte = ID_Corte) > 0) AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = ID_Corte) = 0), 0) AS Total, IFNULL((SELECT Nombre FROM rutas WHERE ID_Ruta = FK_Ruta), '') AS Ruta, Fecha_Inicio, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y') AS FechaI, Fecha_Fin,  DATE_FORMAT(Fecha_Fin, '%d-%m-%Y') AS FechaF, IFNULL((SELECT CONCAT(Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) FROM choferes WHERE ID_Chofer = FK_Chofer), '') AS Chofer, IFNULL((SELECT Descripcion FROM vehiculos WHERE ID_Vehiculo = FK_Vehiculo), '') AS Vehiculo, IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal), '') AS Sucursal, Estatus, Envases_Recaudados, Imagen, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, (SELECT COUNT(*) FROM cortes_ruta $busqueda) AS Num FROM cortes_ruta AS cr $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$bModificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1') {
						if($row[$i]['Estatus'] == 'Pendiente'){
							$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-pencil"></i></button>';
						}else if($row[$i]['Estatus'] == 'Finalizado'){
							$bModificar = '<button type="button" class="btn btn-sm btn-info bModificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-eye"></i></button>';
						}
					}

					$bEliminar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
						$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-trash"></i></button>';
					}

					$bVerificar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][5] == '1') {
						$bVerificar = '<br><br><button type="button" class="btn btn-sm btn-outline-secondary bConcentradoCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Concentrado">Concentrado <i class="fas fa-check"></i></button>';
					}

					/*$bImagen = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
						$bImagen = '<button type="button" class="btn btn-sm btn-secondary bImagenCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Imagen"><i class="fas fa-image"></i></button>';
					}*/

					$verificado = '<span class="badge rounded-pill bg-danger">No</span>'.$bVerificar;
					if($row[$i]['Verificados'] > 0){
						$verificado = '<span class="badge rounded-pill bg-warning">No</span>'.$bVerificar;
					}

					if($row[$i]['Verificados'] >= $row[$i]['Cantidad']){
						$verificado = '<span class="badge rounded-pill bg-success">Si</span>'.$bVerificar;
					}

					$estatus = '';
					if($row[$i]['Estatus'] == 'Pendiente'){
						$estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
					}else if($row[$i]['Estatus'] == 'Finalizado'){
						$estatus = '<span class="badge rounded-pill bg-success">Finalizado</span>';
					}

					$verImagen = '';
					/*if($row[$i]['Imagen'] && $row[$i]['Imagen'] != ''){
						$verImagen = '<a href="vistas/assets/archivos/cortesRuta/'.$row[$i]['Imagen'].'" data-fancybox><div style="background-image: url('."'".'vistas/assets/archivos/cortesRuta/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
						</div></a>';
					}*/

					$bCunetas = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][5] == '1') {
						$bCunetas = '<button type="button" class="btn btn-sm btn-outline-secondary bVerificarCubetas" attrID="'.$row[$i]['ID_Corte'].'" title="Cubetas">Cubetas <i class="fas fa-check"></i></button>';
					}
						
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Corte'],
						'Fecha' => $row[$i]['Fecha_Registro'],
						'Ruta' => $row[$i]['Ruta'],
						'Fecha_Inicio' => $row[$i]['FechaI'],
						'Fecha_Fin' => $row[$i]['FechaF'],
						'Total' => '<span class="dinero">'.$row[$i]['Total'].'</span>',
						'Concentrado' => $verificado,
						'Cubetas' => $bCunetas,
						'Estatus' => $estatus.'</br>'.$row[$i]['Chofer'].'</br>'.$row[$i]['Vehiculo'].'</br>Sucursal: '.$row[$i]['Sucursal'].'</br>'.$verImagen,
						'Acciones' => $bModificar.' '.$bEliminar.' <button type="button" class="btn btn-sm btn-success bGenerarPDFCorteRuta" title="pdf" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-file-pdf"></i></button>'
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
		
		if($tipo == 'ventasRutas'){
			$corte = $omodelo->link->real_escape_string($corte);
			$ruta = $omodelo->link->real_escape_string($ruta);
			$fechaIn = $omodelo->link->real_escape_string($fechaIn);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);
			$sucursal = $omodelo->link->real_escape_string($sucursal);

			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'AND ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y %r'), Total, Nombre, Primer_Apellido, Segundo_Apellido, IF(FK_Direccion = 0, CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais), (SELECT CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais) FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion))) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Venta, IFNULL((SELECT SUM(Cantidad) FROM detalles_ventas WHERE FK_Venta = ID_Venta), 0) AS Cantidad, IFNULL((SELECT SUM(Cantidad) FROM verificar_ruta WHERE FK_Venta = ID_Venta AND FK_Corte = '$corte'), 0) AS Verificados, DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, ventas.Fecha_Registro AS Fecha, Total, FK_Cliente, CONCAT(Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) AS Cliente, IF((SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0, (SELECT Orden FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte' LIMIT 1), Orden_Ruta) AS Orden, IF(FK_Direccion = 0, CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais), (SELECT CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais) FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion)) AS Domicilio, IFNULL((SELECT SUM(Total) FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaIn' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND (FK_Ruta = '$ruta' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0) AND ventas.FK_Sucursal = '$sucursal' AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$corte') = 0 $busqueda), 0) AS SumTotal, (SELECT COUNT(*) FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaIn' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND (FK_Ruta = '$ruta' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0) AND ventas.FK_Sucursal = '$sucursal' AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$corte') = 0 $busqueda) AS Num FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaIn' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND (FK_Ruta = '$ruta' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0) AND ventas.FK_Sucursal = '$sucursal' AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$corte') = 0 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$bVerificar = '';
						if (@$omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1') {
							$bVerificar = '<button type="button" class="btn btn-sm btn-outline-info bVerificarRuta" attrID="'.$row[$i]['ID_Venta'].'" title="Verificar"><i class="fa-solid fa-check"></i></button>';
						}

						$estatus = "<span class='badge rounded-pill bg-danger'>Pendiente</span>";
						if($row[$i]['Verificados'] > 0) {
							$estatus = "<span class='badge rounded-pill bg-warning'>Pendiente</span>";
						}

						if($row[$i]['Verificados'] >= $row[$i]['Cantidad']){
							$estatus = "<span class='badge rounded-pill bg-success'>Listo</span>";
						}

						$arreglo['data'][] = array(
							'ID' => $row[$i]['ID_Venta'],
							'Orden' => $row[$i]['Orden'],
				            'Folio' => $row[$i]['ID_Venta'],
				            'Cliente' => $row[$i]['Cliente'],
				            'Domicilio' => $row[$i]['Domicilio'],         
				            'Total' => '<span class="dinero">'.$row[$i]['Total'].'</span>',
				            'Fecha' => $row[$i]['Fecha_Registro'],
				            'Estatus' => $estatus,
				            'Acciones' => $bVerificar
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num'], 'Total' => '<span class="dinero" id="totalCorteR">'.$row[0]['SumTotal'].'</span>');	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'obtenerCorteGuardado'){
			$id = $omodelo->link->real_escape_string($id);
			$arreglo = array();

			$query = "SELECT FK_Ruta, Fecha_Inicio, Fecha_Fin, FK_Chofer, FK_Vehiculo, Estatus, FK_Sucursal, Monto, Imagen FROM cortes_ruta WHERE ID_Corte = $id";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row === "si"){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if ($numerofilas > 0) {
					$gastos = '';
					$query1 = "SELECT ID_Gasto, Descripcion, Monto, Fecha_Registro FROM gastos_ruta WHERE FK_Corte = '$id'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas1 = $omodelo->numerofilas;

					if($row1 === "si"){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if ($numerofilas1 > 0) {
							for ($i=0; $i < $numerofilas1; $i++) { 
								$gastos .= '<tr>
									<td>'.$row1[$i]['Descripcion'].'</td>
									<td><span class="dinero">'.$row1[$i]['Monto'].'</span></td>
									<td><button type="button" class="btn btn-sm btn-danger bEliminarGastoRuta" attrID="'.$row1[$i]['ID_Gasto'].'"><i class="fas fa-trash"></i></button></td>
								</tr>';
							}
						}
					}

					$extras = '';
					$query1 = "SELECT ID_Extra, FK_Cliente, Nombre, Primer_Apellido, Segundo_Apellido, Orden FROM clientes_extras_ruta INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE FK_Corte = '$id'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas1 = $omodelo->numerofilas;

					if($row1 === "si"){
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if ($numerofilas1 > 0) {
							for ($i=0; $i < $numerofilas1; $i++) { 
								$extras .= '<tr attrID="'.$row1[$i]['FK_Cliente'].'">
									<td>'.$row1[$i]['Nombre'].' '.$row1[$i]['Primer_Apellido'].' '.$row1[$i]['Segundo_Apellido'].'</td>
									<td>'.$row1[$i]['Orden'].'</td>
									<td><button type="button" class="btn btn-sm btn-danger bBorrarExtra" attrID="'.$row1[$i]['ID_Extra'].'"><i class="fas fa-trash"></i></button></td>
								</tr>';
							}
						}
					}

					$quitados = '';
					$query1 = "SELECT ID_Quitar, FK_Cliente, Nombre, Primer_Apellido, Segundo_Apellido FROM quitar_clientes_ruta INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE FK_Corte = '$id'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas1 = $omodelo->numerofilas;

					if($row1 === "si"){
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if ($numerofilas1 > 0) {
							for ($i=0; $i < $numerofilas1; $i++) { 
								$quitados .= '<tr attrID="'.$row1[$i]['FK_Cliente'].'">
									<td>'.$row1[$i]['Nombre'].' '.$row1[$i]['Primer_Apellido'].' '.$row1[$i]['Segundo_Apellido'].'</td>
									<td><button type="button" class="btn btn-sm btn-danger bEliminarClienteQuitado" attrID="'.$row1[$i]['ID_Quitar'].'"><i class="fas fa-trash"></i></button></td>
								</tr>';
							}
						}
					}

					$arreglo = array(
						'FK_Ruta' => $row[0]['FK_Ruta'], 
						'Fecha_Inicio' => $row[0]['Fecha_Inicio'], 
						'Fecha_Fin' => $row[0]['Fecha_Fin'], 
						'FK_Chofer' => $row[0]['FK_Chofer'] == '0' ? '' : $row[0]['FK_Chofer'], 
						'FK_Vehiculo' => $row[0]['FK_Vehiculo'] == '0' ? '' : $row[0]['FK_Vehiculo'], 
						'Estatus' => $row[0]['Estatus'], 
						'FK_Sucursal' => $row[0]['FK_Sucursal'] == '0' ? '' : $row[0]['FK_Sucursal'], 
						'Imagen' => $row[0]['Imagen'],
						'Monto' => $row[0]['Monto'],
						'Gastos' => $gastos,
						'Extras' => $extras,
						'Quitados' => $quitados
					);
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'concentradoCorte'){
			$id =  $omodelo->link->real_escape_string($id);

			$query = "SELECT FK_Producto, FK_Presentacion, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Codigo FROM presentaciones WHERE FK_Presentacion = ID_Presentacion) ELSE (SELECT Codigo FROM productos WHERE FK_Producto = ID_Producto) END) AS Codigo, Descripcion AS Producto, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Nombre FROM presentaciones WHERE FK_Presentacion = ID_Presentacion) ELSE NULL END) AS PresentacionInfo, SUM(Cantidad) AS Cantidadtol, IFNULL((SELECT SUM(Cantidad) FROM verificar_ruta WHERE FK_Corte = '$id' AND FK_Producto = detalles_ventas.FK_Producto AND FK_Presentacion = detalles_ventas.FK_Presentacion), 0) AS Verificacion, IFNULL((SELECT Nombre FROM categorias WHERE ID_Categoria = (SELECT FK_Categoria FROM productos WHERE FK_Producto = ID_Producto)), '') AS Categoria FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND ventas.FK_Sucursal = (SELECT FK_Sucursal FROM cortes_ruta WHERE ID_Corte = '$id') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= (SELECT Fecha_Inicio FROM cortes_ruta WHERE ID_Corte = '$id') AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= (SELECT Fecha_Fin FROM cortes_ruta WHERE ID_Corte = '$id')) AND (FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$id') OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$id') AND FK_Corte = '$id') > 0) AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$id') = 0 GROUP BY Codigo ORDER BY CASE 
				WHEN UPPER(Categoria) LIKE '%BASE%' THEN 1
			  	WHEN UPPER(Categoria) LIKE '%HELADOS%' THEN 2
			  	WHEN UPPER(Categoria) LIKE '%REFRIGERADOS%' THEN 3
			  	WHEN UPPER(Categoria) LIKE '%CONGELADOS%' THEN 4
			  	WHEN UPPER(Producto) LIKE '%BASE%' THEN 5
			  	WHEN UPPER(Producto) LIKE '%CUBETA%' THEN 6
			  	WHEN UPPER(Producto) LIKE '%GALONES%' THEN 7
			  	ELSE 8
		  		END , (SELECT Importe FROM productos WHERE ID_Producto = detalles_ventas.FK_Producto)";  
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$color = '';

						$estatus = "<span class='badge rounded-pill bg-danger'>Pendiente</span>";
						if ($row[$i]['Verificacion'] > 0){
							$estatus = "<span class='badge rounded-pill bg-warning'>Pendiente</span>";
						}

						if ($row[$i]['Verificacion'] >= $row[$i]['Cantidadtol']) {
							$estatus = "<span class='badge rounded-pill bg-success'>Listo</span>";
							$color = 'class="table-success"';
						}

						echo "<tr ".$color." producto='".$row[$i]['FK_Producto']."' presentacion='".$row[$i]['FK_Presentacion']."'>
							<td class='codigoProducto'>".$row[$i]['Codigo']."</td>
							<th><b style='font-size: 17px;'>".$row[$i]['Producto']."</b><br>".($row[$i]['PresentacionInfo'] != "NULL" ? $row[$i]['PresentacionInfo'] : '')."<br>(".($row[$i]['Categoria'] != "NULL" ? $row[$i]['Categoria'] : '').")</th>
							<td>".$row[$i]['Cantidadtol']."</td>
							<td>".$row[$i]['Verificacion']."</td>
							<td>".$estatus."</td>
						</tr>";
					}
				}
			}	
		}else if($tipo == 'verCubetas'){
			$id = $omodelo->link->real_escape_string($id);
			$orden = $omodelo->link->real_escape_string($orden);

			$query = "SELECT ID_Venta, DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, ventas.Fecha_Registro AS Fecha, Total, FK_Cliente, CONCAT(Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) AS Cliente, IF((SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$id') AND FK_Corte = '$id') > 0, (SELECT Orden FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$id') AND FK_Corte = '$id' LIMIT 1), Orden_Ruta) AS Orden, IF(FK_Direccion = 0, CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais), (SELECT CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais) FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion)) AS Domicilio FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND ventas.FK_Sucursal = (SELECT FK_Sucursal FROM cortes_ruta WHERE ID_Corte = '$id') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= (SELECT Fecha_Inicio FROM cortes_ruta WHERE ID_Corte = '$id') AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= (SELECT Fecha_Fin FROM cortes_ruta WHERE ID_Corte = '$id')) AND (FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$id') OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$id') AND FK_Corte = '$id') > 0) AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$id') = 0 ORDER BY Orden $orden";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$card = "<div class='row border border-secondary rounded px-2 py-3 mb-3'>
		                    <div class='col'>
		                        <div class='ventas-header row justify-content-between align-items-center mb-2'>
		                            <div class='col-8 px-2'>
		                                <div class='row'>
		                                	<p class='py-0 my-0 col-12 pl-2'><b>Folio:</b> ".$row[$i]['ID_Venta']."</p>
		                                	<p class='py-0 my-0 col-12 pl-2'><b>Orden:</b> ".$row[$i]['Orden']."</p>
		                                    <p class='py-0 my-0 col-12 pl-2'><b>Cliente:</b> ".$row[$i]['Cliente']."</p>
		                                    <p class='py-0 my-0 col-12 pl-2'><b>Dirección:</b> ".$row[$i]['Domicilio']."</p>
		                                </div>
		                           	</div>
		                        </div>
		                        <div class='ventas-detalle row px-3'>
		                        	<div class='col-12 table-responsive'>
		                            	<table class='table tablaVeri text-center' width='100%' style='font-size: 18px'>
			                                <thead>
			                                    <tr>
			                                        <th scope='col'>Codigo</th>
			                                        <th scope='col'>Producto</th>
			                                        <th scope='col'>Cantidad</th>
			                                        <th scope='col'>Verificados</th>
			                                        <th scope='col'>Estado</th>
			                                    </tr>
			                                </thead>
		                                	<tbody>";


		                    $query1 = "SELECT ID_Detalle_Venta, FK_Producto, FK_Presentacion, Descripcion, Precio, Cantidad, Descuento, Total, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion) ELSE '' END) AS PresentacionInfo, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion) ELSE (SELECT Codigo FROM productos WHERE ID_Producto = FK_Producto) END) AS Codigo, IFNULL((SELECT Cantidad FROM verificar_ruta WHERE FK_Corte = '$id' AND FK_Venta = '".$row[$i]['ID_Venta']."' AND FK_Producto = detalles_ventas.FK_Producto AND FK_Presentacion = detalles_ventas.FK_Presentacion), 0) AS Verificacion, IFNULL((SELECT Nombre FROM categorias WHERE ID_Categoria = (SELECT FK_Categoria FROM productos WHERE ID_Producto = FK_Producto)), '') AS Categoria FROM detalles_ventas WHERE FK_Venta ='".$row[$i]['ID_Venta']."' AND Descripcion LIKE '%CUBETA%' AND Descripcion NOT LIKE '%ALPEZZI%' AND Descripcion NOT LIKE '%CHOCOAMOR%' AND Descripcion NOT LIKE '%PICOLY%'";   
		                    $row1 = $omodelo->_consultar($query1);
							$numerofilas1 = $omodelo->numerofilas;

							if($row1 == 'si'){
								echo "Error 2: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas1 > 0){
									for($x=0; $x<$numerofilas1; $x++){
										$color = '';

										$estatus = "<span class='badge rounded-pill bg-danger'>Pendiente</span>"; 
										if($row1[$x]['Verificacion'] > 0){
											$estatus = "<span class='badge rounded-pill bg-warning'>Pendiente</span>";
										}

										if($row1[$x]['Verificacion'] >= $row1[$x]['Cantidad']){
											$estatus = "<span class='badge rounded-pill bg-success'>Listo</span>";
											$color = 'class="table-success"';
										}

										$card .= "<tr ".$color." class='comproCubetas' corte='".$id."' venta='".$row[$i]['ID_Venta']."' producto='".$row1[$x]['FK_Producto']."' presentacion='".$row1[$x]['FK_Presentacion']."'>
		                                    <th>".$row1[$x]['Codigo']."</th>
		                                    <th>".$row1[$x]['Descripcion']."<br><span style='font-size: 9px;'>".$row1[$x]['PresentacionInfo']."</span></th>
		                                    <th>".$row1[$x]['Cantidad']."</th>
		                                    <th>".$row1[$x]['Verificacion']."</th>
		                                    <th>".$estatus."</th>
		                                  </tr>";
									}
								}else{
									$card = ''; 
								}
							}  

						if($card != ''){
			               	$card .= "</tbody>
			                        		</table>
			                    		</div>
			                    	</div>
			                	</div>
			            	</div>";
		               	}

		               	echo $card;
					}
				}
			}
		}else if($tipo == 'verificarProd'){
			$verificados = json_decode($verificados, true);

			foreach ($verificados as $ve) {
				$ve['corte'] = $omodelo->link->real_escape_string($ve['corte']);
				$ve['venta'] = $omodelo->link->real_escape_string($ve['venta']);
				$ve['producto'] = $omodelo->link->real_escape_string($ve['producto']);
				$ve['presentacion'] = $omodelo->link->real_escape_string($ve['presentacion']);
				$ve['cantidad'] = $omodelo->link->real_escape_string($ve['cantidad']);
				
				$query = "SELECT ID_Verificar FROM verificar_ruta WHERE FK_Corte = '$ve[corte]' AND FK_Venta = '$ve[venta]' AND FK_Producto = '$ve[producto]' AND FK_Presentacion = '$ve[presentacion]'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error 1: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						$query1 = "UPDATE verificar_ruta SET Cantidad = '$ve[cantidad]' WHERE ID_Verificar = '".$row[0]['ID_Verificar']."'";
					}else{
						$query1 = "INSERT INTO verificar_ruta SET FK_Corte = '$ve[corte]', FK_Venta = '$ve[venta]', FK_Producto = '$ve[producto]', FK_Presentacion = '$ve[presentacion]', Cantidad = '$ve[cantidad]'";
					}
					$row = $omodelo->_insertar($query1);

					if ($row == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						$omodelo->movimiento($query1, $_SESSION['user_admin']['ID_Usuario']);
					}
				}
			}

			echo "Correcto";
		}else if($tipo == 'verProductos'){
			$id = $omodelo->link->real_escape_string($id);
			$corte = $omodelo->link->real_escape_string($corte);

			$query = "SELECT ID_Venta, CONCAT(Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) AS Cliente, Orden_Ruta AS Orden, IF(FK_Direccion = 0, CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais), (SELECT CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais) FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion)) AS Domicilio FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$query1 = "SELECT ID_Detalle_Venta, FK_Producto, FK_Presentacion, Descripcion, Precio, Cantidad, Descuento, Total, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion) ELSE '' END) AS PresentacionInfo, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion) ELSE (SELECT Codigo FROM productos WHERE ID_Producto = FK_Producto) END) AS Codigo, IFNULL((SELECT Cantidad FROM verificar_ruta WHERE FK_Corte = '$corte' AND FK_Venta = '$id' AND FK_Producto = detalles_ventas.FK_Producto AND FK_Presentacion = detalles_ventas.FK_Presentacion), 0) AS Verificacion, IFNULL((SELECT Nombre FROM categorias WHERE ID_Categoria = (SELECT FK_Categoria FROM productos WHERE ID_Producto = FK_Producto)), '') AS Categoria FROM detalles_ventas WHERE FK_Venta ='".$id."' ORDER BY CASE 
							WHEN UPPER(Categoria) LIKE '%BASE%' THEN 1
						  	WHEN UPPER(Categoria) LIKE '%HELADOS%' THEN 2
						  	WHEN UPPER(Categoria) LIKE '%REFRIGERADOS%' THEN 3
						  	WHEN UPPER(Categoria) LIKE '%CONGELADOS%' THEN 4
						  	WHEN UPPER(Descripcion) LIKE '%BASE%' THEN 5
						  	WHEN UPPER(Descripcion) LIKE '%CUBETA%' THEN 6
						  	WHEN UPPER(Descripcion) LIKE '%GALONES%' THEN 7
						  	ELSE 8
  							END , (SELECT Importe FROM productos WHERE ID_Producto = detalles_ventas.FK_Producto)";   
		                $row1 = $omodelo->_consultar($query1);
						$numerofilas1 = $omodelo->numerofilas;

						if($row1 == 'si'){
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas1 > 0){
								for($x=0; $x<$numerofilas1; $x++){
									$color = '';

									$estatus = "<span class='badge rounded-pill bg-danger'>Pendiente</span>"; 
									if($row1[$x]['Verificacion'] > 0){
										$estatus = "<span class='badge rounded-pill bg-warning'>Pendiente</span>";
									}

									if($row1[$x]['Verificacion'] >= $row1[$x]['Cantidad']){
										$estatus = "<span class='badge rounded-pill bg-success'>Listo</span>";
										$color = 'class="table-success"';
									}

									echo "<tr ".$color." class='comproProd' corte='".$corte."' venta='".$row[$i]['ID_Venta']."' producto='".$row1[$x]['FK_Producto']."' presentacion='".$row1[$x]['FK_Presentacion']."'>
		                                <th>".$row1[$x]['Codigo']."</th>
		                                <th>".$row1[$x]['Descripcion']."<br><span style='font-size: 9px;'>".$row1[$x]['PresentacionInfo']."</span></th>
		                                <th>".$row1[$x]['Cantidad']."</th>
		                                <th>".$row1[$x]['Verificacion']."</th>
		                                <th>".$estatus."</th>
		                            </tr>";
								}
							}else{
								echo '<tr>
									<td colspan="5" class="text-center">Sin productos</td>
								</tr>';
							}
						} 
					}
				}
			}
		}else if($tipo == 'totales'){
			$corte = $omodelo->link->real_escape_string($corte);
			$arreglo = array();

			$query = "SELECT IFNULL(SUM(Total), 0) AS Total FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND ventas.FK_Sucursal = (SELECT FK_Sucursal FROM cortes_ruta WHERE ID_Corte = '$corte') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= (SELECT Fecha_Inicio FROM cortes_ruta WHERE ID_Corte = '$corte') AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= (SELECT Fecha_Fin FROM cortes_ruta WHERE ID_Corte = '$corte')) AND (FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$corte') OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = (SELECT FK_Ruta FROM cortes_ruta WHERE ID_Corte = '$corte') AND FK_Corte = '$corte')) AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$corte') = 0";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo['Total'] = $row[0]['Total'];
				}
			}

			$query = "SELECT IFNULL(SUM(Monto), 0) AS Monto FROM gastos_ruta WHERE FK_Corte = '$corte'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo['Gastos'] = $row[0]['Monto'];
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'insertar_gastos'){
			$corte = $omodelo->link->real_escape_string($corte);
			$descripcion = $omodelo->link->real_escape_string($descripcion);
			$monto = $omodelo->link->real_escape_string($monto);

			$query = "INSERT INTO gastos_ruta SET FK_Corte = '$corte', Descripcion = '$descripcion', Monto = '$monto', Fecha_Registro = NOW()";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				$id = $omodelo->link->insert_id;

				echo "Correcto~".$id;
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}
		}else if($tipo == 'eliminarGasto'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "DELETE FROM gastos_ruta WHERE ID_Gasto = '$id'";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}
		}else if($tipo == 'finalizar'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "UPDATE cortes_ruta SET Estatus = 'Finalizado' WHERE ID_Corte = '$id'";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}
		}else if($tipo == 'clientesExtra'){
			$ruta = $omodelo->link->real_escape_string($ruta);
			$tipoCliente = $omodelo->link->real_escape_string($tipoCliente);

			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'AND ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(LPAD(ID_Cliente, 4, '0'), clientes.Nombre, Primer_Apellido, Segundo_Apellido, Foto, clientes.Telefono, Celular, Correo, clientes.RFC, Facturar, Titular, Banco, No_Cuenta, Fecha_Registro, IFNULL((SELECT rutas.Nombre FROM rutas WHERE rutas.ID_Ruta = clientes.FK_Ruta), '')) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$filtro = "AND FK_Ruta != '$ruta'";
			if($tipoCliente == '1'){
				$filtro = "AND FK_Ruta = '$ruta'";
			}

			$query = "SELECT ID_Cliente, clientes.Nombre, IFNULL((SELECT rutas.Nombre FROM rutas WHERE rutas.ID_Ruta = clientes.FK_Ruta), '') AS Ruta, clientes.Orden_Ruta AS Orden_Ruta, Primer_Apellido, Segundo_Apellido, (SELECT COUNT(*) FROM clientes WHERE ID_Cliente <> 1 AND FK_Ruta != '$ruta' $filtro $busqueda) AS Num FROM clientes WHERE ID_Cliente <> 1 $filtro $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Cliente'],
							'Nombre' => $row[$i]['Nombre'].' '.$row[$i]['Primer_Apellido'].' '.$row[$i]['Segundo_Apellido'],
							'Ruta' => $row[$i]['Ruta'],
							'Orden' => $row[$i]['Orden_Ruta']
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'agregarExtra'){
			$ruta = $omodelo->link->real_escape_string($ruta);
			$cliente = $omodelo->link->real_escape_string($cliente);
			$orden = $omodelo->link->real_escape_string($orden);
			$corte = $omodelo->link->real_escape_string($corte);

			$query = "INSERT INTO clientes_extras_ruta SET FK_Corte = '$corte', FK_Ruta = '$ruta', FK_Cliente = '$cliente', Orden = '$orden'";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				$id = $omodelo->link->insert_id;
				echo 'Correcto~'.$id;

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}
		}else if($tipo == 'eliminarExtra'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "DELETE FROM clientes_extras_ruta WHERE ID_Extra = '$id'";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}
		}else if($tipo == 'agregarQuitado'){
			$cliente = $omodelo->link->real_escape_string($cliente);
			$corte = $omodelo->link->real_escape_string($corte);

			$query = "INSERT INTO quitar_clientes_ruta SET FK_Corte = '$corte', FK_Cliente = '$cliente'";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				$id = $omodelo->link->insert_id;
				echo 'Correcto~'.$id;

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}
		}else if($tipo == 'eliminarQuitado'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "DELETE FROM quitar_clientes_ruta WHERE ID_Quitar = '$id'";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}
		}
	}

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$ruta = $omodelo->link->real_escape_string($ruta);
		$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
		$fechaFin = $omodelo->link->real_escape_string($fechaFin);
		$chofer = $omodelo->link->real_escape_string($chofer);
		$vehiculo = $omodelo->link->real_escape_string($vehiculo);
		$sucursal = $omodelo->link->real_escape_string($sucursal);
		//$envasesRegresados = $omodelo->link->real_escape_string($envasesRegresados);

		$query = "INSERT INTO cortes_ruta SET FK_Ruta = '$ruta', Fecha_Inicio = '$fechaInicio', Fecha_Fin = '$fechaFin', Fecha_Registro = NOW(), FK_Chofer = '$chofer', FK_Vehiculo = '$vehiculo', FK_Sucursal = '$sucursal', Estatus = 'Pendiente'";
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
		$id = $omodelo->link->real_escape_string($id);
		$ruta = $omodelo->link->real_escape_string($ruta);
		$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
		$fechaFin = $omodelo->link->real_escape_string($fechaFin);
		$chofer = $omodelo->link->real_escape_string($chofer);
		$vehiculo = $omodelo->link->real_escape_string($vehiculo);
		$sucursal = $omodelo->link->real_escape_string($sucursal);
		$monto = $omodelo->link->real_escape_string($monto);
		//$envasesRegresados = $omodelo->link->real_escape_string($envasesRegresados);

		$query = "UPDATE cortes_ruta SET FK_Ruta = '$ruta', Fecha_Inicio = '$fechaInicio', Fecha_Fin = '$fechaFin', FK_Chofer = '$chofer', FK_Vehiculo = '$vehiculo', FK_Sucursal = '$sucursal', Monto = '$monto' WHERE ID_Corte = '$id'";
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

		$query = "DELETE FROM cortes_ruta WHERE ID_Corte = '$id'";
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
