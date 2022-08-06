<?php
class login {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$usuario = $omodelo->link->real_escape_string($usuario);
		$contrasena = $omodelo->link->real_escape_string($contrasena);

		$query4 = "SELECT Ultimo_Intento, Intentos FROM usuarios_administrador WHERE Correo = '$usuario' AND Estatus='Desbloqueado'";
		$row1 = $omodelo->_consultar($query4);
		$numerofilas = $omodelo->numerofilas;
		
		if ($row1 == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				if($row1[0]['Intentos'] == '5'){
					$nuevafecha = strtotime('+15 minute', strtotime($row1[0]['Ultimo_Intento']));
					if(strtotime($fecha) >= $nuevafecha){
						$query5 = "UPDATE usuarios_administrador SET Intentos = 0 WHERE Correo = '$usuario'";
						$resultado = $omodelo->_insertar($query5);

						if ($resultado == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							$row1[0]['Intentos'] = '0';
						}
					}else{
						echo "Supero Intentos";
					}
				}
				if($row1[0]['Intentos'] < 5){
					$query1 = "UPDATE usuarios_administrador SET Ultimo_Intento = '$fecha', Intentos = Intentos+1 WHERE Correo = '$usuario'";
					$resultado = $omodelo->_insertar($query1);
					$afectadas = $omodelo->numerofilas;

					if ($resultado == "si") {
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if($afectadas > 0){
							$query = "SELECT ID_Usuario, Nombre, Correo, Foto, Tipo, Permisos FROM usuarios_administrador WHERE Correo = '$usuario' AND Contrasena = MD5('$contrasena') AND Contrasena != '' AND usuarios_administrador.Tipo = 'Administrador'"; 
							$row = $omodelo->_consultar($query);
							$numerofilas = $omodelo->numerofilas;

							if ($row == "si") {
								echo "Error 4: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas > 0){
									$query2 = "UPDATE usuarios_administrador SET Tiempo_Inicio = '$fecha', Intentos = 0 WHERE Correo = '$usuario'";
									$resultado = $omodelo->_insertar($query2);
						
									if ($resultado == "si") {
									    echo "Error 5: ".mysqli_error($omodelo->link);
									}else{
										$_SESSION['user_admin'] = $row[0];
										echo "Correcto";
									}
								}else{
									$query3 = "SELECT Intentos FROM usuarios_administrador WHERE Correo = '$usuario' AND Intentos = 5";
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
							}
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

		$query = "SELECT ID_Usuario, Foto FROM usuarios_administrador WHERE Correo = '$usuario'";
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

		$query = "UPDATE usuarios_administrador SET Tiempo_Final='$final' WHERE ID_usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
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

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$correo = $omodelo->link->real_escape_string($email);

		$query1 = "SELECT * FROM usuarios_administrador WHERE Correo = '$email'";
		$row = $omodelo->_consultar($query1);
		$numerofilas = $omodelo->numerofilas;
		if ($numerofilas == 0) {
			echo "Error 1 Correo";
		}else{
			//Generar contraseña temporal
			$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
			$newcontra = "";
			for($i=0;$i<8;$i++) {
				$newcontra .= substr($cadena,rand(0,62),1);
			}
			
			$query = "UPDATE usuarios_administrador SET Temporal = '1', Contrasena = MD5('$newcontra') WHERE Correo = '$email'";
			$resultado = $omodelo->_insertar($query);
			
			if ($resultado == "si") {
				echo "Error 2: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($email.' Temporal', "");
			}
		}

	}
}
?>
