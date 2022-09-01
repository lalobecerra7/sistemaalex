<?php
class sucursales {

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
				$busqueda .= "CONCAT(ID_Sucursal, Nombre, Direccion, Telefono, RFC, NombreGerente) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Sucursal, Nombre, Direccion, Telefono, RFC, NombreGerente, (SELECT COUNT(*) FROM sucursales $busqueda) AS Num, ((SELECT COUNT(*) FROM ventas INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM cajas WHERE FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM pedidos WHERE FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM detalles_productos WHERE FK_Sucursal = ID_Sucursal)) AS numSucu FROM sucursales $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$direccion = "";$telefono="";$rfc="";$gerente="";
					if ($row[$i]['Direccion'] != "") {
						$direccion = $row[$i]['Direccion'];
					}else{	
						$direccion = "No hay datos registrados";
					}

					if ($row[$i]['Telefono'] != "") {
						$telefono = $row[$i]['Telefono'];
					}else{	
						$telefono = "No hay telefono registrado";
					}

					if ($row[$i]['RFC'] != "") {
						$rfc = $row[$i]['RFC'];
					}else{	
						$rfc = "No hay un RFC registrado";
					}

					if ($row[$i]['NombreGerente'] != "") {
						$gerente = $row[$i]['NombreGerente'];
					}else{	
						$gerente = "No hay datos registrados";
					}

					$EliminarSucursal = '<button class="btn btn-danger btn-sm" id="EliminarSucursal" attrid="'.$row[$i]['ID_Sucursal'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>';
					if ($row[$i]['numSucu'] > 0) {
						$EliminarSucursal = '';
					}
					

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Sucursal'],
						'Nombre' => $row[$i]['Nombre'],
						'Direccion' => $direccion,
						'Telefono' => $row[$i]['Telefono'],
						'RFC' => $rfc,
						'NombreGerente' => $gerente,
						'Acciones' => '<button class="btn btn-primary btn-sm" id="ModificarSucursal" attrid="'.$row[$i]['ID_Sucursal'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button> '.$EliminarSucursal,
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
		$NombreSucursal =  $omodelo->link->real_escape_string($NombreSucursal);
		$DireccionSucursal =  $omodelo->link->real_escape_string($DireccionSucursal);
		$TelefonoSucursal =  $omodelo->link->real_escape_string($TelefonoSucursal);
		$RFCSucursal =  $omodelo->link->real_escape_string($RFCSucursal);
		$NombreSucursalGerente =  $omodelo->link->real_escape_string($NombreSucursalGerente);


		$query = "INSERT INTO sucursales SET Nombre = '$NombreSucursal', Direccion = '$DireccionSucursal', Telefono = '$TelefonoSucursal', RFC = '$RFCSucursal', NombreGerente = '$NombreSucursalGerente'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);

		$query = "DELETE FROM sucursales WHERE ID_Sucursal='$id'";
		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
			echo "Correcto";
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$IDSucursal =  $omodelo->link->real_escape_string($IDSucursal);
		$NombreSucursal =  $omodelo->link->real_escape_string($NombreSucursal);
		$DireccionSucursal =  $omodelo->link->real_escape_string($DireccionSucursal);
		$TelefonoSucursal =  $omodelo->link->real_escape_string($TelefonoSucursal);
		$RFCSucursal =  $omodelo->link->real_escape_string($RFCSucursal);
		$NombreSucursalGerente =  $omodelo->link->real_escape_string($NombreSucursalGerente);


		$query = "UPDATE sucursales SET Nombre = '$NombreSucursal', Direccion = '$DireccionSucursal', Telefono = '$TelefonoSucursal', RFC = '$RFCSucursal', NombreGerente = '$NombreSucursalGerente' WHERE ID_Sucursal = '$IDSucursal'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']["ID_Usuario"]);
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date ('Y-m-d H:i:s');

		if($tipoDetalle == '1'){
			$query = "SELECT *, ((SELECT COUNT(*) FROM ventas INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM cajas WHERE FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM pedidos WHERE FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM detalles_productos WHERE FK_Sucursal = ID_Sucursal)) AS numSucu  FROM sucursales WHERE ID_Sucursal = '$id'";
			$row = $omodelo->_consultar($query);
				
			if ($row == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$botonelim = '';
				$botonModificar = '';
				if($row[0]["numSucu"] == 0){
					$botonelim='<button type="button" class="btn btn-danger bEliminarSucu" attrID="'.$row[0]["ID_Sucursal"].'" nombre="'.utf8_encode($row[0]["Nombre"]).'"><i class="fa fa-trash-o"></i></button>';
				}
				$botonModificar = '<button type="button" class="btn btn-theme-inverse btn-info bModificarSucu" attrID="'.$row[0]["ID_Sucursal"].'" nombre="'.$row[0]["Nombre"].'"><i class="fa fa-pencil-square-o"></i></button> ';

				$arreglo = array('Nombre' => $row[0]["Nombre"], 'Direccion' => $row[0]["Direccion"], 'Telefono' => utf8_encode($row[0]["Telefono"]), 'RFC' => utf8_encode($row[0]["RFC"]), 'Nombre del Gerente' => $row[0]["NombreGerente"]);

				echo json_encode($arreglo);
			}
		}else if($tipoDetalle == '2'){
			$query = "SELECT ID_Sucursal, nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($numerofilas > 0) {
				$opciones = '<option value=""> - Seleccione una opción - </option>';
				for ($i=0; $i < $numerofilas; $i++) { 
					$opciones .= '<option value="'.$row[$i]["ID_Sucursal"].'">'.utf8_encode($row[$i]["nombre"]).'</option>'; 
				}
			}

			$query1 = "SELECT id_proveedor, empresa FROM proveedores";
			$row1 = $omodelo->_consultar($query1);
			$numerofilas1 = $omodelo->numerofilas;
			if ($numerofilas1 > 0) {
				$opciones2 = '<option value=""> - Seleccione una opción - </option>';
				for ($i=0; $i < $numerofilas1; $i++) { 
					$opciones2 .= '<option value="'.$row1[$i]["id_proveedor"].'">'.utf8_encode($row1[$i]["empresa"]).'</option>'; 
				}
				echo $opciones."~".$opciones2;
			}
		}
	}

}
?>
