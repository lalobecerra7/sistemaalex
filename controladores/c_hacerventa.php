<?php
class hacerventa {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 

		if ($tipo == "GuardarPedido") {
			if (!isset($cliente) || $cliente == "") {
				$cliente = 1;
			}
			$query = "INSERT INTO pedidos SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Sucursal = '$idsucursal', FK_Cliente = '$cliente', FK_Direccion = '$idDireccion', Descuento = '$sumadescuento', Total = '$totalventa', Total_Importes = '$totalfinalimporte', Fecha_Registro = NOW(), Fecha_Entrega = '$fechaEntrega'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$idPedido = mysqli_insert_id($omodelo->link);

				$productos = json_decode($productos, true);
				foreach ($productos as $fila) {

					$nombreProducto = '';
					$query2 = "SELECT Descripcion FROM productos WHERE ID_Producto = '$fila[0]'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$nombreProducto = $row2[0]["Descripcion"];
						}
					}
					$query = "INSERT INTO detalles_pedidos SET FK_Pedido = '$idPedido', FK_Producto = '$fila[0]', FK_Presentacion = '$fila[1]', Descripcion = '$nombreProducto', Precio = '$fila[3]', Cantidad = '$fila[2]', Descuento = '$fila[4]', Total = '$fila[6]', Cobrar_Importe = '$fila[7]', FK_Lote = '$fila[9]'";
					$error = $omodelo->_insertar($query);

					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}

					$idDetallePedido = mysqli_insert_id($omodelo->link);

					$separar = explode("~", $fila[5]);
					for ($i=0; $i < sizeof($separar) - 1; $i++) { 
						$datos = explode(",", $separar[$i]);

						$queryImp = "INSERT INTO detalles_impuestos_pedidos SET FK_Detalle_Pedido = '$idDetallePedido', Tipo_Impuesto_CFDI = '$datos[5]', Impuesto_CFDI = '$datos[1]', Clave_CFDI = '$datos[3]',	Tipo_Factor_CFDI = '$datos[4]', Tasa_Cuota_CFDI = '".($datos[2])."'";
						$errorImp = $omodelo->_insertar($queryImp);

						if ($errorImp == "si") {
							echo "Error impuestos: ".mysqli_error($omodelo->link);
						}

					}
				}
				echo "Correcto";
			}
		}else if ($tipo == "RealizarVenta") {
			if (isset($IDVenta) && $IDVenta != "") { //SI ES MODIFICAR LA VENTA

				//CONSULTAR SI EXISTE UNA VENTA EXACTAMENTE IGUAL
				$fechaActual = "";
				$queryConsultaFecha = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d %H-%i') AS FechaActual";
				$rowConsultaFecha = $omodelo->_consultar($queryConsultaFecha);
				$numerofilas = $omodelo->numerofilas;

				if($rowConsultaFecha == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					$fechaActual = $rowConsultaFecha[0]["FechaActual"];
				}

				if (!isset($cliente) || $cliente == "") { 
					$cliente = 1;
				}

				if ($pagoEfectivo == "") {
					$pagoEfectivo = 0;
				}

				if ($pagoTransferencia == "") {
					$pagoTransferencia = 0;
				}

				if ($pagoCheque == "") {
					$pagoCheque = 0;
				}

				if ($pagoTCredito == "") {
					$pagoTCredito = 0;
				}

				if ($pagoTDebito == "") {
					$pagoTDebito = 0;
				}

				if ($Importe == "" || $Importe == 0) {
					$Importe = $total;
				}else{
					$Importe = floatval($pagoEfectivo) + floatval($pagoTransferencia) + floatval($pagoCheque) + floatval($pagoTCredito) + floatval($pagoTDebito);
				}

				if ($pagoEfectivo == "" && $pagoTransferencia == "" && $pagoCheque == "" && $pagoTCredito == "" && $pagoTDebito == "") {
					$pagoEfectivo = $Importe;
				}

				$cambioAbsoluto = abs($cambio);

				$totalFinalVenta = round($totalventa, 2);
				$ImporteTotal = round($Importe, 2);

				//CONSULTAR SI EXISTE UNA VENTA EXACTAMENTE IGUAL
				$queryConsultaVentaExiste = "SELECT * FROM ventas WHERE FK_Sucursal = '$idsucursal' AND FK_Cliente = '$cliente' AND FK_Direccion = '$idDireccion' AND Descuento = '$sumadescuento' AND Total = '$totalFinalVenta' AND Total_Importes = '$totalfinalimporte' AND Tipo_Pago = '$TipoPago' AND Pago = '$ImporteTotal' AND Cambio = '$cambioAbsoluto' AND DATE_FORMAT(Fecha_Registro, '%Y-%m-%d %H-%i') = '".$fechaActual."' AND Estatus = 'Completada' AND FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' AND Pago_Efectivo = '$pagoEfectivo' AND Pago_Transferencia = '$pagoTransferencia' AND Pago_Cheque = '$pagoCheque' AND Pago_Tarjeta_Credito = '$pagoTCredito' AND Pago_Tarjeta_Debito = '$pagoTDebito'";
				$rowConsultaVentaExiste = $omodelo->_consultar($queryConsultaVentaExiste);
				$numerofilasVentaExiste = $omodelo->numerofilas;

				if($rowConsultaVentaExiste == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilasVentaExiste > 0){
						echo "Duplicada";
					}else{
						
			 			$query = "UPDATE ventas SET FK_Sucursal = '$idsucursal', FK_Cliente = '$cliente', FK_Direccion = '$idDireccion', Descuento = '$sumadescuento', Total = '$totalFinalVenta', Total_Importes = '$totalfinalimporte', Tipo_Pago = '$TipoPago', Pago = '$ImporteTotal', Cambio = '$cambioAbsoluto', Estatus = 'Completada', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', Pago_Efectivo = '$pagoEfectivo', Pago_Transferencia = '$pagoTransferencia', Pago_Cheque = '$pagoCheque', Pago_Tarjeta_Credito = '$pagoTCredito', Pago_Tarjeta_Debito = '$pagoTDebito' WHERE ID_Venta = '$IDVenta'";
						$error = $omodelo->_insertar($query);

						if ($error == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{

							//SI LA VENTA CONTO REGRESAMOS LOS PRODUCTOS AL INVENTARIO
							$queryConsultarDetalle = "SELECT ID_Detalle_Venta, ventas.FK_Sucursal AS IDSucursal, FK_Venta, FK_Producto, FK_Presentacion, Descripcion, Precio, Cantidad, detalles_ventas.Descuento, FK_Token, detalles_ventas.Total, detalles_ventas.Devuelto, detalles_ventas.Fecha_Devolucion,detalles_ventas. Regreso_Inventario, detalles_ventas.Clave_ProdServ_CFDI, detalles_ventas.Identificacion_CFDI, detalles_ventas.Clave_Unidad_CFDI, detalles_ventas.Unidad_CFDI, detalles_ventas.Objeto_Impuesto_CFDI, detalles_ventas.Contar_Venta FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Venta = '$IDVenta' AND detalles_ventas.Contar_Venta = 0";
							$rowConsultarDetalle = $omodelo->_consultar($queryConsultarDetalle);
							$numerofilasConsultarDetalle = $omodelo->numerofilas;

							if($rowConsultarDetalle == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasConsultarDetalle > 0){
									for ($z=0; $z < $numerofilasConsultarDetalle; $z++) { 
										$queryRegresarInventario = "UPDATE inventario SET Cantidad = (Cantidad + '".$rowConsultarDetalle[$z]["Cantidad"]."') WHERE FK_Producto = '$".$rowConsultarDetalle[$z]["FK_Producto"]."' AND FK_Presentacion = '".$rowConsultarDetalle[$z]["FK_Presentacion"]."' AND FK_Sucursal = '$idsucursal'";
										$errorRegresarInventario = $omodelo->_insertar($queryRegresarInventario);

										if ($errorRegresarInventario == "si") {
											echo "Error 2: ".mysqli_error($omodelo->link);
										}
									}
								}
							}

							$queryDetalleVentas = "DELETE FROM detalles_ventas WHERE FK_Venta = '$IDVenta'";
							$errorDetalleVentas = $omodelo->_insertar($queryDetalleVentas);

							if ($errorDetalleVentas == "si") {
								echo "Error Detalle Ventas Eliminar: ".mysqli_error($omodelo->link);
							}else{
								$queryDetalleImportes = "DELETE FROM importes WHERE FK_Venta = '$IDVenta'";
								$DetalleImportes = $omodelo->_insertar($queryDetalleImportes);

								if ($DetalleImportes == "si") {
									echo "Error Importes Eliminar: ".mysqli_error($omodelo->link);
								}

								$queryDetalleVentasPromociones = "DELETE FROM detalles_ventas_promociones WHERE FK_Venta = '$IDVenta'";
								$errorDetalleVentasPromociones = $omodelo->_insertar($queryDetalleVentasPromociones);

								if ($errorDetalleVentasPromociones == "si") {
									echo "Error Detalle Ventas Promociones Eliminar: ".mysqli_error($omodelo->link);
								}

								$productos = json_decode($productos, true);
								foreach ($productos as $fila) {
									$precioProducto = 0;
									$precioProducto = $fila[3];
									$ImporteProducto = 0;

									$nombreProducto = '';
									$query2 = "SELECT Descripcion, importe FROM productos WHERE ID_Producto = '$fila[0]'";
									$row2 = $omodelo->_consultar($query2);
									$numerofilas2 = $omodelo->numerofilas;

									if($row2 == 'si'){
										echo "Error: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilas2 > 0){
											$nombreProducto = $row2[0]["Descripcion"];
										}
									}

									if ($fila[1] == "null") {
										$fila[1] = 0;
									}

									if ($fila[9] != "") {
										$codigo = $fila[9];
										$queryToken = "UPDATE tokens_descuentos SET Activo = 1 WHERE Codigo = '$codigo'";
										$errorToken = $omodelo->_insertar($queryToken);

										if ($errorToken == "si") {
											echo "Error token: ".mysqli_error($omodelo->link);
										}
									}

									$TipoPromocion = $fila[11];
									$IDPromocion =  $fila[12];
									
									$idDetalleVenta = "";
									if ($IDPromocion != '0') {
										if($precioProducto == 0){
											$precioProducto = 0.1;
										}

										$idProductoCombo =  $fila[13];
										$idPresentacionCombo =  $fila[14];
										$idProductoRegalar =  $fila[15];
										$idPresentacionRegalar =  $fila[16];

										if ($TipoPromocion == "CantidadRegalo") {
											$query = "INSERT INTO detalles_ventas_promociones SET 
											FK_Venta = '$IDVenta',
											FK_Producto = '$fila[0]',
											FK_Presentacion = '$idPresentacionRegalar',
											Descripcion = '$nombreProducto',
											Precio = '0',
											Cantidad = '$fila[2]',
											Descuento = '0',
											Total = '0', 
											Cobrar_Importe = '0', 
											Contar_Venta = '$contarVenta',
											FK_Promocion = '$IDPromocion'";
										}else if ($TipoPromocion == "ProductoRegalo") {

											$nombreProductoRegalado = '';
											$queryRegalado = "SELECT Descripcion FROM productos WHERE ID_Producto = '$idProductoRegalar'";
											$rowRegalado = $omodelo->_consultar($queryRegalado);
											$numerofilasRegalado = $omodelo->numerofilas;

											if($rowRegalado == 'si'){
												echo "Error: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasRegalado > 0){
													$nombreProductoRegalado = $rowRegalado[0]["Descripcion"];
												}
											}

											$query = "INSERT INTO detalles_ventas_promociones SET 
											FK_Venta = '$IDVenta',
											FK_Producto = '$idProductoRegalar',
											FK_Presentacion = '$idPresentacionRegalar',
											Descripcion = '$nombreProductoRegalado',
											Precio = '0',
											Cantidad = '$fila[2]',
											Descuento = '0',
											Total = '0', 
											Cobrar_Importe = '0', 
											Contar_Venta = '$contarVenta',
											FK_Promocion = '$IDPromocion'";

										}else if ($TipoPromocion == "ComboProductoRegalo") {

											$nombreProductoRegalado = '';
											$queryRegalado = "SELECT Descripcion FROM productos WHERE ID_Producto = '$idProductoRegalar'";
											$rowRegalado = $omodelo->_consultar($queryRegalado);
											$numerofilasRegalado = $omodelo->numerofilas;

											if($rowRegalado == 'si'){
												echo "Error: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasRegalado > 0){
													$nombreProductoRegalado = $rowRegalado[0]["Descripcion"];
												}
											}

											$query = "INSERT INTO detalles_ventas_promociones SET 
											FK_Venta = '$IDVenta',
											FK_Producto = '$idProductoRegalar',
											FK_Presentacion = '$idPresentacionRegalar',
											Descripcion = '$nombreProductoRegalado',
											Precio = '0',
											Cantidad = '$fila[2]',
											Descuento = '0',
											Total = '0', 
											Cobrar_Importe = '0', 
											Contar_Venta = '$contarVenta',
											FK_Promocion = '$IDPromocion'";
										}
									}else{
										$query = "INSERT INTO detalles_ventas SET 
										FK_Venta = '$IDVenta', 
										FK_Producto = '$fila[0]', 
										FK_Presentacion = '$fila[1]', 
										Descripcion = '$nombreProducto', 
										Precio = '$precioProducto', 
										Cantidad = '$fila[2]', 
										Descuento = '$fila[4]', 
										FK_Token = '$fila[10]', 
										Total = '$fila[6]', 
										Cobrar_Importe = '$fila[7]', 
										Contar_Venta = '$contarVenta',
										FK_Lote = '$fila[17]'";
									}
									
									$error = $omodelo->_insertar($query);

									if ($error == "si") {
										echo "Error 1: ".mysqli_error($omodelo->link);
									}else{
										$precioProducto = 0;
										$idDetalleVenta = mysqli_insert_id($omodelo->link);
									}

									if (!isset($fila[7])) {
										$fila[7] = 0;
									}

									if (!isset($fila[8])) {
										$fila[8] = 0;
									}

									$cantidadImportes = $fila[7];
									$precioImporte = $fila[8];
									$totaDeImporte = doubleval($cantidadImportes) * doubleval($precioImporte);

									if ($totaDeImporte > 0) {
										$queryImportes = "INSERT INTO importes SET FK_Venta = '$IDVenta', FK_Producto = '$fila[0]', FK_Presentacion = '$fila[1]', Cantidad = '$cantidadImportes', Importe = '".$precioImporte."', Total = '$totaDeImporte', Estatus = 'Se debe'";
										$errorImportes = $omodelo->_insertar($queryImportes);

										if ($errorImportes == "si") {
											echo "Error importes: ".mysqli_error($omodelo->link);
										}
									}

									$separar = explode("~", $fila[5]);
									for ($i=0; $i < sizeof($separar) - 1; $i++) { 
										$datos = explode(",", $separar[$i]);

										$queryImp = "INSERT INTO detalles_impuestos_ventas SET FK_Detalle_Venta = '$idDetalleVenta', Tipo_Impuesto_CFDI = '$datos[5]', Impuesto_CFDI = '$datos[1]', Clave_CFDI = '$datos[3]',	Tipo_Factor_CFDI = '$datos[4]', Tasa_Cuota_CFDI = '".($datos[2])."'";
										$errorImp = $omodelo->_insertar($queryImp);

										if ($errorImp == "si") {
											echo "Error impuestos: ".mysqli_error($omodelo->link);
										}

									}
								}
								echo "Correcto~".$IDVenta;
							}
						}
					}
				}
			}else{									 //SI ES GUARDAR UNA NUEVA VENTA
				//CONSULTAR SI EXISTE UNA VENTA EXACTAMENTE IGUAL
				$fechaActual = "";
				$queryConsultaFecha = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d %H-%i') AS FechaActual";
				$rowConsultaFecha = $omodelo->_consultar($queryConsultaFecha);
				$numerofilas = $omodelo->numerofilas;

				if($rowConsultaFecha == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					$fechaActual = $rowConsultaFecha[0]["FechaActual"];
				}

				if (!isset($cliente) || $cliente == "") { 
					$cliente = 1;
				}
				//$cambio = $Importe - $total;

				if ($pagoEfectivo == "") {
					$pagoEfectivo = 0;
				}

				if ($pagoTransferencia == "") {
					$pagoTransferencia = 0;
				}

				if ($pagoCheque == "") {
					$pagoCheque = 0;
				}

				if ($pagoTCredito == "") {
					$pagoTCredito = 0;
				}

				if ($pagoTDebito == "") {
					$pagoTDebito = 0;
				}

				if ($Importe == "" || $Importe == 0) {
					$Importe = $total;
				}else{
					$Importe = floatval($pagoEfectivo) + floatval($pagoTransferencia) + floatval($pagoCheque) + floatval($pagoTCredito) + floatval($pagoTDebito);
				}

				if ($pagoEfectivo == "" && $pagoTransferencia == "" && $pagoCheque == "" && $pagoTCredito == "" && $pagoTDebito == "") {
					$pagoEfectivo = $Importe;
				}

				$cambioAbsoluto = abs($cambio);

				$totalFinalVenta = round($totalventa, 2);
				$ImporteTotal = round($Importe, 2);

				//CONSULTAR SI EXISTE UNA VENTA EXACTAMENTE IGUAL
				$queryConsultaVentaExiste = "SELECT * FROM ventas WHERE FK_Sucursal = '$idsucursal' AND FK_Cliente = '$cliente' AND FK_Direccion = '$idDireccion' AND Descuento = '$sumadescuento' AND Total = '$totalFinalVenta' AND Total_Importes = '$totalfinalimporte' AND Tipo_Pago = '$TipoPago' AND Pago = '$ImporteTotal' AND Cambio = '$cambioAbsoluto' AND DATE_FORMAT(Fecha_Registro, '%Y-%m-%d %H-%i') = '".$fechaActual."' AND Estatus = 'Completada' AND FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' AND Pago_Efectivo = '$pagoEfectivo' AND Pago_Transferencia = '$pagoTransferencia' AND Pago_Cheque = '$pagoCheque' AND Pago_Tarjeta_Credito = '$pagoTCredito' AND Pago_Tarjeta_Debito = '$pagoTDebito'";
				$rowConsultaVentaExiste = $omodelo->_consultar($queryConsultaVentaExiste);
				$numerofilas = $omodelo->numerofilas;

				if($rowConsultaVentaExiste == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						echo "Duplicada";
					}else{

			 			$queryDeVentas = "INSERT INTO ventas SET FK_Sucursal = '$idsucursal', FK_Cliente = '$cliente', FK_Direccion = '$idDireccion', Descuento = '$sumadescuento', Total = '$totalFinalVenta', Total_Importes = '$totalfinalimporte', Tipo_Pago = '$TipoPago', Pago = '$ImporteTotal', Cambio = '$cambioAbsoluto', Fecha_Registro = NOW(), Estatus = 'Completada', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', Pago_Efectivo = '$pagoEfectivo', Pago_Transferencia = '$pagoTransferencia', Pago_Cheque = '$pagoCheque', Pago_Tarjeta_Credito = '$pagoTCredito', Pago_Tarjeta_Debito = '$pagoTDebito'";
						$errorDeVentas = $omodelo->_insertar($queryDeVentas);

						if ($errorDeVentas == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							$idVenta = mysqli_insert_id($omodelo->link);

							$productos = json_decode($productos, true);
							foreach ($productos as $fila) {
								$precioProducto = 0;
								$precioProducto = $fila[3];
								$ImporteProducto = 0;

								$nombreProducto = '';
								$query2 = "SELECT Descripcion, importe FROM productos WHERE ID_Producto = '$fila[0]'";
								$row2 = $omodelo->_consultar($query2);
								$numerofilas2 = $omodelo->numerofilas;

								if($row2 == 'si'){
									echo "Error: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilas2 > 0){
										$nombreProducto = $row2[0]["Descripcion"];
									}
								}

								if ($fila[1] == "null") {
									$fila[1] = 0;
								}

								if ($fila[9] != "") {
									$codigo = $fila[9];
									$queryToken = "UPDATE tokens_descuentos SET Activo = 1 WHERE Codigo = '$codigo'";
									$errorToken = $omodelo->_insertar($queryToken);

									if ($errorToken == "si") {
										echo "Error token: ".mysqli_error($omodelo->link);
									}
								}

								$TipoPromocion = $fila[11];
								$IDPromocion =  $fila[12];
								
								$idDetalleVenta = "";
								if ($IDPromocion != '0') {
									if($precioProducto == 0){
										$precioProducto = 0.1;
									}
									$idProductoCombo =  $fila[13];
									$idPresentacionCombo =  $fila[14];
									$idProductoRegalar =  $fila[15];
									$idPresentacionRegalar =  $fila[16];

									if ($TipoPromocion == "CantidadRegalo") {
										$query = "INSERT INTO detalles_ventas_promociones SET 
										FK_Venta = '$idVenta',
										FK_Producto = '$fila[0]',
										FK_Presentacion = '$idPresentacionRegalar',
										Descripcion = '$nombreProducto',
										Precio = '0',
										Cantidad = '$fila[2]',
										Descuento = '0',
										Total = '0', 
										Cobrar_Importe = '0', 
										Contar_Venta = '$contarVenta',
										FK_Promocion = '$IDPromocion'";
									}else if ($TipoPromocion == "ProductoRegalo") {

										$nombreProductoRegalado = '';
										$queryRegalado = "SELECT Descripcion FROM productos WHERE ID_Producto = '$idProductoRegalar'";
										$rowRegalado = $omodelo->_consultar($queryRegalado);
										$numerofilasRegalado = $omodelo->numerofilas;

										if($rowRegalado == 'si'){
											echo "Error: ".mysqli_error($omodelo->link);
										}else{
											if($numerofilasRegalado > 0){
												$nombreProductoRegalado = $rowRegalado[0]["Descripcion"];
											}
										}

										$query = "INSERT INTO detalles_ventas_promociones SET 
										FK_Venta = '$idVenta',
										FK_Producto = '$idProductoRegalar',
										FK_Presentacion = '$idPresentacionRegalar',
										Descripcion = '$nombreProductoRegalado',
										Precio = '0',
										Cantidad = '$fila[2]',
										Descuento = '0',
										Total = '0', 
										Cobrar_Importe = '0', 
										Contar_Venta = '$contarVenta',
										FK_Promocion = '$IDPromocion'";

									}else if ($TipoPromocion == "ComboProductoRegalo") {

										$nombreProductoRegalado = '';
										$queryRegalado = "SELECT Descripcion FROM productos WHERE ID_Producto = '$idProductoRegalar'";
										$rowRegalado = $omodelo->_consultar($queryRegalado);
										$numerofilasRegalado = $omodelo->numerofilas;

										if($rowRegalado == 'si'){
											echo "Error: ".mysqli_error($omodelo->link);
										}else{
											if($numerofilasRegalado > 0){
												$nombreProductoRegalado = $rowRegalado[0]["Descripcion"];
											}
										}

										$query = "INSERT INTO detalles_ventas_promociones SET 
										FK_Venta = '$idVenta',
										FK_Producto = '$idProductoRegalar',
										FK_Presentacion = '$idPresentacionRegalar',
										Descripcion = '$nombreProductoRegalado',
										Precio = '0',
										Cantidad = '$fila[2]',
										Descuento = '0',
										Total = '0', 
										Cobrar_Importe = '0', 
										Contar_Venta = '$contarVenta',
										FK_Promocion = '$IDPromocion'";
									}

								}else{
									$query = "INSERT INTO detalles_ventas SET 
									FK_Venta = '$idVenta',
									FK_Producto = '$fila[0]',
									FK_Presentacion = '$fila[1]',
									Descripcion = '$nombreProducto',
									Precio = '$precioProducto',
									Cantidad = '$fila[2]',
									Descuento = '$fila[4]',
									Total = '$fila[6]', 
									Cobrar_Importe = '$fila[7]', 
									Contar_Venta = '$contarVenta',
									FK_Lote = '$fila[17]'";
								}

								$error = $omodelo->_insertar($query);

								if ($error == "si") {
									echo "Error 1: ".mysqli_error($omodelo->link);
								}else{
									$precioProducto = 0;

									$idDetalleVenta = mysqli_insert_id($omodelo->link);
								}

								if (!isset($fila[7])) {
									$fila[7] = 0;
								}

								if (!isset($fila[8])) {
									$fila[8] = 0;
								}

								$cantidadImportes = $fila[7];
								$precioImporte = $fila[8];
								$totaDeImporte = doubleval($cantidadImportes) * doubleval($precioImporte);

								if ($totaDeImporte > 0) {
									$queryImportes = "INSERT INTO importes SET FK_Venta = '$idVenta', FK_Producto = '$fila[0]', FK_Presentacion = '$fila[1]', Cantidad = '$cantidadImportes', Importe = '".$precioImporte."', Total = '$totaDeImporte', Estatus = 'Se debe'";
									$errorImportes = $omodelo->_insertar($queryImportes);

									if ($errorImportes == "si") {
										echo "Error importes: ".mysqli_error($omodelo->link);
									}
								}

								$separar = explode("~", $fila[5]);
								for ($i=0; $i < sizeof($separar) - 1; $i++) { 
									$datos = explode(",", $separar[$i]);

									$queryImp = "INSERT INTO detalles_impuestos_ventas SET FK_Detalle_Venta = '$idDetalleVenta', Tipo_Impuesto_CFDI = '$datos[5]', Impuesto_CFDI = '$datos[1]', Clave_CFDI = '$datos[3]',	Tipo_Factor_CFDI = '$datos[4]', Tasa_Cuota_CFDI = '".($datos[2])."'";
									$errorImp = $omodelo->_insertar($queryImp);

									if ($errorImp == "si") {
										echo "Error impuestos: ".mysqli_error($omodelo->link);
									}

								}
							}
							echo "Correcto~".$idVenta;
						}
					}
				}
			}
		}
	}
	
	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST); 
		$fecha = date('Y-m-d H:i:s'); 
		$IDPedido =  $omodelo->link->real_escape_string($IDPedido);
		if ($tipo == "ModificarPedido") {

			if (!isset($cliente) || $cliente == "") {
				$cliente = 1;
			}
			$query = "UPDATE pedidos SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Sucursal = '$idsucursal', FK_Cliente = '$cliente', Descuento = '$sumadescuento', Total = '$total', Fecha_Registro = NOW(), Fecha_Entrega = '$fechaEntrega' WHERE ID_Pedido = '$IDPedido'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{

				$queryEliminar = "DELETE FROM detalles_pedidos WHERE FK_Pedido = '$IDPedido'";
				$errorEliminar = $omodelo->_insertar($queryEliminar);

				if ($errorEliminar == "si") {
					echo "Error Eliminar: ".mysqli_error($omodelo->link);
				}

				$productos = json_decode($productos, true);
				foreach ($productos as $fila) {

					$nombreProducto = '';
					$query2 = "SELECT Descripcion FROM productos WHERE ID_Producto = '$fila[0]'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$nombreProducto = $row2[0]["Descripcion"];
						}
					}
					$query = "INSERT INTO detalles_pedidos SET FK_Pedido = '$IDPedido', FK_Producto = '$fila[0]', FK_Presentacion = '$fila[1]', Descripcion = '$nombreProducto', Precio = '$fila[3]', Cantidad = '$fila[2]', Descuento = '$fila[4]', Total = '$fila[6]', Cobrar_Importe = '$fila[7]'";
					$error = $omodelo->_insertar($query);

					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}

					$idDetallePedido = mysqli_insert_id($omodelo->link);

					$separar = explode("~", $fila[5]);
					for ($i=0; $i < sizeof($separar) - 1; $i++) { 
						$datos = explode(",", $separar[$i]);

						$queryImp = "INSERT INTO detalles_impuestos_pedidos SET FK_Detalle_Pedido = '$idDetallePedido', Tipo_Impuesto_CFDI = '$datos[5]', Impuesto_CFDI = '$datos[1]', Clave_CFDI = '$datos[3]',	Tipo_Factor_CFDI = '$datos[4]', Tasa_Cuota_CFDI = '".($datos[2])."'";
						$errorImp = $omodelo->_insertar($queryImp);

						if ($errorImp == "si") {
							echo "Error impuestos: ".mysqli_error($omodelo->link);
						}

					}
				}
				echo "Correcto";
			}
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$IDPedido = $omodelo->link->real_escape_string($IDPedido);

		$query = "DELETE FROM pedidos WHERE ID_Pedido = '$IDPedido'";
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

		if ($tipo == "ConsultarCliente") {
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
					$busqueda .= "CONCAT(Nombre, Primer_Apellido, Segundo_Apellido, Calle, No_Exterior, No_Interior, Colonia, Ciudad, Codigo_Postal, Estado, Pais, Telefono, Celular, Correo, RFC) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Cliente, Nombre, Primer_Apellido, Segundo_Apellido, Calle AS Direccion, No_Exterior, No_Interior, Colonia, Ciudad, Codigo_Postal, Estado, Pais, Telefono, Celular, Correo AS Contacto, RFC, Facturar, (SELECT COUNT(*) FROM clientes INNER JOIN detalles_clientes_sucursal ON FK_Cliente = ID_Cliente WHERE detalles_clientes_sucursal.FK_Sucursal = '$sucursal' $busqueda) AS Num FROM clientes INNER JOIN detalles_clientes_sucursal ON FK_Cliente = ID_Cliente WHERE detalles_clientes_sucursal.FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarCompras = 0;
					for($i=0; $i<$numerofilas; $i++){
						$contacto = "";$direccion=""; $facturar = "No";
						
						if ($row[$i]["Direccion"] != "") {
							$direccion.="Calle: ".$row[$i]["Direccion"]."<br>";
						}

						if ($row[$i]["No_Exterior"] != "") {
							$direccion.="No. Exterior: ".$row[$i]["No_Exterior"]."<br>";
						}

						if ($row[$i]["No_Interior"] != "") {
							$direccion.="No. Interior: ".$row[$i]["No_Interior"]."<br>";
						}

						if ($row[$i]["Colonia"] != "") {
							$direccion.="Colonia: ".$row[$i]["Colonia"]."<br>";
						}

						if ($row[$i]["Ciudad"] != "") {
							$direccion.="Ciudad: ".$row[$i]["Ciudad"]."<br>";
						}

						if ($row[$i]["Codigo_Postal"] != "") {
							$direccion.="Codigo postal: ".$row[$i]["Codigo_Postal"]."<br>";
						}

						if ($row[$i]["Estado"] != "") {
							$direccion.="Estado: ".$row[$i]["Estado"]."<br>";
						}

						if ($row[$i]["Pais"] != "") {
							$direccion.="Pais: ".$row[$i]["Pais"]."<br>";
						}

						if ($direccion == "") {
							$direccion = "No hay datos registrados";
						}

						if ($row[$i]["Telefono"] != "") {
							$contacto.="Teléfono: ".$row[$i]["Telefono"]."<br>";
						}

						if ($row[$i]["Celular"] != "") {
							$contacto.="Celular: ".$row[$i]["Celular"]."<br>";
						}

						if ($row[$i]["Contacto"] != "") {
							$contacto.="Correo electrónico: ".$row[$i]["Contacto"]."<br>";
						}

						if ($contacto == "") {
							$contacto = "No hay datos registrados";
						}

						if ($row[$i]["Facturar"] == "1") {
							$facturar = "Si";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Cliente'],
							'Nombre' => $row[$i]['Nombre'].' '.$row[$i]["Primer_Apellido"].' '.$row[$i]["Segundo_Apellido"],
							'Direccion' => $direccion,
							'RFC' => $row[$i]['RFC'],
							'Contacto' => $contacto,
							'Facturar' => $facturar
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]["Num"]);
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarDireccionCliente") {
			$idCliente =  $omodelo->link->real_escape_string($idCliente);
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
					$busqueda .= "CONCAT(Calle, No_Exterior, No_Interior, Colonia, Ciudad, Codigo_Postal, Estado, Pais) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Cliente, Calle AS Domicilio, No_Exterior, No_Interior, Colonia, Ciudad AS Ubicacion, Codigo_Postal, Estado, Pais, (SELECT COUNT(*) FROM clientes WHERE ID_Cliente = '$idCliente' $busqueda) AS Num FROM clientes WHERE ID_Cliente = '$idCliente' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarCompras = 0;
					for($i=0; $i<$numerofilas; $i++){
						$direccion=""; $ubicacion = ""; $colonia = ""; $idDireccion ="0";
						
						if ($row[$i]["Domicilio"] != "") {
							$direccion.="Calle: ".$row[$i]["Domicilio"]."<br>";
						}

						if ($row[$i]["No_Exterior"] != "") {
							$direccion.="No. Exterior: ".$row[$i]["No_Exterior"]."<br>";
						}

						if ($row[$i]["No_Interior"] != "") {
							$direccion.="No. Interior: ".$row[$i]["No_Interior"]."<br>";
						}

						if ($row[$i]["Colonia"] != "") {
							$colonia.="Colonia: ".$row[$i]["Colonia"]."<br>";
						}

						if ($row[$i]["Codigo_Postal"] != "") {
							$colonia.="Codigo postal: ".$row[$i]["Codigo_Postal"]."<br>";
						}

						if ($row[$i]["Ubicacion"] != "") {
							$ubicacion.="Ciudad: ".$row[$i]["Ubicacion"]."<br>";
						}

						if ($row[$i]["Estado"] != "") {
							$ubicacion.="Estado: ".$row[$i]["Estado"]."<br>";
						}

						if ($row[$i]["Pais"] != "") {
							$ubicacion.="Pais: ".$row[$i]["Pais"]."<br>";
						}

						if ($direccion == "") {
							 $idDireccion ="No";
							$direccion = "No hay datos registrados";
						}

						if ($colonia == "") {
							$colonia = "No hay datos registrados";
						}

						if ($ubicacion == "") {
							$ubicacion = "No hay datos registrados";
						}

						$arreglo['data'][] = array(
							'ID' => $idDireccion,
							'Domicilio' => $direccion,
							'Colonia' => $colonia,
							'Ubicación' => $ubicacion,
						);
					}

					
				}
			}

			$query2 = "SELECT ID_Detalle_Cliente, FK_Cliente AS IDCliente, Calle AS Domicilio, No_Exterior, No_Interior, Colonia, Ciudad AS Ubicacion, Codigo_Postal, Estado, Pais, (SELECT COUNT(*) FROM detalles_clientes WHERE FK_Cliente = '$idCliente' $busqueda) AS Num FROM detalles_clientes WHERE FK_Cliente = '$idCliente' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row2 = $omodelo->_consultar($query2);
			$numerofilas = $omodelo->numerofilas;

			if($row2 == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarCompras = 0;
					for($x=0; $x<$numerofilas; $x++){
						$direccion=""; $ubicacion = ""; $colonia = ""; $idDireccion =$row2[$x]["ID_Detalle_Cliente"];
						
						if ($row2[$x]["Domicilio"] != "") {
							$direccion.="Calle: ".$row2[$x]["Domicilio"]."<br>";
						}

						if ($row2[$x]["No_Exterior"] != "") {
							$direccion.="No. Exterior: ".$row2[$x]["No_Exterior"]."<br>";
						}

						if ($row2[$x]["No_Interior"] != "") {
							$direccion.="No. Interior: ".$row2[$x]["No_Interior"]."<br>";
						}

						if ($row2[$x]["Colonia"] != "") {
							$colonia.="Colonia: ".$row2[$x]["Colonia"]."<br>";
						}

						if ($row2[$x]["Codigo_Postal"] != "") {
							$colonia.="Codigo postal: ".$row2[$x]["Codigo_Postal"]."<br>";
						}

						if ($row2[$x]["Ubicacion"] != "") {
							$ubicacion.="Ciudad: ".$row2[$x]["Ubicacion"]."<br>";
						}

						if ($row2[$x]["Estado"] != "") {
							$ubicacion .="Estado: ".$row2[$x]["Estado"]."<br>";
						}

						if ($row2[$x]["Pais"] != "") {
							$ubicacion .="Pais: ".$row2[$x]["Pais"]."<br>";
						}

						if ($direccion == "") {
							$direccion = "No hay datos registrados";
						 	$idDireccion ="No";
						}

						if ($colonia == "") {
							$colonia = "No hay datos registrados";
						}

						if ($ubicacion == "") {
							$ubicacion = "No hay datos registrados";
						}

						$arreglo['data'][] = array(
							'ID' => $row2[$x]["ID_Detalle_Cliente"],
							'Domicilio' => $direccion,
							'Colonia' => $colonia,
							'Ubicación' => $ubicacion,
						);
					}
				}
			}

			$arreglo['totales'] = array('NumRows' => ($row[0]["Num"]));

			//
			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarProductos") {
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
					$busqueda .= "CONCAT(ID_Producto, productos.Codigo, productos.Descripcion) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query2 = "SELECT ID_Producto, IFNULL(presentaciones.Codigo, productos.Codigo) AS CodigoFinal,  productos.Codigo, productos.Descripcion AS Descripcion,areas.Nombre AS NombreArea, IFNULL(ID_Presentacion,0) AS ID_Presentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, productos.Nombre_Unidad AS NombreGenerico, productos.Abreviatura_Unidad AS AbreviaturaGenerico,  productos.Costo AS Costo_General, 

				productos.Precio AS Precio_General, 				
				productos.Precio_Mayoreo AS Mayoreo, 

				(SELECT ID_Precio FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = ID_Producto AND FK_Presentacion = IFNULL(ID_Presentacion,0) AND ID_Sucursal = '$sucursal' AND precios.Nombre = 'Precio 3' LIMIT 1) AS IDPrecioProducto,

				IFNULL((SELECT Precio FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE ID_Precio = IDPrecioProducto AND ID_Sucursal = '$sucursal'),0) AS PrecioPresentacion,

				IFNULL((SELECT Precio_Bruto FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona  WHERE ID_Precio = IDPrecioProducto AND ID_Sucursal = '$sucursal'),0) AS PrecioBruto,

				(SELECT precios.Nombre FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE ID_Precio = IDPrecioProducto AND ID_Sucursal = '$sucursal') AS NombrePrecio,

				(SELECT sucursales.Nombre FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE ID_Precio = IDPrecioProducto AND ID_Sucursal = '$sucursal') AS NombreSucursal,


				


				(SELECT ID_Precio FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = ID_Producto AND FK_Presentacion = IFNULL(ID_Presentacion,0) AND ID_Sucursal = '$sucursal' LIMIT 1) AS IDPrecioProductoAlterno,

				IFNULL((SELECT Precio FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE ID_Precio = IDPrecioProductoAlterno AND ID_Sucursal = '$sucursal'), 0) AS PrecioPresentacionAlterno,

				IFNULL((SELECT Precio_Bruto FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona  WHERE ID_Precio = IDPrecioProductoAlterno AND ID_Sucursal = '$sucursal'), 0) AS PrecioBrutoAlterno,

				(SELECT precios.Nombre FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE ID_Precio = IDPrecioProductoAlterno AND ID_Sucursal = '$sucursal') AS NombrePrecioAlterno,

				(SELECT sucursales.Nombre FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE ID_Precio = IDPrecioProductoAlterno AND ID_Sucursal = '$sucursal') AS NombreSucursalAlterno,



			 	(SELECT Precio_Mayoreo FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = IFNULL(ID_Presentacion,0) ORDER BY Nombre LIMIT 1) AS PrecioMayPresentacion, 
			 	
			 	Detalles, Fecha_Registro, inventario.Cantidad AS Existencia, (SELECT COUNT(*) FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON inventario.FK_Presentacion = IFNULL(ID_Presentacion,0) LEFT JOIN areas ON FK_Area = ID_Area WHERE FK_Sucursal = '$sucursal' $busqueda) AS Num 
				
				FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON inventario.FK_Presentacion = IFNULL(ID_Presentacion,0) LEFT JOIN areas ON FK_Area = ID_Area WHERE FK_Sucursal = '$sucursal' AND productos.Bloqueado = 0 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row2 = $omodelo->_consultar($query2);
			$numerofilas2 = $omodelo->numerofilas;

			if($row2 == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas2 > 0){
					for($x=0; $x<$numerofilas2; $x++){

						if ($row2[$x]['Existencia'] == "") {
							$row2[$x]['Existencia'] = 0;
						}

						if ($row2[$x]['NombreArea'] == "") {
							$row2[$x]['NombreArea'] = "No hay area registrada";
						}

						$nombrePresentacion = "";
						if ($row2[$x]['Presentacion'] != "") {
							$nombrePresentacion = $row2[$x]['Presentacion']." (".$row2[$x]['AbreviaturaPresentacion'].")";
						}else{
							if ($row2[$x]['NombreGenerico'] == "") {
								$nombrePresentacion = "Sin presentación";
							}else{
								$nombrePresentacion = $row2[$x]['NombreGenerico']." (".$row2[$x]['AbreviaturaGenerico'].")";
							}
						}

						$precio = 0;

						$NombrePrecio = 'General';
						if($row2[$x]['PrecioPresentacion'] > 0){
							$precio = $row2[$x]['PrecioPresentacion'];
							$NombrePrecio = $row2[$x]['NombrePrecio'];
						}else if($row2[$x]['PrecioPresentacionAlterno'] > 0){
							$precio = $row2[$x]['PrecioPresentacionAlterno'];
							$NombrePrecio = $row2[$x]['NombrePrecioAlterno'];
						}else{
							$NombrePrecio = 'General';
						}

						
						$precioBruto = 0;
						/*if ($row2[$x]['PrecioBruto'] != "" && $row2[$x]['PrecioBruto'] > 0) {
							$precioBruto = $row2[$x]['PrecioBruto'];
						}else */
						if($row2[$x]['PrecioBrutoAlterno'] != "" && $row2[$x]['PrecioBrutoAlterno'] > 0){
							$precioBruto = $row2[$x]['PrecioBrutoAlterno'];
						}

						$precioMayoreo = 0;

						if($NombrePrecio != "General"){
							$arreglo['data'][] = array(
								'ID' => $row2[$x]['ID_Producto'],
								'Descripcion' => $row2[$x]['Descripcion']."<br>Codigo: <b id='CodigoProducto'>".$row2[$x]['CodigoFinal']."</b>",
								'Presentacion' =>"<span hidden id='IdPresentacionProd'>".$row2[$x]['ID_Presentacion']."</span>".$nombrePresentacion,
								'Nombre' => $NombrePrecio,
								'Precio' => $precio,
								'PrecioBruto' => $precioBruto,
								'Existencia' => $row2[$x]['Existencia'],
							);
						}
						
						
					}

					$arreglo['totales'] = array('NumRows' => $row2[0]["Num"]);	
				}
			}
			
			echo json_encode($arreglo);
		}else if($tipo == "AgregarProducto"){
			if (!isset($presentacion) || $presentacion == "") {
				$presentacion = 0;
			}

			//CONSULTAR POR CODIGO DE PRESENTACION
			$query = "SELECT 
				ID_Producto, 
				presentaciones.Codigo, 
				productos.Descripcion AS Descripcion, 
				IFNULL(ID_Presentacion,0) AS IDPresentacion, 
				presentaciones.Nombre AS Presentacion, 
				presentaciones.Abreviatura AS AbreviaturaPresentacion, 
				productos.Nombre_Unidad AS NombreGenerico, 
				productos.Abreviatura_Unidad AS AbreviaturaGenerico, 
				productos.Costo AS Costo_General, 
				productos.Precio AS Precio_General, 
				IFNULL(productos.importe, 0) AS ImporteGeneral, 
				IFNULL(presentaciones.Importe, 0) AS ImportePresentacion, 
				productos.Precio_Mayoreo AS Precio_Mayoreo_General, 
				precios.Nombre AS NombrePrecio, 
				precios.Precio AS PrecioPresentacion, 
				precios.Precio_Mayoreo AS PrecioMayPresentacion, 
				areas.Nombre AS NombreArea, 
				Detalles, 
				Fecha_Registro, 
				inventario.Cantidad AS Existencia, 
				(SELECT Precio FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = IFNULL(ID_Presentacion,0) AND Nombre = 'Precio 3' GROUP BY Nombre) AS Precio3Presentacion 
			FROM productos LEFT JOIN areas ON FK_Area = ID_Area INNER JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' LEFT JOIN presentaciones ON inventario.FK_Presentacion = IFNULL(ID_Presentacion,0) LEFT JOIN precios ON precios.FK_Presentacion = IFNULL(ID_Presentacion,0) WHERE presentaciones.Codigo = '$codigo' AND productos.Bloqueado = 0 AND presentaciones.ID_Presentacion = '$presentacion'"; //AND inventario.FK_Presentacion = '$presentacion'
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$subarreglo = null;
					// echo "1";
					$campoImpuestos = "";
					$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre AS Nombre, impuestos.Porcentaje AS Porcentaje, impuestos.Clave_CFDI AS Clave_CFDI, impuestos.Tipo_Factor AS Tipo_Factor, impuestos.Clase AS Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row[0]["ID_Producto"]."'";
					$rowI = $omodelo->_consultar($queryI);
					$numerofilasI = $omodelo->numerofilas;
					if($rowI == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasI > 0){
							for ($i=0; $i < $numerofilasI; $i++) { 
								$campoImpuestos .= '
									<div class="form-check impuesto">
										<input class="form-check-input seleccionarImpuesto oculto" checked type="checkbox" nombre="'.$rowI[$i]["Nombre"].'" porcentaje="'.$rowI[$i]["Porcentaje"].'" attrid="'.$rowI[$i]["FK_Impuesto"].'" clavecfdi="'.$rowI[$i]["Clave_CFDI"].'" tipofactor="'.$rowI[$i]["Tipo_Factor"].'" clase="'.$rowI[$i]["Clase"].'">
										<label class="form-check-label" for="flexCheckDefault">
											'.$rowI[$i]["Nombre"].' ('.$rowI[$i]["Porcentaje"].'%)
										</label>
									</div>
								';
							}
						}
					}		
					
					$PrecioPresentacion = 0;
					$PrecioBruto = 0;
					$NombrePrecio = 0;
					$NombreSucursal = 0;

					//CONSULTAR PRECIO 3 (PRECIO MAS BAJO)
					$idPrecio = 0;
					$queryPrecioID = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre = 'Precio 3'";
					$rowP = $omodelo->_consultar($queryPrecioID);
					//echo "1".$queryPrecioID;
					$numerofilasP = $omodelo->numerofilas;

					if($rowP == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasP > 0){
							$PrecioPresentacion = $rowP[0]["Precio"];
							$PrecioBruto = $rowP[0]["Precio_Bruto"];
							$NombrePrecio = $rowP[0]["NombrePrecio"];
							$NombreSucursal = $rowP[0]["NombreSucursal"];
						}else{
							//SI NO TIENE PRECIO 3 CONSULTAMOS OTRO PRECIO
							$queryPrecioOtro = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre != 'Precio 3' LIMIT 1";
							$rowPrecioOtro = $omodelo->_consultar($queryPrecioOtro);
							//echo "1".$queryPrecio3;
							$numerofilasPrecioOtro = $omodelo->numerofilas;

							if($rowPrecioOtro == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasPrecioOtro > 0){
									$PrecioPresentacion = $rowPrecioOtro[0]["Precio"];
									$PrecioBruto = $rowPrecioOtro[0]["Precio_Bruto"];
									$NombrePrecio = $rowPrecioOtro[0]["NombrePrecio"];
									$NombreSucursal = $rowPrecioOtro[0]["NombreSucursal"];
								}
							}
						}
					}

					$NombrePresentacion = "";
					if ($row[0]['Presentacion'] != "") {
						$NombrePresentacion = $row[0]['Presentacion']." (".$row[0]['AbreviaturaPresentacion'].")";
					}else{
						if ($row[0]['NombreGenerico'] == "") {
							$NombrePresentacion = "Sin presentación";
						}else{
							$NombrePresentacion = $row[0]['NombreGenerico']." (".$row[0]['AbreviaturaGenerico'].")";
						}
					}

					$precio = $PrecioPresentacion;
					$precioBruto = $PrecioBruto;
					$NombrePrecio = $NombrePrecio;
					
					if($precio == 0){
						$precio = $row[0]['Precio_General'];
					}

					$precioMayoreo = $row[0]['Precio_Mayoreo_General'];

					$arreglo = array(
						'ID_Producto' => $row[0]["ID_Producto"],
						'Codigo' => $row[0]["Codigo"],
						'Descripcion' => $row[0]["Descripcion"],
						'Presentacion' => $NombrePresentacion,
						'IDPresentacion' => $row[0]["IDPresentacion"],
						'Costo_General' => $row[0]["Costo_General"],
						'Nombre_Precio' => $NombrePrecio,
						'Precio_General' => $precio,
						'Precio_Bruto' => $precioBruto,
						'Precio_Mayoreo_General' => $precioMayoreo,
						'NombreArea' => $row[0]["NombreArea"],
						'Detalles' => $row[0]["Detalles"],
						'Fecha_Registro' => $row[0]["Fecha_Registro"],
						'Existencia' => $row[0]["Existencia"],
						'ImporteGeneral' => $row[0]["ImporteGeneral"],
						'ImportePresentacion' => $row[0]["ImportePresentacion"],
						'Impuestos' => $campoImpuestos
					);
					echo json_encode($arreglo);
				}else{

					//CONSULTAR POR CODIGO DEL PRODUCTO
					$query = "SELECT ID_Producto, productos.Codigo, productos.Descripcion AS Descripcion, IFNULL(ID_Presentacion,0) AS IDPresentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, productos.Nombre_Unidad AS NombreGenerico, productos.Abreviatura_Unidad AS AbreviaturaGenerico, productos.Costo AS Costo_General, productos.Precio AS Precio_General, IFNULL(productos.importe, 0) AS ImporteGeneral, IFNULL(presentaciones.Importe, 0) AS ImportePresentacion, productos.Precio_Mayoreo AS Precio_Mayoreo_General, precios.Nombre AS NombrePrecio, precios.Precio AS PrecioPresentacion, precios.Precio_Bruto AS PrecioBruto, precios.Precio_Mayoreo AS PrecioMayPresentacion, areas.Nombre AS NombreArea, Detalles, Fecha_Registro, inventario.Cantidad AS Existencia, (SELECT Precio FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = IFNULL(ID_Presentacion,0) AND Nombre = 'Precio 3' GROUP BY Nombre) AS Precio3Presentacion FROM productos LEFT JOIN areas ON FK_Area = ID_Area INNER JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' LEFT JOIN presentaciones ON inventario.FK_Presentacion = IFNULL(ID_Presentacion,0) LEFT JOIN precios ON precios.FK_Presentacion = IFNULL(ID_Presentacion,0) WHERE productos.Codigo = '$codigo' AND inventario.FK_Presentacion = '$presentacion' AND productos.Bloqueado = 0";
					$row = $omodelo->_consultar($query);
					$numerofilas = $omodelo->numerofilas;

					if($row == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							// echo "2";
							$subarreglo = null;
							$campoImpuestos = "";
							$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre AS Nombre, impuestos.Porcentaje AS Porcentaje, impuestos.Clave_CFDI AS Clave_CFDI, impuestos.Tipo_Factor AS Tipo_Factor, impuestos.Clase AS Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row[0]["ID_Producto"]."'";
							$rowI = $omodelo->_consultar($queryI);
							$numerofilasI = $omodelo->numerofilas;
							if($rowI == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasI > 0){
									for ($i=0; $i < $numerofilasI; $i++) { 
										$campoImpuestos .= '
											<div class="form-check impuesto">
												<input class="form-check-input seleccionarImpuesto oculto" checked type="checkbox" nombre="'.$rowI[$i]["Nombre"].'" porcentaje="'.$rowI[$i]["Porcentaje"].'" attrid="'.$rowI[$i]["FK_Impuesto"].'" clavecfdi="'.$rowI[$i]["Clave_CFDI"].'" tipofactor="'.$rowI[$i]["Tipo_Factor"].'" clase="'.$rowI[$i]["Clase"].'">
												<label class="form-check-label" for="flexCheckDefault">
													'.$rowI[$i]["Nombre"].' ('.$rowI[$i]["Porcentaje"].'%)
												</label>
											</div>
										';
									}
								}
							}				

							$PrecioPresentacion = 0;
							$PrecioBruto = 0;
							$NombrePrecio = 0;
							$NombreSucursal = 0;

							//CONSULTAR PRECIO 3 (PRECIO MAS BAJO)
							$idPrecio = 0;
							$queryPrecioID = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre = 'Precio 3'";
							$rowP = $omodelo->_consultar($queryPrecioID);
							//echo "1".$queryPrecioID;
							$numerofilasP = $omodelo->numerofilas;

							if($rowP == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilasP > 0){
									$PrecioPresentacion = $rowP[0]["Precio"];
									$PrecioBruto = $rowP[0]["Precio_Bruto"];
									$NombrePrecio = $rowP[0]["NombrePrecio"];
									$NombreSucursal = $rowP[0]["NombreSucursal"];
								}else{
									
									//SI NO TIENE PRECIO 3 CONSULTAMOS OTRO PRECIO
									$queryPrecioOtro = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre != 'Precio 3' LIMIT 1";
									$rowPrecioOtro = $omodelo->_consultar($queryPrecioOtro);
									//echo "1".$queryPrecio3;
									$numerofilasPrecioOtro = $omodelo->numerofilas;

									if($rowPrecioOtro == 'si'){
										echo "Error: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasPrecioOtro > 0){
											$PrecioPresentacion = $rowPrecioOtro[0]["Precio"];
											$PrecioBruto = $rowPrecioOtro[0]["Precio_Bruto"];
											$NombrePrecio = $rowPrecioOtro[0]["NombrePrecio"];
											$NombreSucursal = $rowPrecioOtro[0]["NombreSucursal"];
										}
									}
								}
							}

							$NombrePresentacion = "";
							if ($row[0]['Presentacion'] != "") {
								$NombrePresentacion = $row[0]['Presentacion']." (".$row[0]['AbreviaturaPresentacion'].")";
							}else{
								if ($row[0]['NombreGenerico'] == "") {
									$NombrePresentacion = "Sin presentación";
								}else{
									$NombrePresentacion = $row[0]['NombreGenerico']." (".$row[0]['AbreviaturaGenerico'].")";
								}
							}

							$precio = $PrecioPresentacion;
							$precioBruto = $PrecioBruto;
							$NombrePrecio = $NombrePrecio;

							/*$precio = 0; 

							//echo $Precio3Presentacion." y ".$row[0]["PrecioPresentacion"]." y ".$PrecioPresentacionAlterno." y ".$row[0]["Precio_General"];

							if ($Precio3Presentacion != "0") {
								$precio = $Precio3Presentacion;
							}else if ($row[0]["PrecioPresentacion"] != "") {
								$precio = $row[0]["PrecioPresentacion"];
							}else if ($PrecioPresentacionAlterno != "0") {
								$precio = $PrecioPresentacionAlterno;
							}else{
								$precio = $row[0]["Precio_General"];	
							}

							$precioBruto = 0;
							if ($PrecioBruto3Presentacion != "0") {
								$precioBruto = $PrecioBruto3Presentacion;
							}else if ($row[0]["PrecioBruto"] != "") {
								$precioBruto = $row[0]["PrecioBruto"];
							}else if ($PrecioBrutoAlterno != "0") {
								$precioBruto = $PrecioBrutoAlterno;
							}else{
								$precioBruto = $row[0]["Precio_General"];	
							}*/

							$precioMayoreo = $row[0]["Precio_Mayoreo_General"];
							
							$arreglo = array(
								'ID_Producto' => $row[0]["ID_Producto"],
								'Codigo' => $row[0]["Codigo"],
								'Descripcion' => $row[0]["Descripcion"],
								'Presentacion' => $NombrePresentacion,
								'IDPresentacion' => $row[0]["IDPresentacion"],
								'Costo_General' => $row[0]["Costo_General"],
								'Nombre_Precio' => $NombrePrecio,
								'Precio_General' => $precio,
								'Precio_Bruto' => $precioBruto,
								'Precio_Mayoreo_General' => $precioMayoreo,
								'NombreArea' => $row[0]["NombreArea"],
								'Detalles' => $row[0]["Detalles"],
								'Fecha_Registro' => $row[0]["Fecha_Registro"],
								'Existencia' => $row[0]["Existencia"],
								'ImporteGeneral' => $row[0]["ImporteGeneral"],
								'ImportePresentacion' => $row[0]["ImportePresentacion"],
								'Impuestos' => $campoImpuestos
							);
							echo json_encode($arreglo);
						}else{
							$query = "SELECT ID_Producto, productos.Codigo, productos.Descripcion AS Descripcion, IFNULL(ID_Presentacion,0) AS IDPresentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, productos.Nombre_Unidad AS NombreGenerico, productos.Abreviatura_Unidad AS AbreviaturaGenerico, productos.Costo AS Costo_General, productos.Precio AS Precio_General, IFNULL(productos.importe, 0) AS ImporteGeneral, IFNULL(presentaciones.Importe, 0) AS ImportePresentacion, productos.Precio_Mayoreo AS Precio_Mayoreo_General, precios.Nombre AS NombrePrecio, precios.Precio AS PrecioPresentacion, precios.Precio_Bruto AS PrecioBruto, precios.Precio_Mayoreo AS PrecioMayPresentacion, areas.Nombre AS NombreArea, Detalles, Fecha_Registro, inventario.Cantidad AS Existencia, (SELECT Precio FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = IFNULL(ID_Presentacion,0) AND Nombre = 'Precio 3' GROUP BY Nombre) AS Precio3Presentacion FROM productos LEFT JOIN areas ON FK_Area = ID_Area INNER JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' LEFT JOIN presentaciones ON inventario.FK_Presentacion = IFNULL(ID_Presentacion,0) LEFT JOIN precios ON precios.FK_Presentacion = IFNULL(ID_Presentacion,0) WHERE productos.Codigo = '$codigo' AND productos.Bloqueado = 0";
							$row = $omodelo->_consultar($query);
							$numerofilas = $omodelo->numerofilas;

							if($row == 'si'){
								echo "Error: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas > 0){
									// echo "3";
									$subarreglo = null;
									$campoImpuestos = "";
									$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre AS Nombre, impuestos.Porcentaje AS Porcentaje, impuestos.Clave_CFDI AS Clave_CFDI, impuestos.Tipo_Factor AS Tipo_Factor, impuestos.Clase AS Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row[0]["ID_Producto"]."'";
									$rowI = $omodelo->_consultar($queryI);
									$numerofilasI = $omodelo->numerofilas;
									if($rowI == 'si'){
										echo "Error: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasI > 0){
											for ($i=0; $i < $numerofilasI; $i++) { 
												$campoImpuestos .= '
													<div class="form-check impuesto">
														<input class="form-check-input seleccionarImpuesto oculto" checked type="checkbox" nombre="'.$rowI[$i]["Nombre"].'" porcentaje="'.$rowI[$i]["Porcentaje"].'" attrid="'.$rowI[$i]["FK_Impuesto"].'" clavecfdi="'.$rowI[$i]["Clave_CFDI"].'" tipofactor="'.$rowI[$i]["Tipo_Factor"].'" clase="'.$rowI[$i]["Clase"].'">
														<label class="form-check-label" for="flexCheckDefault">
															'.$rowI[$i]["Nombre"].' ('.$rowI[$i]["Porcentaje"].'%)
														</label>
													</div>
												';
											}
										}
									}				

									$PrecioPresentacion = 0;
									$PrecioBruto = 0;
									$NombrePrecio = 0;
									$NombreSucursal = 0;

									//CONSULTAR PRECIO 3 (PRECIO MAS BAJO)
									$idPrecio = 0;
									$queryPrecioID = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre = 'Precio 3'";
									$rowP = $omodelo->_consultar($queryPrecioID);
									//echo "1".$queryPrecioID;
									$numerofilasP = $omodelo->numerofilas;

									if($rowP == 'si'){
										echo "Error: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasP > 0){
											$PrecioPresentacion = $rowP[0]["Precio"];
											$PrecioBruto = $rowP[0]["Precio_Bruto"];
											$NombrePrecio = $rowP[0]["NombrePrecio"];
											$NombreSucursal = $rowP[0]["NombreSucursal"];
										}else{
											//SI NO TIENE PRECIO 3 CONSULTAMOS OTRO PRECIO
											$queryPrecioOtro = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre != 'Precio 3' LIMIT 1";
											$rowPrecioOtro = $omodelo->_consultar($queryPrecioOtro);
											//echo "1".$queryPrecio3;
											$numerofilasPrecioOtro = $omodelo->numerofilas;

											if($rowPrecioOtro == 'si'){
												echo "Error: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasPrecioOtro > 0){
													$PrecioPresentacion = $rowPrecioOtro[0]["Precio"];
													$PrecioBruto = $rowPrecioOtro[0]["Precio_Bruto"];
													$NombrePrecio = $rowPrecioOtro[0]["NombrePrecio"];
													$NombreSucursal = $rowPrecioOtro[0]["NombreSucursal"];
												}
											}
										}
									}

									$NombrePresentacion = "";
									if ($row[0]['Presentacion'] != "") {
										$NombrePresentacion = $row[0]['Presentacion']." (".$row[0]['AbreviaturaPresentacion'].")";
									}else{
										if ($row[0]['NombreGenerico'] == "") {
											$NombrePresentacion = "Sin presentación";
										}else{
											$NombrePresentacion = $row[0]['NombreGenerico']." (".$row[0]['AbreviaturaGenerico'].")";
										}
									}

									$precio = $PrecioPresentacion;
									$precioBruto = $PrecioBruto;
									$NombrePrecio = $NombrePrecio;

									/*$precio = 0; 

									//echo $Precio3Presentacion." y ".$row[0]["PrecioPresentacion"]." y ".$PrecioPresentacionAlterno." y ".$row[0]["Precio_General"];

									if ($Precio3Presentacion != "0") {
										$precio = $Precio3Presentacion;
									}else if ($row[0]["PrecioPresentacion"] != "") {
										$precio = $row[0]["PrecioPresentacion"];
									}else if ($PrecioPresentacionAlterno != "0") {
										$precio = $PrecioPresentacionAlterno;
									}else{
										$precio = $row[0]["Precio_General"];	
									}
									
									$precioMayoreo = 0;

									$precioBruto = 0;
									if ($PrecioBruto3Presentacion != "0") {
										$precioBruto = $PrecioBruto3Presentacion;
									}else if ($row[0]["PrecioBruto"] != "") {
										$precioBruto = $row[0]["PrecioBruto"];
									}else if ($PrecioBrutoAlterno != "0") {
										$precioBruto = $PrecioBrutoAlterno;
									}else{
										$precioBruto = $row[0]["Precio_General"];	
									}*/
							
									$arreglo = array(
										'ID_Producto' => $row[0]["ID_Producto"],
										'Codigo' => $row[0]["Codigo"],
										'Descripcion' => $row[0]["Descripcion"],
										'Presentacion' => $NombrePresentacion,
										'IDPresentacion' => $row[0]["IDPresentacion"],
										'Costo_General' => $row[0]["Costo_General"],
										'Nombre_Precio' => $NombrePrecio,
										'Precio_General' => $precio,
										'Precio_Bruto' => $precioBruto,
										'Precio_Mayoreo_General' => $precioMayoreo,
										'NombreArea' => $row[0]["NombreArea"],
										'Detalles' => $row[0]["Detalles"],
										'Fecha_Registro' => $row[0]["Fecha_Registro"],
										'Existencia' => $row[0]["Existencia"],
										'ImporteGeneral' => $row[0]["ImporteGeneral"],
										'ImportePresentacion' => $row[0]["ImportePresentacion"],
										'Impuestos' => $campoImpuestos
									);
									echo json_encode($arreglo);
								}else{
									//BUSQUEDA POR REFERENCIA
									$query = "SELECT ID_Producto, IF(presentaciones.Referencia != '', presentaciones.Referencia, productos.Referencia) AS Codigo, productos.Descripcion AS Descripcion, IFNULL(ID_Presentacion,0) AS IDPresentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, productos.Nombre_Unidad AS NombreGenerico, productos.Abreviatura_Unidad AS AbreviaturaGenerico, productos.Costo AS Costo_General, productos.Precio AS Precio_General, IFNULL(productos.importe, 0) AS ImporteGeneral, IFNULL(presentaciones.Importe, 0) AS ImportePresentacion, productos.Precio_Mayoreo AS Precio_Mayoreo_General, precios.Nombre AS NombrePrecio, precios.Precio AS PrecioPresentacion, precios.Precio_Mayoreo AS PrecioMayPresentacion, areas.Nombre AS NombreArea, Detalles, Fecha_Registro, inventario.Cantidad AS Existencia, (SELECT Precio FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = IFNULL(ID_Presentacion,0) AND Nombre = 'Precio 3' GROUP BY Nombre) AS Precio3Presentacion FROM productos LEFT JOIN areas ON FK_Area = ID_Area INNER JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' LEFT JOIN presentaciones ON inventario.FK_Presentacion = IFNULL(ID_Presentacion,0) LEFT JOIN precios ON precios.FK_Presentacion = IFNULL(ID_Presentacion,0) WHERE IF(presentaciones.Referencia != '', presentaciones.Referencia, productos.Referencia) = '$codigo' AND productos.Bloqueado = 0";
									//echo $query;
									$row = $omodelo->_consultar($query);
									$numerofilas = $omodelo->numerofilas;

									if($row == 'si'){
										echo "Error: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilas > 0){
											$subarreglo = null;
											// echo "1";
											$campoImpuestos = "";
											$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre AS Nombre, impuestos.Porcentaje AS Porcentaje, impuestos.Clave_CFDI AS Clave_CFDI, impuestos.Tipo_Factor AS Tipo_Factor, impuestos.Clase AS Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row[0]["ID_Producto"]."'";
											$rowI = $omodelo->_consultar($queryI);
											$numerofilasI = $omodelo->numerofilas;
											if($rowI == 'si'){
												echo "Error: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasI > 0){
													for ($i=0; $i < $numerofilasI; $i++) { 
														$campoImpuestos .= '
															<div class="form-check impuesto">
																<input class="form-check-input seleccionarImpuesto oculto" checked type="checkbox" nombre="'.$rowI[$i]["Nombre"].'" porcentaje="'.$rowI[$i]["Porcentaje"].'" attrid="'.$rowI[$i]["FK_Impuesto"].'" clavecfdi="'.$rowI[$i]["Clave_CFDI"].'" tipofactor="'.$rowI[$i]["Tipo_Factor"].'" clase="'.$rowI[$i]["Clase"].'">
																<label class="form-check-label" for="flexCheckDefault">
																	'.$rowI[$i]["Nombre"].' ('.$rowI[$i]["Porcentaje"].'%)
																</label>
															</div>
														';
													}
												}
											}		
											
											$PrecioPresentacion = 0;
											$PrecioBruto = 0;
											$NombrePrecio = 0;
											$NombreSucursal = 0;

											//CONSULTAR PRECIO 3 (PRECIO MAS BAJO)
											$idPrecio = 0;
											$queryPrecioID = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre = 'Precio 3'";
											$rowP = $omodelo->_consultar($queryPrecioID);
											//echo "1".$queryPrecioID;
											$numerofilasP = $omodelo->numerofilas;

											if($rowP == 'si'){
												echo "Error: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasP > 0){
													$PrecioPresentacion = $rowP[0]["Precio"];
													$PrecioBruto = $rowP[0]["Precio_Bruto"];
													$NombrePrecio = $rowP[0]["NombrePrecio"];
													$NombreSucursal = $rowP[0]["NombreSucursal"];
												}else{
													//SI NO TIENE PRECIO 3 CONSULTAMOS OTRO PRECIO
													$queryPrecioOtro = "SELECT ID_Precio, Precio, Precio_Bruto, precios.Nombre AS NombrePrecio, sucursales.Nombre AS NombreSucursal FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON sucursales.FK_Zona = ID_Zona WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '".$row[0]["IDPresentacion"]."' AND ID_Sucursal = '$sucursal' AND precios.Nombre != 'Precio 3' LIMIT 1";
													$rowPrecioOtro = $omodelo->_consultar($queryPrecioOtro);
													//echo "1".$queryPrecio3;
													$numerofilasPrecioOtro = $omodelo->numerofilas;

													if($rowPrecioOtro == 'si'){
														echo "Error: ".mysqli_error($omodelo->link);
													}else{
														if($numerofilasPrecioOtro > 0){
															$PrecioPresentacion = $rowPrecioOtro[0]["Precio"];
															$PrecioBruto = $rowPrecioOtro[0]["Precio_Bruto"];
															$NombrePrecio = $rowPrecioOtro[0]["NombrePrecio"];
															$NombreSucursal = $rowPrecioOtro[0]["NombreSucursal"];
														}
													}
												}
											}

											$NombrePresentacion = "";
											if ($row[0]['Presentacion'] != "") {
												$NombrePresentacion = $row[0]['Presentacion']." (".$row[0]['AbreviaturaPresentacion'].")";
											}else{
												if ($row[0]['NombreGenerico'] == "") {
													$NombrePresentacion = "Sin presentación";
												}else{
													$NombrePresentacion = $row[0]['NombreGenerico']." (".$row[0]['AbreviaturaGenerico'].")";
												}
											}

											$precio = $PrecioPresentacion;
											$precioBruto = $PrecioBruto;
											$NombrePrecio = $NombrePrecio;
											

											$arreglo = array(
												'ID_Producto' => $row[0]["ID_Producto"],
												'Codigo' => $row[0]["Codigo"],
												'Descripcion' => $row[0]["Descripcion"],
												'Presentacion' => $NombrePresentacion,
												'IDPresentacion' => $row[0]["IDPresentacion"],
												'Costo_General' => $row[0]["Costo_General"],
												'Nombre_Precio' => $NombrePrecio,
												'Precio_General' => $precio,
												'Precio_Bruto' => $precioBruto,
												'Precio_Mayoreo_General' => $precioMayoreo,
												'NombreArea' => $row[0]["NombreArea"],
												'Detalles' => $row[0]["Detalles"],
												'Fecha_Registro' => $row[0]["Fecha_Registro"],
												'Existencia' => $row[0]["Existencia"],
												'ImporteGeneral' => $row[0]["ImporteGeneral"],
												'ImportePresentacion' => $row[0]["ImportePresentacion"],
												'Impuestos' => $campoImpuestos
											);
											echo json_encode($arreglo);
										}else{
											echo "No encontrado";
										}
									}
								}
							}
						}
					}		
				}
			}
		}else if($tipo == "ConsultarPrecios"){
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
					$busqueda .= "CONCAT(ID_Precio, Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			$numerofilas = 0;
			$numerofilas2 = 0;
			$numerofilas3 = 0;
			$numerofilas4 = 0;
			if (isset($presentacion) && $presentacion != "") {
				$idZona = "";
				$queryz = "SELECT FK_Zona FROM sucursales WHERE ID_Sucursal = '$sucursal'";

				$rowz = $omodelo->_consultar($queryz);
				$numerofilasz = $omodelo->numerofilas;

				if($rowz == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilasz > 0){
						$idZona = $rowz[0]["FK_Zona"];
					}
				}	

				//CAMPO PARA PERSONALIZAR PRECIO
				if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][10] == '1') {
					$arreglo['data'][] = array(
						'Clases' => "activa",
						'ID' => 'Nada',
						'Nombre' => 'Personalizado',
						'Precio' => '
						<div class="row">
							<div class="col-md-8 col-sm-12">
								<input class="form-control" id="PrecioPersonalizadoPrecios" type="number" min="0" step="any"> 
							</div>
							<div class="col-md-4 col-sm-12 d-grid gap-2">
								<button class="btn btn-sm btn-primary SeleccionarPrecioPersonalizado">Seleccionar</button>
							</div>
						</div>',
						'PrecioBruto' => '',
					);	
				}

				if($presentacion == "" || $presentacion == null){
					$presentacion = 0;
				}

				$query = "SELECT ID_Precio, Nombre, Precio, IFNULL(Precio_Bruto, 0) AS PrecioBruto, (SELECT COUNT(*) FROM precios WHERE FK_Presentacion = '$presentacion' AND FK_Producto = '$idproducto' $busqueda) AS Num FROM precios WHERE FK_Presentacion = '$presentacion' AND FK_Producto = '$idproducto' AND FK_Zona = '$idZona' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						for($x=0; $x<$numerofilas; $x++){

							$arreglo['data'][] = array(
								'ID' => $row[$x]['ID_Precio'],
								'Nombre' => $row[$x]['Nombre'],
								'Precio' => $row[$x]['Precio'],
								'PrecioBruto' => $row[$x]['PrecioBruto'],	
							);
							
						}	
					}
				}
			}

			//OBTENER PRECIO GENERAL DEL PRODUCTO
			/*$query3 = "SELECT 0 AS ID_Precio, 'General' AS Nombre, Precio, (SELECT COUNT(*) FROM precios $busqueda) AS Num FROM productos WHERE ID_Producto = '$idproducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

			$row3 = $omodelo->_consultar($query3);
			$numerofilas3 = $omodelo->numerofilas;

			if($row3 == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas3 > 0){
					for($x=0; $x<$numerofilas3; $x++){

						$arreglo['data'][] = array(
							'ID' => $row3[$x]['ID_Precio'],
							'Nombre' => $row3[$x]['Nombre'],
							'Precio' => $row3[$x]['Precio'],
							'PrecioBruto' => 0,
						);
						
					}	
				}
			}*/

			$numerofilasTotal = 25;
			$arreglo['totales'] = array('NumRows' => $numerofilasTotal);

			echo json_encode($arreglo);
		}else if($tipo == "CargarPedidos"){
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
					$busqueda .= "CONCAT(DATE_FORMAT(pedidos.Fecha_Registro, '%Y-%m-%d'), ID_Pedido, clientes.Nombre, pedidos.Descuento, Total, Tipo_Pago, Notas, clientes.Correo) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Pedido, pedidos.FK_Usuario, pedidos.FK_Sucursal, FK_Caja, FK_Cliente, pedidos.Descuento, Total, Tipo_Pago, Pago, Cambio, Notas, pedidos.Fecha_Registro AS Datos, Cancelada, Fecha_Cancelacion, Regreso_Inventario, (SELECT CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) FROM usuarios WHERE ID_Usuario = pedidos.FK_Usuario) AS NombreUsuario, clientes.Nombre AS NombreCliente, clientes.Telefono AS Telefono, clientes.Correo AS CorreoCliente, clientes.RFC AS RFCCliente, (SELECT COUNT(*) FROM pedidos WHERE pedidos.FK_Sucursal = '$sucursal') AS Num FROM pedidos INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE pedidos.FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarVentas = 0;
					for($i=0; $i<$numerofilas; $i++){
						$tipoUsuario = "";$usuario="";$estatus="";
						$folio = str_pad($row[$i]['ID_Pedido'], 8, "0", STR_PAD_LEFT);

						$SumarVentas += $row[$i]['Total'];

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Pedido'],
							'Datos' => "Fecha: <b>".$row[$i]['Datos']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b>",
							'Cliente' => 'Nombre: <b>'.$row[$i]['NombreCliente'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Correo electrónico: <b>'.$row[$i]['CorreoCliente'].'</b><br>RFC: <b>'.$row[$i]['RFCCliente']."</b>",
							'Total' => "Subtotal: <b>$".number_format(($row[$i]['Total'] + $row[$i]['Descuento']), 2)."</b><br>Descuento: <b>$".number_format($row[$i]['Descuento'], 2)."</b><br>Total: <b>$".number_format($row[$i]['Total'], 2)."</b>",
							'Detalles' => '<button class="btn btn-link btn-sm" id="VerProductosPedido" attrid="'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'">Ver productos</button>',
							'Acciones' => '<button type="button" class="btn btn-primary btn-sm SeleccionarPedido" attrid="'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'">Seleccionar</button> <br> <br> <button type="button" class="btn btn-danger btn-sm EliminarPedido" attrid="'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'"><i class="fas fa-trash"></i></button>',
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
		}else if($tipo == "productos"){
			$IDPedido = $omodelo->link->real_escape_string($IDPedido);
			$tabla = "";
			$query = "SELECT ID_Detalle_Pedido, FK_Pedido, detalles_pedidos.FK_Producto, FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, Descripcion, Precio, Cantidad, detalles_pedidos.Descuento, Total, Devuelto, Fecha_Devolucion, Regreso_Inventario FROM detalles_pedidos LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Pedido = '$IDPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$sumaImpuestos=0; $subtotal = 0;

						$query2 = "SELECT ID_Impuesto, FK_Detalle_Pedido, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_pedidos WHERE FK_Detalle_Pedido = '".$row[$i]["ID_Detalle_Pedido"]."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;

						if($row2 == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								for($x=0; $x<$numerofilas2; $x++){
									$totalProducto=0; $descuento=0; $totalFinal=0;
									$totalProducto = $row[$i]["Precio"]*$row[$i]["Cantidad"];
									$descuento = $row[$i]["Descuento"];
									$totalFinal = $totalProducto - $descuento;

									$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);

									/*if (($row2[$x]["Impuesto_CFDI"] == "IVA" || $row2[$x]["Impuesto_CFDI"] == "IEPS") && $row2[$x]["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
										$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									}*/

									/*if ($row2[$x]["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
										$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] != "Exento"){ //Se resta al total
										$sumaImpuestos -= $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);

									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] == "Exento"){ ////No se suma ni se resta
										$sumaImpuestos += 0;
									}*/
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
						$descuento = $row[$i]["Descuento"];
						$subtotal = $subtotal - $descuento;
						$tabla .= "
							<tr>
								<td >".$row[$i]["Descripcion"].$presentacion."</td>
								<td style='vertical-align: middle;'>$".number_format($row[$i]["Precio"], 2)."</td>
								<td style='vertical-align: middle;'>".number_format($row[$i]["Cantidad"], 2)."</td>
								<td style='vertical-align: middle;'>".$row[$i]["Descuento"]."%</td>
								<td style='vertical-align: middle;'>$".number_format($subtotal, 2)."</td>
								<td><button class='btn btn-primary btn-sm verImpuestosProductoPedido' nombre='".$row[$i]["Descripcion"].$nombrepresentacion."' attrid='".$row[$i]["ID_Detalle_Pedido"]."'>$".number_format($sumaImpuestos, 2)."</button></td>
								<td>$".number_format($row[$i]["Total"], 2)."</td>
							</tr>
						";
					}
				}
			}

			echo $tabla;
		}else if($tipo == "impuestos"){
			$query = "SELECT ID_Impuesto, FK_Detalle_Pedido, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_pedidos WHERE FK_Detalle_Pedido = '".$IDDetalle."'";
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
		}else if($tipo == "AgregarPedido"){
			$IDPedido = $omodelo->link->real_escape_string($IDPedido);
			$productos = null;
			$impuestos = null;
			$query = "SELECT ID_Pedido, FK_Usuario, FK_Caja, pedidos.FK_Sucursal, FK_Cliente, clientes.Nombre AS NombreCliente, clientes.RFC AS RFCCliente, pedidos.Descuento, Total, Tipo_Pago, Pago, Cambio, Notas, Fecha_Entrega, pedidos.Fecha_Registro, Cancelada, Fecha_Cancelacion, Regreso_Inventario FROM pedidos INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Pedido = '$IDPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){

					$query2 = "SELECT ID_Detalle_Pedido, FK_Lote, FK_Pedido, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura, detalles_pedidos.FK_Producto, productos.Codigo AS CodigoProducto, presentaciones.Codigo AS CodigoPresentacion, IFNULL(productos.importe, 0) AS ImporteGeneral, IFNULL(presentaciones.Importe, 0) AS ImportePresentacion, detalles_pedidos.FK_Presentacion, detalles_pedidos.Descripcion, detalles_pedidos.Precio, detalles_pedidos.Cantidad, detalles_pedidos.Descuento, Total, detalles_pedidos.Devuelto, detalles_pedidos.Fecha_Devolucion, Regreso_Inventario FROM detalles_pedidos LEFT JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON detalles_pedidos.FK_Presentacion = ID_Presentacion WHERE FK_Pedido = '$IDPedido'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							for ($i=0; $i < $numerofilas2; $i++) { 
								$campoImpuestos = "";
								$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre AS Nombre, impuestos.Porcentaje AS Porcentaje, impuestos.Clave_CFDI AS Clave_CFDI, impuestos.Tipo_Factor AS Tipo_Factor, impuestos.Clase AS Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row2[$i]["FK_Producto"]."'";
								$rowI = $omodelo->_consultar($queryI);
								$numerofilasI = $omodelo->numerofilas;
								if($rowI == 'si'){
									echo "Error: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilasI > 0){
										for ($a=0; $a < $numerofilasI; $a++) { 
											$checked = "";
											$query3 = "SELECT ID_Impuesto, FK_Detalle_Pedido, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_pedidos WHERE FK_Detalle_Pedido = '".$row2[$i]["ID_Detalle_Pedido"]."' AND Impuesto_CFDI = '".$rowI[$a]["Nombre"]."' AND Tasa_Cuota_CFDI = '".$rowI[$a]["Porcentaje"]."' AND Clave_CFDI = '".$rowI[$a]["Clave_CFDI"]."'";
											$row3 = $omodelo->_consultar($query3); 
											$numerofilas3 = $omodelo->numerofilas;

											if($row3 == 'si'){
												echo "Error 3: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilas3 > 0){
													$checked = "checked";
												}else{
													$checked = "";
												}
											}

											$campoImpuestos .= '
												<div class="form-check impuesto">
													<input class="form-check-input oculto seleccionarImpuesto" '.$checked.' type="checkbox" nombre="'.$rowI[$a]["Nombre"].'" porcentaje="'.$rowI[$a]["Porcentaje"].'" attrid="'.$rowI[$a]["FK_Impuesto"].'" clavecfdi="'.$rowI[$a]["Clave_CFDI"].'" tipofactor="'.$rowI[$a]["Tipo_Factor"].'" clase="'.$rowI[$a]["Clase"].'">
													<label class="form-check-label" for="flexCheckDefault">
														'.$rowI[$a]["Nombre"].' ('.$rowI[$a]["Porcentaje"].'%)
													</label>
												</div>
											';
										}

									}
								}	


								$nombrePresentacion = ""; $codigoactual = "";
								if ($row2[$i]['Presentacion'] != "") {
									$nombrePresentacion = $row2[$i]['Presentacion']." (".$row2[$i]['Abreviatura'].")";
									$codigoactual = $row2[$i]['CodigoPresentacion'];
								}else{
									$nombrePresentacion = "Sin presentación";
									$codigoactual = $row2[$i]['CodigoProducto'];
								}

								$productos['data'][$i] = array(
									'ID_Detalle_Pedido' => $row2[$i]["ID_Detalle_Pedido"],
									'FK_Pedido' => $row2[$i]["FK_Pedido"],
									'FK_Producto' => $row2[$i]["FK_Producto"],
									'Codigo' => $codigoactual,
									'FK_Presentacion' => $row2[$i]["FK_Presentacion"],
									'NombrePresentacion' => $nombrePresentacion,
									'Descripcion' => $row2[$i]["Descripcion"],
									'Precio' => $row2[$i]["Precio"],
									'Cantidad' => $row2[$i]["Cantidad"],
									'Descuento' => $row2[$i]["Descuento"],
									'Total' => $row2[$i]["Total"],
									'Devuelto' => $row2[$i]["Devuelto"],
									'Fecha_Devolucion' => $row2[$i]["Fecha_Devolucion"],
									'Regreso_Inventario' => $row2[$i]["Regreso_Inventario"],
									'ImporteGeneral' => $row2[$i]["ImporteGeneral"],
									'ImportePresentacion' => $row2[$i]["ImportePresentacion"],
									'Impuestos' => $campoImpuestos,
									'FK_Lote' => $row2[$i]['FK_Lote']
								);

							}
						}
					}

					$arreglo['data'] = array(
						'ID_Pedido' => $row[0]['ID_Pedido'],
						'FK_Sucursal' => $row[0]["FK_Sucursal"],
						'FK_Cliente' => $row[0]["FK_Cliente"],
						'NombreCliente' => $row[0]["NombreCliente"],
						'RFCCliente' => $row[0]["RFCCliente"],
						'Descuento' => $row[0]["Descuento"],
						'Total' => $row[0]["Total"],
						'Tipo_Pago' => $row[0]["Tipo_Pago"],
						'Pago' => $row[0]["Pago"],
						'Cambio' => $row[0]["Cambio"],
						'Notas' => $row[0]["Notas"],
						'Fecha_Entrega' => $row[0]["Fecha_Entrega"],
						'Productos' => $productos,
					);
				}

				echo json_encode($arreglo);
			}
		}else if($tipo == "ConsultarPresentacionesProducto"){
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
					$busqueda .= "CONCAT(Cantidad, Nombre, Abreviatura) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			if ($presentacion == null) {
				$presentacion = 0;
			}
			
			$query = "SELECT inventario.FK_Presentacion AS ID_Presentacion, presentaciones.Codigo AS CodigoPresentacion, productos.Codigo AS CodigoProducto, inventario.FK_Producto, Cantidad AS Existencia, IFNULL(productos.importe, 0) AS ImporteGeneral, IFNULL(presentaciones.Importe, 0) AS ImportePresentacion, IFNULL((SELECT Precio FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON zonas.ID_Zona = sucursales.FK_Zona WHERE sucursales.ID_Sucursal = '$sucursal' AND FK_Producto = inventario.FK_Producto AND FK_Presentacion = inventario.FK_Presentacion ORDER BY Precio LIMIT 1), (SELECT Precio FROM productos WHERE ID_Producto = inventario.FK_Producto)) AS PrimerPrecio, Nombre, Abreviatura, productos.Nombre_Unidad AS NombreGenerico, productos.Abreviatura_Unidad AS AbreviaturaGenerico, (SELECT COUNT(*) FROM inventario LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN productos ON inventario.FK_Producto = ID_Producto WHERE inventario.FK_Sucursal = '$sucursal' AND inventario.FK_Producto = '$idproducto' AND inventario.FK_Presentacion <> '$presentacion' $busqueda) AS Num FROM inventario LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN productos ON inventario.FK_Producto = ID_Producto WHERE inventario.FK_Sucursal = '$sucursal' AND inventario.FK_Producto = '$idproducto' AND inventario.FK_Presentacion <> '$presentacion' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$nombrePresentacion = "";
						$abreviaPresentacion = "";
						if ($row[$i]['ID_Presentacion'] == "" || $row[$i]['ID_Presentacion'] == 0) {
							$row[$i]['ID_Presentacion'] = null;	
						}

						if ($row[$i]['ID_Presentacion'] != "") {
							$nombrePresentacion = $row[$i]['Nombre'];
							$abreviaPresentacion = $row[$i]['Abreviatura'];
						}else{
							if ($row[$i]['NombreGenerico'] == "") {
								$nombrePresentacion = "Sin presentación";
								$abreviaPresentacion = "";
							}else{
								$nombrePresentacion = $row[$i]['NombreGenerico'];
								$abreviaPresentacion = $row[$i]['AbreviaturaGenerico'];
							}
						}


						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Presentacion'],
							'Nombre'  => $nombrePresentacion,
							'Abreviatura' => $abreviaPresentacion,
							'Existencia' => $row[$i]['Existencia'],
							'Accion' => '<button type="button" class="btn btn-primary btn-sm SeleccionarPresentacion" presentacionactual="'.$presentacion.'" attrid="'.$row[$i]['ID_Presentacion'].'" precio="'.$row[$i]['PrimerPrecio'].'" nombre="'.$nombrePresentacion.'" abreviatura="'.$abreviaPresentacion.'" producto="'.$row[$i]['FK_Producto'].'" codigopresentacion="'.$row[$i]["CodigoPresentacion"].'" codigoproducto="'.$row[$i]["CodigoProducto"].'" importegeneral="'.$row[$i]["ImporteGeneral"].'" importepresentacion="'.$row[$i]["ImportePresentacion"].'">Seleccionar</button>',
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]["Num"]);	
		
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "ConsultarAdministrador"){
			echo $_SESSION['user_admin']['Tipo_Usuario'];
		}else if($tipo == "ValidarAdministrador"){
			$correo =  $omodelo->link->real_escape_string($correo);
			$contra =  $omodelo->link->real_escape_string($contra);

			$query = "SELECT ID_Usuario, Contrasena FROM usuarios WHERE Tipo_Usuario = 'Administrador' AND Correo = '$correo'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0 && password_verify($contra, $row[0]['Contrasena'])){
					echo "Correcto";
				}else{
					echo "Incorrecto";
				}
			}
		}else if($tipo == "CerrarCaja"){
			$fecha = date('Y-m-d H:i:s'); 
			$MontoCierre =  $omodelo->link->real_escape_string($MontoCierre);
			$sucursal =  $omodelo->link->real_escape_string($sucursal);
			$iddetallecaja =  $omodelo->link->real_escape_string($iddetallecaja);

			//$query = "SELECT ID_Detalle_Caja FROM detalles_caja WHERE FK_Caja = (SELECT ID_Caja FROM cajas WHERE FK_Sucursal = '$sucursal') ORDER BY ID_Detalle_Caja DESC LIMIT 1";
			$query = "SELECT ID_Detalle_Caja FROM detalles_caja WHERE ID_Detalle_Caja = '$iddetallecaja'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$query = "UPDATE cajas SET Estado = 0, FK_Usuario = '0' WHERE FK_Sucursal = '$sucursal'";
					$error = $omodelo->_insertar($query);

					if($error == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						$query2 = "UPDATE detalles_caja SET Fecha_Cierre = NOW(), Monto_Cierre = '$MontoCierre', FK_Usuario_Cierre = '".$_SESSION['user_admin']['ID_Usuario']."' WHERE ID_Detalle_Caja = '".$row[0]["ID_Detalle_Caja"]."'";

						$error2 = $omodelo->_insertar($query2);
						
						if($error2 == 'si'){
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							echo "Correcto~".$row[0]["ID_Detalle_Caja"];
						}
					}
				}
			}
		}else if($tipo == "ConsultarDetalleCaja"){
			$fecha = date('Y-m-d H:i:s'); 
			$sucursal =  $omodelo->link->real_escape_string($sucursal);

			$query = "SELECT ID_Detalle_Caja FROM detalles_caja WHERE FK_Caja = (SELECT ID_Caja FROM cajas WHERE FK_Sucursal = '$sucursal') ORDER BY ID_Detalle_Caja DESC LIMIT 1";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo $row[0]["ID_Detalle_Caja"];
				}
			}
		}else if($tipo == "ConsultarBalanceCerrar"){
			$fecha = date('Y-m-d H:i:s'); 

			$sucursal =  $omodelo->link->real_escape_string($sucursal);
			$arreglo = [];
			$query = "SELECT ID_Detalle_Caja, FK_Caja, DATE_FORMAT(Fecha_Abrir, '%Y-%m-%d') AS FechaAbrirCorta, Fecha_Abrir, Monto_Abrir, FK_Usuario_Abrir, (SELECT Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario_Abrir) AS UsuarioAbrir, (SELECT Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario_Cierre) AS UsuarioCerrar, Fecha_Cierre, Monto_Cierre, FK_Usuario_Cierre, DATE_FORMAT(Fecha_Cierre, '%Y-%m-%d %r') AS FechaCerrar, DATE_FORMAT(Fecha_Abrir, '%Y-%m-%d %r') AS FechaAbrir FROM detalles_caja WHERE ID_Detalle_Caja = '$IDDetalleCaja'";
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

					$queryv = "SELECT ventas.Total, ventas.Total_Importes, ventas.Tipo_Pago, Pago_Efectivo, Pago_Transferencia, Pago_Cheque, Pago_Tarjeta_Credito, Pago_Tarjeta_Debito FROM ventas WHERE (Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND Fecha_Registro <= NOW()) AND Estatus = 'Completada' AND Contar_Venta = 0 AND FK_Sucursal = '$sucursal'";
					$rowv = $omodelo->_consultar($queryv);
					$numerofilasv = $omodelo->numerofilas;
					if($rowv == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasv > 0){
							for ($ventas=0; $ventas < $numerofilasv; $ventas++) { 
								$totalIngresos += $rowv[$ventas]["Total"] + $rowv[$ventas]["Total_Importes"];
								$totalventas += $rowv[$ventas]["Total"] + $rowv[$ventas]["Total_Importes"];

								if ($rowv[$ventas]["Pago_Efectivo"] > 0) {
									$totalvefectivo += $rowv[$ventas]["Pago_Efectivo"];
									$totalIngresosEfectivo += $rowv[$ventas]["Pago_Efectivo"];
								}

								if ($rowv[$ventas]["Pago_Transferencia"] > 0) {
									$totalvtransferencia += $rowv[$ventas]["Pago_Transferencia"];
								}

								if ($rowv[$ventas]["Pago_Cheque"] > 0) {
									$totalvcheque += $rowv[$ventas]["Pago_Cheque"];
								}

								if ($rowv[$ventas]["Pago_Tarjeta_Credito"] > 0) {
									$totalvtarjeta += $rowv[$ventas]["Pago_Tarjeta_Credito"];
								}

								if ($rowv[$ventas]["Pago_Tarjeta_Debito"] > 0) {
									$totalvtarjetadebito += $rowv[$ventas]["Pago_Tarjeta_Debito"];
								}


							}
						}
					}

					$queryi = "SELECT importes.Total FROM importes INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (ventas.Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND ventas.Fecha_Registro <= NOW()) AND ventas.FK_Sucursal = '$sucursal'";
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
					$queryiEgresos = "SELECT detalles_importes.Cantidad AS CantidadImportes, importes.Importe AS PrecioImporte FROM detalles_importes INNER JOIN importes ON FK_Importe = ID_Importe INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (detalles_importes.Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND detalles_importes.Fecha_Registro <= NOW()) AND ventas.FK_Sucursal = '$sucursal'";
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
					$query2 = "SELECT Total FROM compras WHERE Estatus = 1 AND Tipo_Compra = 'Contado' AND (Fecha_Registro >= '".$row[0]["Fecha_Abrir"]."' AND Fecha_Registro <= NOW()) AND FK_Sucursal = '$sucursal'";
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
					$queryContado = "SELECT pagos.Monto, pagos.Tipo_Pago FROM pagos INNER JOIN compras ON FK_Compra = ID_Compra WHERE (pagos.Fecha >= '".$row[0]["Fecha_Abrir"]."' AND pagos.Fecha <= NOW()) AND Estatus = 1 AND Tipo_Compra = 'Contado' AND compras.FK_Sucursal = '$sucursal'";
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
					$query2 = "SELECT pagos.Monto, pagos.Tipo_Pago FROM pagos INNER JOIN compras ON FK_Compra = ID_Compra WHERE (pagos.Fecha >= '".$row[0]["Fecha_Abrir"]."' AND pagos.Fecha <= NOW()) AND Tipo_Compra = 'Credito' AND compras.FK_Sucursal = '$sucursal'";
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

					// Devoluciones 
					$querydev = "SELECT 
						SUM(dds.TotalDetalle) AS TotalDevoluciones,
						SUM(CASE WHEN v.Tipo_Pago = 'Pago_Efectivo' THEN dds.TotalDetalle ELSE 0 END) AS DevolucionesEfectivo
						FROM devoluciones d
						INNER JOIN (
						SELECT FK_Devolucion, SUM(Total) AS TotalDetalle
						FROM detalles_devolucion
						GROUP BY FK_Devolucion
						) dds ON d.ID_Devolucion = dds.FK_Devolucion
						INNER JOIN ventas v ON d.FK_Venta = v.ID_Venta
						WHERE d.Fecha_Registro BETWEEN '".$row[0]["Fecha_Abrir"]."' AND NOW()
						AND v.FK_Sucursal = '$sucursal';
						";

					$rowdev = $omodelo->_consultar($querydev);
					if ($rowdev == 'si') {
						echo "Error: " . mysqli_error($omodelo->link);
					} else {
						$totaldevoluciones = $rowdev[0]["TotalDevoluciones"] ?? 0;
						$totalEgresos += $totaldevoluciones; // Se suma una sola vez
						$totalEgresosEfectivo += $rowdev[0]["DevolucionesEfectivo"] ?? 0;
					}

					//Gastos
					$gastosefectivo = 0;
					$gastoscheque = 0;
					$gastosdeposito = 0;
					$gastostarjeta = 0;
					$gastostransferencia = 0;
					$queryGasto = "SELECT Forma_Pago, Monto FROM gastos_generales WHERE (Fecha_Gasto >= '".$row[0]["FechaAbrirCorta"]."' AND Fecha_Gasto <= NOW()) AND FK_Sucursal = '$sucursal'";
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
					$queryDeposito = "SELECT Monto FROM depositos WHERE (Fecha_Deposito >= '".$row[0]["FechaAbrirCorta"]."' AND Fecha_Deposito <= NOW()) AND FK_Sucursal = '$sucursal'";
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
		}else if($tipo == "ConsultarToken"){
			$codigo=  $omodelo->link->real_escape_string($codigo);

			//if ($_SESSION['user_admin']['Tipo_Usuario'] == "Administrador") {
				$query = "SELECT ID_Token, Codigo, Cantidad FROM tokens_descuentos WHERE Codigo = '".$codigo."' AND Activo = 0";
				$row = $omodelo->_consultar($query);
				$numerofilas2 = $omodelo->numerofilas;
				$tabla ="";
				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas2 > 0){
						echo "Correcto~".$row[0]["Cantidad"]."~".$row[0]["ID_Token"];
					}else{
						echo "Invalido~";	
					}
				}
			/*}else{
				echo "NoAdmin~";
			}*/
		}else if($tipo == "CargarVentas"){
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'AND ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(LPAD(ID_Venta, 8, '0'), DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d'), ID_Venta, (SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM clientes WHERE FK_Cliente = ID_Cliente), ventas.Descuento, Total, Tipo_Pago, Notas) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Venta, LPAD(ID_Venta, 8, '0') AS FolioVenta, FK_Usuario, FK_Caja, ventas.FK_Sucursal, FK_Cliente, FK_Direccion, Descuento, Total, Total_Importes, Tipo_Pago, Cambio, Notas, ventas.Fecha_Registro AS Datos, Estatus, (SELECT CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) FROM usuarios WHERE ID_Usuario = ventas.FK_Usuario) AS NombreUsuario, (SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM clientes WHERE FK_Cliente = ID_Cliente) AS NombreCliente, 

				(SELECT Telefono FROM clientes WHERE ID_Cliente = FK_Cliente) AS Telefono, 
				(SELECT Correo FROM clientes WHERE ID_Cliente = FK_Cliente) AS CorreoCliente, 
				(SELECT RFC FROM clientes WHERE ID_Cliente = FK_Cliente) AS RFCCliente, 

				(SELECT COUNT(*) FROM ventas WHERE ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND Estatus = 'Completada' AND Facturada = 0 $busqueda) AS Num FROM ventas WHERE ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND Estatus = 'Completada' AND Facturada = 0 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarVentas = 0;
					for($i=0; $i<$numerofilas; $i++){
						$tipoUsuario = "";$usuario="";$estatus="";
						$folio = $row[$i]['FolioVenta'];

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
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Venta'],
							'Datos' => "Fecha: <b>".$row[$i]['Datos']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b>",
							'Cliente' => 'Nombre: <b>'.$row[$i]['NombreCliente'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Correo electrónico: <b>'.$row[$i]['CorreoCliente'].'</b><br>RFC: <b>'.$row[$i]['RFCCliente']."</b>",
							'Total' => "Subtotal: <b>$".number_format(($row[$i]['Total'] + $row[$i]['Descuento']), 2)."</b><br>Descuento: <b>$".number_format($row[$i]['Descuento'], 2)."</b><br>Total de la venta: <b>$".number_format($row[$i]['Total'], 2)."</b>".$MostrarDevolucion,
							'Detalles' => '<button class="btn btn-link btn-sm" id="VerProductosHacerVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'">Ver productos</button>',
							'Acciones' => '<button type="button" class="btn btn-primary btn-sm SeleccionarVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'">Seleccionar</button>',
						);
					}

					$arreglo['totales'] = array(
						'NumRows' => $row[0]['Num'], 
						'Datos' => "",
						'Cliente' => "Totales",
						'Total' => "",
						'Detalles' =>"",
						'Acciones' => "");	
		
				}
			}
			echo json_encode($arreglo);
		}else if($tipo == "productosVentas"){
			$IDVenta = $omodelo->link->real_escape_string($IDVenta);
			$tabla = "";
			$query = "SELECT ID_Detalle_Venta, FK_Venta, detalles_ventas.FK_Producto, FK_Presentacion, Cobrar_Importe, Descripcion, Precio, Cantidad, Descuento, Total, presentaciones.Nombre AS NombrePresentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion FROM detalles_ventas LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$IDVenta'";
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
									$descuento = $row[$i]["Descuento"];
									$totalFinal = $totalProducto - $descuento;
									
									$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									/*if (($row2[$x]["Impuesto_CFDI"] == "IVA" || $row2[$x]["Impuesto_CFDI"] == "IEPS") && $row2[$x]["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
										$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									}*/
									/*if ($row2[$x]["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
										$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] != "Exento"){ //Se resta al total
										$sumaImpuestos -= $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);

									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] == "Exento"){ ////No se suma ni se resta
										$sumaImpuestos += 0;
									}*/
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
						$descuento = $row[$i]["Descuento"];
						$subtotal = $subtotal - $descuento;
						$tabla .= "
							<tr>
								<td >".$row[$i]["Descripcion"].$presentacion."</td>
								<td style='vertical-align: middle;'>$".number_format($row[$i]["Precio"], 2)."</td>
								<td style='vertical-align: middle;'>".number_format($row[$i]["Cantidad"], 2)."</td>
								<td style='vertical-align: middle;'>$".$row[$i]["Descuento"]."</td>
								<td style='vertical-align: middle;'>$".number_format($subtotal, 2)."</td>
								<td><button class='btn btn-primary btn-sm verImpuestosProductoPedido' nombre='".$row[$i]["Descripcion"].$nombrepresentacion."' attrid='".$row[$i]["ID_Detalle_Venta"]."'>$".number_format($sumaImpuestos, 2)."</button></td>
								<td>$".number_format($row[$i]["Total"], 2)."</td>
							</tr>
						";
					}
				}
			}

			echo $tabla;
		}else if($tipo == "AgregarVenta"){
			$IDVenta = $omodelo->link->real_escape_string($IDVenta);
			$productos = null;
			$impuestos = null;
			$query = "SELECT ID_Venta, FK_Usuario, FK_Caja, ventas.FK_Sucursal, FK_Cliente, clientes.Nombre AS NombreCliente, clientes.RFC AS RFCCliente, ventas.Descuento, Total, Tipo_Pago, Pago, Cambio, Notas, ventas.Fecha_Registro, Estatus FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$IDVenta'";

			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){

					$query2 = "SELECT ID_Detalle_Venta, FK_Token, (SELECT Codigo FROM tokens_descuentos WHERE ID_Token = FK_Token) AS CodigoToken, FK_Venta, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura, detalles_ventas.FK_Producto, productos.Codigo AS CodigoProducto, presentaciones.Codigo AS CodigoPresentacion, IFNULL(productos.importe, 0) AS ImporteGeneral, IFNULL(presentaciones.Importe, 0) AS ImportePresentacion, detalles_ventas.FK_Presentacion, detalles_ventas.Descripcion, detalles_ventas.Precio, detalles_ventas.Cantidad, detalles_ventas.Descuento, Total, detalles_ventas.Devuelto, detalles_ventas.Fecha_Devolucion, Regreso_Inventario FROM detalles_ventas LEFT JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON detalles_ventas.FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$IDVenta' AND FK_Promocion = 0";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							for ($i=0; $i < $numerofilas2; $i++) { 
								$campoImpuestos = "";
								$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre AS Nombre, impuestos.Porcentaje AS Porcentaje, impuestos.Clave_CFDI AS Clave_CFDI, impuestos.Tipo_Factor AS Tipo_Factor, impuestos.Clase AS Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row2[$i]["FK_Producto"]."'";
								$rowI = $omodelo->_consultar($queryI);
								$numerofilasI = $omodelo->numerofilas;
								if($rowI == 'si'){
									echo "Error: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilasI > 0){
										for ($a=0; $a < $numerofilasI; $a++) { 
											$checked = "";
											$query3 = "SELECT ID_Impuesto, FK_Detalle_Venta, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row2[$i]["ID_Detalle_Venta"]."' AND Impuesto_CFDI = '".$rowI[$a]["Nombre"]."' AND Tasa_Cuota_CFDI = '".$rowI[$a]["Porcentaje"]."' AND Clave_CFDI = '".$rowI[$a]["Clave_CFDI"]."'";
											$row3 = $omodelo->_consultar($query3); 
											$numerofilas3 = $omodelo->numerofilas;

											if($row3 == 'si'){
												echo "Error 3: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilas3 > 0){
													$checked = "checked";
												}else{
													$checked = "";
												}
											}

											$campoImpuestos .= '
												<div class="form-check impuesto">
													<input class="form-check-input oculto seleccionarImpuesto" '.$checked.' type="checkbox" nombre="'.$rowI[$a]["Nombre"].'" porcentaje="'.$rowI[$a]["Porcentaje"].'" attrid="'.$rowI[$a]["FK_Impuesto"].'" clavecfdi="'.$rowI[$a]["Clave_CFDI"].'" tipofactor="'.$rowI[$a]["Tipo_Factor"].'" clase="'.$rowI[$a]["Clase"].'">
													<label class="form-check-label" for="flexCheckDefault">
														'.$rowI[$a]["Nombre"].' ('.$rowI[$a]["Porcentaje"].'%)
													</label>
												</div>
											';
										}

									}
								}	


								$nombrePresentacion = ""; $codigoactual = "";
								$codigoactual = $row2[$i]['CodigoProducto'];
								if ($row2[$i]['Presentacion'] != "") {
									$nombrePresentacion = $row2[$i]['Presentacion']." (".$row2[$i]['Abreviatura'].")";
								}else{
									$nombrePresentacion = "Sin presentación";
								}

								$productos['data'][$i] = array(
									'ID_Detalle_Venta' => $row2[$i]["ID_Detalle_Venta"],
									'FK_Venta' => $row2[$i]["FK_Venta"],
									'FK_Producto' => $row2[$i]["FK_Producto"],
									'Codigo' => $codigoactual,
									'FK_Presentacion' => $row2[$i]["FK_Presentacion"],
									'NombrePresentacion' => $nombrePresentacion,
									'Descripcion' => $row2[$i]["Descripcion"],
									'Precio' => $row2[$i]["Precio"],
									'Cantidad' => $row2[$i]["Cantidad"],
									'Descuento' => $row2[$i]["Descuento"],
									'ID_Token' => $row2[$i]["FK_Token"],
									'CodigoToken' => $row2[$i]["CodigoToken"],
									'Total' => $row2[$i]["Total"],
									'Devuelto' => $row2[$i]["Devuelto"],
									'Fecha_Devolucion' => $row2[$i]["Fecha_Devolucion"],
									'Regreso_Inventario' => $row2[$i]["Regreso_Inventario"],
									'ImporteGeneral' => $row2[$i]["ImporteGeneral"],
									'ImportePresentacion' => $row2[$i]["ImportePresentacion"],
									'Impuestos' => $campoImpuestos,
								);

							}
						}
					}

					$arreglo['data'] = array(
						'ID_Venta' => $row[0]['ID_Venta'],
						'FK_Sucursal' => $row[0]["FK_Sucursal"],
						'FK_Cliente' => $row[0]["FK_Cliente"],
						'NombreCliente' => $row[0]["NombreCliente"],
						'RFCCliente' => $row[0]["RFCCliente"],
						'Descuento' => $row[0]["Descuento"],
						'Total' => $row[0]["Total"],
						'Tipo_Pago' => $row[0]["Tipo_Pago"],
						'Pago' => $row[0]["Pago"],
						'Cambio' => $row[0]["Cambio"],
						'Notas' => $row[0]["Notas"],
						'Productos' => $productos,
					);
				}

				echo json_encode($arreglo);
			}
		}else if($tipo == "ConsultarContraAdmin"){
			$contrasena = $omodelo->link->real_escape_string($contrasena);
			if ($contrasena == "adminmr24") {
				echo "Correcto";
			}else{
				echo "Error";
			}
		}else if($tipo == "ConsultarPromocionesProductos"){
			$arreglo = null;
			$sucursal = $omodelo->link->real_escape_string($sucursal);
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$IDPresentacion = $omodelo->link->real_escape_string($IDPresentacion);
			$Cantidad = $omodelo->link->real_escape_string($Cantidad);

			$query = "SELECT ID_Promocion, Nombre, Descripcion, Tipo_Promocion, FK_Presentacion_Cantidad_Regalar,
			(SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto) AS NombreProductoPromocion,  
			(SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion) AS NombrePresentacionPromocion,  
			
			(SELECT Descripcion FROM productos INNER JOIN detalles_promociones ON FK_Producto = ID_Producto WHERE detalles_promociones.FK_Promocion = ID_Promocion AND ID_Producto != '$IDProducto') AS NombreProductoCombo,
			(SELECT Nombre FROM presentaciones INNER JOIN detalles_promociones ON FK_Presentacion = ID_Presentacion WHERE detalles_promociones.FK_Promocion = ID_Promocion AND ID_Presentacion != '$IDPresentacion') AS NombrePresentacionCombo, 
			(SELECT ID_Producto FROM productos INNER JOIN detalles_promociones ON FK_Producto = ID_Producto WHERE detalles_promociones.FK_Promocion = ID_Promocion AND ID_Producto != '$IDProducto') AS IDProductoCombo,
			(SELECT ID_Presentacion FROM presentaciones INNER JOIN detalles_promociones ON FK_Presentacion = ID_Presentacion WHERE detalles_promociones.FK_Promocion = ID_Promocion AND ID_Presentacion != '$IDPresentacion') AS IDPresentacionCombo, 

			Cantidad_Promocion, 
			FK_Producto_Regalar, 
			(SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto_Regalar) AS NombreProductoRegalar, 
			FK_Presentacion_Regalar, 
			(SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Regalar) AS NombrePresentacionRegalar, 
			Cantidad_Regalar, 
			(SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Cantidad_Regalar) AS NombrePresentacionCantidadRegalar, 
			Precio_Especial, Estatus, FK_Usuario, Fecha_Registro 
			FROM promociones INNER JOIN detalles_promociones_sucursales ON detalles_promociones_sucursales.FK_Promocion = ID_Promocion INNER JOIN detalles_promociones ON detalles_promociones.FK_Promocion = ID_Promocion WHERE FK_Producto = '$IDProducto' AND FK_Presentacion = '$IDPresentacion' AND Estatus = 'Vigente' AND detalles_promociones_sucursales.FK_Sucursal = '$sucursal'";
			//Consultamos las promociones que aplican con ese producto
			//echo $query;
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$VecesQueAplica = 0;
						if ($row[$i]["Tipo_Promocion"] == "CantidadRegalo") {
							$VecesQueAplica = floor($Cantidad / $row[$i]["Cantidad_Promocion"]);
							if ($VecesQueAplica >= 1) {
								$arreglo[] = array(
									'IDPromocion' => $row[$i]["ID_Promocion"],
									'TipoPromocion' => $row[$i]["Tipo_Promocion"],
									'CantidadRegalo' =>  ($VecesQueAplica * $row[$i]["Cantidad_Regalar"]),
									'CantidadRegalarPromocion' => $row[$i]["Cantidad_Regalar"],
									'CantidadPromocion' => $row[$i]["Cantidad_Promocion"],
									'NombreProductoRegalo' =>  $row[$i]["NombreProductoPromocion"],
									'NombrePresentacionPromocion' =>  $row[$i]["NombrePresentacionPromocion"],
									'PresentacionRegalo' =>  $row[$i]["NombrePresentacionCantidadRegalar"],
									'IDPresentacionRegalo' =>  $row[$i]["FK_Presentacion_Cantidad_Regalar"],
									"Eliminar" => "No",
									'IDProductoRegalar' => $IDProducto,
									'IDPresentacionRegalar' => $IDPresentacion,
								);
							}else{
								$arreglo[] = array(
									'IDPromocion' => $row[$i]["ID_Promocion"],
									'TipoPromocion' => $row[$i]["Tipo_Promocion"],
									'CantidadRegalo' =>  ($VecesQueAplica * $row[$i]["Cantidad_Regalar"]),
									'CantidadRegalarPromocion' => $row[$i]["Cantidad_Regalar"],
									'CantidadPromocion' => $row[$i]["Cantidad_Promocion"],
									'NombreProductoRegalo' =>  $row[$i]["NombreProductoPromocion"],
									'PresentacionRegalo' =>  $row[$i]["NombrePresentacionCantidadRegalar"],
									'IDPresentacionRegalo' =>  $row[$i]["FK_Presentacion_Cantidad_Regalar"],
									"Eliminar" => "Si",
									'IDProductoRegalar' => $IDProducto,
									'IDPresentacionRegalar' => $IDPresentacion,
								);
							}
						}else if ($row[$i]["Tipo_Promocion"] == "ProductoRegalo") { 
							//CALCULAR CANTIDAD DE PRODUCTOS A REGALAR
							$VecesQueAplica = floor($Cantidad / $row[$i]["Cantidad_Promocion"]);
							if ($VecesQueAplica >= 1) {
								$arreglo[] = array(
									'IDPromocion' => $row[$i]["ID_Promocion"],
									'TipoPromocion' => $row[$i]["Tipo_Promocion"],
									'NombreProductoPromocion' => $row[$i]["NombreProductoPromocion"],
									'NombrePresentacionPromocion' => $row[$i]["NombrePresentacionPromocion"],
									'CantidadRegalo' =>  $VecesQueAplica,
									'CantidadPromocion' => $row[$i]["Cantidad_Promocion"],
									'ProductoRegalo' =>  $row[$i]["NombreProductoRegalar"],
									'IDProductoRegalo' =>  $row[$i]["FK_Producto_Regalar"],
									'PresentacionRegalo' =>  $row[$i]["NombrePresentacionRegalar"],
									'IDPresentacionRegalo' =>  $row[$i]["FK_Presentacion_Regalar"],
									'Eliminar' => 'No',
									'IDProductoRegalar' => $row[$i]["FK_Producto_Regalar"],
									'IDPresentacionRegalar' => $row[$i]["FK_Presentacion_Regalar"],
								);
							}else{
								$arreglo[] = array(
									'IDPromocion' => $row[$i]["ID_Promocion"],
									'TipoPromocion' => $row[$i]["Tipo_Promocion"],
									'NombreProductoPromocion' => $row[$i]["NombreProductoPromocion"],
									'NombrePresentacionPromocion' => $row[$i]["NombrePresentacionPromocion"],
									'CantidadRegalo' =>  $VecesQueAplica,
									'CantidadPromocion' => $row[$i]["Cantidad_Promocion"],
									'ProductoRegalo' =>  $row[$i]["NombreProductoRegalar"],
									'IDProductoRegalo' =>  $row[$i]["FK_Producto_Regalar"],
									'PresentacionRegalo' =>  $row[$i]["NombrePresentacionRegalar"],
									'IDPresentacionRegalo' =>  $row[$i]["FK_Presentacion_Regalar"],
									'Eliminar' => 'Si',
									'IDProductoRegalar' => $row[$i]["FK_Producto_Regalar"],
									'IDPresentacionRegalar' => $row[$i]["FK_Presentacion_Regalar"],
								);
							}
						}else if ($row[$i]["Tipo_Promocion"] == "ComboProductoRegalo") { 
							$ArregloProductos = json_decode($ProductosVenta, true);
							
							foreach ($ArregloProductos as $fila) {
								//SI EL PRODUCTO ES COMBO
								if (($row[$i]["IDProductoCombo"] == $fila[0] && $row[$i]["IDPresentacionCombo"] == $fila[1])) {

									$VecesQueAplica = min($Cantidad, $fila[2]); //Cantidad de producto regalado
									if ($VecesQueAplica >= 1) {
										$arreglo[] = array(
											'IDPromocion' => $row[$i]["ID_Promocion"],
											'TipoPromocion' => $row[$i]["Tipo_Promocion"],
											'NombreProductoPromocion' => $row[$i]["NombreProductoPromocion"],
											'NombrePresentacionPromocion' => $row[$i]["NombrePresentacionPromocion"],
											'IDProductoCombo' => $row[$i]["IDProductoCombo"],
											'IDPresentacionCombo' => $row[$i]["IDPresentacionCombo"],
											'NombreProductoCombo' => $row[$i]["NombreProductoCombo"],
											'NombrePresentacionCombo' => $row[$i]["NombrePresentacionCombo"],
											'NombreProductoRegalar' => $row[$i]["NombreProductoRegalar"],
											'NombrePresentacionRegalar' => $row[$i]["NombrePresentacionRegalar"],
											'IDProductoRegalar' => $row[$i]["FK_Producto_Regalar"],
											'IDPresentacionRegalar' => $row[$i]["FK_Presentacion_Regalar"],
											'CantidadProductoRegalado' => $VecesQueAplica,
											'Eliminar' => 'No'
										);
									}else{
										$arreglo[] = array(
											'IDPromocion' => $row[$i]["ID_Promocion"],
											'TipoPromocion' => $row[$i]["Tipo_Promocion"],
											'NombreProductoPromocion' => $row[$i]["NombreProductoPromocion"],
											'NombrePresentacionPromocion' => $row[$i]["NombrePresentacionPromocion"],
											'IDProductoCombo' => $row[$i]["IDProductoCombo"],
											'IDPresentacionCombo' => $row[$i]["IDPresentacionCombo"],
											'NombreProductoCombo' => $row[$i]["NombreProductoCombo"],
											'NombrePresentacionCombo' => $row[$i]["NombrePresentacionCombo"],
											'NombreProductoRegalar' => $row[$i]["NombreProductoRegalar"],
											'NombrePresentacionRegalar' => $row[$i]["NombrePresentacionRegalar"],
											'IDProductoRegalar' => $row[$i]["FK_Producto_Regalar"],
											'IDPresentacionRegalar' => $row[$i]["FK_Presentacion_Regalar"],
											'CantidadProductoRegalado' => $VecesQueAplica,
											'Eliminar' => 'Si'
										);
									}
								}
							}
						}else if ($row[$i]["Tipo_Promocion"] == "ComboPrecioEspecial") { 
							$ArregloProductos = json_decode($ProductosVenta, true);
							
							foreach ($ArregloProductos as $fila) {
								//SI EL PRODUCTO ES COMBO
								if (($row[$i]["IDProductoCombo"] == $fila[0] && $row[$i]["IDPresentacionCombo"] == $fila[1])) {
									//$Cantidad //Cantidad del producto actual
									//$fila[2] //Cantidad del producto del loop
									if ($Cantidad == $fila[2]) {
										$arreglo[] = array(
											'IDPromocion' => $row[$i]["ID_Promocion"],
											'TipoPromocion' => $row[$i]["Tipo_Promocion"],
											'NombreProductoPromocion' => $row[$i]["NombreProductoPromocion"],
											'NombrePresentacionPromocion' => $row[$i]["NombrePresentacionPromocion"],
											'IDProductoCombo' => $row[$i]["IDProductoCombo"],
											'IDPresentacionCombo' => $row[$i]["IDPresentacionCombo"],
											'NombreProductoCombo' => $row[$i]["NombreProductoCombo"],
											'NombrePresentacionCombo' => $row[$i]["NombrePresentacionCombo"],
											'NombreProductoRegalar' => $row[$i]["NombreProductoRegalar"],
											'NombrePresentacionRegalar' => $row[$i]["NombrePresentacionRegalar"],
											'IDProductoRegalar' => $row[$i]["FK_Producto_Regalar"],
											'IDPresentacionRegalar' => $row[$i]["FK_Presentacion_Regalar"],
											'PrecioEspecial' => $row[$i]["Precio_Especial"],
											'Eliminar' => 'No'
										);
									}else{
										$arreglo[] = array(
											'IDPromocion' => $row[$i]["ID_Promocion"],
											'TipoPromocion' => $row[$i]["Tipo_Promocion"],
											'NombreProductoPromocion' => $row[$i]["NombreProductoPromocion"],
											'NombrePresentacionPromocion' => $row[$i]["NombrePresentacionPromocion"],
											'IDProductoCombo' => $row[$i]["IDProductoCombo"],
											'IDPresentacionCombo' => $row[$i]["IDPresentacionCombo"],
											'NombreProductoCombo' => $row[$i]["NombreProductoCombo"],
											'NombrePresentacionCombo' => $row[$i]["NombrePresentacionCombo"],
											'NombreProductoRegalar' => $row[$i]["NombreProductoRegalar"],
											'NombrePresentacionRegalar' => $row[$i]["NombrePresentacionRegalar"],
											'IDProductoRegalar' => $row[$i]["FK_Producto_Regalar"],
											'IDPresentacionRegalar' => $row[$i]["Precio_Especial"],
											'PrecioEspecial' => $row[$i]["FK_Presentacion_Regalar"],
											'Eliminar' => 'Si'
										);
									}
								}
							}
						}
					}
				}
				echo json_encode($arreglo);
			}
		}
	}
}
?>
