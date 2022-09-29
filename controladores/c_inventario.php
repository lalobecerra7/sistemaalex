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
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(ID_Producto, Codigo, Descripcion, Precio, Costo) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query =  "SELECT DISTINCT(ID_Producto) AS 'ID_Producto', Descripcion, Codigo, Precio, Costo, IFNULL(((SELECT SUM(inventario.Cantidad) FROM inventario
		 WHERE FK_Producto = productos.ID_Producto)),0) AS  'Cantidad', Imagen, 
		 (SELECT COUNT(DISTINCT(ID_Producto)) FROM productos) AS 'Num' FROM productos LEFT JOIN inventario ON inventario.FK_Producto = productos.ID_Producto 
		 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				
				for($i=0; $i<$numerofilas; $i++){

					$Merma = '';

					$queryMerma = "SELECT SUM(Cantidad * productos.Costo) AS TotalMerma, SUM(Cantidad) AS CantidadMerma FROM merma INNER JOIN productos ON merma.FK_Producto = ID_Producto WHERE merma.FK_Producto = '".$row[$i]["ID_Producto"]."'";
					
					$rowmerma = $omodelo->_consultar($queryMerma);
					$numerofilasmerma = $omodelo->numerofilas; 

					if ($rowmerma == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasmerma > 0){
							if ($rowmerma[0]["TotalMerma"] == "") {
								$rowmerma[0]["TotalMerma"] = 0;
							}
							if ($rowmerma[0]["CantidadMerma"] == "") {
								$rowmerma[0]["CantidadMerma"] = 0;
							}
							$Merma = '<b class="dinero">$'.number_format($rowmerma[0]["TotalMerma"], 2).'</b><br>Cantidad: <b class="cantidad">'.$rowmerma[0]["CantidadMerma"].'</b>';
						}
					}

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

					$totalPrecio = '';
					$totalCosto = '';
					$queryTotales = "SELECT SUM(inventario.Cantidad * Precio) AS TotalPrecio, SUM(inventario.Cantidad * Costo) AS TotalCosto FROM productos, inventario WHERE ID_Producto = '".$row[$i]["ID_Producto"]."'";
					$rowTotales = $omodelo->_consultar($queryTotales);
					$numerofilasTotales = $omodelo->numerofilas; 

					if ($rowTotales == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasTotales > 0){
							if ($rowTotales[0]["TotalPrecio"] == "") {
								$rowTotales[0]["TotalPrecio"] = 0;
							}
							if ($rowTotales[0]["TotalCosto"] == "") {
								$rowTotales[0]["TotalCosto"] = 0;
							}
							$totalPrecio = '<b class="dinero">$'.number_format($rowTotales[0]["TotalPrecio"], 2).'</b>';
							$totalCosto = '<b class="dinero">$'.number_format($rowTotales[0]["TotalCosto"], 2).'</b>';
						}
					}

					$sucursales="";
					$querySucursales="SELECT sucursales.Nombre AS 'Sucursal', Cantidad FROM inventario INNER JOIN sucursales ON  FK_Sucursal=sucursales.ID_Sucursal WHERE FK_Producto ='".$row[$i]["ID_Producto"]."'";
					$rowSucursales = $omodelo->_consultar($querySucursales);
					$numerofilasSucursales = $omodelo->numerofilas; 
					
					if ($rowSucursales == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasSucursales > 0){
							for($a=0; $a<$numerofilasSucursales; $a++){
								$sucursales.= $rowSucursales[$a]["Sucursal"].": ".$rowSucursales[$a]["Cantidad"]."\n";
							}	
						 }
					}
					
					$precios="";
					$costos="";
					$queryPrecios="SELECT sucursales.Nombre AS Sucursal, Precio, Costo FROM detalles_productos INNER JOIN sucursales ON FK_Sucursal = sucursales.ID_Sucursal WHERE FK_Producto ='".$row[$i]["ID_Producto"]."'";
					$rowPrecios = $omodelo->_consultar($queryPrecios);
					$numerofilasPrecios = $omodelo->numerofilas; 
					
					if ($rowPrecios == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasPrecios > 0){
							for($j=0; $j<$numerofilasPrecios; $j++){
								$precios.= $rowPrecios[$j]["Sucursal"].": <b>$".number_format($rowPrecios[$j]["Precio"],2)."</b><br>";
								$costos.= $rowPrecios[$j]["Sucursal"].": <b>$".number_format($rowPrecios[$j]["Costo"],2)."</b><br>";
							}	
						}
					}

					$preciosTotales="";
					$costosTotales="";
					$queryPreciosTotales="SELECT sucursales.Nombre AS Sucursal, SUM(inventario.Cantidad * detalles_productos.Precio) AS TotalPrecioSucursal, SUM(inventario.Cantidad * detalles_productos.Costo) AS TotalCostoSucursal FROM detalles_productos INNER JOIN inventario ON inventario.FK_Producto = '".$row[$i]["ID_Producto"]."' INNER JOIN sucursales ON detalles_productos.FK_Sucursal = sucursales.ID_Sucursal WHERE detalles_productos.FK_Producto = '".$row[$i]["ID_Producto"]."'";
					$rowPreciosTotales = $omodelo->_consultar($queryPreciosTotales);
					$numerofilasPreciosTotales = $omodelo->numerofilas; 
					
					if ($rowPreciosTotales == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasPreciosTotales > 0){
							for($j=0; $j<$numerofilasPreciosTotales; $j++){
								if($rowPreciosTotales[$j]["Sucursal"] == '' || $rowPreciosTotales[$j]["Sucursal"] == null){
									$preciosTotales=" ";
									$costosTotales=" ";
								}else {
									$preciosTotales.= $rowPreciosTotales[$j]["Sucursal"].": <b>$".number_format($rowPreciosTotales[$j]["TotalPrecioSucursal"],2)."</b><br>";
									$costosTotales.= $rowPreciosTotales[$j]["Sucursal"].": <b>$".number_format($rowPreciosTotales[$j]["TotalCostoSucursal"],2)."</b><br>";
								}
							}	
						}
					}

					$botonPermisosAgregarMerma = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][2] == '1') {
						$botonPermisosAgregarMerma = '<button class="btn btn-warning btn-sm mb-1" id="AgregarMerma" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Descripcion'].'"><i class="fas fa-level-down"></i></button>';
					}

					$botonPermisosTraslados = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][3] == '1') {
						$botonPermisosTraslados = '<button class="btn btn-primary btn-sm mb-1" id="Traslados" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Descripcion'].'"><i class="fa-solid fa-right-left"></i></button>';
					}

					$botonPermisosVerMerma = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][4] == '1') {
						$botonPermisosVerMerma = '<br><button type="button" class="btn btn-link btn-sm verDetallesMerma" nombre="'.$row[$i]["Descripcion"].'" attrid="'.$row[$i]["ID_Producto"].'" title="Detalles de la merma Merma">Ver detalles <i class="fas fa-eye"></i></button>';
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Producto'],
						'Producto' => $foto."<b>".$row[$i]['Codigo']."</b>",
						'Descripcion' => $row[$i]['Descripcion'],
						'Costo' => 'General: <b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b><br>'.$costos,
						'TotalCosto' => 'General: '.$totalCosto.'<br>'.$costosTotales,
						'Precio' => 'General: <b class="dinero">$'.number_format($row[$i]['Precio'], 2).'</b><br>'.$precios,
						'TotalPrecio' => 'General: '.$totalPrecio.'<br>'.$preciosTotales,
						'Merma' => $Merma.$botonPermisosVerMerma,
						'Detalles' => 'Existencia: <b>'.$row[$i]['Cantidad'].'</b><br>'.$sucursales,
						'Acciones' => $botonPermisosAgregarMerma.' '.$botonPermisosTraslados,
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
			/*$Cantidad = $omodelo->link->real_escape_string($Cantidad);
			$Motivo = $omodelo->link->real_escape_string($Motivo);
			$Sucursal = $omodelo->link->real_escape_string($IDSucursal);
			$FechaMerma = $omodelo->link->real_escape_string($FechaMerma);*/
			$Usuario = $_SESSION['user_admin']['ID_Usuario'];
			$existencia= '';
			$Costo = '';
			$query2 = "SELECT Cantidad, (SELECT Costo FROM detalles_Productos WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$Sucursal') AS Costo, productos.Costo AS CostoGral FROM inventario INNER JOIN productos ON ID_Producto = '$IDProducto' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$Sucursal'";
			$row = $omodelo->_consultar($query2);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$existencia = $row[0]["Cantidad"];
				}
				if($existencia < $CantidadMerma){
					echo "ErrorCantidad";
				}else{
					if($row[0]["Costo"] != '' || $row[0]["Costo"] != null){
						$Costo = $row[0]["Costo"];
					}else {
						$Costo = $row[0]["CostoGral"];
					}
					$existenciaM = ($existencia-$CantidadMerma);
				
					$query = "INSERT INTO merma SET FK_Sucursal = '$Sucursal', FK_Producto = '$IDProducto', Cantidad = '$CantidadMerma', Motivo = '$MotivoMerma', Fecha_Registro = '$fecha', Fecha_Merma = '$FechaMerma', FK_Usuario = '$Usuario'";
					$error = $omodelo->_insertar($query);
			
					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$IDMerma = mysqli_insert_id($omodelo->link);
						$query3 = "UPDATE inventario SET Cantidad = '$existenciaM' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$Sucursal'";
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
			$SucursalDestino = $omodelo->link->real_escape_string($SucursalDestino);
			$Cantidad = $omodelo->link->real_escape_string($Cantidad);
			
			$query = "INSERT INTO traslados SET Fecha_Registro = '$fecha', Fecha_Traslado = '$FechaTraslado', FK_Producto = '$IDProducto', FK_Sucursal_Origen = '$SucursalOrigen', Cantidad = '$Cantidad', FK_Sucursal_Destino = '$SucursalDestino', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);
	
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$query2 = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalOrigen'";
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

					$query3 = "UPDATE inventario SET Cantidad = '$cantidadNueva' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalOrigen' ";
					$error3 = $omodelo->_insertar($query3);
				
					if ($error3 == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$query4 = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalDestino'";
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

								$query5 = "UPDATE inventario SET Cantidad = '$cantidadNueva' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalDestino' ";
								$error5 = $omodelo->_insertar($query5);
							
								if ($error5 == "si") {
									echo "Error 1: ".mysqli_error($omodelo->link);
								}else{
									//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
									echo "Correcto";
								}
							}else {
								$query6 = "INSERT INTO inventario SET Cantidad = '$Cantidad', FK_Producto = '$IDProducto', FK_Sucursal = '$SucursalDestino' ";
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
						$busqueda .= "CONCAT(ID_Producto, sucursales.Nombre, merma.Cantidad, merma.Costo, DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r'), Motivo) REGEXP '".$separa[$i]."'";
						if($i < (count($separa)-1)){
							$busqueda .= ' AND ';
						}
					}
				}
		
				$query = "SELECT ID_Merma, merma.FK_Producto AS 'ID_Producto', sucursales.Nombre AS 'Sucursal', merma.Cantidad, (merma.Cantidad*merma.Costo) AS 'Costo', 
				DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r') AS Fecha_Merma, Motivo, (SELECT COUNT(DISTINCT(FK_Producto)) FROM merma) AS 'Num', Foto FROM `merma` INNER JOIN sucursales 
				ON sucursales.ID_Sucursal=merma.FK_Sucursal INNER JOIN productos 
				ON productos.ID_Producto=merma.FK_Producto WHERE FK_Producto='$IDProducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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
			}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "EliminarMerma") {

			$IDMerma = $omodelo->link->real_escape_string($IDMerma);
			$RegresarInventario = $omodelo->link->real_escape_string($RegresarInventario);
			if ($RegresarInventario == "Si") {
				$queryInventario = "SELECT ID_Merma, FK_Producto, Cantidad, FK_Sucursal, Foto FROM merma WHERE ID_Merma = '$IDMerma'";
				$row = $omodelo->_consultar($queryInventario);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){

						if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"])){
					       	unlink("vistas/assets/archivos/fotosMerma/".$row[0]["Foto"]);
					    }

						$query1 = "UPDATE inventario SET Cantidad = (Cantidad + ".$row[0]["Cantidad"].") WHERE FK_Producto = '".$row[0]["FK_Producto"]."' AND FK_Sucursal = '".$row[0]["FK_Sucursal"]."'";
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
