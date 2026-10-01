<?php
class depositos {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$buscar =  $omodelo->link->real_escape_string($buscar);
		$limit =  $omodelo->link->real_escape_string($limit);
		$pagina =  $omodelo->link->real_escape_string($pagina);
		$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
		$orden =  $omodelo->link->real_escape_string($orden);
		$arreglo = array();

		$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
		$fechaFin = $omodelo->link->real_escape_string($fechaFin);
		//$sucursal = $omodelo->link->real_escape_string($sucursal);
		$qSucursales = "";
		$cadenaSucursales = "";
		$sucursales = json_decode($Sucursales, true);
		foreach ($sucursales as $sucursal) {
			$cadenaSucursales .= $sucursal["ID"].",";
		}
		$string = rtrim($cadenaSucursales, ",");
		if ($string != "") {
			$qSucursales = "AND depositos.FK_Sucursal IN(".$string.")";
		}

		$busqueda = '';
		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));
			$busqueda = 'AND ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(sucursales.Nombre, Descripcion, Forma_Pago, Monto) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}
		
		$query = "SELECT ID_Deposito, depositos.FK_Sucursal, sucursales.Nombre AS Sucursal, Fecha_Deposito AS Fecha, Descripcion, Forma_Pago, Monto, Archivo, Fecha_Registro, FK_Usuario, usuarios.Nombre AS Usuario, (SELECT COUNT(*) FROM depositos INNER JOIN usuarios ON ID_Usuario = FK_Usuario INNER JOIN sucursales ON depositos.FK_Sucursal = ID_Sucursal WHERE Fecha_Deposito >= '$fechaInicio' AND Fecha_Deposito <= '$fechaFin' $qSucursales $busqueda) AS Num FROM depositos INNER JOIN usuarios ON FK_Usuario = ID_Usuario INNER JOIN sucursales ON depositos.FK_Sucursal = ID_Sucursal WHERE Fecha_Deposito >= '$fechaInicio' AND Fecha_Deposito <= '$fechaFin' $qSucursales $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);

		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
				
					$EliminarDeposito = '<button class="btn btn-danger btn-sm" id="EliminarDeposito" attrid="'.$row[$i]['ID_Deposito'].'" nombre="'.$row[$i]['Descripcion'].'"><i class="fas fa-trash"></i></button>';
					

					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_depositos'][3] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm" id="ModificarDeposito" attrid="'.$row[$i]['ID_Deposito'].'" Descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_depositos'][4] == '1') {
						$botonPermisosEliminar = '<button class="btn btn-danger btn-sm" id="EliminarDeposito" attrid="'.$row[$i]['ID_Deposito'].'" Descripcion="'.$row[$i]['Descripcion'].'"><i class="fas fa-trash"></i></button>';
					}

					$imagen = '<a href="vistas/assets/archivos/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
					if ($row[$i]["Archivo"] != "") {
						if($row[$i]["Archivo"] != "" && file_exists("vistas/assets/archivos/comprobantesDepositos/".$row[$i]["Archivo"])){
							$imagen = '<a href="vistas/assets/archivos/comprobantesDepositos/'.$row[$i]["Archivo"].'" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/comprobantesDepositos/'.$row[$i]["Archivo"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
						}	
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Deposito'],
						'Fecha' => $row[$i]['Fecha'],
						'Sucursal' => $row[$i]['Sucursal'],
						'Descripcion' => $row[$i]['Descripcion']."<br>Forma de pago: <b>".$row[$i]['Forma_Pago']."</b>",
						'Monto' => "<b style='font-size: 15px;'>$".number_format($row[$i]['Monto'], 2)."</b>",
						'Evidencias' => $imagen,
						'Acciones' => $botonPermisosModificar.' '.$botonPermisosEliminar,
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
		$FechaDepositos = $omodelo->link->real_escape_string($FechaDepositos);
		$MontoDepositos = $omodelo->link->real_escape_string($MontoDepositos);
		$DescripcionDepositos = $omodelo->link->real_escape_string($DescripcionDepositos);
		$Sucursal = $_SESSION['user_admin']["FK_Sucursal"];

		$query = "INSERT INTO depositos SET Fecha_Deposito = '$FechaDepositos', FK_Sucursal = '$Sucursal', Descripcion = '$DescripcionDepositos', Forma_Pago = 'Efectivo', Monto = '$MontoDepositos', Fecha_Registro = NOW(), FK_Usuario = '".$_SESSION['user_admin']["ID_Usuario"]."'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			$IDDeposito = mysqli_insert_id($omodelo->link);

			$status = 1;
			if ($_FILES['ArchivoDepositos']['size'] > 0 && $_FILES['ArchivoDepositos']['error'] == 0) {
				$file = $_FILES["ArchivoDepositos"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/comprobantesDepositos/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/JPEG' && $tipo != 'image/jpg' && $tipo != 'image/JPG' && $tipo != 'image/png' && $tipo != 'image/PNG' && $tipo != 'application/pdf' && $tipo != 'application/PDF' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status = 0;
					$ruta = $carpeta;
				}
			}
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

			if($status == 0){
				$query2 = "UPDATE depositos SET Archivo = '".$IDDeposito.'_'.$nombreDoc."' WHERE ID_Deposito = '$IDDeposito'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$IDDeposito.'_'.$nombreDoc);
				}
			}
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);

		$query = "DELETE FROM depositos WHERE ID_Deposito='$id'";
		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
			echo "Correcto";
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$IDDeposito = $omodelo->link->real_escape_string($IDDepositos);
		$FechaDepositos = $omodelo->link->real_escape_string($FechaDepositos);
		$MontoDepositos = $omodelo->link->real_escape_string($MontoDepositos);
		$DescripcionDepositos = $omodelo->link->real_escape_string($DescripcionDepositos);
		$Sucursal = $_SESSION['user_admin']["FK_Sucursal"]; 

		$query = "UPDATE depositos SET Fecha_Deposito = '$FechaDepositos', FK_Sucursal = '$Sucursal', Descripcion = '$DescripcionDepositos', Forma_Pago = 'Efectivo', Monto = '$MontoDepositos' WHERE ID_Deposito = '$IDDeposito'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{

			$status = 1;
			if ($_FILES['ArchivoDepositos']['size'] > 0 && $_FILES['ArchivoDepositos']['error'] == 0) {
				$file = $_FILES["ArchivoDepositos"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/comprobantesDepositos/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/JPEG' && $tipo != 'image/jpg' && $tipo != 'image/JPG' && $tipo != 'image/png' && $tipo != 'image/PNG' && $tipo != 'application/pdf' && $tipo != 'application/PDF' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status = 0;
					$ruta = $carpeta;
				}
			}
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

			if($status == 0){
				$query2 = "UPDATE depositos SET Archivo = '".$IDDeposito.'_'.$nombreDoc."' WHERE ID_Deposito = '$IDDeposito'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$IDDeposito.'_'.$nombreDoc);
				}
			}
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date ('Y-m-d H:i:s');

		if($tipo == 'ConsultarDepositos'){
			$arreglo = array();
			$IDDepositos = $omodelo->link->real_escape_string($IDDepositos);

			$query = "SELECT ID_Deposito, depositos.FK_Sucursal, Fecha_Deposito, Descripcion, Forma_Pago, Monto, Archivo, Fecha_Registro, FK_Usuario FROM depositos WHERE ID_Deposito = '$IDDepositos'";
			$row = $omodelo->_consultar($query);
				
			if ($row == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{

				$arreglo = array(
					"ID_Deposito" => $row[0]["ID_Deposito"],
					"Fecha_Deposito" => $row[0]["Fecha_Deposito"],
					"Descripcion" => $row[0]["Descripcion"],
					"Forma_Pago" => $row[0]["Forma_Pago"],
					"Monto" => $row[0]["Monto"],
				);	
			}

			echo json_encode($arreglo);
		}else if($tipo == 'ConsultarSucursales'){
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
					$busqueda .= "CONCAT(ID_Sucursal, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Email, FK_Encargado, usuarios.Nombre,  usuarios.Primer_Apellido, usuarios.Segundo_Apellido, zonas.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Sucursal, '' AS Seleccionar, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Segundo_Telefono, Email, FK_Encargado, usuarios.Nombre AS NombreEncargado, usuarios.Primer_Apellido AS PrimerApellido, usuarios.Segundo_Apellido AS SegundoApellido, FK_Zona, zonas.Nombre AS NombreZona, Latitud, Longitud, (SELECT COUNT(*) FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda) AS Num, ((SELECT COUNT(*) FROM ventas INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM cajas WHERE FK_Sucursal = ID_Sucursal)) AS numSucu FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$direccion = "";$telefono="";$email="";$gerente="";

						if ($row[$i]['NombreZona'] != "") {
							$direccion .= "Zona: <b>".$row[$i]['NombreZona'].'</b><br>';
						}

						if ($row[$i]['Calle'] != "") {
							$direccion .= "Calle: <b>".$row[$i]['Calle'].'</b><br>';
						}

						if ($row[$i]['No_Exterior'] != "") {
							$direccion .= "No. Exterior: <b>".$row[$i]['No_Exterior'].'</b><br>';
						}

						if ($row[$i]['No_Interior'] != "") {
							$direccion .= "No. Interior: <b>".$row[$i]['No_Interior'].'</b><br>';
						}

						if ($row[$i]['Colonia'] != "") {
							$direccion .= "Colonia: <b>".$row[$i]['Colonia'].'</b><br>';
						}

						if ($row[$i]['CP'] != "") {
							$direccion .= "Codigo postal: <b>".$row[$i]['CP'].'</b><br>';
						}

						if ($row[$i]['Ciudad'] != "") {
							$direccion .= "Ciudad: <b>".$row[$i]['Ciudad'].'</b><br>';
						}

						if ($row[$i]['Estado'] != "") {
							$direccion .= "Estado: <b>".$row[$i]['Estado'].'</b><br>';
						}

						if ($row[$i]['Pais'] != "") {
							$direccion .= "País: <b>".$row[$i]['Pais'].'</b><br>';
						}

						if ($row[$i]['Telefono'] != "") {
							$telefono .= "Primer teléfono: <b>".$row[$i]['Telefono'].'</b><br>';
						}

						if ($row[$i]['Segundo_Telefono'] != "") {
							$telefono .= "Segundo teléfono: <b>".$row[$i]['Segundo_Telefono'].'</b><br>';
						}

						if ($row[$i]['Latitud'] != '' && $row[$i]['Longitud'] != '') {
							$telefono .= 'Ubicación: <b>'.$row[$i]['Latitud'].', '.$row[$i]['Longitud'].'</b>';
						}
						
						if ($telefono == "") {
							$telefono = "No hay telefono registrado";
						}
						
						if ($row[$i]['Email'] != "") {
							$email = $row[$i]['Email'];
						}else{	
							$email = "No hay un correo registrado";
						}

						if ($row[$i]['FK_Encargado'] != "") {
							$gerente = $row[$i]['NombreEncargado'].' '.$row[$i]['PrimerApellido'].' '.$row[$i]['SegundoApellido'];
						}else{	
							$gerente = "No hay datos registrados";
						}

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Sucursal'],
							'Seleccionar' => '<input type="checkbox" class="CheckInputSucursalDepositos" nombre="'.$row[$i]['Nombre'].'" attrid="'.$row[$i]['ID_Sucursal'].'">',
							'Sucursal' => $row[$i]['Nombre'],
							'Direccion' => $direccion
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
