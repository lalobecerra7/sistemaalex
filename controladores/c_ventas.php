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
				$busqueda .= "CONCAT(DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d'), ID_Venta, clientes.Nombre, ventas.Descuento, Total, Tipo_Pago, Notas, clientes.Correo) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}
		
		$query = "SELECT ID_Venta, Facturada, ventas.FK_Usuario, ventas.FK_Sucursal, FK_Caja, FK_Cliente, ventas.Descuento, Total, Tipo_Pago, Pago, Cambio, Notas, ventas.Fecha_Registro, Cancelada, Fecha_Cancelacion, Regreso_Inventario, (SELECT CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) FROM usuarios WHERE ID_Usuario = ventas.FK_Usuario) AS NombreUsuario, clientes.Nombre AS Datos, clientes.Telefono AS Telefono, clientes.Correo AS CorreoCliente, clientes.RFC AS RFCCliente, (SELECT COUNT(*) FROM ventas) AS Num FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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
					$botonEliminar = '<button class="btn btn-danger btn-sm" id="EliminarVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'"><i class="fas fa-trash"></i></button>';

					$botondeCancelar = '<button class="btn btn-warning btn-sm" id="CancelarVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'" sucursal="'.$row[$i]['FK_Sucursal'].'"><i class="fas fa-circle-xmark"></i></button>';

					$botonTicket = '<button class="btn btn-success btn-sm" id="ImprimirTicketVentaSinCaja" attrid="'.$row[$i]['ID_Venta'].'" sucursal="'.$row[$i]['FK_Sucursal'].'" folio="'.$folio.'"><i class="fas fa-print"></i></button>';

					if($row[$i]['Facturada'] == '0'){//agregar permisos
						$botonFacturar = '<button class="btn btn-info btn-sm bFacturar" attrID="'.$row[$i]['ID_Venta'].'" title="Facturar"><i class="fas fa-file-lines"></i></button>';
					}else{
						$botonFacturar = '<button class="btn btn-info btn-sm bImprimirFacPDF" attrID="'.$row[$i]['ID_Venta'].'" title="Factura PDF"><i class="fas fa-file-pdf"></i></button> <button class="btn btn-info btn-sm bImprimirFacXml" attrID="'.$row[$i]['ID_Venta'].'" title="XML"><i class="fas fa-file-excel"></i></button>';
					}

					if ($row[$i]['Cancelada'] == 1) {
						$estatus='<span class="badge rounded-pill bg-danger">Cancelada</span>';
						$motivocancelada = "Motivo de cancelación: ".$row[$i]['Notas'];
						$fechacancelada = '<br>Fecha de cancelación: <b>'.$row[$i]['Fecha_Cancelacion']."</b><br>";
					}else{
						$estatus='<span class="badge rounded-pill bg-success">Completada</span>';
					}

					$SumarVentas += $row[$i]['Total'];

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Venta'],
						'Datos' => "Fecha: <b>".$row[$i]['Fecha_Registro']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b>",
						'Cliente' => 'Nombre: <b>'.$row[$i]['Datos'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Correo electrónico: <b>'.$row[$i]['CorreoCliente'].'</b><br>RFC: <b>'.$row[$i]['RFCCliente']."</b>",
						'Total' => "Subtotal: <b>$".number_format(($row[$i]['Total'] + $row[$i]['Descuento']), 2)."</b><br>Descuento: <b>$".number_format($row[$i]['Descuento'], 2)."</b><br>Total: <b>$".number_format($row[$i]['Total'], 2)."</b>",
						'Detalles' => $estatus."<br>".$motivocancelada.$fechacancelada.'<button class="btn btn-link btn-sm" id="VerProductosVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'">Ver productos</button>',
						'Acciones' => $botonEliminar.' '.$botondeCancelar .' '.$botonTicket.' '.$botonFacturar,
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

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$ImportePago=  $omodelo->link->real_escape_string($ImportePagoCompra);
		$Concepto =  $omodelo->link->real_escape_string($ConceptoPago);
		$TipoPago =  $omodelo->link->real_escape_string($TipoDePago);
		$Detalles =  $omodelo->link->real_escape_string($DetallesPago);
		$IdCompra =  $omodelo->link->real_escape_string($IDCompra);
		$fecha = date('Y-m-d H:i:s'); 
		$usuario = $_SESSION['user_admin']['ID_Usuario'];

		$query = "INSERT INTO pagos SET FK_Compra = '$IdCompra', Monto = '$ImportePago', Concepto = '$Concepto', Tipo_Pago = '$TipoPago', Fecha = '$fecha', FK_Usuario = '$usuario', Detalles_Pago = '$Detalles'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$ID = mysqli_insert_id($omodelo->link);

			$status = 1;
			if ($_FILES['ComprobantePago']['size'] > 0 && $_FILES['ComprobantePago']['error'] == 0) {
				$file = $_FILES["ComprobantePago"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosPagos/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status = 0;
					$ruta = $carpeta;
				}
			}
			
			if($status == 0){
				$query2 = "UPDATE pagos SET Archivo = '".$id.'_'.$nombreDoc."' WHERE ID_Pago = '$ID'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$id.'_'.$nombreDoc);

					$query3 = "SELECT compras.Total AS Total, (SELECT SUM(Monto) FROM pagos WHERE FK_Compra = '$IdCompra') AS TotalPagos FROM compras INNER JOIN pagos ON FK_Compra = ID_Compra WHERE ID_Compra = '$IdCompra'";
					$row3 = $omodelo->_consultar($query3);
					$numerofilas3 = $omodelo->numerofilas;

					if($row3 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas3 > 0){
							if($row3[0]['Total'] <= $row3[0]['TotalPagos']){
								$query4 = "UPDATE compras SET Estatus = '1' WHERE ID_Compra = '$IDCompra'";
								$error4 = $omodelo->_insertar($query4);
								if ($error4 == "si") {
									echo "Error 5: ".mysqli_error($omodelo->link);
								}else{
									echo "Correcto";
									$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
								}
							}
						}
					}
				}
			}
			echo ('Correcto');
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
		$query = "UPDATE ventas SET Cancelada = '1', Notas = '$Motivo', Fecha_Cancelacion = '$fecha' $queryRegresar WHERE ID_Venta = '$IDVenta'";
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
			$query = "SELECT ID_Detalle_Venta, FK_Venta, detalles_ventas.FK_Producto, FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, Descripcion, Precio, Cantidad, Descuento, Total, Devuelto, Fecha_Devolucion, Regreso_Inventario, Clave_Unidad_CFDI, Unidad_CFDI, Objeto_Impuesto_CFDI FROM detalles_ventas LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$IDVenta'";
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
								<td style='vertical-align: middle;'>".$row[$i]["Descuento"]."%</td>
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
		}
	}
}
?>
