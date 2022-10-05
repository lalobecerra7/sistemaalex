
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
				$busqueda .= "CONCAT(ID_Producto, Codigo, productos.Descripcion, Tipo, Clase, Costo, Precio, Precio_Mayoreo, Detalles, Minimo, Maximo, Imagen, areas.Nombre, areas.Descripcion, areas.Nivel) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Producto, Codigo, productos.Descripcion, Tipo, Clase, Costo, Precio, Precio_Mayoreo, Detalles, Minimo, Maximo, Imagen, areas.Nombre, areas.Descripcion AS DescripcionArea, areas.Nivel, (SELECT COUNT(*) FROM productos $busqueda) AS Num, ((SELECT COUNT(*) FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta WHERE detalles_ventas.FK_Producto = ID_Producto) + (SELECT COUNT(*) FROM detalle_compras INNER JOIN compras ON FK_Compra = ID_Compra WHERE FK_Producto = ID_Producto)) AS numProd FROM productos LEFT JOIN areas ON FK_Area = ID_Area $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$area = "";
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
					$tipoProducto = '';
					if($row[$i]['Tipo'] == '1'){
						$tipoProducto = 'Producto';
					}else if ($row[$i]['Tipo'] == '0'){
						$tipoProducto = 'Materia';
					}

					if ($row[$i]['Nombre'] != "") {
						$area .= "Area: ".$row[$i]['Nombre']."<br>";
					}

					if ($row[$i]['Nivel'] != "") {
						$area .= "Nivel: ".$row[$i]['Nivel']."<br>";
					}

					if ($row[$i]['DescripcionArea'] != "") {
						$area .= "Descripción: ".$row[$i]['DescripcionArea']."<br>";
					}
					$precios = '';
					$queryZona = "SELECT ID_Zona, Nombre FROM zona";
					$rowZona = $omodelo->_consultar($queryZona);
					$numerofilasZona = $omodelo->numerofilas;
					if ($numerofilasZona > 0) {
						for ($a=0; $a < $numerofilasZona; $a++) { 
							$queryPrecios = "SELECT ID_Precio, FK_Producto, FK_Zona, Nombre, Precio, Precio_Mayoreo FROM precios WHERE FK_Producto = '".$row[$i]['ID_Producto']."' AND FK_Zona = '".$rowZona[$a]["ID_Zona"]."'";
							$rowPrecios = $omodelo->_consultar($queryPrecios);
							$numerofilasPrecios = $omodelo->numerofilas;
							if ($numerofilasPrecios > 0) {
								$precios .= "Zona: ".$rowZona[$a]["Nombre"]."<br>";
								for ($x=0; $x < $numerofilasPrecios; $x++) { 
									$precios .= $rowPrecios[$x]["Nombre"].": <b>$".number_format($rowPrecios[$x]["Precio"], 2)."</b><br>";
								}
							}	
						}
					}

					$presentacion = "<br><b>Presentaciones</b> <br>";
					$queryPresentacion = "SELECT ID_Presentacion, FK_Producto, Nombre, Abreviatura FROM presentaciones WHERE FK_Producto = '".$row[$i]['ID_Producto']."'";
					$rowPresentacion = $omodelo->_consultar($queryPresentacion);
					$numerofilasPresentacion = $omodelo->numerofilas;
					if ($numerofilasPresentacion > 0) {
						for ($z=0; $z < $numerofilasPresentacion; $z++) { 
							$presentacion .= "Nombre: ".$rowPresentacion[$z]["Nombre"]."<br>Abreviatura: ".$rowPresentacion[$z]["Abreviatura"]."<br>";
						}
					}

					$EliminarProducto = '<button class="btn btn-danger btn-sm mb-1" id="EliminarProducto" title="Eliminar producto" attrid="'.$row[$i]['ID_Producto'].'" descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-trash"></i></button>';
					if ($row[$i]['numProd'] > 0) {
						$EliminarProducto = '';
					}

					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][3] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm mb-1" id="ModificarProducto" title="Modificar producto" attrid="'.$row[$i]['ID_Producto'].'" descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][4] == '1') {
						$botonPermisosEliminar = $EliminarProducto;
					}

					$botonPermisosPreciosSucursal = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][5] == '1') {
						$botonPermisosPreciosSucursal = '<button class="btn btn-warning btn-sm mb-1" id="EditarPrecios" attrid="'.$row[$i]['ID_Producto'].'" descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-plus"></i></button>';
					}
					$botonAumentarExistencias = '';
					$botonAumentarExistencias = '<button class="btn btn-warning btn-sm mb-1" id="AumentarExistencias" title="Aumentar existencias" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-plus"></i></button>';
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Producto'],
						'Codigo' => $imagen."<b>".$row[$i]['Codigo']."</b>",
						'Descripcion' => $row[$i]['Descripcion'],
						'Costo' => '<b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b>',
						'Precio' => 'General: <b class="dinero">$'.number_format($row[$i]['Precio'], 2).'</b><br>'.$precios,
						'Detalles' => $area."Detalles: ".$row[$i]['Detalles'].$tipoProducto."<br> Clase: <b>".$row[$i]['Clase']."</b><br> Minimo: <b>".$row[$i]['Minimo']."</b> <br> Maximo: <b>".$row[$i]['Maximo']."</b>".$presentacion,
						'Acciones' => $botonPermisosModificar.' '.$botonPermisosEliminar.' '.$botonAumentarExistencias,
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
		$Costo =  $omodelo->link->real_escape_string($CostoProducto);
		$Precio =  $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo =  $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area =  $omodelo->link->real_escape_string($Area);
		$Detalles =  $omodelo->link->real_escape_string($DetallesProducto);
		$Minimo =  $omodelo->link->real_escape_string($Minimo);
		$Maximo =  $omodelo->link->real_escape_string($Maximo);
		$detalle =  explode("~", $detalleProducto);
		$impuesto =  explode(",", $impuestos);

		$query = "INSERT INTO productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion',  FK_Categoria = '$Categoria', Clase = '$Clase', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Minimo = '$Minimo', Maximo = '$Maximo', Fecha_Registro = '$Fecha'";
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

			$productos = explode(",", $productos);
			for ($i=0; $i < sizeof($productos) - 1; $i++) { 
				$datosproductos = explode("~", $productos[$i]);
				$queryPreciosProductos = "INSERT INTO precios SET FK_Producto = '$id', Nombre = '$datosproductos[1]', FK_Zona = '$datosproductos[0]', Precio = '$datosproductos[2]', Precio_Mayoreo = '$datosproductos[3]'";
				$errorPrecios = $omodelo->_insertar($queryPreciosProductos);	

				if ($errorPrecios == "si") {
					echo "Error productos precio: ".mysqli_error($omodelo->link); 
				}
			}

			$presentaciones = explode(",", $presentaciones);
			for ($i=0; $i < sizeof($presentaciones) - 1; $i++) { 
				$datospresentacion = explode("~", $presentaciones[$i]);
				$queryPresentacionProducto = "INSERT INTO presentaciones SET FK_Producto = '$id', Nombre = '$datospresentacion[0]', Abreviatura = '$datospresentacion[1]'";
				$errorPresentacion = $omodelo->_insertar($queryPresentacionProducto);	

				if ($errorPresentacion == "si") {
					echo "Error presentacion: ".mysqli_error($omodelo->link); 
				}
			}

			for($i=1; $i<sizeOf($detalle); $i++){
				$detallesucursal = explode(",", $detalle[$i]);
				$query = "INSERT INTO detalles_productos SET FK_Producto = '$id', FK_Sucursal = '$detallesucursal[1]', Costo = '$detallesucursal[2]', Precio = '$detallesucursal[3]', Precio_Mayoreo = '$detallesucursal[4]', Minimo = '$detallesucursal[5]', Maximo = '$detallesucursal[6]'";
				$row = $omodelo->_insertar($query);

				if ($row == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}

				if ($detallesucursal[7] != "" && $detallesucursal[7] > "0") {
					$queryInventario = "INSERT INTO inventario SET FK_Producto = '$id', FK_Sucursal = '$detallesucursal[1]', Cantidad = '$detallesucursal[7]'";
					$error = $omodelo->_insertar($queryInventario);

					if ($error == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}
				}

			}

			if(sizeOf($impuesto) > 1 ){
				for($j=0; $j<sizeOf($impuesto); $j++){
					$imp = explode("~", $impuesto[$j]);
					$query = "INSERT INTO detalles_impuestos_productos SET FK_Producto = '$id', FK_Sucursal = '$imp[0]', FK_Impuesto = '$imp[1]'";
					$row = $omodelo->_insertar($query);
	
					if ($row == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}
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
		$Costo =  $omodelo->link->real_escape_string($CostoProducto);
		$Precio =  $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo =  $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area =  $omodelo->link->real_escape_string($Area);
		$Detalles =  $omodelo->link->real_escape_string($DetallesProducto);
		$Minimo =  $omodelo->link->real_escape_string($Minimo);
		$Maximo =  $omodelo->link->real_escape_string($Maximo);

		$query = "UPDATE productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion', FK_Categoria = '$Categoria', Clase = '$Clase', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Minimo = '$Minimo', Maximo = '$Maximo' WHERE ID_Producto = '$IDProducto'";
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

			$queryProd = "DELETE FROM precios WHERE FK_Producto = '$IDProducto'";
			$errorDir = $omodelo->_insertar($queryProd);

			if ($errorDir == "si") {
				echo "Error Eliminar Productos: ".mysqli_error($omodelo->link);
			}else{
				$productos = explode(",", $productos);
				for ($i=0; $i < sizeof($productos) - 1; $i++) { 
					$datosproductos = explode("~", $productos[$i]);
					$queryPreciosProductos = "INSERT INTO precios SET FK_Producto = '$IDProducto', Nombre = '$datosproductos[1]', FK_Zona = '$datosproductos[0]', Precio = '$datosproductos[2]', Precio_Mayoreo = '$datosproductos[3]'";
					$errorPrecios = $omodelo->_insertar($queryPreciosProductos);	

					if ($errorPrecios == "si") {
						echo "Error productos: ".mysqli_error($omodelo->link); 
					}
				}
			}

			$queryProd = "DELETE FROM presentaciones WHERE FK_Producto = '$IDProducto'";
			$errorDir = $omodelo->_insertar($queryProd);

			if ($errorDir == "si") {
				echo "Error Eliminar Productos Presentacion: ".mysqli_error($omodelo->link);
			}else{
				$presentaciones = explode(",", $presentaciones);
				for ($i=0; $i < sizeof($presentaciones) - 1; $i++) { 
					$datospresentacion = explode("~", $presentaciones[$i]);
					$queryPresentacionProducto = "INSERT INTO presentaciones SET FK_Producto = '$IDProducto', Nombre = '$datospresentacion[0]', Abreviatura = '$datospresentacion[1]'";
					$errorPresentacion = $omodelo->_insertar($queryPresentacionProducto);	

					if ($errorPresentacion == "si") {
						echo "Error presentacion: ".mysqli_error($omodelo->link); 
					}
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

			$query = "SELECT ID_Producto, Codigo, Descripcion, FK_Categoria, Clase, Costo, Precio, Precio_Mayoreo, FK_Area, Detalles, Minimo, Maximo, Fecha_Registro, Imagen FROM productos WHERE ID_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$subarreglo = null; $subarreglo2 = null;
					$queryPrecios = "SELECT ID_Precio, FK_Producto, FK_Zona, Nombre, Precio, Precio_Mayoreo FROM precios WHERE FK_Producto = '".$row[0]['ID_Producto']."'";
					$rowPrecios = $omodelo->_consultar($queryPrecios);
					$numerofilasPrecios = $omodelo->numerofilas;
					for ($x=0; $x < $numerofilasPrecios; $x++) { 

						$queryZona = "SELECT ID_Zona, Nombre FROM zona";
						$rowZona = $omodelo->_consultar($queryZona);
						$numerofilasZona = $omodelo->numerofilas;
						$opciones = "";

						if ($rowZona == "si") {
							echo "Error: " . mysqli_error($omodelo->link);
						} else {
							if ($numerofilasZona > 0) {
								for ($z = 0; $z < $numerofilasZona; $z++) {
									if ($rowPrecios[$x]["FK_Zona"] == $rowZona[$z]['ID_Zona']) {
										$opciones .= '<option value="' . $rowZona[$z]['ID_Zona'] . '" selected>' . $rowZona[$z]['Nombre']. '</option>';
									}else{
										$opciones .= '<option value="' . $rowZona[$z]['ID_Zona'] . '" >' . $rowZona[$z]['Nombre']. '</option>';
									}
									
								}
							}
						}

						$subarreglo[$x] = array(
							'ID_Precio' => $rowPrecios[$x]["ID_Precio"],
							'FK_Producto' => $rowPrecios[$x]["FK_Producto"],
							'Zona' => $opciones,
							'Nombre' => $rowPrecios[$x]["Nombre"],
							'Precio' => $rowPrecios[$x]["Precio"],
							'Precio_Mayoreo' => $rowPrecios[$x]["Precio_Mayoreo"],
						);
					}

					$queryPresentacion = "SELECT ID_Presentacion, Nombre, Abreviatura FROM presentaciones WHERE FK_Producto = '".$row[0]['ID_Producto']."'";
					$rowPresentacion = $omodelo->_consultar($queryPresentacion);
					$numerofilasPresentacion = $omodelo->numerofilas;
					for ($z=0; $z < $numerofilasPresentacion; $z++) { 
						$subarreglo2[$z] = array(
							'ID_Presentacion' => $rowPresentacion[$z]["ID_Presentacion"],
							'Nombre' => $rowPresentacion[$z]["Nombre"],
							'Abreviatura' => $rowPresentacion[$z]["Abreviatura"],
						);
					}

					$arreglo = array(
							'ID_Producto' => $row[0]['ID_Producto'],
							'Codigo' => $row[0]["Codigo"],
							'Descripcion' => $row[0]["Descripcion"],
							'FK_Categoria' => $row[0]["FK_Categoria"],
							'Clase' => $row[0]["Clase"],
							'Costo' => $row[0]["Costo"],
							'Precio' => $row[0]["Precio"],
							'Precio_Mayoreo' => $row[0]["Precio_Mayoreo"],
							'FK_Area' => $row[0]["FK_Area"],
							'Detalles' => $row[0]["Detalles"],
							'Minimo' => $row[0]["Minimo"],
							'Maximo' => $row[0]["Maximo"],
							'Fecha_Registro' => $row[0]["Fecha_Registro"],
							'Imagen' => $row[0]["Imagen"],
							'Extras' => $subarreglo,
							'Presentaciones' => $subarreglo2
					);

					echo json_encode($arreglo);
				}
			}
			/*$query = "SELECT * FROM productos WHERE ID_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}*/
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
						$impuestosNombres = '';
						$sucursal = $row[$i]['FK_Sucursal'];
						
						$query2 = "SELECT Nombre FROM impuestos, detalles_impuestos_productos WHERE detalles_impuestos_productos.FK_Producto = '$IDProducto' AND detalles_impuestos_productos.FK_Sucursal = '$sucursal' AND detalles_impuestos_productos.FK_Impuesto = ID_Impuesto";
						$row2 = $omodelo->_consultar($query2);
						$numerofilasImpuestos = $omodelo->numerofilas;
						
						if ($row2 == "si") {
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilasImpuestos > 0){
								for($j=0; $j<$numerofilasImpuestos; $j++){

									$impuestosNombres .= $row2[$j]['Nombre'].', ';
	
								}
							}
						}

						$impuestosNombre= substr($impuestosNombres, 0, -1);

						$tabla .= ' 
						<tr>
						<td>' . $row[$i]['NombreSucursal'] . '</td>
						<td>' . $row[$i]['Costo'] . '</td>
						<td>' . $row[$i]['Precio'] . '</td>
						<td>' . $row[$i]['Precio_Mayoreo'] . '</td>
						<td>' . $row[$i]['Minimo'] . '</td>
						<td>' . $row[$i]['Maximo'] . '</td>
						<td>' . $impuestosNombre . '</td>
						<td><button class="btn btn-primary btn-sm mb-1" type= "button" id="EditarDetalle" attrid="'.$row[$i]['ID_Detalle_Producto'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm mb-1" type= "button" id="EliminarDetalle" attrid="'.$row[$i]['ID_Detalle_Producto'].'" ><i class="fas fa-trash"></i></button></td>
						</tr>';
					}
					echo json_encode($tabla);
				}else {
					$tabla = 'No se encontraron resultados';
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
		}else if($tipo == 'impuestosSucursalP'){
			$IdSucursal =  $omodelo->link->real_escape_string($IdSucursal);
			$IdProducto =  $omodelo->link->real_escape_string($IdProducto);
			$arreglo = '';

			$query = "SELECT ID_Detalle_Im_Producto, FK_Sucursal, FK_Producto, FK_Impuesto, impuestos.Nombre AS NombreImpuesto FROM detalles_impuestos_productos INNER JOIN impuestos ON ID_Impuesto = FK_Impuesto WHERE FK_Producto = '$IdProducto' AND FK_Sucursal = '$IdSucursal'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo .= 
							$row[$i]['FK_Sucursal'].'~'.$row[$i]['FK_Impuesto'].','.$row[$i]['NombreImpuesto'].',';
					}
					echo json_encode($arreglo);	
				}
			}
		} else if ($tipo == 'modificar'){
			$omodelo = new m_modelo();
			extract($_POST);
			$fecha = date('Y-m-d H:i:s'); 
			$IdProducto =  $omodelo->link->real_escape_string($IdProducto);
			$IdDetalle =  $omodelo->link->real_escape_string($IdDetalle);
			$Sucursal =  $omodelo->link->real_escape_string($Sucursal);
			$Costo =  $omodelo->link->real_escape_string($CostoProductoE);
			$Precio =  $omodelo->link->real_escape_string($PrecioProductoE);
			$PrecioMayoreo =  $omodelo->link->real_escape_string($PrecioMayoreoE);
			$Minimo =  $omodelo->link->real_escape_string($MinimoE);
			$Maximo =  $omodelo->link->real_escape_string($MaximoE);
			$impuesto = explode(",", $impuestos);

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
						$query = "DELETE FROM detalles_impuestos_productos WHERE FK_Producto='$IdProducto' AND FK_Sucursal = '$Sucursal'";
						$error = $omodelo->_insertar($query);
							
						if ($error == "si") {
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if(sizeOf($impuesto) > 0){
								for($j=0; $j<sizeOf($impuesto); $j++){
									$imp = explode("~", $impuesto[$j]);
									$query = "INSERT INTO detalles_impuestos_productos SET FK_Producto = '$IdProducto', FK_Sucursal = '$imp[0]', FK_Impuesto = '$imp[1]'";
									$row = $omodelo->_insertar($query);
					
									if ($row == "si") {
										echo "Error: ".mysqli_error($omodelo->link);
									}
								}
							}
							echo "Correcto";
						}
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
			$impuesto = explode(",", $impuestos);

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
						if(sizeOf($impuesto) > 0){
							for($j=0; $j<sizeOf($impuesto); $j++){
								$imp = explode("~", $impuesto[$j]);
								$query = "INSERT INTO detalles_impuestos_productos SET FK_Producto = '$IdProducto', FK_Sucursal = '$imp[0]', FK_Impuesto = '$imp[1]'";
								$row = $omodelo->_insertar($query);
				
								if ($row == "si") {
									echo "Error: ".mysqli_error($omodelo->link);
								}
							}
						}
						echo "Correcto";
						//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
					}
				}
			}
		}else if($tipo == 'eliminarPrecio'){
			$IDPrecio =  $omodelo->link->real_escape_string($IDPrecio);

			$query = "DELETE FROM detalles_productos WHERE ID_Detalle_Producto='$IDPrecio'";
			$error = $omodelo->_insertar($query);
				
			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
				echo "Correcto";
			}
		}else if($tipo == 'impuestos'){
			$impuesto = explode(",", $impuestos); ;
			$query = "SELECT * FROM impuestos";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$tabla = '';
			$checked = '';
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				for($i=0; $i<$numerofilas; $i++){
					if(sizeof($impuesto)>0){
						for($j=0; $j<sizeof($impuesto); $j++){
							if($impuesto[$j] == $row[$i]['ID_Impuesto']){
								$checked = 'checked';
								break;
							}else {
								$checked = '';
							}
						}
					}
					

					$tabla .='

					<tr>

					<td><input type="checkbox" id="checkImpuesto" name="checkImpuesto" attrid = '. $row[$i]['ID_Impuesto'] .' '.$checked.'></td>

					<td>' . $row[$i]['Nombre'] . '</td>

					<td>' . $row[$i]['Clave_CFDI'] . '</td>

					<td>' . $row[$i]['Porcentaje'] . '%</td>

					</tr>';

				}
				
				echo $tabla;
			}
		}else if($tipo == 'impuestosSeleccionados'){
			$Impuestos =  $omodelo->link->real_escape_string($impuestos);
			$Impuestos = trim($Impuestos, ',');
			$impuestos = '';
				
				$query = "SELECT Nombre FROM impuestos WHERE ID_Impuesto IN ($Impuestos)";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
				
				if ($row == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					for($i=0; $i<$numerofilas; $i++){

						$impuestos .= $row[$i]['Nombre'].', ';

					}
				}
				$imp = substr($impuestos, 0, -1);
			echo $imp;
		}else if($tipo  == "ConsultarZonaProducto"){
			$query = "SELECT ID_Zona, Nombre FROM zona";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						if (isset($IDZona) && $IDZona == $row[$i]['ID_Zona']) {
							$opciones .= '<option value="' . $row[$i]['ID_Zona'] . '" selected>' . $row[$i]['Nombre']. '</option>';
						}else{
							$opciones .= '<option value="' . $row[$i]['ID_Zona'] . '" >' . $row[$i]['Nombre']. '</option>';
						}
						
					}
				}
				echo $opciones;
			}
		}else if($tipo == "ConsultarPresentacionesExistencia"){
			$query = "SELECT ID_Presentacion, FK_Producto, Nombre, Abreviatura FROM presentaciones WHERE FK_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = '<option value="">- Seleccione una opción -</option>';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Presentacion'].'">' . $row[$i]['Nombre']. '</option>';
					}
				}
				echo $opciones;
			}
		}else if($tipo == 'AgregarExistenciaProducto'){
			$query = "SELECT * FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalExistencia'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					$query = "UPDATE inventario SET Cantidad = (Cantidad + $CantidadExistencia) WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalExistencia' AND FK_Presentacion = '$PresentacionesProducto'";
					$error = $omodelo->_insertar($query);
					if ($error == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
						echo "Correcto";
					}
				}else{
					$query = "INSERT INTO inventario SET Cantidad = $CantidadExistencia, FK_Producto = '$IDProducto', FK_Sucursal = '$SucursalExistencia', FK_Presentacion = '$PresentacionesProducto'";
					$error = $omodelo->_insertar($query);
					if ($error == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
						echo "Correcto";
					}
				}
			}
		}
	}
}
?>
