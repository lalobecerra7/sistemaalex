
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
				$busqueda .= "CONCAT(ID_Empleado, Nombre, Direccion, Colonia, Telefono, Celular, Ciudad, Correo, Fecha_Nacimiento, Fecha_Entrada, FK_Puesto, Sueldo, SDI, SPH, Tipo_Sueldo, Horario_Entrada, Horario_Salida, Horario_Entrada_Sabado, Horario_Salida_Sabado, Sexo, Lugar_Nacimiento, Estado_Civil, Pais, Estado, RFC, Numero_Seguro, CURP, FK_Area, Bonos, Tipo_Sangre, Alergias, Contacto_Emergencias, Numero_Emergencias, Estado_Empleado, Foto, Fecha_Baja, Motivo_Baja, Fecha_Termino_Contrato, Fecha_Reingreso, Codigo_Postal, Fecha_Registro) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Empleado, Nombre, Direccion, Colonia, Telefono, Celular, Ciudad, Correo, Fecha_Nacimiento, Fecha_Entrada, FK_Puesto, Sueldo, SDI, SPH, Tipo_Sueldo, Horario_Entrada, Horario_Salida, Horario_Entrada_Sabado, Horario_Salida_Sabado, Sexo, Lugar_Nacimiento, Estado_Civil, Pais, Estado, RFC, Foto, Numero_Seguro, CURP, FK_Area, Bonos, Tipo_Sangre, Alergias, Contacto_Emergencias, Numero_Emergencias, Estado_Empleado , Fecha_Baja, Motivo_Baja, Fecha_Termino_Contrato, Fecha_Reingreso, Codigo_Postal, Fecha_Registro AS Fecha, (SELECT COUNT(*) FROM empleados $busqueda) AS Num FROM empleados $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

					$foto = '<a href="vistas/assets/archivos/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
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

					if ($direccion == "") {
						$direccion = "No hay datos ingresados";
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Empleado'],
						'Fecha' => $row[$i]['Fecha'],
						'Empleado' => $foto.$row[$i]['Nombre'],
						'Direccion' => $direccion,
						'Contacto' => $contacto,
						'Acciones' => '<button class="btn btn-primary btn-sm" id="ModificarEmpleado" attrid="'.$row[$i]['ID_Empleado'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm" id="EliminarEmpleado" attrid="'.$row[$i]['ID_Empleado'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>',
						
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
		$query = "INSERT INTO empleados SET Nombre = '$NombreEmpleado', Direccion = '$DireccionEmpleado', Colonia = '$ColoniaEmpleado', Telefono = '$TelefonoEmpleado', Celular = '$CelularEmpleado', Ciudad = '$CiudadEmpleado', Correo = '$CorreoEmpleado', Fecha_Nacimiento = '$FechaNacimientoEmpleado', Fecha_Entrada = '$FechaIngreso', FK_Puesto = '$PuestoEmpleado', Sueldo = '$SueldoEmpleado', SDI = '$SDIEmpleado', SPH = '$SPHEmpleado', Tipo_Sueldo = '$TipoSueldo', Horario_Entrada = '$HoraEntrada', Horario_Salida = '$HorarioSalida', Horario_Entrada_Sabado = '$HoraEntradaSabado', Horario_Salida_Sabado = '$HorarioSalidaSabado', Sexo = '$SexoEmpleado', Lugar_Nacimiento = '$LugarNacimientoEmpleado', Estado_Civil = '$EstadoCivilEmpleado', Pais = '$PaisEmpleado', Estado = '$EstadoEmpleado', RFC = '$RFCEmpleado', Numero_Seguro = '$NoSeguroSocialEmpleado', CURP = '$CURPEmpleado', FK_Area = '$AreasEmpleado', Tipo_Sangre = '$TipoSangreEmpleado', Alergias = '$AlergiasEmpleado', Contacto_Emergencias = '$ContactoEmergencia', Numero_Emergencias = '$TelefonoEmergencia', Estado_Empleado  = '$EstatusEmpleado', Fecha_Baja = '$FechaBajaEmpleado', Motivo_Baja = '$MotivoBajaEmpleado', Fecha_Termino_Contrato = '$FechaTerminoContrato', Fecha_Reingreso = '$FechaReingresoEmpleado', Codigo_Postal = '$CPEmpleado', Fecha_Registro = '$fecha'
		";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorInsertar: ".mysqli_error($omodelo->link);
		}else{
			$IDEmpleado = mysqli_insert_id($omodelo->link);
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

			$status = 1;
			if ($_FILES['FotoEmpleado']['size'] > 0 && $_FILES['FotoEmpleado']['error'] == 0) {
				$file = $_FILES["FotoEmpleado"];
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
				$query2 = "UPDATE empleados SET Foto = '".$IDEmpleado.'_'.$nombreDoc."' WHERE ID_Empleado = '$IDEmpleado'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$IDEmpleado.'_'.$nombreDoc);
				}
			}
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$query = "UPDATE empleados SET Nombre = '$NombreEmpleado', Direccion = '$DireccionEmpleado', Colonia = '$ColoniaEmpleado', Telefono = '$TelefonoEmpleado', Celular = '$CelularEmpleado', Ciudad = '$CiudadEmpleado', Correo = '$CorreoEmpleado', Fecha_Nacimiento = '$FechaNacimientoEmpleado', Fecha_Entrada = '$FechaIngreso', FK_Puesto = '$PuestoEmpleado', Sueldo = '$SueldoEmpleado', SDI = '$SDIEmpleado', SPH = '$SPHEmpleado', Tipo_Sueldo = '$TipoSueldo', Horario_Entrada = '$HoraEntrada', Horario_Salida = '$HorarioSalida', Horario_Entrada_Sabado = '$HoraEntradaSabado', Horario_Salida_Sabado = '$HorarioSalidaSabado', Sexo = '$SexoEmpleado', Lugar_Nacimiento = '$LugarNacimientoEmpleado', Estado_Civil = '$EstadoCivilEmpleado', Pais = '$PaisEmpleado', Estado = '$EstadoEmpleado', RFC = '$RFCEmpleado', Numero_Seguro = '$NoSeguroSocialEmpleado', CURP = '$CURPEmpleado', FK_Area = '$AreasEmpleado', Tipo_Sangre = '$TipoSangreEmpleado', Alergias = '$AlergiasEmpleado', Contacto_Emergencias = '$ContactoEmergencia', Numero_Emergencias = '$TelefonoEmergencia', Estado_Empleado  = '$EstatusEmpleado', Fecha_Baja = '$FechaBajaEmpleado', Motivo_Baja = '$MotivoBajaEmpleado', Fecha_Termino_Contrato = '$FechaTerminoContrato', Fecha_Reingreso = '$FechaReingresoEmpleado', Codigo_Postal = '$CPEmpleado' WHERE ID_Empleado = '$idEmpleado'
		";

		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorModificar: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto~";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

			$status = 1;
			if ($_FILES['FotoEmpleado']['size'] > 0 && $_FILES['FotoEmpleado']['error'] == 0) {
				$file = $_FILES["FotoEmpleado"];
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

				$query = "SELECT Foto FROM empleados WHERE ID_Empleado = '$idEmpleado'";
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

				$query2 = "UPDATE empleados SET Foto = '".$idEmpleado.'_'.$nombreDoc."' WHERE ID_Empleado = '$idEmpleado'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$idEmpleado.'_'.$nombreDoc);
				}
			}
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$idEmpleado =  $omodelo->link->real_escape_string($idEmpleado);

		$query = "SELECT Foto FROM empleados WHERE ID_Empleado = '$idEmpleado'";
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

		$query = "DELETE FROM empleados WHERE ID_Empleado = '$idEmpleado'";
		$error = $omodelo->_insertar($query);
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
		    $omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "ConsultarPuestos") {
			$opciones = "<option value=''> Seleccione una opción </option>";
			$query = "SELECT ID_Puesto, Nombre, FK_Departamento FROM puestos";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opciones .= '<option value="'.$row[$i]["ID_Puesto"].'">'.$row[$i]["Nombre"].'</option>';
					}
				}
				echo $opciones;
			}
		}else if ($tipo == "ConsultarAreas") {
			$opciones = "<option value=''> Seleccione una opción </option>";
			$query = "SELECT ID_Area, Nombre FROM areas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opciones .= '<option value="'.$row[$i]["ID_Area"].'">'.$row[$i]["Nombre"].'</option>';
					}
				}
				echo $opciones;
			}
		}
	}

}
?>
