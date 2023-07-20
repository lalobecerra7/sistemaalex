<?php
class reportes {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);
		$arreglo = array();

		if($tipo == 'productos'){
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);

			$query = "SELECT ID_Detalle_Venta, FK_Presentacion, Imagen, productos.Descripcion AS Producto, Nombre_Unidad, Abreviatura_Unidad, Nombre, Abreviatura, (SUM(Cantidad) - IFNULL((SELECT SUM(Cantidad) FROM `detalles_devolucion` WHERE FK_Detalle_Venta = ID_Detalle_Venta), 0)) AS Cantidad FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN productos ON detalles_ventas.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY detalles_ventas.FK_Producto, FK_Presentacion ORDER BY Cantidad DESC LIMIT 10";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = 'vistas/assets/archivos/fotosProductos/default.jpg';

						if ($row[$i]["Imagen"] != "") {
							if($row[$i]["Imagen"] != "" && file_exists("vistas/assets/archivos/fotosProductos/".$row[$i]["Imagen"])){
								$foto = 'vistas/assets/archivos/fotosProductos/'.$row[$i]["Imagen"];
							}	
						}

						$presentacion = 'Sin presentación';
						$abreviatura = '';
						if($row[$i]['FK_Presentacion'] != '0'){
							if($row[$i]['Abreviatura'] != ""){
								$abreviatura = '('.$row[$i]['Abreviatura'].')';
							}
							$presentacion = $row[$i]['Nombre'].$abreviatura;
						}else{
							if($row[$i]['Nombre_Unidad'] != ""){
								if($row[$i]['Abreviatura_Unidad'] != ""){
									$abreviatura = '('.$row[$i]['Abreviatura_Unidad'].')';
								}
								$presentacion = $row[$i]['Nombre_Unidad'].$abreviatura;
							}
						}

						$arreglo[$i] = array('Foto' => $foto, 'Producto' => $row[$i]['Producto'].' '.$presentacion, 'Cantidad' => $row[$i]['Cantidad']);	
					}
				}
			}
		}else if($tipo == 'clientes'){
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);

			$query = "SELECT ID_Venta, Foto, Nombre, (SUM(Total) - IFNULL((SELECT SUM(Total) FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE FK_Venta = ID_Venta), 0)) AS Total FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE Estatus = 'Completada' AND Contar_Venta = 0 AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY FK_Cliente ORDER BY Total DESC LIMIT 10";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = 'vistas/assets/archivos/default.jpg';

						if ($row[$i]["Foto"] != "") {
							if($row[$i]["Foto"] != "" && file_exists("vistas/assets/archivos/fotosClientes/".$row[$i]["Foto"])){
								$foto = 'vistas/assets/archivos/fotosClientes/'.$row[$i]["Foto"];
							}	
						}

						$arreglo[$i] = array('Foto' => $foto, 'Cliente' => $row[$i]['Nombre'], 'Total' => $row[$i]['Total']);	
					}
				}
			}
		}else if($tipo == 'ventas'){
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);

			$query = "SELECT ID_Venta, DATE_FORMAT(Fecha_Registro, '%Y-%m-%d') AS Fecha, (SUM(Total) - IFNULL((SELECT SUM(Total) FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE FK_Venta = ID_Venta), 0)) AS Total FROM ventas WHERE Estatus = 'Completada' AND Contar_Venta = 0 AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY Fecha ORDER BY Fecha";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo[$i] = array('Fecha' => $row[$i]['Fecha'], 'Total' => $row[$i]['Total']);	
					}
				}
			}
		}else if($tipo == 'compras'){
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);

			$query = "SELECT ID_Compra, DATE_FORMAT(Fecha_Registro, '%Y-%m-%d') AS Fecha, SUM(Total) AS Total FROM compras WHERE (DATE_FORMAT(compras.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(compras.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY Fecha ORDER BY Fecha";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo[$i] = array('Fecha' => $row[$i]['Fecha'], 'Total' => $row[$i]['Total']);	
					}
				}
			}
		}else if($tipo == 'finanzas'){
			$fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
			$fechaFin = $omodelo->link->real_escape_string($fechaFin);

			$query = "SELECT ID_Compra, DATE_FORMAT(Fecha_Registro, '%Y-%m') AS Fecha, SUM(Total) AS Total FROM compras WHERE ( DATE_FORMAT(Fecha_Registro, '%Y-%m') >= '$fechaInicio' AND  DATE_FORMAT(Fecha_Registro, '%Y-%m') <= '$fechaFin') GROUP BY Fecha ORDER BY Fecha";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['Compras'][$i] = array('Fecha' => $row[$i]['Fecha'], 'Total' => $row[$i]['Total']);	
					}
				}
			}

			$query = "SELECT ID_Venta, DATE_FORMAT(Fecha_Registro, '%Y-%m') AS Fecha, (SUM(Total) - IFNULL((SELECT SUM(Total) FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE FK_Venta = ID_Venta), 0)) AS Total FROM ventas WHERE Estatus = 'Completada' AND Contar_Venta = 0 AND (DATE_FORMAT(Fecha_Registro, '%Y-%m') >= '$fechaInicio' AND DATE_FORMAT(Fecha_Registro, '%Y-%m') <= '$fechaFin') GROUP BY Fecha ORDER BY Fecha";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo['Ventas'][$i] = array('Fecha' => $row[$i]['Fecha'], 'Total' => $row[$i]['Total']);


						/*
						$query2 = "SELECT SUM(Total) AS TotalDevolucion FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE FK_Venta = '".$row[$i]['ID_Venta']."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;

						if($row2 == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								$TotalDevolucion = $row2[0]["TotalDevolucion"];
							}
						}

						$Total = $row[$i]['Total'] - $TotalDevolucion;*/	
					}
				}
			}
		}

		echo json_encode($arreglo);
	}
}
?>
