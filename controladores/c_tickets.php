
<?php
class tickets {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$query = "SELECT ID_General, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Email, Imagen_Ticket FROM general";
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
		$Nombre =  $omodelo->link->real_escape_string($Nombre);
		$IDSucursal =  $omodelo->link->real_escape_string($IDSucursal);
		$Domicilio =  $omodelo->link->real_escape_string($Domicilio);
		$Telefono =  $omodelo->link->real_escape_string($Telefono);
		$Email =  $omodelo->link->real_escape_string($Email);
		$TotalLetras =  $omodelo->link->real_escape_string($TotalLetras);
		$IncluirMensaje =  $omodelo->link->real_escape_string($IncluirMensaje);
		$Mensaje =  $omodelo->link->real_escape_string($Mensaje);
		$ticket = '';

		$query = "SELECT * FROM tickets WHERE FK_Sucursal = '$IDSucursal'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$ticket = $row[0]['ID_Ticket'];
				$query2 = "UPDATE tickets SET Nombre = '$Nombre', Domicilio = '$Domicilio', Telefono = '$Telefono', Email = '$Email', Total_Letras = '$TotalLetras', Incluir_Mensaje = '$IncluirMensaje', Mensaje = '$Mensaje' WHERE ID_Ticket = '$ticket'";
				$row2 = $omodelo->_insertar($query2);

				if ($row2 == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
					//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
				}
			}else {
				$query3 = "INSERT INTO tickets SET FK_Sucursal = $IDSucursal, Nombre = '$Nombre', Domicilio = '$Domicilio', Telefono = '$Telefono', Email = '$Email', Total_Letras = '$TotalLetras', Incluir_Mensaje = '$IncluirMensaje', Mensaje = '$Mensaje'";
				$row3 = $omodelo->_insertar($query3);

				if ($row3 == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					echo "Correcto";
					//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
				}
			}
		}

		
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$IDCategoria =  $omodelo->link->real_escape_string($IDCategoria);
		$Nombre =  $omodelo->link->real_escape_string($Nombre);
		$Descripcion =  $omodelo->link->real_escape_string($Descripcion);

		$query = "UPDATE categorias SET Nombre = '$Nombre', Descripcion = '$Descripcion' WHERE ID_categoria = '$IDCategoria'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$IDCategoria =  $omodelo->link->real_escape_string($IDCategoria);

		$query = "DELETE FROM categorias WHERE ID_Categoria='$IDCategoria'";
		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		if($tipo=='Sucursal'){
			$IDSucursal =  $omodelo->link->real_escape_string($IDSucursal);

			$query = "SELECT * FROM sucursales WHERE ID_Sucursal = '$IDSucursal'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}
		}else if($tipo=='ticketSucursal'){
			$IDSucursal =  $omodelo->link->real_escape_string($IDSucursal);

			$query = "SELECT * FROM tickets WHERE FK_Sucursal = '$IDSucursal'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}
		}else if($tipo=='CambiarImagen'){
			$IDTicket =  $omodelo->link->real_escape_string($idTicket);
			$tabla = $omodelo->link->real_escape_string($tabla);
			
			$status = 1;
			if ($_FILES['imagenTicket']['size'] > 0 && $_FILES['imagenTicket']['error'] == 0) {
				$file = $_FILES["imagenTicket"];
				$nombreDoc = $file["name"];
				$tipo = $file["type"];
				$ruta_provisional = $file["tmp_name"];
				$size = $file["size"];
				if($tabla == 'general'){
					$carpeta = "vistas/assets/archivos/imagenTicket/General/";
				}else if($tabla == 'tickets'){
					$carpeta = "vistas/assets/archivos/imagenTicket/Sucursales/";
				}
				

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
				if($tabla == 'general'){
					$query1 = "SELECT Imagen_Ticket FROM general WHERE ID_General = '1'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas = $omodelo->numerofilas;

					if ($row1 == "si") {
						echo "Error consultar archivo: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							if($row1[0]['Imagen_Ticket'] != "" && file_exists('vistas/assets/archivos/imagenTicket/General/'.$row1[0]['Imagen_Ticket'])){
									unlink('vistas/assets/archivos/imagenTicket/General/'.$row1[0]['Imagen_Ticket']);
							}
						}
					}
					$query2 = "UPDATE general SET Imagen_Ticket = '1_".$nombreDoc."' WHERE ID_General = '1'";
					$error3 = $omodelo->_insertar($query2);	

					if ($error3 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.'1_'.$nombreDoc);
						echo "Correcto";
						//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
					}
				}else if($tabla == 'tickets'){
					$query1 = "SELECT Ruta_Imagen FROM tickets WHERE ID_Ticket = '$IDTicket'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas = $omodelo->numerofilas;

					if ($row1 == "si") {
						echo "Error consultar archivo: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							if($row1[0]['Ruta_Imagen'] != "" && file_exists('vistas/assets/archivos/imagenTicket/Sucursales/'.$row1[0]['Ruta_Imagen'])){
									unlink('vistas/assets/archivos/imagenTicket/Sucursales/'.$row1[0]['Ruta_Imagen']);
							}
						}
					}
					$query2 = "UPDATE tickets SET Ruta_Imagen = '".$IDTicket."_".$nombreDoc."' WHERE ID_Ticket = '$IDTicket'";
					$error3 = $omodelo->_insertar($query2);	

					if ($error3 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.''.$IDTicket.'_'.$nombreDoc);
						echo "Correcto";
						//$omodelo->movimiento($query, $_SESSION['user_smart']['usuario']['id_usuario']);
					}
				}
				
			}
		}else if($tipo=='impuestos'){
			$query = "SELECT Nombre, Porcentaje FROM impuestos";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$impuesto = '';

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$impuesto ='<label for="impuestos">'.$row[0]['Nombre'].'('.$row[0]['Porcentaje'].'%)</label>';
				}
				echo $impuesto;
			}
		}
	}

}
?>
