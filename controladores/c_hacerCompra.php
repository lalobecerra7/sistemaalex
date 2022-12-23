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
		$detalles =  $omodelo->link->real_escape_string($DetallesPago);
		$sucursal =  $omodelo->link->real_escape_string($Sucursal);
		$datos = json_decode($Productos);
		$fecha = date('Y-m-d H:i:s'); 

		$Cambio = floatval($ImportePagadoCompra) - floatval($total);
		if ($Cambio <= 0) {
			$Cambio = 0;
		}

		$tipoPagoA = 'Pago';
		$estatus = '1';
		if($tipoCompra == 'Credito'){
			$estatus = '0';
			$tipoPagoA = 'Abono';
		}

		$query = "INSERT INTO compras SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Proveedor= '$idProveedor', Total= '$total', Anticipo= '$ImportePagadoCompra', Estatus= '$estatus', Fecha_Registro = '$fecha', Fecha_Credito = '$fechaCredito', Tipo_Compra = '$tipoCompra', Descuento = '$descuento', FK_Sucursal = '$sucursal'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$IDCompra = mysqli_insert_id($omodelo->link);

			foreach ($datos as $fila) {
				$calcularSubtotal = floatval($fila[1]) * floatval($fila[2]);
				$queryDetalles = "INSERT INTO detalle_compras SET FK_Compra = '$IDCompra', FK_Producto = '".$fila[0]."', Costo = '".$fila[1]."', Cantidad = '".$fila[2]."', FK_Presentacion = '".$fila[3]."', Subtotal = '$calcularSubtotal'";
				$errorDetalles = $omodelo->_insertar($queryDetalles);

				if ($errorDetalles == "si") {
					echo "Error detalles: ".mysqli_error($omodelo->link);
				}
			}
			$usuario = $_SESSION['user_admin']['ID_Usuario'];
			$queryPago = "INSERT INTO pagos SET FK_Compra = '$IDCompra', Monto = '$ImportePagadoCompra', Concepto = '$tipoPagoA', Tipo_Pago = '$tipoPago', Fecha = '$fecha', FK_Usuario = '$usuario', Detalles_Pago = '$detalles'";
			$errorPago = $omodelo->_insertar($queryPago);
			if ($errorPago == "si") {
				echo "Error pagos: ".mysqli_error($omodelo->link);
			}else{
				$ID = mysqli_insert_id($omodelo->link);

				$status = 1;
				if ($_FILES['ComprobantePagoHC']['size'] > 0 && $_FILES['ComprobantePagoHC']['error'] == 0) {
					$file = $_FILES["ComprobantePagoHC"];
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
					$query2 = "UPDATE pagos SET Archivo = '".$ID.'_'.$nombreDoc."' WHERE ID_Pago = '$ID'";
					$error3 = $omodelo->_insertar($query2);	

					if ($error3 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.''.$ID.'_'.$nombreDoc);
					}
				}

				echo "Correcto~".$IDCompra.'~'.$sucursal;
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
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
					$busqueda .= "CONCAT(ID_Producto, Descripcion, Codigo, Costo, presentaciones.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
					  
			$query = "SELECT ID_Producto, Codigo, Descripcion, Nombre_Unidad AS NombrePresentacion, Abreviatura_Unidad AS Abreviatura, Costo, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Presentacion = 0 AND inventario.FK_Producto = ID_Producto), 0) AS Existencia, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			echo $query;
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$presentacion = '';
						if($row[$i]['NombrePresentacion'] != '' || $row[$i]['Abreviatura'] != ''){
							$presentacion .= '<button style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresCom" presentacion="0" costo="'.$row[$i]['Costo'].'">Ex. '.number_format($row[$i]['Existencia'], 2).' - <span>'.$row[$i]['NombrePresentacion'].'('.$row[$i]['Abreviatura'].')</span></button>';
						}else{
							$presentacion .= '<button style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresCom" presentacion="0" costo="'.$row[$i]['Costo'].'"><span>Sin presentación</span></button>';
						}

						$query1 = "SELECT ID_Presentacion, Nombre, Abreviatura, Clave_CFDI, Costo, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Presentacion = ID_Presentacion AND inventario.FK_Producto = FK_Producto), 0) AS Existencia FROM presentaciones WHERE FK_Producto = '".$row[$i]['ID_Producto']."' ORDER BY Nombre";
						$row1 = $omodelo->_consultar($query1);
						$numerofilas1 = $omodelo->numerofilas;

						if($row1 == 'si'){
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas1 > 0){
								for($x=0; $x < $numerofilas1; $x++){
									$presentacion .= '<button style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresCom" presentacion="'.$row1[$x]['ID_Presentacion'].'" costo="'.$row1[$x]['Costo'].'">Ex. '.number_format($row1[$x]['Existencia'], 2).' - <span>'.$row1[$x]['Nombre'].'('.$row1[$x]['Abreviatura'].')</span></button>';
								}
							}
						}
						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Codigo' => "<b class='codigo'>".$row[$i]['Codigo']."</b>",
							'Descripcion' => "<b class='NombreProducto'>".$row[$i]['Descripcion']."</b>",
							'Costo' => "<b class='CostoProducto'>$".number_format($row[$i]['Costo'], 2)."</b>",
							'Presentacion' => $presentacion,
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
							$empresa .= "Razón social: <b class= razonSocial>".$row[$i]['Razon_Social']."</b><br>";
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
			$arreglo = null;	

			$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, CONCAT(Nombre_Unidad, ' (', Abreviatura_Unidad, ')') AS NombrePresentacion, Costo FROM productos WHERE Codigo = '$Codigo'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo = $row[0];
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

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo =  $omodelo->link->real_escape_string($tipo);

		if ($tipo == "consultarPrese") {
			$id =  $omodelo->link->real_escape_string($id);
			$tabla = '';

			$query = "SELECT Nombre_Unidad, Abreviatura_Unidad, Costo FROM productos WHERE ID_Producto = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$tabla .= '<tr>
						<td>'.$row[0]['Nombre_Unidad'].'</td>
						<td>'.$row[0]['Abreviatura_Unidad'].'</td>
						<td>'.$row[0]['Costo'].'</td>
						<td><button type="button" class="btn btn-sm btn-primary bSeleCamPres" attrID="0">Seleccionar</button></td>
					</tr>';
				}
			}

			$query = "SELECT ID_Presentacion, Nombre, Abreviatura, Costo FROM presentaciones WHERE FK_Producto = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$tabla .= '<tr>
							<td>'.$row[$i]['Nombre'].'</td>
							<td>'.$row[$i]['Abreviatura'].'</td>
							<td>'.$row[$i]['Costo'].'</td>
							<td><button type="button" class="btn btn-sm btn-primary bSeleCamPres" attrID="'.$row[$i]['ID_Presentacion'].'">Seleccionar</button></td>
						</tr>';
					}
				}
			}

			echo $tabla;
		}
	}
}
?>
