<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=VentasPorUsuario.xls");

	$con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	//$con = new mysqli("localhost", "root", "", "wits_sistemaalex3");

	$fechaInicio =  $_GET["fechaInicio"];
	$fechaFin =  $_GET["fechaFin"];
	$sucursal =  $_GET["sucursal"];

	$tabla = "<table>
			<thead>
	  			<tr>
	                <th color:#000;  width: 200px;'>
	                	Usuario
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
	  			</tr>
	  		</thead>
	  		<tbody>";



	if($_GET["palabra"] != ''){
		$palabra = $_GET["palabra"];
		$separa = explode(' ', trim($palabra));
		$busqueda = ' AND ';
		for ($i=0; $i < count($separa); $i++) { 
			$busqueda .= "CONCAT(
				(SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM usuarios WHERE ID_Usuario = ventas.FK_Usuario)
			) REGEXP '".$separa[$i]."'";
			if($i < (count($separa)-1)){
				$busqueda .= ' AND ';
			}
		}
		

		$query = "SELECT 
				FK_Usuario AS IDUsuario, 
				(SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM usuarios WHERE ID_Usuario = IDUsuario) AS Usuario,
				COUNT(*) AS Cantidad, 
				SUM(ventas.Total) AS Total,
				(SELECT SUM(detalles_devolucion.Total) FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND ventas.FK_Usuario =  IDUsuario GROUP BY ventas.FK_Usuario) AS Devuelto
				FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN usuarios ON FK_Usuario = ID_Usuario WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $busqueda GROUP BY FK_Usuario";
	}else {
		$query = "SELECT 
				FK_Usuario AS IDUsuario, 
				(SELECT Nombre FROM usuarios WHERE ID_Usuario = IDUsuario) AS Usuario,
				COUNT(*) AS Cantidad, 
				SUM(ventas.Total) AS Total,
				(SELECT SUM(detalles_devolucion.Total) FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion INNER JOIN ventas ON FK_Venta = ID_Venta WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND ventas.FK_Usuario =  IDUsuario GROUP BY ventas.FK_Usuario) AS Devuelto
				FROM ventas INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal INNER JOIN usuarios ON FK_Usuario = ID_Usuario WHERE ventas.FK_Sucursal = '$sucursal' AND (ventas.Estatus = 'Completada' OR ventas.Estatus = 'Devuelta') AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') GROUP BY FK_Usuario";
	}
	
	if ($res = $con->query($query)) {
		if ($res->num_rows > 0) {
			while ($row = $res->fetch_assoc()) {
				
				if ($res->num_rows > 0) {
					$tabla .= '
						<tr>
							<td style="text-align:center;">
								' .utf8_encode(utf8_decode($row["Usuario"])).'   
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