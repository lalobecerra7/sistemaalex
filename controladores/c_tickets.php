
<?php
class tickets {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$query = "SELECT ID_General, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Email FROM general";
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
		$Descripcion =  $omodelo->link->real_escape_string($Descripcion);

		$query = "INSERT INTO categorias SET Nombre = '$Nombre', Descripcion = '$Descripcion'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			//$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
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
		}
	}

}
?>
