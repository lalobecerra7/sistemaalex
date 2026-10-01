<?php
class ventasxdia {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$buscar =  $omodelo->link->real_escape_string($buscar);
		$limit =  $omodelo->link->real_escape_string($limit);
		$pagina =  $omodelo->link->real_escape_string($pagina);
		$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
		$orden =  $omodelo->link->real_escape_string($orden);
		$arreglo = array();
		$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
		$fechaFin = $omodelo->link->real_escape_string($fechaFin);
		$sucursal = $omodelo->link->real_escape_string($sucursal);

		
		$busqueda = '';
		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));
			$busqueda = 'AND ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d')) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		//CONSULTAR NUMERO DE FILAS
		$cantidadRegistros = 0;
		$queryRegistros = "SELECT COUNT(*) FROM ventas INNER JOIN sucursales ON ventas.FK_Sucursal = ID_Sucursal WHERE ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d')";
		$rowRegistros = $omodelo->_consultar($queryRegistros);
		$numerofilasRegistros = $omodelo->numerofilas;
		if($rowRegistros == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			$cantidadRegistros = $numerofilasRegistros;
		}

		$query = "SELECT DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') AS Fecha, sucursales.Nombre AS Sucursal, SUM(Descuento) AS Descuento, SUM(Total) AS Total, SUM(Total_Importes) AS Importes, (SELECT SUM(Precio * Cantidad) AS Subtotal FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND ventas.FK_Sucursal = '$sucursal' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') = Fecha) AS Subtotal,

		(SELECT SUM(detalles_devolucion.Total) AS TotalDevolucion FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND DATE_FORMAT(devoluciones.Fecha_Registro, '%Y-%m-%d') = Fecha) AS Devoluciones


		 FROM ventas INNER JOIN sucursales ON ventas.FK_Sucursal = ID_Sucursal WHERE (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $busqueda GROUP BY DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$SumaTotales = 0;
				$SumaImportes = 0;
				$SumaDescuentos = 0;
				$SumaSubtotal = 0;
				$SumaDevoluciones = 0;
				for($i=0; $i<$numerofilas; $i++){
					$SumaTotales += $row[$i]["Total"];
					$SumaImportes += $row[$i]["Importes"];
					$SumaDescuentos += $row[$i]["Descuento"];
					$SumaSubtotal += $row[$i]["Subtotal"];
					$SumaDevoluciones += $row[$i]["Devoluciones"];
					//CONSULTAR SUBTOTAL
					/*$query2 = "SELECT SUM(Precio * Cantidad) AS Subtotal FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') = '".$row[$i]["Fecha"]."';";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;
					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$SumaSubtotal = $row2[0]["Subtotal"];
						}
					}
					$SubtotalFinal += $SumaSubtotal;*/

					//CONSULTAR IMPUESTOS

					$arreglo["data"][$i] = array(
						'ID' => $row[$i]["Fecha"],
						'Fecha' => $row[$i]["Fecha"],
						'Sucursal' => $row[$i]["Sucursal"],
						'Importes' => "$".number_format($row[$i]["Importes"], 2),
						'Descuento' => "$".number_format($row[$i]["Descuento"], 2),
						'Subtotal' => "$".number_format($row[$i]["Subtotal"], 2),
						'Impuestos' => '0',
						'Total' => "$".number_format($row[$i]["Total"], 2),
						'Devoluciones' => "$".number_format($row[$i]["Devoluciones"], 2),
					);	
				}

				$arreglo['totales'] = array(
					'NumRows' => $cantidadRegistros, 
					'Fecha' => "",
					'Sucursal' => "",
					'Importes' => "$".number_format($SumaImportes, 2),
					'Descuento' => "$".number_format($SumaDescuentos, 2),
					'Subtotal' => "$".number_format($SumaSubtotal, 2),
					'Impuestos' => "",
					'Total' => "$".number_format($SumaTotales, 2),
					'Devoluciones' => "$".number_format($SumaDevoluciones, 2),
				);	
			}
			echo json_encode($arreglo);
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST); 
		$fecha = date('Y-m-d H:i:s'); 
		$IDVenta = $omodelo->link->real_escape_string($IDVenta);
		$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);
		$Regresar = $omodelo->link->real_escape_string($Regresar);
		$Motivo = $omodelo->link->real_escape_string($Motivo);
		$queryRegresar = ', Regreso_Inventario = "0"';
		if ($Regresar == "Si") {
			$queryRegresar = ', Regreso_Inventario = "1"';
			$query = "SELECT ID_Detalle_Venta, FK_Producto, FK_Presentacion, Cantidad FROM detalles_ventas WHERE FK_Venta = '$IDVenta'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$query1 = "UPDATE inventario SET Cantidad = (Cantidad + '".$row[$i]["Cantidad"]."') WHERE FK_Producto = '".$row[$i]["FK_Producto"]."' AND FK_Presentacion = '".$row[$i]["FK_Presentacion"]."' AND FK_Sucursal = '".$IDSucursal."'";
						$error1 = $omodelo->_insertar($query1);
						if ($error1 == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							$query2 = "UPDATE detalles_ventas SET Regreso_Inventario = '1' WHERE ID_Detalle_Venta = '".$row[$i]["ID_Detalle_Venta"]."'";
							$error2 = $omodelo->_insertar($query2);
							if ($error2 == "si") {
								echo "Error 3: ".mysqli_error($omodelo->link);
							}
						}
					}
				}
			}
		}
		$query = "UPDATE ventas SET Notas = '$Motivo', Estatus = 'Cancelada',  Fecha_Cancelacion = '$fecha' $queryRegresar WHERE ID_Venta = '$IDVenta'";
		$error = $omodelo->_insertar($query);
		if ($error == "si") {
			echo "Error 5: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}	
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$IDVenta = $omodelo->link->real_escape_string($IDVenta);

		$query = "DELETE FROM ventas WHERE ID_Venta = '$IDVenta'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}	
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		if($tipo == 'CalcularTotales'){
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);
			$sucursal = $omodelo->link->real_escape_string($sucursal);

			$query = "SELECT DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') AS Fecha, sucursales.Nombre AS Sucursal, SUM(Descuento) AS Descuento, SUM(Total) AS Total, SUM(Total_Importes) AS Importes, (SELECT SUM(Precio * Cantidad) AS Subtotal FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND ventas.FK_Sucursal = '$sucursal' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') = Fecha) AS Subtotal,

			(SELECT SUM(detalles_devolucion.Total) AS TotalDevolucion FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND DATE_FORMAT(devoluciones.Fecha_Registro, '%Y-%m-%d') = Fecha) AS Devoluciones


			 FROM ventas INNER JOIN sucursales ON ventas.FK_Sucursal = ID_Sucursal WHERE (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d')";

			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumaTotales = 0;
					$SumaImportes = 0;
					$SumaDescuentos = 0;
					$SumaSubtotal = 0;
					$SumaDevoluciones = 0;
					for($i=0; $i<$numerofilas; $i++){
						$SumaTotales += $row[$i]["Total"];
						$SumaImportes += $row[$i]["Importes"];
						$SumaDescuentos += $row[$i]["Descuento"];
						$SumaSubtotal += $row[$i]["Subtotal"];
						$SumaDevoluciones += $row[$i]["Devoluciones"];
					}
				}
			}

			echo $SumaTotales."~".$SumaImportes."~".$SumaDescuentos."~".$SumaSubtotal."~".$SumaDevoluciones;


		}else if($tipo == "impuestos"){
			$query = "SELECT ID_Impuesto, FK_Detalle_Venta, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$IDDetalle."'";
			$row = $omodelo->_consultar($query);
			$numerofilas2 = $omodelo->numerofilas;
			$tabla ="";
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas2 > 0){
					for ($i=0; $i < $numerofilas2; $i++) { 
						$tabla .= "
						<tr>
						<td >".$row[$i]["Impuesto_CFDI"]."</td>
						<td >".$row[$i]["Clave_CFDI"]."</td>
						<td >".$row[$i]["Tasa_Cuota_CFDI"]."</td>
						<td >".$row[$i]["Tipo_Factor_CFDI"]."</td>
						<td >".$row[$i]["Tipo_Impuesto_CFDI"]."</td>
						</tr>
						";
					}
				}else{
					$tabla = "
					<tr>
					<td style='vertical-align: middle;' colspan='6'>No hay impuestos registrados</td>
					</tr>
					";
				}
				echo $tabla;
			}
		}else if($tipo == "ConsultarProductosVentaDevolucion"){
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
					$busqueda .= "CONCAT(presentaciones.Nombre, presentaciones.Abreviatura, Descripcion, Precio, Cantidad, Descuento, Total) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			///////////////////////////////////////////////////////////////////////////////////

			$query = "SELECT ID_Detalle_Venta, detalles_ventas.FK_Producto AS IDProducto, FK_Presentacion, Nombre, Abreviatura, Nombre_Unidad, Abreviatura_Unidad, detalles_ventas.Descripcion AS Producto, detalles_ventas.Precio AS Precio, Cantidad, Descuento, Total, (SELECT COUNT(*) FROM detalles_ventas INNER JOIN productos ON detalles_ventas.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$idventa' $busqueda) AS Num FROM detalles_ventas INNER JOIN productos ON detalles_ventas.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$idventa' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$nombrePresentacion = "<br>Sin presentación";
						$nombreabreviatura = "";
						if ($row[$i]['FK_Presentacion'] != '0') {
							if ($row[$i]['Abreviatura'] != "") {
								$nombreabreviatura = "(".$row[$i]['Abreviatura'].")";
							}
							$nombrePresentacion = "<br>".$row[$i]['Nombre'].$nombreabreviatura;
						}else{
							if ($row[$i]['Nombre_Unidad'] != "") {
								if ($row[$i]['Abreviatura_Unidad'] != "") {
									$nombreabreviatura = "(".$row[$i]['Abreviatura_Unidad'].")";
								}
								$nombrePresentacion = "<br>".$row[$i]['Nombre_Unidad'].$nombreabreviatura;
							}
						}

						$query2 = "SELECT ID_Detalle_Devolucion, FK_Devolucion, FK_Detalle_Venta, SUM(detalles_devolucion.Cantidad) AS Cantidad, SUM(detalles_devolucion.Total) AS Total FROM detalles_devolucion INNER JOIN detalles_ventas ON FK_Detalle_Venta = ID_Detalle_Venta WHERE detalles_ventas.FK_Venta = '$idventa' AND FK_Detalle_Venta = '".$row[$i]['ID_Detalle_Venta']."' GROUP BY FK_Detalle_Venta";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;
						$cantidadDevuelto = 0;
						$total = 0; $totalVenta = 0;
						$cantidadActual = $row[$i]['Cantidad'];
						if($row2 == 'si'){
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								$cantidadActual = $row[$i]['Cantidad'] - (double)$row2[0]["Cantidad"];
								$cantidadDevuelto = (double)$row2[0]["Cantidad"];
								$total = $row2[0]["Total"];
							}
						}

						$campoDevolver = "";
						if ($cantidadActual > 0) {
							$campoDevolver = '<input class="form-control devolverProducto" type="number" attrid="'.$row[$i]['ID_Detalle_Venta'].'" value="0" min="0" max="'.$cantidadActual.'">';
						}	

						$totalVenta = $row[$i]['Total'] - $total;
						$precio = 0;
						if ($totalVenta <= 0) {
							$precio = $total / $cantidadDevuelto;
						}else{
							$precio = ($totalVenta / $cantidadActual);
						}

						$arreglo['data'][] = array(
							'ID' => $row[$i]['ID_Detalle_Venta'],
							'Producto' => $row[$i]['Producto']." ".$nombrePresentacion,
							'Cantidad' => $cantidadActual,
							'Precio' => '<b class="dinero">'.$precio.'</b>',
							'TotalVenta' => '<b class="dinero">'.$totalVenta.'</b>',
							'Devuelto' => $cantidadDevuelto,
							'Total' => '<b class="dinero">'.$total.'</b>',
							'Devolver' => $campoDevolver,
						);
						
					}	
				}
			}
			//$numerofilasTotal = $numerofilas + $numerofilas2;
			$arreglo['totales'] = array('NumRows' => $row[0]["Num"]);
			echo json_encode($arreglo);
		}else if($tipo == "GuardarDevolucionProductos"){
			$idventa =  $omodelo->link->real_escape_string($idventa);
			$folio = str_pad($idventa, 8, "0", STR_PAD_LEFT);
			$sucursal =  $omodelo->link->real_escape_string($sucursal);
			$acciones =  $omodelo->link->real_escape_string($acciones);
			$fecha = date('Y-m-d H:i:s'); 

			$query = "INSERT INTO devoluciones SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' , FK_Venta = '$idventa', Toda = '0', Fecha_Registro = '$fecha'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$idDevolucion = mysqli_insert_id($omodelo->link);

				$productos = json_decode($producto, true);
				foreach ($productos as $fila) {
					$individual = 0;
					$total = 0;
					$query2 = "SELECT ID_Detalle_Venta, FK_Venta, FK_Producto, FK_Presentacion, Descripcion, Precio, Cantidad, Descuento, Total FROM detalles_ventas WHERE ID_Detalle_Venta = '$fila[0]'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;
					if($row2 == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$individual = $row2[0]["Total"] / $row2[0]["Cantidad"];
							$total = $fila[1] * $individual;
							$query3 = "INSERT INTO detalles_devolucion SET FK_Devolucion = '$idDevolucion', FK_Detalle_Venta = '".$row2[0]["ID_Detalle_Venta"]."', Cantidad = '$fila[1]', Total = '$total', Destino = '$acciones'";
							$error3 = $omodelo->_insertar($query3);
							if ($error3 == "si") {
								echo "Error 3: ".mysqli_error($omodelo->link);
							}
						}
					}

					if ($acciones == "Inventario") {
						$query4 = "UPDATE inventario SET Cantidad = (Cantidad + $fila[1]) WHERE FK_Producto = '".$row2[0]["FK_Producto"]."' AND FK_Presentacion = '".$row2[0]["FK_Presentacion"]."' AND FK_Sucursal = '$sucursal'";
						$error4 = $omodelo->_insertar($query4);
						if ($error4 == "si") {
							echo "Error inventario: ".mysqli_error($omodelo->link);
						}	
					}else if($acciones == "Merma"){
						$query4 = "INSERT INTO merma SET FK_Producto = '".$row2[0]["FK_Producto"]."', FK_Presentacion = '".$row2[0]["FK_Presentacion"]."', Costo = '0', FK_Sucursal = '$sucursal', Cantidad = '$fila[1]', Fecha_Merma = '$fecha', Fecha_Registro = '$fecha', Motivo = 'Devolución de la venta $folio', Foto = '', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
						$error4 = $omodelo->_insertar($query4);
						if ($error4 == "si") {
							echo "Error inventario: ".mysqli_error($omodelo->link);
						}
					}

				}

				echo "Correcto";
			}
		}else if($tipo == "ComprobarDevolucionProducto"){
			$idventa =  $omodelo->link->real_escape_string($idventa);

			//COMPROBAR SI ESTA TOTALMENTE DEVUELTA
			$cantidadventa = 0; $cantidaddevolucion = 0;
			$queryCanVen = "SELECT SUM(Cantidad) AS CantidadVenta FROM detalles_ventas WHERE FK_Venta = '".$idventa."'";
			$rowCanVen = $omodelo->_consultar($queryCanVen);
			$numerofilasCanVen = $omodelo->numerofilas;
			if($rowCanVen == 'si'){
				echo "Error 3: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilasCanVen > 0){
					$cantidadventa = $rowCanVen[0]["CantidadVenta"];
				}
			}

			$queryCanDev = "SELECT SUM(Cantidad) AS CantidadDevolucion FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE FK_Venta = '$idventa'";
			$rowCanDev = $omodelo->_consultar($queryCanDev);
			$numerofilasCanDev = $omodelo->numerofilas;
			if($rowCanDev == 'si'){
				echo "Error 4: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilasCanDev > 0){
					$cantidaddevolucion = $rowCanDev[0]["CantidadDevolucion"];
				}
			}

			if ($cantidadventa > 0 && $cantidaddevolucion > 0) {
				if ($cantidadventa == $cantidaddevolucion) {
					$queryVenta = "UPDATE ventas SET Estatus = 'Devuelta' WHERE ID_Venta = '$idventa'";
					$errorVenta = $omodelo->_insertar($queryVenta);
					if ($errorVenta == "si") {
						echo "Error Venta Toda: ".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";
					}
				}
			}else{
				echo "No";
			}
		}else if($tipo == "ConsultarCaja"){
			$query = "SELECT Estado, FK_Usuario FROM cajas WHERE ID_Caja = 1";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					if ($row[0]["Estado"] == 1) {
						echo "Abierta";
					}else{
						echo "Cerrada";
					}
				}
			}
		}else if($tipo == "AbrirCaja"){
			$query = "UPDATE cajas SET Estado = 1, FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' WHERE ID_Caja = 1";
			$error = $omodelo->_insertar($query);
			if($error == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$query2 = "INSERT INTO detalles_caja SET FK_Caja = '1', Fecha_Abrir = '$fecha', 	Monto_Abrir = '$MontoAbrir', FK_Usuario_Abrir = '".$_SESSION['user_admin']['ID_Usuario']."'";
				$error2 = $omodelo->_insertar($query2);
				if($error2 == 'si'){
					echo "Error 2: ".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
				}
			}
		}
	}
}
?>
