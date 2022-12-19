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

		$busqueda = '';
		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));
			$busqueda = 'WHERE ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(ID_Venta, clientes.Nombre, Notas, ventas.Fecha_Registro) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}
		
		$query = "SELECT ID_Venta, ventas.FK_Usuario, (SELECT COUNT(*) FROM importes WHERE FK_venta = ID_Venta) AS NumeroImportes, (SELECT COUNT(*) FROM importes WHERE FK_venta = ID_Venta AND Estatus = 'Pagado') AS ImportesPagados, (SELECT COUNT(*) FROM importes WHERE FK_venta = ID_Venta AND Estatus = 'Se debe') AS ImportesPendientes, ventas.FK_Sucursal, FK_Cliente, ventas.Descuento, ventas.Total, Tipo_Pago, Pago, Cambio, Notas, ventas.Fecha_Registro, Cancelada, Fecha_Cancelacion, Regreso_Inventario, (SELECT CONCAT(usuarios.Nombre,' ',usuarios.Primer_Apellido,' ',usuarios.Segundo_Apellido) FROM usuarios WHERE ID_Usuario = ventas.FK_Usuario) AS NombreUsuario, clientes.Nombre AS Datos, clientes.Telefono AS Telefono, clientes.Correo AS CorreoCliente, clientes.RFC AS RFCCliente FROM importes INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN clientes ON FK_Cliente = ID_Cliente $busqueda GROUP BY ID_Venta ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$SumarVentas = 0;
				for($i=0; $i<$numerofilas; $i++){
					$tipoUsuario = "";$usuario="";$estatus="";$motivocancelada="";$botonCancelar="";$fechacancelada="";$botonTicket="";
					$folio = str_pad($row[$i]['ID_Venta'], 8, "0", STR_PAD_LEFT);

					$botonVerImportes = '<button class="btn btn-primary btn-sm" id="VerProductosImporte" attrid="'.$row[$i]['ID_Venta'].'" folio="'.$folio.'">Ver importes</button>';

					$botonTicket = '<button class="btn btn-success btn-sm" id="ImprimirTicketVentaSinCajaImporte" attrid="'.$row[$i]['ID_Venta'].'" sucursal="'.$row[$i]['FK_Sucursal'].'" folio="'.$folio.'"><i class="fas fa-print"></i></button>';

					if ($row[$i]['Cancelada'] == 1) {
						$estatus='<span class="badge rounded-pill bg-danger">Venta cancelada</span>';
						$motivocancelada = "Motivo de cancelación: ".$row[$i]['Notas'];
						$fechacancelada = '<br>Fecha de cancelación: <b>'.$row[$i]['Fecha_Cancelacion']."</b><br>";
					}else{
						$estatus='<span class="badge rounded-pill bg-success">Venta completada</span>';
					}

					$SumarVentas += $row[$i]['Total'];

					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Venta'],
						'Datos' => "Fecha: <b>".$row[$i]['Fecha_Registro']."<br></b>Folio: <b>".$folio."</b><br>Usuario: <b>".$row[$i]['NombreUsuario']."</b>",
						'Cliente' => 'Nombre: <b>'.$row[$i]['Datos'].'</b><br>Teléfono: <b>'.$row[$i]['Telefono'].'</b><br>Correo electrónico: <b>'.$row[$i]['CorreoCliente'].'</b><br>RFC: <b>'.$row[$i]['RFCCliente']."</b>",
						'Total' => "Subtotal: <b>$".number_format(($row[$i]['Total'] + $row[$i]['Descuento']), 2)."</b><br>Descuento: <b>$".number_format($row[$i]['Descuento'], 2)."</b><br>Total: <b>$".number_format($row[$i]['Total'], 2)."</b>",
						'Importes' => "Cantidad de importes: <b>".number_format($row[$i]["NumeroImportes"], 2)."</b><br>
							Pagados: <b>".$row[$i]['ImportesPagados']."</b><br> Pendientes: <b>".$row[$i]['ImportesPendientes']."</b><br>".$botonVerImportes,
						'Detalles' => $estatus."<br>".$motivocancelada.$fechacancelada,
						'Acciones' => $botonTicket,
					);
				}

				$arreglo['totales'] = array(
					'NumRows' => $numerofilas, 
					'Datos' => "",
					'Cliente' => "Totales",
					'Total' => "<b>$".number_format($SumarVentas, 2)."</b>",
					'Importes' =>"",
					'Detalles' =>"",
					'Acciones' => "");	
	
			}
		}

		echo json_encode($arreglo);
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$IDImporte = $omodelo->link->real_escape_string($IDImporte);

		$query = "UPDATE importes SET Estatus = 'Pagado' WHERE ID_Importe = '$IDImporte'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}	

	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		if($tipo == 'ConsultarProductosImporte'){
			$idventa =  $omodelo->link->real_escape_string($idventa);
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
					$busqueda .= "CONCAT(ID_Importe, FK_Venta, FK_Producto, Cantidad, Importe, Total, Estatus, productos.Descripcion) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Importe, FK_Venta, FK_Producto, Cantidad, importes.Importe, Total, Estatus, productos.Descripcion FROM importes INNER JOIN productos ON FK_Producto = ID_Producto WHERE FK_Venta = '$idventa' $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$SumarImportes = 0;
					for($i=0; $i<$numerofilas; $i++){
						$estatus = "";
						$botonMarcarPagado = "";
						if ($row[$i]['Estatus'] == "Se debe") {
							$estatus = '<span class="badge rounded-pill bg-warning">Se debe</span>';
							$botonMarcarPagado = '<button class="btn btn-primary btn-sm MarcarPagadoImporte" attrid="'.$row[$i]['ID_Importe'].'" idventa="'.$row[$i]['FK_Venta'].'">Pagado</button>';
						}else if($row[$i]['Estatus'] == "Pagado"){
							$estatus = '<span class="badge rounded-pill bg-primary">Pagado</span>';
						}

						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Importe'],
							'Producto' => $row[$i]['Descripcion'],
							'Cantidad' =>  "<b>".number_format(($row[$i]['Cantidad']), 2)."</b>",
							'Importe' => "<b>$".number_format(($row[$i]['Importe']), 2)."</b>",
							'Total' =>"<b>$".number_format(($row[$i]['Total']), 2)."</b>",
							'Estatus' => $estatus,
							'Acciones' => $botonMarcarPagado,
						);
						$SumarImportes += $row[$i]['Total'];
					}

					$arreglo['totales'] = array(
						'NumRows' => $numerofilas, 
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
