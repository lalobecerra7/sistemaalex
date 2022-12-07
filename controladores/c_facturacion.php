<?php
class facturacion {
	public function _consultar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id = trim($omodelo->link->real_escape_string($id));

		$query = "SELECT ";

	}

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$rfcFacturacion = trim($omodelo->link->real_escape_string($rfcFacturacion));
		$nombreFacturacion = trim($omodelo->link->real_escape_string($nombreFacturacion));
		$regimenFacturacion = $omodelo->link->real_escape_string($regimenFacturacion);
		$contraFacturacion = $omodelo->link->real_escape_string($contraFacturacion);

		$contra = '';
		if(trim($contraFacturacion) != ''){
			$contra = ", Contrasena = '$contraFacturacion'";
		}

		$query = "UPDATE general SET RFC = '$rfcFacturacion', Nombre = '$nombreFacturacion', Regimen = '$regimenFacturacion' $contra WHERE ID_General = '1'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$id = mysqli_insert_id($omodelo->link);

			$status1 = 1;$status2 = 1;
			$carpeta = "vistas/assets/archivos/certificados/";
			if ($_FILES['certificadoFacturacion']['size'] > 0 && $_FILES['certificadoFacturacion']['error'] == 0) {
				$file = $_FILES["certificadoFacturacion"];
				$nombreCer = $file["name"];
				$tipo = $file["type"];
				$ruta_provisionalCer = $file["tmp_name"];
				$size = $file["size"];

				if ($tipo != 'application/x-x509-ca-cert' && $tipo != ''){
					echo "Error 2 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 3 Peso";
				}else{
					$status1 = 0;
					$ruta = $carpeta;
				}
			}

			if ($_FILES['keyFacturacion']['size'] > 0 && $_FILES['keyFacturacion']['error'] == 0) {
				$file = $_FILES["keyFacturacion"];
				$nombreKey = $file["name"];
				$tipo = $file["type"];
				$ruta_provisionalKey = $file["tmp_name"];
				$size = $file["size"];

				if ($tipo != 'application/octet-stream' && $tipo != ''){
					echo "Error 4 Formato";
				}else if ($size > (1024*1024*10)){
					echo "Error 5 Peso";
				}else{
					$status2 = 0;
					$ruta = $carpeta;
				}
			}
			
			if($status1 == 0 && $status2 == 0){
				$query1 = "UPDATE general SET Certificado = '$nombreCer', Key_Cer = '$nombreKey' WHERE ID_General = '1'";
				$error1 = $omodelo->_insertar($query1);	

				if ($error1 == "si") {
					echo "Error 6: ".mysqli_error($omodelo->link); 
				}else{
					move_uploaded_file($ruta_provisionalCer,  $ruta.$nombreCer);
					move_uploaded_file($ruta_provisionalKey,  $ruta.$nombreKey);
				}
			}

			echo "Correcto";
		}
	}
}
?>