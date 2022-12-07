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
					$queryZona = "SELECT ID_Zona, Nombre FROM zonas";
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

					$EliminarProducto = '<button class="btn btn-danger btn-sm mb-1 EliminarProducto" title="Eliminar producto" attrid="'.$row[$i]['ID_Producto'].'" descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-trash"></i></button>';
					if ($row[$i]['numProd'] > 0) {
						$EliminarProducto = '';
					}

					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][3] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm mb-1 ModificarProducto" title="Modificar producto" attrid="'.$row[$i]['ID_Producto'].'" descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][4] == '1') {
						$botonPermisosEliminar = $EliminarProducto;
					}

					$botonAumentarExistencias = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][5] == '1') {
						$botonAumentarExistencias = '<button class="btn btn-warning btn-sm mb-1 AumentarExistencias" title="Aumentar existencias" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-plus"></i></button>';
					}
					
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Producto'],
						'Codigo' => $imagen."<b>".$row[$i]['Codigo']."</b>",
						'Descripcion' => $row[$i]['Descripcion'],
						'Costo' => '<b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b>',
						'Precio' => 'General: <b class="dinero">$'.number_format($row[$i]['Precio'], 2).'</b><br>'.$precios,
						'Detalles' => $area."Detalles: ".$row[$i]['Detalles']."<br> Clase: <b>".$row[$i]['Clase']."</b><br> Minimo: <b>".$row[$i]['Minimo']."</b> <br> Maximo: <b>".$row[$i]['Maximo']."</b>".$presentacion,
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
		$CodigoBarras = $omodelo->link->real_escape_string($CodigoBarras);
		$Descripcion = $omodelo->link->real_escape_string($Descripcion);
		$Categoria = $omodelo->link->real_escape_string($Categoria);
		$Clase = $omodelo->link->real_escape_string($ClaseProducto);
		$Costo = $omodelo->link->real_escape_string($CostoProducto);
		$Precio = $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo = $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area = $omodelo->link->real_escape_string($Area);
		$Detalles = $omodelo->link->real_escape_string($DetallesProducto);
		$Minimo = $omodelo->link->real_escape_string($Minimo);
		$Maximo = $omodelo->link->real_escape_string($Maximo);
		$claveProdServ = $omodelo->link->real_escape_string($claveProdServ);
		$claveUnidadProd = $omodelo->link->real_escape_string($claveUnidadProd);
		$unidadProd = $omodelo->link->real_escape_string($unidadProd);
		$abreUnudadProd = $omodelo->link->real_escape_string($abreUnudadProd);
		$objImProducto = $omodelo->link->real_escape_string($objImProducto);

		$query = "INSERT INTO productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion',  FK_Categoria = '$Categoria', Tipo = '1', Clase = '$Clase', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Minimo = '$Minimo', Maximo = '$Maximo', Fecha_Registro = '$Fecha', Clave_ProdServ_CFDI = '$claveProdServ', Clave_Unidad_CFDI = '$claveUnidadProd', Nombre_Unidad = '$unidadProd', Abreviatura_Unidad = '$abreUnudadProd', Objeto_Impuesto_CFDI = '$objImProducto'";
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


			$presentaciones = json_decode($presentaciones, true);
			foreach ($presentaciones as $pres) {
				$pres['Clave'] = $omodelo->link->real_escape_string($pres['Clave']);
				$pres['Nombre'] = $omodelo->link->real_escape_string($pres['Nombre']);
				$pres['Abreviatura'] = $omodelo->link->real_escape_string($pres['Abreviatura']);

				$queryPresentacion = "INSERT INTO presentaciones SET FK_Producto = '$id', Nombre = '$pres[Nombre]', Abreviatura = '$pres[Abreviatura]', Clave_CFDI = '$pres[Clave]'";
				$errorPresentacion = $omodelo->_insertar($queryPresentacion);	

				if ($error == "si") {
					echo "Error presentacion: ".mysqli_error($omodelo->link); 
				}
			}

			$precios = json_decode($precios, true);
			foreach ($precios as $pre) {
				$pre['Zona'] = $omodelo->link->real_escape_string($pre['Zona']); 
				$pre['Presentacion'] = $omodelo->link->real_escape_string($pre['Presentacion']); 
				$pre['Nombre'] = $omodelo->link->real_escape_string($pre['Nombre']); 
				$pre['Precio'] = $omodelo->link->real_escape_string($pre['Precio']); 
				$pre['Precio_Mayoreo'] = $omodelo->link->real_escape_string($pre['Precio_Mayoreo']); 

				$queryPrecio = "INSERT INTO precios SET FK_Producto = '$id', FK_Zona = '$pre[Zona]', FK_Presentacion = IFNULL((SELECT ID_Presentacion FROM presentaciones WHERE Nombre = '$pre[Presentacion]' AND FK_Producto = '$id'), 0), Nombre = '$pre[Nombre]', Precio = '$pre[Precio]', Precio_Mayoreo = '$pre[Precio_Mayoreo]'";
				$error = $omodelo->_insertar($queryPrecio);	

				if ($error == "si") {
					echo "Error precio: ".mysqli_error($omodelo->link); 
				}
			}

			$impuestos = json_decode($impuestos, true);
			foreach ($impuestos as $im) {
				$im['ID_Impuesto'] = $omodelo->link->real_escape_string($im['ID_Impuesto']);

				$query1 = "INSERT INTO detalles_impuestos_productos SET FK_Producto = '$id', FK_Impuesto = '$im[ID_Impuesto]'";
				$error = $omodelo->_insertar($query1);
	
				if ($error == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}
			}
			
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$IDProducto = $omodelo->link->real_escape_string($IDProducto);
		$CodigoBarras = $omodelo->link->real_escape_string($CodigoBarras);
		$Descripcion = $omodelo->link->real_escape_string($Descripcion);
		$Categoria = $omodelo->link->real_escape_string($Categoria);
		$Clase = $omodelo->link->real_escape_string($ClaseProducto);
		$Costo = $omodelo->link->real_escape_string($CostoProducto);
		$Precio = $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo = $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area = $omodelo->link->real_escape_string($Area);
		$Detalles = $omodelo->link->real_escape_string($DetallesProducto);
		$Minimo = $omodelo->link->real_escape_string($Minimo);
		$Maximo = $omodelo->link->real_escape_string($Maximo);
		$claveProdServ = $omodelo->link->real_escape_string($claveProdServ);
		$claveUnidadProd = $omodelo->link->real_escape_string($claveUnidadProd);
		$unidadProd = $omodelo->link->real_escape_string($unidadProd);
		$abreUnudadProd = $omodelo->link->real_escape_string($abreUnudadProd);
		$objImProducto = $omodelo->link->real_escape_string($objImProducto);

		$query = "UPDATE productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion', FK_Categoria = '$Categoria', Clase = '$Clase', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Minimo = '$Minimo', Maximo = '$Maximo', Clave_ProdServ_CFDI = '$claveProdServ', Clave_Unidad_CFDI = '$claveUnidadProd', Nombre_Unidad = '$unidadProd', Abreviatura_Unidad = '$abreUnudadProd', Objeto_Impuesto_CFDI = '$objImProducto' WHERE ID_Producto = '$IDProducto'";
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

			$query1 = "DELETE FROM presentaciones WHERE FK_Producto = '$IDProducto'";
			$error = $omodelo->_insertar($query1);
	
			if ($error == "si") {
				echo "Error 5: ".mysqli_error($omodelo->link);
			}else{
				$presentaciones = json_decode($presentaciones, true);
				foreach ($presentaciones as $pres) {
					$pres['ID_Presentacion'] = $omodelo->link->real_escape_string($pres['ID_Presentacion']);
					$pres['Clave'] = $omodelo->link->real_escape_string($pres['Clave']);
					$pres['Nombre'] = $omodelo->link->real_escape_string($pres['Nombre']);
					$pres['Abreviatura'] = $omodelo->link->real_escape_string($pres['Abreviatura']);

					$queryPresentacion = "INSERT INTO presentaciones SET ID_Presentacion = '$pres[ID_Presentacion]',FK_Producto = '$IDProducto', Nombre = '$pres[Nombre]', Abreviatura = '$pres[Abreviatura]', Clave_CFDI = '$pres[Clave]'";
					$errorPresentacion = $omodelo->_insertar($queryPresentacion);	

					if ($error == "si") {
						echo "Error presentacion: ".mysqli_error($omodelo->link); 
					}
				}
			}

			$query1 = "DELETE FROM precios WHERE FK_Producto = '$IDProducto'";
			$error = $omodelo->_insertar($query1);
	
			if ($error == "si") {
				echo "Error 6: ".mysqli_error($omodelo->link);
			}else{
				$precios = json_decode($precios, true);
				foreach ($precios as $pre) {
					$pre['Zona'] = $omodelo->link->real_escape_string($pre['Zona']); 
					$pre['Presentacion'] = $omodelo->link->real_escape_string($pre['Presentacion']); 
					$pre['Nombre'] = $omodelo->link->real_escape_string($pre['Nombre']); 
					$pre['Precio'] = $omodelo->link->real_escape_string($pre['Precio']); 
					$pre['Precio_Mayoreo'] = $omodelo->link->real_escape_string($pre['Precio_Mayoreo']); 

					$queryPrecio = "INSERT INTO precios SET FK_Producto = '$IDProducto', FK_Zona = '$pre[Zona]', FK_Presentacion = IFNULL((SELECT ID_Presentacion FROM presentaciones WHERE Nombre = '$pre[Presentacion]' AND FK_Producto = '$IDProducto'), 0), Nombre = '$pre[Nombre]', Precio = '$pre[Precio]', Precio_Mayoreo = '$pre[Precio_Mayoreo]'";
					$error = $omodelo->_insertar($queryPrecio);	

					if ($error == "si") {
						echo "Error precio: ".mysqli_error($omodelo->link); 
					}
				}
			}

			$query1 = "DELETE FROM detalles_impuestos_productos WHERE FK_Producto = '$IDProducto'";
			$error = $omodelo->_insertar($query1);
	
			if ($error == "si") {
				echo "Error 7: ".mysqli_error($omodelo->link);
			}else{
				$impuestos = json_decode($impuestos, true);
				foreach ($impuestos as $im) {
					$query2 = "INSERT INTO detalles_impuestos_productos SET FK_Producto = '$IDProducto', FK_Impuesto = '$im[ID_Impuesto]'";
					$error1 = $omodelo->_insertar($query2);
		
					if ($error1 == "si") {
						echo "Error 8: ".mysqli_error($omodelo->link);
					}
				}
			}
			
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo =  $omodelo->link->real_escape_string($tipo);

		if ($tipo == "EliminarProducto") {
			$IDProducto =  $omodelo->link->real_escape_string($IDProducto);
			
			$query = "SELECT Imagen FROM productos WHERE ID_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					$query1 = "DELETE FROM productos WHERE ID_Producto='$IDProducto'";
					$error = $omodelo->_insertar($query1);

					if ($error == "si") {
						echo "Error 1: " . mysqli_error($omodelo->link);
					} else {
						echo "Correcto";

						if ($row[0]["Imagen"] !="" && file_exists("vistas/assets/archivos/fotosProductos/".$row[0]["Imagen"]."")) {
							unlink("vistas/assets/archivos/fotosProductos/".$row[0]["Imagen"]."");
						}

						$omodelo->movimiento($query1, $_SESSION['user_admin']['ID_Usuario']);
					}
				}
			}
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo =  $omodelo->link->real_escape_string($tipo);
		$arreglo = array();

		if($tipo == 'modificarProducto'){
			$IDProducto =  $omodelo->link->real_escape_string($IDProducto);

			$query = "SELECT ID_Producto, Codigo, Descripcion, FK_Categoria, Clase, Costo, Precio, Precio_Mayoreo, FK_Area, Detalles, Minimo, Maximo, Fecha_Registro, Imagen FROM productos WHERE ID_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$presentaciones = null;
					$queryPresentacion = "SELECT ID_Presentacion, Nombre, Abreviatura, Clave_CFDI FROM presentaciones WHERE FK_Producto = '$IDProducto'";
					$rowPresentacion = $omodelo->_consultar($queryPresentacion);
					$numerofilasPresentacion = $omodelo->numerofilas;

					if($rowPresentacion == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasPresentacion > 0){
							for ($z=0; $z < $numerofilasPresentacion; $z++) { 
								$presentaciones[$z] = array(
									'ID_Presentacion' => $rowPresentacion[$z]["ID_Presentacion"],
									'Nombre' => $rowPresentacion[$z]["Nombre"],
									'Abreviatura' => $rowPresentacion[$z]["Abreviatura"],
									'Clave_CFDI' => $rowPresentacion[$z]["Clave_CFDI"]
								);
							}
						}
					}

					$precios = null;
					$queryPrecio = "SELECT ID_Precio, FK_Zona, zonas.Nombre AS Zona, FK_Presentacion, IFNULL(presentaciones.Nombre, '') AS Presentacion, precios.Nombre AS Nombre, Precio, Precio_Mayoreo FROM precios INNER JOIN zonas ON FK_Zona = ID_Zona LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE precios.FK_Producto = '$IDProducto'";
					$rowPrecio = $omodelo->_consultar($queryPrecio);
					$numerofilasPrecios = $omodelo->numerofilas;

					if($rowPrecio == 'si'){
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasPrecios > 0){
							for ($z=0; $z < $numerofilasPrecios; $z++) { 
								$precios[$z] = array(
									'ID_Precio' => $rowPrecio[$z]["ID_Precio"],
									'FK_Zona' => $rowPrecio[$z]["FK_Zona"],
									'Zona' => $rowPrecio[$z]["Zona"],
									'FK_Presentacion' => $rowPrecio[$z]["FK_Presentacion"],
									'Presentacion' => $rowPrecio[$z]["Presentacion"],
									'Nombre' => $rowPrecio[$z]["Nombre"],
									'Precio' => $rowPrecio[$z]["Precio"],
									'Precio_Mayoreo' => $rowPrecio[$z]["Precio_Mayoreo"]
								);
							}
						}
					}

					$impuestos = null;
					$queryImpuestos = "SELECT ID_Impuesto, Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '$IDProducto'";
					$rowIm = $omodelo->_consultar($queryImpuestos);
					$numerofilasIm = $omodelo->numerofilas;

					if($rowIm == 'si'){
						echo "Error 4: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasIm > 0){
							for ($z=0; $z < $numerofilasIm; $z++) { 
								$impuestos[$z] = array(
									'ID_Impuesto' => $rowIm[$z]['ID_Impuesto'],
									'Nombre' => $rowIm[$z]['Nombre'],
									'Porcentaje' => number_format($rowIm[$z]['Porcentaje'], 2).'%',
									'Clave' => $rowIm[$z]['Clave_CFDI'],
									'Tipo' => $rowIm[$z]['Tipo_Factor'],
									'Clase' => $rowIm[$z]['Clase']	
								);
							}
						}
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
						'Presentaciones' => $presentaciones,
						'Precios' => $precios, 
						'Impuestos' => $impuestos
					);
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'impuestos'){
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Impuesto, Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase, (SELECT COUNT(*) FROM impuestos $busqueda) AS Num FROM impuestos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Impuesto'],
							'Nombre' => $row[$i]['Nombre'],
							'Porcentaje' => number_format($row[$i]['Porcentaje'], 2)."%",
							'Clave' => $row[$i]['Clave_CFDI'],
							'Tipo' => $row[$i]['Tipo_Factor'],
							'Clase' => $row[$i]['Clase'],
							'Acciones' => '<button type="button" class="btn btn-primary btn-sm bSeleccionarIm" attrID="'.$row[$i]['ID_Impuesto'].'">Seleccionar</button>',
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'clavesProdServ'){
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(Clave, Descripcion, Palabras) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Clave, Clave, Descripcion, Palabras, (SELECT COUNT(*) FROM claves_productos_cfdi $busqueda) AS Num FROM claves_productos_cfdi $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Clave'],
							'Clave' => $row[$i]['Clave'],
							'Descripcion' => utf8_encode($row[$i]['Descripcion']),
							'Palabras' => utf8_encode($row[$i]['Palabras']),
							'Acciones' => '<button type="button" class="btn btn-primary btn-sm bSeleccionarClaveProdServ" attrID="'.$row[$i]['ID_Clave'].'">Seleccionar</button>',
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'clavesUnidades'){
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(Clave, Nombre, Simbolo) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Clave, Clave, Nombre, Simbolo, (SELECT COUNT(*) FROM claves_unidades_cfdi $busqueda) AS Num FROM claves_unidades_cfdi $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Clave'],
							'Clave' => $row[$i]['Clave'],
							'Nombre' => utf8_encode($row[$i]['Nombre']),
							'Simbolo' => utf8_encode($row[$i]['Simbolo']),
							'Acciones' => '<button type="button" class="btn btn-primary btn-sm bSeleccionarClaveUnidad" attrID="'.$row[$i]['ID_Clave'].'">Seleccionar</button>',
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'AgregarExistenciaProducto'){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$CantidadExistencia = $omodelo->link->real_escape_string($CantidadExistencia);
			$PresentacionesProducto = $omodelo->link->real_escape_string($PresentacionesProducto);
			$SucursalExistencia = $omodelo->link->real_escape_string($SucursalExistencia);

			$query = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalExistencia' AND FK_Presentacion = '$PresentacionesProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error 1: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					$query1 = "UPDATE inventario SET Cantidad = (Cantidad + $CantidadExistencia) WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalExistencia' AND FK_Presentacion = '$PresentacionesProducto'";
					$error = $omodelo->_insertar($query1);
					
					if ($error == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";

						$omodelo->movimiento($query1, $_SESSION['user_admin']["ID_Usuario"]);
					}
				}else{
					$query1 = "INSERT INTO inventario SET Cantidad = $CantidadExistencia, FK_Producto = '$IDProducto', FK_Sucursal = '$SucursalExistencia', FK_Presentacion = '$PresentacionesProducto'";
					$error = $omodelo->_insertar($query1);
					
					if ($error == "si") {
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";

						$omodelo->movimiento($query1, $_SESSION['user_admin']["ID_Usuario"]);
					}
				}
			}
		}else if($tipo == "ConsultarPresentacionesExistencia"){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);

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
			}

			echo $opciones;
		}
	}
}
?>
