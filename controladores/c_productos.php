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
				$busqueda .= "CONCAT(Codigo, productos.Descripcion, Detalles, Referencia) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Producto, Codigo, Referencia, productos.Descripcion, Tipo, Costo, Precio, Precio_Mayoreo, Detalles, Imagen, areas.Nombre, areas.Descripcion AS DescripcionArea, areas.Nivel, (SELECT COUNT(*) FROM productos LEFT JOIN areas ON FK_Area = ID_Area $busqueda) AS Num, ((SELECT COUNT(*) FROM detalles_ventas WHERE FK_Producto = ID_Producto) + (SELECT COUNT(*) FROM detalle_compras WHERE FK_Producto = ID_Producto)) AS numProd FROM productos LEFT JOIN areas ON FK_Area = ID_Area $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

					$presentacion = "<b>Presentaciones:</b><br>";
					$queryPresentacion = "SELECT ID_Presentacion, FK_Producto, Nombre, Abreviatura FROM presentaciones WHERE FK_Producto = '".$row[$i]['ID_Producto']."'";
					$rowPresentacion = $omodelo->_consultar($queryPresentacion);
					$numerofilasPresentacion = $omodelo->numerofilas;
					if ($numerofilasPresentacion > 0) {
						for ($z=0; $z < $numerofilasPresentacion; $z++) { 
							$presentacion .= $rowPresentacion[$z]["Nombre"].'('.$rowPresentacion[$z]["Abreviatura"].")<br>";
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

					$botonModificarExistencia = '';
					if ($omodelo->permisos() == 'Administrador') {
						$botonModificarExistencia = '<button class="btn btn-success btn-sm mb-1 ModificarExistencia" title="Modificar existencias" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-cog"></i></button>';
					}

					$referenciaDeiman = "";
					if ($row[$i]['Referencia'] != "") {
						$referenciaDeiman = "Referencia(Deiman): <b>".$row[$i]['Referencia']."</b><br>";
					}
					
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Producto'],
						'Codigo' => $imagen."<b>".$row[$i]['Codigo']."</b>",
						'Descripcion' => $row[$i]['Descripcion'],
						'Costo' => '<b class="dinero">$'.number_format($row[$i]['Costo'], 2).'</b>',
						'Precio' => 'General: <b class="dinero">$'.number_format($row[$i]['Precio'], 2).'</b><br>'.$precios,
						'Detalles' => $referenciaDeiman.$area."Detalles: ".$row[$i]['Detalles']."<br>".$presentacion,
						'Acciones' => $botonPermisosModificar.' '.$botonPermisosEliminar.' '.$botonAumentarExistencias.' '.$botonModificarExistencia,
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
		$Costo = $omodelo->link->real_escape_string($CostoProducto);
		$Precio = $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo = $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area = $omodelo->link->real_escape_string($Area);
		$Detalles = $omodelo->link->real_escape_string($DetallesProducto);
		$ImporteProducto = $omodelo->link->real_escape_string($ImporteProducto);
		$claveProdServ = $omodelo->link->real_escape_string($claveProdServ);
		$claveUnidadProd = $omodelo->link->real_escape_string($claveUnidadProd);
		$unidadProd = $omodelo->link->real_escape_string($unidadProd);
		$abreUnudadProd = $omodelo->link->real_escape_string($abreUnudadProd);
		$objImProducto = $omodelo->link->real_escape_string($objImProducto);
		$bloqueado = $omodelo->link->real_escape_string($bloqueado);
		$ReferenciaProducto = $omodelo->link->real_escape_string($ReferenciaProducto);

		$query = "INSERT INTO productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion',  FK_Categoria = '$Categoria', Tipo = '1', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Fecha_Registro = '$Fecha', Clave_ProdServ_CFDI = '$claveProdServ', Clave_Unidad_CFDI = '$claveUnidadProd', Nombre_Unidad = '$unidadProd', Abreviatura_Unidad = '$abreUnudadProd', Objeto_Impuesto_CFDI = '$objImProducto', Importe = '$ImporteProducto', Bloqueado = '$bloqueado', Referencia = '$ReferenciaProducto'";
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
				$pres['Costo'] = $omodelo->link->real_escape_string($pres['Costo']);
				$pres['Importe'] = $omodelo->link->real_escape_string($pres['Importe']);
				$pres['Codigo'] = $omodelo->link->real_escape_string($pres['Codigo']);
				$pres['Referencia'] = $omodelo->link->real_escape_string($pres['Referencia']);

				$queryPresentacion = "INSERT INTO presentaciones SET FK_Producto = '$id', Nombre = '$pres[Nombre]', Abreviatura = '$pres[Abreviatura]', Clave_CFDI = '$pres[Clave]', Costo = '$pres[Costo]', Importe = '$pres[Importe]', Codigo = '$pres[Codigo]', Referencia = '$pres[Referencia]'";
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

			$proveedores = json_decode($proveedores, true);
			foreach ($proveedores as $pro) {
				$pro['ID_Proveedor'] = $omodelo->link->real_escape_string($pro['ID_Proveedor']);

				$query1 = "INSERT INTO detalles_proveedores_productos SET FK_Producto = '$id', FK_Proveedor = '$pro[ID_Proveedor]'";
				$error = $omodelo->_insertar($query1);
	
				if ($error == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}
			}

			$stock = json_decode($stock, true);
			foreach ($stock as $sto) {
				$sto['ID_Sucursal'] = $omodelo->link->real_escape_string($sto['ID_Sucursal']);
				$sto['Presentacion'] = $omodelo->link->real_escape_string($sto['Presentacion']);
				$sto['Minimo'] = $omodelo->link->real_escape_string($sto['Minimo']);
				$sto['Maximo'] = $omodelo->link->real_escape_string($sto['Maximo']);

				$query1 = "INSERT INTO stock_productos SET FK_Producto = '$id', FK_Presentacion = IFNULL((SELECT ID_Presentacion FROM presentaciones WHERE Nombre = '$sto[Presentacion]' AND FK_Producto = '$id'), 0), FK_Sucursal = '$sto[ID_Sucursal]', Minimo = '$sto[Minimo]', Maximo = '$sto[Maximo]'";
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
		$Costo = $omodelo->link->real_escape_string($CostoProducto);
		$Precio = $omodelo->link->real_escape_string($PrecioProducto);
		$PrecioMayoreo = $omodelo->link->real_escape_string($PrecioMayoreo);
		$Area = $omodelo->link->real_escape_string($Area);
		$Detalles = $omodelo->link->real_escape_string($DetallesProducto);
		$ImporteProducto = $omodelo->link->real_escape_string($ImporteProducto);
		$claveProdServ = $omodelo->link->real_escape_string($claveProdServ);
		$claveUnidadProd = $omodelo->link->real_escape_string($claveUnidadProd);
		$unidadProd = $omodelo->link->real_escape_string($unidadProd);
		$abreUnudadProd = $omodelo->link->real_escape_string($abreUnudadProd);
		$objImProducto = $omodelo->link->real_escape_string($objImProducto);
		$bloqueado = $omodelo->link->real_escape_string($bloqueado);
		$ReferenciaProducto = $omodelo->link->real_escape_string($ReferenciaProducto);

		$query = "UPDATE productos SET Codigo = '$CodigoBarras', Descripcion = '$Descripcion', FK_Categoria = '$Categoria', Costo = '$Costo', Precio = '$Precio', Precio_Mayoreo = '$PrecioMayoreo', FK_Area = '$Area', Detalles = '$Detalles', Clave_ProdServ_CFDI = '$claveProdServ', Clave_Unidad_CFDI = '$claveUnidadProd', Nombre_Unidad = '$unidadProd', Abreviatura_Unidad = '$abreUnudadProd', Objeto_Impuesto_CFDI = '$objImProducto', Importe = '$ImporteProducto', Bloqueado = '$bloqueado', Referencia = '$ReferenciaProducto' WHERE ID_Producto = '$IDProducto'";
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
					$pres['Costo'] = $omodelo->link->real_escape_string($pres['Costo']);
					$pres['Importe'] = $omodelo->link->real_escape_string($pres['Importe']);
					$pres['Codigo'] = $omodelo->link->real_escape_string($pres['Codigo']);
					$pres['Referencia'] = $omodelo->link->real_escape_string($pres['Referencia']);

					$queryPresentacion = "INSERT INTO presentaciones SET ID_Presentacion = '$pres[ID_Presentacion]',FK_Producto = '$IDProducto', Nombre = '$pres[Nombre]', Abreviatura = '$pres[Abreviatura]', Clave_CFDI = '$pres[Clave]', Costo = '$pres[Costo]', Importe = '$pres[Importe]', Codigo = '$pres[Codigo]', Referencia = '$pres[Referencia]'";
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

			$query1 = "DELETE FROM detalles_proveedores_productos WHERE FK_Producto = '$IDProducto'";
			$error = $omodelo->_insertar($query1);
	
			if ($error == "si") {
				echo "Error 8: ".mysqli_error($omodelo->link);
			}else{
				$proveedores = json_decode($proveedores, true);
				foreach ($proveedores as $pro) {
					$pro['ID_Proveedor'] = $omodelo->link->real_escape_string($pro['ID_Proveedor']);

					$query1 = "INSERT INTO detalles_proveedores_productos SET FK_Producto = '$IDProducto', FK_Proveedor = '$pro[ID_Proveedor]'";
					$error = $omodelo->_insertar($query1);
		
					if ($error == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}
				}
			}

			$query1 = "DELETE FROM stock_productos WHERE FK_Producto = '$IDProducto'";
			$error = $omodelo->_insertar($query1);
	
			if ($error == "si") {
				echo "Error 9: ".mysqli_error($omodelo->link);
			}else{
				$stock = json_decode($stock, true);
				foreach ($stock as $sto) {
					$sto['ID_Sucursal'] = $omodelo->link->real_escape_string($sto['ID_Sucursal']);
					$sto['Presentacion'] = $omodelo->link->real_escape_string($sto['Presentacion']);
					$sto['Minimo'] = $omodelo->link->real_escape_string($sto['Minimo']);
					$sto['Maximo'] = $omodelo->link->real_escape_string($sto['Maximo']);

					$query1 = "INSERT INTO stock_productos SET FK_Producto = '$IDProducto', FK_Presentacion = IFNULL((SELECT ID_Presentacion FROM presentaciones WHERE Nombre = '$sto[Presentacion]' AND FK_Producto = '$IDProducto'), 0), FK_Sucursal = '$sto[ID_Sucursal]', Minimo = '$sto[Minimo]', Maximo = '$sto[Maximo]'";
					$error = $omodelo->_insertar($query1);
		
					if ($error == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
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

			$query = "SELECT ID_Producto, Codigo, Descripcion, Tipo, FK_Categoria, Costo, Precio, Precio_Mayoreo, FK_Area, Detalles, importe, Fecha_Registro, Imagen, Clave_ProdServ_CFDI, Clave_Unidad_CFDI, Nombre_Unidad, Abreviatura_Unidad, Objeto_Impuesto_CFDI, Bloqueado, Referencia FROM productos WHERE ID_Producto = '$IDProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$presentaciones = null;
					$queryPresentacion = "SELECT ID_Presentacion, Nombre, Codigo, Abreviatura, Costo, Importe, Clave_CFDI, Referencia, IFNULL((SELECT COUNT(*) FROM detalles_ventas WHERE FK_Presentacion = ID_Presentacion) + (SELECT COUNT(*) FROM detalle_compras WHERE FK_Presentacion = ID_Presentacion), 0) AS NumProd FROM presentaciones WHERE FK_Producto = '$IDProducto'";
					$rowPresentacion = $omodelo->_consultar($queryPresentacion);
					$numerofilasPresentacion = $omodelo->numerofilas;

					if($rowPresentacion == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasPresentacion > 0){
							for ($z=0; $z < $numerofilasPresentacion; $z++) { 
								$presentaciones[$z] = array(
									'ID_Presentacion' => $rowPresentacion[$z]['ID_Presentacion'],
									'Nombre' => $rowPresentacion[$z]['Nombre'],
									'Codigo' => $rowPresentacion[$z]['Codigo'],
									'Referencia' => $rowPresentacion[$z]['Referencia'],
									'Abreviatura' => $rowPresentacion[$z]['Abreviatura'],
									'Clave_CFDI' => $rowPresentacion[$z]['Clave_CFDI'],
									'NumProd' => $rowPresentacion[$z]['NumProd'],
									'Costo' => $rowPresentacion[$z]['Costo'],
									'Importe' => $rowPresentacion[$z]['Importe']
								);
							}
						}
					}

					$precios = null;
					$queryPrecio = "SELECT IFNULL(presentaciones.Costo, 0) AS CostoPre, 
					productos.Costo AS CostoPro, 
					IF(IFNULL(presentaciones.Costo, 0) = 0, IFNULL(productos.Costo, 0), IFNULL(presentaciones.Costo, 0)) AS CostoGeneral, 
					(((precios.Precio / IF(IFNULL(presentaciones.Costo, 0) = 0, IFNULL(productos.Costo, 0), IFNULL(presentaciones.Costo, 0))) * 100)-100) AS Margen, 
					ID_Precio, FK_Zona, zonas.Nombre AS Zona, FK_Presentacion, IFNULL(presentaciones.Nombre, '') AS Presentacion, precios.Nombre AS Nombre, precios.Precio FROM precios INNER JOIN zonas ON FK_Zona = ID_Zona INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE precios.FK_Producto = '$IDProducto'";
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
									'Margen' => number_format($rowPrecio[$z]['Margen'], 0).'%',
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

					$proveedores = null;
					$queryProveedores = "SELECT ID_Detalle_Proveedor, FK_Proveedor, Nombre, Empresa FROM detalles_proveedores_productos INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor WHERE FK_Producto = '$IDProducto'";
					$rowProve = $omodelo->_consultar($queryProveedores);
					$numerofilasProve = $omodelo->numerofilas;

					if($rowProve == 'si'){
						echo "Error 5: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasProve > 0){
							for ($z=0; $z < $numerofilasProve; $z++) { 
								$proveedores[$z] = array(
									'ID_Detalle_Proveedor' => $rowProve[$z]['ID_Detalle_Proveedor'],
									'FK_Proveedor' => $rowProve[$z]['FK_Proveedor'],
									'Nombre' => $rowProve[$z]['Nombre'],
									'Empresa' => $rowProve[$z]['Empresa']	
								);
							}
						}
					}

					$stocks = null;
					$queryStock = "SELECT ID_Stock, FK_Presentacion, FK_Sucursal, sucursales.Nombre AS Sucursal, IFNULL(presentaciones.Nombre, '') AS Presentacion, Minimo, Maximo FROM stock_productos INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE stock_productos.FK_Producto = '$IDProducto'";
					$rowStock = $omodelo->_consultar($queryStock);
					$numerofilasStock = $omodelo->numerofilas;

					if($rowStock == 'si'){
						echo "Error 6: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasStock > 0){
							for ($z=0; $z < $numerofilasStock; $z++) { 
								$stocks[$z] = array(
									'ID_Stock' => $rowStock[$z]['ID_Stock'],
									'FK_Presentacion' => $rowStock[$z]['FK_Presentacion'],
									'FK_Sucursal' => $rowStock[$z]['FK_Sucursal'],
									'Sucursal' => $rowStock[$z]['Sucursal'],
									'Presentacion' => $rowStock[$z]['Presentacion'],
									'Minimo' => $rowStock[$z]['Minimo'],
									'Maximo' => $rowStock[$z]['Maximo']	
								);
							}
						}
					}

					$arreglo = array(
						'ID_Producto' => $row[0]['ID_Producto'],
						'Codigo' => $row[0]["Codigo"],
						'Referencia' => $row[0]["Referencia"],
						'Descripcion' => $row[0]["Descripcion"],
						'FK_Categoria' => $row[0]["FK_Categoria"],
						'Costo' => $row[0]["Costo"],
						'Precio' => $row[0]["Precio"],
						'Precio_Mayoreo' => $row[0]["Precio_Mayoreo"],
						'FK_Area' => $row[0]["FK_Area"],
						'Detalles' => $row[0]["Detalles"],
						'Fecha_Registro' => $row[0]["Fecha_Registro"],
						'Imagen' => $row[0]["Imagen"],
						'Importe' => $row[0]['importe'],  
						'Clave_ProdServ_CFDI' => $row[0]['Clave_ProdServ_CFDI'], 
						'Clave_Unidad_CFDI' => $row[0]['Clave_Unidad_CFDI'], 
						'Nombre_Unidad' => $row[0]['Nombre_Unidad'], 
						'Abreviatura_Unidad' => $row[0]['Abreviatura_Unidad'], 
						'Objeto_Impuesto_CFDI' => $row[0]['Objeto_Impuesto_CFDI'],
						'Presentaciones' => $presentaciones,
						'Precios' => $precios, 
						'Proveedores' => $proveedores,
						'Stocks' => $stocks,
						'Impuestos' => $impuestos,
						'Bloqueado' => $row[0]['Bloqueado']
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
		}else if($tipo == "ConsultarValidezCodigo"){
			$Codigo = $omodelo->link->real_escape_string($Codigo);

			$query = "SELECT Codigo FROM presentaciones WHERE Codigo = '$Codigo'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					echo "NoValido";
				}else{
					$query = "SELECT Codigo FROM productos WHERE Codigo = '$Codigo'";
					$row = $omodelo->_consultar($query);
					$numerofilas = $omodelo->numerofilas;
					if ($row == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
					} else {
						if ($numerofilas > 0) {
							echo "NoValido2";
						}else{
							echo "Valido";
						}
					}
				}
			}
		}else if($tipo == "ConsultarValidezReferencia"){
			$Referencia = $omodelo->link->real_escape_string($Referencia);

			$query = "SELECT Referencia FROM presentaciones WHERE Referencia = '$Referencia'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					echo "NoValido";
				}else{
					$query2 = "SELECT Referencia FROM productos WHERE Referencia = '$Referencia'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;
					if ($row2 == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
					} else {
						if ($numerofilas2 > 0) {
							echo "NoValido2";
						}else{
							echo "Valido";
						}
					}
				}
			}
		}else if($tipo == "ConsultarContraAdmin"){
			$contrasena = $omodelo->link->real_escape_string($contrasena);
			if ($omodelo->permisos() == 'Administrador'){
				if ($contrasena == "admin1154") {
					echo "Correcto";
				}else{
					echo "Error";
				}
			}else{
				echo "Error";
			}
		}else if($tipo == 'ModificarExistenciaProducto'){
			/*$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$PresentacionesProductoMod = $omodelo->link->real_escape_string($PresentacionesProductoMod);
			$SucursalExistenciaMod = $omodelo->link->real_escape_string($SucursalExistenciaMod);
			$ExistenciaProductoMod = $omodelo->link->real_escape_string($ExistenciaProductoMod);

			$query1 = "UPDATE inventario SET Cantidad = $ExistenciaProductoMod WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalExistenciaMod' AND FK_Presentacion = '$PresentacionesProductoMod'";
			$error = $omodelo->_insertar($query1);
					
			if ($error == "si") {
				echo "Error 2: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query1, $_SESSION['user_admin']["ID_Usuario"]);
			}*/


			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$PresentacionesProductoMod = $omodelo->link->real_escape_string($PresentacionesProductoMod);
			$SucursalExistenciaMod = $omodelo->link->real_escape_string($SucursalExistenciaMod);
			$ExistenciaProductoMod = $omodelo->link->real_escape_string($ExistenciaProductoMod);

			$query = "SELECT ID_Inventario FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalExistenciaMod' AND FK_Presentacion = '$PresentacionesProductoMod'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error 1: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					$query1 = "UPDATE inventario SET Cantidad = $ExistenciaProductoMod WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$SucursalExistenciaMod' AND FK_Presentacion = '$PresentacionesProductoMod'";
					$error = $omodelo->_insertar($query1);
					
					if ($error == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";

						$omodelo->movimiento($query1, $_SESSION['user_admin']["ID_Usuario"]);
					}
				}else{
					$query1 = "INSERT INTO inventario SET Cantidad = $ExistenciaProductoMod, FK_Producto = '$IDProducto', FK_Sucursal = '$SucursalExistenciaMod', FK_Presentacion = '$PresentacionesProductoMod'";
					$error = $omodelo->_insertar($query1);
					
					if ($error == "si") {
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";

						$omodelo->movimiento($query1, $_SESSION['user_admin']["ID_Usuario"]);
					}
				}
			}

		}else if($tipo == "ConsultarExistenciaActual"){
			$IDProducto = $omodelo->link->real_escape_string($IDProducto);
			$Presentacion = $omodelo->link->real_escape_string($presentacion);
			$Sucursal = $omodelo->link->real_escape_string($sucursal);

			$query = "SELECT Cantidad FROM inventario WHERE FK_Producto = '$IDProducto' AND FK_Sucursal = '$Sucursal' AND FK_Presentacion = '$Presentacion'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					echo $row[0]["Cantidad"];
				}else{
					echo 0;
				}
			}
		}else if($tipo == "ConsultarAlertas"){
			$cantidadAlertas = 0;
			$query = "SELECT ID_Stock, stock_productos.FK_Producto, Descripcion, Nombre_Unidad, FK_Presentacion, FK_Sucursal, Minimo, Maximo, Nombre, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Producto = stock_productos.FK_Producto AND FK_Presentacion = stock_productos.FK_Presentacion AND FK_Sucursal = stock_productos.FK_Sucursal), 0) AS Cantidad FROM stock_productos INNER JOIN productos ON stock_productos.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				if ($numerofilas > 0) {
					$cantidadAlertas = $numerofilas;
				}
			}
			echo $cantidadAlertas;
		}else if($tipo == "InsertarNuevoPrecio3"){
			$referencia = $omodelo->link->real_escape_string($ReferenciaPrecioNuevoProducto);
			$precio3Neto = $omodelo->link->real_escape_string($Precio3PrecioNuevoProducto);
			$NombreImpuesto = $omodelo->link->real_escape_string($ImpuestosPrecioNuevoProducto);
			$AumentoPrecioNuevo = $omodelo->link->real_escape_string($AumentoPrecioNuevoProducto);
			$zona = $omodelo->link->real_escape_string($ZonaPrecioNuevoProducto);
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
