<?php
require_once('vendor/autoload.php');

class facturacion {

	public function _consultar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id = trim($omodelo->link->real_escape_string($id));
		$array = null;

		$query = "SELECT RFC, Nombre, Regimen, Certificado, Key_Cer, Contrasena FROM general WHERE ID_General = '1'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				if(trim($row[0]['RFC']) == '' || trim($row[0]['Nombre']) == '' || trim($row[0]['Regimen']) == '' || trim($row[0]['Certificado']) == '' || trim($row[0]['Key_Cer']) == '' || trim($row[0]['Contrasena']) == ''){
					echo "Error 2 Datos Facturacion";
				}else{
					$query1 = "SELECT ID_Venta, FK_Cliente, Razon_CFDI, Regimen_CFDI, clientes.Calle AS Calle_Cliente, clientes.No_Exterior AS No_Exterior_Cliente, clientes.No_Interior AS No_Interior_Cliente, clientes.Colonia AS Colonia_Cliente, clientes.Ciudad AS Ciudad_Cliente, clientes.Codigo_Postal AS Codigo_Postal_Cliente, clientes.Estado AS Estado_Cliente, clientes.Pais AS Pais_Cliente, RFC AS RFC_Cliente, sucursales.Nombre AS Sucursal, sucursales.Calle AS Calle_Sucursal, sucursales.No_Exterior AS No_Exterior_Sucursal, sucursales.No_Interior AS No_Interior_Sucursal, sucursales.Colonia AS Colonia_Sucursal, sucursales.CP AS CP_Sucursal, sucursales.Ciudad AS Ciudad_Sucursal, sucursales.Estado AS Estado_Sucursal, sucursales.Pais AS Pais_Sucursal, ventas.Descuento AS Descuento, Total, ventas.Fecha_Registro AS Fecha_Registro FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id' AND Facturada = '0' AND Cancelada = '0'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas1 = $omodelo->numerofilas;

					if($row1 == 'si'){
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas1 > 0){
							$productos = null;
							$query2 = "SELECT ID_Detalle_Venta, FK_Presentacion, Codigo, productos.Clave_ProdServ_CFDI AS Clave_ProdServ_CFDI, productos.Clave_Unidad_CFDI AS Clave_Unidad_CFDI, productos.Objeto_Impuesto_CFDI AS Objeto_Impuesto_CFDI, Clave_CFDI, Nombre_Unidad, Abreviatura_Unidad, presentaciones.Nombre AS Nombre_Presentacion, Abreviatura AS Abreviatura_Presentacion, detalles_ventas.Descripcion AS Descripcion, detalles_ventas.Precio AS Precio, Cantidad, Descuento, Total FROM detalles_ventas INNER JOIN productos ON detalles_ventas.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$id'";
							$row2 = $omodelo->_consultar($query2);
							$numerofilas2 = $omodelo->numerofilas;

							if($row2 == 'si'){
								echo "Error 4: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas2 > 0){
									for ($i=0; $i < $numerofilas2; $i++) { 
										$impuestos = null;
										$query3 = "SELECT ID_Impuesto, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row2[$i]['ID_Detalle_Venta']."'";
										$row3 = $omodelo->_consultar($query3);
										$numerofilas3 = $omodelo->numerofilas;

										if($row3 == 'si'){
											echo "Error 5: ".mysqli_error($omodelo->link);
										}else{
											if($numerofilas3 > 0){
												for ($x=0; $x < $numerofilas3; $x++) {
													$impuestos[$x] = array(
														'ID_Impuesto' => $row3[$x]['ID_Impuesto'],
														'Tipo_Impuesto_CFDI' => $row3[$x]['Tipo_Impuesto_CFDI'], 
														'Impuesto_CFDI' => $row3[$x]['Impuesto_CFDI'], 
														'Clave_CFDI' => $row3[$x]['Clave_CFDI'], 
														'Tipo_Factor_CFDI' => $row3[$x]['Tipo_Factor_CFDI'], 
														'Tasa_Cuota_CFDI' => $row3[$x]['Tasa_Cuota_CFDI'] 
													);
												}
											}
										}

										$claveUnidad = $row2[$i]['Clave_Unidad_CFDI'];
										$presentacion = $row2[$i]['Nombre_Unidad'];
										$abreviatura = $row2[$i]['Abreviatura_Unidad'];
										if($row2[$i]['FK_Presentacion'] != '0'){
											$claveUnidad = $row2[$i]['Clave_CFDI'];
											$presentacion = $row2[$i]['Nombre_Presentacion'];
											$abreviatura = $row2[$i]['Abreviatura_Presentacion'];
										}

										$productos[$i] = array(
											'ID_Detalle_Venta' => $row2[$i]['ID_Detalle_Venta'], 
											'Codigo' => $row2[$i]['Codigo'],
											'Clave_ProdServ_CFDI' => $row2[$i]['Clave_ProdServ_CFDI'],
											'Objeto_Impuesto_CFDI' => $row2[$i]['Objeto_Impuesto_CFDI'],
											'Clave_Unidad_CFDI' => $claveUnidad,
											'Nombre_Presentacion' => $presentacion,
											'Abreviacion_Presentacion' => $abreviatura, 
											'Descripcion' => $row2[$i]['Descripcion'], 
											'Precio' => $row2[$i]['Precio'], 
											'Cantidad' => $row2[$i]['Cantidad'], 
											'Descuento' => $row2[$i]['Descuento'], 
											'Total' => $row2[$i]['Total'],
											'Impuestos' => $impuestos 
										);
									}
								}
							}

							$array = array(
								'RFC_General' => $row[0]['RFC'],
								'Nombre_General' => $row[0]['Nombre'],
								'Regimen_General' => $row[0]['Regimen'],
								'FK_Cliente' => $row1[0]['FK_Cliente'],
								'Razon_CFDI' => $row1[0]['Razon_CFDI'],
								'Regimen_CFDI' => $row1[0]['Regimen_CFDI'],
								'Calle_Cliente' => $row1[0]['Calle_Cliente'],
								'No_Exterior_Cliente' => $row1[0]['No_Exterior_Cliente'],
								'No_Interior_Cliente' => $row1[0]['No_Interior_Cliente'],
								'Colonia_Cliente' => $row1[0]['Colonia_Cliente'],
								'Ciudad_Cliente' => $row1[0]['Ciudad_Cliente'],
								'Codigo_Postal_Cliente' => $row1[0]['Codigo_Postal_Cliente'],
								'Estado_Cliente' => $row1[0]['Estado_Cliente'],
								'Pais_Cliente' => $row1[0]['Pais_Cliente'],
								'RFC_Cliente' => $row1[0]['RFC_Cliente'],
								'Sucursal' => $row1[0]['Sucursal'],
								'Calle_Sucursal' => $row1[0]['Calle_Sucursal'],
								'No_Exterior_Sucursal' => $row1[0]['No_Exterior_Sucursal'],
								'No_Interior_Sucursal' => $row1[0]['No_Interior_Sucursal'],
								'Colonia_Sucursal' => $row1[0]['Colonia_Sucursal'],
								'CP_Sucursal' => $row1[0]['CP_Sucursal'],
								'Ciudad_Sucursal' => $row1[0]['Ciudad_Sucursal'],
								'Estado_Sucursal' => $row1[0]['Estado_Sucursal'],
								'Pais_Sucursal' => $row1[0]['Pais_Sucursal'],
								'Descuento' => $row1[0]['Descuento'],
								'Total' => $row1[0]['Total'],
								'Fecha_Registro' => $row1[0]['Fecha_Registro'],
								'Productos' => $productos
							);
						}
					}

					echo json_encode($array);
				}
			}else{
				echo "Error 6 Tabla datos generales";
			}
		}		
	}

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$rfcFacturacion = trim($omodelo->link->real_escape_string($rfcFacturacion));
		$nombreFacturacion = trim($omodelo->link->real_escape_string($nombreFacturacion));
		$regimenFacturacion = $omodelo->link->real_escape_string($regimenFacturacion);
		$contraFacturacion = $omodelo->link->real_escape_string($contraFacturacion);

		$contra = '';
		if(trim($contraFacturacion) != ''){
			$contra = ", Contrasena = '$contraFacturacion'";
		}

		$query = "UPDATE general SET RFC = '$rfcFacturacion', Nombre = '$nombreFacturacion', Regimen = '$regimenFacturacion' $contra WHERE ID_General = '1'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$id = mysqli_insert_id($omodelo->link);

			$status1 = 1;$status2 = 1;
			$carpeta = "vistas/assets/archivos/certificados/";
			if ($_FILES['certificadoFacturacion']['size'] > 0 && $_FILES['certificadoFacturacion']['error'] == 0) {
				$file = $_FILES["certificadoFacturacion"];
				$nombreCer = $file["name"];
				$tipo = $file["type"];
				$ruta_provisionalCer = $file["tmp_name"];
				$size = $file["size"];

				if ($tipo != 'application/x-x509-ca-cert' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status1 = 0;
					$ruta = $carpeta;
				}
			}

			if ($_FILES['keyFacturacion']['size'] > 0 && $_FILES['keyFacturacion']['error'] == 0) {
				$file = $_FILES["keyFacturacion"];
				$nombreKey = $file["name"];
				$tipo = $file["type"];
				$ruta_provisionalKey = $file["tmp_name"];
				$size = $file["size"];

				if ($tipo != 'application/octet-stream' && $tipo != ''){
					echo "Error 4 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 5 Peso";
				}else{
					$status2 = 0;
					$ruta = $carpeta;
				}
			}
			
			if($status1 == 0 && $status2 == 0){
				$query1 = "UPDATE general SET Certificado = '$nombreCer', Key_Cer = '$nombreKey' WHERE ID_General = '1'";
				$error1 = $omodelo->_insertar($query1);	

				if ($error1 == "si") {
					echo "Error 6: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisionalCer,  $ruta.$nombreCer);
					move_uploaded_file($ruta_provisionalKey,  $ruta.$nombreKey);
				}
			}

			echo "Correcto";
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$id = $omodelo->link->real_escape_string($id);
		$formaPagoCFDI = $omodelo->link->real_escape_string($formaPagoCFDI);
		$relacionCFDI = $omodelo->link->real_escape_string($relacionCFDI);
		$usoCFDI = $omodelo->link->real_escape_string($usoCFDI);
		$periodicidadCFDI = $omodelo->link->real_escape_string($periodicidadCFDI);
		$mesesCFDI = $omodelo->link->real_escape_string($mesesCFDI);
		$anoCFDI = $omodelo->link->real_escape_string($anoCFDI);

		$client = new \GuzzleHttp\Client();

		$query = "SELECT RFC, Nombre, Regimen, Certificado, Key_Cer, Contrasena FROM general WHERE ID_General = '1'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				if(trim($row[0]['RFC']) == '' || trim($row[0]['Nombre']) == '' || trim($row[0]['Regimen']) == '' || trim($row[0]['Certificado']) == '' || trim($row[0]['Key_Cer']) == '' || trim($row[0]['Contrasena']) == ''){
					echo "Error 2 Datos Facturacion";
				}else{
					$query1 = "SELECT ID_Venta, FK_Cliente, Razon_CFDI, Regimen_CFDI, clientes.Calle AS Calle_Cliente, clientes.No_Exterior AS No_Exterior_Cliente, clientes.No_Interior AS No_Interior_Cliente, clientes.Colonia AS Colonia_Cliente, clientes.Ciudad AS Ciudad_Cliente, clientes.Codigo_Postal AS Codigo_Postal_Cliente, clientes.Estado AS Estado_Cliente, clientes.Pais AS Pais_Cliente, RFC AS RFC_Cliente, sucursales.Nombre AS Sucursal, sucursales.Calle AS Calle_Sucursal, sucursales.No_Exterior AS No_Exterior_Sucursal, sucursales.No_Interior AS No_Interior_Sucursal, sucursales.Colonia AS Colonia_Sucursal, sucursales.CP AS CP_Sucursal, sucursales.Ciudad AS Ciudad_Sucursal, sucursales.Estado AS Estado_Sucursal, sucursales.Pais AS Pais_Sucursal, ventas.Descuento AS Descuento, Total, ventas.Fecha_Registro AS Fecha_Registro FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id' AND Facturada = '0' AND Cancelada = '0'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas1 = $omodelo->numerofilas;

					if($row1 == 'si'){
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas1 > 0){
							if(trim($row1[0]['Razon_CFDI']) == '' || trim($row1[0]['Regimen_CFDI']) == ''){
								echo "Error 4 Razon y Regimen Cliente";
							}else if($row1[0]['FK_Cliente'] != '1' && (trim($row1[0]['Calle_Cliente']) == '' || trim($row1[0]['No_Exterior_Cliente']) == '' || trim($row1[0]['Ciudad_Cliente']) == '' || trim($row1[0]['Codigo_Postal_Cliente']) == '' || trim($row1[0]['Estado_Cliente']) == '' || trim($row1[0]['Pais_Cliente']) == '' || trim($row1[0]['RFC_Cliente']) == '')){
								echo "Error 5 Domicilio Cliente";
							}else if(trim($row1[0]['Calle_Sucursal']) == '' || trim($row1[0]['No_Exterior_Sucursal']) == '' || trim($row1[0]['CP_Sucursal']) == '' || trim($row1[0]['Ciudad_Sucursal']) == '' || trim($row1[0]['Estado_Sucursal']) == '' || trim($row1[0]['Pais_Sucursal']) == ''){
								echo "Error 5 Domicilio Sucursal";
							}else{
								$productos = ''; $subtotal = 0; $totalImTras = 0; $totalImRete = 0; $imAgrupadosTras = []; $imAgrupadosRete = []; $error = false;
								$query2 = "SELECT ID_Detalle_Venta, FK_Presentacion, Codigo, productos.Clave_ProdServ_CFDI AS Clave_ProdServ_CFDI, productos.Clave_Unidad_CFDI AS Clave_Unidad_CFDI, productos.Objeto_Impuesto_CFDI AS Objeto_Impuesto_CFDI, Clave_CFDI, Nombre_Unidad, Abreviatura_Unidad, presentaciones.Nombre AS Nombre_Presentacion, Abreviatura AS Abreviatura_Presentacion, detalles_ventas.Descripcion AS Descripcion, detalles_ventas.Precio AS Precio, Cantidad, Descuento, Total FROM detalles_ventas INNER JOIN productos ON detalles_ventas.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$id'";
								$row2 = $omodelo->_consultar($query2);
								$numerofilas2 = $omodelo->numerofilas;

								if($row2 == 'si'){
									echo "Error 6: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilas2 > 0){
										for ($i=0; $i < $numerofilas2; $i++) { 
											$claveUnidad = $row2[$i]['Clave_Unidad_CFDI'];
											$presentacion = $row2[$i]['Nombre_Unidad'];
											$abreviatura = $row2[$i]['Abreviatura_Unidad'];
											if($row2[$i]['FK_Presentacion'] != '0'){
												$claveUnidad = $row2[$i]['Clave_CFDI'];
												$presentacion = $row2[$i]['Nombre_Presentacion'];
												$abreviatura = $row2[$i]['Abreviatura_Presentacion'];
											}

											if(trim($row2[$i]['Codigo']) == ''){
												echo "Error 7 Codigo~".$row2[$i]['Descripcion'];
												$error = true;
												break;
											}else if(trim($claveUnidad) == '' || trim($presentacion) == ''){
												echo "Error 8 Clave y Unidad~".$row2[$i]['Descripcion'];
												$error = true;
												break;
											}else if(trim($row2[$i]['Clave_ProdServ_CFDI']) == '' || trim($row2[$i]['Objeto_Impuesto_CFDI']) == ''){
												echo "Error 9 Clave producto y Objeto Impuesto~".$row2[$i]['Descripcion'];
												$error = true;
												break;
											}else{
												$query5 = "UPDATE detalles_ventas SET Clave_ProdServ_CFDI = '".$row2[$i]['Clave_ProdServ_CFDI']."', Identificacion_CFDI = '".$row2[$i]['Codigo']."', Clave_Unidad_CFDI = '$claveUnidad', Unidad_CFDI = '$presentacion', Objeto_Impuesto_CFDI = '".$row2[$i]['Objeto_Impuesto_CFDI']."' WHERE ID_Detalle_Venta = '".$row2[$i]['ID_Detalle_Venta']."'";
												$error3 = $omodelo->_insertar($query5);

												if($error3 == 'si'){
													echo "Error 12: ".mysqli_error($omodelo->link);
												}

												$impuestosTras = ''; $impuestosRet = '';
												$query3 = "SELECT ID_Impuesto, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row2[$i]['ID_Detalle_Venta']."'";
												$row3 = $omodelo->_consultar($query3);
												$numerofilas3 = $omodelo->numerofilas;

												if($row3 == 'si'){
													echo "Error 10: ".mysqli_error($omodelo->link);
												}else{
													if($numerofilas3 > 0){
														for ($x=0; $x < $numerofilas3; $x++) {
															if($row2[$i]['Objeto_Impuesto_CFDI'] != '01' && $row2[$i]['Objeto_Impuesto_CFDI'] != '03'){

																if($row3[$x]['Tipo_Impuesto_CFDI'] == 'Trasladado'){
																	if($row3[$x]['Tipo_Factor_CFDI'] == 'Exento'){
																		$impuestosTras .= '<cfdi:Traslado Base="'.number_format((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']), 2).'" Impuesto="'.$row3[$x]['Clave_CFDI'].'" TipoFactor="'.$row3[$x]['Tipo_Factor_CFDI'].'"/>';
																	}else{
																		$impuestosTras .= '<cfdi:Traslado Base="'.number_format((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']), 2).'" Impuesto="'.$row3[$x]['Clave_CFDI'].'" TipoFactor="'.$row3[$x]['Tipo_Factor_CFDI'].'" TasaOCuota="'.number_format(($row3[$x]['Tasa_Cuota_CFDI'] / 100), 6).'" Importe="'.number_format(((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100)), 2).'"/>';

																		$totalImTras += (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100);

																		if(count($imAgrupadosTras) == 0){
																			array_push($imAgrupadosTras, array('Base' => (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']), 'Impuesto' => $row3[$x]['Clave_CFDI'], 'TipoFactor' => $row3[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row3[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100))));
																		}else{
																			$encontro = false;
																			for ($y=0; $y < count($imAgrupadosTras); $y++) { 
																				if($imAgrupadosTras[$y]['Impuesto'] == $row3[$x]['Clave_CFDI'] && $imAgrupadosTras[$y]['TipoFactor'] == $row3[$x]['Tipo_Factor_CFDI'] && $imAgrupadosTras[$y]['TasaOCuota'] == ($row3[$x]['Tasa_Cuota_CFDI'] / 100)){

																					$imAgrupadosTras[$y]['Base'] += ($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento'];
																					$imAgrupadosTras[$y]['Importe'] += (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100);

																					$encontro = true;
																					break;
																				}
																			}

																			if($encontro == false){
																				array_push($imAgrupadosTras, array('Base' => (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']), 'Impuesto' => $row3[$x]['Clave_CFDI'], 'TipoFactor' => $row3[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row3[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100))));
																			}
																		}
																	}
																}else{
																	$impuestosRet .= '<cfdi:Retencion Base="'.number_format((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']), 2).'" Impuesto="'.$row3[$x]['Clave_CFDI'].'" TipoFactor="'.$row3[$x]['Tipo_Factor_CFDI'].'" TasaOCuota="'.number_format(($row3[$x]['Tasa_Cuota_CFDI'] / 100), 6).'" Importe="'.number_format(((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100)), 2).'"/>';

																	$totalImRete += (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100);

																	if(count($imAgrupadosRete) == 0){
																		array_push($imAgrupadosRete, array('Base' => (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']), 'Impuesto' => $row3[$x]['Clave_CFDI'], 'TipoFactor' => $row3[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row3[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100))));
																	}else{
																		$encontro = false;
																		for ($y=0; $y < count($imAgrupadosRete); $y++) { 
																			if($imAgrupadosRete[$y]['Impuesto'] == $row3[$x]['Clave_CFDI'] && $imAgrupadosRete[$y]['TipoFactor'] == $row3[$x]['Tipo_Factor_CFDI'] && $imAgrupadosRete[$y]['TasaOCuota'] == ($row3[$x]['Tasa_Cuota_CFDI'] / 100)){

																				$imAgrupadosRete[$y]['Base'] += ($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento'];
																				$imAgrupadosRete[$y]['Importe'] += (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100);

																				$encontro = true;
																				break;
																			}
																		}

																		if($encontro == false){
																			array_push($imAgrupadosRete, array('Base' => (($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']), 'Impuesto' => $row3[$x]['Clave_CFDI'], 'TipoFactor' => $row3[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row3[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento']) * ($row3[$x]['Tasa_Cuota_CFDI'] / 100))));
																		}
																	}
																}
															}
														}
													}
												}

												$impuestos = '';
												if(trim($impuestosTras) != '' || trim($impuestosRet) != ''){
													$impuestos .= '<cfdi:Impuestos>';
													
													if(trim($impuestosTras) != ''){
														$impuestos .= '
														<cfdi:Traslados>
															'.trim($impuestosTras).'
														</cfdi:Traslados>';
													}
													
													if(trim($impuestosRet) != ''){
														$impuestos .= '
														<cfdi:Retenciones>
															'.trim($impuestosRet).'
														</cfdi:Retenciones>';
													}

													$impuestos .= '
													</cfdi:Impuestos>';
												}

												$productos .= '<cfdi:Concepto ClaveProdServ="'.$row2[$i]['Clave_ProdServ_CFDI'].'" NoIdentificacion="'.$row2[$i]['Codigo'].'" Cantidad="'.number_format($row2[$i]['Cantidad'], 2).'" ClaveUnidad="'.$claveUnidad.'" Unidad="'.$presentacion.'" Descripcion="'.$row2[$i]['Descripcion'].'" ValorUnitario="'.number_format($row2[$i]['Precio'], 2).'" Importe="'.number_format(($row2[$i]['Cantidad'] * $row2[$i]['Precio']), 2).'" Descuento="'.number_format($row2[$i]['Descuento'], 2).'" ObjetoImp="'.$row2[$i]['Objeto_Impuesto_CFDI'].'">
												      	'.$impuestos.'
												</cfdi:Concepto>';

											    $subtotal += $row2[$i]['Cantidad'] * $row2[$i]['Precio'];
											}
										}
									}
								}
								
								if($error == false){
									$uuids = '';
									$query5 = "DELETE FROM relacionados_cfdi WHERE FK_Venta = '$id'";
									$error4 = $omodelo->_insertar($query5);

									if($error4 == 'si'){
										echo "Error 13: ".mysqli_error($omodelo->link);
									}

									$relaciones = json_decode($relaciones, true);
									if (count($relaciones) > 0) {
										$uuids .= '
										<cfdi:CfdiRelacionados TipoRelacion="'.$relacionCFDI.'">';
										
										foreach ($relaciones as $rel) {
											$query6 = "INSERT INTO relacionados_cfdi SET FK_Venta = '$id', UUID = '".trim($rel['UUID'])."'";
											$error5 = $omodelo->_insertar($query6);

											if($error5 == 'si'){
												echo "Error 14: ".mysqli_error($omodelo->link);
											}

											$uuids .= '
											<cfdi:CfdiRelacionado UUID="'.trim($rel['UUID']).'"/>';
										}

										$uuids .= '
										</cfdi:CfdiRelacionados>';
									}

									$totalImpuestos = '';
									if($totalImTras > 0 || $totalImRete > 0){
										if($totalImTras > 0 && $totalImRete > 0){
											$totalImpuestos .= '<cfdi:Impuestos TotalImpuestosRetenidos="'.number_format($totalImRete, 2).'" TotalImpuestosTrasladados="'.number_format($totalImTras, 2).'">';
										}else if($totalImTras > 0){
											$totalImpuestos .= '<cfdi:Impuestos TotalImpuestosTrasladados="'.number_format($totalImTras, 2).'">';
										}else{
											$totalImpuestos .= '<cfdi:Impuestos TotalImpuestosRetenidos="'.number_format($totalImRete, 2).'">';
										}

										if(count($imAgrupadosRete) > 0){
											$totalImpuestos .= '
											<cfdi:Retenciones>';

											foreach ($imAgrupadosRete as $imp) {
												$totalImpuestos .= '
												<cfdi:Retencion Base="'.number_format($imp['Base'], 2).'" Impuesto="'.$imp['Impuesto'].'" TipoFactor="'.$imp['TipoFactor'].'" TasaOCuota="'.number_format($imp['TasaOCuota'], 6).'" Importe="'.number_format($imp['Importe'], 2).'"/>';
											}

											$totalImpuestos .= '
											</cfdi:Retenciones>';
										}

										if(count($imAgrupadosTras) > 0){
											$totalImpuestos .= '
											<cfdi:Traslados>';

											foreach ($imAgrupadosTras as $imp) {
												$totalImpuestos .= '
												<cfdi:Traslado Base="'.number_format($imp['Base'], 2).'" Impuesto="'.$imp['Impuesto'].'" TipoFactor="'.$imp['TipoFactor'].'" TasaOCuota="'.number_format($imp['TasaOCuota'], 6).'" Importe="'.number_format($imp['Importe'], 2).'"/>';
											}

											$totalImpuestos .= '
											</cfdi:Traslados>';
										}

										$totalImpuestos .= '
										</cfdi:Impuestos>';
									}

									$global = '';
									if($row1[0]['FK_Cliente'] == '1'){
										$global = '
										<cfdi:InformacionGlobal Periodicidad="'.$periodicidadCFDI.'" Meses="'.$mesesCFDI.'" Año="'.$anoCFDI.'"/>';
										$row1[0]['Codigo_Postal_Cliente'] = trim($row1[0]['CP_Sucursal']);
									}

									// Para usar openssl agergar la variable de entorno en windows, nombre: openssl, ruta: C:\xampp\php\extras\openssl\openssl.exe 
									// Si se ejecuta en openssl.exe quitar la palabra openssl
									$noCertificado = shell_exec('openssl x509 -inform DER -in "'.__DIR__.'../../vistas/assets/archivos/certificados/'.$row[0]['Certificado'].'" -noout -serial');

									$Certificado = ''; # Variable vacia para almacenar el número de certificado

									$noCertificado = str_replace(' ', '', $noCertificado); # Función para eliminar los espacios en la cadena

									$arr1 = str_split($noCertificado); # Función para convertir la cadena en un array

									# Ciclo para obtener el número de certificado
									for($i = 7; $i < count($arr1); $i++) { # La variable $i comienza en la posición 7, para obtener solo el valor del certificado
										if(($i % 2) == 0) { # Si la posición es par, el valor de la posición se almacena en la variable $Certificado
											$Certificado = ($Certificado.($arr1[$i])); # Concatena las posiciones pares del array para obtener el número de certificado
										}
									}

									$certificado = str_replace(array('\n', '\r'), '', base64_encode(file_get_contents(__DIR__.'../../vistas/assets/archivos/certificados/'.$row[0]['Certificado'])));

									$textoXML = '<?xml version="1.0" encoding="UTF-8"?>
									<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd" Version="4.0" Serie="'.$id.'" Folio="'.str_pad($id, 8, '0', STR_PAD_LEFT).'" Fecha="'.str_replace(' ', 'T', $fecha).'" Sello="" FormaPago="'.$formaPagoCFDI.'" NoCertificado="'.$Certificado.'" Certificado="'.$certificado.'" SubTotal="'.number_format($subtotal, 2).'" Descuento="'.number_format($row1[0]['Descuento'], 2).'" Moneda="MXN" Total="'.number_format($row1[0]['Total'], 2).'" TipoDeComprobante="I" Exportacion="01" MetodoPago="PUE" LugarExpedicion="'.trim($row1[0]['CP_Sucursal']).'">'.$global.$uuids.'
									 	<cfdi:Emisor Rfc="'.$row[0]['RFC'].'" Nombre="'.$row[0]['Nombre'].'" RegimenFiscal="'.$row[0]['Regimen'].'"/>
									  	<cfdi:Receptor Rfc="'.$row1[0]['RFC_Cliente'].'" Nombre="'.$row1[0]['Razon_CFDI'].'" DomicilioFiscalReceptor="'.$row1[0]['Codigo_Postal_Cliente'].'" RegimenFiscalReceptor="'.$row1[0]['Regimen_CFDI'].'" UsoCFDI="'.$usoCFDI.'"/>
									  	<cfdi:Conceptos>
										    '.$productos.'
									  	</cfdi:Conceptos>
									  	'.$totalImpuestos.'
									  	<cfdi:Complemento></cfdi:Complemento>
									</cfdi:Comprobante>';

									// Crear un objeto DOMDocument para cargar el CFDI
									$xml = new DOMDocument("1.0","UTF-8"); 
									// Cargar el CFDI
									$xml->loadXML($textoXML);
											 
									// Crear un objeto DOMDocument para cargar el archivo de transformación XSLT
									// Cambiar la version en todos los archivos de la cadena original (descargar los archivos de tramites y servicios sat)
									$xsl = new DOMDocument();
									$xsl->load(__DIR__.'../../vistas/assets/archivos/cadena/cadenaoriginal_4_0.xslt');
											 
									// Crear el procesador XSLT que nos generará la cadena original con base en las reglas descritas en el XSLT
									// Agregar extension=php_xsl.dll o descomentar en xampp windows
									$proc = new XSLTProcessor;
									// Cargar las reglas de transformación desde el archivo XSLT.
									$proc->importStyleSheet($xsl);
									// Generar la cadena original y asignarla a una variable
									$cadenaOriginal = $proc->transformToXML($xml);

									// Se debe convertir la key a .pem
									shell_exec('openssl pkcs8 -inform DER -in "'.__DIR__.'../../vistas/assets/archivos/certificados/'.$row[0]['Key_Cer'].'" -passin pass:'.$row[0]['Contrasena'].' -out "'.__DIR__.'../../vistas/assets/archivos/certificados/'.$row[0]['Key_Cer'].'.pem"');

									$private = openssl_pkey_get_private(file_get_contents(__DIR__.'../../vistas/assets/archivos/certificados/'.$row[0]['Key_Cer'].'.pem'));

									// Se genera el sello mediante la key y la cadena original 
									// La cadena original se conforma del xml y el xls de la candena original de la version sat 
									openssl_sign($cadenaOriginal, $sig, $private, OPENSSL_ALGO_SHA256);
									
									// Se hace el sello en formato base64		    
									$sello = base64_encode($sig);

									$textoXML = str_replace('Sello=""', 'Sello="'.$sello.'"', $textoXML);

									$nuevoXML = str_replace('"', "'", $textoXML);

									$nuevoXML = preg_replace('/(\v|\s)+/', ' ', $nuevoXML);

									$response = $client->request('POST', 'https://testapi.facturoporti.com.mx/servicios/timbrar/xml', [
								  		'body' => '{"cfdi": "'.$nuevoXML.'"}',
								  		'headers' => [
								    		'accept' => 'application/json',
								    		'authorization' => 'Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoialYrdVVUYmtWNmUxRmNZb2cvNWtGQT09IiwibmJmIjoxNjY5NzY1MTM1LCJleHAiOjE2NzIzNTcxMzUsImlzcyI6IlNjYWZhbmRyYVNlcnZpY2lvcyIsImF1ZCI6IlNjYWZhbmRyYSBTZXJ2aWNpb3MiLCJJZEVtcHJlc2EiOiJqVit1VVRia1Y2ZTFGY1lvZy81a0ZBPT0iLCJJZFVzdWFyaW8iOiJidXlaYzFMWUl5VURaSGhGR3NqaGdRPT0ifQ.7NfXWvnQSy_2PtWEnzItEtZseWV0VqahTuAS3YPG8TE',
								    		'content-type' => 'application/*+json',
								  		],
									]);

									//echo $response->getBody();

									$respuesta  = json_decode($response->getBody(), true);	

									if($respuesta['estatus']['informacionTecnica'] == 'Ok' && $respuesta['estatus']['descripcion'] == 'Timbrado del CFDI realizado con éxito'){
										$textoXML = str_replace(
											'<cfdi:Complemento></cfdi:Complemento>', 
										  	'<cfdi:Complemento>
										  		<tfd:TimbreFiscalDigital xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd" Version="1.1" UUID="'.$respuesta['timbrado']['uuid'].'" FechaTimbrado="'.$respuesta['timbrado']['fecha'].'" RfcProvCertif="'.$respuesta['timbrado']['rfcProvCertif'].'" SelloCFD="'.$respuesta['timbrado']['selloCFD'].'" NoCertificadoSAT="'.$respuesta['timbrado']['noCertificado'].'" SelloSAT="'.$respuesta['timbrado']['selloSAT'].'"/>
										  	</cfdi:Complemento>', $textoXML);

										$query4 = "UPDATE ventas SET Facturada = '1', Version_CFDI = '4.0', Fecha_Expedicion_CFDI = '$fecha', Sello_CFDI = '$sello', Forma_Pago_CFDI = '$formaPagoCFDI', No_Certificado_CFDI = '$Certificado', Certificado_CFDI = '$certificado', Moneda_CFDI = 'MXN', Tipo_Comprobante_CFDI = 'I', Exportacion_CFDI = '01', Metodo_Pago_CFDI = 'PUE', Lugar_Expedicion_CFDI = '".trim($row1[0]['CP_Sucursal'])."', Confirmacion_CFDI = '', Emisor_RFC_CFDI = '".$row[0]['RFC']."', Emisor_Nombre_CFDI = '".$row[0]['Nombre']."', Emisor_Regimen_Fiscal_CFDI = '".$row[0]['Regimen']."', Receptor_RFC_CFDI = '".$row1[0]['RFC_Cliente']."', Receptor_Nombre_CFDI = '".$row1[0]['Razon_CFDI']."', Receptor_Domicilio_CFDI = '".$row1[0]['Codigo_Postal_Cliente']."', Receptor_Regimen_Fiscal_CFDI = '".$row1[0]['Regimen_CFDI']."', Receptor_Uso_CFDI = '".$usoCFDI."', UUID_CFDI = '".$respuesta['timbrado']['uuid']."', Fecha_Timbrado_CFDI = '".str_replace('T', ' ', $respuesta['timbrado']['fecha'])."', Rfc_ProvCertif_CFDI = '".$respuesta['timbrado']['rfcProvCertif']."', Sello_CFD_CFDI = '".$respuesta['timbrado']['selloCFD']."', No_Certificado_SAT_CFDI = '".$respuesta['timbrado']['noCertificado']."', Sello_SAT_CFDI = '".$respuesta['timbrado']['selloSAT']."', Periodicidad_CFDI = '".$periodicidadCFDI."', Meses_CFDI = '".$mesesCFDI."', Ano_CFDI = '".$anoCFDI."', Relacion_CFDI = '".$relacionCFDI."', Cadena_CFDI = '".$respuesta['timbrado']['cadenaOriginal']."' WHERE ID_Venta = '$id'";
										$error2 = $omodelo->_insertar($query4);

										if($error2 == 'si'){
											echo "Error 11: ".mysqli_error($omodelo->link);
										}else{
											echo "Correcto";

											$omodelo->movimiento($query4, $_SESSION['user_admin']['ID_Usuario']);
										}
									}else{
										echo $respuesta['estatus']['informacionTecnica'].'</p><p>'.$respuesta['estatus']['descripcion'];
									}
								}
							}
						}else{
							echo "Error 13 No encontro";
						}
					}
				}
			}else{
				echo "Error 12 Tabla datos generales";
			}
		}		
	}
}

/*
	{
		"token": "eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoialYrdVVUYmtWNmUxRmNZb2cvNWtGQT09IiwibmJmIjoxNjY5NzY1MTM1LCJleHAiOjE2NzIzNTcxMzUsImlzcyI6IlNjYWZhbmRyYVNlcnZpY2lvcyIsImF1ZCI6IlNjYWZhbmRyYSBTZXJ2aWNpb3MiLCJJZEVtcHJlc2EiOiJqVit1VVRia1Y2ZTFGY1lvZy81a0ZBPT0iLCJJZFVzdWFyaW8iOiJidXlaYzFMWUl5VURaSGhGR3NqaGdRPT0ifQ.7NfXWvnQSy_2PtWEnzItEtZseWV0VqahTuAS3YPG8TE",
	  	"codigo": "000",
	  	"mensaje": "Token generado correctamente"
	}
*/
?>