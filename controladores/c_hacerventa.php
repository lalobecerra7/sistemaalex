<?php
class hacerventa {

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
				$busqueda .= "CONCAT(DATE_FORMAT(compras.Fecha_Registro, '%Y-%m-%d'), ID_Compra, FK_Proveedor, FK_Usuario, proveedores.Nombre, proveedores.Empresa, proveedores.Credito, Anticipo, Total, Estatus) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Compra, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, FK_Proveedor, proveedores.Nombre AS Datos, proveedores.Empresa AS Empresa, proveedores.Telefono AS Telefono, proveedores.Razon_Social AS RazonSocial, proveedores.Credito as Credito, Anticipo, Total, Estatus, compras.Fecha_Registro AS Fecha_Registro, (SELECT COUNT(*) FROM compras) AS Num FROM compras INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$SumarCompras = 0;
				for($i=0; $i<$numerofilas; $i++){
					$tipoUsuario = "";$usuario="";$estatus="";$motivocancelada="";$botonCancelar="";$botonPagos="";
					$folio = str_pad($row[$i]['ID_Compra'], 8, "0", STR_PAD_LEFT);

					if ($row[$i]['Estatus'] == "1") {
						$estatus = '<span class="badge rounded-pill bg-success">Completada</span>';
						$botonCancelar = ' <button class="btn btn-warning btn-sm" id="CancelarCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'"><i style="color: white;" class="fas fa-circle-xmark"></i></button> ';
						$SumarCompras += $row[$i]['Total'];
					}else if($row[$i]['Estatus'] == "2"){
						$estatus = '<span class="badge rounded-pill bg-danger">Cancelada</span>';
						//$motivocancelada="Fecha de cancelación: <b>".$row[$i]['Fecha_Cancelada']."</b><br>Motivo: ".$row[$i]['Motivo_Cancelada']; 
					}else if ($row[$i]['Estatus'] == "0") {
						$estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
						$botonCancelar = ' <button class="btn btn-warning btn-sm" id="CancelarCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'"><i style="color: white;" class="fas fa-circle-xmark"></i></button> ';
						$SumarCompras += $row[$i]['Total'];
						$botonPagos = '<button class="btn btn-primary btn-sm"  id="PagoCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'"><i class="fa-solid fa-sack-dollar"></i></button>';
					}

					$botonEliminar = "";
					$botondeCancelar = "";
					//$botonTicket = "";
						$botonEliminar = '<button class="btn btn-danger btn-sm" id="EliminarCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'"><i class="fas fa-trash"></i></button>';

						$botondeCancelar = $botonCancelar;

						

						//$botonTicket = '<button class="btn btn-success" id="ImprimirTicketVenta" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'"><i class="fas fa-print"></i></button>';

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Compra'],
						'Datos' => "Fecha: <b>".$row[$i]['Fecha_Registro']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b>",
						'Proveedor' => 'Nombre: <b>'.$row[$i]['Datos'].'</b><br>Empresa: <b>'.$row[$i]['Empresa'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Razón social: <b>'.$row[$i]['RazonSocial'].'</b>',
						'Total' => 'Anticipo: <b>$'.number_format($row[$i]['Anticipo'], 2).'</b><br>Crédito: <b>$'.number_format($row[$i]['Credito'],2).'</b><br>Total: <b style="font-size: 15px;">$'.number_format($row[$i]['Total'],2 ).'</b><br>',
						'Detalles' => $estatus.'<br>'.$motivocancelada.'<br><button class="btn btn-link btn-sm" id="VerProductosCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'">Ver productos</button><br><button class="btn btn-link btn-sm" id="VerHistorialPagos" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'">Ver pagos</button>',
						'Acciones' => $botonEliminar.' '.$botondeCancelar .' '.$botonPagos,
					);
				}

				$arreglo['totales'] = array(
					'NumRows' => $row[0]['Num'], 
					'Datos' => "",
					'Proveedor' => "Totales",
					'Total' => "<b>$".number_format($SumarCompras, 2)."</b>",
					'Detalles' =>"",
					'Acciones' => "");	
	
			}
		}

		echo json_encode($arreglo);
	}

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$ImportePago=  $omodelo->link->real_escape_string($ImportePagoCompra);
		$Concepto =  $omodelo->link->real_escape_string($ConceptoPago);
		$TipoPago =  $omodelo->link->real_escape_string($TipoDePago);
		$Detalles =  $omodelo->link->real_escape_string($DetallesPago);
		$IdCompra =  $omodelo->link->real_escape_string($IDCompra);
		$fecha = date('Y-m-d H:i:s'); 
		$usuario = $_SESSION['user_admin']['ID_Usuario'];

		$query = "INSERT INTO pagos SET FK_Compra = '$IdCompra', Monto = '$ImportePago', Concepto = '$Concepto', Tipo_Pago = '$TipoPago', Fecha = '$fecha', FK_Usuario = '$usuario', Detalles_Pago = '$Detalles'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$ID = mysqli_insert_id($omodelo->link);

			$status = 1;
			if ($_FILES['ComprobantePago']['size'] > 0 && $_FILES['ComprobantePago']['error'] == 0) {
				$file = $_FILES["ComprobantePago"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosPagos/";

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
				$query2 = "UPDATE pagos SET Archivo = '".$id.'_'.$nombreDoc."' WHERE ID_Pago = '$ID'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$id.'_'.$nombreDoc);

					$query3 = "SELECT compras.Total AS Total, (SELECT SUM(Monto) FROM pagos WHERE FK_Compra = '$IdCompra') AS TotalPagos FROM compras INNER JOIN pagos ON FK_Compra = ID_Compra WHERE ID_Compra = '$IdCompra'";
					$row3 = $omodelo->_consultar($query3);
					$numerofilas3 = $omodelo->numerofilas;

					if($row3 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas3 > 0){
							if($row3[0]['Total'] <= $row3[0]['TotalPagos']){
								$query4 = "UPDATE compras SET Estatus = '1' WHERE ID_Compra = '$IDCompra'";
								$error4 = $omodelo->_insertar($query4);
								if ($error4 == "si") {
									echo "Error 5: ".mysqli_error($omodelo->link);
								}else{
									echo "Correcto";
									$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
								}
							}
						}
					}
				}
			}
			echo ('Correcto');
		}
	}
	
	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST); 
		$fecha = date('Y-m-d H:i:s'); 

		$IDVenta = $omodelo->link->real_escape_string($IDVenta);
		$Regresar = $omodelo->link->real_escape_string($Regresar);
		$Motivo = $omodelo->link->real_escape_string($Motivo);
		$queryRegresar = ', Regreso_Inventario = ""';
		if ($Regresar == "Si") {
			$queryRegresar = ', Regreso_Inventario = "Inventario"';
			$query = "SELECT FK_Producto, Cantidad FROM detalles_venta WHERE FK_Venta = '$IDVenta'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$query1 = "UPDATE productos SET Existencia = (Existencia + '".$row[$i]["Cantidad"]."') WHERE ID_Producto = '".$row[$i]["FK_Producto"]."'";
						$error1 = $omodelo->_insertar($query1);
						if ($error1 == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{

							$CostoProducto = 0;
							$queryCosto = "SELECT Costo FROM productos WHERE ID_Producto = '".$row[$i]["FK_Producto"]."'";
							$rowCosto = $omodelo->_consultar($queryCosto);
							$numerofilasCosto = $omodelo->numerofilas;

							if($rowCosto == 'si'){
								echo "Error: 3".mysqli_error($omodelo->link);
							}else{
								if($numerofilasCosto > 0){
									$CostoProducto = $rowCosto[0]["Costo"];
								}
							}
							$query2 = "INSERT INTO entradas_inventario SET Fecha_Registro = '$fecha', 	FK_producto = '".$row[$i]["FK_Producto"]."', Costo = '$CostoProducto', Cantidad = '".$row[$i]["Cantidad"]."', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', Motivo = 'VentaCancelada'";
							$error2 = $omodelo->_insertar($query2);
							if ($error2 == "si") {
								echo "Error 4: ".mysqli_error($omodelo->link);
							}
						}
					}
				}
			}
		}
		$query = "UPDATE ventas SET Estatus = 'Cancelada', Motivo_Cancelada = '$Motivo', Fecha_Cancelada = '$fecha' $queryRegresar WHERE ID_Venta = '$IDVenta'";
		$error = $omodelo->_insertar($query);
		if ($error == "si") {
			echo "Error 5: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}	
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$IDCompra = $omodelo->link->real_escape_string($IDCompra);

		$query = "DELETE FROM compras WHERE ID_Compra = '$IDCompra'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}	
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "ConsultarCliente") {
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
					$busqueda .= "CONCAT(ID_Cliente, Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Cliente, Nombre, Calle, No_Exterior, No_Interior, Colonia, Ciudad, Codigo_Postal, Estado, Pais, Telefono, Celular, Correo, RFC, (SELECT COUNT(*) FROM clientes) AS Num FROM clientes $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarCompras = 0;
					for($i=0; $i<$numerofilas; $i++){
						$contacto = "";$direccion="";
						
						if ($row[$i]["Calle"] != "") {
							$direccion="Calle: ".$row[$i]["Calle"]."<br>";
						}

						if ($row[$i]["No_Exterior"] != "") {
							$direccion="No. Exterior: ".$row[$i]["No_Exterior"]."<br>";
						}

						if ($row[$i]["No_Interior"] != "") {
							$direccion="No. Interior: ".$row[$i]["No_Interior"]."<br>";
						}

						if ($row[$i]["Colonia"] != "") {
							$direccion="Colonia: ".$row[$i]["Colonia"]."<br>";
						}

						if ($row[$i]["Ciudad"] != "") {
							$direccion="Ciudad: ".$row[$i]["Ciudad"]."<br>";
						}

						if ($row[$i]["Codigo_Postal"] != "") {
							$direccion="Codigo postal: ".$row[$i]["Codigo_Postal"]."<br>";
						}

						if ($row[$i]["Estado"] != "") {
							$direccion="Estado: ".$row[$i]["Estado"]."<br>";
						}

						if ($row[$i]["Pais"] != "") {
							$direccion="Pais: ".$row[$i]["Pais"]."<br>";
						}

						if ($direccion == "") {
							$direccion = "No hay datos registrados";
						}

						if ($row[$i]["Telefono"] != "") {
							$contacto="Teléfono: ".$row[$i]["Telefono"]."<br>";
						}

						if ($row[$i]["Celular"] != "") {
							$contacto="Celular: ".$row[$i]["Celular"]."<br>";
						}

						if ($row[$i]["Correo"] != "") {
							$contacto="Correo electrónico: ".$row[$i]["Correo"]."<br>";
						}

						if ($contacto == "") {
							$contacto = "No hay datos registrados";
						}

						

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Cliente'],
							'Nombre' => $row[$i]['Nombre'],
							'Direccion' => $direccion,
							'RFC' => $row[$i]['RFC'],
							'Contacto' => $contacto
						);
					}
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarProductos") {
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
					$busqueda .= "CONCAT(ID_Producto, Codigo, productos.Descripcion, presentaciones.Nombre, areas.Nombre, inventario.Cantidad, productos.Precio, productos.Precio_Mayoreo) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, presentaciones.ID_Presentacion  AS IDPresentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, Poner_Unidad, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Precio_Mayoreo_General, areas.Nombre AS NombreArea, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia, detalles_productos.Costo AS Costo, detalles_productos.Minimo AS Minimo, detalles_productos.Maximo AS Maximo, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos LEFT JOIN detalles_productos ON detalles_productos.FK_Producto = ID_Producto AND detalles_productos.FK_Sucursal = '$sucursal' LEFT JOIN areas ON FK_Area = ID_Area LEFT JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' LEFT JOIN presentaciones ON inventario.FK_Presentacion = ID_Presentacion  $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						if ($row[$i]['Existencia'] == "") {
							$row[$i]['Existencia'] = 0;
						}

						if ($row[$i]['NombreArea'] == "") {
							$row[$i]['NombreArea'] = "No hay area registrada";
						}

						$nombrePresentacion = "";
						if ($row[$i]['Presentacion'] != "") {
							$nombrePresentacion = $row[$i]['Presentacion']."(".$row[$i]['AbreviaturaPresentacion'].")";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Producto' => $row[$i]['Descripcion']."<br>Codigo: <b id='CodigoProducto'>".$row[$i]['Codigo']."</b>",
							'Presentacion' =>"<span hidden id='IdPresentacionProd'>".$row[$i]['IDPresentacion']."</span>".$nombrePresentacion,
							'Precio' => $row[$i]['Precio_General'],
							'Mayoreo' => $row[$i]['Precio_Mayoreo_General'],
							'Existencia' => $row[$i]['Existencia'],
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "AgregarProducto"){
			$queryPresentacion = "";
			if (isset($presentacion) && $presentacion != "") {
				$queryPresentacion = "AND inventario.FK_Presentacion = '$presentacion'";
			}
			$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, presentaciones.Nombre AS Presentacion, presentaciones.ID_Presentacion AS IDPresentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, Clase, Poner_Unidad, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Precio_Mayoreo_General, areas.Nombre AS NombreArea, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia, detalles_productos.Costo AS Costo, detalles_productos.Minimo AS Minimo, detalles_productos.Maximo AS Maximo FROM productos LEFT JOIN detalles_productos ON detalles_productos.FK_Producto = ID_Producto AND detalles_productos.FK_Sucursal = '$sucursal' LEFT JOIN unidades ON FK_Unidad = ID_Unidad LEFT JOIN areas ON FK_Area = ID_Area LEFT JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' LEFT JOIN presentaciones ON inventario.FK_Presentacion = ID_Presentacion WHERE Tipo = 1 AND Codigo = '$codigo' $queryPresentacion";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$subarreglo = null;
					$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre, impuestos.Porcentaje FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row[0]["ID_Producto"]."'";
					$rowI = $omodelo->_consultar($queryI);
					$numerofilasI = $omodelo->numerofilas;

					if($rowI == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasI > 0){
							for ($i=0; $i < $numerofilasI; $i++) { 
								$subarreglo[$i] = array(
									'ID_Impuesto' => $rowI[$i]["FK_Impuesto"],
									'Nombre' => $rowI[$i]["Nombre"],
									'Porcentaje' => $rowI[$i]["Porcentaje"]
								);
							}
						}
					}
					$NombrePresentacion = "";
					if ($row[0]["Presentacion"] != "") {
						$abreviatura = "";
						if ($row[0]["AbreviaturaPresentacion"] != "") {
							$abreviatura = "(".$row[0]["AbreviaturaPresentacion"].")";
						}
						$NombrePresentacion = $row[0]["Presentacion"].$abreviatura;
					}

					$arreglo = array(
						'ID_Producto' => $row[0]["ID_Producto"],
						'Codigo' => $row[0]["Codigo"],
						'Descripcion' => $row[0]["Descripcion"],
						'Presentacion' => $NombrePresentacion,
						'Clase' => $row[0]["Clase"],
						'IDPresentacion' => $row[0]["IDPresentacion"],
						'Poner_Unidad' => $row[0]["Poner_Unidad"],
						'Costo_General' => $row[0]["Costo_General"],
						'Precio_General' => $row[0]["Precio_General"],
						'Precio_Mayoreo_General' => $row[0]["Precio_Mayoreo_General"],
						'NombreArea' => $row[0]["NombreArea"],
						'Detalles' => $row[0]["Detalles"],
						'Minimo_General' => $row[0]["Minimo_General"],
						'Maximo_General' => $row[0]["Maximo_General"],
						'Fecha_Registro' => $row[0]["Fecha_Registro"],
						'Existencia' => $row[0]["Existencia"],
						'Costo' => $row[0]["Costo"],
						'Minimo' => $row[0]["Minimo"],
						'Maximo' => $row[0]["Maximo"],
						'Impuestos' => $subarreglo
					);
					echo json_encode($arreglo);
				}else{
					echo "No encontrado";
				}
			}
		}
		/*if($tipo == 'productos'){
			$IDCompra = $omodelo->link->real_escape_string($IDCompra);
			$tabla = "";
			$query = "SELECT ID_Detalle_Compra, FK_Compra, FK_Producto, productos.Codigo, productos.Descripcion, productos.Imagen, detalle_compras.Costo AS Costo, Cantidad, Subtotal FROM detalle_compras INNER JOIN productos ON FK_Producto = ID_Producto WHERE FK_Compra = '$IDCompra'";
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

						$tabla .= "
							<tr>
								<td >".$imagen.$row[$i]["Codigo"]."</td>
								<td >".$row[$i]["Descripcion"]."</td>
								<td style='vertical-align: middle;'>$".number_format($row[$i]["Costo"], 2)."</td>
								<td style='vertical-align: middle;'>".number_format($row[$i]["Cantidad"], 2)."</td>
								<td style='vertical-align: middle;'>$".number_format($row[$i]["Subtotal"], 2)."</td>
							</tr>
						";
					}
				}
			}

			echo $tabla;
		}else if($tipo == 'pago'){
			$IDCompra = $omodelo->link->real_escape_string($IDCompra);
			$query = "SELECT FK_Proveedor, proveedores.Nombre AS Proveedor, compras.Total AS Total, (SELECT SUM(Monto) FROM pagos WHERE FK_Compra = '$IDCompra') AS TotalPagos FROM compras INNER JOIN proveedores ON ID_Proveedor = FK_Proveedor INNER JOIN pagos ON FK_Compra = ID_Compra WHERE ID_Compra = '$IDCompra'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}
		}else if($tipo == 'historialPagos'){
			$IDCompra = $omodelo->link->real_escape_string($IDCompra);
			$tabla = "";
			$imagen = '';
			$query = "SELECT ID_Pago, FK_Compra, Concepto, Monto, Tipo_Pago, Fecha, Detalles_Pago, Archivo FROM pagos WHERE FK_Compra = '$IDCompra'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						if ($row[$i]["Archivo"] != "") {
							if($row[$i]["Archivo"] != "" && file_exists("vistas/assets/archivos/fotosPagos/".$row[$i]["Archivo"])){
								$imagen = '<a href="vistas/assets/archivos/fotosPagos/'.$row[$i]["Archivo"].'" data-fancybox="images">
										<div style="background-image: url('."'".'vistas/assets/archivos/fotosPagos/'.$row[$i]["Archivo"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;">
										</div>
									</a><br>';
							}	
						}

						$tabla .= "
							<tr>
								<td >".$row[$i]["Fecha"]."</td>
								<td >".$row[$i]["Concepto"]."</td>
								<td >".$row[$i]["Tipo_Pago"]."</td>
								<td style='vertical-align: middle;'>$".number_format($row[$i]["Monto"], 2)."</td>
								<td >".$row[$i]["Detalles_Pago"]."</td>
								<td >".$imagen."</td>
							</tr>
						";
					}
				}
			}

			echo $tabla;
		}*/
	}
}
?>
