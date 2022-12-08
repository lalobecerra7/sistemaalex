<?php
class facturacion {
	public function _consultar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id = trim($omodelo->link->real_escape_string($id));
		$array = null;

		$query = "SELECT RFC, Nombre, Regimen, Certificado, Key_Cer, Contrasena FROM general WHERE ID_General = '1'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				if(trim($row[0]['RFC']) == '' || trim($row[0]['Nombre']) == '' || trim($row[0]['Regimen']) == '' || trim($row[0]['Certificado']) == '' || trim($row[0]['Key_Cer']) == '' || trim($row[0]['Contrasena']) == ''){
					echo "Error 2 Datos Facturacion";
				}else{
					$query1 = "SELECT FK_Cliente, Razon_CFDI, Regimen_CFDI, clientes.Calle AS Calle_Cliente, clientes.No_Exterior AS No_Exterior_Cliente, clientes.No_Interior AS No_Interior_Cliente, clientes.Colonia AS Colonia_Cliente, clientes.Ciudad AS Ciudad_Cliente, clientes.Codigo_Postal AS Codigo_Postal_Cliente, clientes.Estado AS Estado_Cliente, clientes.Pais AS Pais_Cliente, RFC AS RFC_Cliente, sucursales.Nombre AS Sucursal, sucursales.Calle AS Calle_Sucursal, sucursales.No_Exterior AS No_Exteriror_Sucursal, sucursales.No_Interior AS No_Interior_Sucursal, sucursales.Colonia AS Colonia_Sucursal, sucursales.CP AS CP_Sucursal, sucursales.Ciudad AS Ciudad_Sucursal, sucursales.Estado AS Estado_Sucursal, sucursales.Pais AS Pais_Sucursal, ventas.Descuento AS Descuento, Total, ventas.Fecha_Registro AS Fecha_Registro FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ID_Venta = '$id' AND Facturada = '0' AND Cancelada = '0'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas1 = $omodelo->numerofilas;

					if($row1 == 'si'){
						echo "Error 3: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas1 > 0){
							$productos = null;
							$query2 = "SELECT ID_Detalle_Venta, FK_Presentacion, Codigo, productos.Clave_ProdServ_CFDI AS Clave_ProdServ_CFDI, productos.Clave_Unidad_CFDI AS Clave_Unidad_CFDI, productos.Objeto_Impuesto_CFDI AS Objeto_Impuesto_CFDI, productos.Clave_Unidad_CFDI AS Clave_Unidad_CFDI, Clave_CFDI, Nombre_Presentacion, Abreviacion_Presentacion, detalles_ventas.Descripcion AS Descripcion, detalles_ventas.Precio AS Precio, Cantidad, Descuento, Total FROM detalles_ventas INNER JOIN productos ON detalles_ventas.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '$id'";
							$row2 = $omodelo->_consultar($query2);
							$numerofilas2 = $omodelo->numerofilas;

							if($row2 == 'si'){
								echo "Error 4: ".mysqli_error($omodelo->link);
							}else{
								if($numerofilas2 > 0){
									for ($i=0; $i < $numerofilas2; $i++) { 
										
										//$query3 de impuestos

										$claveUnidad = $row2[$i]['Clave_Unidad_CFDI'];
										if($row2[$i]['FK_Presentacion'] != '0'){
											$claveUnidad = $row2[$i]['Clave_CFDI'];
										}

										$productos[$i] = array(
											'ID_Detalle_Venta' => $row2[$i]['ID_Detalle_Venta'], 
											'Codigo' => $row2[$i]['Codigo'],
											'Clave_ProdServ_CFDI' => $row2[$i]['Clave_ProdServ_CFDI'],
											'Clave_Unidad_CFDI' => $row2[$i]['Clave_Unidad_CFDI'],
											'Objeto_Impuesto_CFDI' => $row2[$i]['Objeto_Impuesto_CFDI'],
											'Clave_Unidad_CFDI' => $row2[$i]['Clave_Unidad_CFDI'],
											'Clave_Unidad_CFDI' => $claveUnidad,
											'Abreviacion_Presentacion' => $row2[$i]['Abreviacion_Presentacion'], 
											'Descripcion' => $row2[$i]['Descripcion'], 
											'Precio' => $row2[$i]['Precio'], 
											'Cantidad' => $row2[$i]['Cantidad'], 
											'Descuento' => $row2[$i]['Descuento'], 
											'Total' => $row2[$i]['Total'] 
										);
									}
								}
							}

							$array = array(
								'RFC_General' => $row[0]['RFC'],
								'Nombre_General' => $row[0]['Nombre'],
								'Regimen_General' => $row[0]['Regimen'],
								'FK_Cliente' => $row1[0]['FK_Cliente'],
								'Razon_CFDI' => $row1[0]['Razon_CFDI'],
								'Regimen_CFDI' => $row1[0]['Regimen_CFDI'],
								'Calle_Cliente' => $row1[0]['Calle_Cliente'],
								'No_Exterior_Cliente' => $row1[0]['No_Exterior_Cliente'],
								'No_Interior_Cliente' => $row1[0]['No_Interior_Cliente'],
								'Colonia_Cliente' => $row1[0]['Colonia_Cliente'],
								'Ciudad_Cliente' => $row1[0]['Ciudad_Cliente'],
								'Codigo_Postal_Cliente' => $row1[0]['Codigo_Postal_Cliente'],
								'Estado_Cliente' => $row1[0]['Estado_Cliente'],
								'Pais_Cliente' => $row1[0]['Pais_Cliente'],
								'RFC_Cliente' => $row1[0]['RFC_Cliente'],
								'Sucursal' => $row1[0]['Sucursal'],
								'Calle_Sucursal' => $row1[0]['Calle_Sucursal'],
								'No_Exterior_Sucursal' => $row1[0]['No_Exteriror_Sucursal'],
								'No_Interior_Sucursal' => $row1[0]['No_Interior_Sucursal'],
								'Colonia_Sucursal' => $row1[0]['Colonia_Sucursal'],
								'CP_Sucursal' => $row1[0]['CP_Sucursal'],
								'Ciudad_Sucursal' => $row1[0]['Ciudad_Sucursal'],
								'Estado_Sucursal' => $row1[0]['Estado_Sucursal'],
								'Pais_Sucursal' => $row1[0]['Pais_Sucursal'],
								'Descuento' => $row1[0]['Descuento'],
								'Total' => $row1[0]['Total'],
								'Fecha_Registro' => $row1[0]['Fecha_Registro']
							);
						}
					}

					echo json_encode($array);
				}
			}else{
				echo "Error 5 Tabla datos generales";
			}
		}		
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