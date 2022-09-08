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
							$Merma = '<b class="dinero">$'.number_format($rowmerma[0]["TotalMerma"], 2).'</b><br>Cantidad: <b class="cantidad">'.$rowmerma[0]["CantidadMerma"].'</b><br><button type="button" class="btn btn-link btn-sm verDetallesMerma" nombre="'.$row[$i]["Descripcion"].'" attrid="'.$row[$i]["ID_Producto"].'" title="Detalles de la merma Merma">Ver detalles <i class="fas fa-eye"></i></button>';
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
							$totalPrecio = '<b class="dinero">$'.number_format($rowTotales[0]["TotalPrecio"], 2);
							$totalCosto = '<b class="dinero">$'.number_format($rowTotales[0]["TotalCosto"], 2);
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
					

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Producto'],
						'Producto' => $foto."<b>".$row[$i]['Codigo']."</b>",
						'Descripcion' => $row[$i]['Descripcion'],
						'Cantidad' => $row[$i]['Cantidad'],
						'Costo' => '<b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b>',
						'TotalCosto' => $totalCosto,
						'Precio' => '<b class="dinero">$'.number_format($row[$i]['Precio'], 2).'</b>',
						'TotalPrecio' => $totalPrecio,
						'Merma' => $Merma,
						'Detalles' => $sucursales,
						'Acciones' => '<button class="btn btn-warning" id="AgregarMerma" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Descripcion'].'"><i class="fa-solid fa-cart-arrow-down"></i></button>   <button class="btn btn-primary" id="Traslados" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Descripcion'].'"><i class="fa-solid fa-right-left"></i></button>' ,
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
			$Cantidad = $omodelo->link->real_escape_string($Cantidad);
			$Motivo = $omodelo->link->real_escape_string($Motivo);
			$Sucursal = $omodelo->link->real_escape_string($IDSucursal);
			$FechaMerma = $omodelo->link->real_escape_string($FechaMerma);
			$Usuario = $_SESSION['user_admin']['ID_Usuario'];
			$existencia= '';
			$query2 = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$Sucursal'";
			$row = $omodelo->_consultar($query2);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$existencia = $row[0]["Cantidad"];
				}
				if($existencia < $Cantidad){
					echo "ErrorCantidad";
				}else{
					$existenciaM = ($existencia-$Cantidad);
				
					$query = "INSERT INTO merma SET FK_Sucursal = '$Sucursal', FK_Producto = '$IDProducto', Cantidad = '$Cantidad', Motivo = '$Motivo', Fecha_Registro = '$fecha', Fecha_Merma = '$FechaMerma', FK_Usuario = '$Usuario'";
					$error = $omodelo->_insertar($query);
			
					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$query3 = "UPDATE inventario SET Cantidad = '$existenciaM' WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$Sucursal'";
						$error3 = $omodelo->_insertar($query3);
					
						if ($error3 == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
							echo "Correcto";
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
			
			$query = "INSERT INTO traslados SET Fecha_Registro = '$fecha', Fecha_Traslado = '$FechaTraslado', FK_Producto = '$IDProducto', FK_Sucursal_Origen = '$SucursalOrigen', Cantidad = '$Cantidad', FK_Sucursal_Destino = '$SucursalDestino'";
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
						$busqueda .= "CONCAT(ID_Producto, Sucursal, Cantidad, Costo, DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r')) REGEXP '".$separa[$i]."'";
						if($i < (count($separa)-1)){
							$busqueda .= ' AND ';
						}
					}
				}
		
				$query = "SELECT ID_Merma, merma.FK_Producto as 'ID_Producto', sucursales.Nombre as 'Sucursal', merma.Cantidad, (merma.Cantidad*productos.Costo) as 'Costo', 
				DATE_FORMAT(Fecha_Merma, '%d-%m-%Y %r') AS Fecha_Merma, Motivo, (SELECT COUNT(DISTINCT(FK_Producto)) FROM merma) as 'Num' FROM `merma` inner join sucursales 
				on sucursales.ID_Sucursal=merma.FK_Sucursal inner JOIN productos 
				on productos.ID_Producto=merma.FK_Producto where FK_Producto='$IDProducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
	
				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						for($i=0; $i<$numerofilas; $i++){
							$arreglo['data'][$i] = array(
								'ID' => $row[$i]['ID_Producto'],
								'Fecha' => $row[$i]['Fecha_Merma'],
								'Motivo' => $row[$i]['Motivo'],
								'Sucursal' => $row[$i]['Sucursal'],
								'Cantidad' => $row[$i]['Cantidad'],
								'Costo' => '<b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b>',
								'Acciones' => '<button class="btn btn-warning" id="ModificarMerma" attrid="'.$row[$i]['ID_Merma'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger" id="EliminarMerma" attrid="'.$row[$i]['ID_Merma'].'"><i class="fas fa-trash"></i></button>' ,
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
			}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "EliminarMerma") {

			$IDMerma = $omodelo->link->real_escape_string($IDMerma);
			$RegresarInventario = $omodelo->link->real_escape_string($RegresarInventario);
			if ($RegresarInventario == "Si") {
				$queryInventario = "SELECT ID_Merma, FK_Producto, Cantidad, FK_Sucursal FROM merma WHERE ID_Merma = '$IDMerma'";
				$row = $omodelo->_consultar($queryInventario);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){

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
