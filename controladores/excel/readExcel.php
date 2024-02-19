<?php
	session_start();
  	require 'vendor/autoload.php';
  	include '../../modelo/m_modelo.php';
  	if (!isset($_SESSION['user_admin'])) return http_response_code(400);
  	$omodelo = new m_modelo();
  	extract($_POST);
  	$ZonaCargarPrecioProducto = $omodelo->link->real_escape_string($Zonas);
  	$fecha = date('Y-m-d H:i:s');
	
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	$spreadsheet = new Spreadsheet();

   	$nombreImg = '';
	$ruta = '';
	$rutaProvisional = '';

    if ($_FILES['ExcelPrecios']['size'] > 0 && $_FILES['ExcelPrecios']['error'] == 0) {
        $file = $_FILES['ExcelPrecios'];
        $nombreImg = $file['name'];
        $tipoImg = $file['type'];
        $rutaProvisional = $file['tmp_name'];
        $sizeImg = $file['size'];

        if($tipoImg != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $tipoImg != '') {
          	echo 'Error 2 Formato';
        }else if ($sizeImg > (1024 * 1024 * 10)) {
          	echo 'Error 3 Peso';
        }else{
          	$inputFileType = 'Xlsx';
			$inputFileName = $rutaProvisional;

			/** Create a new Reader of the type defined in $inputFileType **/
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
			/** Advise the Reader that we only want to load cell data **/
			$reader->setReadDataOnly(true);

			$worksheetData = $reader->listWorksheetInfo($inputFileName);

			foreach ($worksheetData as $worksheet) {

			$sheetName = $worksheet['worksheetName'];

			/** Load $inputFileName to a Spreadsheet Object **/
			$reader->setLoadSheetsOnly($sheetName);
			$spreadsheet = $reader->load($inputFileName);

			$worksheet = $spreadsheet->getActiveSheet();
			$arreglo = $worksheet->toArray();

			//echo '<pre>',print_r($arr,1),'</pre>';

			$x = 0;
			foreach ($arreglo as $precio) {
				if($x > 0){
					$zona = 0;
					if (isset($ZonaCargarPrecioProducto)) {
						$zona = $ZonaCargarPrecioProducto;
					}
					if (isset($precio[0]) && $precio[0] != "") {
						//COLUMNAS
						$referencia = $precio[0];
						$precio3Neto = round2deci($precio[1]); //PRECIO MÁS BAJO
						//$NombreImpuesto = strtoupper($precio[2]);
						$NombreImpuesto = preg_replace('/\s+/', '', strtoupper($precio[2]));
						if ($NombreImpuesto == "IVA") {
							$cantidadImpuesto = 1.16;
							$calcularImpuesto = 0.16;
							$Precio3Bruto = round2deci(($precio3Neto / $cantidadImpuesto));
						}else if($NombreImpuesto == "IEPS"){
							$cantidadImpuesto = 1.08;
							$calcularImpuesto = 0.08;
							$Precio3Bruto = round2deci(($precio3Neto / $cantidadImpuesto));
						}else{
							$Precio3Bruto = round2deci($precio3Neto);
						}
						
						$PorcentajePrecio1 = $precio[3];
						$TotalPrecio1 = round2deci($precio[4]);
						$PorcentajePrecio2 = $precio[5];
						$TotalPrecio2 = round2deci($precio[6]);
						$TotalPrecio4 = round2deci($precio[7]);
						$TotalPrecio5 = round2deci($precio[8]);
						$PrecioFinal1 = 0;
						$PrecioFinal2 = 0;
						$PrecioFinal3 = 0;
						$PrecioFinal4 = 0;
						$PrecioFinal5 = 0;
						$PorcentajePrecio4 = 0;
						$PorcentajePrecio5 = 0;

						//CALCULO PRECIO 2
						if ($TotalPrecio2 == 0) { //Si se agrego un porcentaje se ignora el total
							$MontoDeAumento = $Precio3Bruto * ($PorcentajePrecio2 / 100);
							$PrecioFinal2 = round2deci($Precio3Bruto + $MontoDeAumento);
						}else{
							$PrecioFinal2 = round2deci($TotalPrecio2);
							$PorcentajePrecio2 = 0;
						}
						
						//CALCULO PRECIO 1
						if ($TotalPrecio1 == 0) { //Si se agrego un porcentaje se ignora el total
							$MontoDeAumento = $PrecioFinal2 * ($PorcentajePrecio1 / 100);
							$PrecioFinal1 = round2deci($PrecioFinal2 + $MontoDeAumento);
						}else{
							$PrecioFinal1 = round2deci($TotalPrecio1);
							$PorcentajePrecio1 = 0;
						}

						//CALCULAR PRECIO 4
						//Primer se calcula el precio 2 despues al total del precio2 se aplica el aumento del precio1
						$PrecioFinal4 = round2deci($TotalPrecio4);

						//CALCULO PRECIO 5
						$PrecioFinal5 = round2deci($TotalPrecio5);
						
						//CONSULTAR SI EXISTE LA REFERENCIA EN PRODUCTOS
						$query = "SELECT ID_Producto, Referencia FROM productos WHERE Referencia = '$referencia'";
						$row = $omodelo->_consultar($query);
						$numerofilas = $omodelo->numerofilas;
						
						if($row == 'si'){
							echo "Error Referencia Producto: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas > 0){ //Si existe la referencia entonces consultamos si ya estan los precios para modificarlos, si no existen los insertamos

								//GUARDAR EL DETALLE DEL PRECIO PARA SABER QUE AUMENTOS SE HICIERON
								$queryDetallePrecio = "INSERT INTO detalles_precios SET FK_Referencia = '".$referencia."', Precio_3 = '$precio3Neto', Impuesto = '$NombreImpuesto', Porcentaje_Precio_1 = '$PorcentajePrecio1', Porcentaje_Precio_2 = '$PorcentajePrecio2', Porcentaje_Precio_4 = '$PorcentajePrecio4', Porcentaje_Precio_5 = '$PorcentajePrecio5', Total_Precio_1 = '$TotalPrecio1', Total_Precio_2 = '$TotalPrecio2', Total_Precio_4 = '$TotalPrecio4', Total_Precio_5 = '$TotalPrecio5', Fecha_Registro = '$fecha'";
								$errorDetallePrecio = $omodelo->_insertar($queryDetallePrecio);

								if ($errorDetallePrecio == 'si') {
								    echo "Error insert detalle precio: " . mysqli_error($omodelo->link);
								}
								if ($PrecioFinal1 > 0) {
									//PRECIO 1
									$queryP1 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '0' AND Nombre = 'Precio 1' AND FK_Zona = '$zona'";
									$rowP1 = $omodelo->_consultar($queryP1);
									$numerofilasP1 = $omodelo->numerofilas;
									
									if($rowP1 == 'si'){
										echo "Error buscar precio 1: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasP1 > 0){ //HACER UPDATE PRECIO 1
											$queryUP1 = "UPDATE precios SET Precio = '".$PrecioFinal1."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio1' WHERE ID_Precio = '".$rowP1[0]["ID_Precio"]."'";
											$errorUP1 = $omodelo->_insertar($queryUP1);
											if ($errorUP1 == 'si') {
											    echo "Error update precio1: " . mysqli_error($omodelo->link);
											}
										}else{ //INSERTAR NUEVO PRECIO 1
											$queryIN1 = "INSERT INTO precios SET FK_Producto = '".$row[0]["ID_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '0', Nombre = 'Precio 1', Precio = '".$PrecioFinal1."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio1'";
											$errorIN1 = $omodelo->_insertar($queryIN1);

											if ($errorIN1 == 'si') {
											    echo "Error insert precio1: " . mysqli_error($omodelo->link);
											}
										}
									}
								}

								if ($PrecioFinal2 > 0) {
									//PRECIO 2
									$queryP2 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '0' AND Nombre = 'Precio 2' AND FK_Zona = '$zona'";
									$rowP2 = $omodelo->_consultar($queryP2);
									$numerofilasP2 = $omodelo->numerofilas;
									
									if($rowP2 == 'si'){
										echo "Error buscar precio 2: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasP2 > 0){ //HACER UPDATE PRECIO 2
											$queryUP2 = "UPDATE precios SET Precio = '".$PrecioFinal2."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio2' WHERE ID_Precio = '".$rowP2[0]["ID_Precio"]."'";
											$errorUP2 = $omodelo->_insertar($queryUP2);

											if ($errorUP2 == 'si') {
											    echo "Error update precio2: " . mysqli_error($omodelo->link);
											}
										}else{ //INSERTAR NUEVO PRECIO 2
											$queryIN2 = "INSERT INTO precios SET FK_Producto = '".$row[0]["ID_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '0', Nombre = 'Precio 2', Precio = '".$PrecioFinal2."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio2'";
											$errorIN2 = $omodelo->_insertar($queryIN2);

											if ($errorIN2 == 'si') {
											    echo "Error insert precio2: " . mysqli_error($omodelo->link);
											}
										}
									}
								}

								if ($Precio3Bruto > 0) {
									//PRECIO 3
									$queryP3 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '0' AND Nombre = 'Precio 3' AND FK_Zona = '$zona'";
									$rowP3 = $omodelo->_consultar($queryP3);
									$numerofilasP3 = $omodelo->numerofilas;
									
									if($rowP3 == 'si'){
										echo "Error buscar precio 3: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasP3 > 0){ //HACER UPDATE PRECIO 3
											$queryUP3 = "UPDATE precios SET Precio = '".$Precio3Bruto."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '0' WHERE ID_Precio = '".$rowP3[0]["ID_Precio"]."'";
											$errorUP3 = $omodelo->_insertar($queryUP3);

											if ($errorUP3 == 'si') {
											    echo "Error update precio3: " . mysqli_error($omodelo->link);
											}
										}else{ //INSERTAR NUEVO PRECIO 3
											$queryIN3 = "INSERT INTO precios SET FK_Producto = '".$row[0]["ID_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '0', Nombre = 'Precio 3', Precio = '".$Precio3Bruto."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '0'";
											$errorIN3 = $omodelo->_insertar($queryIN3);

											if ($errorIN3 == 'si') {
											    echo "Error insert precio3: " . mysqli_error($omodelo->link);
											}
										}
									}
								}


								if ($PrecioFinal4 > 0) {
									//PRECIO 4
									$queryP4 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '0' AND Nombre = 'Precio 4' AND FK_Zona = '$zona'";
									$rowP4 = $omodelo->_consultar($queryP4);
									$numerofilasP4 = $omodelo->numerofilas;
									
									if($rowP4 == 'si'){
										echo "Error buscar precio 4: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasP4 > 0){ //HACER UPDATE PRECIO 4
											$queryUP4 = "UPDATE precios SET Precio = '".$PrecioFinal4."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio4' WHERE ID_Precio = '".$rowP4[0]["ID_Precio"]."'";
											$errorUP4 = $omodelo->_insertar($queryUP4);

											if ($errorUP4 == 'si') {
											    echo "Error update precio4: " . mysqli_error($omodelo->link);
											}
										}else{ //INSERTAR NUEVO PRECIO 4
											$queryIN4 = "INSERT INTO precios SET FK_Producto = '".$row[0]["ID_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '0', Nombre = 'Precio 4', Precio = '".$PrecioFinal4."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio4'";
											$errorIN4 = $omodelo->_insertar($queryIN4);

											if ($errorIN4 == 'si') {
											    echo "Error insert precio4: " . mysqli_error($omodelo->link);
											}
										}
									}
								}

								if ($PrecioFinal5 > 0) {
									//PRECIO 5
									$queryP5 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$row[0]["ID_Producto"]."' AND FK_Presentacion = '0' AND Nombre = 'Precio 5' AND FK_Zona = '$zona'";
									$rowP5 = $omodelo->_consultar($queryP5);
									$numerofilasP5 = $omodelo->numerofilas;
									
									if($rowP5 == 'si'){
										echo "Error buscar precio 5: ".mysqli_error($omodelo->link);
									}else{
										if($numerofilasP5 > 0){ //HACER UPDATE PRECIO 5
											$queryUP5 = "UPDATE precios SET Precio = '".$PrecioFinal5."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio5' WHERE ID_Precio = '".$rowP5[0]["ID_Precio"]."'";
											$errorUP5 = $omodelo->_insertar($queryUP5);

											if ($errorUP5 == 'si') {
											    echo "Error update precio5: " . mysqli_error($omodelo->link);
											}
										}else{ //INSERTAR NUEVO PRECIO 5
											$queryIN5 = "INSERT INTO precios SET FK_Producto = '".$row[0]["ID_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '0', Nombre = 'Precio 5', Precio = '".$PrecioFinal5."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio5'";
											$errorIN5 = $omodelo->_insertar($queryIN5);

											if ($errorIN5 == 'si') {
											    echo "Error insert precio5: " . mysqli_error($omodelo->link);
											}
										}
									}
								}

							}else{//Si no existe consultamos en presentaciones
								//CONSULTAR SI EXISTE LA REFERENCIA EN PRODUCTOS
								$query = "SELECT ID_Presentacion, FK_Producto, Referencia FROM presentaciones WHERE Referencia = '$referencia'";
								$rowPres = $omodelo->_consultar($query);
								$numerofilas = $omodelo->numerofilas;
								
								if($rowPres == 'si'){
									echo "Error Referencia Presentacion: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilas > 0){

										//GUARDAR EL DETALLE DEL PRECIO PARA SABER QUE AUMENTOS SE HICIERON
										$queryDetallePrecio = "INSERT INTO detalles_precios SET FK_Referencia = '".$referencia."', Precio_3 = '$precio3Neto', Impuesto = '$cantidadImpuesto', Porcentaje_Precio_1 = '$PorcentajePrecio1', Porcentaje_Precio_2 = '$PorcentajePrecio2', Porcentaje_Precio_4 = '$PorcentajePrecio4', Porcentaje_Precio_5 = '$PorcentajePrecio5', Total_Precio_1 = '$TotalPrecio1', Total_Precio_2 = '$TotalPrecio2', Total_Precio_4 = '$TotalPrecio4', Total_Precio_5 = '$TotalPrecio5', Fecha_Registro = '$fecha'";
										$errorDetallePrecio = $omodelo->_insertar($queryDetallePrecio);

										if ($errorDetallePrecio == 'si') {
										    echo "Error insert detalle precio: " . mysqli_error($omodelo->link);
										}
										if ($PrecioFinal1 > 0) {
											//PRECIO 1
											$queryP1 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$rowPres[0]["FK_Producto"]."' AND FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."' AND Nombre = 'Precio 1' AND FK_Zona = '$zona'";
											$rowP1 = $omodelo->_consultar($queryP1);
											$numerofilasP1 = $omodelo->numerofilas;
											
											if($rowP1 == 'si'){
												echo "Error buscar precio 1: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasP1 > 0){ //HACER UPDATE PRECIO 1
													$queryUP1 = "UPDATE precios SET Precio = '".$PrecioFinal1."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio1' WHERE ID_Precio = '".$rowP1[0]["ID_Precio"]."'";
													$errorUP1 = $omodelo->_insertar($queryUP1);

													if ($errorUP1 == 'si') {
													    echo "Error update precio1: " . mysqli_error($omodelo->link);
													}
												}else{ //INSERTAR NUEVO PRECIO 1
													$queryIN1 = "INSERT INTO precios SET FK_Producto = '".$rowPres[0]["FK_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."', Nombre = 'Precio 1', Precio = '".$PrecioFinal1."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio1'";
													$errorIN1 = $omodelo->_insertar($queryIN1);

													if ($errorIN1 == 'si') {
													    echo "Error insert precio1: " . mysqli_error($omodelo->link);
													}
												}
											}
										}

										if ($PrecioFinal2 > 0) {
											//PRECIO 2
											$queryP2 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$rowPres[0]["FK_Producto"]."' AND FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."' AND Nombre = 'Precio 2' AND FK_Zona = '$zona'";
											$rowP2 = $omodelo->_consultar($queryP2);
											$numerofilasP2 = $omodelo->numerofilas;
											
											if($rowP2 == 'si'){
												echo "Error buscar precio 2: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasP2 > 0){ //HACER UPDATE PRECIO 2
													$queryUP2 = "UPDATE precios SET Precio = '".$PrecioFinal2."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio2' WHERE ID_Precio = '".$rowP2[0]["ID_Precio"]."'";
													$errorUP2 = $omodelo->_insertar($queryUP2);

													if ($errorUP2 == 'si') {
													    echo "Error update precio2: " . mysqli_error($omodelo->link);
													}
												}else{ //INSERTAR NUEVO PRECIO 2
													$queryIN2 = "INSERT INTO precios SET FK_Producto = '".$rowPres[0]["FK_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."', Nombre = 'Precio 2', Precio = '".$PrecioFinal2."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio2'";
													$errorIN2 = $omodelo->_insertar($queryIN2);

													if ($errorIN2 == 'si') {
													    echo "Error insert precio2: " . mysqli_error($omodelo->link);
													}
												}
											}
										}

										if ($precio3Neto > 0) {
											//PRECIO 3
											$queryP3 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$rowPres[0]["FK_Producto"]."' AND FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."' AND Nombre = 'Precio 3' AND FK_Zona = '$zona'";
											$rowP3 = $omodelo->_consultar($queryP3);
											$numerofilasP3 = $omodelo->numerofilas;
											
											if($rowP3 == 'si'){
												echo "Error buscar precio 3: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasP3 > 0){ //HACER UPDATE PRECIO 3
													$queryUP3 = "UPDATE precios SET Precio = '".$precio3Neto."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '0' WHERE ID_Precio = '".$rowP3[0]["ID_Precio"]."'";
													$errorUP3 = $omodelo->_insertar($queryUP3);

													if ($errorUP3 == 'si') {
													    echo "Error update precio3: " . mysqli_error($omodelo->link);
													}
												}else{ //INSERTAR NUEVO PRECIO 3
													$queryIN3 = "INSERT INTO precios SET FK_Producto = '".$rowPres[0]["FK_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."', Nombre = 'Precio 3', Precio = '".$precio3Neto."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '0'";
													$errorIN3 = $omodelo->_insertar($queryIN3);

													if ($errorIN3 == 'si') {
													    echo "Error insert precio3: " . mysqli_error($omodelo->link);
													}
												}
											}
										}

										if ($PrecioFinal4 > 0) {
											//PRECIO 4
											$queryP4 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$rowPres[0]["FK_Producto"]."' AND FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."' AND Nombre = 'Precio 4' AND FK_Zona = '$zona'";
											$rowP4 = $omodelo->_consultar($queryP4);
											$numerofilasP4 = $omodelo->numerofilas;
											
											if($rowP4 == 'si'){
												echo "Error buscar precio 4: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasP4 > 0){ //HACER UPDATE PRECIO 4
													$queryUP4 = "UPDATE precios SET Precio = '".$PrecioFinal4."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio4' WHERE ID_Precio = '".$rowP4[0]["ID_Precio"]."'";
													$errorUP4 = $omodelo->_insertar($queryUP4);

													if ($errorUP4 == 'si') {
													    echo "Error update precio4: " . mysqli_error($omodelo->link);
													}
												}else{ //INSERTAR NUEVO PRECIO 4
													$queryIN4 = "INSERT INTO precios SET FK_Producto = '".$rowPres[0]["FK_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."', Nombre = 'Precio 4', Precio = '".$PrecioFinal4."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio4'";
													$errorIN4 = $omodelo->_insertar($queryIN4);

													if ($errorIN4 == 'si') {
													    echo "Error insert precio4: " . mysqli_error($omodelo->link);
													}
												}
											}
										}

										if ($PrecioFinal5 > 0) {
											//PRECIO 5
											$queryP5 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$rowPres[0]["FK_Producto"]."' AND FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."' AND Nombre = 'Precio 5' AND FK_Zona = '$zona'";
											$rowP5 = $omodelo->_consultar($queryP5);
											$numerofilasP5 = $omodelo->numerofilas;
											
											if($rowP5 == 'si'){
												echo "Error buscar precio 5: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilasP5 > 0){ //HACER UPDATE PRECIO 5
													$queryUP5 = "UPDATE precios SET Precio = '".$PrecioFinal5."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio5' WHERE ID_Precio = '".$rowP5[0]["ID_Precio"]."'";
													$errorUP5 = $omodelo->_insertar($queryUP5);

													if ($errorUP5 == 'si') {
													    echo "Error update precio5: " . mysqli_error($omodelo->link);
													}
												}else{ //INSERTAR NUEVO PRECIO 5
													$queryIN5 = "INSERT INTO precios SET FK_Producto = '".$rowPres[0]["FK_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."', Nombre = 'Precio 5', Precio = '".$PrecioFinal5."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '$PorcentajePrecio5'";
													$errorIN5 = $omodelo->_insertar($queryIN5);

													if ($errorIN5 == 'si') {
													    echo "Error insert precio5: " . mysqli_error($omodelo->link);
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}

				$x++;
			}
			echo "Correcto";
        }
    }
}

function round_up ($value, $places=0) {
  if ($places < 0) { $places = 0; }
  $mult = pow(10, $places);
  return ceil($value * $mult) / $mult;
}

function round2deci($number){
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

function ceil_dec(float $number, int $precision = 2, string $separator = '.') : float
{
    $numberpart    = explode($separator, (string)$number);
    $numberpart[1] = substr_replace($numberpart[1], $separator, $precision, 0);
    if ($numberpart[0] >= 0) {
        $numberpart[1] = ceil($numberpart[1]);
    } else {
        $numberpart[1] = floor($numberpart[1]);
    }

    $ceil_number = [$numberpart[0], $numberpart[1]];

    return (float)implode($separator, $ceil_number);
}
?>