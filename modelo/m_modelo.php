<?php
date_default_timezone_set('America/Mexico_City');
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
		    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
		    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		    $mail->Username   = 'cremasi.alex@gmail.com';                     // SMTP username
		    $mail->Password   = 'eyoubxbbycarxxqf';		// SMTP password
		    $mail->SMTPSecure = 'tls';                                
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		    $mail->Port 	  = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		    //Recipients
		    $mail->setFrom('cremasi.alex@gmail.com', utf8_decode('MISCELÁNEA RÍOS'));
		    $mail->addAddress($destino);     // Add a recipient
		    $mail->FromName = utf8_decode("MISCELÁNEA RÍOS");
		    /*$mail->addAddress('ellen@example.com');               // Name is optional
		    $mail->addReplyTo('info@example.com', 'Information');
		    $mail->addCC('cc@example.com');
		    $mail->addBCC('bcc@example.com');*/

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
}

?>
