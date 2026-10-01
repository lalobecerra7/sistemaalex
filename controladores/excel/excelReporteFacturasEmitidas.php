<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ReporteFacturasEmitidas.xls");

    $con = mysqli_connect('localhost','miscelanearios_alex','Sistemaalex_2024','miscelanearios_sistemaalex2025');
	// $con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	// $con = new mysqli("localhost", "root", "", "miscelanearios_sistemaalex2025");

	$fechaInicio =  $_GET["fechaInicio"];
	$fechaFin =  $_GET["fechaFin"];
	$sucursal =  $_GET["sucursal"];

	$tabla = "<table>
			<thead>
	  			<tr>
	  				<th color:#000;  width: 200px;'>
	                	Folio
	                </th>
	  				<th color:#000;  width: 200px;'>
	                	UUID
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Fecha
	                </th>
	                <th color:#000;  width: 200px;'>
	                	RFC
	                </th>
					<th color:#000;  width: 100px;'>
	                    Cliente
	                </th>
	                <th color:#000; width: 200px;'>
	                	Importe
	                </th>
	                <th color:#000; width: 200px;'>
	                	Divisa
	                </th>
	                <th color:#000; width: 200px;'>
	                	Estatus
	                </th>
	  			</tr>
	  		</thead>
	  		<tbody>";
    
    
            if($_GET["palabra"] != ''){
        
                $palabra = $_GET["palabra"];
                $separa = explode(' ', trim($palabra));
                $busqueda = ' AND ';
                for ($i=0; $i < count($separa); $i++) { 
                    $busqueda .= "CONCAT(DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d'), LPAD(ventas.ID_Venta, 8, '0'), ventas.Receptor_Nombre_CFDI, ventas.Receptor_RFC_CFDI, ventas.Estatus) REGEXP '".$separa[$i]."'";
                    if($i < (count($separa)-1)){
                        $busqueda .= ' AND ';
                    }
                }
                
	            $query = "SELECT LPAD(ventas.ID_Venta, 8, '0') AS Folio, ventas.UUID_CFDI AS UUID_CFDI, DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') AS Fecha_Registro, ventas.Receptor_RFC_CFDI AS RFC_Cliente, ventas.Receptor_Nombre_CFDI AS Nombre_Cliente, ventas.Total AS Importe, ventas.Moneda_CFDI AS Divisa, ventas.Estatus AS Estatus, (SELECT COUNT(*) FROM ventas WHERE ventas.Facturada = 1 AND ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $busqueda) AS Num FROM ventas WHERE ventas.Facturada = 1 AND ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $busqueda";

            }else {

	            $query = "SELECT LPAD(ventas.ID_Venta, 8, '0') AS Folio, ventas.UUID_CFDI AS UUID_CFDI, DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') AS Fecha_Registro, ventas.Receptor_RFC_CFDI AS RFC_Cliente, ventas.Receptor_Nombre_CFDI AS Nombre_Cliente, ventas.Total AS Importe, ventas.Moneda_CFDI AS Divisa, ventas.Estatus AS Estatus, (SELECT COUNT(*) FROM ventas WHERE ventas.Facturada = 1 AND ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin')) AS Num FROM ventas WHERE ventas.Facturada = 1 AND ventas.FK_Sucursal = '$sucursal' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin')";

            }
	
	if ($res = $con->query($query)) {
		if ($res->num_rows > 0) {

            $totalImporte = 0;

			while ($row = $res->fetch_assoc()) {

                $totalImporte += $row['Importe'];
				
				if ($res->num_rows > 0) {
					$tabla .= '
						<tr>
							<td style="text-align:center;">
								' .$row["Folio"]. '
							</td>
							<td style="text-align:center;">
								' .$row["UUID_CFDI"]. '
							</td>
							<td style="text-align:center;">
								' .$row["Fecha_Registro"]. '
							</td>
							<td style="text-align:center;">
								' .$row["RFC_Cliente"]. '
							</td>
							<td style="text-align:center;">
								' .$row["Nombre_Cliente"]. '
							</td>
							<td style="text-align:center;">
								' . "$".number_format($row["Importe"], 2). '
							</td>
							<td style="text-align:center;">
								' .$row["Divisa"]. '
							</td>
							<td style="text-align:center;">
								' .$row["Estatus"]. '
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
                            <td> Total: $' .number_format($totalImporte, 2). '</td>
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