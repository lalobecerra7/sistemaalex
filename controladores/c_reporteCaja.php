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
		$sucursal =  $omodelo->link->real_escape_string($sucursal);

		$fechaInicio =  $omodelo->link->real_escape_string($fechaInicio);
		$fechaFin =  $omodelo->link->real_escape_string($fechaFin);

		$arreglo = array();

		$busqueda = '';
		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));
			$busqueda = 'AND ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(Fecha_Abrir, Monto_Abrir, Fecha_Cierre, Monto_Cierre, usuarios.Nombre, usuarios.Primer_Apellido, usuarios.Segundo_Apellido) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		/*$querySucursal = '';

		if ($omodelo->permisos() == 'Administrador') {
			$querySucursal = '';
		}else{
			if ($busqueda == "") {
				$querySucursal = "WHERE cajas.FK_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}else{
				$querySucursal = "AND cajas.FK_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
		}*/
		
		$query = "SELECT ID_Detalle_Caja, cajas.Nombre AS Caja, FK_Caja, cajas.FK_Sucursal AS IDSucursal, Fecha_Abrir AS Abrir, CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) AS NombreUsuarioAbrir, Monto_Abrir AS MontoAbrir, FK_Usuario_Abrir, (SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM usuarios WHERE ID_Usuario = FK_Usuario_Cierre) AS NombreUsuarioCerrar, Fecha_Cierre AS Cerrar, Monto_Cierre AS MontoCerrar, FK_Usuario_Cierre, (SELECT COUNT(*) FROM detalles_caja INNER JOIN usuarios ON FK_Usuario_Abrir = ID_Usuario INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = '$sucursal' AND (DATE(Fecha_Abrir) >= '$fechaInicio' AND DATE(Fecha_Abrir) <= '$fechaFin') $busqueda) AS Num FROM detalles_caja INNER JOIN usuarios ON FK_Usuario_Abrir = ID_Usuario INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = '$sucursal' AND DATE(Fecha_Abrir) >= '$fechaInicio' AND (DATE(Fecha_Abrir) <= '$fechaFin') $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		//echo $query;
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$botonTicket = '<button title="Imprimir ticket" class="btn btn-success btn-sm" id="ReimprimirTicketCaja" attrid="'.$row[$i]['ID_Detalle_Caja'].'" sucursal="'.$row[$i]['IDSucursal'].'"><i class="fas fa-print"></i></button>';
					// <button class="mt-2 btn btn-primary VerCorteCaja" attrid="'.$row[$i]['ID_Detalle_Caja'].'" idSucursal="'.$row[$i]['IDSucursal'].'">Ver</button>
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Detalle_Caja'],
						'Caja' => $row[$i]['Caja'],
						'Abrir' => "La caja se abrio el: <b>".$row[$i]['Abrir']."</b><br>Abierta por: ".$row[$i]['NombreUsuarioAbrir'],
						'MontoAbrir' => number_format($row[$i]['MontoAbrir'], 2),
						'Cerrar' => "La caja se cerro el: <b>".$row[$i]['Cerrar']."</b><br>Cerrada por: ".$row[$i]['NombreUsuarioCerrar'],
						'MontoCerrar' => number_format($row[$i]['MontoCerrar'], 2),
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
		}else if($tipo == "ConsultarBalanceCerrar"){
			$fecha = date('Y-m-d H:i:s'); 

			$sucursal =  $omodelo->link->real_escape_string($sucursal);
			$arreglo = [];
			$query = "SELECT ID_Detalle_Caja, FK_Caja, DATE_FORMAT(Fecha_Abrir, '%Y-%m-%d') AS FechaAbrirCorta, Fecha_Abrir, Monto_Abrir, FK_Usuario_Abrir, (SELECT Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario_Abrir) AS UsuarioAbrir, (SELECT Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario_Cierre) AS UsuarioCerrar, Fecha_Cierre, Monto_Cierre, FK_Usuario_Cierre, DATE_FORMAT(Fecha_Cierre, '%Y-%m-%d') AS FechaCierreCorta, DATE_FORMAT(Fecha_Cierre, '%Y-%m-%d %r') AS FechaCerrar, DATE_FORMAT(Fecha_Abrir, '%Y-%m-%d %r') AS FechaAbrir FROM detalles_caja WHERE ID_Detalle_Caja = '$IDDetalleCaja'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$totalIngresos = 0; $totalIngresosEfectivo = 0; $totalEgresos = 0; $totalEgresosEfectivo = 0; $totalventas = 0; $totalimportes = 0; 
					$totalcompras = 0; $totaldevoluciones = 0; $totalpagos = 0; $totalimportesEgresos = 0; $totalGastos = 0; $totalDepositos = 0;

					//************************** INGRESOS ***********************//
					//Ventas
					//Importes
					$totalvefectivo = 0;
					$totalvcheque = 0;
					$totalvtarjeta = 0;
					$totalvdeposito = 0;
					$totalvonline = 0; 
					$totalvtarjetadebito = 0;
					$totalvtransferencia = 0;

					$queryv = "SELECT ventas.Total, ventas.Total_Importes, ventas.Tipo_Pago, Pago_Efectivo, Pago_Transferencia, Pago_Cheque, Pago_Tarjeta_Credito, Pago_Tarjeta_Debito FROM ventas WHERE (Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND Fecha_Registro <= '".$row[0]["Fecha_Cierre"]."') AND Estatus = 'Completada' AND Contar_Venta = 0 AND FK_Sucursal = '$sucursal'";
					$rowv = $omodelo->_consultar($queryv);
					$numerofilasv = $omodelo->numerofilas;
					if($rowv == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasv > 0){
							for ($ventas=0; $ventas < $numerofilasv; $ventas++) { 
								$totalIngresos += $rowv[$ventas]["Total"];
								$totalventas += $rowv[$ventas]["Total"] + $rowv[$ventas]["Total_Importes"];

								if ($rowv[$ventas]["Pago_Efectivo"] > 0) {
									$totalvefectivo += $rowv[$ventas]["Pago_Efectivo"];
									$totalIngresosEfectivo += $rowv[$ventas]["Pago_Efectivo"] - $rowv[$ventas]["Total_Importes"];
								}

								if ($rowv[$ventas]["Pago_Transferencia"] > 0) {
									$totalvtransferencia += $rowv[$ventas]["Pago_Transferencia"] - $rowv[$ventas]["Total_Importes"];
								}

								if ($rowv[$ventas]["Pago_Cheque"] > 0) {
									$totalvcheque += $rowv[$ventas]["Pago_Cheque"] - $rowv[$ventas]["Total_Importes"];
								}

								if ($rowv[$ventas]["Pago_Tarjeta_Credito"] > 0) {
									$totalvtarjeta += $rowv[$ventas]["Pago_Tarjeta_Credito"] - $rowv[$ventas]["Total_Importes"];
								}

								if ($rowv[$ventas]["Pago_Tarjeta_Debito"] > 0) {
									$totalvtarjetadebito += $rowv[$ventas]["Pago_Tarjeta_Debito"] - $rowv[$ventas]["Total_Importes"];
								}


							}
						}
					}

					$queryi = "SELECT importes.Total FROM importes INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (ventas.Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND ventas.Fecha_Registro <= '".$row[0]["FechaCierreCorta"]."') AND ventas.FK_Sucursal = '$sucursal'";
					$rowi = $omodelo->_consultar($queryi);
					$numerofilasi = $omodelo->numerofilas;
					if($rowi == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasi > 0){
							for ($importes=0; $importes < $numerofilasi; $importes++) { 
								$totalIngresos += $rowi[$importes]["Total"];
								$totalimportes += $rowi[$importes]["Total"];
								$totalIngresosEfectivo += $rowi[$importes]["Total"];
							}
						}
					}

					//************************** EGRESOS ************************//
					//Pago de importes a los clientes
					$queryiEgresos = "SELECT detalles_importes.Cantidad AS CantidadImportes, importes.Importe AS PrecioImporte FROM detalles_importes INNER JOIN importes ON FK_Importe = ID_Importe INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (detalles_importes.Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND detalles_importes.Fecha_Registro <= '".$row[0]["FechaCierreCorta"]."') AND ventas.FK_Sucursal = '$sucursal'";
					$rowiEgresos = $omodelo->_consultar($queryiEgresos);
					$numerofilasiEgresos = $omodelo->numerofilas;
					if($rowiEgresos == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasiEgresos > 0){
							for ($importesEgresos=0; $importesEgresos < $numerofilasiEgresos; $importesEgresos++) { 
								$totalEgresos += $rowiEgresos[$importesEgresos]["CantidadImportes"] * $rowiEgresos[$importesEgresos]["PrecioImporte"];
								$totalimportesEgresos += $rowiEgresos[$importesEgresos]["CantidadImportes"] * $rowiEgresos[$importesEgresos]["PrecioImporte"];
								$totalEgresosEfectivo += $rowiEgresos[$importesEgresos]["CantidadImportes"] * $rowiEgresos[$importesEgresos]["PrecioImporte"];
							}
						}
					}


					//Compras al contado
					$query2 = "SELECT Total FROM compras WHERE Estatus = 1 AND Tipo_Compra = 'Contado' AND (Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND Fecha_Registro <= '".$row[0]["Fecha_Cierre"]."') AND FK_Sucursal = '$sucursal'";
					$rowc = $omodelo->_consultar($query2);
					$numerofilasc = $omodelo->numerofilas;
					if($rowc == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasc > 0){
							for ($compras=0; $compras < $numerofilasc; $compras++) { 
								$totalEgresos += $rowc[$compras]["Total"];
								$totalcompras += $rowc[$compras]["Total"];
							}
						}
					}

					//Pagos de compras al contado
					$pagoscontadoefectivo = 0;
					$pagoscontadocheque = 0;
					$pagoscontadodeposito = 0;
					$pagostarjetacontado = 0;
					$pagoscontadotransferencia = 0;
					$queryContado = "SELECT pagos.Monto, pagos.Tipo_Pago FROM pagos INNER JOIN compras ON FK_Compra = ID_Compra WHERE (pagos.Fecha >= '".$row[0]["Fecha_Abrir"]."' AND pagos.Fecha <= '".$row[0]["FechaCierreCorta"]."') AND Estatus = 1 AND Tipo_Compra = 'Contado' AND compras.FK_Sucursal = '$sucursal'";
					$rowcontado = $omodelo->_consultar($queryContado);
					$numerofilascontado = $omodelo->numerofilas;
					if($rowcontado == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilascontado > 0){
							for ($pagoscontado=0; $pagoscontado < $numerofilascontado; $pagoscontado++) { 
								if ($rowcontado[$pagoscontado]["Tipo_Pago"] == "Efectivo") {
									$pagoscontadoefectivo += $rowcontado[$pagoscontado]["Monto"];
									$totalEgresosEfectivo += $rowcontado[$pagoscontado]["Monto"];
								}else if ($rowcontado[$pagoscontado]["Tipo_Pago"] == "Deposito") {
									$pagoscontadodeposito += $rowcontado[$pagoscontado]["Monto"];
								}else if ($rowcontado[$pagoscontado]["Tipo_Pago"] == "Cheque") {
									$pagoscontadocheque += $rowcontado[$pagoscontado]["Monto"];
								}else if ($rowcontado[$pagoscontado]["Tipo_Pago"] == "TransferenciaBancaria") {
									$pagoscontadotransferencia += $rowcontado[$pagoscontado]["Monto"];
								}else if ($rowcontado[$pagoscontado]["Tipo_Pago"] == "TarjetaCreditoDebito") {
									$pagostarjetacontado += $rowcontado[$pagoscontado]["Monto"];	
								}
							}
						}
					}

					//Pagos
					$pagosefectivo = 0;
					$pagoscheque = 0;
					$pagosdeposito = 0;
					$pagostarjeta = 0;
					$pagostransferencia = 0;
					$query2 = "SELECT pagos.Monto, pagos.Tipo_Pago FROM pagos INNER JOIN compras ON FK_Compra = ID_Compra WHERE (pagos.Fecha >= '".$row[0]["Fecha_Abrir"]."' AND pagos.Fecha <= '".$row[0]["FechaCierreCorta"]."') AND Tipo_Compra = 'Credito' AND compras.FK_Sucursal = '$sucursal'";
					$rowp = $omodelo->_consultar($query2);
					$numerofilasp = $omodelo->numerofilas;
					if($rowp == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasp > 0){
							for ($pagos=0; $pagos < $numerofilasp; $pagos++) { 
								$totalEgresos += $rowp[$pagos]["Monto"];
								$totalpagos += $rowp[$pagos]["Monto"];
								if ($rowp[$pagos]["Tipo_Pago"] == "Efectivo") {
									$pagosefectivo += $rowp[$pagos]["Monto"];
									$totalEgresosEfectivo += $rowp[$pagos]["Monto"];
								}else if ($rowp[$pagos]["Tipo_Pago"] == "Deposito") {
									$pagosdeposito += $rowp[$pagos]["Monto"];
								}else if ($rowp[$pagos]["Tipo_Pago"] == "Cheque") {
									$pagoscheque += $rowp[$pagos]["Monto"];
								}else if ($rowp[$pagos]["Tipo_Pago"] == "TransferenciaBancaria") {
									$pagostransferencia += $rowp[$pagos]["Monto"];
								}else if ($rowp[$pagos]["Tipo_Pago"] == "TarjetaCreditoDebito") {
									$pagostarjeta += $rowp[$pagos]["Monto"];	
								}
							}
						}
					}

					//Devoluciones
					$querydev = "SELECT detalles_devolucion.Total FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (devoluciones.Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND devoluciones.Fecha_Registro <= '".$row[0]["FechaCierreCorta"]."') AND ventas.FK_Sucursal = '$sucursal'";
					$rowdev = $omodelo->_consultar($querydev);
					$numerofilasdev = $omodelo->numerofilas;
					if($rowdev == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasdev > 0){
							for ($devol=0; $devol < $numerofilasdev; $devol++) { 
								$totalEgresos += $rowdev[$devol]["Total"];
								$totaldevoluciones += $rowdev[$devol]["Total"];
								$totalEgresosEfectivo += $rowdev[$devol]["Total"];
							}
						}
					}

					//Gastos
					$gastosefectivo = 0;
					$gastoscheque = 0;
					$gastosdeposito = 0;
					$gastostarjeta = 0;
					$gastostransferencia = 0;
					$queryGasto = "SELECT Forma_Pago, Monto FROM gastos_generales WHERE (Fecha_Gasto >= '".$row[0]["FechaAbrirCorta"]."' AND Fecha_Gasto <= '".$row[0]["FechaCierreCorta"]."') AND FK_Sucursal = '$sucursal'";
					$rowGasto = $omodelo->_consultar($queryGasto);
					$numerofilasgastos = $omodelo->numerofilas;
					if($rowGasto == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasgastos > 0){
							for ($gasto=0; $gasto < $numerofilasgastos; $gasto++) { 
								$totalEgresos += $rowGasto[$gasto]["Monto"];
								$totalGastos += $rowGasto[$gasto]["Monto"];
								if ($rowGasto[$gasto]["Forma_Pago"] == "Efectivo") {
									$gastosefectivo +=$rowGasto[$gasto]["Monto"];
									$totalEgresosEfectivo +=$rowGasto[$gasto]["Monto"];
								}else if ($rowGasto[$gasto]["Forma_Pago"] == "Deposito") {
									$gastosdeposito +=$rowGasto[$gasto]["Monto"];
								}else if ($rowGasto[$gasto]["Forma_Pago"] == "Cheque") {
									$gastoscheque +=$rowGasto[$gasto]["Monto"];
								}else if ($rowGasto[$gasto]["Forma_Pago"] == "Transferencia") {
									$gastostransferencia +=$rowGasto[$gasto]["Monto"];
								}else if ($rowGasto[$gasto]["Forma_Pago"] == "TDebitoCredito") {
									$gastostarjeta +=$rowGasto[$gasto]["Monto"];	
								}
							}
						}
					}

					//Depositos
					$totalDepositos = 0;
					$queryDeposito = "SELECT Monto FROM depositos WHERE (Fecha_Deposito >= '".$row[0]["FechaAbrirCorta"]."' AND Fecha_Deposito <= '".$row[0]["FechaCierreCorta"]."') AND FK_Sucursal = '$sucursal'";
					$rowDeposito = $omodelo->_consultar($queryDeposito);
					$numerofilasdev = $omodelo->numerofilas;
					if($rowDeposito == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasdev > 0){
							for ($deposito=0; $deposito < $numerofilasdev; $deposito++) { 
								$totalEgresos += $rowDeposito[$deposito]["Monto"];
								$totalDepositos += $rowDeposito[$deposito]["Monto"];
								$totalEgresosEfectivo += $rowDeposito[$deposito]["Monto"];
							}
						}
					}

					$arreglo[] = array(
						"ID_Detalle_Caja" => $row[0]["ID_Detalle_Caja"],
						"Fecha_Abrir" => $row[0]["FechaAbrir"],
						"Fecha_Cerrar" => $row[0]["FechaCerrar"],
						"Usuario_Abrir" => $row[0]["UsuarioAbrir"],
						"Usuario_Cerrar" => $row[0]["UsuarioCerrar"],
						"Monto_Abrir" => $row[0]["Monto_Abrir"],
						"Monto_Cierre" => $row[0]["Monto_Cierre"],
						"Total_Ingresos" => $totalIngresos,
						"Total_Ingresos_Efectivo" => $totalIngresosEfectivo,
						"Total_Egresos" => $totalEgresos,
						"Total_Egresos_Efectivo" => $totalEgresosEfectivo,
						"Total_Ventas" => $totalventas,
						"Total_Ventas_Efectivo" => $totalvefectivo,
						"Total_Ventas_Deposito" => $totalvdeposito,
						"Total_Ventas_Cheque" => $totalvcheque,
						"Total_Ventas_TransferenciaBancaria" => $totalvtransferencia,
						"Total_Ventas_TarjetaCredito" => $totalvtarjeta,
						"Total_Ventas_TarjetaDebito" => $totalvtarjetadebito,
						"Total_Ventas_PagoOnline" => $totalvonline,
						"Total_Importes" => $totalimportes,
						"Total_Importes_Egresos" => $totalimportesEgresos,
						"Total_Compras" => $totalcompras,
						"Total_Compras_Efectivo" => $pagoscontadoefectivo,
						"Total_Compras_Cheque" => $pagoscontadocheque,
						"Total_Compras_Deposito" => $pagoscontadodeposito,
						"Total_Compras_Tarjeta" => $pagostarjetacontado,
						"Total_Compras_Transferencia" => $pagoscontadotransferencia,
						"Total_Pagos" => $totalpagos,
						"Total_Pagos_Efectivo" => $pagosefectivo,
						"Total_Pagos_Deposito" => $pagosdeposito,
						"Total_Pagos_Cheque" => $pagoscheque,
						"Total_Pagos_TransferenciaBancaria" => $pagostransferencia,
						"Total_Pagos_TarjetaCreditoDebito" => $pagostarjeta,
						"Total_Devoluciones" => $totaldevoluciones,
						"Total_Gastos" => $totalGastos,
						"Total_Gastos_Efectivo" => $gastosefectivo,
						"Total_Gastos_Deposito" => $gastosdeposito,
						"Total_Gastos_Cheque" => $gastoscheque,
						"Total_Gastos_TransferenciaBancaria" => $gastostransferencia,
						"Total_Gastos_TarjetaCreditoDebito" => $gastostarjeta,
						"Total_Depositos" => $totalDepositos,
					);
				}
				echo json_encode($arreglo);
			}
		}
	}
}
?>
