
<?php
class hacerventa {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

	}

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$arreglo = null;
		$fecha = date('Y-m-d H:i:s'); 
		if ($tipo == "ConsultarCajas") {
			$query = "SELECT ID_Caja, cajas.Nombre AS Caja, Detalles, cajas.Estado AS Estado, FK_Usuario, CONCAT(usuarios.Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) AS Usuario, ID_Detalle_Caja, DATE_FORMAT(Fecha_Abrir, '%d-%m-%Y %r') AS Fecha_Abrir, Monto_Abrir, FK_Usuario_Abrir, (SELECT CONCAT(usuarios.Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) FROM usuarios WHERE ID_Usuario = FK_Usuario_Abrir) AS Abrio, DATE_FORMAT(Fecha_Cierre, '%d-%m-%Y %r') AS Fecha_Cierre, Monto_Cierre, FK_Usuario_Cierre, FK_Sucursal, sucursales.Nombre AS Sucursal FROM cajas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal LEFT JOIN usuarios ON FK_Usuario = ID_Usuario LEFT JOIN detalles_caja ON FK_Caja = ID_Caja AND ID_Detalle_Caja = (SELECT MAX(ID_Detalle_Caja) FROM cajas WHERE FK_Caja = ID_Caja) ORDER BY cajas.Nombre";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo[] = array(
							"ID_Caja" => $row[$i]["ID_Caja"],
							"Caja" => $row[$i]["Caja"],
							"Detalles" => $row[$i]["Detalles"],
							"Estado" => $row[$i]["Estado"],
							"FK_Usuario" => $row[$i]["FK_Usuario"],
							"Usuario" => $row[$i]["Usuario"],
							"ID_Detalle_Caja" => $row[$i]["ID_Detalle_Caja"],
							"Fecha_Abrir" => $row[$i]["Fecha_Abrir"],
							"Monto_Abrir" => $row[$i]["Monto_Abrir"],
							"FK_Usuario_Abrir" => $row[$i]["FK_Usuario_Abrir"],
							"Abrio" => $row[$i]["Abrio"],
							"Fecha_Cierre" => $row[$i]["Fecha_Cierre"],
							"Monto_Cierre" => $row[$i]["Monto_Cierre"],
							"FK_Usuario_Cierre" => $row[$i]["FK_Usuario_Cierre"],
							"FK_Sucursal" => $row[$i]["FK_Sucursal"],
							"Sucursal" => $row[$i]["Sucursal"]
						);
					}
				}
				echo json_encode($arreglo);
			}

		}else if ($tipo == "AbrirCaja") {
			$query = "UPDATE cajas SET Estado = '1', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' WHERE ID_Caja = '$Caja'";
			$error = $omodelo->_insertar($query);
				
			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if ($Estatus == 0) {
					$query2 = "INSERT INTO detalles_caja SET FK_Caja = '$Caja', Fecha_Abrir = '$fecha', Monto_Abrir = '$Monto', FK_Usuario_Abrir = '".$_SESSION['user_admin']['ID_Usuario']."'";
					$error2 = $omodelo->_insertar($query2);
						
					if ($error2 == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}
					echo "Correcto";
					
				}else{
					$query2 = "INSERT INTO historial_caja SET FK_Detalle_Caja = '$Detalle', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."', Fecha_Uso = '$fecha'";
					$error2 = $omodelo->_insertar($query2);
						
					if ($error2 == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}
					echo "Correcto";
				}
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
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
					$busqueda .= "CONCAT(ID_Producto, Codigo, productos.Descripcion) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, Clase, Abreviatura AS Unidad, Poner_Unidad, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Precio_Mayoreo_General, areas.Nombre AS NombreArea, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia, detalles_productos.Costo AS Costo, detalles_productos.Precio AS Precio, detalles_productos.Precio_Mayoreo AS Precio_Mayoreo, detalles_productos.Minimo AS Minimo, detalles_productos.Maximo AS Maximo, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos LEFT JOIN detalles_productos ON detalles_productos.FK_Producto = ID_Producto AND detalles_productos.FK_Sucursal = '$sucursal' LEFT JOIN unidades ON FK_Unidad = ID_Unidad LEFT JOIN areas ON FK_Area = ID_Area LEFT JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						if ($row[$i]['Precio'] == "") {
							$row[$i]['Precio'] = 0;
						}

						if ($row[$i]['Precio_Mayoreo'] == "") {
							$row[$i]['Precio_Mayoreo'] = 0;
						}

						if ($row[$i]['Existencia'] == "") {
							$row[$i]['Existencia'] = 0;
						}

						if ($row[$i]['NombreArea'] == "") {
							$row[$i]['NombreArea'] = "No hay area registrada";
						}

						if ($row[$i]['Precio_Mayoreo'] == "" || $row[$i]['Precio_Mayoreo'] < 0) {
							$row[$i]['Precio_Mayoreo'] = $row[$i]['Precio_Mayoreo_General'];
						}

						if ($row[$i]['Precio'] == "" || $row[$i]['Precio'] < 0) {
							$row[$i]['Precio'] = $row[$i]['Precio_General'];
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Descripcion' => $row[$i]['Descripcion'],
							'Codigo' => $row[$i]['Codigo'],
							'Clase' => $row[$i]['Clase'],
							'Precio' => $row[$i]['Precio'],
							'Precio Mayoreo' => $row[$i]['Precio_Mayoreo'],
							'Area' => $row[$i]['NombreArea'],
							'Existencia' => $row[$i]['Existencia'],
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "AgregarProducto"){
			$query = "SELECT ID_Producto, Codigo, productos.Descripcion AS Descripcion, Clase, Abreviatura AS Unidad, Poner_Unidad, productos.Costo AS Costo_General, productos.Precio AS Precio_General, productos.Precio_Mayoreo AS Precio_Mayoreo_General, areas.Nombre AS NombreArea, Detalles, productos.Minimo AS Minimo_General, productos.Maximo AS Maximo_General, Fecha_Registro, inventario.Cantidad AS Existencia, detalles_productos.Costo AS Costo, detalles_productos.Precio AS Precio, detalles_productos.Precio_Mayoreo AS Precio_Mayoreo, detalles_productos.Minimo AS Minimo, detalles_productos.Maximo AS Maximo FROM productos LEFT JOIN detalles_productos ON detalles_productos.FK_Producto = ID_Producto AND detalles_productos.FK_Sucursal = '$sucursal' LEFT JOIN unidades ON FK_Unidad = ID_Unidad LEFT JOIN areas ON FK_Area = ID_Area LEFT JOIN inventario ON inventario.FK_Producto = ID_Producto AND inventario.FK_Sucursal = '$sucursal' WHERE Tipo = 1 AND Codigo = '$codigo'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}else{
					echo "No encontrado";
				}
			}
		}else if($tipo == "GuardarEntradaDinero"){
			$query = "INSERT INTO dinero SET Tipo = 'Entrada', FK_Detalle_Caja = '$DetalleCaja', Monto = '$Cantidad', Motivo = '$Motivo', Fecha_Registro = '$fecha', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);
				
			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if($tipo == "GuardarSalidaDinero"){
			$query = "INSERT INTO dinero SET Tipo = 'Salida', FK_Detalle_Caja = '$DetalleCaja', Monto = '$Cantidad', Motivo = '$Motivo', Fecha_Registro = '$fecha', FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$error = $omodelo->_insertar($query);
				
			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if($tipo == "ConsultarEntradasTurno"){
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
					$busqueda .= "CONCAT(ID_Dinero, Tipo, FK_Detalle_Caja, Monto, Motivo, FK_Usuario, Fecha_Registro) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Dinero, Tipo, FK_Detalle_Caja, Monto AS Cantidad, Motivo, FK_Usuario, Fecha_Registro AS Fecha, (SELECT COUNT(*) FROM dinero WHERE Tipo = 'Entrada' AND FK_Detalle_Caja = '$DetalleCaja' $busqueda) AS Num FROM dinero  WHERE Tipo = 'Entrada' AND FK_Detalle_Caja = '$DetalleCaja' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						
						$arreglo['data'][$i] = array(
							'ID_Dinero' => $row[$i]['ID_Dinero'],
							'Cantidad' => "<b class='dinero'>".$row[$i]['Cantidad']."</b>",
							'Motivo' => $row[$i]['Motivo'],
							'Fecha' => $row[$i]['Fecha']
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "ConsultarSalidasTurno"){
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
					$busqueda .= "CONCAT(ID_Dinero, Tipo, FK_Detalle_Caja, Monto, Motivo, FK_Usuario, Fecha_Registro) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Dinero, Tipo, FK_Detalle_Caja, Monto AS Cantidad, Motivo, FK_Usuario, Fecha_Registro AS Fecha, (SELECT COUNT(*) FROM dinero WHERE Tipo = 'Salida' AND FK_Detalle_Caja = '$DetalleCaja' $busqueda) AS Num FROM dinero  WHERE Tipo = 'Salida' AND FK_Detalle_Caja = '$DetalleCaja' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						
						$arreglo['data'][$i] = array(
							'ID_Dinero' => $row[$i]['ID_Dinero'],
							'Cantidad' => "<b class='dinero'>".$row[$i]['Cantidad']."</b>",
							'Motivo' => $row[$i]['Motivo'],
							'Fecha' => $row[$i]['Fecha']
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "ConsultarImpuestosProducto"){
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
					$busqueda .= "CONCAT(ID_Impuesto, Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase, Ticket, Producto, Predeterminado) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Impuesto, Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase, Ticket, Producto, Predeterminado, (SELECT COUNT(*) FROM impuestos WHERE Producto = '1' $busqueda) AS Num FROM impuestos WHERE Producto = '1' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$checked = "";
						$queryImpuestos = "SELECT ID_Detalle_Im_Producto, FK_Producto, FK_Sucursal, FK_Impuesto FROM detalles_impuestos_productos WHERE FK_Producto = '".$IDProducto."' AND FK_Impuesto = '". $row[$i]['ID_Impuesto']."'";
						$rowI = $omodelo->_consultar($queryImpuestos);
						$numerofilasI = $omodelo->numerofilas;
						if($rowI == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilasI > 0){
								$checked = "checked";
							}
						}

						$detalles = ""; 
						if ($row[$i]['Clave_CFDI'] != "") {
							$detalles .= "Clave CFDI: ".$row[$i]['Clave_CFDI']."<br>"; 
						}

						if ($row[$i]['Tipo_Factor'] != "") {
							$detalles .= "Tipo de factor: ".$row[$i]['Tipo_Factor']."<br>"; 
						}

						if ($row[$i]['Clase'] != "") {
							$detalles .= "Clase: ".$row[$i]['Clase']."<br>"; 
						}

						if ($detalles == "") {
							$detalles = "No hay datos registrados"; 
						}

						$arreglo['data'][$i] = array(
							'ID_Impuesto' => $row[$i]['ID_Impuesto'],
							'Aplicar' => '<input class="form-check-input CheckImpuesto" '.$checked.' type="checkbox" attrid="'.$row[$i]["ID_Impuesto"].'" cantidad="'.$row[$i]["Porcentaje"].'">',
							'Nombre' => $row[$i]['Nombre'],
							'Porcentaje' => number_format($row[$i]["Porcentaje"], 2)."%",
							'Detalles' => $detalles
						);	
					}
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			echo json_encode($arreglo);	
		}else if($tipo == "ConsultarClientesVenta"){
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
					$busqueda .= "CONCAT(ID_Cliente, Nombre, Direccion, Telefono, Foto) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Cliente, Nombre, Direccion, Telefono, Foto, (SELECT COUNT(*) FROM clientes $busqueda) AS Num FROM clientes $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$contacto = "";
						$foto = '<a href="vistas/assets/archivos/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
						if ($row[$i]["Foto"] != "") {
							if($row[$i]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosClientes/".$row[$i]["Foto"])){
								$foto = '<a href="vistas/assets/archivos/fotosClientes/'.$row[$i]["Foto"].'" data-fancybox="images">
										<div style="background-image: url('."'".'vistas/assets/archivos/fotosClientes/'.$row[$i]["Foto"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
										</div>
									</a><br>';
							}	
						}

						if ($row[$i]['Direccion'] != "") {
							$contacto .= "Dirección: ".$row[$i]['Direccion']."<br>";
						}

						if ($row[$i]['Telefono'] != "") {
							$contacto .= "Telefono: ".$row[$i]['Telefono']."<br>";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Cliente'],
							'Foto' => $foto,
							'Nombre' => $row[$i]['Nombre'],
							'Contacto' => $contacto,
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
