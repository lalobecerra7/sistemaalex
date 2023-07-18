<?php
class clientes {

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
			$busqueda = 'AND ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(LPAD(ID_Cliente, 4, '0'), clientes.Nombre, Primer_Apellido, Segundo_Apellido, Foto, clientes.Telefono, Celular, Correo, clientes.RFC, Facturar, Titular, Banco, No_Cuenta, Fecha_Registro) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Cliente, LPAD(ID_Cliente, 4, '0') AS IDCliente, clientes.Nombre, Primer_Apellido, Segundo_Apellido, Foto, clientes.Telefono, Celular, Correo, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, clientes.RFC, Facturar, Titular, Banco, No_Cuenta, FK_Sucursal, sucursales.Nombre AS NombreSucursal, clientes.Calle AS Direccion, clientes.No_Exterior, clientes.No_Interior, clientes.Colonia, clientes.Ciudad, clientes.Codigo_Postal, clientes.Estado, clientes.Pais, (SELECT COUNT(*) FROM clientes WHERE ID_Cliente <> 1 $busqueda) AS Num FROM clientes LEFT JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE ID_Cliente <> 1 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$direccion = ""; $contacto = ""; $datosbancarios = ""; $direccionfiscal = "";

					if ($row[$i]['Direccion'] != "") {
						$direccionfiscal .= "Calle: ".$row[$i]['Direccion']."<br>";
					}

					if ($row[$i]['No_Exterior'] != "") {
						$direccionfiscal .= "No. Exterior: ".$row[$i]['No_Exterior']."<br>";
					}

					if ($row[$i]['No_Interior'] != "") {
						$direccionfiscal .= "No. Interior: ".$row[$i]['No_Interior']."<br>";
					}

					if ($row[$i]['Colonia'] != "") {
						$direccionfiscal .= "Colonia: ".$row[$i]['Colonia']."<br>";
					}

					if ($row[$i]['Codigo_Postal'] != "") {
						$direccionfiscal .= "Codigo postal: ".$row[$i]['Codigo_Postal']."<br>";
					}

					if ($row[$i]['Ciudad'] != "") {
						$direccionfiscal .= "Ciudad: ".$row[$i]['Ciudad']."<br>";
					}

					if ($row[$i]['Estado'] != "") {
						$direccionfiscal .= "Estado: ".$row[$i]['Estado']."<br>";
					}

					if ($row[$i]['Pais'] != "") {
						$direccionfiscal .= "País: ".$row[$i]['Pais']."<br>";
					}

					if ($direccionfiscal == "") {
						$direccionfiscal = "No hay datos registrados";
					}

					$queryDirecciones = "SELECT ID_Detalle_Cliente, FK_Cliente, Calle, No_Exterior, No_Interior, Colonia, Codigo_Postal, Ciudad, Estado, Pais, Detalles, Nombre_Contacto, Puesto_Contacto, Email_Contacto, Telefono_Contacto, Latitud, Longitud, Entre_Calles FROM detalles_clientes WHERE FK_Cliente = '".$row[$i]['ID_Cliente']."'";
					$rowDirecciones = $omodelo->_consultar($queryDirecciones);
					$numerofilasDirecciones = $omodelo->numerofilas;
					for ($x=0; $x < $numerofilasDirecciones; $x++) { 
						$direccion .= "<button class='btn btn-link verDatosDireccion' Calle='".$rowDirecciones[$x]['Calle']."' No_Exterior='".$rowDirecciones[$x]['No_Exterior']."' No_Interior='".$rowDirecciones[$x]['No_Interior']."' Colonia='".$rowDirecciones[$x]['Colonia']."' Codigo_Postal='".$rowDirecciones[$x]['Codigo_Postal']."'
							Latitud='".$rowDirecciones[$x]["Latitud"]."' Longitud='".$rowDirecciones[$x]["Longitud"]."' EntreCalles='".$rowDirecciones[$x]["Entre_Calles"]."' Ciudad='".$rowDirecciones[$x]['Ciudad']."' Estado='".$rowDirecciones[$x]['Estado']."' Pais='".$rowDirecciones[$x]['Pais']."' Nombre_Contacto='".$rowDirecciones[$x]['Nombre_Contacto']."' Puesto_Contacto='".$rowDirecciones[$x]['Puesto_Contacto']."' Email_Contacto='".$rowDirecciones[$x]['Email_Contacto']."' Telefono_Contacto='".$rowDirecciones[$x]['Telefono_Contacto']."' Detalles='".$rowDirecciones[$x]['Detalles']."'  title='Ver datos de la dirección' attrid='".$rowDirecciones[$x]['ID_Detalle_Cliente']."' >
						Contacto: ".$rowDirecciones[$x]['Nombre_Contacto']." <br>
						Dirección: ".$rowDirecciones[$x]['Calle']." ".$rowDirecciones[$x]['No_Exterior']."</button><br>";
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

					if ($row[$i]['Facturar'] == "1") {
						$datosbancarios .= "Facturar ventas: <b>Si</b><br>";
					}else{
						$datosbancarios .= "Facturar ventas: <b>No</b><br>";
					}

					if ($row[$i]['NombreSucursal'] != "") {
						$datosbancarios .= "Sucursal del cliente: <b>".$row[$i]['NombreSucursal']."</b><br>";
					}

					if ($row[$i]['RFC'] != "") {
						$datosbancarios .= "RFC: <b>".$row[$i]['RFC']."</b><br>";
					}

					if ($row[$i]['Titular'] != "") {
						$datosbancarios .= "Titular: ".$row[$i]['Titular']."<br>";
					}

					if ($row[$i]['Banco'] != "") {
						$datosbancarios .= "Banco: ".$row[$i]['Banco']."<br>";
					}

					if ($row[$i]['No_Cuenta'] != "") {
						$datosbancarios .= "CLABE o número de cuenta: ".$row[$i]['No_Cuenta']."<br>";
					}

					if ($direccion == "") {
						$direccion = "No hay direcciones agregadas";
					}

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

					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][3] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm " id="ModificarCliente" title="Modificar cliente" attrid="'.$row[$i]['ID_Cliente'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][4] == '1') {
						$botonPermisosEliminar = '<button class="btn btn-danger btn-sm" title="Eliminar cliente" id="EliminarCliente" attrid="'.$row[$i]['ID_Cliente'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>';
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Cliente'],
						'IDCliente' => $row[$i]['IDCliente'],
						'Fecha' => $row[$i]['Fecha_Registro'],
						'Nombre' => $foto.$row[$i]['Nombre'].' '.$row[$i]['Primer_Apellido'].' '.$row[$i]['Segundo_Apellido'],
						'Direcciones' => "Dirección fiscal: <br>".$direccionfiscal."<br>".$direccion,
						'Detalles' => $datosbancarios,
						'Acciones' => $botonPermisosModificar.' '.$botonPermisosEliminar,
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
		$TipoPersona = $omodelo->link->real_escape_string($TipoPersona);
		$NombreCliente = $omodelo->link->real_escape_string($NombreCliente);
		$primerApellidoCliente = $omodelo->link->real_escape_string($primerApellidoCliente);
		$segundoApellidoCliente = $omodelo->link->real_escape_string($segundoApellidoCliente);
		$CalleClienteGeneral = $omodelo->link->real_escape_string($CalleClienteGeneral);
		$NoExteriorClienteGeneral = $omodelo->link->real_escape_string($NoExteriorClienteGeneral);
		$NoInteriorClienteGeneral = $omodelo->link->real_escape_string($NoInteriorClienteGeneral);
		$CPClienteGeneral = $omodelo->link->real_escape_string($CPClienteGeneral);
		$ColoniaClienteGeneral = $omodelo->link->real_escape_string($ColoniaClienteGeneral);
		$CiudadClienteGeneral = $omodelo->link->real_escape_string($CiudadClienteGeneral);
		$EstadoClienteGeneral = $omodelo->link->real_escape_string($EstadoClienteGeneral);
		$PaisClienteGeneral = $omodelo->link->real_escape_string($PaisClienteGeneral);
		$TelefonoCliente = $omodelo->link->real_escape_string($TelefonoCliente);
		$CelularCliente = $omodelo->link->real_escape_string($CelularCliente);
		$CorreoCliente = $omodelo->link->real_escape_string($CorreoCliente);
		$FechaNacimientoCliente = $omodelo->link->real_escape_string($FechaNacimientoCliente);
		$SexoCliente = $omodelo->link->real_escape_string($SexoCliente);
		$RFCCliente = $omodelo->link->real_escape_string($RFCCliente);
		$FacturarCliente = $omodelo->link->real_escape_string($FacturarCliente);
		$CuentaBancoCliente = $omodelo->link->real_escape_string($CuentaBancoCliente);
		$BancoCliente = $omodelo->link->real_escape_string($BancoCliente);
		$TitularBancoCliente = $omodelo->link->real_escape_string($TitularBancoCliente);
		$SucursalCliente = $omodelo->link->real_escape_string($SucursalCliente);
		$razonCliente = $omodelo->link->real_escape_string($razonCliente);
		$regimenCliente = $omodelo->link->real_escape_string($regimenCliente);
		$contactoCliente = $omodelo->link->real_escape_string($contactoCliente);
		$puestoContactoCliente = $omodelo->link->real_escape_string($puestoContactoCliente);
		$correoContactoCliente = $omodelo->link->real_escape_string($correoContactoCliente);
		$telefonoContactoCliente = $omodelo->link->real_escape_string($telefonoContactoCliente);
		$INECliente = $omodelo->link->real_escape_string($INECliente);

		if ($TipoPersona == "Fisica") {
			$query = "INSERT INTO clientes SET Nombre = '$NombreCliente', Primer_Apellido = '$primerApellidoCliente', Segundo_Apellido = '$segundoApellidoCliente', Calle = '$CalleClienteGeneral', No_Exterior = '$NoExteriorClienteGeneral', No_Interior = '$NoInteriorClienteGeneral', Codigo_Postal = '$CPClienteGeneral', Colonia = '$ColoniaClienteGeneral', Ciudad = '$CiudadClienteGeneral', Estado = '$EstadoClienteGeneral', Pais = '$PaisClienteGeneral', Telefono = '$TelefonoCliente', Celular = '$CelularCliente', Correo = '$CorreoCliente', Fecha_Nacimiento = '$FechaNacimientoCliente', Sexo = '$SexoCliente', Fecha_Registro = '$fecha', RFC = '$RFCCliente', Facturar = '$FacturarCliente',  No_Cuenta = '$CuentaBancoCliente', Banco = '$BancoCliente', Titular = '$TitularBancoCliente', FK_Sucursal = '$SucursalCliente', Razon_CFDI = '$razonCliente', Regimen_CFDI = '$regimenCliente', Nombre_Contacto = '$contactoCliente', Puesto_Contacto = '$puestoContactoCliente', Email_Contacto = '$correoContactoCliente', Tel_Contacto = '$telefonoContactoCliente', INE = '$INECliente', Tipo_Persona = '$TipoPersona'";		
		}else{
			$query = "INSERT INTO clientes SET Nombre = '$NombreCliente', Calle = '$CalleClienteGeneral', No_Exterior = '$NoExteriorClienteGeneral', No_Interior = '$NoInteriorClienteGeneral', Codigo_Postal = '$CPClienteGeneral', Colonia = '$ColoniaClienteGeneral', Ciudad = '$CiudadClienteGeneral', Estado = '$EstadoClienteGeneral', Pais = '$PaisClienteGeneral', Telefono = '$TelefonoCliente', Celular = '$CelularCliente', Correo = '$CorreoCliente',  Fecha_Registro = '$fecha', RFC = '$RFCCliente', Facturar = '$FacturarCliente',  No_Cuenta = '$CuentaBancoCliente', Banco = '$BancoCliente', Titular = '$TitularBancoCliente', FK_Sucursal = '$SucursalCliente', Razon_CFDI = '$razonCliente', Regimen_CFDI = '$regimenCliente', Nombre_Contacto = '$contactoCliente', Puesto_Contacto = '$puestoContactoCliente', Email_Contacto = '$correoContactoCliente', Tel_Contacto = '$telefonoContactoCliente', Tipo_Persona = '$TipoPersona'";	
		}
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorInsertar: ".mysqli_error($omodelo->link);
		}else{
			$IDCliente = mysqli_insert_id($omodelo->link);
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

			$direcciones = explode(",", $direcciones);
			for ($i=0; $i < sizeof($direcciones) - 1; $i++) { 
				$datosdireccion = explode("~", $direcciones[$i]);
				$queryDirecciones = "INSERT INTO detalles_clientes SET FK_Cliente = '$IDCliente', Calle = '$datosdireccion[0]', No_Exterior = '$datosdireccion[1]', No_Interior = '$datosdireccion[2]', Colonia = '$datosdireccion[4]', Codigo_Postal = '$datosdireccion[3]', Ciudad = '$datosdireccion[5]', Estado = '$datosdireccion[6]', Pais = '$datosdireccion[7]', Nombre_Contacto = '$datosdireccion[8]', Puesto_Contacto = '$datosdireccion[9]', Email_Contacto = '$datosdireccion[10]', Telefono_Contacto  = '$datosdireccion[11]', Detalles  = '$datosdireccion[12]', Latitud  = '$datosdireccion[13]', Longitud  = '$datosdireccion[14]', Entre_Calles  = '$datosdireccion[15]',

				";
				$errorDirecciones = $omodelo->_insertar($queryDirecciones);	

				if ($errorDirecciones == "si") {
					echo "Error direcciones: ".mysqli_error($omodelo->link); 
				}
			}

			
			$status = 1;
			if ($_FILES['FotoCliente']['size'] > 0 && $_FILES['FotoCliente']['error'] == 0) {
				$file = $_FILES["FotoCliente"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosClientes/";

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
		$TipoPersona = $omodelo->link->real_escape_string($TipoPersona);
		$NombreCliente = $omodelo->link->real_escape_string($NombreCliente);
		$primerApellidoCliente = $omodelo->link->real_escape_string($primerApellidoCliente);
		$segundoApellidoCliente = $omodelo->link->real_escape_string($segundoApellidoCliente);
		$CalleClienteGeneral = $omodelo->link->real_escape_string($CalleClienteGeneral);
		$NoExteriorClienteGeneral = $omodelo->link->real_escape_string($NoExteriorClienteGeneral);
		$NoInteriorClienteGeneral = $omodelo->link->real_escape_string($NoInteriorClienteGeneral);
		$CPClienteGeneral = $omodelo->link->real_escape_string($CPClienteGeneral);
		$ColoniaClienteGeneral = $omodelo->link->real_escape_string($ColoniaClienteGeneral);
		$CiudadClienteGeneral = $omodelo->link->real_escape_string($CiudadClienteGeneral);
		$EstadoClienteGeneral = $omodelo->link->real_escape_string($EstadoClienteGeneral);
		$PaisClienteGeneral = $omodelo->link->real_escape_string($PaisClienteGeneral);
		$TelefonoCliente = $omodelo->link->real_escape_string($TelefonoCliente);
		$CelularCliente = $omodelo->link->real_escape_string($CelularCliente);
		$CorreoCliente = $omodelo->link->real_escape_string($CorreoCliente);
		$FechaNacimientoCliente = $omodelo->link->real_escape_string($FechaNacimientoCliente);
		$SexoCliente = $omodelo->link->real_escape_string($SexoCliente);
		$RFCCliente = $omodelo->link->real_escape_string($RFCCliente);
		$FacturarCliente = $omodelo->link->real_escape_string($FacturarCliente);
		$CuentaBancoCliente = $omodelo->link->real_escape_string($CuentaBancoCliente);
		$BancoCliente = $omodelo->link->real_escape_string($BancoCliente);
		$TitularBancoCliente = $omodelo->link->real_escape_string($TitularBancoCliente);
		$SucursalCliente = $omodelo->link->real_escape_string($SucursalCliente);
		$razonCliente = $omodelo->link->real_escape_string($razonCliente);
		$regimenCliente = $omodelo->link->real_escape_string($regimenCliente);
		$contactoCliente = $omodelo->link->real_escape_string($contactoCliente);
		$puestoContactoCliente = $omodelo->link->real_escape_string($puestoContactoCliente);
		$correoContactoCliente = $omodelo->link->real_escape_string($correoContactoCliente);
		$telefonoContactoCliente = $omodelo->link->real_escape_string($telefonoContactoCliente);
		$INECliente = $omodelo->link->real_escape_string($INECliente);

		if ($TipoPersona == "Fisica") {
			$query = "UPDATE clientes SET Nombre = '$NombreCliente', Primer_Apellido = '$primerApellidoCliente', Segundo_Apellido = '$segundoApellidoCliente', Calle = '$CalleClienteGeneral', No_Exterior = '$NoExteriorClienteGeneral', No_Interior = '$NoInteriorClienteGeneral', Codigo_Postal = '$CPClienteGeneral', Colonia = '$ColoniaClienteGeneral', Ciudad = '$CiudadClienteGeneral', Estado = '$EstadoClienteGeneral', Pais = '$PaisClienteGeneral', Telefono = '$TelefonoCliente', Celular = '$CelularCliente', Correo = '$CorreoCliente', Fecha_Nacimiento = '$FechaNacimientoCliente', Sexo = '$SexoCliente', RFC = '$RFCCliente', Facturar = '$FacturarCliente', No_Cuenta = '$CuentaBancoCliente', Banco = '$BancoCliente', Titular = '$TitularBancoCliente', FK_Sucursal = '$SucursalCliente', Razon_CFDI = '$razonCliente', Regimen_CFDI = '$regimenCliente', Nombre_Contacto = '$contactoCliente', Puesto_Contacto = '$puestoContactoCliente', Email_Contacto = '$correoContactoCliente', Tel_Contacto = '$telefonoContactoCliente', INE = '$INECliente', Tipo_Persona = '$TipoPersona' WHERE ID_Cliente = '$IDCliente'";
		}else{
			$query = "UPDATE clientes SET Nombre = '$NombreCliente', Primer_Apellido = '', Segundo_Apellido = '', Calle = '$CalleClienteGeneral', No_Exterior = '$NoExteriorClienteGeneral', No_Interior = '$NoInteriorClienteGeneral', Codigo_Postal = '$CPClienteGeneral', Colonia = '$ColoniaClienteGeneral', Ciudad = '$CiudadClienteGeneral', Estado = '$EstadoClienteGeneral', Pais = '$PaisClienteGeneral', Telefono = '$TelefonoCliente', Celular = '$CelularCliente', Correo = '$CorreoCliente', Fecha_Nacimiento = '', Sexo = '', RFC = '$RFCCliente', Facturar = '$FacturarCliente', No_Cuenta = '$CuentaBancoCliente', Banco = '$BancoCliente', Titular = '$TitularBancoCliente', FK_Sucursal = '$SucursalCliente', Razon_CFDI = '$razonCliente', Regimen_CFDI = '$regimenCliente', Nombre_Contacto = '$contactoCliente', Puesto_Contacto = '$puestoContactoCliente', Email_Contacto = '$correoContactoCliente', Tel_Contacto = '$telefonoContactoCliente', Tipo_Persona = '$TipoPersona' WHERE ID_Cliente = '$IDCliente'";
		}
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorModificar: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto~";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

			$direcciones = explode(",", $direcciones);
			for ($i=0; $i < sizeof($direcciones) - 1; $i++) { 
				$datosdireccion = explode("~", $direcciones[$i]);

				$queryPrese = "SELECT ID_Detalle_Cliente, FK_Cliente, Calle, No_Exterior, No_Interior, Colonia, Codigo_Postal, Ciudad, Estado, Pais, Nombre_Contacto, Puesto_Contacto, Email_Contacto, Telefono_Contacto FROM detalles_clientes WHERE FK_Cliente = '$IDCliente' AND Calle = '$datosdireccion[0]' AND No_Exterior = '$datosdireccion[1]' AND Ciudad = '$datosdireccion[5]' AND Estado = '$datosdireccion[6]' AND Pais = '$datosdireccion[7]'";
				$rowPrese = $omodelo->_consultar($queryPrese);
				$numerofilasPrese = $omodelo->numerofilas;

				if ($rowPrese == "si") {
					echo "Error consultar archivo: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilasPrese > 0){
						$queryDirecciones = "UPDATE detalles_clientes SET No_Interior = '$datosdireccion[2]', Colonia = '$datosdireccion[4]', Codigo_Postal = '$datosdireccion[3]',  Nombre_Contacto = '$datosdireccion[8]', Puesto_Contacto = '$datosdireccion[9]', Email_Contacto = '$datosdireccion[10]', Telefono_Contacto  = '$datosdireccion[11]', Detalles  = '$datosdireccion[12]', Latitud  = '$datosdireccion[13]', Longitud  = '$datosdireccion[14]', Entre_Calles  = '$datosdireccion[15]' WHERE FK_Cliente = '$IDCliente' AND Calle = '$datosdireccion[0]' AND No_Exterior = '$datosdireccion[1]' AND Ciudad = '$datosdireccion[5]' AND Estado = '$datosdireccion[6]' AND Pais = '$datosdireccion[7]'";
						$errorDirecciones = $omodelo->_insertar($queryDirecciones);	

						if ($errorDirecciones == "si") {
							echo "Error direcciones: ".mysqli_error($omodelo->link); 
						}
					}else{
						$queryDirecciones = "INSERT INTO detalles_clientes SET FK_Cliente = '$IDCliente', Calle = '$datosdireccion[0]', No_Exterior = '$datosdireccion[1]', No_Interior = '$datosdireccion[2]', Colonia = '$datosdireccion[4]', Codigo_Postal = '$datosdireccion[3]', Ciudad = '$datosdireccion[5]', Estado = '$datosdireccion[6]', Pais = '$datosdireccion[7]', Nombre_Contacto = '$datosdireccion[8]', Puesto_Contacto = '$datosdireccion[9]', Email_Contacto = '$datosdireccion[10]', Telefono_Contacto  = '$datosdireccion[11]', Detalles  = '$datosdireccion[12]', Latitud  = '$datosdireccion[13]', Longitud  = '$datosdireccion[14]', Entre_Calles  = '$datosdireccion[15]'";
						$errorDirecciones = $omodelo->_insertar($queryDirecciones);	

						if ($errorDirecciones == "si") {
							echo "Error direcciones: ".mysqli_error($omodelo->link); 
						}
					}
				}
			}

			$status = 1;
			if ($_FILES['FotoCliente']['size'] > 0 && $_FILES['FotoCliente']['error'] == 0) {
				$file = $_FILES["FotoCliente"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosClientes/";

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
						if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosClientes/".$row[0]["Foto"])){
					       	unlink("vistas/assets/archivos/fotosClientes/".$row[0]["Foto"]);
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
		if ($tipo == "EliminarCliente") {
			$IDCliente = $omodelo->link->real_escape_string($IDCliente);

			$query = "SELECT Foto FROM clientes WHERE ID_Cliente = '$IDCliente'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$nombreFoto = "";
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosClientes/".$row[0]["Foto"])){
				       	unlink("vistas/assets/archivos/fotosClientes/".$row[0]["Foto"]);
				    }
				}
			}

			$query = "DELETE FROM clientes WHERE ID_Cliente = '$IDCliente'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			    $omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if ($tipo == "EliminarDireccion") {
			$IDDireccion =  $omodelo->link->real_escape_string($IDDireccion);

			$query = "DELETE FROM detalles_clientes WHERE ID_Detalle_Cliente = '$IDDireccion'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: " . mysqli_error($omodelo->link);
			} else {
				echo "Correcto";
				
				//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$IDCliente = $omodelo->link->real_escape_string($IDCliente);

		$query = "SELECT ID_Cliente, Nombre, Primer_Apellido, Segundo_Apellido, Telefono, Celular, Correo, Fecha_Nacimiento, Sexo, Fecha_Registro, Foto, RFC, Facturar, No_Cuenta, Banco, Titular, FK_Sucursal, Razon_CFDI, Regimen_CFDI, Calle, No_Exterior, No_Interior, Colonia, Ciudad, Codigo_Postal, Estado, Pais, Nombre_Contacto, Puesto_Contacto, Email_Contacto, Tel_Contacto, INE, Tipo_Persona FROM clientes WHERE ID_Cliente = '$IDCliente'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$subarreglo = null;

				$queryDirecciones = "SELECT ID_Detalle_Cliente, FK_Cliente, Calle, No_Exterior, No_Interior, Colonia, Codigo_Postal, Ciudad, Estado, Pais, Detalles, Nombre_Contacto, Puesto_Contacto, Email_Contacto, Telefono_Contacto, Latitud, Longitud, Entre_Calles FROM detalles_clientes WHERE FK_Cliente = '".$row[0]['ID_Cliente']."'";
				$rowDirecciones = $omodelo->_consultar($queryDirecciones);
				$numerofilasDirecciones = $omodelo->numerofilas;
				for ($x=0; $x < $numerofilasDirecciones; $x++) { 
					$subarreglo[$x] = array(
						'ID_Detalle_Cliente' => $rowDirecciones[$x]["ID_Detalle_Cliente"],
						'FK_Cliente' => $rowDirecciones[$x]["FK_Cliente"],
						'Calle' => $rowDirecciones[$x]["Calle"],
						'No_Exterior' => $rowDirecciones[$x]["No_Exterior"],
						'No_Interior' => $rowDirecciones[$x]["No_Interior"],
						'Colonia' => $rowDirecciones[$x]["Colonia"],
						'Codigo_Postal' => $rowDirecciones[$x]["Codigo_Postal"],
						'Ciudad' => $rowDirecciones[$x]["Ciudad"],
						'Estado' => $rowDirecciones[$x]["Estado"],
						'Pais' => $rowDirecciones[$x]["Pais"],
						'Detalles' => $rowDirecciones[$x]["Detalles"],
						'Nombre_Contacto' => $rowDirecciones[$x]["Nombre_Contacto"],
						'Puesto_Contacto' => $rowDirecciones[$x]["Puesto_Contacto"],
						'Email_Contacto' => $rowDirecciones[$x]["Email_Contacto"],
						'Telefono_Contacto' => $rowDirecciones[$x]["Telefono_Contacto"],
						'Latitud' => $rowDirecciones[$x]["Latitud"],
						'Longitud' => $rowDirecciones[$x]["Longitud"],
						'Entre_Calles' => $rowDirecciones[$x]["Entre_Calles"]
					);
				}

				$arreglo = array(
						'ID' => $row[0]['ID_Cliente'],
						'Nombre' => $row[0]["Nombre"],
						'Primer_Apellido' => $row[0]['Primer_Apellido'],
						'Segundo_Apellido' => $row[0]['Segundo_Apellido'],
						'Telefono' => $row[0]["Telefono"],
						'Celular' => $row[0]["Celular"],
						'Correo' => $row[0]["Correo"],
						'Fecha_Nacimiento' => $row[0]["Fecha_Nacimiento"],
						'Sexo' => $row[0]["Sexo"],
						'Fecha_Registro' => $row[0]["Fecha_Registro"],
						'Foto' => $row[0]["Foto"],
						'RFC' => $row[0]["RFC"],
						'Facturar' => $row[0]["Facturar"],
						'No_Cuenta' => $row[0]["No_Cuenta"],
						'Banco' => $row[0]["Banco"],
						'Titular' => $row[0]["Titular"],
						'FK_Sucursal' => $row[0]["FK_Sucursal"],
						'Razon_CFDI' => $row[0]["Razon_CFDI"],
						'Regimen_CFDI' => $row[0]["Regimen_CFDI"],
						'Calle' => $row[0]["Calle"],
						'No_Exterior' => $row[0]["No_Exterior"],
						'No_Interior' => $row[0]["No_Interior"],
						'Colonia' => $row[0]["Colonia"],
						'Codigo_Postal' => $row[0]["Codigo_Postal"],
						'Ciudad' => $row[0]["Ciudad"],
						'Estado' => $row[0]["Estado"],
						'Pais' => $row[0]["Pais"],
						'Nombre_Contacto' => $row[0]["Nombre_Contacto"], 
						'Puesto_Contacto' => $row[0]["Puesto_Contacto"], 
						'Email_Contacto' => $row[0]["Email_Contacto"], 
						'Tel_Contacto' => $row[0]["Tel_Contacto"],
						'INE' => $row[0]["INE"],
						'Tipo_Persona' => $row[0]["Tipo_Persona"],
						'Extras' => $subarreglo
				);

				echo json_encode($arreglo);
			}
		}
	}
}
?>
