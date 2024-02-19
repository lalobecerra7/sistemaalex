<?php
session_start();

if (isset($_SESSION['user_gaheto'])) {
	require_once __DIR__ . '/vendor/autoload.php';

    function _valorEnLetras($x, $mos) 
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

		if($E8 > 1){
			$H6 = unidades($G6); 
		}else{
			$H6 = "";
		}

		if($G7==1 AND $G8==0) { $H7 = "Cien "; } 
		else {    $H7 = decenas($G7); } 

		if($E8 > 1){
			$H8 = unidades($G8); 
		}else{
			$H8 = "";
		}

		if($G9==1 AND $G10==0) { $H9 = "Cien "; } 
		else {    $H9 = decenas($G9); } 

		if($E8 > 1){
			$H10 = unidades($G10); 
		}else{
			$H10 = "";
		}

		if($G11 < 10) { $H11 = "0".$G11; } 
		else { $H11 = $G11; } 

		///////////////////////////// 
		    if($G6==0) { $I6=" "; } 
		elseif($G6==1) { $I6="Millón "; } 
		         else { $I6="Millones "; } 
		          
		if ($G8==0 AND $G7==0) { $I8=" "; } 
		         else { $I8="Mil "; } 
		          
		if($mos == 1){
			$I10 = "PESOS "; 
			$I11 = "/100 MONEDA NACIONAL DE MÉXICO";
		}else{
			$I10 = ""; 
			$I11 = "";
			$H11 = "";
		}

		$C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$I10.$H11.$I11; 

		return $C3; //Retornar el resultado 
	} 

	function decenas($d) 
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

	function unidades($u) 
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

	$con = new mysqli("localhost", "root", "", "gaheto");

    $compra = $con->real_escape_string($_GET['compra']);
    $cliente = $con->real_escape_string($_GET['cliente']);

    $query = "SELECT Nombre, Primer_Apellido, Segundo_Apellido, Nacionalidad, Fecha_Nacimiento, Lugar_Nacimiento, Estado_Civil, Regimen_Matrimonial, Nombre_Pareja, Actividad_Preponderante, Calle, No_Interior, No_Exterior, Colonia, CP, Ciudad, Estado, Pais, CURP, RFC, Telefono, Email FROM clientes WHERE ID_Cliente = '$cliente'";

	if($res=$con->query($query)){
		if ($res->num_rows > 0) {
			$row = $res->fetch_assoc();

			$mpdf = new \Mpdf\Mpdf(
				[
					'mode' => 'utf-8', 
					'format' => 'Letter', 
					'margin_left' => 15,    	
					'margin_right' => 15,    	
					'margin_top' => 15,     
					'margin_bottom' => 15
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

			$domicilio = $row['Calle'].' #'.$row['No_Exterior'];
			if($row['No_Interior'] != ''){
				$domicilio .= ' - '.$row['No_Interior'].',';
			}

			if($row['Colonia'] != ''){
				$domicilio .= ' '.$row['Colonia'];
			}

			$domicilio .= ' C.P. '.$row['CP'].', '.$row['Ciudad'].', '.$row['Estado'].' '.$row['Pais'].'.';

			$query1 = "SELECT Total, (SELECT IFNULL(SUM(Monto), 0) FROM pagos WHERE FK_Compra = ID_Compra AND Cancelado = 0) AS Pago, Numero, Etapa, Superficie FROM compras INNER JOIN lotes ON FK_Lote = ID_Lote WHERE ID_Compra = '$compra'";
			if($res1=$con->query($query1)){
				if ($res1->num_rows > 0) {
					$row1 = $res1->fetch_assoc();

					$restante = $row1['Total'] - $row1['Pago'];
					if($restante < 0){
						$restante = 0;
					}

					$htmlContrato = '<div style="font-family: serif;">
						<p style="text-align: center; text-decoration: underline; font-style: italic;"><b>CARÁTULA DEL CONTRATO DE ADHESIÓN A FIDEICOMISO 04032022</b></p>
					</div>
					<div style="font-family: serif; border: 2px solid #000; font-size: 13px; padding: 2px 10px;">
						<p style="text-align: center; margin: 0px;"><b>DATOS DEL FIDEICOMITENTE Y FIDEICOMISARIO ADHERENTE</b></p>
						<p><b>NOMBRE: </b><span style="font-size: 16px;">'.$row['Nombre'].' '.$row['Primer_Apellido'].' '.$row['Segundo_Apellido'].'</span>.</p>
						<p><b>NACIONALIDAD: </b> <span style="font-size: 16px;">'.$row['Nacionalidad'].'</span>.</p>
						<p><b>LUGAR DE NACIMIENTO (ESTADO Y MUNICIPIO): </b>'.$row['Lugar_Nacimiento'].'.</p>
						<p><b>FECHA DE NACIMIENTO: </b><span style="font-size: 16px;">'.$row['Fecha_Nacimiento'].'</span>.</p>
						<p><b>ESTADO CIVIL: </b><span style="font-size: 16px;">'.$row['Estado_Civil'].'</span>.</p>
						<p><b>RÉGIMEN MATRIMONIAL: </b>'.$row['Regimen_Matrimonial'].'.</p>
						<p><b>CÓNYUGE O CONCUBINA: </b>'.$row['Nombre_Pareja'].'.</p>
						<p><b>OCUPACIÓN: </b><span style="font-size: 16px;">'.$row['Actividad_Preponderante'].'</span>.</p>
						<p><b>DOMICILIO: </b><span style="font-size: 16px;">'.$domicilio.'</span>.</p>
						<p><b>CURP: </b><span style="font-size: 16px;">'.$row['CURP'].'</span>.</p>
						<p><b>RFC: </b><span style="font-size: 16px;">'.$row['RFC'].'</span>.</p>
						<p><b>NÚMERO CELULAR: </b><span style="font-size: 16px;">'.$row['Telefono'].'</span>.</p>
						<p><b>CORREO ELECTRÓNICO: </b><span style="font-size: 16px;">'.$row['Email'].'</span>.</p>

						<p><b>DESIGNACIÓN DE SUSTITUTO(S) Y/O BENEFICIARIOS EN CASO DE MUERTE: </b>.</p>

						<p><b>MONTO TOTAL DE APORTACIÓN: </b> $'.number_format($row1['Total'], 2).' pesos ('._valorEnLetras($row1['Total'], 1).').</p>

						<p><b>MONTO APORTADO A LA FIRMA DEL PRESENTE CONTRATO:</b> $'.number_format($row1['Pago'], 2).' pesos ('._valorEnLetras($row1['Pago'], 1).'), los cuales se realizan mediante transferencia bancaria al momento de la firma del presente contrato y los cuales se tendrán por efectivamente aportados hasta en tanto la transferencia electrónica respectiva quede en firme en la cuenta del FIDUCIARIO.</p>

						<p><b>MONTO PENDIENTE DE APORTACIÓN:</b> $'.number_format($restante, 2).' ('._valorEnLetras($restante, 1).') los cuales serán aportados como se manifiesta en el ANEXO TABLA DE APORTACIONES, cada aportación pagadera los primeros 10 diez días calendario de cada mes en los términos de la cláusula segunda del contrato de Adhesión.</p>

						<p>La aportación antes señalada le dará derechos al FIDEICOMITENTE Y FIDEICOMISARIO ADHERENTE sobre la unidad privativa número '.$row1['Numero'].' de la etapa '.$row1['Etapa'].' con medidas de '.number_format($row1['Superficie'], 2).' m2 ('._valorEnLetras($row1['Superficie'], 0).' metros cuadrados).</p>
					</div>
					<div style="font-family: serif; font-size: 12px; padding: 2px 25px;">
						<p style="text-align: justify;"><b>LOS DATOS GENERALES MENCIONADOS EN ESTA CARÁTULA, FORMAN PARTE DEL PRESENTE CONTRATO DE ADHESIÓN AL FIDEICOMISO IRREVOCABLE DE GARANTÍA CON DERECHO A REVERSIÓN CON NÚMERO ADMINISTRATIVO 04032022 Y DEBEN CONSIDERARSE COMO INTEGRADOS AL MISMO, POR LO QUE, ES SU DESEO SUJETARSE AL TENOR DEL CONTENIDO DEL PRESENTE CONTRATO.</b></p>
					</div>';

					//echo $htmlContra;
					$mpdf->WriteHTML($htmlContrato);
					$mpdf->AddPage();

					$htmlContrato = '<div style="font-family: serif; font-size: 12.5px;">
						<p style="text-align: center;"><b>DOCUMENTOS NECESARIOS ANTES DE LA FIRMA PARA VALIDACIÓN</b></p>
					</div>
					<div style="font-family: serif; font-size: 12.5px; padding: 0px 30px;">
						<p style="margin: 2px;"><b>FIDEICOMITENTE Y/O FIDEICOMISARIO <span style="text-decoration: underline;">NACIONAL SOLTERO<span></b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">1.	IDENTIFICACIÓN OFICIAL VIGENTE</p>
							<p style="margin: 0px;">2.	COMPROBANTE DE DOMICILIO (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
							<p style="margin: 0px;">3.	CONSTANCIA DE SITUACIÓN FISCAL (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
							<p style="margin: 0px;">4.	DOCUMENTO OFICIAL DE CLAVE UNICA DE REGISTRO DE POBLACIÓN</p>
						</div>
						<br>
						<p style="margin: 2px;"><b>FIDEICOMITENTE Y/O FIDEICOMISARIO <span style="text-decoration: underline;">NACIONAL CASADO Y/O UNIÓN LIBRE</span></b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">1.	IDENTIFICACIÓN OFICIAL VIGENTE</p>
							<p style="margin: 0px;">2.	COMPROBANTE DE DOMICILIO (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
							<p style="margin: 0px;">3.	CONSTANCIA DE SITUACIÓN FISCAL (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
							<p style="margin: 0px;">4.	DOCUMENTO OFICIAL DE CLAVE UNICA DE REGISTRO DE POBLACIÓN</p>
							<p style="margin: 0px;">5.	ACTA DE MATRIMONIO</p>
						</div>
						<br>
						<p style="margin: 2px;"><b style="text-decoration: underline;">DEL CÓNYUGE Y/O CONCUBINA</b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">6.	IDENTIFICACIÓN OFICIAL VIGENTE</p>
							<p style="margin: 0px;">7.	COMPROBANTE DE DOMICILIO (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
							<p style="margin: 0px;">8.	CONSTANCIA DE SITUACIÓN FISCAL (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
							<p style="margin: 0px;">9.	DOCUMENTO OFICIAL DE CLAVE UNICA DE REGISTRO DE POBLACIÓN</p>
						</div>	
						<br>
						<p style="margin: 2px;"><b>FIDEICOMITENTE Y/O FIDEICOMISARIO <span style="text-decoration: underline;">EXTRANJERO SOLTERO</span></b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">1.	PASAPORTE VIGENTE</p>
							<p style="margin: 0px;">2.	DOCUMENTO QUE ACREDITE LA LEGAL ESTANCIA EN EL PAÍS</p>
							<p style="margin: 0px;">3.	COMPROBANTE DE DOMICILIO (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
						</div>	
						<br>
						<p style="margin: 2px;"><b>FIDEICOMITENTE Y/O FIDEICOMISARIO <span style="text-decoration: underline;">EXTRANJERO CASADO Y/O UNIÓN LIBRE</span></b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">1.	PASAPORTE VIGENTE</p>
							<p style="margin: 0px;">2.	DOCUMENTO QUE ACREDITE LA LEGAL ESTANCIA EN EL PAÍS</p>
							<p style="margin: 0px;">3.	COMPROBANTE DE DOMICILIO (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
							<p style="margin: 0px;">4.	ACTA DE MATRIMONIO</p>
						</div>	
						<br>
						<p style="margin: 2px;"><b style="text-decoration: underline;">DEL CÓNYUGE Y/ CONCUBINA</b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">5.	PASAPORTE VIGENTE</p>
							<p style="margin: 0px;">6.	DOCUMENTO QUE ACREDITE LA LEGAL ESTANCIA EN EL PAÍS</p>
							<p style="margin: 0px;">7.	COMPROBANTE DE DOMICILIO (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
						</div>	
						<br>
						<p style="margin: 2px;"><b>DE LOS <span style="text-decoration: underline;">BENEFICIARIOS NACIONALES Y EXTRANJEROS MAYORES DE EDAD</span></b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">1.	IDENTIFICACIÓN OFICIAL VIGENTE</p>
							<p style="margin: 0px;">2.	COMPROBANTE DE DOMICILIO (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
						</div>
						<br>
						<p style="margin: 2px;"><b>DE LOS <span style="text-decoration: underline;">BENEFICIARIOS NACIONALES Y EXTRANJEROS MENORES DE EDAD</span></b></p>
						<div style="padding: 0px 20px;">
							<p style="margin: 0px;">1.	IDENTIFICACIÓN OFICIAL VIGENTE</p>
							<p style="margin: 0px;">2.	COMPROBANTE DE DOMICILIO DEL PADRE O TUTOR (RECIENTE ANTIGÜEDAD MÁXIMA 60 DÍAS)</p>
						<div>	
					</div>';

					$mpdf->WriteHTML($htmlContrato);
					$mpdf->Output();
				}
			}else{
				echo "Error 1: ".mysqli_error($con);
			}
		}
	}else{
		echo "Error 2: ".mysqli_error($con);
	}
}else{
	http_response_code(400);
}	
?>