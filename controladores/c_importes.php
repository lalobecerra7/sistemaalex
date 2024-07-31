<?php
class importes {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$buscar =  $omodelo->link->real_escape_string($buscar);
		$limit =  $omodelo->link->real_escape_string($limit);
		$pagina =  $omodelo->link->real_escape_string($pagina);
		$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
		$orden =  $omodelo->link->real_escape_string($orden);
		$arreglo = array();
		$NumRows = 0;

		$busqueda = '';
		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));
			$busqueda = 'WHERE ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(Nombre, Primer_Apellido, Segundo_Apellido, Telefono, Correo, RFC) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$queryRows = "SELECT ID_Importe FROM `importes` INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN clientes ON FK_Cliente = ID_Cliente $busqueda GROUP BY ID_Cliente";
		$rowRows = $omodelo->_consultar($queryRows);
		$numerofilasRows = $omodelo->numerofilas;

		if($rowRows == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilasRows > 0){
				$NumRows = $numerofilasRows;
			}
		}

		$query = "SELECT ID_Cliente, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS Cliente, Telefono, Correo, RFC FROM `importes` INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN clientes ON FK_Cliente = ID_Cliente $busqueda GROUP BY ID_Cliente ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$SumarVentas = 0;
				for($i=0; $i<$numerofilas; $i++){
					$tipoUsuario = "";$usuario="";$estatus="";$motivocancelada="";$botonCancelar="";$fechacancelada="";$botonTicket="";
					$botonVerImportes = '<button class="btn btn-primary btn-sm" id="VerProductosImporte" nombre="'.$row[$i]['Cliente'].'" idcliente="'.$row[$i]['ID_Cliente'].'">Ver importes</button>';

					/*if ($row[$i]['EstatusVenta'] == "Cancelada") {
						$estatus='<span class="badge rounded-pill bg-danger">Venta cancelada</span>';
						$motivocancelada = "Motivo de cancelación: ".$row[$i]['Notas'];
						$fechacancelada = '<br>Fecha de cancelación: <b>'.$row[$i]['Fecha_Cancelacion']."</b><br>";
					}else{
						$estatus='<span class="badge rounded-pill bg-success">Venta completada</span>';
					}
					$sumapendientes = 0;
					$query2 = "SELECT presentaciones.Importe AS ImportePresentacion, Cantidad, importes.Importe FROM importes INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '".$row[$i]['ID_Venta']."' AND Estatus = 'Se debe'";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							for ($x=0; $x < $numerofilas2; $x++) { 
								if ($row2[$x]["ImportePresentacion"] != "") {
									$sumapendientes += $row2[$x]["ImportePresentacion"] * $row2[$x]["Cantidad"];
								}else{
									$sumapendientes += $row2[$x]["Importe"] * $row2[$x]["Cantidad"];
								}
 							}
						}
					}

					$sumapagados = 0;
					$query3 = "SELECT presentaciones.Importe AS ImportePresentacion, Cantidad, importes.Importe FROM importes INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '".$row[$i]['ID_Venta']."' AND Estatus = 'Pagado'";
					$row3 = $omodelo->_consultar($query3);
					$numerofilas3 = $omodelo->numerofilas;

					if($row3 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas3 > 0){
							for ($x=0; $x < $numerofilas3; $x++) { 
								if ($row3[$x]["ImportePresentacion"] != "") {
									$sumapagados += $row3[$x]["ImportePresentacion"] * $row3[$x]["Cantidad"];
								}else{
									$sumapagados += $row3[$x]["Importe"] * $row3[$x]["Cantidad"];
								}
 							}
						}
					}


					$SumarVentas += $row[$i]['Total'];*/

					/*if ($row[$i]['ImportesPendientes'] == 0) {
						$estatus='<span class="badge rounded-pill bg-success">PAGADO</span>';
					}else{
						$estatus='<span class="badge rounded-pill bg-warning">PENDIENTE</span>';	
					}*/
					
					/*
						//"Subtotal: <b>$".number_format(($row[$i]['Total'] + $row[$i]['Descuento']), 2)."</b><br>Descuento: <b>$".number_format($row[$i]['Descuento'], 2)."</b><br>Total: <b>$".number_format($row[$i]['Total'], 2)."</b>",

						"Cantidad de importes: <b>".number_format($row[$i]["NumeroImportes"], 2)."</b><br>
							Pagados: <b>".$row[$i]['Estatus']."</b><br>Total pagados: $".number_format($sumapagados, 2)." <br>Pendientes: <b>".$row[$i]['ImportesPendientes']."</b><br>Total pendientes: $".number_format($sumapendientes, 2)."<br>".$botonPermisosModificar
					*/


					$botonPermisosModificar = "";
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_importes'][2] == '1') {
						$botonPermisosModificar = $botonVerImportes;
					}
					$sumapagados = 0;
					$sumapendientes = 0;
					$cantidadImportes = 0;
					$cantidadPendientes = 0;
					$cantidadPagados = 0;
					$query3 = "SELECT FK_Cliente, Pagados, productos.Importe AS ImporteProducto, ID_Importe, presentaciones.Importe AS ImportePresentacion, FK_Venta, importes.FK_Producto, importes.FK_Presentacion, Cantidad, importes.Importe, importes.Total, importes.Estatus FROM importes INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Cliente = '".$row[$i]['ID_Cliente']."'";
					$row3 = $omodelo->_consultar($query3);
					$numerofilas3 = $omodelo->numerofilas;

					if($row3 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas3 > 0){
							for ($x=0; $x < $numerofilas3; $x++) { 
								$cantidadImportes += $row3[$x]["Cantidad"];
								$cantidadPagados += $row3[$x]["Pagados"];
								$sumapagados += ($row3[$x]["Importe"] * $row3[$x]["Pagados"]);
								$cantidadPendientes += $row3[$x]["Cantidad"] - $row3[$x]["Pagados"];
								$sumapendientes += ($row3[$x]["Importe"] * $cantidadPendientes);	
							}
						}
					}

					if ($cantidadPendientes == 0) {
						$estatus='<span class="badge rounded-pill bg-success">PAGADO</span>';
					}else{
						$estatus='<span class="badge rounded-pill bg-warning">PENDIENTE</span>';	
					}

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Cliente'],
						'Cliente' => 'Nombre: <b>'.$row[$i]['Cliente'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Correo electrónico: <b>'.$row[$i]['Correo'].'</b><br>RFC: <b>'.$row[$i]['RFC']."</b>",
						'Importes' => "Cantidad de importes: ".$cantidadImportes."<br>Cantidad pagados: <b>".$cantidadPagados."</b><br>Total pagados: <b class='dinero'>".$sumapagados."</b><br>Cantidad pendientes: <b>".$cantidadPendientes."</b><br>Total pendientes: <b class='dinero'>".$sumapendientes."</b>",
						'Estatus' => $estatus."<br>".$motivocancelada.$fechacancelada,
						'Acciones' => $botonPermisosModificar,
					);
				}

				$arreglo['totales'] = array(
					'NumRows' => $NumRows, 
					'Datos' => "",
					'Cliente' => "Totales",
					'Total' => "<b>$".number_format($SumarVentas, 2)."</b>",
					'Importes' =>"",
					'Estatus' =>"",
					'Acciones' => "");	
	
			}
		}

		echo json_encode($arreglo);
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		$IDImporte = $omodelo->link->real_escape_string($IDImporte);
		$ImportesPagos = $omodelo->link->real_escape_string($ImportesPagos);

		$query = "UPDATE importes SET Pagados = (Pagados + '$ImportesPagos') WHERE ID_Importe = '$IDImporte'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$querydetalle = "INSERT INTO detalles_importes SET Cantidad = '$ImportesPagos', FK_Importe = '$IDImporte', Fecha_Registro = '$fecha'";
			$errordetalle = $omodelo->_insertar($querydetalle);
			if ($errordetalle == "si") {
				echo "Error detalles: ".mysqli_error($omodelo->link);
			}else{
				$query = "SELECT ID_Importe, FK_Venta, FK_Producto, FK_Presentacion, Cantidad, Importe, Total, Pagados, Estatus FROM importes WHERE ID_Importe = '$IDImporte'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;

				if($row == 'si'){
					echo "Error: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						if ($row[0]["Pagados"] == $row[0]["Cantidad"]) {
							$query = "UPDATE importes SET Estatus = 'Pagado' WHERE ID_Importe = '$IDImporte'";
							$error = $omodelo->_insertar($query);

							if ($error == "si") {
								echo "Error 1: ".mysqli_error($omodelo->link);
							}	
						}
					}
				}
				echo "Correcto";
			}
		}	
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		if($tipo == 'ConsultarProductosImporte'){
			$idcliente =  $omodelo->link->real_escape_string($idcliente);
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$sucursal = $_SESSION['user_admin']['FK_Sucursal'];

			if ($omodelo->permisos() == 'Administrador'){
				$busqueda = '';
				if(trim($buscar) != ''){
					$separa = explode(' ', trim($buscar));
					$busqueda = 'AND ';
					for ($i=0; $i < count($separa); $i++) { 
						$busqueda .= "CONCAT(LPAD(FK_Venta, 8, '0'), Cantidad, importes.Total, importes.Estatus, productos.Descripcion) REGEXP '".$separa[$i]."'";
						if($i < (count($separa)-1)){
							$busqueda .= ' AND ';
						}
					}
				}
				$query = "SELECT LPAD(FK_Venta, 8, '0') AS FolioVenta, ID_Importe, importes.Pagados AS ImportesPagados, FK_Venta AS Venta, FK_Cliente, importes.FK_Producto, FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Importe AS ImportePresentacion, productos.Nombre_Unidad AS NombreGenerico, Cantidad, importes.Importe, importes.Total, importes.Estatus, productos.Descripcion AS Producto, (SELECT COUNT(*) FROM importes INNER JOIN productos ON importes.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Cliente = '$idcliente') AS Num FROM importes INNER JOIN productos ON importes.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Cliente = '$idcliente' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			}else{
				$busqueda = '';
				if(trim($buscar) != ''){
					$separa = explode(' ', trim($buscar));
					$busqueda = 'AND ';
					for ($i=0; $i < count($separa); $i++) { 
						$busqueda .= "CONCAT(Cantidad, importes.Total, importes.Estatus, productos.Descripcion) REGEXP '".$separa[$i]."'";
						if($i < (count($separa)-1)){
							$busqueda .= ' AND ';
						}
					}
				}
				$query = "SELECT LPAD(FK_Venta, 8, '0') AS FolioVenta, ID_Importe, importes.Pagados AS ImportesPagados, FK_Venta AS Venta, FK_Cliente, importes.FK_Producto, FK_Presentacion, presentaciones.Nombre AS NombrePresentacion, presentaciones.Importe AS ImportePresentacion, productos.Nombre_Unidad AS NombreGenerico, Cantidad, importes.Importe, importes.Total, importes.Estatus, productos.Descripcion AS Producto, (SELECT COUNT(*) FROM importes INNER JOIN productos ON importes.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Cliente = '$idcliente') AS Num FROM importes INNER JOIN productos ON importes.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Cliente = '$idcliente' AND FK_Sucursal = '$sucursal' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			}

			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarImportes = 0;
					for($i=0; $i<$numerofilas; $i++){
						$estatus = "";

						$totalImportes = 0;
						$nombrePresentacion = "";
						if ($row[$i]['NombrePresentacion'] != "") {
							$nombrePresentacion = $row[$i]['NombrePresentacion'];
						}else{
							$nombrePresentacion = $row[$i]['NombreGenerico'];
						}

						$NombreProducto = $row[$i]['Producto']." (".$nombrePresentacion.")";

						$folio = $row[$i]['FolioVenta'];
						$botonMarcarPagado = "";
						$restantesPagar = $row[$i]['Cantidad'] - $row[$i]['ImportesPagados'];
						if ($row[$i]['Estatus'] == "Se debe") {
							$estatus = '<span class="badge rounded-pill bg-warning">Se debe</span>';
							$botonMarcarPagado = '<button class="btn btn-primary btn-sm MarcarPagadoImporte" attrid="'.$row[$i]['ID_Importe'].'" idventa="'.$row[$i]['Venta'].'" nombreproducto="'.$NombreProducto.'" folio="'.$folio.'" importes="'.$row[$i]['Cantidad'].'" pagados="'.$row[$i]['ImportesPagados'].'" restantes="'.$restantesPagar.'" idcliente="'.$row[$i]['FK_Cliente'].'" precioImporte="'.$row[$i]["Importe"].'">Pagar</button>';
						}else if($row[$i]['Estatus'] == "Pagado"){
							$estatus = '<span class="badge rounded-pill bg-primary">Pagado</span>';
						}

			            $presentacionImporte = $row[$i]["Importe"];

			            $totalImportes = $row[$i]['Cantidad'] * $presentacionImporte;

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Importe'],
							'Venta' => $folio,
							'Producto' => $row[$i]['Producto']." (".$nombrePresentacion.")",
							'Cantidad' =>  "<b>".number_format(($row[$i]['Cantidad']), 2)."</b><br>Pagados: <b>".number_format(($row[$i]['ImportesPagados']), 2)."</b>",
							'Importe' => "<b>$".number_format(($presentacionImporte), 2)."</b>",
							'Total' =>"<b>$".number_format(($totalImportes), 2)."</b>",
							'Estatus' => $estatus,
							'Acciones' => $botonMarcarPagado,
						);
						$SumarImportes += $row[$i]['Total'];
					}

					$arreglo['totales'] = array(
						'NumRows' => $row[0]['Num'], 
						'Venta' => "",
						'Producto' => "",
						'Cantidad' => "",
						'Importe' => "Totales",
						'Total' =>"<b>$".number_format($SumarImportes, 2)."</b>",
						'Estatus' =>"",
						'Acciones' => "");	
		
				}
			}

			echo json_encode($arreglo);
		}
	}
}
?>
