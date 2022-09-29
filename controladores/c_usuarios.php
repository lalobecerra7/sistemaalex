
<?php
class usuarios {

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
				$busqueda .= "CONCAT(ID_Usuario, Nombre, Primer_Apellido, Segundo_Apellido, Correo, Contrasena, Tipo_Usuario, Permisos, BD, Estatus, Intentos, Ultimo_Intento, Tiempo_Inicio, Tiempo_Final, Foto, Temporal, Activo, Tipo_Login, Conectado, Fecha_Alta) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Usuario, Nombre, Primer_Apellido, Segundo_Apellido, Correo, Contrasena, Tipo_Usuario, Permisos, BD, Estatus, Intentos, Ultimo_Intento, Tiempo_Inicio, Tiempo_Final, Foto, Temporal, Activo, Tipo_Login, Conectado, Fecha_Alta, (SELECT COUNT(*) FROM usuarios WHERE ID_Usuario <> '".$_SESSION['user_admin']['ID_Usuario']."' $busqueda) AS Num FROM usuarios WHERE ID_Usuario <> '".$_SESSION['user_admin']['ID_Usuario']."' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					
					$foto = '<a href="vistas/assets/archivos/default.jpg" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/default.jpg'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
					if ($row[$i]["Foto"] != "") {
						if($row[$i]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosUsuarios/".$row[$i]["Foto"])){
							$foto = '<a href="vistas/assets/archivos/fotosUsuarios/'.$row[$i]["Foto"].'" data-fancybox="images">
									<div style="background-image: url('."'".'vistas/assets/archivos/fotosUsuarios/'.$row[$i]["Foto"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
						}	
					}
					$estatus = "";
					if ($row[$i]['Estatus'] == "1") {
						$estatus = '<span class="badge rounded-pill bg-danger">Bloqueado</span>';
					}else if($row[$i]['Estatus'] == "0"){
						$estatus = '<span class="badge rounded-pill bg-success">Desbloqueado</span>';
					}

					if ($row[$i]['Tipo_Usuario'] == "Normal") {
						$botonPermisos = '<button class="btn btn-link" id="VerPermisosUsuario" attrid="'.$row[$i]['ID_Usuario'].'" cadena="'.$row[$i]['Permisos'].'"><i class="fas fa-lock-open"></i></button>';
					}else{
						$botonPermisos = '<button class="btn btn-link"><i class="fas fa-ellipsis"></i></button>';
					}

					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][3] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm" id="ModificarUsuario" attrid="'.$row[$i]['ID_Usuario'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][4] == '1') {
						$botonPermisosEliminar = '<button class="btn btn-danger btn-sm" id="EliminarUsuario" attrid="'.$row[$i]['ID_Usuario'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>';
					}

					$botonPermisosPermisos = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][5] == '1') {
						$botonPermisosPermisos = $botonPermisos;
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Usuario'],
						'Foto' => $foto,
						'Nombre' => $row[$i]['Nombre']." ".$row[$i]['Primer_Apellido']." ".$row[$i]['Segundo_Apellido'],
						'Usuario' => $row[$i]['Correo']."<br>Tipo de usuario: <b>".$row[$i]['Tipo_Usuario']."</b>",
						'Estatus' => $estatus,
						'Permisos' => $botonPermisosPermisos,
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
		
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$opciones = ['cost' => 12];
		$password = password_hash($omodelo->link->real_escape_string($NuevaContrasena), PASSWORD_BCRYPT, $opciones);
		$query = "UPDATE usuarios SET Nombre = '$NombreUsuario', Primer_Apellido = '$PrimerApellidoUsuario', Segundo_Apellido = '$SegundoApellidoUsuario', Correo = '$CorreoUsuario', Contrasena = '$password', Tipo_Usuario = '$TipoUsuario', Estatus = '$EstatusUsuario', Temporal = '$ContraTemporal', Activo = '$EstatusCuenta', Tipo_Login = '1', Conectado = '0', Fecha_Alta = '$fecha' WHERE ID_Usuario = '$idUsuario'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorModificar: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto~";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

			$status = 1;
			if ($_FILES['FotoUsuario']['size'] > 0 && $_FILES['FotoUsuario']['error'] == 0) {
				$file = $_FILES["FotoUsuario"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosUsuarios/";

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

				$query = "SELECT Foto FROM usuarios WHERE ID_Usuario = '$idUsuario'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
				$nombreFoto = "";
				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosUsuarios/".$row[0]["Foto"])){
					       	unlink("vistas/assets/archivos/fotosUsuarios/".$row[0]["Foto"]);
					    }
					}
				}

				$query2 = "UPDATE usuarios SET Foto = '".$idUsuario.'_'.$nombreDoc."' WHERE ID_Usuario = '$idUsuario'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$idUsuario.'_'.$nombreDoc);
				}
			}
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$IDUsuario =  $omodelo->link->real_escape_string($IDUsuario);

		$query = "SELECT Foto FROM usuarios WHERE ID_Usuario = '$IDUsuario'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		$nombreFoto = "";
		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				if($row[0]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosUsuarios/".$row[0]["Foto"])){
			       	unlink("vistas/assets/archivos/fotosUsuarios/".$row[0]["Foto"]);
			    }
			}
		}

		$query = "DELETE FROM usuarios WHERE ID_Usuario = '$IDUsuario'";
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
		if ($tipo == "ConsultarUsuario") {
			$IDUsuario =  $omodelo->link->real_escape_string($IDUsuario);

			$query = "SELECT ID_Usuario, Nombre, Primer_Apellido, Segundo_Apellido, Correo, Contrasena, Tipo_Usuario, Permisos, BD, Estatus, Intentos, Ultimo_Intento, Tiempo_Inicio, Tiempo_Final, Foto, Temporal, Activo, Tipo_Login, Conectado, Fecha_Alta FROM usuarios WHERE ID_Usuario = '$IDUsuario'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}
		}else if($tipo == "CambiarContrasena"){
			$ContraActual =  $omodelo->link->real_escape_string($ContraActual);
			$ContraNueva =  $omodelo->link->real_escape_string($ContraNueva);
			$opciones = ['cost' => 12];
			$password = password_hash($omodelo->link->real_escape_string($ContraNueva), PASSWORD_BCRYPT, $opciones);

			$query1 = "SELECT Contrasena FROM usuarios WHERE ID_Usuario = '".$_SESSION['user_admin']["ID_Usuario"]."'";
			$row = $omodelo->_consultar($query1);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					if (password_verify($ContraActual, $row[0]['Contrasena'])) {
						$query = "UPDATE usuarios SET Contrasena = '$password' WHERE ID_Usuario = '".$_SESSION['user_admin']["ID_Usuario"]."'";
						$error = $omodelo->_insertar($query);

						if ($error == "si") {
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							echo "Correcto";
							$_SESSION['user_admin']["Contrasena"] = $password;
							$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
						}
					}else{
						echo "NoCoincide";
					}
				}else{
					echo "NoCoincide";
				}
			}
		}else if($tipo == "ModificarPermisos"){
			$id = $omodelo->link->real_escape_string($id);
			$cadena = $omodelo->link->real_escape_string($cadena);

			$query = "UPDATE usuarios SET Permisos = '$cadena' WHERE ID_Usuario = '$id'";	
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if($tipo == "ConsultarPermisosUsuario"){
			$query = "SELECT Permisos, Tipo_Usuario FROM usuarios WHERE ID_Usuario='".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			
			if ($row == "si"){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo = array('Cadena'=> $row[0]['Permisos'], 'Tipo'=> $row[0]['Tipo_Usuario']); 
				}
			}

			echo json_encode($arreglo);
		}
	}


}
?>
