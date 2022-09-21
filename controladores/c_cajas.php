
<?php
class cajas {

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
			$busqueda = 'WHERE ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(ID_Caja, FK_Sucursal, sucursales.Nombre, cajas.Nombre, Detalles, Estado, FK_Usuario, usuarios.Nombre) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Caja, FK_Sucursal, sucursales.Nombre AS NombreSucursal, cajas.Nombre AS Caja, Detalles, cajas.Estado, FK_Usuario, usuarios.Nombre AS UsuarioActual, (SELECT COUNT(*) FROM cajas $busqueda) AS Num FROM cajas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal LEFT JOIN usuarios ON FK_Usuario = ID_Usuario $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){

					$estatus = "";
					if ($row[$i]['Estado'] == "0") {
						$estatus = '<span class="badge rounded-pill bg-danger">Caja cerrada</span>';
					}else if($row[$i]['Estado'] == "1"){
						$estatus = '<span class="badge rounded-pill bg-success">Caja abierta</span>';
					}

					$detalles = "No hay datos ingresados";
					if ($row[$i]['Detalles'] != "") {
						$detalles = $row[$i]['Detalles'];
					}

					$usuario = "No hay un usuario utilizando la caja actualmente";
					if ($row[$i]['UsuarioActual'] != "") {
						$usuario = $row[$i]['UsuarioActual'];
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Caja'],
						'Caja' => $row[$i]['Caja'],
						'Sucursal' => $row[$i]['NombreSucursal'],
						'Detalles' => $detalles,
						'Estatus' => $estatus,
						'Usuario' => $usuario,
						'Acciones' => '<button class="btn btn-primary btn-sm mb-2" id="ModificarCaja" attrid="'.$row[$i]['ID_Caja'].'" nombre="'.$row[$i]['Caja'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm" id="EliminarCaja" attrid="'.$row[$i]['ID_Caja'].'" nombre="'.$row[$i]['Caja'].'"><i class="fas fa-trash"></i></button>',
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
		$Nombre =  $omodelo->link->real_escape_string($Nombre);
		$Detalles =  $omodelo->link->real_escape_string($Detalles);
		$Sucursal =  $omodelo->link->real_escape_string($Sucursal);

		$query = "INSERT INTO cajas SET Nombre = '$Nombre', Detalles = '$Detalles', FK_Sucursal = '$Sucursal'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$IDCaja =  $omodelo->link->real_escape_string($IDCaja);
		$Nombre =  $omodelo->link->real_escape_string($Nombre);
		$Detalles =  $omodelo->link->real_escape_string($Detalles);
		$Sucursal =  $omodelo->link->real_escape_string($Sucursal);

		$query = "UPDATE cajas SET Nombre = '$Nombre', Detalles = '$Detalles', FK_Sucursal = '$Sucursal' WHERE ID_Caja = '$IDCaja'";
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
		$IDCaja =  $omodelo->link->real_escape_string($IDCaja);

		$query = "DELETE FROM cajas WHERE ID_Caja = '$IDCaja'";
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
		if ($tipo == "ConsultarSucursales") {
			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = '<option value=""> Seleccione una sucursal </option>';
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opciones .= '<option value="'.$row[$i]["ID_Sucursal"].'"> '.$row[$i]["Nombre"].' </option>';
					}
				}
				echo $opciones;
			}
		}else if($tipo == "ConsultarCaja"){
			$IDCaja =  $omodelo->link->real_escape_string($IDCaja);

			$query = "SELECT ID_Caja, Nombre, Detalles, FK_Sucursal FROM cajas WHERE ID_Caja = '$IDCaja'";
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
