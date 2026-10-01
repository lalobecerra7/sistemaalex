<?php
class ventasxproveedorNuevo {

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		
		if($tipo == "ObtenerColumnas") {
			$mesesNombres = [
				'01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL',
				'05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO',
				'09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'
			];

			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);
			$idProveedor = $omodelo->link->real_escape_string($Proveedor);
			
			// --- CÁLCULO DE FECHAS Y DÍAS PARA EL PROMEDIO ---
			$f1 = new DateTime($fechaInicio);
			$f2 = new DateTime($fechaFin);

			// 1. Días reales transcurridos (se suma 1 para incluir el día inicial)
			$intervalo = $f1->diff($f2);
			$diasSeleccionados = $intervalo->days + 1; 

			// 2. Días totales del mes de la fecha final (ej: 30 en abril)
			$diasDelMesEnCurso = (int)$f2->format('t'); 

			// 3. Diferencia en meses para las columnas del encabezado
			$y1 = (int)$f1->format('Y');
			$m1 = (int)$f1->format('m');
			$y2 = (int)$f2->format('Y');
			$m2 = (int)$f2->format('m');
			$DiferenciaMeses = (($y2 - $y1) * 12) + ($m2 - $m1);
			// -------------------------------------------------

			$qSucursales = "";
			$cadenaSucursales = "";
			$sucursales = json_decode($sucursales, true);
			foreach ($sucursales as $sucursal) {
				$cadenaSucursales .= $sucursal["ID"].",";
			}
			$string = rtrim($cadenaSucursales, ",");
			if ($string != "") {
				$qSucursales = "AND ventas.FK_Sucursal IN(".$string.")";
			}

			$query = "SELECT FK_Producto, FK_Presentacion, SUM(detalles_ventas.Cantidad) AS CantidadTotal, 
					Descripcion AS NombreProducto, 
					IF(FK_Presentacion = 0, 'Sin presentación', (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion)) AS NombrePresentacion,
					DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m') AS MesVenta,
					IF(FK_Presentacion = 0, (SELECT Codigo FROM productos WHERE ID_Producto = FK_Producto), (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion)) AS CodigoProducto 
					FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta 
					WHERE IFNULL((SELECT COUNT(*) FROM detalles_proveedores_productos WHERE detalles_proveedores_productos.FK_Producto = detalles_ventas.FK_Producto AND FK_Proveedor = '$idProveedor'), 0) > 0 
					AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $qSucursales 
					GROUP BY FK_Producto, FK_Presentacion, DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m') 
					ORDER BY FK_Producto, FK_Presentacion, ventas.Fecha_Registro ASC";
			
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			
			$productos = array();
			$mesesArreglo = [];
			$thead = '';

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$fecha_aux = clone $f1; // Usamos clon para no afectar la original

					$thead = '<tr><th>Codigo</th><th>Producto</th>';
					for ($i=0; $i <= $DiferenciaMeses; $i++) { 
						if ($i > 0) { $fecha_aux->modify('+1 month'); }
						$mesesArreglo[] = $fecha_aux->format('Y-m');
						$separa = explode('-', $fecha_aux->format('Y-m'));
						$thead .= '<th>'.$mesesNombres[$separa[1]].' '.$separa[0].'</th>';
					}
					$thead .= '<th>Promedio Mensual</th></tr>';

					$codigo = '';
					$meses = array();
					$contador = 0;
					for($i=0; $i<$numerofilas; $i++){
						if($codigo != $row[$i]["CodigoProducto"]){
							$codigo = $row[$i]["CodigoProducto"];
							$contador++;

							if($i > 0){
								$productos[$contador - 1]['Valores'] = $meses;
								$meses = array();
							}

							$productos[$contador] = array(
								'Codigo' => $row[$i]['CodigoProducto'], 
								'Nombre' => $row[$i]['NombreProducto'],  
								'Presentacion' => $row[$i]['NombrePresentacion']
							);
						}
						$meses[] = array('Mes' => $row[$i]['MesVenta'], 'Cantidad' => $row[$i]['CantidadTotal']);
					}
					$productos[$contador]['Valores'] = $meses;
				}
			}

			$tbody = '';
			foreach ($productos as $producto) {
				$sumaTotalRango = 0;
				$tbody .= '<tr>
					<td>'.$producto["Codigo"].'</td>
					<td>'.$producto["Nombre"].' <span style="font-size:12px;">('.$producto["Presentacion"].')</span></td>';

				for ($i=0; $i < sizeof($mesesArreglo); $i++) { 
					$encontro = false;
					foreach ($producto['Valores'] as $valor) {
						if ($mesesArreglo[$i] == $valor['Mes']) {
							$tbody .= "<td>".number_format($valor["Cantidad"], 2)."</td>";
							$sumaTotalRango += $valor["Cantidad"];
							$encontro = true;
							break;
						}
					}
					if(!$encontro){ $tbody .= '<td>0.00</td>'; }
				}

				// --- CÁLCULO DEL PROMEDIO PROYECTADO ---
				// Venta diaria * días totales del mes final
				$promedioProyectado = ($sumaTotalRango / $diasSeleccionados) * $diasDelMesEnCurso;

				$tbody .= '<td style="font-weight:bold; background-color: #f9f9f9;">'.number_format($promedioProyectado, 2).'</td>
				</tr>';
			}
			
			$arreglo = array('head' => $thead, 'body' => $tbody);
			echo json_encode($arreglo);
		} else if ($tipo == 'ConsultarSucursales') {
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
					$busqueda .= "CONCAT(ID_Sucursal, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Email, FK_Encargado, usuarios.Nombre,  usuarios.Primer_Apellido, usuarios.Segundo_Apellido, zonas.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Sucursal, '' AS Seleccionar, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Segundo_Telefono, Email, FK_Encargado, usuarios.Nombre AS NombreEncargado, usuarios.Primer_Apellido AS PrimerApellido, usuarios.Segundo_Apellido AS SegundoApellido, FK_Zona, zonas.Nombre AS NombreZona, Latitud, Longitud, (SELECT COUNT(*) FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda) AS Num FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$direccion = "";$telefono="";$email="";$gerente="";

						if ($row[$i]['NombreZona'] != "") {
							$direccion .= "Zona: <b>".$row[$i]['NombreZona'].'</b><br>';
						}

						if ($row[$i]['Calle'] != "") {
							$direccion .= "Calle: <b>".$row[$i]['Calle'].'</b><br>';
						}

						if ($row[$i]['No_Exterior'] != "") {
							$direccion .= "No. Exterior: <b>".$row[$i]['No_Exterior'].'</b><br>';
						}

						if ($row[$i]['No_Interior'] != "") {
							$direccion .= "No. Interior: <b>".$row[$i]['No_Interior'].'</b><br>';
						}

						if ($row[$i]['Colonia'] != "") {
							$direccion .= "Colonia: <b>".$row[$i]['Colonia'].'</b><br>';
						}

						if ($row[$i]['CP'] != "") {
							$direccion .= "Codigo postal: <b>".$row[$i]['CP'].'</b><br>';
						}

						if ($row[$i]['Ciudad'] != "") {
							$direccion .= "Ciudad: <b>".$row[$i]['Ciudad'].'</b><br>';
						}

						if ($row[$i]['Estado'] != "") {
							$direccion .= "Estado: <b>".$row[$i]['Estado'].'</b><br>';
						}

						if ($row[$i]['Pais'] != "") {
							$direccion .= "País: <b>".$row[$i]['Pais'].'</b><br>';
						}

						if ($row[$i]['Telefono'] != "") {
							$telefono .= "Primer teléfono: <b>".$row[$i]['Telefono'].'</b><br>';
						}

						if ($row[$i]['Segundo_Telefono'] != "") {
							$telefono .= "Segundo teléfono: <b>".$row[$i]['Segundo_Telefono'].'</b><br>';
						}

						if ($row[$i]['Latitud'] != '' && $row[$i]['Longitud'] != '') {
							$telefono .= 'Ubicación: <b>'.$row[$i]['Latitud'].', '.$row[$i]['Longitud'].'</b>';
						}
						
						if ($telefono == "") {
							$telefono = "No hay telefono registrado";
						}
						
						if ($row[$i]['Email'] != "") {
							$email = $row[$i]['Email'];
						}else{	
							$email = "No hay un correo registrado";
						}

						if ($row[$i]['FK_Encargado'] != "") {
							$gerente = $row[$i]['NombreEncargado'].' '.$row[$i]['PrimerApellido'].' '.$row[$i]['SegundoApellido'];
						}else{	
							$gerente = "No hay datos registrados";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Sucursal'],
							'Seleccionar' => '<input type="checkbox" class="CheckInputSucursalProveedores" nombre="'.$row[$i]['Nombre'].'" attrid="'.$row[$i]['ID_Sucursal'].'">',
							'Sucursal' => $row[$i]['Nombre'],
							'Direccion' => $direccion
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			
			echo json_encode($arreglo);
		}
	}
}
?>
