<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=VentasPorDia.xls");

	$con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	//$con = new mysqli("localhost", "root", "", "wits_sistemaalex3");

	$fechaInicio =  $_GET["fechaInicio"];
	$fechaFin =  $_GET["fechaFin"];
	$sucursal =  $_GET["sucursal"];

	$tabla = "<table>
			<thead>
	  			<tr>
	  				<th color:#000;  width: 200px;'>
	                	Fecha
	                </th>
	  				<th color:#000;  width: 200px;'>
	                	Sucursal
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Importes
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Descuentos
	                </th>
					<th color:#000;  width: 100px;'>
	                    Subtotal
	                </th>
	                <th color:#000; width: 200px;'>
	                	Impuestos
	                </th>
	                <th color:#000; width: 200px;'>
	                	Ventas
	                </th>
	                <th color:#000; width: 200px;'>
	                	Devoluciones
	                </th>
	  			</tr>
	  		</thead>
	  		<tbody>";



	$query = "SELECT DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') AS Fecha, sucursales.Nombre AS Sucursal, SUM(Descuento) AS Descuento, SUM(Total) AS Total, SUM(Total_Importes) AS Importes, (SELECT SUM(Precio * Cantidad) AS Subtotal FROM detalles_ventas INNER JOIN ventas ON FK_Venta = ID_Venta WHERE (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND ventas.FK_Sucursal = '$sucursal' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') = Fecha) AS Subtotal,

		(SELECT SUM(detalles_devolucion.Total) AS TotalDevolucion FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND DATE_FORMAT(devoluciones.Fecha_Registro, '%Y-%m-%d') = Fecha) AS Devoluciones

		 FROM ventas INNER JOIN sucursales ON ventas.FK_Sucursal = ID_Sucursal WHERE (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d')";
	
	
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
								' .$row["Sucursal"]. '
							</td>
							<td style="text-align:center;">
								' ."$".number_format($row["Importes"], 2). '
							</td>
							<td style="text-align:center;">
								' ."$".number_format($row["Descuento"], 2). '
							</td>
							<td style="text-align:center;">
								' ."$".number_format($row["Subtotal"], 2). '
							</td>
							<td style="text-align:center;">
								' .'0'. '
							</td>
							<td style="text-align:center;">
								' . "$".number_format($row["Total"], 2). '
							</td>
							<td style="text-align:center;">
								' ."$".number_format($row["Devoluciones"], 2). '
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