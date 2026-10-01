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
				$busqueda .= "CONCAT(ID_Sucursal, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Email, FK_Encargado, usuarios.Nombre,  usuarios.Primer_Apellido, usuarios.Segundo_Apellido, zonas.Nombre) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}
		
		$query = "SELECT ID_Sucursal, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Segundo_Telefono, Email, FK_Encargado, usuarios.Nombre AS NombreEncargado, usuarios.Primer_Apellido AS PrimerApellido, usuarios.Segundo_Apellido AS SegundoApellido, FK_Zona, zonas.Nombre AS NombreZona, Latitud, Longitud, (SELECT COUNT(*) FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda) AS Num, ((SELECT COUNT(*) FROM ventas INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM cajas WHERE FK_Sucursal = ID_Sucursal)) AS numSucu FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$direccion = "";$telefono="";$email="";$gerente="";

					if ($row[$i]['NombreZona'] != "") {
						$direccion .= "Zona: <b>".$row[$i]['NombreZona'].'</b><br>';
					}

					if ($row[$i]['Calle'] != "") {
						$direccion .= "Calle: <b>".$row[$i]['Calle'].'</b><br>';
					}

					if ($row[$i]['No_Exterior'] != "") {
						$direccion .= "No. Exterior: <b>".$row[$i]['No_Exterior'].'</b><br>';
					}

					if ($row[$i]['No_Interior'] != "") {
						$direccion .= "No. Interior: <b>".$row[$i]['No_Interior'].'</b><br>';
					}

					if ($row[$i]['Colonia'] != "") {
						$direccion .= "Colonia: <b>".$row[$i]['Colonia'].'</b><br>';
					}

					if ($row[$i]['CP'] != "") {
						$direccion .= "Codigo postal: <b>".$row[$i]['CP'].'</b><br>';
					}

					if ($row[$i]['Ciudad'] != "") {
						$direccion .= "Ciudad: <b>".$row[$i]['Ciudad'].'</b><br>';
					}

					if ($row[$i]['Estado'] != "") {
						$direccion .= "Estado: <b>".$row[$i]['Estado'].'</b><br>';
					}

					if ($row[$i]['Pais'] != "") {
						$direccion .= "País: <b>".$row[$i]['Pais'].'</b><br>';
					}

					if ($row[$i]['Telefono'] != "") {
						$telefono .= "Primer teléfono: <b>".$row[$i]['Telefono'].'</b><br>';
					}

					if ($row[$i]['Segundo_Telefono'] != "") {
						$telefono .= "Segundo teléfono: <b>".$row[$i]['Segundo_Telefono'].'</b><br>';
					}

					if ($row[$i]['Latitud'] != '' && $row[$i]['Longitud'] != '') {
						$telefono .= 'Ubicación: <b>'.$row[$i]['Latitud'].', '.$row[$i]['Longitud'].'</b>';
					}
					
					if ($telefono == "") {
						$telefono = "No hay telefono registrado";
					}
					
					if ($row[$i]['Email'] != "") {
						$email = $row[$i]['Email'];
					}else{	
						$email = "No hay un correo registrado";
					}

					if ($row[$i]['FK_Encargado'] != "") {
						$gerente = $row[$i]['NombreEncargado'].' '.$row[$i]['PrimerApellido'].' '.$row[$i]['SegundoApellido'];
					}else{	
						$gerente = "No hay datos registrados";
					}

					$EliminarSucursal = '<button class="btn btn-danger btn-sm" id="EliminarSucursal" attrid="'.$row[$i]['ID_Sucursal'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>';
					if ($row[$i]['numSucu'] > 0) {
						$EliminarSucursal = '';
					}
					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_sucursales'][3] == '1') {
						$botonPermisosModificar = '<button class="btn btn-primary btn-sm" id="ModificarSucursal" attrid="'.$row[$i]['ID_Sucursal'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button>';
					}

					$botonPermisosEliminar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_sucursales'][4] == '1') {
						$botonPermisosEliminar = $EliminarSucursal;
					}
					

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Sucursal'],
						'Nombre' => $row[$i]['Nombre'],
						'NombreGerente' => $gerente,
						'Correo' => $email,
						'Direccion' => $direccion,
						'Telefonos' => $telefono,
						'Acciones' => $botonPermisosModificar.' '.$botonPermisosEliminar,
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
		$NombreSucursal = $omodelo->link->real_escape_string($NombreSucursal);
		$EncargadoSucursal = $omodelo->link->real_escape_string($EncargadoSucursal);
		$CalleSucursal = $omodelo->link->real_escape_string($CalleSucursal);
		$NoExteriorSucursal = $omodelo->link->real_escape_string($NoExteriorSucursal);
		$NoInteriorSucursal = $omodelo->link->real_escape_string($NoInteriorSucursal);
		$ColoniaSucursal = $omodelo->link->real_escape_string($ColoniaSucursal);
		$CPSucursal = $omodelo->link->real_escape_string($CPSucursal);
		$CiudadSucursal = $omodelo->link->real_escape_string($CiudadSucursal);
		$EstadoSucursal = $omodelo->link->real_escape_string($EstadoSucursal);
		$PaisSucursal = $omodelo->link->real_escape_string($PaisSucursal);
		$EmailSucursal = $omodelo->link->real_escape_string($EmailSucursal);
		$TelefonoSucursal = $omodelo->link->real_escape_string($TelefonoSucursal);
		$Telefono2Sucursal = $omodelo->link->real_escape_string($Telefono2Sucursal);
		$Zona = $omodelo->link->real_escape_string($Zona);
		$latitud = $omodelo->link->real_escape_string($latitud);
		$longitud = $omodelo->link->real_escape_string($longitud);

		$query = "INSERT INTO sucursales SET Nombre = '$NombreSucursal', FK_Encargado = '$EncargadoSucursal', Calle = '$CalleSucursal', No_Exterior = '$NoExteriorSucursal', No_Interior = '$NoInteriorSucursal', Colonia = '$ColoniaSucursal', CP = '$CPSucursal', Ciudad = '$CiudadSucursal', Estado = '$EstadoSucursal', Pais = '$PaisSucursal', Email = '$EmailSucursal', Telefono = '$TelefonoSucursal', Segundo_Telefono  = '$Telefono2Sucursal', FK_Zona = '$Zona', Latitud = '$latitud', Longitud = '$longitud'";
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
		$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);
		$NombreSucursal = $omodelo->link->real_escape_string($NombreSucursal);
		$EncargadoSucursal = $omodelo->link->real_escape_string($EncargadoSucursal);
		$CalleSucursal = $omodelo->link->real_escape_string($CalleSucursal);
		$NoExteriorSucursal = $omodelo->link->real_escape_string($NoExteriorSucursal);
		$NoInteriorSucursal = $omodelo->link->real_escape_string($NoInteriorSucursal);
		$ColoniaSucursal = $omodelo->link->real_escape_string($ColoniaSucursal);
		$CPSucursal = $omodelo->link->real_escape_string($CPSucursal);
		$CiudadSucursal = $omodelo->link->real_escape_string($CiudadSucursal);
		$EstadoSucursal = $omodelo->link->real_escape_string($EstadoSucursal);
		$PaisSucursal = $omodelo->link->real_escape_string($PaisSucursal);
		$EmailSucursal = $omodelo->link->real_escape_string($EmailSucursal);
		$TelefonoSucursal = $omodelo->link->real_escape_string($TelefonoSucursal);
		$Telefono2Sucursal = $omodelo->link->real_escape_string($Telefono2Sucursal);
		$Zona = $omodelo->link->real_escape_string($Zona);
		$latitud = $omodelo->link->real_escape_string($latitud);
		$longitud = $omodelo->link->real_escape_string($longitud);

		$query = "UPDATE sucursales SET Nombre = '$NombreSucursal', FK_Encargado = '$EncargadoSucursal', Calle = '$CalleSucursal', No_Exterior = '$NoExteriorSucursal', No_Interior = '$NoInteriorSucursal', Colonia = '$ColoniaSucursal', CP = '$CPSucursal', Ciudad = '$CiudadSucursal', Estado = '$EstadoSucursal', Pais = '$PaisSucursal', Email = '$EmailSucursal', Telefono = '$TelefonoSucursal', Segundo_Telefono  = '$Telefono2Sucursal', FK_Zona = '$Zona', Latitud = '$latitud', Longitud = '$longitud' WHERE ID_Sucursal = '$IDSucursal'";
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
			$arreglo = array();

			$query = "SELECT ID_Sucursal, Nombre, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Email, Telefono, Segundo_Telefono, Latitud, Longitud, FK_Zona, ((SELECT COUNT(*) FROM ventas INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM cajas WHERE FK_Sucursal = ID_Sucursal)) AS numSucu  FROM sucursales WHERE ID_Sucursal = '$id'";
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

				$arreglo = array('Nombre' => $row[0]["Nombre"], 'FK_Encargado' => $row[0]["FK_Encargado"], 'Calle' => utf8_encode($row[0]["Calle"]), 'No_Exterior' => utf8_encode($row[0]["No_Exterior"]), 'No_Interior' => $row[0]["No_Interior"], 'Colonia' => $row[0]["Colonia"], 'CP' => $row[0]["CP"], 'Ciudad' => $row[0]["Ciudad"], 'Estado' => $row[0]["Estado"], 'Pais' => $row[0]["Pais"], 'Email' => $row[0]["Email"], 'Telefono' => $row[0]["Telefono"], 'Segundo_Telefono' => $row[0]["Segundo_Telefono"], 'FK_Zona' => $row[0]["FK_Zona"], 'Latitud' => $row[0]['Latitud'], 'Longitud' => $row[0]['Longitud']);	
			}

			echo json_encode($arreglo);
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
