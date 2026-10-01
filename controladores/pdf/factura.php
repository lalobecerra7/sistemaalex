<?php 
	require_once 'mpdf/vendor/autoload.php';
	//include('phpqrcode/qrlib.php');
	include '../../modelo/m_modelo.php'; 

	$omodelo = new m_modelo();
	extract($_GET);
	$fecha = date('Y-m-d H:i:s');
	$id = $omodelo->link->real_escape_string($id);

	$query = "SELECT ID_Venta, FK_Cliente, clientes.Calle AS Calle_Cliente, clientes.No_Exterior AS No_Exterior_Cliente, clientes.No_Interior AS No_Interior_Cliente, clientes.Colonia AS Colonia_Cliente, clientes.Ciudad AS Ciudad_Cliente, clientes.Estado AS Estado_Cliente, clientes.Pais AS Pais_Cliente, sucursales.Nombre AS Sucursal, sucursales.Calle AS Calle_Sucursal, sucursales.No_Exterior AS No_Exterior_Sucursal, sucursales.No_Interior AS No_Interior_Sucursal, sucursales.Colonia AS Colonia_Sucursal, sucursales.Ciudad AS Ciudad_Sucursal, sucursales.Estado AS Estado_Sucursal, sucursales.Pais AS Pais_Sucursal, ventas.Descuento AS Descuento, Total, ventas.Fecha_Registro AS Fecha_Registro, Version_CFDI, Fecha_Expedicion_CFDI, Sello_CFDI, Forma_Pago_CFDI, No_Certificado_CFDI, Certificado_CFDI, Moneda_CFDI, Tipo_Comprobante_CFDI, Exportacion_CFDI, Metodo_Pago_CFDI, Lugar_Expedicion_CFDI, Confirmacion_CFDI, Emisor_RFC_CFDI, Emisor_Nombre_CFDI, Emisor_Regimen_Fiscal_CFDI, Receptor_RFC_CFDI, Receptor_Nombre_CFDI, Receptor_Domicilio_CFDI, Receptor_Regimen_Fiscal_CFDI, Receptor_Uso_CFDI, UUID_CFDI, Fecha_Timbrado_CFDI, Rfc_ProvCertif_CFDI, Sello_CFD_CFDI, No_Certificado_SAT_CFDI, Sello_SAT_CFDI, Periodicidad_CFDI, Meses_CFDI, Ano_CFDI, Relacion_CFDI, Cadena_CFDI FROM ventas INNER JOIN sucursales ON ID_Sucursal = 8 INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id' AND Facturada = '1' AND Estatus = 'Completada'";
	//FK_Sucursal = ID_Sucursal
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
						$impuestos = '';
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
												$impuestos .= '<p style="margin: 1px 0px;">($0) '.$row2[$x]['Impuesto_CFDI'].' 0%</p>';
											}else{
												$impuestos .= '<p style="margin: 1px 0px;">($'.number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2).') '.$row2[$x]['Impuesto_CFDI'].' '.$row2[$x]['Tasa_Cuota_CFDI'].'%</p>';

												$totalImTras += (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100);
											}
										}else{
											$impuestos .= '<p style="margin: 1px 0px;">($'.number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2).') '.$row2[$x]['Impuesto_CFDI'].' '.$row2[$x]['Tasa_Cuota_CFDI'].'%</p>';

											$totalImRete += (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row2[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100);
										}
									}
								}
							}
						}

						//<td>'.$row1[$i]['Unidad_CFDI'].'</td>
						$productos .= '<tr>
							<td>'.$row1[$i]['Clave_ProdServ_CFDI'].'</td>
							<td>'.$row1[$i]['Identificacion_CFDI'].'</td>
							<td>'.$row1[$i]['Descripcion'].'</td>
							<td>'.$row1[$i]['Clave_Unidad_CFDI'].'</td>
							<td>'.number_format($row1[$i]['Cantidad'], 2).'</td>
							<td>$'.number_format($row1[$i]['Precio'], 2).'</td>
							<td>$'.number_format(($row1[$i]['Cantidad'] * $row1[$i]['Precio']), 2).'</td>
							<td>$'.number_format($row1[$i]['Descuento'], 2).'</td>
							<td>'.$impuestos.'</td>
							<td>$'.number_format($row1[$i]['Total'], 2).'</td>
						</tr>';

						$subtotal += $row1[$i]['Cantidad'] * $row1[$i]['Precio'];
					}
				}
			}
		}
	}
								
	if($error == false){
		$uuids = '';
		$relaciones = array(
			'01' => 'Nota de crédito de los documentos relacionados',
            '02' => 'Nota de débito de los documentos relacionados',
            '03' => 'Devolución de mercancía sobre facturas o traslados previos',
            '04' => 'Sustitución de los CFDI previos',
            '05' => 'Traslados de mercancías facturados previamente',
            '06' => 'Factura generada por los traslados previos',
            '07' => 'CFDI por aplicación de anticipo'
		);

		$query1 = "SELECT ID_Relacion, UUID FROM relacionados_cfdi WHERE FK_Venta = '$id'";
		$row1 = $omodelo->_consultar($query1);
		$numerofilas1 = $omodelo->numerofilas;

		if($row1 == 'si'){
			echo "Error 4: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas1 > 0){
				$uuids .= '<div style="padding: 5px 10px; font-size: 11px;">
					<h4 style="margin: 2px 0px;">CFDI Relacionados - Tipo de relacion: '.$row[0]['Relacion_CFDI'].' '.$relaciones[$row[0]['Relacion_CFDI']].'</h4>
					';
				
				for ($i=0; $i < $numerofilas1; $i++) {				
					$uuids .= '<p style="margin: 2px 0px;">'.$row1[$i]['UUID'].'</p>';
				}

				$uuids .= '</div>';
			}
		}

		$totalImpuetos = '';
		if($totalImTras > 0){ 
			$totalImpuetos .= '<tr>
				<th colspan="8" style="text-align: right;">Total Impuestos Trasladados</th>
				<td colspan="3">$'.number_format($totalImTras, 2).'</td>
			</tr>';
		}

		if($totalImRete > 0){
			$totalImpuetos .= '<tr>
				<th colspan="8" style="text-align: right;">Total Impuestos Trasladados</th>
				<td colspan="3">$'.number_format($totalImRete, 2).'</td>
			</tr>';
		}

		$periodicidad = array(
			'01' => 'Diario',
            '02' => 'Semanal',
            '03' => 'Quincenal',
            '04' => 'Mensual'
		);

		$meses = array(
			'01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
		);

		$global = '';
		if($row[0]['FK_Cliente'] == '1'){
			$global = '<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
				<h4 style="margin: 2px 0px;">Periodicidad</h4>
				<p style="margin: 2px 0px;">'.$row[0]['Periodicidad_CFDI'].' - '.$periodicidad[$row[0]['Periodicidad_CFDI']].'</p>
			</div>
			<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
				<h4 style="margin: 2px 0px;">Meses</h4>
				<p style="margin: 2px 0px;">'.$row[0]['Meses_CFDI'].' - '.$meses[$row[0]['Meses_CFDI']].'</p>
			</div>
			<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
				<h4 style="margin: 2px 0px;">Año</h4>
				<p style="margin: 2px 0px;">'.$row[0]['Ano_CFDI'].'</p>
			</div>';
		}

		$mpdf = new \Mpdf\Mpdf(
			[
				'mode' => 'utf-8', 
				'format' => 'Letter', 
				'margin_left' => 12,    	
				'margin_right' => 12,    	
				'margin_top' => 12,     
				'margin_bottom' => 12
			]
		);
			
		/*
		$mpdf->SetDefaultBodyCSS('background', "url('../../vistas/assets/ejemplo.jpg')");
		$mpdf->SetDefaultBodyCSS('background-image-resize', 6);
		*/

		/*$mpdf->SetHTMLFooter('
		<div width="100%">
			<p align="center">Página {PAGENO}/{nbpg}</td>
		</div>');*/

		QRcode::png('https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id='.$row[0]['UUID_CFDI'].'&re='.$row[0]['Emisor_RFC_CFDI'].'&rr='.$row[0]['Receptor_RFC_CFDI'].'&tt='.$row[0]['Total'].'&fe='.substr($row[0]['Sello_CFD_CFDI'], -8), 'qr.png', 'H', 7);

		$domicilioGeneral = $row[0]['Calle_Sucursal'].' #'.$row[0]['No_Exterior_Sucursal'];
        if($row[0]['No_Interior_Sucursal'] != ''){
            $domicilioGeneral .= ' - '.$row[0]['No_Interior_Sucursal'].',';
        }

        if($row[0]['Colonia_Sucursal'] != ''){
            $domicilioGeneral .= ' '.$row[0]['Colonia_Sucursal'];
        }

        $domicilioGeneral .= ' C.P. '.$row[0]['Lugar_Expedicion_CFDI'].', '.$row[0]['Ciudad_Sucursal'].', '.$row[0]['Estado_Sucursal'].' '.$row[0]['Pais_Sucursal'].'.';

        $domicilioCliente = $row[0]['Calle_Cliente'].' #'.$row[0]['No_Exterior_Cliente'];
        if($row[0]['No_Interior_Cliente'] != ''){
            $domicilioCliente .= ' - '.$row[0]['No_Interior_Cliente'].',';
        }

        if($row[0]['Colonia_Cliente'] != ''){
            $domicilioCliente .= ' '.$row[0]['Colonia_Cliente'];
        }

        $domicilioCliente .= ' C.P. '.$row[0]['Receptor_Domicilio_CFDI'].', '.$row[0]['Ciudad_Cliente'].', '.$row[0]['Estado_Cliente'].' '.$row[0]['Pais_Cliente'].'.';

        if($row[0]['FK_Cliente'] == '1'){
            $domicilioCliente = 'C.P. '.$row[0]['Receptor_Domicilio_CFDI'];
        }

       	$regimen = array(
            '601' => 'General de Ley Personas Morales',
            '603' => 'Personas Morales con Fines no Lucrativos',
            '605' => 'Sueldos y Salarios e Ingresos Asimilados a Salarios',
            '606' => 'Arrendamiento',
            '607' => 'Régimen de Enajenación o Adquisición de Bienes',
            '608' => 'Demás ingresos',
            '610' => 'Residentes en el Extranjero sin Establecimiento Permanente en México',
            '611' => 'Ingresos por Dividendos (socios y accionistas)',
            '612' => 'Personas Físicas con Actividades Empresariales y Profesionales',
            '614' => 'Ingresos por intereses',
            '615' => 'Régimen de los ingresos por obtención de premios',
            '616' => 'Sin obligaciones fiscales',
            '620' => 'Sociedades Cooperativas de Producción que optan por diferir sus ingresos',
            '621' => 'Incorporación Fiscal',
            '622' => 'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras',
            '623' => 'Opcional para Grupos de Sociedades',
            '624' => 'Coordinados',
            '625' => 'Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas',
            '626' => 'Régimen Simplificado de Confianza'
        );

        $tipoCompro = array(
        	'I' => 'Ingreso',
            'E' => 'Egreso',
            'T' => 'Traslado',
            'N' => 'Nómina',
            'P' => 'Pago'
       	);

       	$metodosPago = array(
       		'PUE' => 'Pago en una sola exhibición',
			'PPD' => 'Pago en parcialidades o diferido'
       	);

       	$formasPago = array(
       		'01' => 'Efectivo',
            '02' => 'Cheque nominativo',
            '03' => 'Transferencia electrónica de fondos',
            '04' => 'Tarjeta de crédito',
            '05' => 'Monedero electrónico',
            '06' => 'Dinero electrónico',
            '08' => 'Vales de despensa',
            '12' => 'Dación en pago',
            '13' => 'Pago por subrogación',
            '14' => 'Pago por consignación',
            '15' => 'Condonación',
            '17' => 'Compensación',
            '23' => 'Novación',
            '24' => 'Confusión',
            '25' => 'Remisión de deuda',
            '26' => 'Prescripción o caducidad',
            '27' => 'A satisfacción del acreedor',
            '28' => 'Tarjeta de débito',
            '29' => 'Tarjeta de servicios',
            '30' => 'Aplicación de anticipos',
            '31' => 'Intermediario pagos',
            '99' => 'Por definir'
       	);

       	$usos = array(
            'G01' => 'Adquisición de mercancías.',
            'G02' => 'Devoluciones, descuentos o bonificaciones.',
            'G03' => 'Gastos en general.',
            'I01' => 'Construcciones.',
            'I02' => 'Mobiliario y equipo de oficina por inversiones.',
            'I03' => 'Equipo de transporte.',
            'I04' => 'Equipo de computo y accesorios.',
            'I05' => 'Dados, troqueles, moldes, matrices y herramental.',
            'I06' => 'Comunicaciones telefónicas.',
            'I07' => 'Comunicaciones satelitales.',
            'I08' => 'Otra maquinaria y equipo.',
            'D01' => 'Honorarios médicos, dentales y gastos hospitalarios.',
            'D02' => 'Gastos médicos por incapacidad o discapacidad.',
            'D03' => 'Gastos funerales.',
            'D04' => 'Donativos.',
            'D05' => 'Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).',
            'D06' => 'Aportaciones voluntarias al SAR.',
            'D07' => 'Primas por seguros de gastos médicos.',
            'D08' => 'Gastos de transportación escolar obligatoria.',
            'D09' => 'Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.',
            'D10' => 'Pagos por servicios educativos (colegiaturas).',
            'S01' => 'Sin efectos fiscales.',  
            'CP01' => 'Pagos',
            'CN01' => 'Nómina'
       	);

       	//<th>Unidad</th>
		$htmlFactura = '<div style="font-family: arial; font-size: 14px; color: #303030; height: 100%;">
			<div style="float: left; width: 15%; padding-top: 20px;">
				<img src="../../vistas/assets/img/favicon/favicon.jpg" style="width: 100%;"/>
			</div>
			<div style="float: left; width: 47%; padding: 0px 10px;">
				<h2 style="margin-bottom: 5px;">'.$row[0]['Emisor_Nombre_CFDI'].'</h2>
				<h5 style="margin: 2px; 0px;">RFC: '.$row[0]['Emisor_RFC_CFDI'].'</h5>
				<h5 style="margin: 2px; 0px;">Domicilio: '.$domicilioGeneral.'</h5>
				<h5 style="margin: 2px; 0px;">Régimen Fiscal: '.$row[0]['Emisor_Regimen_Fiscal_CFDI'].' - '.$regimen[$row[0]['Emisor_Regimen_Fiscal_CFDI']].'</h5>
				<h5 style="margin: 2px; 0px;">Lugar de expedición: '.$row[0]['Lugar_Expedicion_CFDI'].'</h5>
			</div>
			<div style="float: left; width: 34.65%; border: 2px solid #000;">
				<div style="background: #303030;">
					<h4 style="color: #FFF; text-align: center; margin: 5px 0px;">Factura</h4>
				</div>
				<div style="text-align: center; font-size: 11px; padding: 10px 0px;">
					<p style="margin: 2px 0px;">Versión CFDI: '.$row[0]['Version_CFDI'].'</p>
					<h4 style="margin: 2px 0px;">Folio:</h4>
					<p style="margin: 2px 0px;">'.str_pad($id, 8, '0', STR_PAD_LEFT).'</p>
					<h4 style="margin: 2px 0px;">Fecha Emisión:</h4>
					<p style="margin: 2px 0px;">'.str_replace('T', ' ', $row[0]['Fecha_Expedicion_CFDI']).'</p>
					<h4 style="margin: 2px 0px;">Fecha Timbrado:</h4>
					<p style="margin: 2px 0px;">'.str_replace('T', ' ', $row[0]['Fecha_Timbrado_CFDI']).'</p>
					<h4 style="margin: 2px 0px;">Folio Fiscal:</h4>
					<p style="margin: 2px 0px;">'.$row[0]['UUID_CFDI'].'</p>
					<h4 style="margin: 2px 0px;">No. Certificado Digital:</h4>
					<p style="margin: 2px 0px;">'.$row[0]['No_Certificado_CFDI'].'</p>
					<h4 style="margin: 2px 0px;">No. Certificado SAT:</h4>
					<p style="margin: 2px 0px;">'.$row[0]['No_Certificado_SAT_CFDI'].'</p>
				</div>
			</div>
			<div style="float: left; width: 65%; margin-top: -115px;">
				<div style="background: #303030;">
					<h5 style="color: #FFF; text-align: center; margin: 5px 0px;">DATOS DEL CLIENTE</h5>
				</div>
				<div style="padding: 0px 10px; font-size: 11px;">
					<p style="margin: 3px 0px;"><b>Nombre:</b> '.$row[0]['Receptor_Nombre_CFDI'].'</p>
					<p style="margin: 3px 0px;"><b>RFC:</b> '.$row[0]['Receptor_RFC_CFDI'].'</p>
					<p style="margin: 3px 0px;"><b>Domicilio:</b> '.$domicilioCliente.'</p>
					<p style="margin: 3px 0px;"><b>Régimen Fiscal:</b> '.$row[0]['Receptor_Regimen_Fiscal_CFDI'].' - '.$regimen[$row[0]['Receptor_Regimen_Fiscal_CFDI']].'</p>
				</div>
			</div>
			<div style="margin-top: 1px; text-align: center;">
				<div style="background: #303030;">
					<h5 style="color: #FFF; margin: 5px 0px;">DATOS DEL COMPROBANTE</h5>
				</div>
				'.$uuids.$global.'
				<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
					<h4 style="margin: 2px 0px;">Tipo de Comprobante</h4>
					<p style="margin: 2px 0px;">'.$row[0]['Tipo_Comprobante_CFDI'].' - '.$tipoCompro[$row[0]['Tipo_Comprobante_CFDI']].'</p>
				</div>
				<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
					<h4 style="margin: 2px 0px;">Método de pago</h4>
					<p style="margin: 2px 0px;">'.$row[0]['Metodo_Pago_CFDI'].' - '.$metodosPago[$row[0]['Metodo_Pago_CFDI']].'</p>
				</div>
				<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
					<h4 style="margin: 2px 0px;">Forma de Pago</h4>
					<p style="margin: 2px 0px;">'.$row[0]['Forma_Pago_CFDI'].' - '.$formasPago[$row[0]['Forma_Pago_CFDI']].'</p>
				</div>
				<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
					<h4 style="margin: 2px 0px;">Moneda</h4>
					<p style="margin: 2px 0px;">'.$row[0]['Moneda_CFDI'].'</p>
				</div>
				<div style="float: left; width: 30%; padding: 5px 10px; font-size: 11px;">
					<h4 style="margin: 2px 0px;">Uso de CFDI</h4>
					<p style="margin: 2px 0px;">'.$row[0]['Receptor_Uso_CFDI'].' - '.$usos[$row[0]['Receptor_Uso_CFDI']].'</p>
				</div>
			</div>
			<div style="text-align: center;">
				<div style="background: #303030;">
					<h5 style="color: #FFF; margin: 5px 0px;">PRODUCTOS/SERVICIOS</h5>
				</div>
				<style>
					th, td {
						padding: 3px;
					}
					thead tr th{
						color: #FFF;
					}
					tfoot tr th{
						font-size: 11px;
					}
					tfoot tr td{
						font-size: 11px;
						border-bottom: 1px solid #A0A0A0;
					} 
				</style>
				<table style="width: 100%; font-size: 10px; color: #202020; text-align: center; font-family: arial; border-collapse: collapse;">
					<thead>
						<tr style="background: #303030;">
							<th>Clave Prod./Serv.</th>
							<th>No. Identificación</th>
							<th>Descripción</th>
							<th>Clave Unidad</th>
							<th>Cantidad</th>
							<th>Valor Unitario</th>
							<th>Subtotal</th>
							<th>Descuento</th>
							<th>Impuestos</th>
							<th>Importe</th>
						</tr>
					</thead>
					<tbody>
						'.$productos.'
					</tbody>
					<tfoot>
						<tr>
							<th colspan="8" style="text-align: right; padding-top: 20px;">Subtotal</th>
							<td style="padding-top: 20px;" colspan="3">$'.number_format($subtotal, 2).'</td>
						</tr>
						'.$totalImpuetos.'
						<tr>
							<th colspan="8" style="text-align: right;">Total</th>
							<td colspan="3">$'.number_format($row[0]['Total'], 2).'</td>
						</tr>
						<tr>
							<th colspan="11" style="text-align: right; padding: 10px 0px;">'.$omodelo->_valorEnLetras($row[0]['Total'], 1).'</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div style="margin-top: 20px; font-size: 12px;">
				<div style="float: left; width: 25%; text-align: center;">
					<img src="qr.png" style="width: 100%"/>
				</div>
				<div style="float: left; width: 75%; text-align: center;">
					<div>
						<div style="background: #303030;">
							<h5 style="color: #FFF; margin: 5px 0px;">Cadena Original del Complemento de Certificación Digital del SAT</h5>
						</div>
						<div>
							<p style="margin: 2px 0; font-size: 9px;">'.$row[0]['Cadena_CFDI'].'</p>
						</div>
					</div>
					<div style="margin-top: 5px;">
						<div style="background: #303030;">
							<h5 style="color: #FFF; margin: 5px 0px;">Sello Digital del CFDI</h5>
						</div>
						<div>
							<p style="margin: 2px 0; font-size: 9px;">'.$row[0]['Sello_CFD_CFDI'].'</p>
						</div>
					</div>
					<div style="margin-top: 5px;">
						<div style="background: #303030;">
							<h5 style="color: #FFF; margin: 5px 0px;">Sello Digital del SAT</h5>
						</div>
						<div>
							<p style="margin: 2px 0; font-size: 9px;">'.$row[0]['Sello_SAT_CFDI'].'</p>
						</div>
					</div>
				</div>
			</div>
		</div>';

		//$mpdf->WriteHTML($htmlFactura);
		//$mpdf->AddPage();

		$mpdf->WriteHTML($htmlFactura);
		$mpdf->Output();

		//echo $htmlFactura;
	}	
?>