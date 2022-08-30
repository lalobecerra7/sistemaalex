
<?php
class hacerventa {

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
				$busqueda .= "CONCAT(ID_Cliente, Nombre, Foto, Direccion, Telefono, Celular, Correo, Ciudad, Colonia, Codigo_Postal, Estado, Pais, Lim_Credito, Titular, Banco, No_Cuenta, Fecha_Registro) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Cliente, Nombre, Foto, Direccion, Telefono, Celular, Correo, Ciudad, Colonia, Codigo_Postal, Estado, Pais, Fecha_Registro AS Fecha, Lim_Credito, Titular, Banco, No_Cuenta, (SELECT COUNT(*) FROM clientes WHERE ID_Cliente <> 1 $busqueda) AS Num FROM clientes WHERE ID_Cliente <> 1 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

					if ($row[$i]['Titular'] != "") {
						$datosbancarios .= "Titular: ".$row[$i]['Titular']."<br>";
					}

					if ($row[$i]['Banco'] != "") {
						$datosbancarios .= "Banco: ".$row[$i]['Banco']."<br>";
					}

					if ($row[$i]['No_Cuenta'] != "") {
						$datosbancarios .= "CLABE o número de cuenta: ".$row[$i]['No_Cuenta']."<br>";
					}

					$foto = '<a href="vistas/assets/archivos/fotosClientes/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosClientes/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
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
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Cliente'],
						'Fecha' => $row[$i]['Fecha'],
						'Nombre' => $foto.$row[$i]['Nombre'],
						'Direccion' => $direccion,
						'Contacto' => $contacto,
						'Detalles' => $datosbancarios,
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
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

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
		$query = "UPDATE clientes SET Nombre = '$NombreCliente', Direccion = '$DireccionCliente', Telefono = '$TelefonoCliente', Celular = '$CelularCliente', Ciudad = '$CiudadCliente', Colonia = '$ColoniaCliente', Codigo_Postal = '$CPCliente', Tipo_Descuento = '$TipoDescuentoCliente', Descuento = '$DescuentoCliente', Correo = '$CorreoCliente', Fecha_Nacimiento = '$FechaNacimientoCliente', Sexo = '$SexoCliente', RFC = '$RFCCliente', Empresa = '$NombreEmpresaCliente', No_Cuenta = '$CuentaBancoCliente', Banco = '$BancoCliente', Titular = '$TitularBancoCliente', Pais = '$PaisCliente', Estado = '$EstadoCliente' WHERE ID_Cliente = '$IDCliente'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorModificar: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto~";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

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
		$IDCliente =  $omodelo->link->real_escape_string($IDCliente);

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

	
	/*public function _insertar(){
		$omodelo = new m_modelo();
		date_default_timezone_set('America/Mexico_City');
		//::::::::::::::::::::::::::::::::::::VALIDACIÓN DE CARACTERES ESPECIALES NO BORRAR
		function sanitize($arg) {
		    if (is_array($arg)) {
		        return array_map('sanitize', $arg);
		    }

		    return htmlspecialchars($arg, ENT_QUOTES, 'UTF-8');
		}
		$array = array_map('sanitize', $_POST);
		extract($array);
		//::::::::::::::::::::::::::::::::::::VALIDACIÓN DE CARACTERES ESPECIALES NO BORRAR
		$Estatus = 0;

		function LimpiarArchivo($string) {
		   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		   return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
		}

		$nombreImg = "";
		$ruta_provisional="";
		$ruta="";

		if (isset($_FILES["fotoCliente"]) && $_FILES["fotoCliente"]['name'] != "") {
			$file = $_FILES["fotoCliente"];
		    $nombreImg = $file["name"];
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $carpeta = "vistas/assets/archivos/fotosClientes/";
		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/svg' && $tipo != 'image/bmp' && $tipo != ''){
		      	echo "Error1"; 
		      	$Estatus = 1;
		    }else if ($size > (1024*1024*10)){
	      		echo "Error2";
	      		$Estatus = 1;
	    	}else{
	    		$NombreArchivo = LimpiarArchivo($nombreImg);
	    		$nombreImg = $_SESSION['user_admin']['cliente']['id_cliente']."_".rand()."_".$NombreArchivo;
	    		$ruta = $carpeta.$nombreImg;
	    	}
		}

		if($Estatus == 0){
			$query = "INSERT INTO clientes SET nombre  = '$nombreC', direccion = '$DireccionC', telefono = '$TelefonoC', celular = '$CelularC', ciudad = '$CiudadC', colonia = '$ColoniaC', codigo_postal = '$CodPostalC', descuento = '$DescuentoC', lim_credito = '$limiteC', correo = '$correoC', fecha_nacimiento = '$fechaNacimientoC', sexo = '$sexoC', fecha_alta = NOW(), rfc = '$rfcC', empresa = '$empresaC', no_cuenta = '$cuentaC', banco = '$bancoC', Pais = '$PaisC', Estado = '$EstadoC', foto = '$nombreImg'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error al insertar cliente: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);
				echo "Correcto~$id";	
				if(file_exists("$ruta") && $nombreImg != ""){
		       		unlink("$ruta");
		    	}
				move_uploaded_file($ruta_provisional,  $ruta);
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
	}
	
	public function _modificar(){
		$omodelo = new m_modelo();
		//::::::::::::::::::::::::::::::::::::VALIDACIÓN DE CARACTERES ESPECIALES NO BORRAR
		function sanitize($arg) {
		    if (is_array($arg)) {
		        return array_map('sanitize', $arg);
		    }

		    return htmlspecialchars($arg, ENT_QUOTES, 'UTF-8');
		}
		$array = array_map('sanitize', $_POST);
		extract($array);
		//::::::::::::::::::::::::::::::::::::VALIDACIÓN DE CARACTERES ESPECIALES NO BORRAR
		
		$Estatus = 0;

		function LimpiarArchivo($string) {
		   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		   return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
		}

		$nombreImg = "";
		$ruta_provisional="";
		$ruta="";

		if (isset($_FILES["fotoCliente"]) && $_FILES["fotoCliente"]['name'] != "") {
			$file = $_FILES["fotoCliente"];
		    $nombreImg = $file["name"];
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $carpeta = "vistas/assets/archivos/fotosClientes/";
		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/svg' && $tipo != 'image/bmp' && $tipo != ''){
		      	echo "Error1"; 
		      	$Estatus = 1;
		    }else if ($size > (1024*1024*10)){
	      		echo "Error2";
	      		$Estatus = 1;
	    	}else{
	    		$NombreArchivo = LimpiarArchivo($nombreImg);
	    		$nombreImg = $_SESSION['user_admin']['cliente']['id_cliente']."_".rand()."_".$NombreArchivo;
	    		$ruta = $carpeta.$nombreImg;
	    	}
	    	$queryfoto = ", foto = '$nombreImg'";
	    	$antigua = $ligaFotoCliente;
	    	$rutaantigua = $carpeta.$antigua;
		}else{
			$queryfoto = "";
			$nombreImg = $ligaFotoCliente;
			$ruta = "";
		}

		if($Estatus == 0){
			$query = "UPDATE clientes SET nombre  = '$nombreC', direccion = '$DireccionC', telefono = '$TelefonoC', celular = '$CelularC', ciudad = '$CiudadC', colonia = '$ColoniaC', codigo_postal = '$CodPostalC', descuento = '$DescuentoC', lim_credito = '$limiteC', correo = '$correoC', fecha_nacimiento = '$fechaNacimientoC', sexo = '$sexoC', fecha_alta = NOW(), rfc = '$rfcC', empresa = '$empresaC', no_cuenta = '$cuentaC', banco = '$bancoC', Pais = '$PaisC', Estado = '$EstadoC' $queryfoto WHERE id_cliente = '$id'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error al modificar cliente: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto~$id";	

				if(file_exists("$rutaantigua") && $nombreImg != ""){
		       		unlink("$rutaantigua");
		    	}
				move_uploaded_file($ruta_provisional,  $ruta);
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$query = ("DELETE FROM clientes WHERE id_cliente = '$id';");
		$error = $omodelo->_insertar($query);
		if ($error == "si") {
			echo "Error al eliminar el cliente: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";

			if($foto != "" && file_exists("vistas/assets/archivos/fotosClientes/$foto")){
		       	unlink("vistas/assets/archivos/fotosClientes/$foto");
		    }
		    $omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _foto(){
		$omodelo = new m_modelo();
		extract($_POST);
		if (!empty($_FILES)) {
			//var_dump($_FILES);
			if($ligaFoto != 0){
				$dir = "archivos/fotosClientes/".$ligaFoto;
				if(file_exists($dir)){
					unlink($dir);
				}
			}
			if($_FILES["foto"]["size"] > 0){
				$tipob = $_FILES["foto"]["type"];
				$archivob = $_FILES["foto"]["name"];
				$prefijo = substr(md5(uniqid(rand())),0,4);
				$archiv = explode(".",$archivob);
				$liga = $_SESSION['id_cliente']."_".$prefijo."_".$archivob;
				$destino =  "archivos/fotosClientes/".$liga;
				if ($archiv[1] == "jpg" || $archiv[1] == "png" || $archiv[1] == "gif") {
					/*if ($nombre != "") {
					$nombreEx = explode(" ",$nombre);
					for($w=0;$w<count($nombreEx);$w++){
						$nombreFotoB = $nombreFotoB.$nombreEx[$w];
					}
					$nombreFoto = $nombreFotoB.".".$archiv[1];
					if (copy($_FILES['foto']['tmp_name'],$destino)) {								
						$estado = "Archivo subido: ".$archiv[0]."";
					} else {
						$estado =  "Error 1 al subir el archivo";
					}
				/*} else {
					$estado =  "Error 2 al subir archivo";
				}
				$query = ("UPDATE clientes set foto='".$liga."' where id_cliente = '$idR';");
				$error = $omodelo->_insertar($query);
				}else{
					echo "Error al subir el archivo";
				}
			}
		}
	}

	
	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$permisosMo = null;

		if(isset($_SESSION['user_admin'])){
			$query = "SELECT permisos FROM usuarios WHERE id_usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_consultar($query); 
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$modulos = explode('~', $row[0]['permisos']);
					for ($i=0; $i < count($modulos); $i++) { 
						$cadena = explode(',', $modulos[$i]);
						$nombreModu = $cadena[0];
						unset($cadena[0]);
						$permisosMo[$nombreModu] = $cadena;
					}	
				}
			}
		}

		if ($tipo == "tabla") {
			$arreglo['data'] = array(); 
			$query  = "SELECT * FROM clientes WHERE id_cliente <> '1'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) {
						$laFoto = 'vistas/assets/archivos/fotosClientes/user.gif';
						if($row[$i]["foto"] != "" && file_exists('vistas/assets/archivos/fotosClientes/'.$row[$i]["foto"].'')){
							$laFoto = 'vistas/assets/archivos/fotosClientes/'.$row[$i]["foto"].'';
						}

						$BotonModificar = '';
						$BotonEliminar = '';

						if (@$permisosMo['v_clientes'][3] == '1') {
							$BotonModificar = '<button type="button" class="btn btn-theme-inverse btn-info modificarCliente btnModificar'.$row[$i]["id_cliente"].'" attrid="'.$row[$i]["id_cliente"].'"  nombre="'.$row[$i]['nombre'].'"  data-toggle="modal" data-target="#ModalClientes"><i class="fa fa-pencil-square-o"></i></button>';
						}

						if (@$permisosMo['v_clientes'][4] == '1') {
							$BotonEliminar = '<button type="button" class="btn btn-danger eliminarCliente" foto="'.$row[$i]['foto'].'"  nombre="'.$row[$i]['nombre'].'" attrid="'.$row[$i]["id_cliente"].'"><i class="fa fa-trash-o"></i></button>';
						}

						$arreglo['data'][$i] = array("Nombre" => '<a href="'.$laFoto.'" title="'.utf8_encode($row[$i]["nombre"]).'" class="preview_fancybox" attrID="'.$row[$i]['id_cliente'].'"><img style="width:50px" class="circle" src="'.$laFoto.'"/></a><br><b>'.$row[$i]["nombre"].'</b>', "Direccion" => $row[$i]["direccion"], "Telefonos" => "<b>Telefono: </b>".$row[$i]["telefono"]."<br> <b>Celular: </b>".$row[$i]["celular"], "Correo" => $row[$i]["correo"], "Limite credito" => "<b class='dinero'>".$row[$i]["lim_credito"]."</b>", "Detalles" => '<button class="btn btn-sm btn-link bVerDetallesCliente" data-toggle="modal" data-target="#ModalVerDetallesCliente" nombre="'.$row[$i]['nombre'].'" attrID="'.$row[$i]['id_cliente'].'"><i class="fa fa-eye"></i> Ver detalles</button>', "Accion" => $BotonModificar.' '.$BotonEliminar); 
					}
				}
				echo json_encode($arreglo);
			} 	
		}else if ($tipo == "ClienteNuevo") {
			$arreglo['data'] = array(); 
			$query  = "SELECT * FROM clientes WHERE id_cliente = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) {
						$laFoto = 'vistas/assets/archivos/fotosClientes/user.gif';
						if($row[$i]["foto"] != "" && file_exists('vistas/assets/archivos/fotosClientes/'.$row[$i]["foto"].'')){
							$laFoto = 'vistas/assets/archivos/fotosClientes/'.$row[$i]["foto"].'';
						}

						$BotonModificar = '';
						$BotonEliminar = '';

						if (@$permisosMo['v_clientes'][3] == '1') {
							$BotonModificar = '<button type="button" class="btn btn-theme-inverse btn-info modificarCliente btnModificar'.$row[$i]["id_cliente"].'" attrid="'.$row[$i]["id_cliente"].'"  nombre="'.$row[$i]['nombre'].'"  data-toggle="modal" data-target="#ModalClientes"><i class="fa fa-pencil-square-o"></i></button>';
						}

						if (@$permisosMo['v_clientes'][4] == '1') {
							$BotonEliminar = '<button type="button" class="btn btn-danger eliminarCliente" foto="'.$row[$i]['foto'].'"  nombre="'.$row[$i]['nombre'].'" attrid="'.$row[$i]["id_cliente"].'"><i class="fa fa-trash-o"></i></button>';
						}

						$arreglo['data'][$i] = array("Nombre" => '<a href="'.$laFoto.'" title="'.$row[$i]["nombre"].'" class="preview_fancybox" attrID="'.$row[$i]['id_cliente'].'"><img style="width:50px" class="circle" src="'.$laFoto.'"/></a><br><b>'.$row[$i]["nombre"].'</b>', "Direccion" => $row[$i]["direccion"], "Telefonos" => "<b>Telefono: </b>".$row[$i]["telefono"]."<br> <b>Celular: </b>".$row[$i]["celular"], "Correo" => $row[$i]["correo"], "Limite credito" => "<b class='dinero'>".$row[$i]["lim_credito"]."</b>", "Detalles" => '<button class="btn btn-sm btn-link bVerDetallesCliente" data-toggle="modal" data-target="#ModalVerDetallesCliente" nombre="'.$row[$i]['nombre'].'" attrID="'.$row[$i]['id_cliente'].'"><i class="fa fa-eye"></i> Ver detalles</button>', "Accion" => $BotonModificar.' '.$BotonEliminar); 
					}
					echo json_encode($arreglo);
				}
			} 	
		}else if ($tipo == "DetallesCliente") {
			$query = "SELECT fecha_nacimiento, ciudad, colonia, Estado, Pais, empresa, codigo_postal, descuento, sexo, rfc, no_cuenta, banco FROM clientes WHERE id_cliente = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$tabla = '<table class="table table-responsive">
						<thead>';
					if ($row[0]["fecha_nacimiento"] != "") {
						$tabla .= '
							<tr>
								<th>Fecha de nacimiento</th>
								<th>'.$row[0]["fecha_nacimiento"].'</th>
							</tr>';
					}
					if ($row[0]["sexo"] != "") {
						$tabla .= '
							<tr>
								<th>Sexo</th>
								<th>'.$row[0]["sexo"].'</th>
							</tr>';
					}
					if ($row[0]["descuento"] != "") {
						$tabla .= '
							<tr>
								<th>Descuento</th>
								<th>'.$row[0]["descuento"].'</th>
							</tr>';
					}
					if ($row[0]["empresa"] != "") {
						$tabla .= '
							<tr>
								<th>Empresa</th>
								<th>'.$row[0]["empresa"].'</th>
							</tr>';
					}
					if ($row[0]["Estado"] != "") {
						$tabla .= '
							<tr>
								<th>Estado</th>
								<th>'.$row[0]["Estado"].'</th>
							</tr>';
					}
					if ($row[0]["colonia"] != "" || $row[0]["codigo_postal"] != "" || $row[0]["ciudad"] != "") {
						$tabla .= '
							<tr>
								<th>Ciudad</th>
								<th>'.$row[0]["colonia"].', '.$row[0]["codigo_postal"].', '.$row[0]["ciudad"].'</th>
							</tr>';
					}
					if ($row[0]["Pais"] != "") {
						$tabla .= '
							<tr>
								<th>Pais</th>
								<th>'.$row[0]["Pais"].'</th>
							</tr>';
					}
					if ($row[0]["rfc"] != "") {
						$tabla .= '
							<tr>
								<th>RFC</th>
								<th>'.$row[0]["rfc"].'</th>
							</tr>';
					}
					if ($row[0]["no_cuenta"] != "") {
						$tabla .= '
							<tr>
								<th>Numero de cuenta: </th>
								<th>'.$row[0]["no_cuenta"].'</th>
							</tr>';
					}
					if ($row[0]["banco"] != "") {
						$tabla .= '
							<tr>
								<th>Banco</th>
								<th>'.$row[0]["banco"].'</th>
							</tr>';
					}

					$tabla .= '</thead>
							</table>';

					echo $tabla;
				}
			}
		}else if($tipo == "CargarCliente"){
			$query  = "SELECT * FROM clientes WHERE id_cliente = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row);
				}
			}
		}
	}

	public function _detalles(){
		unset($_SESSION['sus']);
		$_SESSION['susEmpresa'] = array(
			"id" => $_SESSION['id_cliente'],
			"paquete" => $_SESSION['Paquete'],
			"fechaInicio" => $_SESSION["Fecha_Inicio"],
			"fechaFin" => $_SESSION["Fecha_Fin"]
		);
	}*/

}
?>
