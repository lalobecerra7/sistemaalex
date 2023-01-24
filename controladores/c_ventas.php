<?php
class ventas {

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
				$busqueda .= "CONCAT(DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y %r'), LPAD(ID_Venta, 8, '0'), clientes.Nombre, ventas.Descuento, Total, Tipo_Pago, Notas, clientes.Correo, Total_Importes) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}
		
		$query = "SELECT ID_Venta, Facturada, ventas.FK_Usuario, ventas.FK_Direccion, ventas.FK_Sucursal, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = ventas.FK_Sucursal) AS NombreSucursal, FK_Caja, FK_Cliente, ventas.Descuento, Total, Total_Importes, Tipo_Pago, Estatus, Pago, Cambio, Notas, ventas.Fecha_Registro AS Datos, DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Fecha_Cancelacion, Regreso_Inventario, (SELECT CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) FROM usuarios WHERE ID_Usuario = ventas.FK_Usuario) AS NombreUsuario, clientes.Nombre AS NombreCliente, clientes.Telefono AS Telefono, clientes.Correo AS CorreoCliente, clientes.RFC AS RFCCliente, (SELECT COUNT(*) FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente $busqueda) AS Num FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$SumarVentas = 0;
				for($i=0; $i<$numerofilas; $i++){
					$tipoUsuario = "";$usuario="";$estatus="";$motivocancelada="";$botonCancelar="";$fechacancelada="";$botonTicket="";
					$folio = str_pad($row[$i]['ID_Venta'], 8, "0", STR_PAD_LEFT);
					$botonEliminar = "";
					$botondeCancelar = "";
					$botonEliminar = '<button title="Eliminar venta" class="btn btn-danger btn-sm" id="EliminarVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'"><i class="fas fa-trash"></i></button>';

					$botondeCancelar = '<button title="Cancelar venta" class="btn btn-warning btn-sm" id="CancelarVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'" sucursal="'.$row[$i]['FK_Sucursal'].'"><i class="fas fa-circle-xmark"></i></button>';

					$botonTicket = '<button title="Imprimir ticket" class="btn btn-success btn-sm" id="ImprimirTicketVentaSinCaja" attrid="'.$row[$i]['ID_Venta'].'" sucursal="'.$row[$i]['FK_Sucursal'].'" folio="'.$folio.'"><i class="fas fa-print"></i></button>';

					$botonDevolucion = '<button title="Realizar devolución" class="btn btn-secondary btn-sm" id="DevolverVenta" data-bs-target="#ModalDevolucionVenta" data-bs-toggle="modal" attrid="'.$row[$i]['ID_Venta'].'" sucursal="'.$row[$i]['FK_Sucursal'].'" folio="'.$folio.'"><i class="fas fa-arrow-left"></i></button>';

					if($row[$i]['Facturada'] == '0'){//agregar permisos
						$botonFacturar = '<button class="btn btn-info btn-sm bFacturar" attrID="'.$row[$i]['ID_Venta'].'" title="Facturar"><i class="fas fa-file-lines"></i></button>';
					}else{
						$botonFacturar = '<button class="btn btn-info btn-sm bImprimirFacPDF" attrID="'.$row[$i]['ID_Venta'].'" title="Factura PDF"><i class="fas fa-file-pdf"></i></button> <button class="btn btn-info btn-sm bImprimirFacXml" attrID="'.$row[$i]['ID_Venta'].'" title="XML"><i class="fas fa-file-excel"></i></button>';
					}

					$TotalDevolucion = 0;
					$query2 = "SELECT SUM(Total) AS TotalDevolucion FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE FK_Venta = '".$row[$i]['ID_Venta']."'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$TotalDevolucion = $row2[0]["TotalDevolucion"];
						}
					}
					$totalVenta = $row[$i]['Total_Importes'] + $row[$i]['Total'];
					$MostrarDevolucion = "";
					$totalFinal = 0;
					if ($TotalDevolucion > 0) {
						$totalFinal = $row[$i]['Total'] - $TotalDevolucion;
						$MostrarDevolucion = "<br>Devuelto: <b>$".number_format($TotalDevolucion, 2)."</b><br>
						Total final: <b>$".number_format($totalFinal, 2)."</b>";
						$SumarVentas += $totalFinal;
					}else{
						$SumarVentas += $row[$i]['Total'];
					}

					if ($row[$i]['Estatus'] == "Cancelada") {
						$botondeCancelar = '';
						$botonDevolucion = '';
						$estatus='<span class="badge rounded-pill bg-danger">Cancelada</span>';
						$motivocancelada = "Motivo de cancelación: ".$row[$i]['Notas'];
						$fechacancelada = '<br>Fecha de cancelación: <b>'.$row[$i]['Fecha_Cancelacion']."</b><br>";
					}else if ($row[$i]['Estatus'] == "Devuelta") {
						$botonDevolucion = '';
						$estatus='<span class="badge rounded-pill bg-warning">Devuelta</span>';
					}else if($row[$i]['Estatus'] == "Completada"){
						$estatus='<span class="badge rounded-pill bg-success">Completada</span>';
					}

					$botonPermisosCancelar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][3] == '1') {
						$botonPermisosCancelar = $botondeCancelar;
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][4] == '1') {
						$botonPermisosEliminar = $botonEliminar;
					}

					$botonPermisosFacturar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][5] == '1') {
						$botonPermisosFacturar = $botonFacturar;
					}

					$botonPermisosDevoluciones = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][6] == '1') {
						$botonPermisosDevoluciones = $botonDevolucion;
					}

					$botonPermisosTicket = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][7] == '1') {
						$botonPermisosTicket = $botonTicket;
					}

					$facturada = 'No';
					if($row[$i]['Facturada'] == '1'){
						$facturada = 'Si';
					}

					$direccionCliente = "";
					if ($row[$i]['FK_Direccion'] != "0") {
						$query2 = "SELECT Calle, No_Exterior, No_Interior, Colonia, Codigo_Postal, Ciudad, Estado, Pais FROM detalles_clientes WHERE FK_Cliente = '".$row[$i]['FK_Cliente']."' AND ID_Detalle_Cliente = '".$row[$i]['FK_Direccion']."'";
					}else{
						$query2 = "SELECT Calle, No_Exterior, No_Interior, Colonia, Codigo_Postal, Ciudad, Estado, Pais FROM clientes WHERE ID_Cliente = '".$row[$i]['FK_Cliente']."'";
					}
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){

							if ($row2[0]["Calle"] != "") {
								$direccionCliente.="Calle: ".$row2[0]["Calle"]."<br>";

								if ($row2[0]["No_Exterior"] != "") {
									$direccionCliente.="No. Exterior: ".$row2[0]["No_Exterior"]."<br>";
								}

								if ($row2[0]["No_Interior"] != "") {
									$direccionCliente.="No. Interior: ".$row2[0]["No_Interior"]."<br>";
								}

								if ($row2[0]["Colonia"] != "") {
									$direccionCliente.="Colonia: ".$row2[0]["Colonia"]."<br>";
								}

								if ($row2[0]["Ciudad"] != "") {
									$direccionCliente.="Ciudad: ".$row2[0]["Ciudad"]."<br>";
								}

								if ($row2[0]["Codigo_Postal"] != "") {
									$direccionCliente.="Codigo postal: ".$row2[0]["Codigo_Postal"]."<br>";
								}

								if ($row2[0]["Estado"] != "") {
									$direccionCliente.="Estado: ".$row2[0]["Estado"]."<br>";
								}

								if ($row2[0]["Pais"] != "") {
									$direccionCliente.="Pais: ".$row2[0]["Pais"]."<br>";
								}
							}else{
								$direccionCliente = "Sin dirección registrada";
							}

						}else{
							$direccionCliente = "Sin dirección registrada";
						}
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Venta'],
						'Datos' => "Fecha: <b>".$row[$i]['Fecha_Registro']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b><br>Sucursal: <b>".$row[$i]["NombreSucursal"]."</b>",
						'Cliente' => 'Nombre: <b>'.$row[$i]['NombreCliente'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Correo electrónico: <b>'.$row[$i]['CorreoCliente'].'</b><br>RFC: <b>'.$row[$i]['RFCCliente']."</b><br> Dirección: <br><b>".$direccionCliente."</b>",
						'Total' => "
						Pago: <b>$".number_format(($row[$i]['Pago']), 2)."</b><br>
						Subtotal: <b>$".number_format(($row[$i]['Total'] + $row[$i]['Descuento']), 2)."</b><br>
						Descuento: <b>$".number_format($row[$i]['Descuento'], 2)."</b><br>
						Total de venta: <b>$".number_format($row[$i]['Total'], 2)."</b><br>
						Cambio: <b>$".number_format(($row[$i]['Cambio']), 2)."</b>".$MostrarDevolucion."<br>
						<hr>
						Total de importes: <b>$".number_format($row[$i]['Total_Importes'], 2)."</b>",
						'Facturada' => $facturada,
						'Detalles' => $estatus."<br>".$motivocancelada.$fechacancelada.'<button class="btn btn-link btn-sm" id="VerProductosVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'">Ver productos</button>',
						'Acciones' => $botonPermisosEliminar.' '.$botonPermisosCancelar .' '.$botonPermisosTicket.' '.$botonPermisosFacturar.' '.$botonPermisosDevoluciones,
					);
				}

				$arreglo['totales'] = array(
					'NumRows' => $row[0]['Num'], 
					'Datos' => "",
					'Cliente' => "Totales",
					'Total' => "<b>$".number_format($SumarVentas, 2)."</b>",
					'Detalles' =>"",
					'Acciones' => "");	
	
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
		}
	}
}
?>
