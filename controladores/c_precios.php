<?php 
class precios{
	
	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$buscar = $omodelo->link->real_escape_string($buscar);
		$limit = $omodelo->link->real_escape_string($limit);
		$pagina = $omodelo->link->real_escape_string($pagina);
		$ordenColumna = $omodelo->link->real_escape_string($ordenColumna);
		$orden = $omodelo->link->real_escape_string($orden);
		$zona = $omodelo->link->real_escape_string($zona);
		$tipoProd = $omodelo->link->real_escape_string($tipoProd);
		if(isset($producto)){
			$producto = $omodelo->link->real_escape_string($producto);
		}else{
			$producto = '';
		}
		$arreglo = array();

		$busqueda = '';
		if($zona != 'Todas'){
			$busqueda = "WHERE FK_Zona = '$zona' ";
		}

		if($tipoProd == 'Uno' && $producto != ''){
			if($busqueda != ''){
				$busqueda .= "AND precios.FK_Producto = '$producto' ";
			}else{
				$busqueda .= "WHERE precios.FK_Producto = '$producto' ";
			}
		}

		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));

			if($busqueda == ''){
				$busqueda = 'WHERE ';
			}else{
				$busqueda .= 'AND ';
			}

			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(productos.Descripcion, (SELECT Nombre FROM zonas WHERE FK_Zona = ID_Zona), IFNULL(presentaciones.Nombre, ''), precios.Nombre, IFNULL((SELECT GROUP_CONCAT(Empresa) FROM detalles_proveedores_productos INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor WHERE FK_Producto = ID_Producto), '')) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Precio, 
        IFNULL(presentaciones.Costo, 0) AS CostoPre, 
        productos.Costo AS CostoPro, 

        /* Determinamos el Costo Final: Si Neto es 0 o NULL, usamos Costo Normal */
        @costo_real := COALESCE(
            NULLIF(IF(IFNULL(presentaciones.Costo_Neto, 0) = 0, productos.Costo_Neto, presentaciones.Costo_Neto), 0),
            NULLIF(IF(IFNULL(presentaciones.Costo, 0) = 0, productos.Costo, presentaciones.Costo), 0),
            0
        ) AS CostoFinal,
        
        /* Cálculo correcto del Margen de Utilidad */
		IFNULL(((precios.Precio - @costo_real) / NULLIF(precios.Precio, 0)) * 100, 0) AS Margen,

        productos.Descripcion AS Producto, (SELECT Nombre FROM zonas WHERE FK_Zona = ID_Zona) AS NombreZonaPrecio, IFNULL((SELECT GROUP_CONCAT(Empresa) FROM detalles_proveedores_productos INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor WHERE FK_Producto = ID_Producto), '') AS Proveedores, IFNULL(presentaciones.Nombre, '') AS Presentacion, precios.Nombre AS Nombre, precios.Precio AS Precio, precios.Precio_Mayoreo AS Mayoreo, (SELECT COUNT(*) FROM precios INNER JOIN productos ON precios.FK_Producto = ID_Producto INNER JOIN zonas ON FK_Zona = ID_Zona LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion $busqueda) AS Num FROM precios INNER JOIN productos ON precios.FK_Producto = ID_Producto INNER JOIN zonas ON FK_Zona = ID_Zona LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
        
        $row = $omodelo->_consultar($query);
        $numerofilas = $omodelo->numerofilas;

        if($row == 'si'){
            echo "Error: ".mysqli_error($omodelo->link);
        }else{
            if($numerofilas > 0){
                for($i=0; $i<$numerofilas; $i++){

                    $arreglo['data'][$i] = array(
                        'ID' => $row[$i]['ID_Precio'],
                        'Producto' => $row[$i]['Producto']."<br>".$row[$i]['NombreZonaPrecio'],
                        'Presentacion' => $row[$i]['Presentacion'],
                        'Proveedores' => $row[$i]['Proveedores'],
                        'Nombre' => $row[$i]['Nombre'],
                        'Precio' => '<span class="dinero">'.$row[$i]['Precio'].'</span>',
                        'Margen' => number_format($row[$i]['Margen'], 2).'%',
                        'CostoFinal' => '$'.number_format($row[$i]['CostoFinal'], 2)
                    );
                }

                $arreglo['totales'] = array('NumRows' => $row[0]['Num']);   
            }
        }

        echo json_encode($arreglo);
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == 'precio'){
			$id = $omodelo->link->real_escape_string($id);
			$valor = $omodelo->link->real_escape_string($valor);
			$tipoPrecio = $omodelo->link->real_escape_string($tipoPrecio);

			$query = "UPDATE precios SET $tipoPrecio = $valor WHERE ID_Precio = '$id'";
			$error = $omodelo->_insertar($query);
			
			if($error == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == 'productos'){
			$buscar = $omodelo->link->real_escape_string($buscar);
			$limit = $omodelo->link->real_escape_string($limit);
			$pagina = $omodelo->link->real_escape_string($pagina);
			$ordenColumna = $omodelo->link->real_escape_string($ordenColumna);
			$orden = $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(Codigo, Descripcion) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Producto, Codigo, Descripcion, Imagen, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$imagen = '<a href="vistas/assets/archivos/fotosProductos/default.jpg" data-fancybox="images">
							<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
							</div>
						</a><br>';
						if ($row[$i]["Imagen"] != "") {
							if($row[$i]["Imagen"] != "" && file_exists("vistas/assets/archivos/fotosProductos/".$row[$i]["Imagen"])){
								$imagen = '<a href="vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"].'" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
							}	
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Codigo' => $row[$i]['Codigo'],
							'Descripcion' => $row[$i]['Descripcion'],
							'Imagen' => $imagen,
							'Acciones' => '<button type="button" class="btn btn-sm btn-primary bSeleccionarProdPrecio" attrID="'.$row[$i]['ID_Producto'].'">Seleccionar</button>'
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "InsertarNuevoPrecio3"){
			$referencia = $omodelo->link->real_escape_string($ReferenciaPrecioNuevo);
			$precio3Neto = $omodelo->link->real_escape_string($Precio3PrecioNuevo);
			$NombreImpuesto = $omodelo->link->real_escape_string($ImpuestosPrecioNuevo);
			$AumentoPrecioNuevo = $omodelo->link->real_escape_string($AumentoPrecioNuevo);
			$zona = $omodelo->link->real_escape_string($ZonaPrecioNuevo);
			if($NombreImpuesto == "IVA"){
				$cantidadImpuesto = 1.16;
				$calcularImpuesto = 0.16;
				$Precio3Bruto = $omodelo->round2deci(($precio3Neto / $cantidadImpuesto));
			}else if($NombreImpuesto == "IEPS3"){
				$cantidadImpuesto = 1.03;
				$calcularImpuesto = 0.03;
				$Precio3Bruto = $omodelo->round2deci(($precio3Neto / $cantidadImpuesto));
			}else if($NombreImpuesto == "IEPS"){
				$cantidadImpuesto = 1.08;
				$calcularImpuesto = 0.08;
				$Precio3Bruto = $omodelo->round2deci(($precio3Neto / $cantidadImpuesto));
			}else{
				$cantidadImpuesto = 0;
				$calcularImpuesto = 0;
				$Precio3Bruto = $omodelo->round2deci($precio3Neto);
			}


			$PorcentajePrecio1 = $AumentoPrecioNuevo;
			$TotalPrecio1 = 0;
			$PorcentajePrecio2 = $AumentoPrecioNuevo;
			$TotalPrecio2 = 0;
			$TotalPrecio4 = 0;
			$TotalPrecio5 = 0;
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
				$PrecioFinal2 = $omodelo->round2deci($Precio3Bruto + $MontoDeAumento);
			}else{
				$PrecioFinal2 = $omodelo->round2deci($TotalPrecio2);
				$PorcentajePrecio2 = 0;
			}
			
			//CALCULO PRECIO 1
			if ($TotalPrecio1 == 0) { //Si se agrego un porcentaje se ignora el total
				$MontoDeAumento = $PrecioFinal2 * ($PorcentajePrecio1 / 100);
				$PrecioFinal1 = $omodelo->round2deci($PrecioFinal2 + $MontoDeAumento);
			}else{
				$PrecioFinal1 = $omodelo->round2deci($TotalPrecio1);
				$PorcentajePrecio1 = 0;
			}

			//CALCULAR PRECIO 4
			//Primer se calcula el precio 2 despues al total del precio2 se aplica el aumento del precio1
			$PrecioFinal4 = $omodelo->round2deci($TotalPrecio4);

			//CALCULO PRECIO 5
			$PrecioFinal5 = $omodelo->round2deci($TotalPrecio5);
			
			//CONSULTAR SI EXISTE LA REFERENCIA EN PRODUCTOS
			$query = "SELECT ID_Producto, Referencia FROM productos WHERE Referencia = '$referencia'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			
			if($row == 'si'){
				echo "Error Referencia Producto: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){ //Si existe la referencia entonces consultamos si ya estan los precios para modificarlos, si no existen los insertamos
					//GUARDAR EL DETALLE DEL PRECIO PARA SABER QUE AUMENTOS SE HICIERON
					$queryDetallePrecio = "INSERT INTO detalles_precios SET FK_Referencia = '".$referencia."', Precio_3 = '$precio3Neto', Impuesto = '$NombreImpuesto', Porcentaje_Precio_1 = '$PorcentajePrecio1', Porcentaje_Precio_2 = '$PorcentajePrecio2', Porcentaje_Precio_4 = '$PorcentajePrecio4', Porcentaje_Precio_5 = '$PorcentajePrecio5', Total_Precio_1 = '$TotalPrecio1', Total_Precio_2 = '$TotalPrecio2', Total_Precio_4 = '$TotalPrecio4', Total_Precio_5 = '$TotalPrecio5', Fecha_Registro = NOW()";
					$errorDetallePrecio = $omodelo->_insertar($queryDetallePrecio);

					if ($errorDetallePrecio == 'si') {
					    echo "Error insert detalle precio: " . mysqli_error($omodelo->link);
					}else{
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

						echo "Correcto";
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
							$queryDetallePrecio = "INSERT INTO detalles_precios SET FK_Referencia = '".$referencia."', Precio_3 = '$precio3Neto', Impuesto = '$cantidadImpuesto', Porcentaje_Precio_1 = '$PorcentajePrecio1', Porcentaje_Precio_2 = '$PorcentajePrecio2', Porcentaje_Precio_4 = '$PorcentajePrecio4', Porcentaje_Precio_5 = '$PorcentajePrecio5', Total_Precio_1 = '$TotalPrecio1', Total_Precio_2 = '$TotalPrecio2', Total_Precio_4 = '$TotalPrecio4', Total_Precio_5 = '$TotalPrecio5', Fecha_Registro = NOW()";
							$errorDetallePrecio = $omodelo->_insertar($queryDetallePrecio);

							if ($errorDetallePrecio == 'si') {
							    echo "Error insert detalle precio: " . mysqli_error($omodelo->link);
							}else{

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

								if ($Precio3Bruto > 0) {
									//PRECIO 3
									$queryP3 = "SELECT ID_Precio FROM precios WHERE FK_Producto = '".$rowPres[0]["FK_Producto"]."' AND FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."' AND Nombre = 'Precio 3' AND FK_Zona = '$zona'";
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
											$queryIN3 = "INSERT INTO precios SET FK_Producto = '".$rowPres[0]["FK_Producto"]."', FK_Zona = '$zona', FK_Presentacion = '".$rowPres[0]["ID_Presentacion"]."', Nombre = 'Precio 3', Precio = '".$Precio3Bruto."', Porcentaje_Impuesto_Aplicado = '$cantidadImpuesto', Porcentaje_Aumento_Aplicado = '0'";
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
								echo "Correcto";
							}
						}
					}
				}
			}
		//CIERRE DE INSERTARNUEVOPRECIO3
		}
	}


}
?>