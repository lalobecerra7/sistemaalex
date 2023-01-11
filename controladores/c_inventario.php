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
				$busqueda .= "CONCAT(Codigo, Descripcion, Nombre_Unidad, Abreviatura_Unidad, presentaciones.Nombre, Abreviatura, inventario.Cantidad, sucursales.Nombre) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$where = '';
		if ($omodelo->permisos() != 'Administrador' && $_SESSION['user_admin']['FK_Sucursal'] != '0') {
			$where = "WHERE inventario.FK_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal'];
		}

		$query =  "SELECT ID_Inventario, inventario.FK_Producto AS FK_Producto, Imagen, Codigo, Descripcion, Precio, Nombre_Unidad, Abreviatura_Unidad, inventario.FK_Presentacion AS FK_Presentacion, presentaciones.Nombre AS Presentacion, Abreviatura, inventario.Cantidad AS Existencia, inventario.FK_Sucursal AS FK_Sucursal, sucursales.Nombre AS Sucursal, IFNULL(merma.Costo, 0) AS Costo, IFNULL(merma.Cantidad, 0) AS Merma, (SELECT COUNT(*) FROM inventario INNER JOIN productos ON inventario.FK_Producto = ID_Producto INNER JOIN sucursales ON inventario.FK_Sucursal = ID_Sucursal $where $busqueda) AS 'Num' FROM inventario INNER JOIN productos ON inventario.FK_Producto = ID_Producto INNER JOIN sucursales ON inventario.FK_Sucursal = ID_Sucursal LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN merma ON inventario.FK_Producto = merma.FK_Producto AND inventario.FK_Presentacion = merma.FK_Presentacion AND inventario.FK_Sucursal = merma.FK_Sucursal $where $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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
					
					$botonPermisosVerMerma = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][2] == '1') {
						$botonPermisosVerMerma = '<br><button type="button" class="btn btn-link btn-sm verDetallesMerma" attrProducto="'.$row[$i]['FK_Producto'].'" attrPresentacion="'.$row[$i]['FK_Presentacion'].'" attrSucursal="'.$row[$i]['FK_Sucursal'].'" title="Detalles Merma">Ver detalles <i class="fas fa-eye"></i></button>';
					}

					$botonPermisosAgregarMerma = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][3] == '1') {
						$botonPermisosAgregarMerma = '<button class="btn btn-warning btn-sm mb-1 AgregarMerma" attrProducto="'.$row[$i]['FK_Producto'].'" attrPresentacion="'.$row[$i]['FK_Presentacion'].'" attrSucursal="'.$row[$i]['FK_Sucursal'].'" title="Registrar merma"><i class="fas fa-level-down"></i></button>';
					}

					$botonConversion = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][7] == '1') {
						$botonConversion = '<button class="btn btn-success btn-sm mb-1 ConvertirProducto" attrProducto="'.$row[$i]['FK_Producto'].'" attrPresentacion="'.$row[$i]['FK_Presentacion'].'" attrSucursal="'.$row[$i]['FK_Sucursal'].'" title="Convertir producto"><i class="fa-solid fa-boxes-stacked"></i></button>';
					}

					$presentacion = 'Sin presentación';
					$abreviatura = '';
					if($row[$i]['FK_Presentacion'] != '0'){
						if($row[$i]['Abreviatura'] != ""){
							$abreviatura = '('.$row[$i]['Abreviatura'].')';
						}
						$presentacion = $row[$i]['Presentacion'].$abreviatura;
					}else{
						$precios = 'General: <b class="dinero">'.$row[$i]['Precio'].'</b><br>'.$precios;
						if($row[$i]['Nombre_Unidad'] != ""){
							if($row[$i]['Abreviatura_Unidad'] != ""){
								$abreviatura = '('.$row[$i]['Abreviatura_Unidad'].')';
							}
							$presentacion = $row[$i]['Nombre_Unidad'].$abreviatura;
						}
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Inventario'],
						'Descripcion' => $foto.$row[$i]['Descripcion'].'<br>'.$presentacion.'<br>Codigo: <b>'.$row[$i]['Codigo'].'</b>',
						'Existencia' => 'Total: <b>'.$row[$i]['Existencia'].'</b><br>Sucursal: <b>'.$row[$i]['Sucursal'].'</b>',
						'Precios' => $precios,
						'Merma' => 'Cantidad: <b class="cantidad">'.$row[$i]["Merma"].'</b><br>Total: <b class="dinero">$'.number_format(($row[$i]['Merma'] * $row[$i]['Costo']), 2).'</b><br>'.$botonPermisosVerMerma,
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

		if($tipo == 'agregarMerma'){
			$fecha = date('Y-m-d H:i:s'); 
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$SucursalMerma = $omodelo->link->real_escape_string($SucursalMerma);
			$CantidadMerma = $omodelo->link->real_escape_string($CantidadMerma);
			$MotivoMerma = $omodelo->link->real_escape_string($MotivoMerma);
			$PresentacionProductoMerma = $omodelo->link->real_escape_string($PresentacionProductoMerma);
			$FechaMerma = $omodelo->link->real_escape_string($FechaMerma);
			$Usuario = $_SESSION['user_admin']['ID_Usuario'];
			$existencia = 0;
			$costo = 0;

			$query2 = "SELECT IFNULL(Costo, 0), IFNULL(Cantidad, 0) FROM productos LEFT JOIN inventario ON FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalMerma' AND FK_Presentacion = '$PresentacionProductoMerma' WHERE ID_Producto = '$IDProducto'";

			$row = $omodelo->_consultar($query2);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$existencia = $row[0]["Cantidad"];
					$costo = $row[0]["Costo"];
				}

				if($existencia < $CantidadMerma){
					echo "ErrorCantidad";
				}else{
					$existenciaM = ($existencia-$CantidadMerma);
				
					$query = "INSERT INTO merma SET FK_Sucursal = '$SucursalMerma', FK_Producto = '$IDProducto', FK_Presentacion = '$PresentacionProductoMerma', Cantidad = '$CantidadMerma', Motivo = '$MotivoMerma', Fecha_Registro = '$fecha', Fecha_Merma = '$FechaMerma', FK_Usuario = '$Usuario', Costo = '$costo'";
					$error = $omodelo->_insertar($query);
			
					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$IDMerma = mysqli_insert_id($omodelo->link);
						$query3 = "UPDATE inventario SET Cantidad = '$existenciaM' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalMerma' AND FK_Presentacion = '$PresentacionProductoMerma'";
						$error3 = $omodelo->_insertar($query3);
					
						if ($error3 == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
							echo "Correcto";

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
					}
				}
			}
		} else if($tipo == 'traslados'){
			$fecha = date('Y-m-d H:i:s'); 
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$FechaTraslado = $omodelo->link->real_escape_string($FechaTraslado);
			$SucursalOrigen = $omodelo->link->real_escape_string($SucursalOrigen);
			$Presentacion = $omodelo->link->real_escape_string($Presentacion);
			$SucursalDestino = $omodelo->link->real_escape_string($SucursalDestino);
			$Cantidad = $omodelo->link->real_escape_string($Cantidad);

			$querycomprobar = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalOrigen' AND FK_Presentacion = '$Presentacion'";
			$rowcomprobar = $omodelo->_consultar($querycomprobar);
			$numerofilascomprobar = $omodelo->numerofilas;
			if ($numerofilascomprobar > 0) {
				if ($rowcomprobar[0]["Cantidad"] >= $Cantidad) {
					$query = "INSERT INTO traslados SET Fecha_Registro = '$fecha', Fecha_Traslado = '$FechaTraslado', FK_Producto = '$IDProducto', FK_Presentacion = '$Presentacion',  FK_Sucursal_Origen = '$SucursalOrigen', Cantidad = '$Cantidad', FK_Sucursal_Destino = '$SucursalDestino', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
					$error = $omodelo->_insertar($query);
			
					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$query2 = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalOrigen' AND FK_Presentacion = '$Presentacion'";
						$row = $omodelo->_consultar($query2);
						$numerofilas = $omodelo->numerofilas;

						if($row == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas > 0){
								for($i=0; $i<$numerofilas; $i++){
									$cantidadPrevia = $row[$i]["Cantidad"];
								}
							}
							$cantidadNueva = ($cantidadPrevia-$Cantidad);

							$query3 = "UPDATE inventario SET Cantidad = '$cantidadNueva' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalOrigen' AND FK_Presentacion = '$Presentacion'";
							$error3 = $omodelo->_insertar($query3);
						
							if ($error3 == "si") {
								echo "Error 1: ".mysqli_error($omodelo->link);
							}else{
								$query4 = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalDestino' AND FK_Presentacion = '$Presentacion'";
								$row = $omodelo->_consultar($query4);
								$numerofilas = $omodelo->numerofilas;

								if($row == 'si'){
									echo "Error: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilas > 0){
										for($i=0; $i<$numerofilas; $i++){
											$cantidadPrevia = $row[$i]["Cantidad"];
										}
										$cantidadNueva = ($cantidadPrevia+$Cantidad);

										$query5 = "UPDATE inventario SET Cantidad = '$cantidadNueva' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalDestino' AND FK_Presentacion = '$Presentacion'";
										$error5 = $omodelo->_insertar($query5);
									
										if ($error5 == "si") {
											echo "Error 1: ".mysqli_error($omodelo->link);
										}else{
											//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
											echo "Correcto";
										}
									}else {
										$query6 = "INSERT INTO inventario SET Cantidad = '$Cantidad', FK_Producto = '$IDProducto', FK_Sucursal = '$SucursalDestino', FK_Presentacion = '$Presentacion'";
										$error6 = $omodelo->_insertar($query6);
									
										if ($error6 == "si") {
											echo "Error 1: ".mysqli_error($omodelo->link);
										}else{
											//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
											echo "Correcto";
										}
									}
									
								}
							}
						}
					}
				}else{
					echo "ErrorExistencia";
				}
			}
		}else if($tipo == "GuardarConversion"){
			$fecha = date('Y-m-d H:i:s'); 
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$SucursalOrigen = $omodelo->link->real_escape_string($SucursalOrigen);
			$Origen = $omodelo->link->real_escape_string($Origen);
			$CantidadOrigen = $omodelo->link->real_escape_string($CantidadOrigen);
			$SucursalDestino = $omodelo->link->real_escape_string($SucursalDestino);
			$Destino = $omodelo->link->real_escape_string($Destino);
			$CantidadDestino = $omodelo->link->real_escape_string($CantidadDestino);
			$CantidadTotalInventario = 0;
			$query4 = "SELECT SUM(Cantidad) AS Total FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalOrigen' AND FK_Presentacion = '$Origen'";
			$row = $omodelo->_consultar($query4);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$CantidadTotalInventario = $row[0]["Total"];
				}
			}

			if ($CantidadTotalInventario >= $CantidadOrigen) {
				$query = "INSERT INTO conversiones SET FK_Producto = '$IDProducto', FK_Sucursal_Origen = '$SucursalOrigen', FK_Sucursal_Destino = '$SucursalDestino', FK_Presentacion_Origen = '$Origen', Cantidad_Origen = '$CantidadOrigen', FK_Presentacion_Destino= '$Destino', Cantidad_Destino = '$CantidadDestino', Fecha_Registro = '$fecha', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
				$error = $omodelo->_insertar($query);
		
				if ($error == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
				 	echo "Correcto";

				 	$querydestino = "SELECT * FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalDestino' AND FK_Presentacion = '$Destino'";
					$rowdestino = $omodelo->_consultar($querydestino);
					$numerofilasdestino = $omodelo->numerofilas;

					if($rowdestino == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasdestino > 0){
							$query = "UPDATE inventario SET Cantidad = (Cantidad - '$CantidadOrigen') WHERE FK_Producto = '$IDProducto' AND FK_Presentacion = '$Origen' AND FK_Sucursal= '$SucursalOrigen';";
							$error = $omodelo->_insertar($query);
					
							if ($error == "si") {
								echo "Error 1: ".mysqli_error($omodelo->link);
							}

							$query2 = "UPDATE inventario SET Cantidad = (Cantidad + '$CantidadDestino') WHERE FK_Producto = '$IDProducto' AND FK_Presentacion = '$Destino' AND FK_Sucursal= '$SucursalDestino';";
							$error2 = $omodelo->_insertar($query2);
					
							if ($error2 == "si") {
								echo "Error 2: ".mysqli_error($omodelo->link);
							}
						}else{
							$query = "UPDATE inventario SET Cantidad = (Cantidad - '$CantidadOrigen') WHERE FK_Producto = '$IDProducto' AND FK_Presentacion = '$Origen' AND FK_Sucursal= '$SucursalOrigen';";
							$error = $omodelo->_insertar($query);
					
							if ($error == "si") {
								echo "Error 1: ".mysqli_error($omodelo->link);
							}

							$query2 = "INSERT INTO inventario SET Cantidad = '$CantidadDestino', FK_Producto = '$IDProducto', FK_Presentacion = '$Destino', FK_Sucursal= '$SucursalDestino';";
							$error2 = $omodelo->_insertar($query2);
					
							if ($error2 == "si") {
								echo "Error 2: ".mysqli_error($omodelo->link);
							}

						}
					}
				}
			}else{
				echo "ErrorExistencia";
			}
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);

		if ($tipo == 'merma'){
			$IDProducto = $omodelo->link->real_escape_string($id);
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
					$busqueda .= "CONCAT(ID_Producto, sucursales.Nombre, presentaciones.Nombre, merma.Cantidad, merma.Costo, DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r'), Motivo) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
		
			$query = "SELECT ID_Merma, merma.FK_Producto AS 'ID_Producto', sucursales.Nombre AS 'Sucursal', merma.Cantidad, IFNULL(presentaciones.Nombre, 'Sin presentacion') AS NombrePresentacion, (merma.Cantidad*merma.Costo) AS 'Costo', 
				DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r') AS Fecha_Merma, Motivo, (SELECT COUNT(*) FROM merma $busqueda) AS 'Num', Foto FROM `merma` INNER JOIN sucursales 
				ON sucursales.ID_Sucursal=merma.FK_Sucursal INNER JOIN productos 
				ON productos.ID_Producto=merma.FK_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE merma.FK_Producto='$IDProducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Fecha' => $row[$i]['Fecha_Merma'],
							'Motivo' => $row[$i]['Motivo'],
							'Sucursal' => $row[$i]['Sucursal'],
							'Presentacion' => $row[$i]["NombrePresentacion"],
							'Cantidad' => $row[$i]['Cantidad'],
							'Imagen' => $foto,
							'Acciones' => '<button class="btn btn-warning btn-sm mb-1" id="ModificarMerma" attrid="'.$row[$i]['ID_Merma'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm mb-1" id="EliminarMerma" attrid="'.$row[$i]['ID_Merma'].'"><i class="fas fa-trash"></i></button>' ,
						);
					}
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);
		} else if ($tipo == 'cantidadTraslado'){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);
			$cantidad = '';
			$query = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$IDSucursal'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					if($row[0]["Cantidad"] == null || $row[0]["Cantidad"] == ''){
						$cantidad = 0;
					}else {
						$cantidad = $row[0]["Cantidad"];
					}
					echo $cantidad;
				}	
			}
		}else if ($tipo == 'consultarMerma'){
			$IDMerma = $omodelo->link->real_escape_string($IDMerma);
				
			$query = "SELECT DATE_FORMAT(Fecha_Merma, '%Y-%m-%d') AS Fecha_Merma, Motivo FROM merma WHERE ID_Merma = '$IDMerma'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
					
			}
		}else if ($tipo == 'editarMerma'){
			$IDMerma = $omodelo->link->real_escape_string($IDMerma);
			$FechaMerma = $omodelo->link->real_escape_string($FechaMerma);
			$Motivo = $omodelo->link->real_escape_string($MotivoMerma);

			$query = "UPDATE merma SET Fecha_Merma = '$FechaMerma', Motivo = '$Motivo' WHERE ID_Merma = '$IDMerma'";
			$error = $omodelo->_insertar($query);
							
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
				echo "Correcto";
			}
		}else if ($tipo == 'sucursales'){
			$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);
			$opciones= '';
			$query = "SELECT ID_Sucursal, Nombre FROM sucursales WHERE ID_Sucursal != '$IDSucursal'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '">' . $row[$i]['Nombre'] . '</option>';
					}
				}
				echo $opciones;
			}
		}else if($tipo == "TablaTraslados"){
			$IDProducto = $omodelo->link->real_escape_string($id);
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
					$busqueda .= "CONCAT(ID_Traslado, FK_Producto, FK_Sucursal_Origen, FK_Sucursal_Destino, Cantidad, Fecha_Traslado, Fecha_Registro, FK_Usuario) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
		
			$query = "SELECT ID_Traslado, FK_Producto, FK_Sucursal_Origen AS Origen, FK_Sucursal_Destino AS Destino, Cantidad, Fecha_Traslado, Fecha_Registro, FK_Usuario, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Origen) AS NombreOrigen, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Destino) AS NombreDestino, usuarios.Nombre AS Usuario, (SELECT COUNT(DISTINCT(ID_Traslado)) FROM traslados WHERE FK_Producto = $IDProducto $busqueda) AS 'Num' FROM `traslados` INNER JOIN usuarios ON FK_Usuario = ID_Usuario WHERE FK_Producto = $IDProducto $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
	
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Traslado'],
							'Fecha' => $row[$i]['Fecha_Traslado'],
							'Origen' => $row[$i]['NombreOrigen'],
							'Destino' => $row[$i]['NombreDestino'],
							'Cantidad' => $row[$i]['Cantidad'],
							'Usuario' => $row[$i]['Usuario'],
						);			
					}
		
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);
		}else if($tipo == "TablaTrasladosPrincipal"){
			$fechainicio = $omodelo->link->real_escape_string($fechainicio);
			$fechafin = $omodelo->link->real_escape_string($fechafin);
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
					$busqueda .= "CONCAT(Fecha_Traslado) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			$query = "SELECT ID_Traslado, Fecha_Traslado AS Fecha, SUM(Cantidad) AS CantidadTraslado, (SELECT COUNT(*) FROM traslados WHERE Fecha_Traslado >= '$fechainicio' AND Fecha_Traslado <= '$fechafin' $busqueda) AS 'Num' FROM `traslados` WHERE Fecha_Traslado >= '$fechainicio' AND Fecha_Traslado <= '$fechafin'  $busqueda GROUP BY DATE_FORMAT(Fecha_Traslado, '%Y-%m-%d') ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
	
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Traslado'],
							'Fecha' => $row[$i]['Fecha'],
							'Detalles' => "Cantidad total de traslado: ".$row[$i]['CantidadTraslado'],
								'Acciones' => '',
						);			
					}
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);
		}else if($tipo == "ConsultarSucursalesConversion"){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$opciones = '<option value=""> Seleccione una opción </option>';
			$query = "SELECT ID_Sucursal, Nombre FROM inventario INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Producto = '$IDProducto' GROUP BY ID_Sucursal";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '">' . $row[$i]['Nombre'] . '</option>';
					}
				}
			}
			echo $opciones;
		}else if($tipo == "ConsultarPresentacionOrigen"){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);
			$query = "SELECT ID_Presentacion, Nombre, Abreviatura FROM inventario INNER JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE inventario.FK_Producto = '$IDProducto' AND inventario.FK_Sucursal = '$IDSucursal' GROUP BY ID_Presentacion";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = '<option value=""> Seleccione una opción </option>';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Presentacion'].'">'.$row[$i]['Nombre'].' ('.$row[$i]['Abreviatura'].')</option>';
					}
				}
			}
			echo $opciones;
		}else if($tipo == "ConsultarPresentacionDestino"){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);
			$IDPresentacion = $omodelo->link->real_escape_string($IDPresentacion);

			$query = "SELECT ID_Presentacion, Nombre, Abreviatura FROM presentaciones WHERE FK_Producto = '$IDProducto' AND ID_Presentacion != '$IDPresentacion' GROUP BY ID_Presentacion";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = '<option value=""> Seleccione una opción </option>';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Presentacion'].'">'.$row[$i]['Nombre'].' ('.$row[$i]['Abreviatura'].')</option>';
					}
				}
			}
			echo $opciones;
		}else if($tipo == "PresentacionesProducto"){
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$idSucursal = $omodelo->link->real_escape_string($idSucursal);
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
					$busqueda .= "CONCAT(presentaciones.Nombre, presentaciones.Abreviatura, Cantidad) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			$query = "SELECT ID_Inventario, inventario.FK_Producto, FK_Presentacion, Cantidad, FK_Sucursal, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura, (SELECT COUNT(*) FROM inventario WHERE inventario.FK_Producto = '$idProducto' AND FK_Sucursal = '$idSucursal' $busqueda) AS 'Num' FROM inventario LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE inventario.FK_Producto = '$idProducto' AND FK_Sucursal = '$idSucursal' $busqueda GROUP BY FK_Presentacion ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
	
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						if ($row[$i]['Presentacion'] == null) {
							$row[$i]['Presentacion'] = "NA";
						}

						if ($row[$i]['Abreviatura'] == null) {
							$row[$i]['Abreviatura'] = "NA";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Inventario'],
							'Presentacion' => $row[$i]['Presentacion'],
							'Abreviatura' => $row[$i]['Abreviatura'],
							'Cantidad' => $row[$i]['Cantidad'],
						);			
					}
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);
		}else if($tipo == "ConversionesProducto"){
			$idProducto = $omodelo->link->real_escape_string($idProducto);
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
					$busqueda .= "CONCAT(ID_Conversion, conversiones.FK_Producto, FK_Sucursal_Origen, sucursales.Nombre, presentaciones.Nombre, FK_Presentacion_Origen, Cantidad_Origen, FK_Sucursal_Destino, FK_Presentacion_Destino, Cantidad_Destino, Fecha_Registro, FK_Usuario) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			$query = "SELECT ID_Conversion, CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) AS NombreUsuario, conversiones.FK_Producto, FK_Sucursal_Origen, FK_Presentacion_Origen, Cantidad_Origen, FK_Sucursal_Destino, sucursales.Nombre AS SucursalOrigen, FK_Presentacion_Destino, Cantidad_Destino, Fecha_Registro, FK_Usuario, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Destino) AS NombreDestino,  presentaciones.Nombre AS Origen, (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Destino) AS PresentacionDestino, (SELECT COUNT(*) FROM conversiones WHERE conversiones.FK_Producto = '$idProducto' $busqueda) AS 'Num' FROM conversiones INNER JOIN sucursales ON FK_Sucursal_Origen = ID_Sucursal INNER JOIN presentaciones ON FK_Presentacion_Origen = ID_Presentacion INNER JOIN usuarios ON conversiones.FK_Usuario = ID_Usuario WHERE conversiones.FK_Producto = '$idProducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
	
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$origen = "
							Sucursal de origen: ".$row[$i]['SucursalOrigen']."<br>
							Presentación: ".$row[$i]['Origen']."<br>
							Cantidad: ".$row[$i]['Cantidad_Origen']."<br>
						"; 
						$destino = "
							Sucursal de destino: ".$row[$i]['NombreDestino']."<br>
							Presentación: ".$row[$i]['PresentacionDestino']."<br>
							Cantidad: ".$row[$i]['Cantidad_Destino']."<br>
						"; 

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Conversion'],
							'Origen' => $origen,
							'Destino' => $destino,
							'Usuario' => $row[$i]['NombreUsuario'],
						);			
					}
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);
		}else if($tipo == "ConsultarPresentacionMerma"){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);

			$query = "SELECT ID_Presentacion, Nombre, Abreviatura FROM inventario INNER JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE inventario.FK_Producto = '$IDProducto' AND FK_Sucursal = '$IDSucursal' GROUP BY ID_Presentacion";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = '<option value=""> Seleccione una opción </option>';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Presentacion'].'">'.$row[$i]['Nombre'].' ('.$row[$i]['Abreviatura'].')</option>';
					}
				}
			}
			echo $opciones;
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "EliminarMerma") {

			$IDMerma = $omodelo->link->real_escape_string($IDMerma);
			$RegresarInventario = $omodelo->link->real_escape_string($RegresarInventario);
			if ($RegresarInventario == "Si") {
				$queryInventario = "SELECT ID_Merma, FK_Producto, FK_Presentacion, Cantidad, FK_Sucursal, Foto FROM merma WHERE ID_Merma = '$IDMerma'";
				$row = $omodelo->_consultar($queryInventario);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){

						if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"])){
					       	unlink("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"]);
					    }

						$query1 = "UPDATE inventario SET Cantidad = (Cantidad + ".$row[0]["Cantidad"].") WHERE FK_Producto = '".$row[0]["FK_Producto"]."' AND FK_Sucursal = '".$row[0]["FK_Sucursal"]."' AND FK_Presentacion = '".$row[0]["FK_Presentacion"]."'";
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
		}
	}
}
