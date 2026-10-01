<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ExcelExportarCompras.xls");

    $con = mysqli_connect('localhost','miscelanearios_alex','Sistemaalex_2024','miscelanearios_sistemaalex2025');
	// $con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	// $con = new mysqli("localhost", "root", "", "miscelanearios_sistemaalex2025");

	$tabla = "<table border='1' style='border-collapse:collapse; width:100%;'>
			<thead>
	  			<tr>
	  				<th color:#000;  width: 200px;'>
	                	Folio
	                </th>
	  				<th color:#000;  width: 200px;'>
	                	Fecha
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Sucursal
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Datos del proveedor
	                </th>
					<th color:#000;  width: 100px;'>
	                    Tipo de pago
	                </th>
	                <th color:#000; width: 200px;'>
	                	Total
	                </th>
	                <th color:#000; width: 200px;'>
	                	Monto pagado
	                </th>
                    <th color:#000; width: 200px;'>
	                	Restante
	                </th>
                    <th color:#000; width: 200px;'>
	                	A favor
	                </th>
                    <th color:#000; width: 200px;'>
	                	Estatus
	                </th>
	                <th color:#000; width: 200px;'>
	                	Usuario
	                </th>
	  			</tr>
	  		</thead>
	  		<tbody>";
    
    
            if($_GET["palabra"] != ''){
        
                $palabra = $_GET["palabra"];
                $separa = explode(' ', trim($palabra));
                $busqueda = ' WHERE ';
                for ($i=0; $i < count($separa); $i++) { 
                    $busqueda .= "CONCAT(DATE_FORMAT(compras.Fecha_Registro, '%d-%m-%Y'), LPAD(compras.ID_Compra, 8, '0'), sucursales.Nombre, proveedores.Nombre, proveedores.Empresa, proveedores.Razon_Social, proveedores.Telefono, compras.Tipo_Compra, compras.Estatus, usuarios.Nombre, usuarios.Primer_Apellido, usuarios.Segundo_Apellido) REGEXP '".$separa[$i]."'";
                    if($i < (count($separa)-1)){
                        $busqueda .= ' AND ';
                    }
                }
                
                $query = "SELECT LPAD(compras.ID_Compra, 8, '0') AS ID_Compra, DATE_FORMAT(compras.Fecha_Registro, '%d-%m-%Y') AS Fecha_Registro, sucursales.Nombre AS Sucursal, proveedores.Nombre AS Nombre_Proveedor, proveedores.Empresa AS Empresa_Proveedor, compras.Tipo_Compra AS Tipo_Pago, compras.Total AS Total, IFNULL((SELECT SUM(pagos.Monto) FROM pagos WHERE pagos.FK_Compra = compras.ID_Compra), 0) AS Monto_Pagado, compras.Estatus AS Estatus, usuarios.Nombre AS Nombre_Usuario, usuarios.Primer_Apellido AS ApellidoP_Usuario, usuarios.Segundo_Apellido AS ApellidoM_Usuario FROM compras LEFT JOIN sucursales ON compras.FK_Sucursal = sucursales.ID_Sucursal LEFT JOIN proveedores ON compras.FK_Proveedor = proveedores.ID_Proveedor LEFT JOIN usuarios ON compras.FK_Usuario = usuarios.ID_Usuario" .$busqueda. "";

            }else {

                $query = "SELECT LPAD(compras.ID_Compra, 8, '0') AS ID_Compra, DATE_FORMAT(compras.Fecha_Registro, '%d-%m-%Y') AS Fecha_Registro, sucursales.Nombre AS Sucursal, proveedores.Nombre AS Nombre_Proveedor, proveedores.Empresa AS Empresa_Proveedor, compras.Tipo_Compra AS Tipo_Pago, compras.Total AS Total, IFNULL((SELECT SUM(pagos.Monto) FROM pagos WHERE pagos.FK_Compra = compras.ID_Compra), 0) AS Monto_Pagado, compras.Estatus AS Estatus, usuarios.Nombre AS Nombre_Usuario, usuarios.Primer_Apellido AS ApellidoP_Usuario, usuarios.Segundo_Apellido AS ApellidoM_Usuario FROM compras LEFT JOIN sucursales ON compras.FK_Sucursal = sucursales.ID_Sucursal LEFT JOIN proveedores ON compras.FK_Proveedor = proveedores.ID_Proveedor LEFT JOIN usuarios ON compras.FK_Usuario = usuarios.ID_Usuario";

            }


	if ($res = $con->query($query)) {

		if ($res->num_rows > 0) {

            $totales = 0;
			while ($row = $res->fetch_assoc()) {

                $totales += $row['Total'];
				
				if ($res->num_rows > 0) {

                    $estatus = "";

                    if($row["Estatus"] == 0) {
                        $estatus = "Pendiente";
                    } else if($row["Estatus"] == 1) {
                        $estatus = "Completada";
                    }else if($row["Estatus"] == 2){
                        $estatus = "Cancelada";
                    }

                    $saldoAFavor = 0;
                    $saldoRestante = $row["Total"] - $row["Monto_Pagado"];
                    if($saldoRestante < 0) {
                        $saldoAFavor = $saldoRestante * -1;
						$saldoRestante = 0;
                    }

					$tabla .= '
						<tr>
							<td style="text-align:center; vertical-align: middle;">
								' .$row["ID_Compra"]. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								' .$row["Fecha_Registro"]. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								' .$row["Sucursal"]. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								Nombre: <b>' .$row["Nombre_Proveedor"]. '</b><br>Empresa: <b>' .$row["Empresa_Proveedor"]. '</b>
							</td>
							<td style="text-align:center; vertical-align: middle;">
								' .$row["Tipo_Pago"]. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Total"], 2). '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Monto_Pagado"], 2). '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								$' .number_format($saldoRestante, 2). '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								$' .number_format($saldoAFavor, 2). '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								' .$estatus. '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								' .$row["Nombre_Usuario"]. ' ' .$row["ApellidoP_Usuario"]. ' ' .$row["ApellidoM_Usuario"]. '
							</td>
						</tr>';
				}
				
			}
            $tabla .= '<tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> Total: $' .number_format($totales, 2). '</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
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