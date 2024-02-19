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

		$query = "SELECT ID_Precio, productos.Descripcion AS Producto, (SELECT Nombre FROM zonas WHERE FK_Zona = ID_Zona) AS NombreZonaPrecio, IFNULL((SELECT GROUP_CONCAT(Empresa) FROM detalles_proveedores_productos INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor WHERE FK_Producto = ID_Producto), '') AS Proveedores, IFNULL(presentaciones.Nombre, '') AS Presentacion, precios.Nombre AS Nombre, precios.Precio AS Precio, precios.Precio_Mayoreo AS Mayoreo, (SELECT COUNT(*) FROM precios INNER JOIN productos ON precios.FK_Producto = ID_Producto INNER JOIN zonas ON FK_Zona = ID_Zona LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion $busqueda) AS Num FROM precios INNER JOIN productos ON precios.FK_Producto = ID_Producto INNER JOIN zonas ON FK_Zona = ID_Zona LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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
						'Mayoreo' => '<span class="dinero">'.$row[$i]['Mayoreo'].'</span>'
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
		}
	}
}
?>