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
				$busqueda .= "CONCAT(ID_Producto, Nombre, Codigo, Descripcion, Precio, Costo, Existencia, Foto) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		//SELECT ID_Producto, Descripcion, Codigo, Precio, Costo, IFNULL(((SELECT SUM(inventario.Cantidad) FROM inventario WHERE FK_Producto = productos.ID_Producto)),0) AS 'Cantidad', Imagen, inventario.FK_Sucursal as 'Sucursal', (SELECT COUNT(*) FROM productos) AS Num FROM productos LEFT JOIN inventario ON `FK_Producto` = productos.ID_Producto;

		$query = "SELECT ID_Producto, Codigo, Descripcion, Precio, Costo, Existencia, Foto, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				
				for($i=0; $i<$numerofilas; $i++){

					$Merma = '';

					$queryMerma = "SELECT SUM(Cantidad * productos.Costo) AS TotalMerma, SUM(Cantidad) AS CantidadMerma FROM merma INNER JOIN productos ON merma.Producto = ID_Producto WHERE merma.Producto = '".$row[$i]["ID_Producto"]."'";
					
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
							$Merma = '<b class="dinero">$'.number_format($rowmerma[0]["TotalMerma"], 2).'</b><br>Cantidad: <b class="cantidad">'.$rowmerma[0]["CantidadMerma"].'</b><br><button type="button" class="btn btn-link btn-sm verDetallesMerma" nombre="'.$row[$i]["Nombre"].'" attrid="'.$row[$i]["ID_Producto"].'" title="Detalles de la merma Merma">Ver detalles <i class="fas fa-eye"></i></button>';
						}
					}

					$foto = '<a href="vistas/assets/img/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/img/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
					if ($row[$i]["Foto"] != "") {
						if($row[$i]["Foto"] != "" && file_exists("vistas/assets/img/productos/".$row[$i]["Foto"])){
							$foto = '<a href="vistas/assets/img/productos/'.$row[$i]["Foto"].'" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/img/productos/'.$row[$i]["Foto"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
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

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Producto'],
						'Producto' => $foto."<b>".$row[$i]['Codigo']."</b>",
						'Nombre' => $row[$i]['Nombre'],
						'Existencia' => $row[$i]['Existencia'],
						'Costo' => '<b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b>',
						'TotalCosto' => $totalCosto,
						'Precio' => '<b class="dinero">$'.number_format($row[$i]['Precio'], 2).'</b>',
						'TotalPrecio' => $totalPrecio,
						'Merma' => $Merma,
						'Detalles' => '<button class="btn btn-link" id="Detalles" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Nombre'].'">Entradas</button>   <button class="btn btn-link" id="Salidas" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Nombre'].'">Salidas</button>',
						'Acciones' => '<button class="btn btn-warning" id="AgregarMerma" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fa-solid fa-cart-arrow-down"></i></button>   <button class="btn btn-primary" id="AgregarExistencia" attrid="'.$row[$i]['ID_Producto'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fa-solid fa-cart-plus"></i></button>' ,
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

			$query2 = "SELECT Existencia FROM productos WHERE ID_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query2);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$existencia = $row[0]["Existencia"];
				}
				if($existencia < $Cantidad){
					echo "ErrorCantidad";
				}else{
					$existenciaM = ($existencia-$Cantidad);
				
					$query = "INSERT INTO merma SET FK_Producto = '$IDProducto', Cantidad = '$Cantidad', Motivo_Merma = '$Motivo', Fecha_Registro = '$fecha'";
					$error = $omodelo->_insertar($query);
			
					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$query3 = "UPDATE productos SET Existencia = '$existenciaM' WHERE ID_Producto = '$IDProducto' ";
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
		} else if($tipo == 'agregarExistencia'){
			$fecha = date('Y-m-d H:i:s'); 
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$Costo = $omodelo->link->real_escape_string($Costo);
			$Cantidad = $omodelo->link->real_escape_string($Cantidad);
			$usuario = $_SESSION['user_admin']['ID_Usuario'];
			$existencia = '';
			
			$query = "INSERT INTO entradas_inventario SET Fecha_Registro = '$fecha', FK_Producto = '$IDProducto', Costo = '$Costo', Cantidad = '$Cantidad', Motivo = 'AgregarExistencia', FK_Usuario = '$usuario'";
			$error = $omodelo->_insertar($query);
	
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$query2 = "SELECT Existencia FROM productos WHERE ID_Producto = '$IDProducto'";
				$row = $omodelo->_consultar($query2);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						for($i=0; $i<$numerofilas; $i++){
							$existencia = $row[$i]["Existencia"];
						}
					}
					$existenciaN = ($Cantidad+$existencia);

					$query3 = "UPDATE productos SET Existencia = '$existenciaN' WHERE ID_Producto = '$IDProducto' ";
					$error3 = $omodelo->_insertar($query3);
				
					if ($error3 == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$omodelo->movimiento($query3, $_SESSION['user_admin']['ID_Usuario']);
						echo "Correcto";
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
		if ($tipo == 'detalles'){

			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$tabla = "";
			$query = "SELECT ID_Entrada_Inventario, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, FK_Producto, Costo, Cantidad, FK_Usuario, usuarios.Nombre AS Usuario, Motivo FROM entradas_inventario INNER JOIN usuarios ON FK_Usuario = ID_Usuario WHERE FK_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$motivo = $row[$i]["Motivo"];
						$motivo = preg_replace('/(.)(?=[A-Z])/u', '$0 ', $motivo);

						$tabla .= "
							<tr>
								<td >".$row[$i]["Fecha_Registro"]."</td>
								<td >$".number_format($row[$i]["Costo"], 2)."</td>
								<td >".number_format($row[$i]["Cantidad"], 2)."</td>
								<td >".$row[$i]["Usuario"]."</td>
								<td >".$motivo."</td>
							</tr>
						";
					}
				}else{
					$tabla = "SinResultados";
				}
			}

			echo $tabla;
		}else if ($tipo == 'merma'){

			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$tabla = "";
			$query = "SELECT ID_Merma, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, FK_Producto, Cantidad, Motivo_Merma FROM merma WHERE FK_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$tabla .= "
							<tr>
								<td >".$row[$i]["Fecha_Registro"]."</td>
								<td >".number_format($row[$i]["Cantidad"], 2)."</td>
								<td >".$row[$i]["Motivo_Merma"]."</td>
							</tr>
						";
					}
				}else{
					$tabla = "SinResultados";
				}
			}

			echo $tabla;
		}else if ($tipo == 'salidas'){

			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$tabla = "";
			$query = "SELECT ID_Detalle_Salidas, DATE_FORMAT(salidas_vendedores.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, FK_Producto, Cantidad, FK_Vendedor, vendedores.Nombre AS NombreVendedor, vendedores.Primer_Apellido AS PrimerApellidoVendedor, vendedores.Segundo_Apellido AS SegundoApellidoVendedor, FK_Usuario, usuarios.Nombre AS NombreUsuario FROM detalles_salidas INNER JOIN salidas_vendedores ON FK_Salida = ID_Salida INNER JOIN vendedores on ID_Vendedor = FK_Vendedor INNER JOIN usuarios ON ID_Usuario = FK_Usuario WHERE FK_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$tabla .= "
							<tr>
								<td >".$row[$i]["Fecha_Registro"]."</td>
								<td >".$row[$i]["NombreUsuario"]."</td>
								<td >".$row[$i]["NombreVendedor"]." ".$row[$i]["PrimerApellidoVendedor"]." ".$row[$i]["SegundoApellidoVendedor"]."</td>
								<td >".number_format($row[$i]["Cantidad"], 2)."</td>
							</tr>
						";
					}
				}else{
					$tabla = "SinResultados";
				}
			}

			echo $tabla;
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
	}
}
?>