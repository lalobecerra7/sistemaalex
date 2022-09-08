
<?php
class productos {

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
				$busqueda .= "CONCAT(ID_Producto, Codigo, Descripcion, Tipo, Clase, Costo, Precio, Precio_Mayoreo, Detalles, Minimo, Maximo, Imagen) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Producto, Codigo, Descripcion, Tipo, Clase, Costo, Precio, Precio_Mayoreo, Detalles, Minimo, Maximo, Imagen, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$imagen = '<a href="vistas/assets/img/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
					if ($row[$i]["Imagen"] != "") {
						if($row[$i]["Imagen"] != "" && file_exists("vistas/assets/archivos/fotosProductos/".$row[$i]["Imagen"])){
							$imagen = '<a href="vistas/assets/img/productos/'.$row[$i]["Imagen"].'" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
						}	
					}
					$tipoProducto = '';
					if($row[$i]['Tipo'] == '1'){
						$tipoProducto = 'Producto';
					}else if ($row[$i]['Tipo'] == '0'){
						$tipoProducto = 'Materia';
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Producto'],
						'Codigo' => $imagen."<b>".$row[$i]['Codigo']."</b>",
						'Descripcion' => $row[$i]['Descripcion'],
						'Tipo' => $tipoProducto."<br> Clase: <b>".$row[$i]['Clase']."</b>",
						'Costo' => '<b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b>',
						'Precio' => '<b class="dinero">$'.number_format($row[$i]['Precio'], 2).'</b>',
						'PrecioMayoreo' => '<b class="dinero">$'.number_format($row[$i]['Precio_Mayoreo'], 2).'</b>',
						'Detalles' => $row[$i]['Detalles']."<br> Minimo: <b>".$row[$i]['Minimo']."</b> <br> Maximo: <b>".$row[$i]['Maximo']."</b>",
						'Acciones' => '<button class="btn btn-primary btn-sm" id="ModificarProducto" attrid="'.$row[$i]['ID_Producto'].'" descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm" id="EliminarProducto" attrid="'.$row[$i]['ID_Producto'].'" descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-trash"></i></button> <button class="btn btn-warning btn-sm" id="EditarPrecios" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-plus"></i></button>',
					);
					
				}

				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
			}
		}

		echo json_encode($arreglo);
	}

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$Fecha = date('Y-m-d H:i:s');
		$CodigoBarras =  $omodelo->link->real_escape_string($CodigoBarras);
		$Descripcion =  $omodelo->link->real_escape_string($Descripcion);
		$Categoria =  $omodelo->link->real_escape_string($Categoria);
		$Clase =  $omodelo->link->real_escape_string($ClaseProducto);
		$TipoUnidad =  $omodelo->link->real_escape_string($TipoUnidad);
		$Unidad =  $omodelo->link->real_escape_string($Unidad);
		$PonerUnidad =  $omodelo->link->real_escape_string($PonerUnidad);
		$Costo =  $omodelo->link->real_escape_string($CostoProducto);
		$Precio =  $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo =  $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area =  $omodelo->link->real_escape_string($Area);
		$Detalles =  $omodelo->link->real_escape_string($DetallesProducto);
		$Minimo =  $omodelo->link->real_escape_string($Minimo);
		$Maximo =  $omodelo->link->real_escape_string($Maximo);
		$detalle =  explode("~", $detalleProducto);

		$query = "INSERT INTO productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion', Tipo = '$TipoUnidad', FK_Categoria = '$Categoria', Clase = '$Clase', FK_Unidad = '$Unidad', Poner_Unidad = '$PonerUnidad', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Minimo = '$Minimo', Maximo = '$Maximo', Fecha_Registro = '$Fecha'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			$id = mysqli_insert_id($omodelo->link);

			$status = 1;
			if ($_FILES['ImagenProducto']['size'] > 0 && $_FILES['ImagenProducto']['error'] == 0) {
				$file = $_FILES["ImagenProducto"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosProductos/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status = 0;
					$ruta = $carpeta;
				}
			}
			
			if($status == 0){
				$query2 = "UPDATE productos SET Imagen = '".$id.'_'.$nombreDoc."' WHERE ID_Producto = '$id'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$id.'_'.$nombreDoc);
				}
			}

			for($i=1; $i<sizeOf($detalle); $i++){
				$detallesucursal = explode(",", $detalle[$i]);
				$query = "INSERT INTO detalles_productos SET FK_Producto = '$id', FK_Sucursal = '$detallesucursal[1]', Costo = '$detallesucursal[2]', Precio = '$detallesucursal[3]', Precio_Mayoreo = '$detallesucursal[4]', Minimo = '$detallesucursal[5]', Maximo = '$detallesucursal[6]'";
				$row = $omodelo->_insertar($query);

				if ($row == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}
			}
			echo "Correcto";
			//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$IDProducto =  $omodelo->link->real_escape_string($IDProducto);
		$CodigoBarras =  $omodelo->link->real_escape_string($CodigoBarras);
		$Descripcion =  $omodelo->link->real_escape_string($Descripcion);
		$Categoria =  $omodelo->link->real_escape_string($Categoria);
		$Clase =  $omodelo->link->real_escape_string($ClaseProducto);
		$TipoUnidad =  $omodelo->link->real_escape_string($TipoUnidad);
		$Unidad =  $omodelo->link->real_escape_string($Unidad);
		$PonerUnidad =  $omodelo->link->real_escape_string($PonerUnidad);
		$Costo =  $omodelo->link->real_escape_string($CostoProducto);
		$Precio =  $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo =  $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area =  $omodelo->link->real_escape_string($Area);
		$Detalles =  $omodelo->link->real_escape_string($DetallesProducto);
		$Minimo =  $omodelo->link->real_escape_string($Minimo);
		$Maximo =  $omodelo->link->real_escape_string($Maximo);

		$query = "UPDATE productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion', Tipo = '$TipoUnidad', FK_Categoria = '$Categoria', Clase = '$Clase', FK_Unidad = '$Unidad', Poner_Unidad = '$PonerUnidad', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Minimo = '$Minimo', Maximo = '$Maximo' WHERE ID_Producto = '$IDProducto'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			$status = 1;
			if ($_FILES['ImagenProducto']['size'] > 0 && $_FILES['ImagenProducto']['error'] == 0) {
				$file = $_FILES["ImagenProducto"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosProductos/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status = 0;
					$ruta = $carpeta;
				}
			}
			
			if($status == 0){
				$query1 = "SELECT Imagen FROM productos WHERE ID_Producto = '$IDProducto'";
				$row1 = $omodelo->_consultar($query1);
				$numerofilas = $omodelo->numerofilas;

				if ($row1 == "si") {
					echo "Error consultar archivo: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						if($row1[0]['Imagen'] != "" && file_exists('vistas/assets/archivos/fotosProductos/'.$row1[0]['Imagen'])){
					       		unlink('vistas/assets/archivos/fotosProductos/'.$row1[0]['Imagen']);
					    }
					}
				}
				$query2 = "UPDATE productos SET Imagen = '".$IDProducto.'_'.$nombreDoc."' WHERE ID_Producto = '$IDProducto'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$IDProducto.'_'.$nombreDoc);
				}
			}
			echo "Correcto";
			//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$IDProducto =  $omodelo->link->real_escape_string($IDProducto);

		$query = "SELECT Imagen FROM productos WHERE ID_Producto = '$IDProducto'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		if ($row == "si") {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			if ($numerofilas > 0) {
				if ($row[0]["Imagen"] !="" && file_exists("vistas/assets/archivos/fotosProductos/".$row[0]["Imagen"]."")) {
					unlink("vistas/assets/archivos/fotosProductos/".$row[0]["Imagen"]."");
				}
				$query = "DELETE FROM productos WHERE ID_Producto='$IDProducto'";
				$error = $omodelo->_insertar($query);
				if ($error == "si") {
					echo "Error 1: " . mysqli_error($omodelo->link);
				} else {
					$query = "DELETE FROM detalles_productos WHERE FK_Producto='$IDProducto'";
					$error = $omodelo->_insertar($query);
					if ($error == "si") {
						echo "Error 1: " . mysqli_error($omodelo->link);
					} else {
						echo "Correcto";
						//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
					}
				}
			}
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		if($tipo == 'modificarProducto'){
			$IDProducto =  $omodelo->link->real_escape_string($IDProducto);

			$query = "SELECT * FROM productos WHERE ID_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}
		} else if($tipo == 'detalle'){
			$IDProducto =  $omodelo->link->real_escape_string($IDProducto);
			$tabla = '';
			$query = "SELECT ID_Detalle_Producto, FK_Sucursal, sucursales.Nombre AS NombreSucursal, Costo, Precio, Precio_Mayoreo, Minimo, Maximo FROM detalles_productos INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$tabla .= ' 
						<tr>
						<td>' . $row[$i]['NombreSucursal'] . '</td>
						<td>' . $row[$i]['Costo'] . '</td>
						<td>' . $row[$i]['Precio'] . '</td>
						<td>' . $row[$i]['Precio_Mayoreo'] . '</td>
						<td>' . $row[$i]['Minimo'] . '</td>
						<td>' . $row[$i]['Maximo'] . '</td>
						<td><button class="btn btn-primary btn-sm" type= "button" id="EditarDetalle" attrid="'.$row[$i]['ID_Detalle_Producto'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm" type= "button" id="EliminarDetalle" attrid="'.$row[$i]['ID_Detalle_Producto'].'" ><i class="fas fa-trash"></i></button></td>
						</tr>';
					}
					echo json_encode($tabla);
				}
			}
		} else if($tipo == 'detalleSucursal'){
			$IdDetalle =  $omodelo->link->real_escape_string($IdDetalle);

			$query = "SELECT * FROM detalles_productos WHERE ID_Detalle_Producto = '$IdDetalle'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}
		} else if ($tipo == 'modificar'){
			$omodelo = new m_modelo();
			extract($_POST);
			$fecha = date('Y-m-d H:i:s'); 
			$IdDetalle =  $omodelo->link->real_escape_string($IdDetalle);
			$Sucursal =  $omodelo->link->real_escape_string($Sucursal);
			$Costo =  $omodelo->link->real_escape_string($CostoProductoE);
			$Precio =  $omodelo->link->real_escape_string($PrecioProductoE);
			$PrecioMayoreo =  $omodelo->link->real_escape_string($PrecioMayoreoE);
			$Minimo =  $omodelo->link->real_escape_string($MinimoE);
			$Maximo =  $omodelo->link->real_escape_string($MaximoE);

			$query = "SELECT * FROM detalles_productos WHERE FK_Producto = '$IdProducto' AND FK_Sucursal = '$Sucursal' AND ID_Detalle_Producto != '$IdDetalle'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo "Duplicado";
				}else {
					$query = "UPDATE detalles_productos SET FK_Sucursal = '$Sucursal', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', Minimo = '$Minimo', Maximo = '$Maximo' WHERE ID_Detalle_Producto = '$IdDetalle'";
					$row = $omodelo->_insertar($query);

					if ($row == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";
						//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
					}
				}
			}
			
		}else if ($tipo == 'agregar'){
			$omodelo = new m_modelo();
			extract($_POST);
			$fecha = date('Y-m-d H:i:s'); 
			$IdProducto =  $omodelo->link->real_escape_string($IdProducto);
			$Sucursal =  $omodelo->link->real_escape_string($Sucursal);
			$Costo =  $omodelo->link->real_escape_string($CostoProductoE);
			$Precio =  $omodelo->link->real_escape_string($PrecioProductoE);
			$PrecioMayoreo =  $omodelo->link->real_escape_string($PrecioMayoreoE);
			$Minimo =  $omodelo->link->real_escape_string($MinimoE);
			$Maximo =  $omodelo->link->real_escape_string($MaximoE);

			$query = "SELECT * FROM detalles_productos WHERE FK_Producto = '$IdProducto' AND FK_Sucursal = '$Sucursal'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo "Duplicado";
				}else {
					$query = "INSERT INTO detalles_productos SET FK_Producto = '$IdProducto', FK_Sucursal = '$Sucursal', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', Minimo = '$Minimo', Maximo = '$Maximo'";
					$row = $omodelo->_insertar($query);

					if ($row == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";
						//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
					}
				}
			}
		}
	}
}
?>
