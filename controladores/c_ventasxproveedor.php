<?php
class ventasxproveedor {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$buscar =  $omodelo->link->real_escape_string($buscar);
		$limit =  $omodelo->link->real_escape_string($limit);
		$pagina =  $omodelo->link->real_escape_string($pagina);
		$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
		$orden =  $omodelo->link->real_escape_string($orden);
		$arreglo = array();
		$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
		$fechaFin = $omodelo->link->real_escape_string($fechaFin);
		$sucursal = $omodelo->link->real_escape_string($sucursal);

		
		$busqueda = '';
		if(trim($buscar) != ''){
			$separa = explode(' ', trim($buscar));
			$busqueda = 'AND ';
			for ($i=0; $i < count($separa); $i++) { 
				$busqueda .= "CONCAT(
				(SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM clientes WHERE ID_Cliente = ventas.FK_Cliente)) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		//CONSULTAR NUMERO DE FILAS
		$cantidadRegistros = 0;
		$queryRegistros = "SELECT COUNT(*) FROM `ventas` WHERE ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $busqueda GROUP BY FK_Cliente";
		$rowRegistros = $omodelo->_consultar($queryRegistros);
		$numerofilasRegistros = $omodelo->numerofilas;
		if($rowRegistros == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			$cantidadRegistros = $numerofilasRegistros;
		}

		$query = "SELECT 
				FK_Cliente AS IDCliente, 
				(SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM clientes WHERE ID_Cliente = IDCliente) AS Cliente,
				COUNT(*) AS Cantidad, 
				SUM(ventas.Total) AS Total,
				(SELECT SUM(detalles_devolucion.Total) FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND ventas.FK_Cliente =  IDCliente GROUP BY ventas.FK_Cliente) AS Devuelto
				FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $busqueda GROUP BY FK_Cliente ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				$SumaCantidad = 0;
				$SumaTotales = 0;
				$SumaDevueltos = 0;
				
				for($i=0; $i<$numerofilas; $i++){
					$SumaCantidad += $row[$i]["Cantidad"];
					$SumaTotales += $row[$i]["Total"];
					$SumaDevueltos += $row[$i]["Devuelto"];

					$arreglo["data"][$i] = array(
						'ID' => $row[$i]["IDCliente"],
						'Cliente' => $row[$i]["Cliente"],
						'Cantidad' => number_format($row[$i]["Cantidad"], 2),
						'Total' => "$".number_format($row[$i]["Total"], 2),
						'Devuelto' => "$".number_format($row[$i]["Devuelto"], 2),
					);	
				}

				$arreglo['totales'] = array(
					'NumRows' => $cantidadRegistros, 
					'Cliente' => "",
					'Cantidad' => number_format($SumaCantidad, 2),
					'Total' => "$".number_format($SumaTotales, 2),
					'Devuelto' => "$".number_format($SumaDevueltos, 2),
				);	
			}
			echo json_encode($arreglo);
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 
		if($tipo == 'CalcularTotales'){
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);
			$sucursal = $omodelo->link->real_escape_string($sucursal);


			$query = "SELECT 
				FK_Cliente AS IDCliente, 
				(SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM clientes WHERE ID_Cliente = IDCliente) AS Cliente,
				COUNT(*) AS Cantidad, 
				SUM(ventas.Total) AS Total,
				(SELECT SUM(detalles_devolucion.Total) FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND ventas.FK_Cliente =  IDCliente GROUP BY ventas.FK_Cliente) AS Devuelto
				FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY FK_Cliente";
			$SumaTotales = 0;
			$SumaCantidad = 0;
			$SumaDevoluciones = 0;
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$SumaTotales += $row[$i]["Total"];
						$SumaCantidad += $row[$i]["Cantidad"];
						$SumaDevoluciones += $row[$i]["Devuelto"];
					}
				}
			}

			echo $SumaTotales."~".$SumaCantidad."~".$SumaDevoluciones;

		}
	}
}
?>
