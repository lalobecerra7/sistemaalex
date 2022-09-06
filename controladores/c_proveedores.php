<?php
class proveedores {

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
			$busqueda = 'AND ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(Fecha_Registro, ID_Proveedor, Nombre, Direccion, Telefono, Ciudad, Estado, Pais, Empresa , Colonia, Codigo_Postal, Puesto, Correo, RFC, Credito, Celular, No_Cuenta, Banco, Razon_Social) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Proveedor, Nombre, Direccion, Telefono, Ciudad, Estado, Pais, Empresa , Colonia, Codigo_Postal, Puesto, Correo, RFC, Credito, Celular, No_Cuenta, Banco, Razon_Social AS RazonSocial, Fecha_Registro AS Fecha, (SELECT COUNT(*) FROM proveedores WHERE ID_Proveedor <> 2 $busqueda) AS Num, ((SELECT COUNT(*) FROM compras WHERE proveedor = ID_Proveedor) + (SELECT COUNT(*) FROM detalle_proveedores WHERE proveedor = ID_Proveedor)) AS numProve FROM proveedores WHERE ID_Proveedor <> 2 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$contacto = ""; $direccion=""; $telefono="";$razonsocial="";
					if ($row[$i]['Nombre'] != "") {
						$contacto .= $row[$i]['Nombre']."<br>";
					}

					if ($row[$i]['Correo'] != "") {
						$contacto .= $row[$i]['Correo']."<br>";
					}

					if ($row[$i]['Puesto'] != "") {
						$contacto .= "Puesto: ".$row[$i]['Puesto']."<br>";
					}

					if ($row[$i]['Celular'] != "") {
						$contacto .= "Celular: ".$row[$i]['Celular']."<br>";
					}

					if ($row[$i]['Banco'] != "") {
						$contacto .= "Banco: ".$row[$i]['Banco']."<br>";
					}

					if ($row[$i]['No_Cuenta'] != "") {
						$contacto .= "No. de cuenta: ".$row[$i]['No_Cuenta']."<br>";
					}

					if ($row[$i]['Direccion'] != "") {
						$direccion .= "Dirección: ".$row[$i]['Direccion']."<br>";
					}

					if ($row[$i]['Colonia'] != "") {
						$direccion .= "Colonia: ".$row[$i]['Colonia']."<br>";
					}

					if ($row[$i]['Ciudad'] != "") {
						$direccion .= "Ciudad: ".$row[$i]['Ciudad']."<br>";
					}

					if ($row[$i]['Estado'] != "") {
						$direccion .= "Estado: ".$row[$i]['Estado']."<br>";
					}

					if ($row[$i]['Pais'] != "") {
						$direccion .= "País: ".$row[$i]['Pais']."<br>";
					}

					if ($row[$i]['Telefono'] != "") {
						$telefono = "<br>Telefono: ".$row[$i]['Telefono'];
					}


					$EliminarProveedor = '<button class="btn btn-danger btn-sm" id="EliminarProveedor" attrid="'.$row[$i]['ID_Proveedor'].'" nombre="'.$row[$i]['Empresa'].'"><i class="fas fa-trash"></i></button>';
					if ($row[$i]['numProve'] > 0) {
						$EliminarProveedor = '';
					}

					if ($row[$i]['RazonSocial'] != "") {
						$razonsocial="Razón social: <b>".$row[$i]['RazonSocial']."</b><br>";
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Proveedor'],
						'Fecha' => '<b>'.$row[$i]['Fecha'].'</b>',
						'Empresa' => $razonsocial.$row[$i]['Empresa'].$telefono,
						'Contacto' => $contacto,
						'Direccion' => $direccion,
						'Acciones' => '<button class="btn btn-primary btn-sm" id="ModificarProveedor" attrid="'.$row[$i]['ID_Proveedor'].'" nombre="'.$row[$i]['Empresa'].'"><i class="fas fa-edit"></i></button> '.$EliminarProveedor,
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
		$fecha = date('Y-m-d H:i:s'); 
		$NombreEmpresaProveedor =  $omodelo->link->real_escape_string($NombreEmpresaProveedor);
		$RazonSocialProveedor =  $omodelo->link->real_escape_string($RazonSocialProveedor);
		$TelefonoProveedor =  $omodelo->link->real_escape_string($TelefonoProveedor);
		$DireccionProveedor =  $omodelo->link->real_escape_string($DireccionProveedor);
		$ColoniaProveedor =  $omodelo->link->real_escape_string($ColoniaProveedor);
		$CiudadProveedor =  $omodelo->link->real_escape_string($CiudadProveedor);
		$EstadoProveedor =  $omodelo->link->real_escape_string($EstadoProveedor);
		$PaisProveedor =  $omodelo->link->real_escape_string($PaisProveedor);
		$CPProveedor =  $omodelo->link->real_escape_string($CPProveedor);
		$ContactoProveedor =  $omodelo->link->real_escape_string($ContactoProveedor);
		$PuestoContactoProveedor =  $omodelo->link->real_escape_string($PuestoContactoProveedor);
		$CorreoContactoProveedor =  $omodelo->link->real_escape_string($CorreoContactoProveedor);
		$CelularContactoProveedor =  $omodelo->link->real_escape_string($CelularContactoProveedor);
		$RFCProveedor =  $omodelo->link->real_escape_string($RFCProveedor);
		$BancoProveedor =  $omodelo->link->real_escape_string($BancoProveedor);
		$NoCuentaProveedor =  $omodelo->link->real_escape_string($NoCuentaProveedor);
		$TipoDescuento =  $omodelo->link->real_escape_string($TipoDescuento);
		$DescuentoProveedor =  $omodelo->link->real_escape_string($DescuentoProveedor);
		$Credito =  $omodelo->link->real_escape_string($Credito);

		$query = "INSERT INTO proveedores SET Nombre = '$ContactoProveedor', Direccion = '$DireccionProveedor', Telefono = '$TelefonoProveedor', Ciudad = '$CiudadProveedor', Estado = '$EstadoProveedor', Pais = '$PaisProveedor', Empresa = '$NombreEmpresaProveedor', Colonia = '$ColoniaProveedor', Codigo_Postal = '$CPProveedor', Puesto = '$PuestoContactoProveedor', Correo = '$CorreoContactoProveedor', RFC = '$RFCProveedor', Celular = '$CelularContactoProveedor', No_Cuenta = '$NoCuentaProveedor', Banco = '$BancoProveedor', Razon_Social = '$RazonSocialProveedor', Fecha_Registro = '$fecha', Tipo_Descuento = '$TipoDescuento', Descuento = '$DescuentoProveedor', Credito = '$Credito'";
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
		$IDProveedor =  $omodelo->link->real_escape_string($IDProveedor);
		$NombreEmpresaProveedor =  $omodelo->link->real_escape_string($NombreEmpresaProveedor);
		$RazonSocialProveedor =  $omodelo->link->real_escape_string($RazonSocialProveedor);
		$TelefonoProveedor =  $omodelo->link->real_escape_string($TelefonoProveedor);
		$DireccionProveedor =  $omodelo->link->real_escape_string($DireccionProveedor);
		$ColoniaProveedor =  $omodelo->link->real_escape_string($ColoniaProveedor);
		$CiudadProveedor =  $omodelo->link->real_escape_string($CiudadProveedor);
		$EstadoProveedor =  $omodelo->link->real_escape_string($EstadoProveedor);
		$PaisProveedor =  $omodelo->link->real_escape_string($PaisProveedor);
		$CPProveedor =  $omodelo->link->real_escape_string($CPProveedor);
		$ContactoProveedor =  $omodelo->link->real_escape_string($ContactoProveedor);
		$PuestoContactoProveedor =  $omodelo->link->real_escape_string($PuestoContactoProveedor);
		$CorreoContactoProveedor =  $omodelo->link->real_escape_string($CorreoContactoProveedor);
		$CelularContactoProveedor =  $omodelo->link->real_escape_string($CelularContactoProveedor);
		$RFCProveedor =  $omodelo->link->real_escape_string($RFCProveedor);
		$BancoProveedor =  $omodelo->link->real_escape_string($BancoProveedor);
		$NoCuentaProveedor =  $omodelo->link->real_escape_string($NoCuentaProveedor);
		$TipoDescuento =  $omodelo->link->real_escape_string($TipoDescuento);
		$DescuentoProveedor =  $omodelo->link->real_escape_string($DescuentoProveedor);
		$Credito =  $omodelo->link->real_escape_string($Credito);

		$query = "UPDATE proveedores SET Nombre = '$ContactoProveedor', Direccion = '$DireccionProveedor', Telefono = '$TelefonoProveedor', Ciudad = '$CiudadProveedor', Estado = '$EstadoProveedor', Pais = '$PaisProveedor', Empresa = '$NombreEmpresaProveedor', Colonia = '$ColoniaProveedor', Codigo_Postal = '$CPProveedor', Puesto = '$PuestoContactoProveedor', Correo = '$CorreoContactoProveedor', RFC = '$RFCProveedor', Celular = '$CelularContactoProveedor', No_Cuenta = '$NoCuentaProveedor', Banco = '$BancoProveedor', Razon_Social = '$RazonSocialProveedor', Tipo_Descuento = '$TipoDescuento', Descuento = '$DescuentoProveedor', Credito = '$Credito' WHERE ID_Proveedor = '$IDProveedor'";
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
		$IDProveedor =  $omodelo->link->real_escape_string($id);
		$query = "DELETE FROM proveedores WHERE ID_Proveedor='$IDProveedor'";
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
		$IDProveedor =  $omodelo->link->real_escape_string($IDProveedor);

		$query = "SELECT ID_Proveedor, Nombre, Direccion, Telefono, Ciudad, Estado, Pais, Empresa , Colonia, Codigo_Postal, Puesto, Correo, RFC, Credito, Celular, No_Cuenta, Banco, Razon_Social, Tipo_Descuento, Descuento FROM proveedores WHERE ID_Proveedor = '$IDProveedor'";
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
?>
