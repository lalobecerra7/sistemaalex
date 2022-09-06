
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
		if (trim($buscar) != '') {
			$separa = explode(' ', trim($buscar));
			$busqueda = 'WHERE ';
			for ($i=0; $i < count($separa); $i++) {
				$busqueda .= "CONCAT(ID_Usuario, Nombre, Usuario, Estatus, Foto) REGEXP '" . $separa[$i] . "'";
				if($i < (count($separa)-1)) {
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Usuario, Nombre, Usuario, Estatus, Permisos, Foto, (SELECT COUNT(*) FROM usuarios WHERE ID_Usuario <> " . $_SESSION['user_admin']['ID_Usuario'] . " $busqueda) AS Num FROM usuarios WHERE ID_Usuario <> " . $_SESSION['user_admin']['ID_Usuario'] . " $busqueda LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		if ($row == 'si') {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			if ($numerofilas > 0) {
				for ($i = 0; $i < $numerofilas; $i++) {
					$foto = '<a href="vistas/assets/img/usuarios/usuario.png" data-fancybox="images">
									<div style="background-image: url(' . "'" . 'vistas/assets/img/usuarios/usuario.png' . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
					if ($row[$i]["Foto"] != "") {
						if ($row[$i]["Foto"] != "" && file_exists("vistas/assets/img/usuarios/" . $row[$i]["Foto"])) {
							$foto = '<a href="vistas/assets/img/usuarios/' . $row[$i]["Foto"] . '" data-fancybox="images">
									<div style="background-image: url(' . "'" . 'vistas/assets/img/usuarios/' . $row[$i]["Foto"] . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
									</div>
								</a><br>';
						}
					}

					if ($row[$i]['Estatus'] == "Bloqueado") {
						$estatus = '<span class="badge rounded-pill bg-danger">Bloqueado</span>';
					}else if($row[$i]['Estatus'] == "Desbloqueado"){
						$estatus = '<span class="badge rounded-pill bg-success">Desbloqueado</span>';
					}

					// if ($row[$i]['Tipo'] == "Normal") {
						$botonPermisos = '<button class="btn btn-link" id="VerPermisosUsuario" attrid="'.$row[$i]['ID_Usuario'].'" cadena="'.$row[$i]['Permisos'].'"><i class="fas fa-lock-open"></i></button>';
					// }else{
					// 	$botonPermisos = '<button class="btn btn-link"><i class="fas fa-ellipsis"></i></button>';
					// }

					$botonModificar = '';
					$botonEliminar = '';
					$botondePermisos = '';
					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][3] == '1'){
						$botonModificar = '<button class="btn btn-primary" id="ModificarUsuario" attrid="' . $row[$i]['ID_Usuario'] . '" nombre="' . $row[$i]['Nombre'] . '"><i class="fas fa-edit"></i></button>';
					}

					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][4] == '1'){
						$botonEliminar = '<button class="btn btn-danger" id="EliminarUsuario" attrid="' . $row[$i]['ID_Usuario'] . '" nombre="' . $row[$i]['Nombre'] . '"><i class="fas fa-trash"></i></button>';
					}

					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][5] == '1'){
						$botondePermisos = $botonPermisos;
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Usuario'],
						'Foto' => $foto,
						'Nombre' => $row[$i]['Nombre'],
						'Correo' => $row[$i]['Usuario'],
						//'Tipo' => $row[$i]['Tipo'],
						'Estatus' => $estatus,
						'Permisos' => $botondePermisos,
						'Acciones' => $botonModificar.' '.$botonEliminar,
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
		$opciones = ['cost' => 12];
		$firstname = $omodelo->link->real_escape_string(trim($firstname));
		$email = $omodelo->link->real_escape_string(trim($email));
		$password = password_hash($omodelo->link->real_escape_string($contrasena), PASSWORD_BCRYPT, $opciones);
		$tipo_usuario = $omodelo->link->real_escape_string($Tipo_usuario);
		$estado = $omodelo->link->real_escape_string($estatus);

		$query = "INSERT INTO usuarios (ID_Usuario, Nombre, Usuario, Contrasena, Estatus, Tipo, Permisos) VALUES (null, '$firstname', '$email', '$password', '$estado', '$tipo_usuario', 'v_inventario,0,0,0,0,0~v_ventas,0,0,0,0,0~v_salidas,0,0,0~v_clientes,0,0,0,0~v_etiquetas,0,0~v_productos,0,0,0,0~v_reportesalidas,0~v_vendedores,0,0,0,0~v_vehiculos,0,0,0,0~v_usuarios,0,0,0,0,0~')";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: " . mysqli_error($omodelo->link);
		} else {
			$id = mysqli_insert_id($omodelo->link);
			$status = 1;
			if ($_FILES['foto']['size'] > 0 && $_FILES['foto']['error'] == 0) {
				$file = $_FILES["foto"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/img/usuarios/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != '') {
					echo "Error 2 Formato";
				} else if ($size > (1024 * 1024 * 10)) {
					echo "Error 3 Peso";
				} else {
					$status = 0;
					$ruta = $carpeta;
				}
			}

			if ($status == 0) {
				$query2 = "UPDATE usuarios SET Foto = '" . $id . '_' . $nombreDoc . "' WHERE ID_Usuario = '$id'";
				$error3 = $omodelo->_insertar($query2);

				if ($error3 == "si") {
					echo "Error 4: " . mysqli_error($omodelo->link);
				} else {
					move_uploaded_file($ruta_provisional,  $ruta . '' . $id . '_' . $nombreDoc);
				}
			}
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			echo "Correcto";
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');

		$opciones = ['cost' => 12];
		$password = password_hash($omodelo->link->real_escape_string($contrasena), PASSWORD_BCRYPT, $opciones);

		$IDUsuario = $omodelo->link->real_escape_string($IDUsuario);
		$firstname = $omodelo->link->real_escape_string(trim($firstname));
		$email = $omodelo->link->real_escape_string(trim($email));
		$tipo_usuario = $omodelo->link->real_escape_string($Tipo_usuario);
		$estado = $omodelo->link->real_escape_string($estatus);
		$moCo = $omodelo->link->real_escape_string($modCo);
		if ($moCo == 0) {
			$query = "UPDATE usuarios SET Nombre = '$firstname', Usuario = '$email', Tipo = '$tipo_usuario', Estatus = '$estado' WHERE ID_Usuario = '$IDUsuario'";
			$error = $omodelo->_insertar($query);
		} else {
			$query = "UPDATE usuarios SET Nombre = '$firstname', Contrasena = '$password', Usuario = '$email', Tipo = '$tipo_usuario', Estatus = '$estado' WHERE ID_Usuario = '$IDUsuario'";
			$error = $omodelo->_insertar($query);
		}

		if ($error == "si") {
			echo "Error 1: " . mysqli_error($omodelo->link);
		} else {
			$status = 1;
			if ($_FILES['foto']['size'] > 0 && $_FILES['foto']['error'] == 0) {
				$file = $_FILES["foto"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/img/usuarios/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != '') {
					echo "Error 2 Formato";
				} else if ($size > (1024 * 1024 * 10)) {
					echo "Error 3 Peso";
				} else {
					$status = 0;
					$ruta = $carpeta;
				}
			}

			if ($status == 0) {
				$query1 = "SELECT Foto FROM usuarios WHERE ID_Usuario = '$IDUsuario'";
				$row1 = $omodelo->_consultar($query1);
				$numerofilas = $omodelo->numerofilas;

				if ($row1 == "si") {
					echo "Error consultar archivo: " . mysqli_error($omodelo->link);
				} else {
					if ($numerofilas > 0) {
						if ($row1[0]['Foto'] != "" && file_exists('vistas/assets/img/usuarios/' . $row1[0]['Foto'])) {
							unlink('vistas/assets/img/usuarios/' . $row1[0]['Foto']);
						}
					}
				}
				$query2 = "UPDATE usuarios SET Foto = '" . $IDUsuario . '_' . $nombreDoc . "' WHERE ID_Usuario = '$IDUsuario'";
				$error3 = $omodelo->_insertar($query2);

				if ($error3 == "si") {
					echo "Error 4: " . mysqli_error($omodelo->link);
				} else {
					move_uploaded_file($ruta_provisional,  $ruta . '' . $IDUsuario . '_' . $nombreDoc);
				}
			}
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			echo "Correcto";
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "ConsultarDatosUsuario") {
			$IDUsuario =  $omodelo->link->real_escape_string($IDUsuario);
			$query = "SELECT ID_Usuario, Nombre, Usuario, Tipo, Estatus, Foto FROM usuarios WHERE ID_Usuario = '$IDUsuario'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == 'si') {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					echo json_encode($row[0]);
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
			$query = "SELECT Permisos, Tipo FROM usuarios WHERE ID_Usuario='".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			
			if ($row == "si"){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo = array('Cadena'=> $row[0]['Permisos'], 'Tipo'=> $row[0]['Tipo']); 
				}
			}

			echo json_encode($arreglo);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$ID = $omodelo->link->real_escape_string($IDUsuario);

		$query = "SELECT Foto FROM usuarios WHERE ID_Usuario = '$ID'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		if ($row == "si") {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			if ($numerofilas > 0) {
				if ($row[0]["Foto"] != "" && file_exists("vistas/assets/img/usuarios/" . $row[0]["Foto"] . "")) {
					unlink("vistas/assets/img/usuarios/" . $row[0]["Foto"] . "");
				}
				$query = "DELETE FROM usuarios WHERE ID_Usuario = '$ID'";
				$error = $omodelo->_insertar($query);
				if ($error == "si") {
					echo "Error 1: " . mysqli_error($omodelo->link);
				} else {
					$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
					echo "Correcto";
				}
			}
		}
	}
}
