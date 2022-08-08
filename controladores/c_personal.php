
<?php
class personal {

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
				$busqueda .= "CONCAT(ID_Empleado, Nombre, Direccion, Colonia, Telefono, Celular, Ciudad, Correo, Fecha_Nacimiento, Fecha_Entrada, Puesto, Sueldo, Horario, Sexo, Foto, Lugar_Nacimiento, Estado_Civil, Pais, Estado, RFC, Numero_Seguro, CURP, FK_Area, Forma_Pago, Bonos, SDI, Tipo_Sangre, Alergias, Contacto_Emergencias, Numero_Emergencias, Estado_Empleado, Turno, Fecha_Baja, Motivo_Baja, Fecha_Termino_Contrato, Fecha_Reingreso, Usuario, Codigo_Postal, Fecha_Registro) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Empleado, Nombre, Direccion, Colonia, Telefono, Celular, Ciudad, Correo, Fecha_Nacimiento, Fecha_Entrada, Puesto, Sueldo, Horario, Sexo, Foto, Lugar_Nacimiento, Estado_Civil, Pais, Estado, RFC, Numero_Seguro, CURP, FK_Area, Forma_Pago, Bonos, SDI, Tipo_Sangre, Alergias, Contacto_Emergencias, Numero_Emergencias, Estado_Empleado, Turno, Fecha_Baja, Motivo_Baja, Fecha_Termino_Contrato, Fecha_Reingreso, Usuario, Codigo_Postal, Fecha_Registro AS Fecha, (SELECT COUNT(*) FROM empleados $busqueda) AS Num FROM empleados $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$direccion = ""; $contacto = ""; $datosbancarios = "";

					if ($row[$i]['Direccion'] != "") {
						$direccion .= "Dirección: ".$row[$i]['Direccion']."<br>";
					}

					if ($row[$i]['Colonia'] != "") {
						$direccion .= "Colonia: ".$row[$i]['Colonia']."<br>";
					}

					if ($row[$i]['Codigo_Postal'] != "") {
						$direccion .= "Codigo postal: ".$row[$i]['Codigo_Postal']."<br>";
					}

					if ($row[$i]['Ciudad'] != "") {
						$direccion .= "Ciudad: ".$row[$i]['Ciudad']."<br>";
					}

					if ($row[$i]['Estado'] != "") {
						$direccion .= "Estado: ".$row[$i]['Estado']."<br>";
					}

					if ($row[$i]['Pais'] != "") {
						$direccion .= "País: ".$row[$i]['Pais']."<br>";
					}

					if ($row[$i]['Telefono'] != "") {
						$contacto .= "Teléfono: ".$row[$i]['Telefono']."<br>";
					}

					if ($row[$i]['Celular'] != "") {
						$contacto .= "Celular: ".$row[$i]['Celular']."<br>";
					}

					if ($row[$i]['Correo'] != "") {
						$contacto .= "Correo electrónico: ".$row[$i]['Correo']."<br>";
					}

					$foto = '<a href="vistas/assets/archivos/fotosEmpleados/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosEmpleados/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
					if ($row[$i]["Foto"] != "") {
						if($row[$i]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosEmpleados/".$row[$i]["Foto"])){
							$foto = '<a href="vistas/assets/archivos/fotosEmpleados/'.$row[$i]["Foto"].'" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosEmpleados/'.$row[$i]["Foto"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
						}	
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Cliente'],
						'Fecha' => $row[$i]['Fecha'],
						'Empleado' => $foto.$row[$i]['Nombre'],
						'Direccion' => $direccion,
						'Contacto' => $contacto,
						'Acciones' => '<button class="btn btn-primary btn-sm" id="ModificarCliente" attrid="'.$row[$i]['ID_Cliente'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm" id="EliminarCliente" attrid="'.$row[$i]['ID_Cliente'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>',
						
					);
					
				}

				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
			}
		}

		echo json_encode($arreglo);
	}

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$query = "INSERT INTO clientes SET Nombre = '$NombreCliente', Direccion = '$DireccionCliente', Telefono = '$TelefonoCliente', Celular = '$CelularCliente', Ciudad = '$CiudadCliente', Colonia = '$ColoniaCliente', Codigo_Postal = '$CPCliente', Tipo_Descuento = '$TipoDescuentoCliente', Descuento = '$DescuentoCliente', Correo = '$CorreoCliente', Fecha_Nacimiento = '$FechaNacimientoCliente', Sexo = '$SexoCliente', Fecha_Registro = '$fecha', RFC = '$RFCCliente', Empresa = '$NombreEmpresaCliente', No_Cuenta = '$CuentaBancoCliente', Banco = '$BancoCliente', Titular = '$TitularBancoCliente', Pais = '$PaisCliente', Estado = '$EstadoCliente'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorInsertar: ".mysqli_error($omodelo->link);
		}else{
			$IDCliente = mysqli_insert_id($omodelo->link);
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);

			$status = 1;
			if ($_FILES['FotoCliente']['size'] > 0 && $_FILES['FotoCliente']['error'] == 0) {
				$file = $_FILES["FotoCliente"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosEmpleados/";

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
				$query2 = "UPDATE clientes SET Foto = '".$IDCliente.'_'.$nombreDoc."' WHERE ID_Cliente = '$IDCliente'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$IDCliente.'_'.$nombreDoc);
				}
			}
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$query = "UPDATE clientes SET Nombre = '$NombreCliente', Direccion = '$DireccionCliente', Telefono = '$TelefonoCliente', Celular = '$CelularCliente', Ciudad = '$CiudadCliente', Colonia = '$ColoniaCliente', Codigo_Postal = '$CPCliente', Tipo_Descuento = '$TipoDescuentoCliente', Descuento = '$DescuentoCliente', Correo = '$CorreoCliente', Fecha_Nacimiento = '$FechaNacimientoCliente', Sexo = '$SexoCliente', RFC = '$RFCCliente', Empresa = '$NombreEmpresaCliente', No_Cuenta = '$CuentaBancoCliente', Banco = '$BancoCliente', Titular = '$TitularBancoCliente', Pais = '$PaisCliente', Estado = '$EstadoCliente' WHERE ID_Cliente = '$IDCliente'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorModificar: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto~";
			$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);

			$status = 1;
			if ($_FILES['FotoCliente']['size'] > 0 && $_FILES['FotoCliente']['error'] == 0) {
				$file = $_FILES["FotoCliente"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosEmpleados/";

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

				$query = "SELECT Foto FROM clientes WHERE ID_Cliente = '$IDCliente'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
				$nombreFoto = "";
				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosEmpleados/".$row[0]["Foto"])){
					       	unlink("vistas/assets/archivos/fotosEmpleados/".$row[0]["Foto"]);
					    }
					}
				}

				$query2 = "UPDATE clientes SET Foto = '".$IDCliente.'_'.$nombreDoc."' WHERE ID_Cliente = '$IDCliente'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$IDCliente.'_'.$nombreDoc);
				}
			}
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$IDCliente =  $omodelo->link->real_escape_string($IDCliente);

		$query = "SELECT Foto FROM clientes WHERE ID_Cliente = '$IDCliente'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		$nombreFoto = "";
		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosEmpleados/".$row[0]["Foto"])){
			       	unlink("vistas/assets/archivos/fotosEmpleados/".$row[0]["Foto"]);
			    }
			}
		}

		$query = "DELETE FROM clientes WHERE ID_Cliente = '$IDCliente'";
		$error = $omodelo->_insertar($query);
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
		    $omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$IDCliente =  $omodelo->link->real_escape_string($IDCliente);

		$query = "SELECT ID_Cliente, Nombre, Direccion, Telefono, Celular, Ciudad, Colonia, Codigo_Postal, Tipo_Descuento, Descuento, Lim_Credito, Correo, Fecha_Nacimiento, Sexo, Fecha_Registro, Foto, RFC, Empresa, No_Cuenta, Banco, Titular, Pais, Estado FROM clientes WHERE ID_Cliente = '$IDCliente'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				echo json_encode($row[0]);
			}
		}
	}

}
?>
