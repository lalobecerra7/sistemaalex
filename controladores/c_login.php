<?php
class login {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$opciones = ['cost' => 12];

		$usuario = $omodelo->link->real_escape_string($usuario);
		$contrasena = $omodelo->link->real_escape_string($contrasena);

		$query4 = "SELECT ID_Usuario, Nombre, Primer_Apellido, Segundo_Apellido, Correo, Contrasena, Tipo_Usuario, Permisos, BD, Estatus, Intentos, Ultimo_Intento, Tiempo_Inicio, Tiempo_Final, Foto, Temporal, Activo, Tipo_Login, Conectado, Fecha_Alta FROM usuarios WHERE Correo = '$usuario' AND Estatus='0'";
		$row = $omodelo->_consultar($query4);
		$numerofilas = $omodelo->numerofilas;
		
		if ($row == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				if($row[0]['Intentos'] == '5'){
					$nuevafecha = strtotime('+15 minute', strtotime($row[0]['Ultimo_Intento']));
					if(strtotime($fecha) >= $nuevafecha){
						$query5 = "UPDATE usuarios SET Intentos = 0 WHERE Correo = '$usuario'";
						$resultado = $omodelo->_insertar($query5);

						if ($resultado == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							$row[0]['Intentos'] = '0';
						}
					}else{
						echo "Supero Intentos";
					}
				}
				if($row[0]['Intentos'] < 5){
					$query1 = "UPDATE usuarios SET Ultimo_Intento = '$fecha', Intentos = Intentos+1 WHERE Correo = '$usuario'";
					$resultado = $omodelo->_insertar($query1);
					$afectadas = $omodelo->numerofilas;

					if ($resultado == "si") {
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if($afectadas > 0){

							if($numerofilas > 0 && password_verify($contrasena, $row[0]['Contrasena'])){
								$query2 = "UPDATE usuarios SET Tiempo_Inicio = '$fecha', Intentos = 0 WHERE Correo = '$usuario'";
								$resultado = $omodelo->_insertar($query2);
						
								if ($resultado == "si") {
								    echo "Error 5: ".mysqli_error($omodelo->link);
								}else{
									$_SESSION['user_admin'] = $row[0];
									echo "Correcto";
								}
							}else{
								$query3 = "SELECT Intentos FROM usuarios WHERE Correo = '$usuario' AND Intentos = 5";
								$resultado = $omodelo->_consultar($query3);
								$numerofilas = $omodelo->numerofilas;

								if ($resultado == "si") {
								    echo "Error 6: ".mysqli_error($omodelo->link);
								}else{
									echo "0";
									if($numerofilas > 0){
										echo "Superaste el numero de intentos";
									}
								}
							}


							/*$query = "SELECT ID_Usuario, Nombre, Correo, Foto, Tipo, Permisos FROM usuarios WHERE Correo = '$usuario' AND Contrasena = MD5('$contrasena') AND Contrasena != '' AND usuarios.Tipo = 'Administrador'"; 
							$row = $omodelo->_consultar($query);
							$numerofilas = $omodelo->numerofilas;

							if ($row == "si") {
								echo "Error 4: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas > 0){
									
								}else{
									$query3 = "SELECT Intentos FROM usuarios WHERE Correo = '$usuario' AND Intentos = 5";
									$resultado = $omodelo->_consultar($query3);
									$numerofilas = $omodelo->numerofilas;

									if ($resultado == "si") {
									    echo "Error 6: ".mysqli_error($omodelo->link);
									}else{
										echo "0";
										if($numerofilas > 0){
											echo "Superaste el numero de intentos";
										}
									}
								}
							}*/
						}	
					}
				}
			}else{
				echo "0";
			}

			$omodelo->movimiento("LOGIN ADMIN $usuario", "");
		}	
	}
	
	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$usuario = $omodelo->link->real_escape_string($usuario);

		$query = "SELECT ID_Usuario, Foto FROM usuarios WHERE Correo = '$usuario'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		
		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0 && $row[0]['Foto'] != ""){
				echo $row[0]['ID_Usuario'].'_'.$row[0]['Foto'];
			}else{
				echo "0";
			}
		}	
	}

	public function _eliminar(){
		$omodelo = new m_modelo();	
		$final = date("Y-m-d H:i:s");
		//session_start();

		$query = "UPDATE usuarios SET Tiempo_Final='$final' WHERE ID_usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
		$resultado = $omodelo->_insertar($query);
		if ($resultado == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}
		/*$_SESSION = array();
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        @$params["secure"], $params["httponly"]
		    );
		}
		session_destroy();*/
		unset($_SESSION["user_admin"]);
	}

}
?>
