<?php
class reporteCaja {

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
				$busqueda .= "CONCAT(Fecha_Abrir, Monto_Abrir, Fecha_Cierre, Monto_Cierre, usuarios.Nombre, usuarios.Primer_Apellido, usuarios.Segundo_Apellido) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}
		
		$query = "SELECT ID_Detalle_Caja, FK_Caja, cajas.FK_Sucursal AS IDSucursal, Fecha_Abrir AS Abrir, CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) AS NombreUsuarioAbrir, Monto_Abrir AS MontoAbrir, FK_Usuario_Abrir, (SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM usuarios WHERE ID_Usuario = FK_Usuario_Cierre) AS NombreUsuarioCerrar, Fecha_Cierre AS Cerrar, Monto_Cierre AS MontoCerrar, FK_Usuario_Cierre, (SELECT COUNT(*) FROM detalles_caja INNER JOIN usuarios ON FK_Usuario_Abrir = ID_Usuario INNER JOIN cajas ON FK_Caja = ID_Caja $busqueda) AS Num FROM detalles_caja INNER JOIN usuarios ON FK_Usuario_Abrir = ID_Usuario INNER JOIN cajas ON FK_Caja = ID_Caja $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$totalIngresos = 0; $totalEgresos = 0; $totalUtilidad = 0;
					$botonTicket = '<button title="Imprimir ticket" class="btn btn-success btn-sm" id="ReimprimirTicketCaja" attrid="'.$row[$i]['ID_Detalle_Caja'].'" sucursal="'.$row[$i]['IDSucursal'].'"><i class="fas fa-print"></i></button>';

					$querydetallecaja = "SELECT ID_Detalle_Caja, FK_Caja, Fecha_Abrir, Monto_Abrir, FK_Usuario_Abrir, Fecha_Cierre, Monto_Cierre, FK_Usuario_Cierre, DATE_FORMAT(Fecha_Cierre, '%Y-%m-%d %r') AS FechaCerrar, DATE_FORMAT(Fecha_Abrir, '%Y-%m-%d %r') AS FechaAbrir FROM detalles_caja WHERE ID_Detalle_Caja = '".$row[$i]['ID_Detalle_Caja']."'";
					$rowdetallecaja = $omodelo->_consultar($querydetallecaja);
					$numerofilas2 = $omodelo->numerofilas;
					if($rowdetallecaja == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							///*****************************INGRESOS********************************///
							$queryv = "SELECT Total, Tipo_Pago, Contar_Venta FROM ventas WHERE (Fecha_Registro >= '".$rowdetallecaja[0]["Fecha_Abrir"]."' AND Fecha_Registro <= '".$rowdetallecaja[0]["Fecha_Cierre"]."') AND Estatus = 'Completada'";
							$rowv = $omodelo->_consultar($queryv);
							$numerofilasv = $omodelo->numerofilas;
							if($rowv == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasv > 0){
									for ($ventas=0; $ventas < $numerofilasv; $ventas++) { 
										if($rowv[$ventas]['Contar_Venta'] == '0'){
											$totalIngresos += $rowv[$ventas]["Total"];
										}
									}
								}
							}

							$queryi = "SELECT detalles_importes.Cantidad AS CantidadImportes, importes.Importe AS PrecioImporte FROM detalles_importes INNER JOIN importes ON FK_Importe = ID_Importe WHERE (detalles_importes.Fecha_Registro >= '".$rowdetallecaja[0]["Fecha_Abrir"]."' AND detalles_importes.Fecha_Registro <= '".$rowdetallecaja[0]["Fecha_Cierre"]."')";
							$rowi = $omodelo->_consultar($queryi);
							$numerofilasi = $omodelo->numerofilas;
							if($rowi == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasi > 0){
									for ($importes=0; $importes < $numerofilasi; $importes++) { 
										$totalIngresos += $rowi[$importes]["CantidadImportes"] * $rowi[$importes]["PrecioImporte"];
									}
								}
							}
							///*****************************INGRESOS********************************///

							///******************************EGRESOS*******************************///
							$querycompras = "SELECT Total FROM compras WHERE Estatus = 1 AND Tipo_Compra = 'Contado' AND (Fecha_Registro >= '".$rowdetallecaja[0]["Fecha_Abrir"]."' AND Fecha_Registro <= '".$rowdetallecaja[0]["Fecha_Cierre"]."')";
							$rowc = $omodelo->_consultar($querycompras);
							$numerofilasc = $omodelo->numerofilas;
							if($rowc == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasc > 0){
									for ($compras=0; $compras < $numerofilasc; $compras++) { 
										$totalEgresos += $rowc[$compras]["Total"];
									}
								}
							}

							$querypagos = "SELECT Monto, Tipo_Pago FROM pagos INNER JOIN compras ON FK_Compra = ID_Compra WHERE (pagos.Fecha >= '".$rowdetallecaja[0]["Fecha_Abrir"]."' AND pagos.Fecha <= '".$rowdetallecaja[0]["Fecha_Cierre"]."') AND Estatus = 0 AND Tipo_Compra = 'Credito'";
							$rowp = $omodelo->_consultar($querypagos);
							$numerofilasp = $omodelo->numerofilas;
							if($rowp == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasp > 0){
									for ($pagos=0; $pagos < $numerofilasp; $pagos++) { 
										$totalEgresos += $rowp[$pagos]["Monto"];
									}
								}
							}

							$querydev = "SELECT Total FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE (devoluciones.Fecha_Registro >= '".$rowdetallecaja[0]["Fecha_Abrir"]."' AND devoluciones.Fecha_Registro <= '".$rowdetallecaja[0]["Fecha_Cierre"]."')";
							$rowdev = $omodelo->_consultar($querydev);
							$numerofilasdev = $omodelo->numerofilas;
							if($rowdev == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasdev > 0){
									for ($devol=0; $devol < $numerofilasdev; $devol++) { 
										$totalEgresos += $rowdev[$devol]["Total"];
									}
								}
							}
							///******************************EGRESOS*******************************///
						}
					}	


					$totalUtilidad = $totalIngresos - $totalEgresos;

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Detalle_Caja'],
						'Abrir' => "La caja se abrio el: <b>".$row[$i]['Abrir']."</b><br>Abierta por: ".$row[$i]['NombreUsuarioAbrir'],
						'MontoAbrir' => number_format($row[$i]['MontoAbrir'], 2),
						'Cerrar' => "La caja se cerro el: <b>".$row[$i]['Cerrar']."</b><br>Cerrada por: ".$row[$i]['NombreUsuarioCerrar'],
						'MontoCerrar' => number_format($row[$i]['MontoCerrar'], 2),
						'Totales' => "Ingresos: <b class='dinero'>".$totalIngresos."</b><br>Egresos: <b class='dinero'>".$totalEgresos."</b><br>Utilidad: <b class='dinero'>".$totalUtilidad."</b>",
						'Acciones' => $botonTicket,
					);	
				}
				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);
			}
		}	
		echo json_encode($arreglo);
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
		if($tipo == 'productos'){
			$IDVenta = $omodelo->link->real_escape_string($IDVenta);
			$tabla = "";
			$query = "SELECT ID_Detalle_Venta, FK_Venta, detalles_ventas.FK_Producto, FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, Descripcion, Precio, Cantidad, Descuento, Total, Regreso_Inventario, Clave_Unidad_CFDI, Unidad_CFDI, Objeto_Impuesto_CFDI FROM detalles_ventas LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$IDVenta'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$sumaImpuestos=0; $subtotal = 0;

						$query2 = "SELECT ID_Impuesto, FK_Detalle_Venta, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row[$i]["ID_Detalle_Venta"]."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;

						if($row2 == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								for($x=0; $x<$numerofilas2; $x++){
									$totalProducto=0; $descuento=0; $totalFinal=0;
									$totalProducto = $row[$i]["Precio"]*$row[$i]["Cantidad"];
									$descuento = ($row[$i]["Descuento"] / 100);
									$totalFinal = $totalProducto - ($totalProducto * $descuento);
									if ($row2[$x]["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
										$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] != "Exento"){ //Se resta al total
										$sumaImpuestos -= $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] == "Exento"){ ////No se suma ni se resta
										$sumaImpuestos += 0;
									}
								}
							}
						}


						$presentacion = "";
						$nombrepresentacion = "";
						if ($row[$i]["NombrePresentacion"] != "") {
							$presentacion = "<br>".$row[$i]["NombrePresentacion"];
							$nombrepresentacion = " / ".$row[$i]["NombrePresentacion"];
							if ($row[$i]["AbreviaturaPresentacion"] != "") {
								$presentacion .= " (".$row[$i]["AbreviaturaPresentacion"].")";
								$nombrepresentacion .= " (".$row[$i]["AbreviaturaPresentacion"].")";
							} 
						}

						$subtotal = ($row[$i]["Precio"] * $row[$i]["Cantidad"]) ;
						$descuento = $subtotal * ($row[$i]["Descuento"] / 100);
						$subtotal = $subtotal - $descuento;
						$tabla .= "
						<tr>
						<td >".$row[$i]["Descripcion"].$presentacion."</td>
						<td style='vertical-align: middle;'>$".number_format($row[$i]["Precio"], 2)."</td>
						<td style='vertical-align: middle;'>".number_format($row[$i]["Cantidad"], 2)."</td>
						<td style='vertical-align: middle;'>$".number_format($row[$i]["Descuento"], 2)."</td>
						<td style='vertical-align: middle;'>$".number_format($subtotal, 2)."</td>
						<td><button class='btn btn-primary btn-sm verImpuestosProducto' nombre='".$row[$i]["Descripcion"].$nombrepresentacion."' attrid='".$row[$i]["ID_Detalle_Venta"]."'>$".number_format($sumaImpuestos, 2)."</button></td>
						<td>$".number_format($row[$i]["Total"], 2)."</td>
						</tr>
						";
					}
				}
			}

			echo $tabla;
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
