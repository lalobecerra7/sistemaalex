<?php
class perfil {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$nombre = $omodelo->link->real_escape_string($nombre);
		$apellidoP = $omodelo->link->real_escape_string($apellidoP);
		$apellidoS = $omodelo->link->real_escape_string($apellidoS);
		$telefono = $omodelo->link->real_escape_string($telefono);
		$sexo = $omodelo->link->real_escape_string($sexo);
		$nacionalidad = $omodelo->link->real_escape_string($nacionalidad);
		$nombreDNI = $omodelo->link->real_escape_string($nombreDNI);
		$dni = $omodelo->link->real_escape_string($dni);
		$fechaN = $omodelo->link->real_escape_string($fechaN);
		$pais = $omodelo->link->real_escape_string($pais);
		$estado = $omodelo->link->real_escape_string($estado);
		$ciudad = $omodelo->link->real_escape_string($ciudad);
		$colonia = $omodelo->link->real_escape_string($colonia);
		$direccion = $omodelo->link->real_escape_string($direccion);
		$cp = $omodelo->link->real_escape_string($cp);
		$contrasena = $omodelo->link->real_escape_string($contrasena);

		$where = "AND Contrasena=MD5('$contrasena')";

		$query = "SELECT ID_Usuario FROM usuarios_administrador WHERE ID_Usuario='".$_SESSION['user_admin']['ID_Usuario']."' $where";
		$row = $omodelo->_insertar($query);
		$numerofilas = $omodelo->numerofilas; 

		if ($row == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$query1 = "UPDATE clientes SET Nombre='$nombre', Primer_Apellido='$apellidoP', Segundo_Apellido='$apellidoS', Telefono='$telefono', Nombre_DNI='$nombreDNI', DNI='$dni',Fecha_Nacimiento='$fechaN', Sexo='$sexo', Nacionalidad='$nacionalidad', Pais='$pais', Estado='$estado', Ciudad='$ciudad', Colonia='$colonia', Direccion='$direccion', CP='$cp' WHERE FK_Usuario='".$_SESSION['user_admin']['ID_Usuario']."' AND Tipo = 'Administrador'";
				$error = $omodelo->_insertar($query1);
				$numerofilas = $omodelo->numerofilas; 

				if ($error == "si") {
					echo "Error 2: ".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
					if($numerofilas > 0){
						$_SESSION['user_admin']['Nombre'] = $nombre;
						$_SESSION['user_admin']['Primer_Apellido'] = $apellidoP;
						$_SESSION['user_admin']['Segundo_Apellido'] = $apellidoS;
						$_SESSION['user_admin']['Telefono'] = $telefono;
						$_SESSION['user_admin']['Nombre_DNI'] = $nombreDNI;
						$_SESSION['user_admin']['DNI'] = $dni;
						$_SESSION['user_admin']['Sexo'] = $sexo;
						$_SESSION['user_admin']['Nacionalidad'] = $nacionalidad;
						$_SESSION['user_admin']['Fecha_Nacimiento'] = $fechaN;
						$_SESSION['user_admin']['Pais'] = $pais;
						$_SESSION['user_admin']['Estado'] = $estado;
						$_SESSION['user_admin']['Ciudad'] = $ciudad;
						$_SESSION['user_admin']['Colonia'] = $colonia;
						$_SESSION['user_admin']['Direccion'] = $direccion;
						$_SESSION['user_admin']['CP'] = $cp;

						//$omodelo->movimiento($query1, $_SESSION['user_admin']['ID_Cliente']);
					}
				}
			}else{
				echo "Error Contrasena".$query;
			}
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		$fecha = date('Y-m-d H:i:s');
		$tipo = $omodelo->link->real_escape_string($_POST['tipo']);

		if($tipo == '1'){
			$nombre="";$ruta="";$ruta_provisional="";$status=0;

			if (isset($_FILES["profile_avatar"])) {
				$file = $_FILES["profile_avatar"];
			    $nombre = $omodelo->link->real_escape_string($file["name"]);
			    $tipo = $file["type"];
			    $ruta_provisional = $file["tmp_name"];
			    $size = $file["size"];
			    $carpeta = "vistas/assets/media/users/";
			    
			    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/svg'){
			      	echo "Error1"; 
			      	$status=1;
			    }else if ($size > (1024*1024*10)){
		      		echo "Error2";
		      		$status=1;
		    	}else{
		    		$ruta = $carpeta.$_SESSION['user_admin']['ID_Usuario']."_".$nombre;
		    	}

			    if(file_exists($carpeta.$_SESSION['user_admin']['ID_Usuario'].'_'.$_POST['imagenElimina']) && $_POST['imagenElimina'] != "" && $file["name"] != "" && $status == 0){
			       	unlink($carpeta.$_SESSION['user_admin']['ID_Usuario'].'_'.$_POST['imagenElimina']);
			    }
			}

			if($status == 0){
				$query = "UPDATE usuarios_administrador SET Foto = '$nombre' WHERE ID_Usuario='".$_SESSION['user_admin']['ID_Usuario']."'";
				$error = $omodelo->_insertar($query);
				if ($error == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
					move_uploaded_file($ruta_provisional,  $ruta);
					$_SESSION['user_admin']['Foto'] = $nombre;
					$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
				}
			}
		}else if($tipo == '2'){
			extract($_POST);
			$contraA = $omodelo->link->real_escape_string($contraA);
			$contraN = $omodelo->link->real_escape_string($contraN);

			$query = "SELECT ID_Usuario FROM usuarios_administrador WHERE ID_Usuario='".$_SESSION['user_admin']['ID_Usuario']."' AND Contrasena=MD5('$contraN')";
			$row = $omodelo->_insertar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo "Error 3 Igual";
				}else{

					$query = "UPDATE usuarios_administrador SET Contrasena = MD5('$contraN') WHERE ID_Usuario='".$_SESSION['user_admin']['ID_Usuario']."' AND Contrasena = MD5('$contraA')";
					$error = $omodelo->_insertar($query);
					$numerofilas = $omodelo->numerofilas;

					
					if ($error == "si") {
						echo "Error 1: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							
								echo "Correcto 2";
							
							$_SESSION['user_admin']['Tipo_Login'] = 1;

							@$omodelo->_email($_SESSION['user_admin']['Correo'],utf8_decode("Cambio de contraseña en JIMA"),"<p style='text-align: center;'><img src='https://jima.mx/inicio/vistas/assets/media/logos/JIMA-01.png' alt='JIMA' width='35%'></p><br><br><h3'>Haz cambiado de contraseña.</h3><br><p>Tu contraseña en JIMA ha sido cambiada, para cualquier duda o aclaración comunícate con nosotros a través del correo <b>jima.contacto@gmail.com</b>.</p>");

							$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
						}else{
							echo "Error 2 Password";	
						}
					}
				}
			}
		}else if($tipo == '3'){
			extract($_POST);
			$Nip= $omodelo->link->real_escape_string($id);
			$Contra = $omodelo->link->real_escape_string($contra);

			
				$where = "";
				if($_SESSION['user_admin']['Tipo_Login'] == '1'){
					$where = "AND Contrasena=MD5('$Contra')";
				}

				$query = "SELECT ID_Usuario FROM usuarios_administrador WHERE ID_Usuario='".$_SESSION['user_admin']['ID_Usuario']."' $where";
				$row = $omodelo->_insertar($query);
				$numerofilas = $omodelo->numerofilas; 

				if ($row == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						$query1 = "UPDATE clientes SET NIP = '$Nip' WHERE ID_Cliente = '".$_SESSION['user_admin']['ID_Cliente']."'";
						$error = $omodelo->_insertar($query1);
						$numerofilas = $omodelo->numerofilas;  

						if ($error == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							echo "Correcto";
							$_SESSION['user_admin']['NIP'] = $Nip;

							@$omodelo->_email($_SESSION['user_admin']['Correo'],utf8_decode("Cambio de NIP en JIMA"),"<p style='text-align: center;'><img src='https://jima.mx/inicio/vistas/assets/media/logos/JIMA-01.png' alt='JIMA' width='35%'></p><br><br><h3'>Haz cambiado tu NIP de tramites.</h3><br><p>Tu NIP de tramites en JIMA ha sido cambiado, para cualquier duda o aclaración comunícate con nosotros a través del correo <b>jima.contacto@gmail.com</b>.</p>");

							$omodelo->movimiento($query1, $_SESSION['user_admin']['ID_Cliente']);
						}
					}else{
						echo "Error 2 Password";
					}
				}		
			
		}else if($tipo == 'ModificarCorreo'){
			extract($_POST);
			$Correo = $omodelo->link->real_escape_string($CorreoNuevo);
			$ContraJIMA = $omodelo->link->real_escape_string($Contra);
			$query = "SELECT Contrasena FROM usuarios_administrador WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' AND Contrasena = MD5('$ContraJIMA')";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($numerofilas > 0) {
				$query2 = ("UPDATE usuarios_administrador SET Correo = '$Correo' WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'");
				$error = $omodelo->_insertar($query2);
				if ($error == "si") {
					echo "Error al actualizar correo".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
				}
			}
		}
	}

	//INSERTA LAS CUENTAS DE RETIRO
	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipodetalle == "insertar") {
			$Tipo_Retiro = $omodelo->link->real_escape_string($tipo);
			$NombreTitular = $omodelo->link->real_escape_string($b_nombre);
			$NombreBanco = $omodelo->link->real_escape_string($b_banco);
			$Clabebanco = $omodelo->link->real_escape_string($b_clabe);
			$Correopaypal = $omodelo->link->real_escape_string($p_correo);
			$Celularpaypal = $omodelo->link->real_escape_string($p_celular);
			$CorreoMercado = $omodelo->link->real_escape_string($mp_correo);
			$Nombremercado = $omodelo->link->real_escape_string($mp_nombre);
			if ($Tipo_Retiro == "CuentaBanco") {
				$query = "INSERT INTO cuentas_retiro SET FK_Cliente = '".$_SESSION['user_admin']['ID_Cliente']."', Tipo_Retiro = 'CuentaBanco', Nombre_Titular = '$NombreTitular', Nombre_Banco = '$NombreBanco', CLABE = '$Clabebanco'";
			}else if ($Tipo_Retiro == "Paypal") {
				$query = "INSERT INTO cuentas_retiro SET FK_Cliente = '".$_SESSION['user_admin']['ID_Cliente']."', Tipo_Retiro = 'Paypal', Correo_Paypal = '$Correopaypal', Celular_Paypal = '$Celularpaypal'";
			}else if ($Tipo_Retiro == "MercadoPago") {
				$query = "INSERT INTO cuentas_retiro SET FK_Cliente = '".$_SESSION['user_admin']['ID_Cliente']."', Tipo_Retiro = 'MercadoPago', Correo_MP = '$CorreoMercado', Nombre_MP = '$Nombremercado'";
			}
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
			}
		}else if($tipodetalle == "eliminar"){
			$ID = $omodelo->link->real_escape_string($id);
			$Contra = $omodelo->link->real_escape_string($pssw);
			if ($_SESSION['user_admin']['Tipo_Login'] == "1") {
				$query1 = "SELECT Contrasena FROM usuarios_administrador WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."' AND Contrasena = MD5('$Contra')";
				$row = $omodelo->_consultar($query1);
				$numerofilas = $omodelo->numerofilas;
				if ($numerofilas > 0) {
					$query = "DELETE FROM cuentas_retiro WHERE ID_Retiro = '".$ID."'";
					$error = $omodelo->_insertar($query);
					if ($error == "si") {
						echo "Error 1".mysqli_error($omodelo->link);
					}else{
						echo "Correcto";
						$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
					}
				}else{
					echo "¡Error! Contraseña incorrecta";
				}	
			}else{
				$query = "DELETE FROM cuentas_retiro WHERE ID_Retiro = '".$ID."'";
				$error = $omodelo->_insertar($query);
				if ($error == "si") {
					echo "Error 1".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
					$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
				}
			}
			
		}else if($tipodetalle == "editarModal"){
			$ID = $omodelo->link->real_escape_string($id);
			$query1 = "SELECT * FROM cuentas_retiro WHERE ID_Retiro = '$ID'";
			$row = $omodelo->_consultar($query1);
			$numerofilas = $omodelo->numerofilas;
			$campos = '';
			if ($numerofilas > 0) {
				echo json_encode($row[0]); 
			}
		}else if($tipodetalle == "modificar"){
			$Tipo_Retiro = $omodelo->link->real_escape_string($tipo);
			$NombreTitular = $omodelo->link->real_escape_string($b_nombre);
			$NombreBanco = $omodelo->link->real_escape_string($b_banco);
			$Clabebanco = $omodelo->link->real_escape_string($b_clabe);
			$Correopaypal = $omodelo->link->real_escape_string($p_correo);
			$Celularpaypal = $omodelo->link->real_escape_string($p_celular);
			$CorreoMercado = $omodelo->link->real_escape_string($mp_correo);
			$Nombremercado = $omodelo->link->real_escape_string($mp_nombre);
			$IDRetiro = $omodelo->link->real_escape_string($idretiro);
			if ($Tipo_Retiro == "CuentaBanco") {
				$query = "UPDATE cuentas_retiro SET Nombre_Titular = '$NombreTitular', Nombre_Banco = '$NombreBanco', CLABE = '$Clabebanco' WHERE ID_Retiro = '".$IDRetiro."'";
			}else if ($Tipo_Retiro == "Paypal") {
				$query = "UPDATE cuentas_retiro SET Correo_Paypal = '$Correopaypal', Celular_Paypal = '$Celularpaypal' WHERE ID_Retiro = '".$IDRetiro."'";
			}else if ($Tipo_Retiro == "MercadoPago") {
				$query = "UPDATE cuentas_retiro SET Correo_MP = '$CorreoMercado', Nombre_MP = '$Nombremercado' WHERE ID_Retiro = '".$IDRetiro."'";
			}
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
			}
		}
	}

}
?>
