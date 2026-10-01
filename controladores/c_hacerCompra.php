<?php
class hacerCompra {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$ImportePagadoCompra =  $omodelo->link->real_escape_string($Importe);
		$idProveedor = $omodelo->link->real_escape_string($idProveedor);
		$fechaCredito = $omodelo->link->real_escape_string($FechaCredito);
		$subtotal = $omodelo->link->real_escape_string($subtotal);
		$total = $omodelo->link->real_escape_string($total);
		$tipoCompra = $omodelo->link->real_escape_string($TipoCompra);
		$descuento = $omodelo->link->real_escape_string($Descuento);
		$tipoPago = $omodelo->link->real_escape_string($TipoPago);
		$detalles = $omodelo->link->real_escape_string($DetallesPago);
		$sucursal = $omodelo->link->real_escape_string($Sucursal);
		$idOrden = $omodelo->link->real_escape_string($idOrden);
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

		$query = "INSERT INTO compras SET FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', FK_Proveedor= '$idProveedor', Total= '$total', Anticipo= '$ImportePagadoCompra', Estatus= '$estatus', Fecha_Registro = NOW(), Fecha_Credito = '$fechaCredito', Tipo_Compra = '$tipoCompra', Descuento = '$descuento', FK_Sucursal = '$sucursal'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$IDCompra = mysqli_insert_id($omodelo->link);

			foreach ($datos as $fila) {

				//$calcularSubtotal = floatval($fila[1]) * floatval($fila[2]);
				$queryDetalles = "INSERT INTO detalle_compras SET FK_Compra = '$IDCompra', FK_Producto = '".$fila[0]."', Costo = '".$fila[1]."', Cantidad = '".$fila[2]."', FK_Presentacion = '".$fila[3]."', Subtotal = '".$fila[8]."', Descuento = '".$fila[5]."', Impuesto = '".$fila[6]."', Costo_Neto = '".$fila[7]."', Regalado = '".$fila[9]."', Costo_Bruto = '".$fila[10]."'";
				$errorDetalles = $omodelo->_insertar($queryDetalles);

				if ($errorDetalles == "si") {
					echo "Error detalles: ".mysqli_error($omodelo->link);
				}else{
					//ACTUALIZAR EL COSTO DE LA PRESENTACION O PRODUCTO
					if ($fila[3] == "" || $fila[3] == 0) {//actualizar productos
						$queryDetalles = "UPDATE productos SET Costo = '".$fila[1]."', Costo_Bruto = '".$fila[10]."', Fecha_Costo = NOW(), Costo_Neto = '".$fila[7]."' WHERE ID_Producto = '".$fila[0]."'";
					}else{//actualizar presentaciones
						$queryDetalles = "UPDATE presentaciones SET Costo = '".$fila[1]."', Costo_Bruto = '".$fila[10]."', Fecha_Costo = NOW(), Costo_Neto = '".$fila[7]."' WHERE ID_Presentacion = '".$fila[3]."' AND FK_Producto = '".$fila[0]."'";
					}
					$errorDetalles = $omodelo->_insertar($queryDetalles);

					if ($errorDetalles == "si") {
						echo "Error detalles: ".mysqli_error($omodelo->link);
					}
				}
			}

			echo "Correcto~".$IDCompra.'~'.$sucursal;

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

			if(trim($idOrden) != ''){ 
				$query1 = "UPDATE ordenes_compra SET Estatus = 'Completada' WHERE ID_Orden_Compra = '$idOrden'";
				$error = $omodelo->_insertar($query1);
				
				if ($error == "si") {
					echo "Error Orden: ".mysqli_error($omodelo->link);
				}
			}

			if($ImportePagadoCompra > 0){ 
				$usuario = $_SESSION['user_admin']['ID_Usuario'];
				$queryPago = "INSERT INTO pagos SET FK_Compra = '$IDCompra', Monto = '$ImportePagadoCompra', Concepto = '$tipoPagoA', Tipo_Pago = '$tipoPago', Fecha = NOW(), FK_Usuario = '$usuario', Detalles_Pago = '$detalles'";
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
					$busqueda .= "CONCAT(ID_Producto, Descripcion, Codigo, Costo, Nombre_Unidad, Abreviatura_Unidad) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}	  

			$query = "SELECT ID_Producto, Codigo, Descripcion, Nombre_Unidad AS NombrePresentacion, Abreviatura_Unidad AS Abreviatura, Costo, Descuento, IFNULL((SELECT SUM(Cantidad) FROM inventario WHERE FK_Presentacion = 0 AND inventario.FK_Producto = ID_Producto), 0) AS Existencia, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$clase = '';
						if($omodelo->permisos() != 'Administrador'){
							if (@$omodelo->permisos()['v_compras'][2] == '0' || @$omodelo->permisos()['v_orden_compra'][5] == '0') {
								$clase = 'oculto';
							}
						}

						$opcionesImpuestos = "<option value=''>No</option>";
						$query2 = "SELECT ID_Impuesto, Nombre, Porcentaje FROM impuestos";
						$row2 = $omodelo->_consultar($query2);
						$numerofilasimpuestos = $omodelo->numerofilas;

						if($row2 == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilasimpuestos > 0){
								for ($x=0; $x < $numerofilasimpuestos; $x++) { 
									$opcionesImpuestos .= "<option value='".$row2[$x]["ID_Impuesto"]."' impuesto='".$row2[$x]["Porcentaje"]."'>".$row2[$x]["Nombre"]." - ".$row2[$x]["Porcentaje"]."%</option>";
								}
							}
						}

						$presentacion = '';
						if($row[$i]['NombrePresentacion'] != '' || $row[$i]['Abreviatura'] != ''){
							$presentacion .= '<button opciones="'.$opcionesImpuestos.'" style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresCom" presentacion="0" costo="'.$row[$i]['Costo'].'">Ex. '.number_format($row[$i]['Existencia'], 2).' - <span>'.$row[$i]['NombrePresentacion'].'('.$row[$i]['Abreviatura'].')</span> <span class="'.$clase.'">$'.number_format($row[$i]['Costo'], 2).'</span></button>';
						}else{
							$presentacion .= '<button opciones="'.$opcionesImpuestos.'" style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresCom" presentacion="0" costo="'.$row[$i]['Costo'].'"><span>Sin presentación</span> <span class="'.$clase.'">$'.number_format($row[$i]['Costo'], 2).'</span></button>';
						}

						//Costo_Bruto AS Costo
						$query1 = "SELECT ID_Presentacion, Nombre, Abreviatura, Clave_CFDI, Costo, Descuento, IFNULL((SELECT SUM(Cantidad) FROM inventario WHERE FK_Presentacion = ID_Presentacion AND FK_Producto = '".$row[$i]['ID_Producto']."'), 0) AS Existencia FROM presentaciones WHERE FK_Producto = '".$row[$i]['ID_Producto']."' ORDER BY Nombre";
						$row1 = $omodelo->_consultar($query1);
						$numerofilas1 = $omodelo->numerofilas;

						if($row1 == 'si'){
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas1 > 0){
								for($x=0; $x < $numerofilas1; $x++){
									$presentacion .= '<button  opciones="'.$opcionesImpuestos.'" style="margin: 2px;" type="button" class="btn btn-sm btn-primary bSelePresCom" presentacion="'.$row1[$x]['ID_Presentacion'].'" costo="'.$row1[$x]['Costo'].'" descuento="'.$row1[$x]['Descuento'].'">Ex. '.number_format($row1[$x]['Existencia'], 2).' - <span>'.$row1[$x]['Nombre'].'('.$row1[$x]['Abreviatura'].')</span> <span class="'.$clase.'">$'.number_format($row1[$x]['Costo'], 2).'</span></button>';
								}
							}
						}
						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Codigo' => "<b class='codigo'>".$row[$i]['Codigo']."</b>",
							'Descripcion' => "<b class='NombreProducto'>".$row[$i]['Descripcion']."</b>",
							'Presentacion' => $presentacion,
							'Descuento' => $row[$i]['Descuento']
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
				$busqueda = 'AND ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(ID_Proveedor, Nombre, Calle, No_Exterior, No_Interior, Empresa, Telefono, RFC, Credito, Razon_Social) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Proveedor, Nombre, Calle, No_Exterior, No_Interior, Empresa, Telefono, RFC, Credito, Razon_Social, (SELECT COUNT(*) FROM proveedores WHERE ID_Proveedor != 1 $busqueda) AS Num FROM proveedores WHERE ID_Proveedor != 1 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

			$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, Descuento, CONCAT(Nombre_Unidad, ' (', Abreviatura_Unidad, ')') AS NombrePresentacion, Costo FROM productos WHERE Codigo = '$Codigo'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo["producto"] = $row[0];
				}
			}

			$opcionesImpuestos = "<option value=''>No</option>";
			$query2 = "SELECT ID_Impuesto, Nombre, Porcentaje FROM impuestos";
			$row2 = $omodelo->_consultar($query2);
			$numerofilasimpuestos = $omodelo->numerofilas;

			if($row2 == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilasimpuestos > 0){
					for ($i=0; $i < $numerofilasimpuestos; $i++) { 
						$opcionesImpuestos .= "<option value='".$row2[$i]["ID_Impuesto"]."' impuesto='".$row2[$i]["Porcentaje"]."'>".$row2[$i]["Nombre"]." - ".$row2[$i]["Porcentaje"]."%</option>";
					}
					$arreglo["impuestos"][] = array($opcionesImpuestos);
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "creditoProveedor"){
			$IDProveedor =  $omodelo->link->real_escape_string($IDProveedor);
			
			$query = "SELECT ID_Proveedor, Credito, IFNULL(Credito - (SELECT SUM(Total -IFNULL((SELECT SUM(Monto) FROM pagos WHERE FK_Compra = ID_Compra), 0)) FROM compras WHERE FK_Proveedor = ID_Proveedor AND Tipo_Compra = 'Credito' AND Estatus = 0), Credito) AS RestanteCredito FROM proveedores WHERE ID_Proveedor = '$IDProveedor'";
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
		}else if($tipo == "consultarOrdenes"){
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
					$busqueda .= "CONCAT(DATE_FORMAT(ordenes_compra.Fecha_Registro, '%d-%m-%Y %r'), LPAD(ID_Orden_Compra, 8, '0'), proveedores.Nombre, proveedores.Empresa, Total, Estatus, sucursales.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Orden_Compra, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, FK_Proveedor, proveedores.Nombre AS Proveedor, proveedores.Empresa AS Empresa, proveedores.Telefono AS Telefono, proveedores.Razon_Social AS RazonSocial, Total, Estatus AS Detalles, DATE_FORMAT(ordenes_compra.Fecha_Registro, '%d-%m-%Y %r') AS DatosF, ordenes_compra.Fecha_Registro AS Datos, sucursales.Nombre AS Sucursal, FK_Sucursal, (SELECT COUNT(*) FROM ordenes_compra INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal $busqueda) AS Num FROM ordenes_compra INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarCompras = 0;
					for($i=0; $i<$numerofilas; $i++){
						$estatus = ''; $botonCargar = ''; $botonEliminar = '';
						$folio = str_pad($row[$i]['ID_Orden_Compra'], 8, "0", STR_PAD_LEFT);

						if ($row[$i]['Detalles'] == "Pendiente") {
							$estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';

							if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_orden_compra'][3] == '1'){
								$botonCargar = '<button class="btn btn-primary btn-sm bCargarOrden" attrID="'.$row[$i]['ID_Orden_Compra'].'" title="Seleccionar"><i class="fas fa-square-check"></i></button>';
							}	
						}else if($row[$i]['Detalles'] == "Completada"){
							$estatus = '<span class="badge rounded-pill bg-success">Completada</span>';
							$botonCargar = '';
						}

						$botonPermisosEliminar = "";
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_orden_compra'][4] == '1') {
							$botonEliminar = '<button class="btn btn-danger btn-sm bEliminarOrden" attrID="'.$row[$i]['ID_Orden_Compra'].'"><i class="fas fa-trash" title="Eliminar Orden"></i></button>';	
						}

						$total = 'Total: <b style="font-size: 15px;">$'.number_format($row[$i]['Total'],2 ).'</b>';
						if($omodelo->permisos() != 'Administrador'){
							if(@$omodelo->permisos()['v_compras'][2] == '0' || @$omodelo->permisos()['v_orden_compra'][5] == '0'){
								$total = '';
							} 
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Orden_Compra'],
							'Datos' => "Fecha: <b>".$row[$i]['DatosF']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b><br>Sucursal: <b>".$row[$i]['Sucursal']."</b>",
							'Proveedor' => 'Nombre: <b>'.$row[$i]['Proveedor'].'</b><br>Empresa: <b>'.$row[$i]['Empresa'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Razón social: <b>'.$row[$i]['RazonSocial'].'</b>',
							'Total' => $total,
							'Detalles' => $estatus.'<br><button class="btn btn-link btn-sm bVerProductosOrden" attrid="'.$row[$i]['ID_Orden_Compra'].'">Ver productos</button>',
							'Acciones' => $botonCargar.' '.$botonEliminar.' <button class="btn btn-success btn-sm bImprimirTicketOrden" attrID="'.$row[$i]['ID_Orden_Compra'].'" idSucursal="'.$row[$i]['FK_Sucursal'].'" title="Imprimir Ticket"><i class="fas fa-print"></i></button>',
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);	
		}
	}	

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo =  $omodelo->link->real_escape_string($tipo);

		if ($tipo == "consultarPrese"){
			$id =  $omodelo->link->real_escape_string($id);
			$tabla = '';

			$clase = '';
			if($omodelo->permisos() != 'Administrador'){
				if(@$omodelo->permisos()['v_compras'][2] == '0' || @$omodelo->permisos()['v_orden_compra'][5] == '0'){
					$clase = 'oculto';
				} 
			}

			$query = "SELECT Nombre_Unidad, Abreviatura_Unidad, Costo, Descuento FROM productos WHERE ID_Producto = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$tabla .= '<tr>
						<td>'.$row[0]['Nombre_Unidad'].'</td>
						<td>'.$row[0]['Abreviatura_Unidad'].'</td>
						<td class="'.$clase.'"><span class="dinero">'.$row[0]['Costo'].'</span></td>
						<td><span class="porcentaje">'.$row[0]['Descuento'].'</span></td>
						<td><button type="button" class="btn btn-sm btn-primary bSeleCamPres" attrID="0">Seleccionar</button></td>
					</tr>';
				}
			}

			//Costo_Bruto AS Costo
			$query = "SELECT ID_Presentacion, Nombre, Abreviatura, Costo, Descuento FROM presentaciones WHERE FK_Producto = '$id'";
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
							<td class="'.$clase.'"><span class="dinero">'.$row[$i]['Costo'].'</span></td>
							<td><span class="porcentaje">'.$row[$i]['Descuento'].'</span></td>
							<td><button type="button" class="btn btn-sm btn-primary bSeleCamPres" attrID="'.$row[$i]['ID_Presentacion'].'">Seleccionar</button></td>
						</tr>';
					}
				}
			}

			echo $tabla;
		}else if($tipo == 'consultarProductosOrden'){
			$id =  $omodelo->link->real_escape_string($id);
			$tabla = '';

			$query = "SELECT ID_Detalle_Orden, FK_Presentacion, Descripcion, Nombre_Unidad, Abreviatura_Unidad, detalles_orden.Costo AS Costo, Cantidad, Regalado, Subtotal, Nombre, Abreviatura, detalles_orden.Descuento AS Descuento, Impuesto, Costo_Neto FROM detalles_orden INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Orden = '$id' ORDER BY Descripcion";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$presentacion = 'Sin presentación';
						if($row[$i]['FK_Presentacion'] == '0'){
							if($row[$i]['Nombre_Unidad'] != '' || $row[$i]['Abreviatura_Unidad'] != ''){
								$presentacion = $row[$i]['Nombre_Unidad'].' ('.$row[$i]['Abreviatura_Unidad'].')';
							}
						}else{
							$presentacion = $row[$i]['Nombre'].' ('.$row[$i]['Abreviatura'].')';
						}

						$clase = '';
						if($omodelo->permisos() != 'Administrador'){
							if(@$omodelo->permisos()['v_compras'][2] == '0' || @$omodelo->permisos()['v_orden_compra'][5] == '0'){
								$clase = 'oculto';
							} 
						}

						$tabla .= '<tr>
							<td>'.$row[$i]['Descripcion'].'<br>'.$presentacion.'</td>
							<td>'.$row[$i]['Cantidad'].'<br>Regalado: '.$row[$i]['Regalado'].'</td>
							<td class="'.$clase.'"><span class="dinero">'.$row[$i]['Costo'].'</span></td>
							<td class="'.$clase.'"><span class="">'.$row[$i]['Descuento'].'%</span></td>
							<td class="'.$clase.'"><span class="">'.$row[$i]['Impuesto'].'%</span></td>
							<td class="'.$clase.'"><span class="dinero">'.$row[$i]['Costo_Neto'].'</span></td>
							<td class="'.$clase.'"><span class="dinero">'.$row[$i]['Subtotal'].'</span></td>
						</tr>';
					}
				}
			}

			echo $tabla;
		}else if($tipo == "consultarOrden"){
			$id = $omodelo->link->real_escape_string($id); 
			$array = null;

			$query = "SELECT ID_Orden_Compra, FK_Proveedor, proveedores.Nombre AS Proveedor, proveedores.Empresa AS Empresa, proveedores.Telefono AS Telefono, proveedores.Razon_Social AS RazonSocial, Total, Estatus, DATE_FORMAT(ordenes_compra.Fecha_Registro, '%d-%m-%Y %r') AS Datos, sucursales.Nombre AS Sucursal, FK_Sucursal FROM ordenes_compra INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE ID_Orden_Compra = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$productos = array();
					$query1 = "SELECT ID_Detalle_Orden, detalles_orden.FK_Producto AS FK_Producto, FK_Presentacion, Descripcion, productos.Codigo, Nombre_Unidad, Abreviatura_Unidad, detalles_orden.Costo AS Costo, Cantidad, Regalado, Subtotal, Nombre, Abreviatura, detalles_orden.Descuento AS Descuento, Impuesto, detalles_orden.Costo_Neto FROM detalles_orden INNER JOIN productos ON detalles_orden.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Orden = '$id' ORDER BY Descripcion";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas1 = $omodelo->numerofilas;

					if($row1 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas1 > 0){
							for ($i=0; $i < $numerofilas1; $i++) { 
								$presentacion = 'Sin presentación';
								if($row1[$i]['FK_Presentacion'] == '0'){
									if($row1[$i]['Nombre_Unidad'] != '' || $row1[$i]['Abreviatura_Unidad'] != ''){
										$presentacion = $row1[$i]['Nombre_Unidad'].' ('.$row1[$i]['Abreviatura_Unidad'].')';
									}
								}else{
									$presentacion = $row1[$i]['Nombre'].' ('.$row1[$i]['Abreviatura'].')';
								}

								$opcionesImpuestos = "<option value=''>No</option>";
								$query2 = "SELECT ID_Impuesto, Nombre, Porcentaje FROM impuestos";
								$row2 = $omodelo->_consultar($query2);
								$numerofilasimpuestos = $omodelo->numerofilas;

								if($row2 == 'si'){
									echo "Error: ".mysqli_error($omodelo->link);
								}else{
									if($numerofilasimpuestos > 0){
										for ($x=0; $x < $numerofilasimpuestos; $x++) { 
											if ($row2[$x]["Porcentaje"] == $row1[$i]["Impuesto"]) {
												$opcionesImpuestos .= "<option selected value='".$row2[$x]["ID_Impuesto"]."' impuesto='".$row2[$x]["Porcentaje"]."'>".$row2[$x]["Nombre"]." - ".$row2[$x]["Porcentaje"]."%</option>";
											}else{
												$opcionesImpuestos .= "<option value='".$row2[$x]["ID_Impuesto"]."' impuesto='".$row2[$x]["Porcentaje"]."'>".$row2[$x]["Nombre"]." - ".$row2[$x]["Porcentaje"]."%</option>";
											}
										}
									}
								}

								$productos[] = array(
									'ID_Detalle_Orden' => $row1[$i]['ID_Detalle_Orden'], 
									'FK_Producto' => $row1[$i]['FK_Producto'], 
									'FK_Presentacion' => $row1[$i]['FK_Presentacion'], 
									'Descripcion' => $row1[$i]['Descripcion'], 
									'Codigo' => $row1[$i]['Codigo'], 
									'Nombre_Unidad' => $row1[$i]['Nombre_Unidad'], 
									'Abreviatura_Unidad' => $row1[$i]['Abreviatura_Unidad'], 
									'Costo' => $row1[$i]['Costo'], 
									'Cantidad' => $row1[$i]['Cantidad'], 
									'Regalado' => $row1[$i]['Regalado'],
									'Subtotal' => $row1[$i]['Subtotal'], 
									'Nombre' => $row1[$i]['Nombre'], 
									'Abreviatura' => $row1[$i]['Abreviatura'], 
									'Presentacion' => $presentacion,
									'Descuento' => $row1[$i]["Descuento"],
									'Impuesto' => $opcionesImpuestos,
									'Costo_Neto' => $row1[$i]["Costo_Neto"],
								);
							}
						}
					}

					$array = array('ID_Orden_Compra' => $row[0]['ID_Orden_Compra'], 'FK_Proveedor' => $row[0]['FK_Proveedor'], 'Proveedor' => $row[0]['Proveedor'], 'Empresa' => $row[0]['Empresa'], 'Telefono' => $row[0]['Telefono'], 'RazonSocial' => $row[0]['RazonSocial'], 'Total' => $row[0]['Total'], 'Estatus' => $row[0]['Estatus'], 'Datos' => $row[0]['Datos'], 'Sucursal' => $row[0]['Sucursal'], 'FK_Sucursal' => $row[0]['FK_Sucursal'], 'Productos' => $productos);
				}
			}

			echo json_encode($array);
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$tipo =  $omodelo->link->real_escape_string($tipo);

		if($tipo == 'insertarOrden'){
			$idProveedor = $omodelo->link->real_escape_string($idProveedor);
			$subtotal = $omodelo->link->real_escape_string($subtotal);
			$total = $omodelo->link->real_escape_string($total);
			$descuento = $omodelo->link->real_escape_string($Descuento);
			$sucursal = $omodelo->link->real_escape_string($Sucursal);
			$datos = json_decode($Productos);
			
			$query = "INSERT INTO ordenes_compra SET FK_Proveedor= '$idProveedor', Total= '$total', Estatus= 'Pendiente', Fecha_Registro = NOW(), Descuento = '$descuento', FK_Sucursal = '$sucursal', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$IDOrden = mysqli_insert_id($omodelo->link);

				foreach ($datos as $fila) {
					$calcularSubtotal = floatval($fila[1]) * floatval($fila[2]);

					$queryDetalles = "INSERT INTO detalles_orden SET FK_Orden = '$IDOrden', FK_Producto = '".$fila[0]."', Costo = '".$fila[1]."', Cantidad = '".$fila[2]."', FK_Presentacion = '".$fila[3]."', Subtotal = '".$fila[8]."', Descuento = '".$fila[5]."', Impuesto = '".$fila[6]."', Costo_Neto = '".$fila[7]."', Regalado = '".$fila[9]."'";
					$errorDetalles = $omodelo->_insertar($queryDetalles);

					if ($errorDetalles == "si") {
						echo "Error detalles: ".mysqli_error($omodelo->link);
					}
				}

				echo "Correcto~".$IDOrden.'~'.$sucursal;

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if($tipo == 'modificarOrden'){
			$id = $omodelo->link->real_escape_string($id);
			$idProveedor = $omodelo->link->real_escape_string($idProveedor);
			$subtotal = $omodelo->link->real_escape_string($subtotal);
			$total = $omodelo->link->real_escape_string($total);
			$descuento = $omodelo->link->real_escape_string($Descuento);
			$sucursal = $omodelo->link->real_escape_string($Sucursal);
			$datos = json_decode($Productos);
			
			$query = "UPDATE ordenes_compra SET FK_Proveedor= '$idProveedor', Total= '$total', Estatus= 'Pendiente', Fecha_Registro = NOW(), Descuento = '$descuento', FK_Sucursal = '$sucursal', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' WHERE ID_Orden_Compra = '$id'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$query1 = "DELETE FROM detalles_orden WHERE FK_Orden = '$id'";
				$error1 = $omodelo->_insertar($query1);

				if ($error1 == "si") {
					echo "Error 2: ".mysqli_error($omodelo->link);
				}else{
					foreach ($datos as $fila) {
						$calcularSubtotal = floatval($fila[1]) * floatval($fila[2]);

						$queryDetalles = "INSERT INTO detalles_orden SET FK_Orden = '$id', FK_Producto = '".$fila[0]."', Costo = '".$fila[1]."', Cantidad = '".$fila[2]."', FK_Presentacion = '".$fila[3]."', Subtotal = '".$fila[8]."', Descuento = '".$fila[5]."', Impuesto = '".$fila[6]."', Costo_Neto = '".$fila[7]."', Regalado = '".$fila[9]."'";
						$errorDetalles = $omodelo->_insertar($queryDetalles);

						if ($errorDetalles == "si") {
							echo "Error detalles: ".mysqli_error($omodelo->link);
						}
					}
				}

				echo "Correcto~".$id.'~'.$sucursal;

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo =  $omodelo->link->real_escape_string($tipo);

		if($tipo == 'ordenCompra'){
			$id = $omodelo->link->real_escape_string($id);
			
			$query = "DELETE FROM ordenes_compra WHERE ID_Orden_Compra = '$id'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
	}
}
?>
