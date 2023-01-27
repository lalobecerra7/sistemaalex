<?php  
class inventario {

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
			if ($omodelo->permisos() != 'Administrador' && $_SESSION['user_admin']['FK_Sucursal'] != '0') {
				$busqueda = ' AND ';
			}

			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(Codigo, Descripcion, IF(FK_Presentacion = 0, CONCAT(Nombre_Unidad, ' ', Abreviatura_Unidad), CONCAT(IFNULL((SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), ''), ' ', IFNULL((SELECT Abreviatura FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), ''))), inventario.Cantidad, sucursales.Nombre) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$where = '';
		if ($omodelo->permisos() != 'Administrador' && $_SESSION['user_admin']['FK_Sucursal'] != '0') {
			$where = "WHERE inventario.FK_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal'];
		}

		$query =  "SELECT ID_Inventario, inventario.FK_Producto AS FK_Producto, Imagen, Codigo, Descripcion, Precio, Nombre_Unidad, Abreviatura_Unidad, inventario.FK_Presentacion AS FK_Presentacion, IFNULL((SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), '') AS Presentacion, IFNULL((SELECT Abreviatura FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), '') AS Abreviatura, inventario.Cantidad AS Existencia, inventario.FK_Sucursal AS FK_Sucursal, sucursales.Nombre AS Sucursal, IFNULL((SELECT SUM(Costo * Cantidad) FROM merma WHERE FK_Inventario = ID_Inventario), 0) AS Costo, productos.Costo AS Costo_Producto, IFNULL((SELECT Costo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), '') AS Costo_Presentacion, IFNULL((SELECT SUM(Cantidad) FROM merma WHERE FK_Inventario = ID_Inventario), 0) AS Merma, (SELECT COUNT(*) FROM inventario INNER JOIN productos ON inventario.FK_Producto = ID_Producto INNER JOIN sucursales ON inventario.FK_Sucursal = ID_Sucursal $where $busqueda) AS 'Num' FROM inventario INNER JOIN productos ON inventario.FK_Producto = ID_Producto INNER JOIN sucursales ON inventario.FK_Sucursal = ID_Sucursal $where $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$foto = '<a href="vistas/assets/archivos/fotosProductos/default.jpg" data-fancybox="images">
						<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
						</div>
					</a><br>';

					if ($row[$i]["Imagen"] != "") {
						if($row[$i]["Imagen"] != "" && file_exists("vistas/assets/archivos/fotosProductos/".$row[$i]["Imagen"])){
							$foto = '<a href="vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"].'" data-fancybox="images">
								<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
								</div>
							</a><br>';
						}	
					}

					$precios = '';
					$queryZona = "SELECT ID_Zona, Nombre FROM zonas";
					$rowZona = $omodelo->_consultar($queryZona);
					$numerofilasZona = $omodelo->numerofilas;

					if($rowZona == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasZona > 0){
							for ($a=0; $a < $numerofilasZona; $a++) { 
								$queryPrecios = "SELECT ID_Precio, FK_Producto, FK_Zona, Nombre, Precio, Precio_Mayoreo FROM precios WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Zona = '".$rowZona[$a]["ID_Zona"]."'";
								$rowPrecios = $omodelo->_consultar($queryPrecios);
								$numerofilasPrecios = $omodelo->numerofilas;

								if($rowPrecios == 'si'){
									echo "Error 3: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilasPrecios > 0){
										$precios .= "Zona: ".$rowZona[$a]["Nombre"]."<br>";
										
										for ($x=0; $x < $numerofilasPrecios; $x++) { 
											$precios .= $rowPrecios[$x]["Nombre"].": <b>$".number_format($rowPrecios[$x]["Precio"], 2)."</b><br>";
										}
									}	
								}
							}
						}
					}

					$costo = 0;
					$presentacion = 'Sin presentación';
					$abreviatura = '';
					if($row[$i]['FK_Presentacion'] != '0'){
						$costo = $row[$i]['Costo_Producto'];
						if($row[$i]['Abreviatura'] != ""){
							$abreviatura = '('.$row[$i]['Abreviatura'].')';
						}
						$presentacion = $row[$i]['Presentacion'].$abreviatura;
					}else{
						$costo = $row[$i]['Costo_Presentacion'];
						$precios = 'General: <b class="dinero">'.$row[$i]['Precio'].'</b><br>'.$precios;
						if($row[$i]['Nombre_Unidad'] != ""){
							if($row[$i]['Abreviatura_Unidad'] != ""){
								$abreviatura = '('.$row[$i]['Abreviatura_Unidad'].')';
							}
							$presentacion = $row[$i]['Nombre_Unidad'].$abreviatura;
						}
					}

					$botonPermisosVerMerma = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][2] == '1') {
						$botonPermisosVerMerma = '<br><button type="button" class="btn btn-link btn-sm verDetallesMerma" attrID="'.$row[$i]['ID_Inventario'].'" title="Detalles Merma">Ver detalles <i class="fas fa-eye"></i></button>';
					}

					$botonPermisosAgregarMerma = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][3] == '1') {
						$botonPermisosAgregarMerma = '<button class="btn btn-warning btn-sm mb-1 AgregarMerma" attrID="'.$row[$i]['ID_Inventario'].'" attrCosto="'.$costo.'" title="Registrar merma"><i class="fas fa-level-down"></i></button>';
					}

					$botoVerConversiones = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][6] == '1') {
						$botoVerConversiones = '<br><button type="button" class="btn btn-link btn-sm verDetallesConversiones" attrProducto="'.$row[$i]['FK_Producto'].'" attrPresentacion="'.$row[$i]['FK_Presentacion'].'" attrSucursal="'.$row[$i]['FK_Sucursal'].'" title="Ver conversiones">Ver conversiones <i class="fas fa-eye"></i></button>';
					}

					$botonConversion = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][7] == '1') {
						$botonConversion = '<button class="btn btn-success btn-sm mb-1 ConvertirProducto" attrProducto="'.$row[$i]['FK_Producto'].'" attrPresentacion="'.$row[$i]['FK_Presentacion'].'" attrSucursal="'.$row[$i]['FK_Sucursal'].'" title="Convertir producto"><i class="fa-solid fa-boxes-stacked"></i></button>';
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Inventario'],
						'Descripcion' => $foto.$row[$i]['Descripcion'].'<br>'.$presentacion.'<br>Codigo: <b>'.$row[$i]['Codigo'].'</b>',
						'Existencia' => '<div>Total: <b class="cantidad">'.$row[$i]['Existencia'].'</b><br>Sucursal: <b>'.$row[$i]['Sucursal'].'</b></div>'.$botoVerConversiones,
						'Precios' => $precios,
						'Merma' => 'Cantidad: <b class="cantidad">'.$row[$i]["Merma"].'</b><br>Total: <b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b><br>'.$botonPermisosVerMerma,
						'Acciones' => $botonPermisosAgregarMerma.' '.$botonConversion,
					);
					
				}

				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
			}
		}

		echo json_encode($arreglo);
	}

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == 'agregarMerma'){
			$inventario = $omodelo->link->real_escape_string($inventario);
			$fechaMerma = $omodelo->link->real_escape_string($fechaMerma);
			$cantidadMerma = $omodelo->link->real_escape_string($cantidadMerma);
			$motivoMerma = $omodelo->link->real_escape_string(trim($motivoMerma));
			$costo = $omodelo->link->real_escape_string($costo);

			$query = "INSERT INTO merma SET FK_Inventario = '$inventario', Costo = '$costo', Cantidad = '$cantidadMerma', Fecha_Merma = '$fechaMerma', Fecha_Registro = '$fecha', Motivo = '$motivoMerma', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);
			
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$IDMerma = mysqli_insert_id($omodelo->link);

				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

				$status = 1;
				if ($_FILES['FotoMerma']['size'] > 0 && $_FILES['FotoMerma']['error'] == 0) {
					$file = $_FILES["FotoMerma"];
					$nombreDoc = $file["name"];
					$tipo = $file["type"];
					$ruta_provisional = $file["tmp_name"];
					$size = $file["size"];
					$carpeta = "vistas/assets/archivos/fotosMerma/";

					if ($tipo != 'image/jpeg' && $tipo != 'image/JPEG' && $tipo != 'image/jpg' && $tipo != 'image/JPG' && $tipo != 'image/png' && $tipo != 'image/PNG' && $tipo != 'application/pdf' && $tipo != 'application/PDF' && $tipo != ''){
						echo "Error 2 Formato";
					}else if ($size > (1024*1024*10)){
						echo "Error 3 Peso";
					}else{
						$status = 0;
						$ruta = $carpeta;
					}
				}
					
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
				if($status == 0){
					$query3 = "UPDATE merma SET Foto = '".$IDMerma.'_'.$nombreDoc."' WHERE ID_Merma = '$IDMerma'";
					$error4 = $omodelo->_insertar($query3);	

					if ($error4 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.''.$IDMerma.'_'.$nombreDoc);
					}
				}
			}
		}else if($tipo == 'conversion'){
			$producto = $omodelo->link->real_escape_string($producto);
			$presentacion = $omodelo->link->real_escape_string($presentacion);
			$sucursal = $omodelo->link->real_escape_string($sucursal);
			$cantidad = $omodelo->link->real_escape_string($cantidad);

			$query = "INSERT INTO conversiones SET FK_Producto = '$producto', FK_Sucursal = '$sucursal', FK_Presentacion_Origen = '$presentacion', Cantidad_Origen = '$cantidad', Fecha_Registro = '$fecha', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);

				echo "Correcto~".$id;
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
				
				$conversiones = json_decode($conversiones, true);
				foreach ($conversiones as $conversion) {
					$conversion['ID_Presentacion'] = $omodelo->link->real_escape_string($conversion['ID_Presentacion']);
					$conversion['Cantidad'] = $omodelo->link->real_escape_string($conversion['Cantidad']);

					$query1 = "INSERT INTO detalles_conversion SET FK_Conversion = '$id', FK_Presentacion = '$conversion[ID_Presentacion]', Cantidad = '$conversion[Cantidad]'";
					$error1 = $omodelo->_insertar($query1);

					if($error1 == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						$query2 = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '$producto' AND FK_Presentacion = '$conversion[ID_Presentacion]' AND FK_Sucursal = '$sucursal'";
						$row = $omodelo->_consultar($query2);
						$numerofilas = $omodelo->numerofilas;

						if($row == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas > 0){
								$query2 = "UPDATE inventario SET Cantidad = Cantidad + '$conversion[Cantidad]' WHERE FK_Producto = '$producto' AND FK_Presentacion = '$conversion[ID_Presentacion]' AND FK_Sucursal = '$sucursal'";
								$error2 = $omodelo->_insertar($query2);

								if($error2 == 'si'){
									echo "Error 3: ".mysqli_error($omodelo->link);
								}
							}else{
								$query2 = "INSERT INTO inventario SET Cantidad = '$conversion[Cantidad]', FK_Producto = '$producto', FK_Presentacion = '$conversion[ID_Presentacion]', FK_Sucursal = '$sucursal'";
								$error2 = $omodelo->_insertar($query2);

								if($error2 == 'si'){
									echo "Error 4: ".mysqli_error($omodelo->link);
								}
							}
						}
					}
				}
			}
		}else if($tipo == 'traslados'){
			$sucursal = $omodelo->link->real_escape_string($sucursal);
			$codigo = $omodelo->link->real_escape_string($codigo);
			$arreglo = null;

			$query = "SELECT ID_Producto, Descripcion, Nombre_Unidad, Abreviatura_Unidad, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Sucursal = '$sucursal' AND FK_Producto = ID_Producto AND FK_Presentacion = 0), 0) AS Existencia FROM productos WHERE Codigo = '$codigo'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if ($numerofilas > 0) {
					$presentacion = 'Sin presentación';
					if(trim($row[0]['Nombre_Unidad']) != ''){
						$presentacion = trim($row[0]['Nombre_Unidad']);

						if(trim($row[0]['Abreviatura_Unidad']) != ''){
							$presentacion .= '('.trim($row[0]['Abreviatura_Unidad']).')';
						}
					}

					$arreglo = array('ID_Producto' => $row[0]['ID_Producto'], 'Descripcion' => $row[0]['Descripcion'], 'Presentacion' => '<button type="button" class="btn btn-sm btn-secondary bCambiarPresTras" attrID="'.$row[0]['ID_Producto'].'" title="Cambiar presentación">'.$presentacion.'</button>', 'Existencia' => $row[0]['Existencia']);

					echo json_encode($arreglo);
				}else{
					echo "No encontrado";
				}
			}
		}else if($tipo == 'consultarPrese'){
			$id =  $omodelo->link->real_escape_string($id);
			$sucursal =  $omodelo->link->real_escape_string($sucursal);
			$tabla = '';

			$query = "SELECT ID_Producto, Nombre_Unidad, Abreviatura_Unidad, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Sucursal = '$sucursal' AND FK_Producto = ID_Producto AND FK_Presentacion = 0), 0) AS Existencia FROM productos WHERE ID_Producto = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$tabla .= '<tr>
						<td>'.$row[0]['Nombre_Unidad'].'</td>
						<td>'.$row[0]['Abreviatura_Unidad'].'</td>
						<td><span class="cantidad">'.$row[0]['Existencia'].'</span></td>
						<td><button type="button" class="btn btn-sm btn-primary bSeleCamPresTras" attrID="0">Seleccionar</button></td>
					</tr>';
				}
			}

			$query = "SELECT ID_Presentacion, FK_Producto, Nombre, Abreviatura, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Sucursal = '$sucursal' AND FK_Producto = FK_Producto AND FK_Presentacion = ID_Presentacion), 0) AS Existencia FROM presentaciones WHERE FK_Producto = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$tabla .= '<tr>
							<td>'.$row[$i]['Nombre'].'</td>
							<td>'.$row[$i]['Abreviatura'].'</td>
							<td><span class="cantidad">'.$row[$i]['Existencia'].'</span></td>
							<td><button type="button" class="btn btn-sm btn-primary bSeleCamPresTras" attrID="'.$row[$i]['ID_Presentacion'].'">Seleccionar</button></td>
						</tr>';
					}
				}
			}

			echo $tabla;
		}else if($tipo == 'guardarTraslado'){
			$origen = $omodelo->link->real_escape_string($origen);
			$destino = $omodelo->link->real_escape_string($destino);
			$fechaTraslado = $omodelo->link->real_escape_string($fechaTraslado);
			$estatus = $omodelo->link->real_escape_string($estatus);

			$query = "INSERT INTO traslados SET FK_Sucursal_Origen = '$origen', FK_Sucursal_Destino = '$destino', Fecha_Traslado = '$fechaTraslado', Fecha_Registro = '$fecha', Estatus = '$estatus', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);

				echo "Correcto~".$id;
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

				$productos = json_decode($productos, true);
				foreach ($productos as $producto) {
					$producto['ID_Producto'] = $omodelo->link->real_escape_string($producto['ID_Producto']);
					$producto['ID_Presentacion'] = $omodelo->link->real_escape_string($producto['ID_Presentacion']);
					$producto['Cantidad'] = $omodelo->link->real_escape_string($producto['Cantidad']);

					$query1 = "INSERT INTO detalles_traslados SET FK_Traslado = '$id', FK_Producto = '$producto[ID_Producto]', FK_Presentacion = '$producto[ID_Presentacion]', Cantidad = '$producto[Cantidad]'";
					$error1 = $omodelo->_insertar($query1);

					if ($error1 == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($estatus == 'Completado'){
							$query = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '$producto[ID_Producto]' AND FK_Presentacion = '$producto[ID_Presentacion]' AND FK_Sucursal = '$origen'";
							$row = $omodelo->_consultar($query);
							$numerofilas = $omodelo->numerofilas;

							if($row == 'si'){
								echo "Error 3: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas > 0){
									$query2 = "UPDATE inventario SET Cantidad = Cantidad - '$producto[Cantidad]' WHERE FK_Producto = '$producto[ID_Producto]' AND FK_Presentacion = '$producto[ID_Presentacion]' AND FK_Sucursal = '$origen'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 4: ".mysqli_error($omodelo->link);
									}
								}else{
									$query2 = "INSERT INTO inventario SET Cantidad = '-$producto[Cantidad]', FK_Producto = '$producto[ID_Producto]', FK_Presentacion = '$producto[ID_Presentacion]', FK_Sucursal = '$origen'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 5: ".mysqli_error($omodelo->link);
									}
								}
							}

							$query = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '$producto[ID_Producto]' AND FK_Presentacion = '$producto[ID_Presentacion]' AND FK_Sucursal = '$destino'";
							$row = $omodelo->_consultar($query);
							$numerofilas = $omodelo->numerofilas;

							if($row == 'si'){
								echo "Error 6: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas > 0){
									$query2 = "UPDATE inventario SET Cantidad = Cantidad + '$producto[Cantidad]' WHERE FK_Producto = '$producto[ID_Producto]' AND FK_Presentacion = '$producto[ID_Presentacion]' AND FK_Sucursal = '$destino'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 7: ".mysqli_error($omodelo->link);
									}
								}else{
									$query2 = "INSERT INTO inventario SET Cantidad = '$producto[Cantidad]', FK_Producto = '$producto[ID_Producto]', FK_Presentacion = '$producto[ID_Presentacion]', FK_Sucursal = '$destino'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 8: ".mysqli_error($omodelo->link);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == 'agregarMerma'){
			$id = $omodelo->link->real_escape_string($id);
			$fechaMerma = $omodelo->link->real_escape_string($fechaMerma);
			$cantidadMerma = $omodelo->link->real_escape_string($cantidadMerma);
			$motivoMerma = $omodelo->link->real_escape_string(trim($motivoMerma));

			$query = "UPDATE merma SET Cantidad = '$cantidadMerma', Fecha_Merma = '$fechaMerma', Motivo = '$motivoMerma', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' WHERE ID_Merma = '$id'";
			$error = $omodelo->_insertar($query);
			
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

				$status = 1;
				if ($_FILES['FotoMerma']['size'] > 0 && $_FILES['FotoMerma']['error'] == 0) {
					$file = $_FILES["FotoMerma"];
					$nombreDoc = $file["name"];
					$tipo = $file["type"];
					$ruta_provisional = $file["tmp_name"];
					$size = $file["size"];
					$carpeta = "vistas/assets/archivos/fotosMerma/";

					if ($tipo != 'image/jpeg' && $tipo != 'image/JPEG' && $tipo != 'image/jpg' && $tipo != 'image/JPG' && $tipo != 'image/png' && $tipo != 'image/PNG' && $tipo != 'application/pdf' && $tipo != 'application/PDF' && $tipo != ''){
						echo "Error 2 Formato";
					}else if ($size > (1024*1024*10)){
						echo "Error 3 Peso";
					}else{
						$status = 0;
						$ruta = $carpeta;
					}
				}
				
				//eliminar foto anterior
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
				if($status == 0){
					$query2 = "SELECT Foto FROM merma WHERE ID_Merma = '$id'";
					$row = $omodelo->_consultar($query2);
					$numerofilas = $omodelo->numerofilas;

					if($row == 'si'){
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"])){
					    		unlink("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"]);
							}

							$query3 = "UPDATE merma SET Foto = '".$id.'_'.$nombreDoc."' WHERE ID_Merma = '$id'";
							$error4 = $omodelo->_insertar($query3);	

							if ($error4 == "si") {
								echo "Error 4: ".mysqli_error($omodelo->link); 
							}else{
								move_uploaded_file($ruta_provisional,  $ruta.''.$id.'_'.$nombreDoc);
							}
						}
					}
				}
			}
		}else if($tipo == 'conversion'){
			$id = $omodelo->link->real_escape_string($id);
			$presentacion = $omodelo->link->real_escape_string($presentacion);
			$sucursal = $omodelo->link->real_escape_string($sucursal);
			$cantidad = $omodelo->link->real_escape_string($cantidad);

			$query = "UPDATE conversiones SET Cantidad_Origen = '$cantidad', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto~".$id;
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
				
				$conversiones = json_decode($conversiones, true);
				foreach ($conversiones as $conversion) {
					$conversion['ID_Presentacion'] = $omodelo->link->real_escape_string($conversion['ID_Presentacion']);
					$conversion['Cantidad'] = $omodelo->link->real_escape_string($conversion['Cantidad']);
					$conversion['Modificada'] = $omodelo->link->real_escape_string($conversion['Modificada']);

					$query = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '$producto' AND FK_Presentacion = '$conversion[ID_Presentacion]' AND FK_Sucursal = '$sucursal'";
					$row = $omodelo->_consultar($query);
					$numerofilas = $omodelo->numerofilas;

					if($row == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							$query2 = "UPDATE inventario SET Cantidad = Cantidad - IFNULL((SELECT Cantidad FROM detalles_conversion WHERE FK_Conversion = '$id' AND FK_Presentacion = '$conversion[ID_Presentacion]'), 0) + '$conversion[Cantidad]' WHERE FK_Producto = '$producto' AND FK_Presentacion = '$conversion[ID_Presentacion]' AND FK_Sucursal = '$sucursal'";
							$error2 = $omodelo->_insertar($query2);

							if($error2 == 'si'){
								echo "Error 3: ".mysqli_error($omodelo->link);
							}
						}else{
							$query2 = "INSERT INTO inventario SET Cantidad = '$conversion[Cantidad]', FK_Producto = '$producto', FK_Presentacion = '$conversion[ID_Presentacion]', FK_Sucursal = '$sucursal'";
							$error2 = $omodelo->_insertar($query2);

							if($error2 == 'si'){
								echo "Error 4: ".mysqli_error($omodelo->link);
							}
						}
					}

					if($conversion['Modificada'] == 'true'){
						$query1 = "UPDATE detalles_conversion SET Cantidad = '$conversion[Cantidad]' WHERE FK_Conversion = '$id' AND FK_Presentacion = '$conversion[ID_Presentacion]'";
						$error1 = $omodelo->_insertar($query1);

						if($error1 == 'si'){
							echo "Error 5: ".mysqli_error($omodelo->link);
						}
					}else{
						$query1 = "INSERT INTO detalles_conversion SET FK_Conversion = '$id', FK_Presentacion = '$conversion[ID_Presentacion]', Cantidad = '$conversion[Cantidad]'";
						$error1 = $omodelo->_insertar($query1);

						if($error1 == 'si'){
							echo "Error 6: ".mysqli_error($omodelo->link);
						}
					}
				}
			}
		}else if($tipo == 'completarTraslado'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "UPDATE traslados SET Estatus = 'Completado' WHERE ID_Traslado = '$id'";
			$error = $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{			
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

				$query1 = "SELECT ID_Detalle_Traslado, FK_Sucursal_Origen, FK_Sucursal_Destino, FK_Producto, FK_Presentacion, Cantidad FROM detalles_traslados INNER JOIN traslados ON FK_Traslado = ID_Traslado WHERE FK_Traslado = '$id'";
				$row = $omodelo->_consultar($query1);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error 2: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						for ($i=0; $i < $numerofilas; $i++) { 
							$query = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Origen']."'";
							$row1 = $omodelo->_consultar($query);
							$numerofilas1 = $omodelo->numerofilas;

							if($row1 == 'si'){
								echo "Error 3: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas1 > 0){
									$query2 = "UPDATE inventario SET Cantidad = Cantidad - '".$row[$i]['Cantidad']."' WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Origen']."'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 4: ".mysqli_error($omodelo->link);
									}
								}else{
									$query2 = "INSERT INTO inventario SET Cantidad = '-".$row[$i]['Cantidad']."', FK_Producto = '".$row[$i]['FK_Producto']."', FK_Presentacion = '".$row[$i]['FK_Presentacion']."', FK_Sucursal = '".$row[$i]['FK_Sucursal_Origen']."'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 5: ".mysqli_error($omodelo->link);
									}
								}
							}

							$query = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Destino']."'";
							$row1 = $omodelo->_consultar($query);
							$numerofilas1 = $omodelo->numerofilas;

							if($row1 == 'si'){
								echo "Error 6: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas1 > 0){
									$query2 = "UPDATE inventario SET Cantidad = Cantidad + '".$row[$i]['Cantidad']."' WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Destino']."'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 7: ".mysqli_error($omodelo->link);
									}
								}else{
									$query2 = "INSERT INTO inventario SET Cantidad = '".$row[$i]['Cantidad']."', FK_Producto = '".$row[$i]['FK_Producto']."', FK_Presentacion = '".$row[$i]['FK_Presentacion']."', FK_Sucursal = '".$row[$i]['FK_Sucursal_Destino']."'";
									$error2 = $omodelo->_insertar($query2);

									if($error2 == 'si'){
										echo "Error 8: ".mysqli_error($omodelo->link);
									}
								}
							}
						}
					}
				}
			}
		}else if($tipo == 'cancelarTraslado'){
			$id = $omodelo->link->real_escape_string($id);
			$motivo = $omodelo->link->real_escape_string($motivo);
			$regresar = $omodelo->link->real_escape_string($regresar);

			$query = "UPDATE traslados SET Estatus = 'Cancelado', Detalles = '$motivo' WHERE ID_Traslado = '$id'";
			$error = $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{			
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

				if($regresar == 'Si'){
					$query1 = "SELECT ID_Detalle_Traslado, FK_Sucursal_Origen, FK_Sucursal_Destino, FK_Producto, FK_Presentacion, Cantidad FROM detalles_traslados INNER JOIN traslados ON FK_Traslado = ID_Traslado WHERE FK_Traslado = '$id'";
					$row = $omodelo->_consultar($query1);
					$numerofilas = $omodelo->numerofilas;

					if($row == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							for ($i=0; $i < $numerofilas; $i++) { 
								$query2 = "UPDATE inventario SET Cantidad = Cantidad + '".$row[$i]['Cantidad']."' WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Origen']."'";
								$error2 = $omodelo->_insertar($query2);

								if($error2 == 'si'){
									echo "Error 3: ".mysqli_error($omodelo->link);
								}

								$query2 = "UPDATE inventario SET Cantidad = Cantidad - '".$row[$i]['Cantidad']."' WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Destino']."'";
								$error2 = $omodelo->_insertar($query2);

								if($error2 == 'si'){
									echo "Error 4: ".mysqli_error($omodelo->link);
								}
							}
						}
					}
				}
			}
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == 'merma'){
			$inventario = $omodelo->link->real_escape_string($inventario);

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
					$busqueda .= "CONCAT(Cantidad, Costo, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r'), Motivo) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
		
			$query = "SELECT ID_Merma, Cantidad, Costo, Fecha_Registro AS Fecha, Fecha_Merma, DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r') AS FechaMerma, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro,  Motivo, Foto, (SELECT COUNT(*) FROM merma WHERE FK_Inventario = '$inventario' $busqueda) AS 'Num' FROM merma WHERE FK_Inventario = '$inventario' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
	
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = '<a href="vistas/assets/archivos/defaultImagen.jpg" data-fancybox="images">
							<div style="background-image: url('."'".'vistas/assets/archivos/defaultImagen.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
							</div>
						</a><br>';
						
						if ($row[$i]["Foto"] != "") {
							if($row[$i]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosMerma/".$row[$i]["Foto"])){
								$foto = '<a href="vistas/assets/archivos/fotosMerma/'.$row[$i]["Foto"].'" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosMerma/'.$row[$i]["Foto"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
							}	
						}

						$botonModificar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][4] == '1') {
							$botonModificar = '<button class="btn btn-warning btn-sm mb-1 ModificarMerma" attrID="'.$row[$i]['ID_Merma'].'"><i class="fas fa-edit"></i></button>';
						}

						$botonEliminar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][5] == '1') {
							$botonEliminar = '<button class="btn btn-danger btn-sm mb-1 EliminarMerma" attrID="'.$row[$i]['ID_Merma'].'"><i class="fas fa-trash"></i></button>';
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Merma'],
							'Fecha' => $row[$i]['Fecha_Registro'],
							'Fecha_Merma' => $row[$i]['FechaMerma'],
							'Costo' => '<span class="dinero">'.$row[$i]['Costo'].'</span>',
							'Cantidad' => '<span class="cantidad">'.$row[$i]['Cantidad'].'</span>',
							'Total' => '<span class="dinero">'.($row[$i]['Costo'] * $row[$i]['Cantidad']).'</span>',
							'Motivo' => $row[$i]['Motivo'],
							'Imagen' => $foto,
							'Acciones' => $botonModificar.' '.$botonEliminar
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);
		}else if($tipo == 'consultarMerma'){
			$IDMerma = $omodelo->link->real_escape_string($IDMerma);
			$array = array();
				
			$query = "SELECT ID_Merma, FK_Inventario, Costo, Cantidad, Fecha_Merma, Fecha_Registro, Motivo, Foto, FK_Usuario FROM merma WHERE ID_Merma = '$IDMerma'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$array = array('ID_Merma' => $row[0]['ID_Merma'], 'FK_Inventario' => $row[0]['FK_Inventario'], 'Costo' => $row[0]['Costo'], 'Cantidad' => $row[0]['Cantidad'], 'Fecha_Merma' => $row[0]['Fecha_Merma'], 'Fecha_Registro' => $row[0]['Fecha_Registro'], 'Motivo' => $row[0]['Motivo'], 'Foto' => $row[0]['Foto'], 'FK_Usuario' => $row[0]['FK_Usuario']);	
				}
			}

			echo json_encode($array);
		}else if($tipo == 'consultarPresentaciones'){
			$producto = $omodelo->link->real_escape_string($producto);
			$presentacion = $omodelo->link->real_escape_string($presentacion);
			$tabla = '';

			$query = "SELECT ID_Presentacion, Nombre, Abreviatura, Nombre_Unidad, Abreviatura_Unidad FROM presentaciones INNER JOIN productos ON ID_Producto = FK_Producto WHERE FK_Producto = '$producto' AND ID_Presentacion != '$presentacion'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					if($presentacion != 0){
						if(trim($row[0]['Nombre_Unidad']) != ''){ 
							if(trim($row[0]['Abreviatura_Unidad']) != ''){
								$tabla = '<tr attrID="0">
									<td>'.trim($row[0]['Nombre_Unidad']).'('.trim($row[0]['Abreviatura_Unidad']).')</td>
									<td><input type="number" class="form-control" step="any" value="0"></td>
								</tr>';
							}else{
								$tabla = '<tr attrID="0">
									<td>'.trim($row[0]['Nombre_Unidad']).'</td>
									<td><input type="number" class="form-control" step="any" value="0"></td>
								</tr>';
							}
						}else{
							$tabla = '<tr attrID="0">
								<td>Sin presentación</td>
								<td><input type="number" class="form-control" step="any" value="0"></td>
							</tr>';
						}
					}

					for($i=0; $i < $numerofilas; $i++) {
						if(trim($row[$i]['Abreviatura']) != ''){
							$tabla .= '<tr attrID="'.$row[$i]['ID_Presentacion'].'">
								<td>'.trim($row[$i]['Nombre']).'('.trim($row[$i]['Abreviatura']).')</td>
								<td><input type="number" class="form-control" step="any" value="0"></td>
							</tr>';
						}else{
							$tabla .= '<tr attrID="'.$row[$i]['ID_Presentacion'].'">
								<td>'.trim($row[$i]['Nombre']).'</td>
								<td><input type="number" class="form-control" step="any" value="0"></td>
							</tr>';
						}
					} 	
				}
			}

			echo $tabla;
		}else if($tipo == 'ConversionesProducto'){
			$producto = $omodelo->link->real_escape_string($producto);
			$presentacion = $omodelo->link->real_escape_string($presentacion);
			$sucursal = $omodelo->link->real_escape_string($sucursal);

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
					$busqueda .= "CONCAT(Cantidad_Origen, DATE_FORMAT(conversiones.Fecha_Registro, '%d-%m-%Y %r')) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
		
			$query = "SELECT ID_Conversion, FK_Producto, FK_Presentacion_Origen, FK_Sucursal, Cantidad_Origen, conversiones.Fecha_Registro AS Fecha, FK_Usuario, DATE_FORMAT(conversiones.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Nombre_Unidad, Abreviatura_Unidad, (SELECT COUNT(*) FROM conversiones WHERE FK_Producto = '$producto' AND FK_Presentacion_Origen = '$presentacion' AND FK_Sucursal = '$sucursal' $busqueda) AS 'Num' FROM conversiones INNER JOIN productos ON FK_Producto = ID_Producto WHERE FK_Producto = '$producto' AND FK_Presentacion_Origen = '$presentacion' AND FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
	
			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$conversion = '';
						$query1 = "SELECT ID_Detalle_Conversion, FK_Presentacion, Cantidad, Nombre, Abreviatura FROM detalles_conversion LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Conversion = '".$row[$i]['ID_Conversion']."'";
						$row1 = $omodelo->_consultar($query1);
						$numerofilas1 = $omodelo->numerofilas;
				
						if($row1 == 'si'){
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas1 > 0){
								for($x=0; $x<$numerofilas1; $x++){
									$presentacion = '';
									if($row1[$x]['FK_Presentacion'] == 0){
										$presentacion = $row[$i]['Nombre_Unidad'];
										if(trim($row[$i]['Abreviatura_Unidad']) != ''){
											$presentacion .= '('.$row[$i]['Abreviatura_Unidad'].')';
										}
									}else{
										$presentacion = $row1[$x]['Nombre'];
										if(trim($row1[$x]['Abreviatura']) != ''){
											$presentacion .= '('.$row1[$x]['Abreviatura'].')';
										}
									}

									$conversion .= '<p attrID="'.$row1[$x]['FK_Presentacion'].'" style="margin: 1px 0px;">'.$presentacion.': <span class="cantidad">'.$row1[$x]['Cantidad'].'<span></p>';
								}
							}
						}

						$botonModificar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][8] == '1') {
							$botonModificar = '<button class="btn btn-warning btn-sm mb-1 bModificarConversion" attrID="'.$row[$i]['ID_Conversion'].'" attrProducto="'.$row[$i]['FK_Producto'].'" attrPresentacion="'.$row[$i]['FK_Presentacion_Origen'].'" attrSucursal="'.$row[$i]['FK_Sucursal'].'" title="Modificar"><i class="fas fa-edit"></i></button>';
						}

						$botonEliminar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][9] == '1') {
							$botonEliminar = '<button class="btn btn-danger btn-sm mb-1 bEliminarConversion" attrID="'.$row[$i]['ID_Conversion'].'" title="Eliminar"><i class="fas fa-trash"></i></button>';
						}
						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Conversion'],
							'Fecha' => $row[$i]['Fecha_Registro'],
							'Cantidad' => '<span class="cantidad">'.$row[$i]['Cantidad_Origen'].'</span>',
							'Conversion' => $conversion,
							'Acciones' => $botonModificar.' '.$botonEliminar.' <button class="btn btn-info btn-sm bImpririConversion" attrID="'.$row[$i]['ID_Conversion'].'" title="Imprimir ticket"><i class="fas fa-file"></i></button>'
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "TablaTraslados"){
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
					$busqueda .= "CONCAT(Estatus, Detalles, DATE_FORMAT(Fecha_Traslado, '%d-%m-%Y'), DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Origen), (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Destino)) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
		
			$query = "SELECT ID_Traslado, Estatus, Detalles, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Origen) AS Origen, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Destino) AS Destino, Fecha_Traslado AS FechaTraslado, DATE_FORMAT(Fecha_Traslado, '%d-%m-%Y') AS Fecha_Traslado, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, FK_Usuario, (SELECT COUNT(*) FROM traslados $busqueda) AS 'Num' FROM traslados $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
	
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$botonCompletar = '';
						if ($row[$i]['Estatus'] == 'Pendiente' && ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][12] == '1')) {
							$botonCompletar = '<button type="button" class="btn btn-sm btn-success bCompletarTraslado" attrID="'.$row[$i]['ID_Traslado'].'" title="Completar traslado"><i class="fas fa-check"></i></buttton>';
						}

						$botonCancelar = '';
						if ($row[$i]['Estatus'] == 'Completado' && ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][13] == '1')) {
							$botonCancelar = '<button type="button" class="btn btn-sm btn-warning bCancelarTraslado" attrID="'.$row[$i]['ID_Traslado'].'" title="Cancelar traslado"><i class="fas fa-ban"></i></buttton>';
						}	

						$botonEliminar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][14] == '1') {
							$botonEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarTraslado" attrID="'.$row[$i]['ID_Traslado'].'" title="Eliminar traslado"><i class="fas fa-trash"></i></button>';
						}

						$estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
						if($row[$i]['Estatus'] == 'Completado'){
							$estatus = '<span class="badge rounded-pill bg-success">Completado</span>';
						}else if($row[$i]['Estatus'] == 'Cancelado'){
							$estatus = '<span class="badge rounded-pill bg-danger">Cancelado</span>';
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Traslado'],
							'Fecha' => $row[$i]['Fecha_Registro'],
							'FechaTraslado' => $row[$i]['Fecha_Traslado'],
							'Origen' => $row[$i]['Origen'],
							'Destino' => $row[$i]['Destino'],
							'Estatus' => $estatus,
							'Detalles' => $row[$i]['Detalles'].'<br><button type="button" class="btn btn-link btn-sm bDetalleTraslado" attrID="'.$row[$i]['ID_Traslado'].'" title="Detalles traslado">Ver detalles <i class="fas fa-eye"></i></button>',
							'Acciones' => $botonCompletar.' '.$botonCancelar.' '.$botonEliminar.' <button type="button" class="btn btn-sm btn-info bImprimirTraslado" attrID="'.$row[$i]['ID_Traslado'].'" title="Imprimir traslado"><i class="fas fa-print"></i></button>'
						);			
					}
		
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);
		}else if ($tipo == 'ConsultarProductos'){
			$sucursal =  $omodelo->link->real_escape_string($sucursal);

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
					$busqueda .= "CONCAT(ID_Producto, Descripcion, Codigo, Nombre_Unidad, Abreviatura_Unidad) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}	  

			$query = "SELECT ID_Producto, Codigo, Descripcion, Nombre_Unidad AS NombrePresentacion, Abreviatura_Unidad AS Abreviatura, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Presentacion = 0 AND FK_Producto = ID_Producto AND FK_Sucursal = '$sucursal'), 0) AS Existencia, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
					
						$presentacion = '';
						if($row[$i]['NombrePresentacion'] != '' || $row[$i]['Abreviatura'] != ''){
							$presentacion .= '<button style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresTras" presentacion="0">Ex. <span>'.number_format($row[$i]['Existencia'], 2).'</span> - <span>'.$row[$i]['NombrePresentacion'].'('.$row[$i]['Abreviatura'].')</span></button>';
						}else{
							$presentacion .= '<button style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresTras" presentacion="0">Ex. <span>'.number_format($row[$i]['Existencia'], 2).'</span> - <span>Sin presentación</span></button>';
						}

						$query1 = "SELECT ID_Presentacion, Nombre, Abreviatura, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Presentacion = ID_Presentacion AND FK_Producto = '".$row[$i]['ID_Producto']."' AND FK_Sucursal = '$sucursal'), 0) AS Existencia FROM presentaciones WHERE FK_Producto = '".$row[$i]['ID_Producto']."' ORDER BY Nombre";
						$row1 = $omodelo->_consultar($query1);
						$numerofilas1 = $omodelo->numerofilas;

						if($row1 == 'si'){
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas1 > 0){
								for($x=0; $x < $numerofilas1; $x++){
									$presentacion .= '<button style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresTras" presentacion="'.$row1[$x]['ID_Presentacion'].'">Ex. <span>'.number_format($row1[$x]['Existencia'], 2).'</span> - <span>'.$row1[$x]['Nombre'].'('.$row1[$x]['Abreviatura'].')</span></button>';
								}
							}
						}
						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Codigo' => "<b class='codigo'>".$row[$i]['Codigo']."</b>",
							'Descripcion' => "<b class='NombreProducto'>".$row[$i]['Descripcion']."</b>",
							'Presentacion' => $presentacion
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);	
		}else if($tipo == 'productosTraslado'){
			$id = $omodelo->link->real_escape_string($id);
			$tabla = '';

			$query = "SELECT ID_Detalle_Traslado, Codigo, Descripcion, Nombre_Unidad, Abreviatura_Unidad, FK_Presentacion, Cantidad, Nombre, Abreviatura FROM detalles_traslados INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Traslado = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$presentacion = 'Sin presentación';
						if($row[$i]['FK_Presentacion'] == 0){
							if(trim($row[$i]['Nombre_Unidad']) != ''){
								$presentacion = $row[$i]['Nombre_Unidad'];
							}

							if(trim($row[$i]['Abreviatura_Unidad']) != ''){
								$presentacion .= '('.$row[$i]['Abreviatura_Unidad'].')';
							}
						}else{
							if(trim($row[$i]['Nombre']) != ''){
								$presentacion = $row[$i]['Nombre'];
							}

							if(trim($row[$i]['Abreviatura']) != ''){
								$presentacion .= '('.$row[$i]['Abreviatura'].')';
							}
						}

						$tabla .= '<tr>
							<td>'.$row[$i]['Codigo'].'</td>
							<td>'.$row[$i]['Descripcion'].'</td>
							<td>'.$presentacion.'</td>
							<td><span class="cantidad">'.$row[$i]['Cantidad'].'</span></td>
						</tr>';
					}
				}
			}

			echo $tabla;
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);

		if ($tipo == "EliminarMerma") {
			$IDMerma = $omodelo->link->real_escape_string($IDMerma);
			$RegresarInventario = $omodelo->link->real_escape_string($RegresarInventario);

			$queryInventario = "SELECT FK_Inventario, Cantidad, Foto FROM merma WHERE ID_Merma = '$IDMerma'";
			$row = $omodelo->_consultar($queryInventario);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"])){
					    unlink("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"]);
					}

				 	if ($RegresarInventario == "Si") {
						$query1 = "UPDATE inventario SET Cantidad = Cantidad + ".$row[0]['Cantidad']." WHERE ID_Inventario = '".$row[0]["FK_Inventario"]."'";
						$error1 = $omodelo->_insertar($query1);

						if ($error1 == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}
					}
				}
			}

			$query = "DELETE FROM merma WHERE ID_Merma = '$IDMerma'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if ($tipo == "EliminarConversion") {
			$id = $omodelo->link->real_escape_string($id);
			$regresar = $omodelo->link->real_escape_string($regresar);

			if ($regresar == "Si") {
				$queryInventario = "SELECT ID_Conversion, FK_Producto, FK_Sucursal, FK_Presentacion_Origen, Cantidad_Origen, Fecha_Registro, FK_Usuario FROM conversiones WHERE ID_Conversion = '$id'";
				$row = $omodelo->_consultar($queryInventario);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error 1: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){ 	
						$query1 = "UPDATE inventario SET Cantidad = Cantidad + ".$row[0]['Cantidad_Origen']." WHERE FK_Producto = '".$row[0]['FK_Producto']."' AND FK_Sucursal = '".$row[0]['FK_Sucursal']."' AND FK_Presentacion = '".$row[0]['FK_Presentacion_Origen']."'";
						$error1 = $omodelo->_insertar($query1);

						if ($error1 == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}

						$query2 = "SELECT ID_Detalle_Conversion, FK_Conversion, FK_Presentacion, Cantidad FROM detalles_conversion WHERE FK_Conversion = '$id'";
						$row1 = $omodelo->_consultar($query2);
						$numerofilas1 = $omodelo->numerofilas;

						if($row1 == 'si'){
							echo "Error 3: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas1 > 0){ 
								for ($i=0; $i < $numerofilas1; $i++) { 
									$query3 = "UPDATE inventario SET Cantidad = Cantidad - ".$row1[$i]['Cantidad']." WHERE FK_Producto = '".$row[0]['FK_Producto']."' AND FK_Sucursal = '".$row[0]['FK_Sucursal']."' AND FK_Presentacion = '".$row1[$i]['FK_Presentacion']."'";
									$error2 = $omodelo->_insertar($query3);

									if ($error2 == "si") {
										echo "Error 4: ".mysqli_error($omodelo->link);
									}
								}
							}
						}
					}	
				}
			}

			$query = "DELETE FROM conversiones WHERE ID_Conversion = '$id'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 5: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if($tipo == 'eliminarTraslado'){
			$id = $omodelo->link->real_escape_string($id);
			$regresar = $omodelo->link->real_escape_string($regresar);

			if($regresar == 'Si'){
				$query1 = "SELECT ID_Detalle_Traslado, FK_Sucursal_Origen, FK_Sucursal_Destino, FK_Producto, FK_Presentacion, Cantidad FROM detalles_traslados INNER JOIN traslados ON FK_Traslado = ID_Traslado WHERE FK_Traslado = '$id'";
				$row = $omodelo->_consultar($query1);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error 2: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						for ($i=0; $i < $numerofilas; $i++) { 
							$query2 = "UPDATE inventario SET Cantidad = Cantidad + '".$row[$i]['Cantidad']."' WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Origen']."'";
							$error2 = $omodelo->_insertar($query2);

							if($error2 == 'si'){
								echo "Error 3: ".mysqli_error($omodelo->link);
							}

							$query2 = "UPDATE inventario SET Cantidad = Cantidad - '".$row[$i]['Cantidad']."' WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal_Destino']."'";
							$error2 = $omodelo->_insertar($query2);

							if($error2 == 'si'){
								echo "Error 4: ".mysqli_error($omodelo->link);
							}
						}
					}
				}
			}

			$query = "DELETE FROM traslados WHERE ID_Traslado = '$id'";
			$error = $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{			
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
	}
}
