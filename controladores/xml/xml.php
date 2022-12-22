<?php 
	include '../../modelo/m_modelo.php'; 
	$omodelo = new m_modelo();
	extract($_GET);
	$fecha = date('Y-m-d H:i:s');
	$id = $omodelo->link->real_escape_string($id);

	$query = "SELECT ID_Venta, FK_Cliente, clientes.Calle AS Calle_Cliente, clientes.No_Exterior AS No_Exterior_Cliente, clientes.No_Interior AS No_Interior_Cliente, clientes.Colonia AS Colonia_Cliente, clientes.Ciudad AS Ciudad_Cliente, clientes.Estado AS Estado_Cliente, clientes.Pais AS Pais_Cliente, sucursales.Nombre AS Sucursal, sucursales.Calle AS Calle_Sucursal, sucursales.No_Exterior AS No_Exterior_Sucursal, sucursales.No_Interior AS No_Interior_Sucursal, sucursales.Colonia AS Colonia_Sucursal, sucursales.Ciudad AS Ciudad_Sucursal, sucursales.Estado AS Estado_Sucursal, sucursales.Pais AS Pais_Sucursal, ventas.Descuento AS Descuento, Total, ventas.Fecha_Registro AS Fecha_Registro, Version_CFDI, Fecha_Expedicion_CFDI, Sello_CFDI, Forma_Pago_CFDI, No_Certificado_CFDI, Certificado_CFDI, Moneda_CFDI, Tipo_Comprobante_CFDI, Exportacion_CFDI, Metodo_Pago_CFDI, Lugar_Expedicion_CFDI, Confirmacion_CFDI, Emisor_RFC_CFDI, Emisor_Nombre_CFDI, Emisor_Regimen_Fiscal_CFDI, Receptor_RFC_CFDI, Receptor_Nombre_CFDI, Receptor_Domicilio_CFDI, Receptor_Regimen_Fiscal_CFDI, Receptor_Uso_CFDI, UUID_CFDI, Fecha_Timbrado_CFDI, Rfc_ProvCertif_CFDI, Sello_CFD_CFDI, No_Certificado_SAT_CFDI, Sello_SAT_CFDI, Periodicidad_CFDI, Meses_CFDI, Ano_CFDI, Relacion_CFDI FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id' AND Facturada = '1' AND Cancelada = '0'";
	$row = $omodelo->_consultar($query);
	$numerofilas = $omodelo->numerofilas;

	if($row == 'si'){
		echo "Error 1: ".mysqli_error($omodelo->link);
	}else{
		if($numerofilas > 0){
			$productos = ''; $subtotal = 0; $totalImTras = 0; $totalImRete = 0; $imAgrupadosTras = []; $imAgrupadosRete = []; $error = false;
			$query1 = "SELECT ID_Detalle_Venta, Identificacion_CFDI, Clave_ProdServ_CFDI, Clave_Unidad_CFDI, Unidad_CFDI, Objeto_Impuesto_CFDI, Descripcion, Precio, Cantidad, Descuento, Total FROM detalles_ventas WHERE FK_Venta = '$id'";
			$row1 = $omodelo->_consultar($query1);
			$numerofilas1 = $omodelo->numerofilas;

			if($row1 == 'si'){
				echo "Error 2: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas1 > 0){
					for ($i=0; $i < $numerofilas1; $i++) { 
						$impuestosTras = ''; $impuestosRet = '';
						$query2 = "SELECT ID_Impuesto, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row1[$i]['ID_Detalle_Venta']."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;

						if($row2 == 'si'){
							echo "Error 3: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								for ($x=0; $x < $numerofilas2; $x++) {
									if($row1[$i]['Objeto_Impuesto_CFDI'] != '01' && $row1[$i]['Objeto_Impuesto_CFDI'] != '03'){
										if($row2[$x]['Tipo_Impuesto_CFDI'] == 'Trasladado'){
											if($row2[$x]['Tipo_Factor_CFDI'] == 'Exento'){
												$impuestosTras .= '<cfdi:Traslado Base="'.number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 2).'" Impuesto="'.$row2[$x]['Clave_CFDI'].'" TipoFactor="'.$row2[$x]['Tipo_Factor_CFDI'].'"/>';
											}else{
												$impuestosTras .= '<cfdi:Traslado Base="'.number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 2).'" Impuesto="'.$row2[$x]['Clave_CFDI'].'" TipoFactor="'.$row2[$x]['Tipo_Factor_CFDI'].'" TasaOCuota="'.number_format(($row2[$x]['Tasa_Cuota_CFDI'] / 100), 6).'" Importe="'.number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2).'"/>';

												$totalImTras += (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100);

												if(count($imAgrupadosTras) == 0){
													array_push($imAgrupadosTras, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100))));
												}else{
													$encontro = false;
													for ($y=0; $y < count($imAgrupadosTras); $y++) { 
														if($imAgrupadosTras[$y]['Impuesto'] == $row2[$x]['Clave_CFDI'] && $imAgrupadosTras[$y]['TipoFactor'] == $row2[$x]['Tipo_Factor_CFDI'] && $imAgrupadosTras[$y]['TasaOCuota'] == ($row2[$x]['Tasa_Cuota_CFDI'] / 100)){
															$imAgrupadosTras[$y]['Base'] += ($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento'];
															$imAgrupadosTras[$y]['Importe'] += (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100);
															$encontro = true;
															break;
														}
													}

													if($encontro == false){
														array_push($imAgrupadosTras, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100))));
													}
												}
											}
										}else{
											$impuestosRet .= '<cfdi:Retencion Base="'.number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 2).'" Impuesto="'.$row2[$x]['Clave_CFDI'].'" TipoFactor="'.$row2[$x]['Tipo_Factor_CFDI'].'" TasaOCuota="'.number_format(($row2[$x]['Tasa_Cuota_CFDI'] / 100), 6).'" Importe="'.number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2).'"/>';

											$totalImRete += (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row2[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100);

											if(count($imAgrupadosRete) == 0){
												array_push($imAgrupadosRete, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100))));
											}else{
												$encontro = false;
												for ($y=0; $y < count($imAgrupadosRete); $y++) { 
													if($imAgrupadosRete[$y]['Impuesto'] == $row2[$x]['Clave_CFDI'] && $imAgrupadosRete[$y]['TipoFactor'] == $row2[$x]['Tipo_Factor_CFDI'] && $imAgrupadosRete[$y]['TasaOCuota'] == ($row2[$x]['Tasa_Cuota_CFDI'] / 100)){
														$imAgrupadosRete[$y]['Base'] += ($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento'];
														$imAgrupadosRete[$y]['Importe'] += (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100);
														$encontro = true;
														break;
													}
												}

												if($encontro == false){
													array_push($imAgrupadosRete, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => ((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100))));
												}
											}
										}
									}
								}
							}
						}

					$impuestos = '';
					if(trim($impuestosTras) != '' || trim($impuestosRet) != ''){
						$impuestos .= '
						<cfdi:Impuestos>';
														
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

					$productos .= '<cfdi:Concepto ClaveProdServ="'.$row1[$i]['Clave_ProdServ_CFDI'].'" NoIdentificacion="'.$row1[$i]['Identificacion_CFDI'].'" Cantidad="'.number_format($row1[$i]['Cantidad'], 2).'" ClaveUnidad="'.$row1[$i]['Clave_Unidad_CFDI'].'" Unidad="'.$row1[$i]['Unidad_CFDI'].'" Descripcion="'.$row1[$i]['Descripcion'].'" ValorUnitario="'.number_format($row1[$i]['Precio'], 2).'" Importe="'.number_format(($row1[$i]['Cantidad'] * $row1[$i]['Precio']), 2).'" Descuento="'.number_format($row1[$i]['Descuento'], 2).'" ObjetoImp="'.$row1[$i]['Objeto_Impuesto_CFDI'].'">'.$impuestos.'</cfdi:Concepto>';

					$subtotal += $row1[$i]['Cantidad'] * $row1[$i]['Precio'];
				}
			}
		}
	}
								
	if($error == false){
		$uuids = '';
		$query1 = "SELECT ID_Relacion, UUID FROM relacionados_cfdi WHERE FK_Venta = '$id'";
		$row1 = $omodelo->_consultar($query1);
		$numerofilas1 = $omodelo->numerofilas;

		if($row1 == 'si'){
			echo "Error 4: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas1 > 0){
				$uuids .= '
				<cfdi:CfdiRelacionados TipoRelacion="'.$row[0]['Relacion_CFDI'].'">';
				
				for ($i=0; $i < $numerofilas1; $i++) {				
					$uuids .= '
					<cfdi:CfdiRelacionado UUID="'.$row1[$i]['UUID'].'"/>';
				}

				$uuids .= '
				</cfdi:CfdiRelacionados>';
			}
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
		if($row[0]['FK_Cliente'] == '1'){
			$global = '
			<cfdi:InformacionGlobal Periodicidad="'.$row[0]['Periodicidad_CFDI'].'" Meses="'.$row[0]['Meses_CFDI'].'" Año="'.$row[0]['Ano_CFDI'].'"/>';
		}

		$textoXML = '<?xml version="1.0" encoding="UTF-8"?>
			<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd" Version="4.0" Serie="'.$id.'" Folio="'.str_pad($id, 8, '0', STR_PAD_LEFT).'" Fecha="'.str_replace(' ', 'T', $row[0]['Fecha_Expedicion_CFDI']).'" Sello="'.$row[0]['Sello_CFDI'].'" FormaPago="'.$row[0]['Forma_Pago_CFDI'].'" NoCertificado="'.$row[0]['No_Certificado_CFDI'].'" Certificado="'.$row[0]['Certificado_CFDI'].'" SubTotal="'.number_format($subtotal, 2).'" Descuento="'.number_format($row[0]['Descuento'], 2).'" Moneda="'.$row[0]['Moneda_CFDI'].'" Total="'.number_format($row[0]['Total'], 2).'" TipoDeComprobante="'.$row[0]['Tipo_Comprobante_CFDI'].'" Exportacion="'.$row[0]['Exportacion_CFDI'].'" MetodoPago="'.$row[0]['Metodo_Pago_CFDI'].'" LugarExpedicion="'.trim($row[0]['Lugar_Expedicion_CFDI']).'">'.$global.$uuids.'
				<cfdi:Emisor Rfc="'.$row[0]['Emisor_RFC_CFDI'].'" Nombre="'.$row[0]['Emisor_Nombre_CFDI'].'" RegimenFiscal="'.$row[0]['Emisor_Regimen_Fiscal_CFDI'].'"/>
				<cfdi:Receptor Rfc="'.$row[0]['Receptor_RFC_CFDI'].'" Nombre="'.$row[0]['Receptor_Nombre_CFDI'].'" DomicilioFiscalReceptor="'.$row[0]['Receptor_Domicilio_CFDI'].'" RegimenFiscalReceptor="'.$row[0]['Receptor_Regimen_Fiscal_CFDI'].'" UsoCFDI="'.$row[0]['Receptor_Regimen_Fiscal_CFDI'].'"/>
				<cfdi:Conceptos>
					'.$productos.'
				</cfdi:Conceptos>
				'.$totalImpuestos.'
				<cfdi:Complemento>
					<tfd:TimbreFiscalDigital xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd" Version="1.1" UUID="'.$row[0]['UUID_CFDI'].'" FechaTimbrado="'.$row[0]['Fecha_Timbrado_CFDI'].'" RfcProvCertif="'.$row[0]['Rfc_ProvCertif_CFDI'].'" SelloCFD="'.$row[0]['Sello_CFD_CFDI'].'" NoCertificadoSAT="'.$row[0]['No_Certificado_SAT_CFDI'].'" SelloSAT="'.$row[0]['Sello_SAT_CFDI'].'"/>
				</cfdi:Complemento>
			</cfdi:Comprobante>';

			header('Content-type: text/xml');
			header('Content-Disposition: attachment; filename="'.$row[0]['UUID_CFDI'].'.xml"');

			echo $textoXML;
			exit();
		}
	}	
?>