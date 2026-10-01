<?php
date_default_timezone_set('America/Mexico_City');
require_once __DIR__.'/../controladores/pdf/mpdf/vendor/autoload.php';
include(__DIR__.'/../controladores/pdf/phpqrcode/qrlib.php');
include "config/conexion.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/vendor/autoload.php';

class m_modelo extends conexion{
	public $link;
	public $numerofilas;
	public $error;

	public function __construct()
    {
        $this->link = conexion::__construct();
    }
	// METODO PARA INSERTAR, MODIFICAR Y ELIMINAR REGISTROS
	public function _insertar($query){
		if (isset($_SESSION['user_admin']['ID_Usuario']) && $_SESSION['user_admin']['ID_Usuario'] == "59") {
			return "si";
		}else{
			$result = $this->link->query($query);
			$this->numerofilas = $this->link->affected_rows;
			if (!$result){
				$error = 'si';
			}else{
				$error = 'no';
			}
			return $error;

			$this->link->$con->close();
		}
	}

	// METODO PARA OBTENER RESULTADOS DE LA BD
	public function _consultar($query){
		$result = $this->link->query($query);
		$this->numerofilas = $result->num_rows;
		if (!$result) {
			$this->error = 'si';
			echo "Se produjo un error en el modelo: ".mysqli_error($this->link);
		}
        else{
			$this->error = 'no';
			while ($resultado[] = $result->fetch_array());
		}
		return $resultado;

		$this->link->$con->close();
	}

	public function _email($destino, $asunto, $mensaje, $adjunto, $tipo)
	{
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
		    //Server settings
		    $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
		    //$mail->SMTPDebug = 2;                      // Enable verbose debug output
		    $mail->isSMTP();                                            // Send using SMTP
		    $mail->Host       = 'mail.wits.com.mx';                    // Set the SMTP server to send through
		    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		    $mail->Username   = 'contacto@wits.com.mx';                     // SMTP username
		    $mail->Password   = 'Contacto_2024';		// SMTP password
		    $mail->SMTPSecure = 'tls';                                
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		    $mail->Port 	  = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		    //Recipients
		    $mail->setFrom('contacto@wits.com.mx', utf8_decode('MISCELÁNEA RÍOS'));
		    $mail->addAddress($destino);     // Add a recipient
		    $mail->FromName = utf8_decode("MISCELÁNEA RÍOS");
		    /*$mail->addAddress('ellen@example.com');               // Name is optional
		    $mail->addReplyTo('info@example.com', 'Information');*/
		    $mail->addCC('jramongarciaangel@gmail.com');
		    //$mail->addBCC('bcc@example.com');

		    // Attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		    if(isset($adjunto) && $adjunto != '' && $tipo == 'xml'){
		    	$mail->addStringAttachment($adjunto, "xml.xml");         // Add attachments
		    }else if(isset($adjunto) && $adjunto != '' && $tipo == 'pdf'){
		    	$mail->addStringAttachment($adjunto, "factura.pdf");         // Add attachments
		    }

		    // Content
		    $mail->isHTML(true);            // Set email format to HTML
		    $mail->Subject = $asunto;
		    $mail->Body    = $mensaje;
		    $mail->AltBody = $mensaje;

		    $mail->send();
		    //echo 'Message has been sent';
		} catch (Exception $e) {
		    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	public function _valorEnLetras($x, $mos) 
	{ 
		if ($x<0) { $signo = "menos ";} 
		else      { $signo = "";} 
		$x = abs ($x); 
		$C1 = $x; 

		$G6 = floor($x/(1000000));  // 7 y mas 

		$E7 = floor($x/(100000)); 
		$G7 = $E7-$G6*10;   // 6 

		$E8 = floor($x/1000); 
		$G8 = $E8-$E7*100;   // 5 y 4 

		$E9 = floor($x/100); 
		$G9 = $E9-$E8*10;  //  3 

		$E10 = floor($x); 
		$G10 = $E10-$E9*100;  // 2 y 1 


		$G11 = round(($x-$E10)*100,0);  // Decimales 
		////////////////////// 

		$H6 = $this->unidades($G6); 

		if($G7==1 AND $G8==0) { $H7 = "Cien "; } 
		else {    $H7 = $this->decenas($G7); } 

		$H8 = $this->unidades($G8); 

		if($G9==1 AND $G10==0) { $H9 = "Cien "; } 
		else {    $H9 = $this->decenas($G9); } 

		$H10 = $this->unidades($G10); 

		if($G11 < 10) { $H11 = "0".$G11; } 
		else { $H11 = $G11; } 

		///////////////////////////// 
		    if($G6==0) { $I6=" "; } 
		elseif($G6==1) { $I6="Millón "; } 
		         else { $I6="Millones "; } 
		          
		if ($G8==0 AND $G7==0) { $I8=" "; } 
		         else { $I8="Mil "; } 
		          
		if($mos == 1){
			if($x > 1){
				$I10 = 'PESOS '; 
			}else{
				$I10 = 'PESO ';
			}
					
			$I11 = '/100 M.N';
					
		}else{
			$I10 = ""; 
			$I11 = "";
		}

		$C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$I10.$H11.$I11; 

		return $C3; //Retornar el resultado 
	} 

	private function decenas($d) 
	{ 
		if ($d==0)  {$rd = "";} 
		elseif ($d==1)  {$rd = "Ciento ";} 
		elseif ($d==2)  {$rd = "Doscientos ";} 
		elseif ($d==3)  {$rd = "Trescientos ";} 
		elseif ($d==4)  {$rd = "Cuatrocientos ";} 
		elseif ($d==5)  {$rd = "Quinientos ";} 
		elseif ($d==6)  {$rd = "Seiscientos ";} 
		elseif ($d==7)  {$rd = "Setecientos ";} 
		elseif ($d==8)  {$rd = "Ochocientos ";} 
		else            {$rd = "Novecientos ";} 
			
		return $rd; //Retornar el resultado 
	}

	private function unidades($u) 
	{ 
		if ($u==0)  {$ru = " ";} 
		elseif ($u==1)  {$ru = "Un ";} 
		elseif ($u==2)  {$ru = "Dos ";} 
		elseif ($u==3)  {$ru = "Tres ";} 
		elseif ($u==4)  {$ru = "Cuatro ";} 
		elseif ($u==5)  {$ru = "Cinco ";} 
		elseif ($u==6)  {$ru = "Seis ";} 
		elseif ($u==7)  {$ru = "Siete ";} 
		elseif ($u==8)  {$ru = "Ocho ";} 
		elseif ($u==9)  {$ru = "Nueve ";} 
		elseif ($u==10) {$ru = "Diez ";} 

		elseif ($u==11) {$ru = "Once ";} 
		elseif ($u==12) {$ru = "Doce ";} 
		elseif ($u==13) {$ru = "Trece ";} 
		elseif ($u==14) {$ru = "Catorce ";} 
		elseif ($u==15) {$ru = "Quince ";} 
		elseif ($u==16) {$ru = "Dieciseis ";} 
		elseif ($u==17) {$ru = "Decisiete ";} 
		elseif ($u==18) {$ru = "Dieciocho ";} 
		elseif ($u==19) {$ru = "Diecinueve ";} 
		elseif ($u==20) {$ru = "Veinte ";} 

		elseif ($u==21) {$ru = "Veintiun ";} 
		elseif ($u==22) {$ru = "Veintidos ";} 
		elseif ($u==23) {$ru = "Veintitres ";} 
		elseif ($u==24) {$ru = "Veinticuatro ";} 
		elseif ($u==25) {$ru = "Veinticinco ";} 
		elseif ($u==26) {$ru = "Veintiseis ";} 
		elseif ($u==27) {$ru = "Veintisiente ";} 
		elseif ($u==28) {$ru = "Veintiocho ";} 
		elseif ($u==29) {$ru = "Veintinueve ";} 
		elseif ($u==30) {$ru = "Treinta ";} 

		elseif ($u==31) {$ru = "Treinta y un ";} 
		elseif ($u==32) {$ru = "Treinta y dos ";} 
		elseif ($u==33) {$ru = "Treinta y tres ";} 
		elseif ($u==34) {$ru = "Treinta y cuatro ";} 
		elseif ($u==35) {$ru = "Treinta y cinco ";} 
		elseif ($u==36) {$ru = "Treinta y seis ";} 
		elseif ($u==37) {$ru = "Treinta y siete ";} 
		elseif ($u==38) {$ru = "Treinta y ocho ";} 
		elseif ($u==39) {$ru = "Treinta y nueve ";} 
		elseif ($u==40) {$ru = "Cuarenta ";} 

		elseif ($u==41) {$ru = "Cuarenta y un ";} 
		elseif ($u==42) {$ru = "Cuarenta y dos ";} 
		elseif ($u==43) {$ru = "Cuarenta y tres ";} 
		elseif ($u==44) {$ru = "Cuarenta y cuatro ";} 
		elseif ($u==45) {$ru = "Cuarenta y cinco ";} 
		elseif ($u==46) {$ru = "Cuarenta y seis ";} 
		elseif ($u==47) {$ru = "Cuarenta y siete ";} 
		elseif ($u==48) {$ru = "Cuarenta y ocho ";} 
		elseif ($u==49) {$ru = "Cuarenta y nueve ";} 
		elseif ($u==50) {$ru = "Cincuenta ";} 

		elseif ($u==51) {$ru = "Cincuenta y un ";} 
		elseif ($u==52) {$ru = "Cincuenta y dos ";} 
		elseif ($u==53) {$ru = "Cincuenta y tres ";} 
		elseif ($u==54) {$ru = "Cincuenta y cuatro ";} 
		elseif ($u==55) {$ru = "Cincuenta y cinco ";} 
		elseif ($u==56) {$ru = "Cincuenta y seis ";} 
		elseif ($u==57) {$ru = "Cincuenta y siete ";} 
		elseif ($u==58) {$ru = "Cincuenta y ocho ";} 
		elseif ($u==59) {$ru = "Cincuenta y nueve ";} 
		elseif ($u==60) {$ru = "Sesenta ";} 

		elseif ($u==61) {$ru = "Sesenta y un ";} 
		elseif ($u==62) {$ru = "Sesenta y dos ";} 
		elseif ($u==63) {$ru = "Sesenta y tres ";} 
		elseif ($u==64) {$ru = "Sesenta y cuatro ";} 
		elseif ($u==65) {$ru = "Sesenta y cinco ";} 
		elseif ($u==66) {$ru = "Sesenta y seis ";} 
		elseif ($u==67) {$ru = "Sesenta y siete ";} 
		elseif ($u==68) {$ru = "Sesenta y ocho ";} 
		elseif ($u==69) {$ru = "Sesenta y nueve ";} 
		elseif ($u==70) {$ru = "Setenta ";} 

		elseif ($u==71) {$ru = "Setenta y un ";} 
		elseif ($u==72) {$ru = "Setenta y dos ";} 
		elseif ($u==73) {$ru = "Setenta y tres ";} 
		elseif ($u==74) {$ru = "Setenta y cuatro ";} 
		elseif ($u==75) {$ru = "Setenta y cinco ";} 
		elseif ($u==76) {$ru = "Setenta y seis ";} 
		elseif ($u==77) {$ru = "Setenta y siete ";} 
		elseif ($u==78) {$ru = "Setenta y ocho ";} 
		elseif ($u==79) {$ru = "Setenta y nueve ";} 
		elseif ($u==80) {$ru = "Ochenta ";} 

		elseif ($u==81) {$ru = "Ochenta y un ";} 
		elseif ($u==82) {$ru = "Ochenta y dos ";} 
		elseif ($u==83) {$ru = "Ochenta y tres ";} 
		elseif ($u==84) {$ru = "Ochenta y cuatro ";} 
		elseif ($u==85) {$ru = "Ochenta y cinco ";} 
		elseif ($u==86) {$ru = "Ochenta y seis ";} 
		elseif ($u==87) {$ru = "Ochenta y siete ";} 
		elseif ($u==88) {$ru = "Ochenta y ocho ";} 
		elseif ($u==89) {$ru = "Ochenta y nueve ";} 
		elseif ($u==90) {$ru = "Noventa ";} 

		elseif ($u==91) {$ru = "Noventa y un ";} 
		elseif ($u==92) {$ru = "Noventa y dos ";} 
		elseif ($u==93) {$ru = "Noventa y tres ";} 
		elseif ($u==94) {$ru = "Noventa y cuatro ";} 
		elseif ($u==95) {$ru = "Noventa y cinco ";} 
		elseif ($u==96) {$ru = "Noventa y seis ";} 
		elseif ($u==97) {$ru = "Noventa y siete ";} 
		elseif ($u==98) {$ru = "Noventa y ocho ";} 
		else            {$ru = "Noventa y nueve ";} 

		return $ru; //Retornar el resultado 
	} 

	public function permisos(){
		$permisosMo = null;
		$query = "SELECT Permisos, Tipo_Usuario FROM usuarios WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
		$row = $this->_consultar($query);
		$numerofilas = $this->numerofilas;

		if ($row == "si") {
			echo "Error: ".mysqli_error($this->link);
		}else{
			if($numerofilas > 0){
				if($row[0]['Tipo_Usuario'] == 'Administrador'){
					$permisosMo = 'Administrador';
				}else{
					$modulos = explode('~', $row[0]['Permisos']);
					for ($i=0; $i < count($modulos); $i++) {
						$cadena = explode(',', $modulos[$i]);
						$nombreModu = $cadena[0];
						unset($cadena[0]);
						$permisosMo[$nombreModu] = $cadena;
					}
				}
			}
		}
		return $permisosMo;
	}

	public function movimiento($sql, $id){
		$sql = $this->link->real_escape_string($sql);
		$fecha = date ('Y-m-d H:i:s');
		$query="INSERT INTO movimientos SET Descripcion = 'Admin: $sql', FK_Usuario = '$id', Fecha = '$fecha'";
		//$query="INSERT INTO movimientos SET Descripcion = '$sql', '$dataArray->geoplugin_request', '$dataArray->geoplugin_countryName', '$dataArray->geoplugin_regionName', '$user_browser', '$os_platform', '$fecha', '$_SERVER[HTTP_USER_AGENT]', '$id')";
		$error = $this->_insertar($query);

		if($error == 'si'){
			echo "Error Movimientos: ".mysqli_error($this->link);
		}
	}

	public function round2deci($number){
		if(strpos($number, '.') !== false) {
		  $explode = explode(".", $number);
	        /// 51.13 == 51.ab
			$a = substr($explode[1], 0, 1);
			$b =  substr($explode[1], 1, 2);
		        // fix for 51.91
			if($a == 9){
				$explode[0]++;
				$a = 0;
				$b = 0;

			}
			if($b > 0){
				$a++;
			}
			return $explode[0].".".$a."0";
		} else {
		  	return $number;
		}
	}

	public function enviar($id){
		$enviada = '';
		$correo = '';
		$query = "SELECT ID_Venta, FK_Cliente, Enviada, Correo, clientes.Calle AS Calle_Cliente, clientes.No_Exterior AS No_Exterior_Cliente, clientes.No_Interior AS No_Interior_Cliente, clientes.Colonia AS Colonia_Cliente, clientes.Ciudad AS Ciudad_Cliente, clientes.Estado AS Estado_Cliente, clientes.Pais AS Pais_Cliente, sucursales.Nombre AS Sucursal, sucursales.Calle AS Calle_Sucursal, sucursales.No_Exterior AS No_Exterior_Sucursal, sucursales.No_Interior AS No_Interior_Sucursal, sucursales.Colonia AS Colonia_Sucursal, sucursales.Ciudad AS Ciudad_Sucursal, sucursales.Estado AS Estado_Sucursal, sucursales.Pais AS Pais_Sucursal, ventas.Descuento AS Descuento, Total, ventas.Fecha_Registro AS Fecha_Registro, Version_CFDI, Fecha_Expedicion_CFDI, Sello_CFDI, Forma_Pago_CFDI, No_Certificado_CFDI, Certificado_CFDI, Moneda_CFDI, Tipo_Comprobante_CFDI, Exportacion_CFDI, Metodo_Pago_CFDI, Lugar_Expedicion_CFDI, Confirmacion_CFDI, Emisor_RFC_CFDI, Emisor_Nombre_CFDI, Emisor_Regimen_Fiscal_CFDI, Receptor_RFC_CFDI, Receptor_Nombre_CFDI, Receptor_Domicilio_CFDI, Receptor_Regimen_Fiscal_CFDI, Receptor_Uso_CFDI, UUID_CFDI, Fecha_Timbrado_CFDI, Rfc_ProvCertif_CFDI, Sello_CFD_CFDI, No_Certificado_SAT_CFDI, Sello_SAT_CFDI, Periodicidad_CFDI, Meses_CFDI, Ano_CFDI, Relacion_CFDI FROM ventas INNER JOIN sucursales ON ID_Sucursal = 1 INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id' AND Facturada = '1' AND Estatus = 'Completada'";
		//FK_Sucursal = ID_Sucursal
		$row = $this->_consultar($query);
		$numerofilas = $this->numerofilas;

		if($row == 'si'){
			echo "Error 1: ".mysqli_error($this->link);
		}else{
			if($numerofilas > 0){
				$enviada = $row[0]['Enviada'];
				$correo = $row[0]['Correo'];

				$productos = ''; $subtotal = 0; $totalImTras = 0; $totalImRete = 0; $imAgrupadosTras = []; $imAgrupadosRete = []; $error = false;
				$query1 = "SELECT ID_Detalle_Venta, Identificacion_CFDI, Clave_ProdServ_CFDI, Clave_Unidad_CFDI, Unidad_CFDI, Objeto_Impuesto_CFDI, Descripcion, Precio, Cantidad, Descuento, Total FROM detalles_ventas WHERE FK_Venta = '$id'";
				$row1 = $this->_consultar($query1);
				$numerofilas1 = $this->numerofilas;

				if($row1 == 'si'){
				echo "Error 2: ".mysqli_error($this->link);
				}else{
					if($numerofilas1 > 0){
						for ($i=0; $i < $numerofilas1; $i++) { 
							$impuestosTras = ''; $impuestosRet = '';
							$query2 = "SELECT ID_Impuesto, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row1[$i]['ID_Detalle_Venta']."'";
							$row2 = $this->_consultar($query2);
							$numerofilas2 = $this->numerofilas;

							if($row2 == 'si'){
								echo "Error 3: ".mysqli_error($this->link);
							}else{
								if($numerofilas2 > 0){
									for ($x=0; $x < $numerofilas2; $x++) {
										if($row1[$i]['Objeto_Impuesto_CFDI'] != '01' && $row1[$i]['Objeto_Impuesto_CFDI'] != '03'){
											if($row2[$x]['Tipo_Impuesto_CFDI'] == 'Trasladado'){
												if($row2[$x]['Tipo_Factor_CFDI'] == 'Exento'){
													$impuestosTras .= '<cfdi:Traslado Base="'.number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 2, '.', '').'" Impuesto="'.$row2[$x]['Clave_CFDI'].'" TipoFactor="'.$row2[$x]['Tipo_Factor_CFDI'].'"/>';
												}else{
													$impuestosTras .= '<cfdi:Traslado Base="'.number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 2, '.', '').'" Impuesto="'.$row2[$x]['Clave_CFDI'].'" TipoFactor="'.$row2[$x]['Tipo_Factor_CFDI'].'" TasaOCuota="'.number_format(($row2[$x]['Tasa_Cuota_CFDI'] / 100), 6, '.', '').'" Importe="'.number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2, '.', '').'"/>';

													$totalImTras += number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 2, '.', '');

													if(count($imAgrupadosTras) == 0){
														array_push($imAgrupadosTras, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2, '.', '')));
													}else{
														$encontro = false;
														for ($y=0; $y < count($imAgrupadosTras); $y++) { 
															if($imAgrupadosTras[$y]['Impuesto'] == $row2[$x]['Clave_CFDI'] && $imAgrupadosTras[$y]['TipoFactor'] == $row2[$x]['Tipo_Factor_CFDI'] && $imAgrupadosTras[$y]['TasaOCuota'] == ($row2[$x]['Tasa_Cuota_CFDI'] / 100)){
																$imAgrupadosTras[$y]['Base'] += ($row2[$i]['Cantidad'] * $row2[$i]['Precio']) - $row2[$i]['Descuento'];
																$imAgrupadosTras[$y]['Importe'] += number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 2, '.', '');
																$encontro = true;
																break;
															}
														}

														if($encontro == false){
															array_push($imAgrupadosTras, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2, '.', '')));
														}
													}
												}
											}else{
												$impuestosRet .= '<cfdi:Retencion Base="'.number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 2, '.', '').'" Impuesto="'.$row2[$x]['Clave_CFDI'].'" TipoFactor="'.$row2[$x]['Tipo_Factor_CFDI'].'" TasaOCuota="'.number_format(($row2[$x]['Tasa_Cuota_CFDI'] / 100), 6, '.', '').'" Importe="'.number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2, '.', '').'"/>';

												$totalImRete += number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row2[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 2, '.', '');

												if(count($imAgrupadosRete) == 0){
													array_push($imAgrupadosRete, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2, '.', '')));
												}else{
													$encontro = false;
													for ($y=0; $y < count($imAgrupadosRete); $y++) { 
														if($imAgrupadosRete[$y]['Impuesto'] == $row2[$x]['Clave_CFDI'] && $imAgrupadosRete[$y]['TipoFactor'] == $row2[$x]['Tipo_Factor_CFDI'] && $imAgrupadosRete[$y]['TasaOCuota'] == ($row2[$x]['Tasa_Cuota_CFDI'] / 100)){
															$imAgrupadosRete[$y]['Base'] += ($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento'];
															$imAgrupadosRete[$y]['Importe'] += number_format((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 2, '.', '');
															$encontro = true;
															break;
														}
													}

													if($encontro == false){
														array_push($imAgrupadosRete, array('Base' => (($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']), 'Impuesto' => $row2[$x]['Clave_CFDI'], 'TipoFactor' => $row2[$x]['Tipo_Factor_CFDI'], 'TasaOCuota' => ($row2[$x]['Tasa_Cuota_CFDI'] / 100), 'Importe' => number_format(((($row1[$i]['Cantidad'] * $row1[$i]['Precio']) - $row1[$i]['Descuento']) * ($row2[$x]['Tasa_Cuota_CFDI'] / 100)), 2, '.', '')));
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

						$productos .= '<cfdi:Concepto ClaveProdServ="'.$row1[$i]['Clave_ProdServ_CFDI'].'" NoIdentificacion="'.$row1[$i]['Identificacion_CFDI'].'" Cantidad="'.number_format($row1[$i]['Cantidad'], 2, '.', '').'" ClaveUnidad="'.$row1[$i]['Clave_Unidad_CFDI'].'" Unidad="'.$row1[$i]['Unidad_CFDI'].'" Descripcion="'.$row1[$i]['Descripcion'].'" ValorUnitario="'.number_format($row1[$i]['Precio'], 2, '.', '').'" Importe="'.number_format(($row1[$i]['Cantidad'] * $row1[$i]['Precio']), 2, '.', '').'" Descuento="'.number_format($row1[$i]['Descuento'], 2, '.', '').'" ObjetoImp="'.$row1[$i]['Objeto_Impuesto_CFDI'].'">'.$impuestos.'</cfdi:Concepto>';

						$subtotal += $row1[$i]['Cantidad'] * $row1[$i]['Precio'];
					}
				}
			}
		}
								
		if($error == false){
			$uuids = '';
			$query1 = "SELECT ID_Relacion, UUID FROM relacionados_cfdi WHERE FK_Venta = '$id'";
			$row1 = $this->_consultar($query1);
			$numerofilas1 = $this->numerofilas;

			if($row1 == 'si'){
				echo "Error 4: ".mysqli_error($this->link);
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
					$totalImpuestos .= '<cfdi:Impuestos TotalImpuestosRetenidos="'.number_format($totalImRete, 2, '.', '').'" TotalImpuestosTrasladados="'.number_format($totalImTras, 2, '.', '').'">';
				}else if($totalImTras > 0){
					$totalImpuestos .= '<cfdi:Impuestos TotalImpuestosTrasladados="'.number_format($totalImTras, 2, '.', '').'">';
				}else{
				$totalImpuestos .= '<cfdi:Impuestos TotalImpuestosRetenidos="'.number_format($totalImRete, 2, '.', '').'">';
				}

				if(count($imAgrupadosRete) > 0){
					$totalImpuestos .= '
					<cfdi:Retenciones>';

					foreach ($imAgrupadosRete as $imp) {
						$totalImpuestos .= '
						<cfdi:Retencion Base="'.number_format($imp['Base'], 2, '.', '').'" Impuesto="'.$imp['Impuesto'].'" TipoFactor="'.$imp['TipoFactor'].'" TasaOCuota="'.number_format($imp['TasaOCuota'], 6, '.', '').'" Importe="'.number_format($imp['Importe'], 2, '.', '').'"/>';
					}

					$totalImpuestos .= '
					</cfdi:Retenciones>';
				}

				if(count($imAgrupadosTras) > 0){
					$totalImpuestos .= '
					<cfdi:Traslados>';

					foreach ($imAgrupadosTras as $imp) {
						$totalImpuestos .= '
						<cfdi:Traslado Base="'.number_format($imp['Base'], 2, '.', '').'" Impuesto="'.$imp['Impuesto'].'" TipoFactor="'.$imp['TipoFactor'].'" TasaOCuota="'.number_format($imp['TasaOCuota'], 6, '.', '').'" Importe="'.number_format($imp['Importe'], 2, '.', '').'"/>';
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
				<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd" Version="4.0" Serie="'.$id.'" Folio="'.str_pad($id, 8, '0', STR_PAD_LEFT).'" Fecha="'.str_replace(' ', 'T', $row[0]['Fecha_Expedicion_CFDI']).'" Sello="'.$row[0]['Sello_CFDI'].'" FormaPago="'.$row[0]['Forma_Pago_CFDI'].'" NoCertificado="'.$row[0]['No_Certificado_CFDI'].'" Certificado="'.$row[0]['Certificado_CFDI'].'" SubTotal="'.number_format($subtotal, 2, '.', '').'" Descuento="'.number_format($row[0]['Descuento'], 2, '.', '').'" Moneda="'.$row[0]['Moneda_CFDI'].'" Total="'.number_format($row[0]['Total'], 2, '.', '').'" TipoDeComprobante="'.$row[0]['Tipo_Comprobante_CFDI'].'" Exportacion="'.$row[0]['Exportacion_CFDI'].'" MetodoPago="'.$row[0]['Metodo_Pago_CFDI'].'" LugarExpedicion="'.trim($row[0]['Lugar_Expedicion_CFDI']).'">'.$global.$uuids.'
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

				//header('Content-type: text/xml');
				//header('Content-Disposition: attachment; filename="'.$row[0]['UUID_CFDI'].'.xml"');

				if($correo != ''){
					$this->_email($correo, 'Factura', 'Envio de facturas automatico', $textoXML, 'xml');
				}

				//echo $textoXML;
				//exit();
			}
		}

		$query = "SELECT ID_Venta, FK_Cliente, Enviada, Correo, clientes.Calle AS Calle_Cliente, clientes.No_Exterior AS No_Exterior_Cliente, clientes.No_Interior AS No_Interior_Cliente, clientes.Colonia AS Colonia_Cliente, clientes.Ciudad AS Ciudad_Cliente, clientes.Estado AS Estado_Cliente, clientes.Pais AS Pais_Cliente, sucursales.Nombre AS Sucursal, sucursales.Calle AS Calle_Sucursal, sucursales.No_Exterior AS No_Exterior_Sucursal, sucursales.No_Interior AS No_Interior_Sucursal, sucursales.Colonia AS Colonia_Sucursal, sucursales.Ciudad AS Ciudad_Sucursal, sucursales.Estado AS Estado_Sucursal, sucursales.Pais AS Pais_Sucursal, ventas.Descuento AS Descuento, Total, ventas.Fecha_Registro AS Fecha_Registro, Version_CFDI, Fecha_Expedicion_CFDI, Sello_CFDI, Forma_Pago_CFDI, No_Certificado_CFDI, Certificado_CFDI, Moneda_CFDI, Tipo_Comprobante_CFDI, Exportacion_CFDI, Metodo_Pago_CFDI, Lugar_Expedicion_CFDI, Confirmacion_CFDI, Emisor_RFC_CFDI, Emisor_Nombre_CFDI, Emisor_Regimen_Fiscal_CFDI, Receptor_RFC_CFDI, Receptor_Nombre_CFDI, Receptor_Domicilio_CFDI, Receptor_Regimen_Fiscal_CFDI, Receptor_Uso_CFDI, UUID_CFDI, Fecha_Timbrado_CFDI, Rfc_ProvCertif_CFDI, Sello_CFD_CFDI, No_Certificado_SAT_CFDI, Sello_SAT_CFDI, Periodicidad_CFDI, Meses_CFDI, Ano_CFDI, Relacion_CFDI, Cadena_CFDI FROM ventas INNER JOIN sucursales ON ID_Sucursal = 1 INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id' AND Facturada = '1' AND Estatus = 'Completada'";
		//FK_Sucursal = ID_Sucursal
		$row = $this->_consultar($query);
		$numerofilas = $this->numerofilas;

		if($row == 'si'){
			echo "Error 1: ".mysqli_error($this->link);
		}else{
			if($numerofilas > 0){
				$enviada = $row[0]['Enviada'];
				$correo = $row[0]['Correo'];

				$productos = ''; $subtotal = 0; $totalImTras = 0; $totalImRete = 0; $imAgrupadosTras = []; $imAgrupadosRete = []; $error = false;
				$query1 = "SELECT ID_Detalle_Venta, Identificacion_CFDI, Clave_ProdServ_CFDI, Clave_Unidad_CFDI, Unidad_CFDI, Objeto_Impuesto_CFDI, Descripcion, Precio, Cantidad, Descuento, Total FROM detalles_ventas WHERE FK_Venta = '$id'";
				$row1 = $this->_consultar($query1);
				$numerofilas1 = $this->numerofilas;

				if($row1 == 'si'){
					echo "Error 2: ".mysqli_error($this->link);
				}else{
					if($numerofilas1 > 0){
						for ($i=0; $i < $numerofilas1; $i++) { 
							$impuestos = '';
							$query2 = "SELECT ID_Impuesto, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row1[$i]['ID_Detalle_Venta']."'";
							$row2 = $this->_consultar($query2);
							$numerofilas2 = $this->numerofilas;

							if($row2 == 'si'){
								echo "Error 3: ".mysqli_error($this->link);
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

							$productos .= '<tr>
								<td>'.$row1[$i]['Clave_ProdServ_CFDI'].'</td>
								<td>'.$row1[$i]['Identificacion_CFDI'].'</td>
								<td>'.$row1[$i]['Descripcion'].'</td>
								<td>'.$row1[$i]['Clave_Unidad_CFDI'].'</td>
								<td>'.$row1[$i]['Unidad_CFDI'].'</td>
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
			$row1 = $this->_consultar($query1);
			$numerofilas1 = $this->numerofilas;

			if($row1 == 'si'){
				echo "Error 4: ".mysqli_error($this->link);
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

			$htmlFactura = '<div style="font-family: arial; font-size: 14px; color: #303030; height: 100%;">
				<div style="float: left; width: 15%; padding-top: 20px;">
					<img src="https://wits.com.mx/rios/vistas/assets/img/favicon/favicon.jpg" style="width: 100%;"/>
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
								<th>Unidad</th>
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
								<th colspan="11" style="text-align: right; padding: 10px 0px;">'.$this->_valorEnLetras($row[0]['Total'], 1).'</th>
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
			if($correo != ''){
				$this->_email($correo, 'Factura', 'Envio de facturas automatico', $mpdf->Output('factura.pdf', 'S'), 'pdf');
			}
		}
	}
}

?>
