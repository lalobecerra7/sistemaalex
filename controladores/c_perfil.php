<?php
class perfil {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$CorreoActual = $omodelo->link->real_escape_string($CorreoActual);
		$query = "UPDATE usuarios SET Correo = '$CorreoActual' WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";

			$status = 1;
			if ($_FILES['FotoPerfil']['size'] > 0 && $_FILES['FotoPerfil']['error'] == 0) {
				$file = $_FILES["FotoPerfil"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/fotosUsuarios/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status = 0;
					$ruta = $carpeta;
				}
			}
			
			if($status == 0){
				$query2 = "UPDATE usuarios SET Foto = '".$_SESSION['user_admin']['ID_Usuario'].'_'.$nombreDoc."' WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
				$error3 = $omodelo->_insertar($query2);	

				if ($error3 == "si") {
					echo "Error 4: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisional,  $ruta.''.$_SESSION['user_admin']['ID_Usuario'].'_'.$nombreDoc);
				}
			}
			$_SESSION['user_admin']['Foto'] = $_SESSION['user_admin']['ID_Usuario'].'_'.$nombreDoc;
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		echo $_SESSION['user_admin']['Foto'];
	}

}
?>
