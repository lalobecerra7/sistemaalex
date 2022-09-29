
<?php
class general {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$query = "SELECT * FROM general WHERE ID_General = '1'";
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

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$Calle =  $omodelo->link->real_escape_string($CalleGeneral);
		$No_Exterior =  $omodelo->link->real_escape_string($NoExtGeneral);
		$No_Interior =  $omodelo->link->real_escape_string($NoIntGeneral);
		$Colonia =  $omodelo->link->real_escape_string($ColoniaGeneral);
		$CP =  $omodelo->link->real_escape_string($CPGeneral);
		$Ciudad =  $omodelo->link->real_escape_string($CiudadGeneral);
		$Estado =  $omodelo->link->real_escape_string($EstadoGeneral);
		$Pais =  $omodelo->link->real_escape_string($PaisGeneral);
		$Telefono =  $omodelo->link->real_escape_string($TelefonoGeneral);
		$Email =  $omodelo->link->real_escape_string($EmailGeneral);
		$Ticket =  $omodelo->link->real_escape_string($PonerTodosGeneral);
		$Moneda =  $omodelo->link->real_escape_string($MonedaGeneral);
		$Simbolo =  $omodelo->link->real_escape_string($SimboloGeneral);
		$Origen =  $omodelo->link->real_escape_string($OrigenGeneral);
		$Imagen =  $omodelo->link->real_escape_string($PonerImgGeneral);

		$query = "UPDATE general SET Calle = '$Calle', No_Exterior = '$No_Exterior',  No_Interior= '$No_Interior', Colonia = '$Colonia', CP = '$CP', Ciudad = '$Ciudad', Estado = '$Estado', Pais = '$Pais', Telefono= '$Telefono', Email= '$Email', Imagen = '$Imagen', Ticket = '$Ticket', Moneda = '$Moneda', Simbolo = '$Simbolo', Origen ='$Origen' WHERE ID_General = '1'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "ErrorModificar: " . mysqli_error($omodelo->link);
		} else {

			$status = 1;
			if ($_FILES['ImagenGeneral']['size'] > 0 && $_FILES['ImagenGeneral']['error'] == 0) {
				$file = $_FILES["ImagenGeneral"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				$carpeta = "vistas/assets/archivos/imagenTicket/General/";

				if ($tipo != 'image/jpeg' && $tipo != 'image/JPEG' && $tipo != 'image/jpg' && $tipo != 'image/JPG' && $tipo != 'image/png' && $tipo != 'image/PNG' && $tipo != 'application/pdf' && $tipo != 'application/PDF' && $tipo != '') {
					echo "Error 2 Formato";
				} else if ($size > (1024 * 1024 * 10)) {
					echo "Error 3 Peso";
				} else {
					$status = 0;
					$ruta = $carpeta;
				}
			}
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

			if ($status == 0) {

				$query = "SELECT Imagen_Ticket FROM general WHERE ID_General = '1'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
				$nombreFoto = "";
				if ($row == 'si') {
					echo "Error: " . mysqli_error($omodelo->link);
				} else {
					if ($numerofilas > 0) {
						if ($row[0]["Imagen_Ticket"] != "" && file_exists("vistas/assets/archivos/imagenTicket/" . $row[0]["Imagen_Ticket"])) {
							unlink("vistas/assets/archivos/imagenTicket/General/" . $row[0]["Imagen_Ticket"]);
						}
					}
				}

				$query2 = "UPDATE general SET Imagen_Ticket = '1_" . $nombreDoc . "' WHERE ID_General = '1'";
				$error3 = $omodelo->_insertar($query2);

				if ($error3 == "si") {
					echo "Error 4: " . mysqli_error($omodelo->link);
				} else {
					move_uploaded_file($ruta_provisional,  $ruta . '1_' . $nombreDoc);
				}
			}

			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

}
?>
