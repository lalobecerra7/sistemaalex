<?php
class hacerCompra {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$ImportePagadoCompra =  $omodelo->link->real_escape_string($Importe);
		$idProveedor =  $omodelo->link->real_escape_string($idProveedor);
		$fechaCredito =  $omodelo->link->real_escape_string($FechaCredito);
		$subtotal =  $omodelo->link->real_escape_string($subtotal);
		$total =  $omodelo->link->real_escape_string($total);
		$tipoCompra =  $omodelo->link->real_escape_string($TipoCompra);
		$descuento =  $omodelo->link->real_escape_string($Descuento);
		$tipoPago =  $omodelo->link->real_escape_string($TipoPago);
		$detalles =  $omodelo->link->real_escape_string($Detalles);
		$datos = json_decode($Productos);
		$fecha = date('Y-m-d H:i:s'); 

		$Cambio = floatval($ImportePagadoCompra) - floatval($total);
		if ($Cambio <= 0) {
			$Cambio = 0;
		}

		if($tipoCompra == 'Credito'){
			$estatus = '0';
		}else{
			$estatus = '1';
		}

		$query = "INSERT INTO compras SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Proveedor= '$idProveedor', Total= '$total', Anticipo= '$ImportePagadoCompra', Estatus= '$estatus', Fecha_Registro = '$fecha', Fecha_Credito = '$fechaCredito', Tipo_Compra = '$tipoCompra', Descuento = '$descuento'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$IDCompra = mysqli_insert_id($omodelo->link);

			foreach ($datos as $fila) {
				$calcularSubtotal = floatval($fila[1]) * floatval($fila[2]);
				$queryDetalles = "INSERT INTO detalle_compras SET FK_Compra = '$IDCompra', FK_Producto = '".$fila[0]."', Costo = '".$fila[1]."', Cantidad = '".$fila[2]."', FK_Presentacion = '".$fila[4]."', Subtotal = '$calcularSubtotal'";
				$errorDetalles = $omodelo->_insertar($queryDetalles);

				if ($errorDetalles == "si") {
					echo "Error detalles: ".mysqli_error($omodelo->link);
				}else{
					/*$querySumar = "UPDATE inventario SET Cantidad = (Cantidad + ".$fila[2].") WHERE FK_Producto = '".$fila[0]."' AND FK_Sucursal = '".$fila[3]."' AND FK_Presentacion = '".$fila[4]."'";
					$errorSumar = $omodelo->_insertar($querySumar);
					
					if ($errorSumar == "si") {
						echo "Error sumar: ".mysqli_error($omodelo->link);
					}else {
						$usuario = $_SESSION['user_admin']['ID_Usuario'];
						$queryPago = "INSERT INTO pagos SET FK_Compra = '$IDCompra', Monto = '$ImportePagadoCompra', Concepto = 'Anticipo', Tipo_Pago = '$tipoPago', Fecha = '$fecha', FK_Usuario = '$usuario', Detalles_Pago = '$detalles'";
						$errorPago = $omodelo->_insertar($queryPago);
						if ($errorPago == "si") {
							echo "Error pagos: ".mysqli_error($omodelo->link);
						}else{
							echo "Correcto~".$IDCompra;
							$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
						}
					}*/
				}	
			}
		}
	}

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "ConsultarProductos") {
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
					$busqueda .= "CONCAT(ID_Producto, Imagen, Descripcion, Codigo, Costo, sucursal.Nombre, presentaciones.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Producto, Imagen, Descripcion, Codigo, Costo, inventario.FK_Sucursal AS FK_Sucursal, sucursales.Nombre AS NombreSucursal, inventario.FK_Presentacion AS FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Abreviatura AS Abreviatura, (SELECT COUNT(*) FROM inventario ) AS Num FROM productos LEFT JOIN inventario ON FK_Producto = ID_Producto LEFT JOIN sucursales ON inventario.FK_Sucursal = ID_Sucursal LEFT JOIN presentaciones ON inventario.FK_Presentacion = ID_Presentacion $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = '<a href="vistas/assets/archivos/fotosProductos/default.jpg" data-fancybox="images">
										<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/default.jpg'."'".'); width: 30px; height: 30px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
										</div>
									</a><br>';
						if ($row[$i]["Imagen"] != "") {
							if($row[$i]["Imagen"] != "" && file_exists("vistas/assets/archivos/fotosProductos/".$row[$i]["Imagen"])){
								$foto = '<a href="vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"].'" data-fancybox="images">
										<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"]."'".'); width: 30px; height: 30px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
										</div>
									</a><br>';
							}	
						}
						
						$Presentacion = "";
						if($row[$i]['NombrePresentacion'] != '' || $row[$i]['NombrePresentacion'] != null){
							$Presentacion = "<b class='Presentacion' presentacion='".$row[$i]['FK_Presentacion']."'>".$row[$i]['NombrePresentacion']."(".$row[$i]['Abreviatura'].")</b>";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Producto' => $foto."<b class='codigo'>".$row[$i]['Codigo']."</b>",
							'Descripcion' => "<b class='NombreProducto'>".$row[$i]['Descripcion']."</b>",
							'Costo' => "<b class='CostoProducto'>$".number_format($row[$i]['Costo'], 2)."</b>",
							'Presentacion' => $Presentacion,
							'Sucursal' => "<b class='Sucursal' sucursal='".$row[$i]['FK_Sucursal']."'>".$row[$i]['NombreSucursal']."</b>",
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "ConsultarProveedores"){
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
					$busqueda .= "CONCAT(ID_Proveedor, Nombre, Calle, No_Exterior, No_Interior, Empresa, Telefono, RFC, Credito, Razon_Social) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Proveedor, Nombre, Calle, No_Exterior, No_Interior, Empresa, Telefono, RFC, Credito, Razon_Social, (SELECT COUNT(*) FROM proveedores $busqueda) AS Num FROM proveedores $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$direccion = "";$empresa="";$credito="";
						if ($row[$i]['Calle'] != "") {
							$direccion .= "<b>".$row[$i]['Calle']."</b>";
							if ($row[$i]['No_Exterior'] != "") {
								$direccion .= "Numero exterior: <b>".$row[$i]['No_Exterior']."</b><br>";
							}
							if ($row[$i]['No_Interior'] != "") {
								$direccion .= "Numero interior: <b>".$row[$i]['No_Interior']."</b><br>";
							}
						}else{	
							$direccion = "No hay datos registrados";
						}

						if ($row[$i]['Empresa'] != "") {
							$empresa .= "Empresa: <b>".$row[$i]['Empresa']."</b><br>";
						}

						if ($row[$i]['Telefono'] != "") {
							$empresa .= "Telefono: <b>".$row[$i]['Telefono']."</b><br>";
						}

						if ($row[$i]['RFC'] != "") {
							$empresa .= "RFC: <b>".$row[$i]['RFC']."</b><br>";
						}

						if ($row[$i]['Razon_Social'] != "") {
							$empresa .= "Razón social: <b>".$row[$i]['Razon_Social']."</b><br>";
						}

						if ($row[$i]['Credito'] == "SI") {
							$credito = "<b>".$row[$i]['Credito']."</b>";
						}else if ($row[$i]['Credito'] == "NO"){	
							$credito = "No hay datos registrados";
						}else {
							$credito = $row[$i]['Credito'];
						}

						// if ($row[$i]['Tipo_Descuento'] == "Porcentaje") {
						// 	$descuento .= "Tipo: <b class='tipoDescuentoCliente'>".$row[$i]['Tipo_Descuento']."</b><br><b class='cantidadDescuentoCliente'>".number_format($row[$i]['Descuento'], 2)." %</b>";
						// }else if($row[$i]['Tipo_Descuento'] == "Cantidad"){
						// 	$descuento .= "Tipo: <b class='tipoDescuentoCliente'>".$row[$i]['Tipo_Descuento']."</b><br><b class='cantidadDescuentoCliente'>$".number_format($row[$i]['Descuento'], 2)."</b>";
						// }else{
						// 	$descuento = "No aplica descuento";
						// }

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Proveedor'],
							'Nombre' => "<b class='NombreProveedor'>".$row[$i]['Nombre']."</b>",
							'Direccion' => $direccion,
							'Empresa' => $empresa,
							'Credito' => $credito,
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "ConsultarProductoCodigo"){
			$Codigo =  $omodelo->link->real_escape_string($codigo);

			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'AND';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(ID_Producto, Imagen, Descripcion, Codigo, Costo, sucursal.Nombre, presentaciones.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Producto, Imagen, Descripcion, Codigo, Costo, inventario.FK_Sucursal AS FK_Sucursal, sucursales.Nombre AS NombreSucursal, inventario.FK_Presentacion AS FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Abreviatura AS Abreviatura, (SELECT COUNT(*) FROM inventario ) AS Num FROM productos LEFT JOIN inventario ON FK_Producto = ID_Producto LEFT JOIN sucursales ON inventario.FK_Sucursal = ID_Sucursal LEFT JOIN presentaciones ON inventario.FK_Presentacion = ID_Presentacion WHERE Codigo = '$Codigo' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = '<a href="vistas/assets/archivos/fotosProductos/default.jpg" data-fancybox="images">
										<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/default.jpg'."'".'); width: 30px; height: 30px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
										</div>
									</a><br>';
						if ($row[$i]["Imagen"] != "") {
							if($row[$i]["Imagen"] != "" && file_exists("vistas/assets/archivos/fotosProductos/".$row[$i]["Imagen"])){
								$foto = '<a href="vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"].'" data-fancybox="images">
										<div style="background-image: url('."'".'vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"]."'".'); width: 30px; height: 30px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
										</div>
									</a><br>';
							}	
						}
						
						$Presentacion = "";
						if($row[$i]['NombrePresentacion'] != '' || $row[$i]['NombrePresentacion'] != null){
							$Presentacion = "<b class='Presentacion' presentacion='".$row[$i]['FK_Presentacion']."'>".$row[$i]['NombrePresentacion']."(".$row[$i]['Abreviatura'].")</b>";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Producto' => $foto."<b class='codigo'>".$row[$i]['Codigo']."</b>",
							'Descripcion' => "<b class='NombreProducto'>".$row[$i]['Descripcion']."</b>",
							'Costo' => "<b class='CostoProducto'>$".number_format($row[$i]['Costo'], 2)."</b>",
							'Presentacion' => $Presentacion,
							'Sucursal' => "<b class='Sucursal' sucursal='".$row[$i]['FK_Sucursal']."'>".$row[$i]['NombreSucursal']."</b>",
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "creditoProveedor"){
			$IDProveedor =  $omodelo->link->real_escape_string($IDProveedor);
			
			$query = "SELECT ID_Proveedor, Credito, (SELECT proveedores.Credito-SUM(Total)-SUM(Anticipo) FROM compras, proveedores WHERE FK_Proveedor = ID_Proveedor AND Estatus = '0' AND ID_Proveedor = '$IDProveedor') AS RestanteCredito FROM proveedores WHERE ID_Proveedor = '$IDProveedor'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					if($row[0]['RestanteCredito'] == null){
						$RestanteCredito = $row[0]['Credito'];
					}else if($row[0]['RestanteCredito'] < 0) {
						$RestanteCredito = 0;
					}else{
						$RestanteCredito = $row[0]['RestanteCredito'];
					}
					$arreglo['data'] = array(
						'ID_Proveedor' => $row[0]['ID_Proveedor'],
						'Credito' => number_format($row[0]['Credito'], 2),
						'RestanteCredito' => number_format($RestanteCredito, 2)
					);
					echo json_encode ($arreglo);
							
				}
			}
		}
	}	
}
?>
