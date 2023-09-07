<?php
class compras {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$buscar =  $omodelo->link->real_escape_string($buscar);
		$limit =  $omodelo->link->real_escape_string($limit);
		$pagina =  $omodelo->link->real_escape_string($pagina);
		$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
		$orden =  $omodelo->link->real_escape_string($orden);
		$arreglo = array();

		$sucursal = $_SESSION['user_admin']['FK_Sucursal'];

		if ($omodelo->permisos() == 'Administrador'){
			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(DATE_FORMAT(compras.Fecha_Registro, '%d-%m-%Y %r'), LPAD(ID_Compra, 8, '0'), proveedores.Nombre, proveedores.Empresa, Anticipo, Total, Estatus, Tipo_Compra) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Compra, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, FK_Proveedor, proveedores.Nombre AS Proveedor, proveedores.Empresa AS Empresa, proveedores.Telefono AS Telefono, proveedores.Razon_Social AS RazonSocial, Tipo_Compra, proveedores.Credito as Credito, Anticipo, Total, Estatus, compras.Fecha_Registro AS Datos, DATE_FORMAT(compras.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, sucursales.Nombre AS Sucursal, FK_Sucursal, IFNULL((SELECT SUM(Monto) FROM pagos WHERE FK_Compra = ID_Compra), 0) AS Pagado, (SELECT COUNT(*) FROM compras INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal $busqueda) AS Num FROM compras INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		}else{
			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'AND ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(DATE_FORMAT(compras.Fecha_Registro, '%d-%m-%Y %r'), LPAD(ID_Compra, 8, '0'), proveedores.Nombre, proveedores.Empresa, Anticipo, Total, Estatus, Tipo_Compra) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Compra, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, FK_Proveedor, proveedores.Nombre AS Proveedor, proveedores.Empresa AS Empresa, proveedores.Telefono AS Telefono, proveedores.Razon_Social AS RazonSocial, Tipo_Compra, proveedores.Credito as Credito, Anticipo, Total, Estatus, compras.Fecha_Registro AS Datos, DATE_FORMAT(compras.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, sucursales.Nombre AS Sucursal, FK_Sucursal, IFNULL((SELECT SUM(Monto) FROM pagos WHERE FK_Compra = ID_Compra), 0) AS Pagado, (SELECT COUNT(*) FROM compras INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE compras.FK_Sucursal = '$sucursal' $busqueda) AS Num FROM compras INNER JOIN proveedores ON FK_Proveedor = ID_Proveedor INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE compras.FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		}

		
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
						$botonCancelar = ' <button class="btn btn-warning btn-sm CancelarCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'" title="Cancelar"><i style="color: white;" class="fas fa-circle-xmark"></i></button> ';
						$SumarCompras += $row[$i]['Total'];
					}else if($row[$i]['Estatus'] == "2"){
						$estatus = '<span class="badge rounded-pill bg-danger">Cancelada</span>';
						//$motivocancelada="Fecha de cancelación: <b>".$row[$i]['Fecha_Cancelada']."</b><br>Motivo: ".$row[$i]['Motivo_Cancelada']; 
					}else if ($row[$i]['Estatus'] == "0") {
						$estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
						$botonCancelar = ' <button class="btn btn-warning btn-sm CancelarCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'" title="Cancelar"><i style="color: white;" class="fas fa-circle-xmark"></i></button> ';
						$SumarCompras += $row[$i]['Total'];
						$botonPagos = '<button class="btn btn-primary btn-sm PagoCom" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'"><i class="fa-solid fa-sack-dollar" title="Realizar Pago"></i></button>';
					}

					$botonEliminar = "";
					$botondeCancelar = "";
					//$botonTicket = "";
						$botonEliminar = '<button class="btn btn-danger btn-sm" id="EliminarCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'"><i class="fas fa-trash" title="Eliminar Venta"></i></button>';

						$botondeCancelar = $botonCancelar;

						
						$botonTicket = '<button class="btn btn-success btn-sm" id="ImprimirTicketCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'" idSucursal="'.$row[$i]['FK_Sucursal'].'" title="Imprimir Ticket"><i class="fas fa-print"></i></button>';

					$favor = 0;
					$restante = $row[$i]['Total'] - $row[$i]['Pagado'];
					if($restante < 0){
						$favor = $restante * -1;
						$restante = 0;
					}

					$botonPermisosCancelar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_compras'][3] == '1') {
						$botonPermisosCancelar = $botondeCancelar;
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_compras'][4] == '1') {
						$botonPermisosEliminar = $botonEliminar;
					}

					$botonPermisosTicket = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_compras'][5] == '1') {
						$botonPermisosTicket = $botonTicket;
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Compra'],
						'Datos' => "Fecha: <b>".$row[$i]['Fecha_Registro']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b><br>Sucursal: <b>".$row[$i]['Sucursal']."</b>",
						'Proveedor' => 'Nombre: <b>'.$row[$i]['Proveedor'].'</b><br>Empresa: <b>'.$row[$i]['Empresa'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Razón social: <b>'.$row[$i]['RazonSocial'].'</b>',
						'Total' => 'Total: <b style="font-size: 15px;">$'.number_format($row[$i]['Total'],2 ).'</b><br>Tipo: <b>'.$row[$i]['Tipo_Compra'].'</b><br> Pago: <b>$'.number_format($row[$i]['Pagado'], 2).'</b><br>Restante: <b>$'.number_format($restante,2).'</b><br>A favor: <b>$'.number_format($favor,2).'</b>',
						'Detalles' => $estatus.'<br>'.$motivocancelada.'<br><button class="btn btn-link btn-sm" id="VerProductosCompra" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'">Ver productos</button><br><button class="btn btn-link btn-sm" id="VerHistorialPagos" attrid="'.$row[$i]['ID_Compra'].'" folio="'.$folio.'">Ver pagos</button>',
						'Acciones' => $botonPermisosEliminar.' '.$botonPermisosCancelar.' '.$botonPagos.' '.$botonPermisosTicket,
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
				$query2 = "UPDATE pagos SET Archivo = '".$ID.'_'.$nombreDoc."' WHERE ID_Pago = '$ID'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$ID.'_'.$nombreDoc);
				}
			}

			echo 'Correcto';
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}
	
	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST); 
		$fecha = date('Y-m-d H:i:s'); 

		$IDCompra = $omodelo->link->real_escape_string($IDCompra);
		
		$query = "UPDATE compras SET Estatus = '2' WHERE ID_Compra = '$IDCompra'";
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
		$tipo = $omodelo->link->real_escape_string($tipo);
		
		if($tipo == 'productos'){
			$IDCompra = $omodelo->link->real_escape_string($IDCompra);
			$tabla = "";

			$query = "SELECT ID_Detalle_Compra, FK_Compra, FK_Presentacion, Codigo, Descripcion, Imagen, detalle_compras.Costo AS Costo, Cantidad, Subtotal, Nombre_Unidad, Abreviatura_Unidad, presentaciones.Nombre AS Presentacion, Abreviatura FROM detalle_compras INNER JOIN productos ON detalle_compras.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Compra = '$IDCompra'";
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

						$presentacion = 'Sin presentación';
						if($row[$i]['FK_Presentacion'] == '0'){
							if($row[$i]['Nombre_Unidad'] != '' || $row[$i]['Abreviatura_Unidad'] != ''){
								$presentacion = $row[$i]['Nombre_Unidad'].' ('.$row[$i]['Abreviatura_Unidad'].')';
							}
						}else{
							$presentacion = $row[$i]['Presentacion'].' ('.$row[$i]['Abreviatura'].')';
						}

						$tabla .= "
							<tr>
								<td>".$imagen.$row[$i]["Codigo"]."</td>
								<td>".$row[$i]["Descripcion"]."<br>Presentación: <b>".$presentacion."</b></td>
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

			$query = "SELECT FK_Proveedor, proveedores.Nombre AS Proveedor, compras.Total AS Total, IFNULL((SELECT SUM(Monto) FROM pagos WHERE FK_Compra = '$IDCompra'), 0) AS TotalPagos FROM compras LEFT JOIN proveedores ON ID_Proveedor = FK_Proveedor WHERE ID_Compra = '$IDCompra'";
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
			$omodelo = new m_modelo();
			extract($_POST);

			$IDCompra = $omodelo->link->real_escape_string($IDCompra);
			$imagen = '';

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
					$busqueda .= "CONCAT(ID_Pago, FK_Compra, Concepto, Monto, Tipo_Pago, Fecha, Detalles_Pago) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Pago, FK_Compra, Concepto, Monto, Tipo_Pago, Fecha, Detalles_Pago, Archivo, (SELECT COUNT(*) FROM pagos WHERE FK_Compra = '$IDCompra' $busqueda) AS Num FROM pagos WHERE FK_Compra = '$IDCompra' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

						$botonEliminar = "";
						$botonEliminar = '<button class="btn btn-danger btn-sm" id="EliminarPago" attrid="'.$row[$i]['ID_Pago'].'" idCompra="'.$row[$i]['FK_Compra'].'"><i class="fas fa-trash"></i></button>';
						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Pago'],
							'Fecha' => $row[$i]["Fecha"],
							'Concepto' => $row[$i]["Concepto"],
							'TipoPago' => $row[$i]["Tipo_Pago"],
							'Monto' => '$'.number_format($row[$i]["Monto"], 2),
							'Detalles' => $row[$i]["Detalles_Pago"],
							'Comprobante' => $imagen,
							'Accion' => $botonEliminar
						);
					}
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == 'eliminarPago'){
			$omodelo = new m_modelo();
			extract($_POST);
			
			$IDPago = $omodelo->link->real_escape_string($IDPago);	

			$query = "SELECT Archivo FROM pagos WHERE ID_Pago = '$IDPago'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					$query1 = "DELETE FROM pagos WHERE ID_Pago='$IDPago'";
					$error = $omodelo->_insertar($query1);

					if ($error == "si") {
						echo "Error 1: " . mysqli_error($omodelo->link);
					} else {
						echo "Correcto";

						if ($row[0]["Archivo"] !="" && file_exists("vistas/assets/archivos/fotosPagos/".$row[0]["Archivo"]."")) {
							unlink("vistas/assets/archivos/fotosPagos/".$row[0]["Archivo"]."");
						}

						$omodelo->movimiento($query1, $_SESSION['user_admin']['ID_Usuario']);
					}
				}
			}
		}else if($tipo == 'inventario'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "SELECT ID_Detalle_Compra, FK_Producto, FK_Presentacion, Costo, Cantidad, Subtotal, FK_Sucursal FROM detalle_compras INNER JOIN compras ON FK_Compra = ID_Compra WHERE FK_Compra = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$query1 = "UPDATE inventario SET Cantidad = Cantidad - ".$row[$i]['Cantidad']." WHERE FK_Producto = '".$row[$i]['FK_Producto']."' AND FK_Presentacion = '".$row[$i]['FK_Presentacion']."' AND FK_Sucursal = '".$row[$i]['FK_Sucursal']."'";
						$error = $omodelo->_insertar($query1);

						if ($error == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}	
					}
				}

				echo "Correcto";
			}
		}
	}
}
?>
