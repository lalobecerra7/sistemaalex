<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=VentasPorProducto.xls");

	$con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	//$con = new mysqli("localhost", "root", "", "wits_sistemaalex3");

	$fechaInicio =  $_GET["fechaInicio"];
	$fechaFin =  $_GET["fechaFin"];
	$Sucursales =  $_GET["sucursales"];

	$tabla = "<table>
			<thead>
	  			<tr>
	  				<th color:#000;  width: 200px;'>
	                	Fecha
	                </th>
	  				<th color:#000;  width: 200px;'>
	                	Codigo
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Descripcion
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Cantidad
	                </th>
					<th color:#000;  width: 100px;'>
	                    Total
	                </th>
	                <th color:#000; width: 200px;'>
	                	Devuelto
	                </th>
	                <th color:#000; width: 200px;'>
	                	Sucursal
	                </th>
	  			</tr>
	  		</thead>
	  		<tbody>";



	if($_GET["palabra"] != ''){
		$qSucursales = "";
		$cadenaSucursales = "";
		$sucursales = json_decode($Sucursales, true);
		foreach ($sucursales as $sucursal) {
			$cadenaSucursales .= $sucursal["ID"].",";
		}
		$string = rtrim($cadenaSucursales, ",");
		if ($string != "") {
			$qSucursales = "AND ventas.FK_Sucursal IN(".$string.")";
		}


		$palabra = $_GET["palabra"];
		$separa = explode(' ', trim($palabra));
		$busqueda = ' AND ';
		for ($i=0; $i < count($separa); $i++) { 
			$busqueda .= "CONCAT(
				DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d'), 
				productos.Descripcion,
				IFNULL(presentaciones.Nombre, ''),
				IFNULL(presentaciones.Codigo, productos.Codigo)
			) REGEXP '".$separa[$i]."'";
			if($i < (count($separa)-1)){
				$busqueda .= ' AND ';
			}
		}
		

		$query = "SELECT DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') AS Fecha, sucursales.Nombre AS Sucursal, productos.Descripcion AS Descripcion,presentaciones.Nombre AS NombrePresentacion, productos.Codigo AS CodigoProducto, presentaciones.Codigo AS CodigoPresentacion, productos.ID_Producto AS IDProducto, IFNULL(presentaciones.ID_Presentacion, 0) AS IDPresentacion, IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo, SUM(detalles_ventas.Cantidad) AS Cantidad, SUM(detalles_ventas.Total) AS Total, 

		0 AS Devuelto

		FROM `detalles_ventas` INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $qSucursales $busqueda GROUP BY IDProducto, IDPresentacion, DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d')";
	}else {
		$qSucursales = "";
		$cadenaSucursales = "";
		$sucursales = json_decode($Sucursales, true);
		foreach ($sucursales as $sucursal) {
			$cadenaSucursales .= $sucursal["ID"].",";
		}
		$string = rtrim($cadenaSucursales, ",");
		if ($string != "") {
			$qSucursales = "AND inventario.FK_Sucursal IN(".$string.")";
		}

		$query = "SELECT DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') AS Fecha, sucursales.Nombre AS Sucursal, productos.Descripcion AS Descripcion,presentaciones.Nombre AS NombrePresentacion, productos.Codigo AS CodigoProducto, presentaciones.Codigo AS CodigoPresentacion, productos.ID_Producto AS IDProducto, IFNULL(presentaciones.ID_Presentacion, 0) AS IDPresentacion, IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo, SUM(detalles_ventas.Cantidad) AS Cantidad, SUM(detalles_ventas.Total) AS Total, 

		0 AS Devuelto

		FROM `detalles_ventas` INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $qSucursales GROUP BY IDProducto, IDPresentacion, DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d')";
	}
	
	if ($res = $con->query($query)) {
		if ($res->num_rows > 0) {
			while ($row = $res->fetch_assoc()) {
				
				if ($res->num_rows > 0) {
					$tabla .= '
						<tr>
							<td style="text-align:center;">
								' .$row["Fecha"]. '
							</td>
							<td style="text-align:center;">
								' .$row["Codigo"]. '
							</td>
							<td style="text-align:center;">
								' .utf8_encode(utf8_decode($row["Descripcion"]))."<br>".utf8_encode(utf8_decode($row["NombrePresentacion"])). '   
							</td>
							<td style="text-align:center;">
								' . number_format($row["Cantidad"], 2). '   
							</td>
							<td style="text-align:center;">
								' ."$".number_format($row["Total"], 2). '
							</td>
							<td style="text-align:center;">
								' ."$".number_format($row["Devuelto"], 2). ' 
							</td>
							<td style="text-align:center;">
								' .$row["Sucursal"]. ' 
							</td>
						</tr>';
				}
				
			}
		}
	} else {
		echo "Error 1: " . mysqli_error($con);
	}
	$tabla .= "
			</tbody>
		</table>";

	echo $tabla;
}

exit;
?>