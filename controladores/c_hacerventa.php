<?php
class hacerventa {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		
	}

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		if ($tipo == "GuardarPedido") {
			if (!isset($cliente) || $cliente == "") {
				$cliente = 1;
			}
			$query = "INSERT INTO pedidos SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Sucursal = '$idsucursal', FK_Cliente = '$cliente', Descuento = '$sumadescuento', Total = '$total', Fecha_Registro = '$fecha', Fecha_Entrega = '$fechaEntrega'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$idPedido = mysqli_insert_id($omodelo->link);

				$productos = json_decode($productos, true);
				foreach ($productos as $fila) {

					$nombreProducto = '';
					$query2 = "SELECT Descripcion FROM productos WHERE ID_Producto = '$fila[0]'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$nombreProducto = $row2[0]["Descripcion"];
						}
					}
					$query = "INSERT INTO detalles_pedidos SET FK_Pedido = '$idPedido', FK_Producto = '$fila[0]', FK_Presentacion = '$fila[1]', Descripcion = '$nombreProducto', Precio = '$fila[3]', Cantidad = '$fila[2]', Descuento = '$fila[4]', Total = '$fila[6]'";
					$error = $omodelo->_insertar($query);

					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}

					$idDetallePedido = mysqli_insert_id($omodelo->link);

					$separar = explode("~", $fila[5]);
					for ($i=0; $i < sizeof($separar) - 1; $i++) { 
						$datos = explode(",", $separar[$i]);

						$queryImp = "INSERT INTO detalles_impuestos_pedidos SET FK_Detalle_Pedido = '$idDetallePedido', Tipo_Impuesto_CFDI = '$datos[5]', Impuesto_CFDI = '$datos[1]', Clave_CFDI = '$datos[3]',	Tipo_Factor_CFDI = '$datos[4]', Tasa_Cuota_CFDI = '".($datos[2])."'";
						$errorImp = $omodelo->_insertar($queryImp);

						if ($errorImp == "si") {
							echo "Error impuestos: ".mysqli_error($omodelo->link);
						}

					}
				}
				echo "Correcto";
			}
		}else if ($tipo == "RealizarVenta") {
			if (!isset($cliente) || $cliente == "") {
				$cliente = 1;
			}
			if ($Importe == "" || $Importe == 0) {
				$Importe = $total;
			}
			$cambio = $Importe - $total;
			$query = "INSERT INTO ventas SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Sucursal = '$idsucursal', FK_Cliente = '$cliente', Descuento = '$sumadescuento', Total = '$total', Tipo_Pago = '$TipoPago', Pago = '$Importe', Cambio = '$cambio', Fecha_Registro = '$fecha', Estatus = 'Completada'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$idVenta = mysqli_insert_id($omodelo->link);

				$productos = json_decode($productos, true);
				foreach ($productos as $fila) {

					$nombreProducto = '';
					$query2 = "SELECT Descripcion, importe FROM productos WHERE ID_Producto = '$fila[0]'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$nombreProducto = $row2[0]["Descripcion"];
						}
					}
					$idDetalleVenta = "";
					$query = "INSERT INTO detalles_ventas SET FK_Venta = '$idVenta', FK_Producto = '$fila[0]', FK_Presentacion = '$fila[1]', Descripcion = '$nombreProducto', Precio = '$fila[3]', Cantidad = '$fila[2]', Descuento = '$fila[4]', Total = '$fila[6]'";
					$error = $omodelo->_insertar($query);

					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						$idDetalleVenta = mysqli_insert_id($omodelo->link);
					}

					if ($row2[0]["importe"] > 0) {
						$totalImporte = $fila[2] * $row2[0]["importe"];

						$query3 = "SELECT Cantidad FROM importes WHERE FK_Producto = '$fila[0]' AND FK_Venta = '".$idVenta."'";
						$row3 = $omodelo->_consultar($query3);
						$numerofilas3 = $omodelo->numerofilas;

						if($row3 == 'si'){
							echo "Error 3: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas3 > 0){
								$CalculoTotalImporte = ($row3[0]["Cantidad"] + $fila[2]) * $row2[0]["importe"];
								$queryImportes = "UPDATE importes SET Cantidad = (Cantidad + $fila[2]), Total = '$CalculoTotalImporte' WHERE FK_Venta = '$idVenta' AND FK_Producto = '$fila[0]'";
								$errorImportes = $omodelo->_insertar($queryImportes);

								if ($errorImportes == "si") {
									echo "Error importes: ".mysqli_error($omodelo->link);
								}		
							}else{
								$queryImportes = "INSERT INTO importes SET FK_Venta = '$idVenta', FK_Producto = '$fila[0]', Cantidad = '$fila[2]', Importe = '".$row2[0]["importe"]."', Total = '$totalImporte', Estatus = 'Se debe'";
								$errorImportes = $omodelo->_insertar($queryImportes);

								if ($errorImportes == "si") {
									echo "Error importes: ".mysqli_error($omodelo->link);
								}	
							}
						}
					}

					$separar = explode("~", $fila[5]);
					for ($i=0; $i < sizeof($separar) - 1; $i++) { 
						$datos = explode(",", $separar[$i]);

						$queryImp = "INSERT INTO detalles_impuestos_ventas SET FK_Detalle_Venta = '$idDetalleVenta', Tipo_Impuesto_CFDI = '$datos[5]', Impuesto_CFDI = '$datos[1]', Clave_CFDI = '$datos[3]',	Tipo_Factor_CFDI = '$datos[4]', Tasa_Cuota_CFDI = '".($datos[2])."'";
						$errorImp = $omodelo->_insertar($queryImp);

						if ($errorImp == "si") {
							echo "Error impuestos: ".mysqli_error($omodelo->link);
						}

					}
				}
				echo "Correcto~".$idVenta;
			}
		}

		
		/*$ImportePago=  $omodelo->link->real_escape_string($ImportePagoCompra);
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
		}*/
	}
	
	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST); 
		$fecha = date('Y-m-d H:i:s'); 
		$IDPedido =  $omodelo->link->real_escape_string($IDPedido);
		if ($tipo == "ModificarPedido") {

			if (!isset($cliente) || $cliente == "") {
				$cliente = 1;
			}
			$query = "UPDATE pedidos SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Sucursal = '$idsucursal', FK_Cliente = '$cliente', Descuento = '$sumadescuento', Total = '$total', Fecha_Registro = '$fecha', Fecha_Entrega = '$fechaEntrega' WHERE ID_Pedido = '$IDPedido'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{

				$queryEliminar = "DELETE FROM detalles_pedidos WHERE FK_Pedido = '$IDPedido'";
				$errorEliminar = $omodelo->_insertar($queryEliminar);

				if ($errorEliminar == "si") {
					echo "Error Eliminar: ".mysqli_error($omodelo->link);
				}

				$productos = json_decode($productos, true);
				foreach ($productos as $fila) {

					$nombreProducto = '';
					$query2 = "SELECT Descripcion FROM productos WHERE ID_Producto = '$fila[0]'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							$nombreProducto = $row2[0]["Descripcion"];
						}
					}
					$query = "INSERT INTO detalles_pedidos SET FK_Pedido = '$IDPedido', FK_Producto = '$fila[0]', FK_Presentacion = '$fila[1]', Descripcion = '$nombreProducto', Precio = '$fila[3]', Cantidad = '$fila[2]', Descuento = '$fila[4]', Total = '$fila[6]'";
					$error = $omodelo->_insertar($query);

					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}

					$idDetallePedido = mysqli_insert_id($omodelo->link);

					$separar = explode("~", $fila[5]);
					for ($i=0; $i < sizeof($separar) - 1; $i++) { 
						$datos = explode(",", $separar[$i]);

						$queryImp = "INSERT INTO detalles_impuestos_pedidos SET FK_Detalle_Pedido = '$idDetallePedido', Tipo_Impuesto_CFDI = '$datos[5]', Impuesto_CFDI = '$datos[1]', Clave_CFDI = '$datos[3]',	Tipo_Factor_CFDI = '$datos[4]', Tasa_Cuota_CFDI = '".($datos[2])."'";
						$errorImp = $omodelo->_insertar($queryImp);

						if ($errorImp == "si") {
							echo "Error impuestos: ".mysqli_error($omodelo->link);
						}

					}
				}
				echo "Correcto";
			}
		}
			
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$IDPedido = $omodelo->link->real_escape_string($IDPedido);

		$query = "DELETE FROM pedidos WHERE ID_Pedido = '$IDPedido'";
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
				$busqueda = 'AND ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(ID_Cliente, Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Cliente, Nombre, Calle, No_Exterior, No_Interior, Colonia, Ciudad, Codigo_Postal, Estado, Pais, Telefono, Celular, Correo, RFC, Facturar, (SELECT COUNT(*) FROM clientes WHERE ID_Cliente > 1 $busqueda) AS Num FROM clientes WHERE ID_Cliente > 1 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarCompras = 0;
					for($i=0; $i<$numerofilas; $i++){
						$contacto = "";$direccion=""; $facturar = "No";
						
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

						if ($row[$i]["Facturar"] == "1") {
							$facturar = "Si";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Cliente'],
							'Nombre' => $row[$i]['Nombre'],
							'Direccion' => $direccion,
							'RFC' => $row[$i]['RFC'],
							'Contacto' => $contacto,
							'Facturar' => $facturar
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
				$busqueda = 'AND ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(ID_Producto, Codigo, productos.Descripcion, presentaciones.Nombre, areas.Nombre, inventario.Cantidad, productos.Precio, productos.Precio_Mayoreo) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			///////////////////////////////////////////////////////////////////////////////////

			$query2 = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion,areas.Nombre AS NombreArea, ID_Presentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Mayoreo, (SELECT Nombre FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = ID_Presentacion ORDER BY Nombre LIMIT 1) AS NombrePrecio, (SELECT Precio FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = ID_Presentacion ORDER BY Nombre LIMIT 1) AS PrecioPresentacion, (SELECT Precio_Mayoreo FROM precios WHERE FK_Producto = ID_Producto AND FK_Presentacion = ID_Presentacion ORDER BY Nombre LIMIT 1) AS PrecioMayPresentacion, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia, (SELECT COUNT(*) FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON inventario.FK_Presentacion = ID_Presentacion LEFT JOIN areas ON FK_Area = ID_Area WHERE FK_Sucursal = '$sucursal' $busqueda) AS Num FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON inventario.FK_Presentacion = ID_Presentacion LEFT JOIN areas ON FK_Area = ID_Area WHERE FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

			$row2 = $omodelo->_consultar($query2);
			$numerofilas2 = $omodelo->numerofilas;

			if($row2 == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas2 > 0){
					for($x=0; $x<$numerofilas2; $x++){

						if ($row2[$x]['Existencia'] == "") {
							$row2[$x]['Existencia'] = 0;
						}

						if ($row2[$x]['NombreArea'] == "") {
							$row2[$x]['NombreArea'] = "No hay area registrada";
						}

						$nombrePresentacion = "";
						if ($row2[$x]['Presentacion'] != "") {
							$nombrePresentacion = $row2[$x]['Presentacion']." (".$row2[$x]['AbreviaturaPresentacion'].")";
						}else{
							$nombrePresentacion = "Sin presentación";
						}

						$precio = 0;
						if ($row2[$x]['PrecioPresentacion'] != "") {
							$precio = $row2[$x]['PrecioPresentacion'];
						}else{
							$precio = $row2[$x]['Precio_General'];
						}

						$precioMayoreo = 0;
						if ($row2[$x]['PrecioMayPresentacion'] != "") {
							$precioMayoreo = $row2[$x]['PrecioMayPresentacion'];
						}else{
							$precioMayoreo = $row2[$x]['Mayoreo'];
						}

						if ($row2[$x]['NombrePrecio'] == "") {
							$row2[$x]['NombrePrecio'] = "General";
						}

						$arreglo['data'][] = array(
							'ID' => $row2[$x]['ID_Producto'],
							'Producto' => $row2[$x]['Descripcion']."<br>Codigo: <b id='CodigoProducto'>".$row2[$x]['Codigo']."</b>",
							'Presentacion' =>"<span hidden id='IdPresentacionProd'>".$row2[$x]['ID_Presentacion']."</span>".$nombrePresentacion,
							'Nombre' => $row2[$x]['NombrePrecio'],
							'Precio' => $precio,
							'Mayoreo' => $precioMayoreo,
							'Existencia' => $row2[$x]['Existencia'],
						);
						
					}	
				}
			}
			//$numerofilasTotal = $numerofilas + $numerofilas2;
			$arreglo['totales'] = array('NumRows' => $row2[0]["Num"]);
			echo json_encode($arreglo);
		}else if($tipo == "AgregarProducto"){
			/*if (isset($presentacion) && $presentacion != "") {
				$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, presentaciones.ID_Presentacion AS IDPresentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, Clase, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Precio_Mayoreo_General, precios.Nombre AS NombrePrecio, precios.Precio AS PrecioPresentacion, precios.Precio_Mayoreo AS PrecioMayPresentacion, areas.Nombre AS NombreArea, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia FROM productos LEFT JOIN areas ON FK_Area = ID_Area INNER JOIN presentaciones ON FK_Producto = ID_Producto LEFT JOIN inventario ON ID_Presentacion = FK_Presentacion AND inventario.FK_Sucursal = '$sucursal' AND FK_Presentacion = '$presentacion' INNER JOIN precios ON precios.FK_Presentacion = ID_Presentacion WHERE Codigo = '$codigo'";				
			}else{
				$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, 'Sin presentación' AS Presentacion, 0 AS IDPresentacion, 'NA' AS AbreviaturaPresentacion,  Clase, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Precio_Mayoreo_General, areas.Nombre AS NombreArea, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia FROM productos LEFT JOIN areas ON FK_Area = ID_Area LEFT JOIN inventario ON inventario.FK_Producto = ID_Producto AND FK_Presentacion = 0 WHERE Codigo = '$codigo'";
			}*/

			if (!isset($presentacion) || $presentacion == "") {
				$presentacion = 0;
			}

			$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, presentaciones.ID_Presentacion AS IDPresentacion, presentaciones.Nombre AS Presentacion, Clase, presentaciones.Abreviatura AS AbreviaturaPresentacion, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Precio_Mayoreo_General, precios.Nombre AS NombrePrecio, precios.Precio AS PrecioPresentacion, precios.Precio_Mayoreo AS PrecioMayPresentacion, areas.Nombre AS NombreArea, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia FROM productos LEFT JOIN areas ON FK_Area = ID_Area INNER JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' LEFT JOIN presentaciones ON inventario.FK_Presentacion = ID_Presentacion LEFT JOIN precios ON precios.FK_Presentacion = ID_Presentacion WHERE Codigo = '$codigo' AND inventario.FK_Presentacion = '$presentacion'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$subarreglo = null;
					$campoImpuestos = "";
					$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre, impuestos.Porcentaje, impuestos.Clave_CFDI, impuestos.Tipo_Factor, impuestos.Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row[0]["ID_Producto"]."'";
					$rowI = $omodelo->_consultar($queryI);
					$numerofilasI = $omodelo->numerofilas;
					if($rowI == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasI > 0){
							for ($i=0; $i < $numerofilasI; $i++) { 
								$campoImpuestos .= '
									<div class="form-check impuesto">
										<input class="form-check-input seleccionarImpuesto oculto" checked type="checkbox" nombre="'.$rowI[$i]["Nombre"].'" porcentaje="'.$rowI[$i]["Porcentaje"].'" attrid="'.$rowI[$i]["FK_Impuesto"].'" clavecfdi="'.$rowI[$i]["Clave_CFDI"].'" tipofactor="'.$rowI[$i]["Tipo_Factor"].'" clase="'.$rowI[$i]["Clase"].'">
										<label class="form-check-label" for="flexCheckDefault">
											'.$rowI[$i]["Nombre"].' ('.$rowI[$i]["Porcentaje"].'%)
										</label>
									</div>
								';
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
					}else{
						$NombrePresentacion = "Sin presentación";	
					}

					$precio = 0; $precioMayoreo = 0;
					if ($row[0]["PrecioPresentacion"] != "") {
						$precio = $row[0]["PrecioPresentacion"];
					}else{
						$precio = $row[0]["Precio_General"];	
					}

					if ($row[0]["PrecioMayPresentacion"] != "") {
						$precioMayoreo = $row[0]["PrecioMayPresentacion"];
					}else{
						$precioMayoreo = $row[0]["Precio_Mayoreo_General"];	
					}

					
					$arreglo = array(
						'ID_Producto' => $row[0]["ID_Producto"],
						'Codigo' => $row[0]["Codigo"],
						'Descripcion' => $row[0]["Descripcion"],
						'Presentacion' => $NombrePresentacion,
						'Clase' => $row[0]["Clase"],
						'IDPresentacion' => $row[0]["IDPresentacion"],
						'Costo_General' => $row[0]["Costo_General"],
						'Precio_General' => $precio,
						'Precio_Mayoreo_General' => $precioMayoreo,
						'NombreArea' => $row[0]["NombreArea"],
						'Detalles' => $row[0]["Detalles"],
						'Minimo_General' => $row[0]["Minimo_General"],
						'Maximo_General' => $row[0]["Maximo_General"],
						'Fecha_Registro' => $row[0]["Fecha_Registro"],
						'Existencia' => $row[0]["Existencia"],
						'Impuestos' => $campoImpuestos
					);
					echo json_encode($arreglo);
				}else{
					echo "No encontrado";
				}
			}
		}else if($tipo == "ConsultarPrecios"){
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
					$busqueda .= "CONCAT(ID_Precio, Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			$numerofilas = 0;
			$numerofilas2 = 0;
			$numerofilas3 = 0;
			$numerofilas4 = 0;
			if (isset($presentacion) && $presentacion != "") {
				$query = "SELECT ID_Precio, Nombre, Precio, (SELECT COUNT(*) FROM precios $busqueda) AS Num FROM precios WHERE FK_Presentacion = '$presentacion' AND FK_Producto = '$idproducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						for($x=0; $x<$numerofilas; $x++){

							$arreglo['data'][] = array(
								'ID' => $row[$x]['ID_Precio'],
								'Nombre' => $row[$x]['Nombre'],
								'Precio' => $row[$x]['Precio'],
							);
							
						}	
					}
				}

				$query2 = "SELECT ID_Precio, Nombre, Precio_Mayoreo AS Precio, (SELECT COUNT(*) FROM precios $busqueda) AS Num FROM precios WHERE FK_Presentacion = '$presentacion' AND FK_Producto = '$idproducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

				$row2 = $omodelo->_consultar($query2);
				$numerofilas2 = $omodelo->numerofilas;

				if($row2 == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas2 > 0){
						for($x=0; $x<$numerofilas2; $x++){

							$arreglo['data'][] = array(
								'ID' => $row2[$x]['ID_Precio'],
								'Nombre' => $row2[$x]['Nombre'],
								'Precio' => $row2[$x]['Precio'],
							);
							
						}	
					}
				}		
			}


			$query3 = "SELECT 0 AS ID_Precio, 'General' AS Nombre, Precio, (SELECT COUNT(*) FROM precios $busqueda) AS Num FROM productos WHERE ID_Producto = '$idproducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

			$row3 = $omodelo->_consultar($query3);
			$numerofilas3 = $omodelo->numerofilas;

			if($row3 == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas3 > 0){
					for($x=0; $x<$numerofilas3; $x++){

						$arreglo['data'][] = array(
							'ID' => $row3[$x]['ID_Precio'],
							'Nombre' => $row3[$x]['Nombre'],
							'Precio' => $row3[$x]['Precio'],
						);
						
					}	
				}
			}

			$query4 = "SELECT 0 AS ID_Precio, 'General Mayoreo' AS Nombre, Precio_Mayoreo AS Precio, (SELECT COUNT(*) FROM precios $busqueda) AS Num FROM productos WHERE ID_Producto = '$idproducto' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

			$row4 = $omodelo->_consultar($query4);
			$numerofilas4 = $omodelo->numerofilas;

			if($row4 == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas4 > 0){
					for($x=0; $x<$numerofilas4; $x++){

						$arreglo['data'][] = array(
							'ID' => $row4[$x]['ID_Precio'],
							'Nombre' => $row4[$x]['Nombre'],
							'Precio' => $row4[$x]['Precio'],
						);
						
					}	
				}
			}
			$numerofilasTotal = $numerofilas + $numerofilas2 + $numerofilas3 + $numerofilas4;
			$arreglo['totales'] = array('NumRows' => $numerofilasTotal);

			echo json_encode($arreglo);
		}else if($tipo == "CargarPedidos"){

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
					$busqueda .= "CONCAT(DATE_FORMAT(pedidos.Fecha_Registro, '%Y-%m-%d'), ID_Pedido, clientes.Nombre, pedidos.Descuento, Total, Tipo_Pago, Notas, clientes.Correo) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Pedido, pedidos.FK_Usuario, pedidos.FK_Sucursal, FK_Caja, FK_Cliente, pedidos.Descuento, Total, Tipo_Pago, Pago, Cambio, Notas, pedidos.Fecha_Registro AS Datos, Cancelada, Fecha_Cancelacion, Regreso_Inventario, (SELECT CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) FROM usuarios WHERE ID_Usuario = pedidos.FK_Usuario) AS NombreUsuario, clientes.Nombre AS NombreCliente, clientes.Telefono AS Telefono, clientes.Correo AS CorreoCliente, clientes.RFC AS RFCCliente, (SELECT COUNT(*) FROM pedidos WHERE pedidos.FK_Sucursal = '$sucursal') AS Num FROM pedidos INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE pedidos.FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarVentas = 0;
					for($i=0; $i<$numerofilas; $i++){
						$tipoUsuario = "";$usuario="";$estatus="";
						$folio = str_pad($row[$i]['ID_Pedido'], 8, "0", STR_PAD_LEFT);

						$SumarVentas += $row[$i]['Total'];

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Pedido'],
							'Datos' => "Fecha: <b>".$row[$i]['Datos']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b>",
							'Cliente' => 'Nombre: <b>'.$row[$i]['NombreCliente'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Correo electrónico: <b>'.$row[$i]['CorreoCliente'].'</b><br>RFC: <b>'.$row[$i]['RFCCliente']."</b>",
							'Total' => "Subtotal: <b>$".number_format(($row[$i]['Total'] + $row[$i]['Descuento']), 2)."</b><br>Descuento: <b>$".number_format($row[$i]['Descuento'], 2)."</b><br>Total: <b>$".number_format($row[$i]['Total'], 2)."</b>",
							'Detalles' => '<button class="btn btn-link btn-sm" id="VerProductosPedido" attrid="'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'">Ver productos</button>',
							'Acciones' => '<button type="button" class="btn btn-primary btn-sm SeleccionarPedido" attrid="'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'">Seleccionar</button> <br> <br> <button type="button" class="btn btn-danger btn-sm EliminarPedido" attrid="'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'"><i class="fas fa-trash"></i></button>',
						);
					}

					$arreglo['totales'] = array(
						'NumRows' => $row[0]['Num'], 
						'Datos' => "",
						'Cliente' => "Totales",
						'Total' => "<b>$".number_format($SumarVentas, 2)."</b>",
						'Detalles' =>"",
						'Acciones' => "");	
		
				}
			}

			echo json_encode($arreglo);

		}else if($tipo == "productos"){
			$IDPedido = $omodelo->link->real_escape_string($IDPedido);
			$tabla = "";
			$query = "SELECT ID_Detalle_Pedido, FK_Pedido, detalles_pedidos.FK_Producto, FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Abreviatura AS AbreviaturaPresentacion, Descripcion, Precio, Cantidad, Descuento, Total, Devuelto, Fecha_Devolucion, Regreso_Inventario FROM detalles_pedidos LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Pedido = '$IDPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$sumaImpuestos=0; $subtotal = 0;

						$query2 = "SELECT ID_Impuesto, FK_Detalle_Pedido, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_pedidos WHERE FK_Detalle_Pedido = '".$row[$i]["ID_Detalle_Pedido"]."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;

						if($row2 == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								for($x=0; $x<$numerofilas2; $x++){
									$totalProducto=0; $descuento=0; $totalFinal=0;
									$totalProducto = $row[$i]["Precio"]*$row[$i]["Cantidad"];
									$descuento = ($row[$i]["Descuento"] / 100);
									$totalFinal = $totalProducto - ($totalProducto * $descuento);
									if ($row2[$x]["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
										$sumaImpuestos += $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);
									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] != "Exento"){ //Se resta al total
										$sumaImpuestos -= $totalFinal * ($row2[$x]["Tasa_Cuota_CFDI"] / 100);

									}else if($row2[$x]["Tipo_Impuesto_CFDI"] == "Retenido" && $row2[$x]["Tipo_Factor_CFDI"] == "Exento"){ ////No se suma ni se resta
										$sumaImpuestos += 0;
									}
								}
							}
						}


						$presentacion = "";
						$nombrepresentacion = "";
						if ($row[$i]["NombrePresentacion"] != "") {
							$presentacion = "<br>".$row[$i]["NombrePresentacion"];
							$nombrepresentacion = " / ".$row[$i]["NombrePresentacion"];
							if ($row[$i]["AbreviaturaPresentacion"] != "") {
								$presentacion .= " (".$row[$i]["AbreviaturaPresentacion"].")";
								$nombrepresentacion .= " (".$row[$i]["AbreviaturaPresentacion"].")";
							} 
						}

						$subtotal = ($row[$i]["Precio"] * $row[$i]["Cantidad"]) ;
						$descuento = $subtotal * ($row[$i]["Descuento"] / 100);
						$subtotal = $subtotal - $descuento;
						$tabla .= "
							<tr>
								<td >".$row[$i]["Descripcion"].$presentacion."</td>
								<td style='vertical-align: middle;'>$".number_format($row[$i]["Precio"], 2)."</td>
								<td style='vertical-align: middle;'>".number_format($row[$i]["Cantidad"], 2)."</td>
								<td style='vertical-align: middle;'>".$row[$i]["Descuento"]."%</td>
								<td style='vertical-align: middle;'>$".number_format($subtotal, 2)."</td>
								<td><button class='btn btn-primary btn-sm verImpuestosProductoPedido' nombre='".$row[$i]["Descripcion"].$nombrepresentacion."' attrid='".$row[$i]["ID_Detalle_Pedido"]."'>$".number_format($sumaImpuestos, 2)."</button></td>
								<td>$".number_format($row[$i]["Total"], 2)."</td>
							</tr>
						";
					}
				}
			}

			echo $tabla;
		}else if($tipo == "impuestos"){
			$query = "SELECT ID_Impuesto, FK_Detalle_Pedido, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_pedidos WHERE FK_Detalle_Pedido = '".$IDDetalle."'";
			$row = $omodelo->_consultar($query);
			$numerofilas2 = $omodelo->numerofilas;
			$tabla ="";
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas2 > 0){
					for ($i=0; $i < $numerofilas2; $i++) { 
						$tabla .= "
							<tr>
								<td >".$row[$i]["Impuesto_CFDI"]."</td>
								<td >".$row[$i]["Clave_CFDI"]."</td>
								<td >".$row[$i]["Tasa_Cuota_CFDI"]."</td>
								<td >".$row[$i]["Tipo_Factor_CFDI"]."</td>
								<td >".$row[$i]["Tipo_Impuesto_CFDI"]."</td>
							</tr>
						";
					}
				}else{
					$tabla = "
						<tr>
							<td style='vertical-align: middle;' colspan='6'>No hay impuestos registrados</td>
						</tr>
					";
				}
				echo $tabla;
			}
		}else if($tipo == "AgregarPedido"){
			$IDPedido = $omodelo->link->real_escape_string($IDPedido);
			$productos = null;
			$impuestos = null;
			$query = "SELECT ID_Pedido, FK_Usuario, FK_Caja, pedidos.FK_Sucursal, FK_Cliente, clientes.Nombre AS NombreCliente, clientes.RFC AS RFCCliente, pedidos.Descuento, Total, Tipo_Pago, Pago, Cambio, Notas, Fecha_Entrega, pedidos.Fecha_Registro, Cancelada, Fecha_Cancelacion, Regreso_Inventario FROM pedidos INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Pedido = '$IDPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){

					$query2 = "SELECT ID_Detalle_Pedido, FK_Pedido, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura, detalles_pedidos.FK_Producto, Codigo, detalles_pedidos.FK_Presentacion, detalles_pedidos.Descripcion, detalles_pedidos.Precio, detalles_pedidos.Cantidad, detalles_pedidos.Descuento, Total, detalles_pedidos.Devuelto, detalles_pedidos.Fecha_Devolucion, Regreso_Inventario FROM detalles_pedidos LEFT JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON detalles_pedidos.FK_Presentacion = ID_Presentacion WHERE FK_Pedido = '$IDPedido'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							for ($i=0; $i < $numerofilas2; $i++) { 
								$campoImpuestos = "";
								$queryI = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Impuesto, impuestos.Nombre, impuestos.Porcentaje, impuestos.Clave_CFDI, impuestos.Tipo_Factor, impuestos.Clase FROM detalles_impuestos_productos INNER JOIN impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = '".$row2[$i]["FK_Producto"]."'";
								$rowI = $omodelo->_consultar($queryI);
								$numerofilasI = $omodelo->numerofilas;
								if($rowI == 'si'){
									echo "Error: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilasI > 0){
										for ($a=0; $a < $numerofilasI; $a++) { 
											$checked = "";
											$query3 = "SELECT ID_Impuesto, FK_Detalle_Pedido, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_pedidos WHERE FK_Detalle_Pedido = '".$row2[$i]["ID_Detalle_Pedido"]."' AND Impuesto_CFDI = '".$rowI[$a]["Nombre"]."' AND Tasa_Cuota_CFDI = '".$rowI[$a]["Porcentaje"]."' AND Clave_CFDI = '".$rowI[$a]["Clave_CFDI"]."'";
											$row3 = $omodelo->_consultar($query3); 
											$numerofilas3 = $omodelo->numerofilas;

											if($row3 == 'si'){
												echo "Error 3: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilas3 > 0){
													$checked = "checked";
												}else{
													$checked = "";
												}
											}

											$campoImpuestos .= '
												<div class="form-check impuesto">
													<input class="form-check-input oculto seleccionarImpuesto" '.$checked.' type="checkbox" nombre="'.$rowI[$a]["Nombre"].'" porcentaje="'.$rowI[$a]["Porcentaje"].'" attrid="'.$rowI[$a]["FK_Impuesto"].'" clavecfdi="'.$rowI[$a]["Clave_CFDI"].'" tipofactor="'.$rowI[$a]["Tipo_Factor"].'" clase="'.$rowI[$a]["Clase"].'">
													<label class="form-check-label" for="flexCheckDefault">
														'.$rowI[$a]["Nombre"].' ('.$rowI[$a]["Porcentaje"].'%)
													</label>
												</div>
											';
											/*$query3 = "SELECT ID_Impuesto, FK_Detalle_Pedido, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_pedidos WHERE FK_Detalle_Pedido = '".$row2[0]["ID_Detalle_Pedido"]."'";
											$row3 = $omodelo->_consultar($query3); 
											$numerofilas3 = $omodelo->numerofilas;

											if($row3 == 'si'){
												echo "Error 3: ".mysqli_error($omodelo->link);
											}else{
												if($numerofilas3 > 0){

														($rowI[$a]["Nombre"] == $row3[0]["Impuesto_CFDI"] && 
															$rowI[$a]["Porcentaje"] == $row3[0]["Tasa_Cuota_CFDI"] && 
															$rowI[$a]["Tipo_Factor"] == $row3[0]["Tipo_Factor_CFDI"] && 
															$rowI[$a]["Clave_CFDI"] == $row3[0]["Clave_CFDI"] && 
															$rowI[$a]["Clase"] == $row3[0]["Tipo_Impuesto_CFDI"]
														) {
															$campoImpuestos .= '
																<div class="form-check impuesto">
																	<input class="form-check-input seleccionarImpuesto" checked type="checkbox" nombre="'.$rowI[$a]["Nombre"].'" porcentaje="'.$rowI[$a]["Porcentaje"].'" attrid="'.$rowI[$a]["FK_Impuesto"].'" clavecfdi="'.$rowI[$a]["Clave_CFDI"].'" tipofactor="'.$rowI[$a]["Tipo_Factor"].'" clase="'.$rowI[$a]["Clase"].'">
																	<label class="form-check-label" for="flexCheckDefault">
																					'.$rowI[$a]["Nombre"].' ('.$rowI[$a]["Porcentaje"].'%)
																	</label>
																</div>
															';	
														}else{
															$campoImpuestos .= '
																<div class="form-check impuesto">
																	<input class="form-check-input seleccionarImpuesto" type="checkbox" nombre="'.$rowI[$a]["Nombre"].'" porcentaje="'.$rowI[$a]["Porcentaje"].'" attrid="'.$rowI[$a]["FK_Impuesto"].'" clavecfdi="'.$rowI[$a]["Clave_CFDI"].'" tipofactor="'.$rowI[$a]["Tipo_Factor"].'" clase="'.$rowI[$a]["Clase"].'">
																	<label class="form-check-label" for="flexCheckDefault">
																		'.$rowI[$a]["Nombre"].' ('.$rowI[$a]["Porcentaje"].'%)
																	</label>
																</div>
															';
														}

														// ///////////////////////////////////////////
														// $impuestos['data'][0] = array(
														// 	'ID_Impuesto' => $row3[$x]["ID_Impuesto"],
														// 	'FK_Detalle_Pedido' => $row3[$x]["FK_Detalle_Pedido"],
														// 	'Tipo_Impuesto_CFDI' => $row3[$x]["Tipo_Impuesto_CFDI"],
														// 	'Impuesto_CFDI' => $row3[$x]["Impuesto_CFDI"],
														// 	'Clave_CFDI' => $row3[$x]["Clave_CFDI"],
														// 	'Tipo_Factor_CFDI' => $row3[$x]["Tipo_Factor_CFDI"],
														// 	'Tasa_Cuota_CFDI' => $row3[$x]["Tasa_Cuota_CFDI"]
														// );
													

												}
											}*/

										//
										}

									}
								}	


								$nombrePresentacion = "";
								if ($row2[$i]['Presentacion'] != "") {
									$nombrePresentacion = $row2[$i]['Presentacion']." (".$row2[$i]['Abreviatura'].")";
								}else{
									$nombrePresentacion = "Sin presentación";
								}


								$productos['data'][$i] = array(
									'ID_Detalle_Pedido' => $row2[$i]["ID_Detalle_Pedido"],
									'FK_Pedido' => $row2[$i]["FK_Pedido"],
									'FK_Producto' => $row2[$i]["FK_Producto"],
									'Codigo' => $row2[$i]["Codigo"],
									'FK_Presentacion' => $row2[$i]["FK_Presentacion"],
									'NombrePresentacion' => $nombrePresentacion,
									'Descripcion' => $row2[$i]["Descripcion"],
									'Precio' => $row2[$i]["Precio"],
									'Cantidad' => $row2[$i]["Cantidad"],
									'Descuento' => $row2[$i]["Descuento"],
									'Total' => $row2[$i]["Total"],
									'Devuelto' => $row2[$i]["Devuelto"],
									'Fecha_Devolucion' => $row2[$i]["Fecha_Devolucion"],
									'Regreso_Inventario' => $row2[$i]["Regreso_Inventario"],
									'Impuestos' => $campoImpuestos,
								);

							}
						}
					}

					$arreglo['data'] = array(
						'ID_Pedido' => $row[0]['ID_Pedido'],
						'FK_Sucursal' => $row[0]["FK_Sucursal"],
						'FK_Cliente' => $row[0]["FK_Cliente"],
						'NombreCliente' => $row[0]["NombreCliente"],
						'RFCCliente' => $row[0]["RFCCliente"],
						'Descuento' => $row[0]["Descuento"],
						'Total' => $row[0]["Total"],
						'Tipo_Pago' => $row[0]["Tipo_Pago"],
						'Pago' => $row[0]["Pago"],
						'Cambio' => $row[0]["Cambio"],
						'Notas' => $row[0]["Notas"],
						'Fecha_Entrega' => $row[0]["Fecha_Entrega"],
						'Productos' => $productos,
					);
				}

				echo json_encode($arreglo);
			}
		}else if($tipo == "ConsultarPresentacionesProducto"){
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
					$busqueda .= "CONCAT(Cantidad, Nombre, Abreviatura) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			if ($presentacion == null) {
				$presentacion = 0;
			}
			
			$query = "SELECT inventario.FK_Presentacion AS ID_Presentacion, inventario.FK_Producto, Cantidad AS Existencia, IFNULL((SELECT Precio FROM precios INNER JOIN zonas ON precios.FK_Zona = ID_Zona INNER JOIN sucursales ON precios.FK_Zona = ID_Zona WHERE sucursales.ID_Sucursal = '$sucursal' AND FK_Producto = inventario.FK_Producto AND FK_Presentacion = inventario.FK_Presentacion ORDER BY Precio LIMIT 1), (SELECT Precio FROM productos WHERE ID_Producto = inventario.FK_Producto)) AS PrimerPrecio, IFNULL(Nombre, 'Sin presentación') AS Nombre, IFNULL(Abreviatura, '') AS Abreviatura, (SELECT COUNT(*) FROM inventario LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN productos ON inventario.FK_Producto = ID_Producto WHERE inventario.FK_Sucursal = '$sucursal' AND inventario.FK_Producto = '$idproducto' AND inventario.FK_Presentacion <> '$presentacion' $busqueda) AS Num FROM inventario LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN productos ON inventario.FK_Producto = ID_Producto WHERE inventario.FK_Sucursal = '$sucursal' AND inventario.FK_Producto = '$idproducto' AND inventario.FK_Presentacion <> '$presentacion' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						if ($row[$i]['ID_Presentacion'] == "" || $row[$i]['ID_Presentacion'] == 0) {
							$row[$i]['ID_Presentacion'] = null;
						}
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Presentacion'],
							'Nombre'  => $row[$i]['Nombre'],
							'Abreviatura' => $row[$i]['Abreviatura'],
							'Existencia' => $row[$i]['Existencia'],
							'Accion' => '<button type="button" class="btn btn-primary btn-sm SeleccionarPresentacion" presentacionactual="'.$presentacion.'" attrid="'.$row[$i]['ID_Presentacion'].'" precio="'.$row[$i]['PrimerPrecio'].'" abreviatura="'.$row[$i]['Abreviatura'].'" nombre="'.$row[$i]['Nombre'].'" producto="'.$row[$i]['FK_Producto'].'">Seleccionar</button>',
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]["Num"]);	
		
				}
			}

			echo json_encode($arreglo);
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
