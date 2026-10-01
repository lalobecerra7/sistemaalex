<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ExcelExportarVentas.xls");

    $con = mysqli_connect('localhost','miscelanearios_alex','Sistemaalex_2024','miscelanearios_sistemaalex2025');
	// $con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	// $con = new mysqli("localhost", "root", "", "miscelanearios_sistemaalex2025");

	$fechaInicio =  $_GET["RangoFechaInicialVenta"];
	$fechaFin =  $_GET["RangoFechaFinalVenta"];
    $cliente = $_GET["ClienteVenta"];
    $qSucursales = "";

    // Traer las sucursales seleccionadas del modal filtro de sucursales.
    $cadenaSucursales = "";
    $sucursalesSelectReporte = json_decode($_GET["SucursalesSelectVenta"], true);
    foreach ($sucursalesSelectReporte as $sucursal) {
        if ($sucursal["ID"] == "todas") {
            $cadenaSucursales = "";
        } else {
            $cadenaSucursales .= $sucursal["ID"].",";
        }
    }
    $string = rtrim($cadenaSucursales, ",");
    if ($string != "") {
        $qSucursales = "WHERE sucursales.ID_Sucursal IN(".$string.") AND";
    } else {
        $qSucursales = "WHERE";
    }

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
	                	Datos del cliente
	                </th>
					<th color:#000;  width: 100px;'>
	                    Pago
	                </th>
	                <th color:#000; width: 200px;'>
	                	Subtotal
	                </th>
	                <th color:#000; width: 200px;'>
	                	Descuento
	                </th>
                    <th color:#000; width: 200px;'>
	                	Total de venta
	                </th>
                    <th color:#000; width: 200px;'>
	                	Cambio
	                </th>
                    <th color:#000; width: 200px;'>
	                	Total de importes
	                </th>
                    <th color:#000; width: 200px;'>
	                	Facturada
	                </th>
                    <th color:#000; width: 200px;'>
	                	Estatus
	                </th>
                    <th color:#000; width: 200px;'>
	                	Tipo de pago
	                </th>
                    <th color:#000; width: 200px;'>
	                	Monto
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
                $busqueda = ' AND ';
                for ($i=0; $i < count($separa); $i++) { 
                    $busqueda .= "CONCAT(DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d'), LPAD(ventas.ID_Venta, 8, '0'), clientes.Nombre, clientes.Primer_Apellido, clientes.Segundo_Apellido, clientes.RFC, ventas.Estatus, ventas.Tipo_Pago) REGEXP '".$separa[$i]."'";
                    if($i < (count($separa)-1)){
                        $busqueda .= ' AND ';
                    }
                }
                
                $query = "SELECT LPAD(ventas.ID_Venta, 8, '0') AS ID_Venta, DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y') AS Fecha_Registro, sucursales.Nombre AS Sucursal, usuarios.Nombre AS Nombre_Usuario, usuarios.Primer_Apellido AS ApellidoP_Usuario, usuarios.Segundo_Apellido AS ApellidoM_Usuario, clientes.Nombre AS Nombre_Cliente, clientes.Primer_Apellido AS ApellidoP_Cliente, clientes.Segundo_Apellido AS ApellidoM_Cliente, clientes.Telefono AS Telefono_Cliente, clientes.Correo AS Correo_Cliente, clientes.RFC AS RFC_Cliente, detalles_clientes.Calle AS Calle_Detalle_Cliente, detalles_clientes.No_Interior AS No_Int_Detalle_Cliente, detalles_clientes.No_Exterior AS No_Ext_Detalle_Cliente, detalles_clientes.Codigo_Postal AS Cod_Postal_Detalle_Cliente, detalles_clientes.Colonia AS Colonia_Detalle_Cliente, detalles_clientes.Ciudad AS Ciudad_Detalle_Cliente, detalles_clientes.Estado AS Estado_Detalle_Cliente, detalles_clientes.Pais AS Pais_Detalle_Cliente, ventas.Pago AS Pago_Cliente, ventas.Descuento AS Descuento_Venta, ventas.Total AS Total_Venta, ventas.Cambio AS Cambio_Venta, ventas.Total_Importes AS Total_Importes_Venta, ventas.Facturada AS Venta_Facturada, ventas.Estatus AS Estatus_Venta, ventas.Tipo_Pago AS Tipo_Pago, ventas.Pago_Efectivo AS Pago_Efectivo, ventas.Pago_Transferencia AS Pago_Transferencia, ventas.Pago_Cheque AS Pago_Cheque, ventas.Pago_Tarjeta_Credito AS Pago_Tarjeta_Credito, ventas.Pago_Tarjeta_Debito AS Pago_Tarjeta_Debito, (SELECT COUNT(*) FROM ventas $qSucursales ventas.FK_Cliente = '$cliente' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') $busqueda) AS Num FROM ventas LEFT JOIN sucursales ON ventas.FK_Sucursal = sucursales.ID_Sucursal LEFT JOIN clientes ON ventas.FK_Cliente = clientes.ID_Cliente LEFT JOIN usuarios ON ventas.FK_Usuario = usuarios.ID_Usuario LEFT JOIN detalles_clientes ON ventas.FK_Cliente = detalles_clientes.FK_Cliente $qSucursales ventas.FK_Cliente = '$cliente' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin')" . $busqueda . "";

            }else {

                $query = "SELECT LPAD(ventas.ID_Venta, 8, '0') AS ID_Venta, DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y') AS Fecha_Registro, sucursales.Nombre AS Sucursal, usuarios.Nombre AS Nombre_Usuario, usuarios.Primer_Apellido AS ApellidoP_Usuario, usuarios.Segundo_Apellido AS ApellidoM_Usuario, clientes.Nombre AS Nombre_Cliente, clientes.Primer_Apellido AS ApellidoP_Cliente, clientes.Segundo_Apellido AS ApellidoM_Cliente, clientes.Telefono AS Telefono_Cliente, clientes.Correo AS Correo_Cliente, clientes.RFC AS RFC_Cliente, detalles_clientes.Calle AS Calle_Detalle_Cliente, detalles_clientes.No_Interior AS No_Int_Detalle_Cliente, detalles_clientes.No_Exterior AS No_Ext_Detalle_Cliente, detalles_clientes.Codigo_Postal AS Cod_Postal_Detalle_Cliente, detalles_clientes.Colonia AS Colonia_Detalle_Cliente, detalles_clientes.Ciudad AS Ciudad_Detalle_Cliente, detalles_clientes.Estado AS Estado_Detalle_Cliente, detalles_clientes.Pais AS Pais_Detalle_Cliente, ventas.Pago AS Pago_Cliente, ventas.Descuento AS Descuento_Venta, ventas.Total AS Total_Venta, ventas.Cambio AS Cambio_Venta, ventas.Total_Importes AS Total_Importes_Venta, ventas.Facturada AS Venta_Facturada, ventas.Estatus AS Estatus_Venta, ventas.Tipo_Pago AS Tipo_Pago, ventas.Pago_Efectivo AS Pago_Efectivo, ventas.Pago_Transferencia AS Pago_Transferencia, ventas.Pago_Cheque AS Pago_Cheque, ventas.Pago_Tarjeta_Credito AS Pago_Tarjeta_Credito, ventas.Pago_Tarjeta_Debito AS Pago_Tarjeta_Debito, (SELECT COUNT(*) FROM ventas $qSucursales ventas.FK_Cliente = '$cliente' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin')) AS Num FROM ventas LEFT JOIN sucursales ON ventas.FK_Sucursal = sucursales.ID_Sucursal LEFT JOIN clientes ON ventas.FK_Cliente = clientes.ID_Cliente LEFT JOIN usuarios ON ventas.FK_Usuario = usuarios.ID_Usuario LEFT JOIN detalles_clientes ON ventas.FK_Cliente = detalles_clientes.FK_Cliente $qSucursales ventas.FK_Cliente = '$cliente' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaInicio' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin')";

            }

            // echo $query;
	if ($res = $con->query($query)) {

		if ($res->num_rows > 0) {

            $totales = 0;
			while ($row = $res->fetch_assoc()) {

                $totales += $row['Total_Venta'];
				
				if ($res->num_rows > 0) {

                    $direccionCliente = "</b><br><br>Direccion:";

                    if($row["Calle_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>Calle: <b>" . $row["Calle_Detalle_Cliente"] . "</b>";
                    }

                    if($row["No_Int_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>No. Int.: <b>" . $row["No_Int_Detalle_Cliente"] . "</b>";
                    }

                    if($row["No_Ext_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>No. Ext.: <b>" . $row["No_Ext_Detalle_Cliente"] . "</b>";
                    }

                    if($row["Colonia_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>Colonia: <b>" . $row["Colonia_Detalle_Cliente"] . "</b>";
                    }

                    if($row["Cod_Postal_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>C.P.: <b>" . $row["Cod_Postal_Detalle_Cliente"] . "</b>";
                    }

                    if($row["Ciudad_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>Ciudad: <b>" . $row["Ciudad_Detalle_Cliente"] . "</b>";
                    }

                    if($row["Estado_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>Estado: <b>" . $row["Estado_Detalle_Cliente"] . "</b>";
                    }

                    if($row["Pais_Detalle_Cliente"] != ""){
                        $direccionCliente .= "<br>Pais: <b>" . $row["Pais_Detalle_Cliente"] . "</b>";
                    }

                    if($row["Calle_Detalle_Cliente"] == "" && $row["No_Int_Detalle_Cliente"] == "" && $row["No_Ext_Detalle_Cliente"] == "" && $row["Colonia_Detalle_Cliente"] == "" && $row["Cod_Postal_Detalle_Cliente"] == "" && $row["Ciudad_Detalle_Cliente"] == "" && $row["Estado_Detalle_Cliente"] == "" && $row["Pais_Detalle_Cliente"] == ""){
                        $direccionCliente .= "<br><b>Sin direccion</b>";
                    }

                    $ventaFacturada = "";

                    if($row["Venta_Facturada"] == 1) {
                        $ventaFacturada = "Facturada";
                    } else {
                        $ventaFacturada = "No Facturada";
                    }


                    $montoTipoPago = "";

                    if($row["Pago_Efectivo"] > 0){
                        $montoTipoPago .= "Efectivo: $" . number_format($row["Pago_Efectivo"], 2) . "<br>";
                    }

                    if($row["Pago_Transferencia"] > 0){
                        $montoTipoPago .= "Transferencia: $" . number_format($row["Pago_Transferencia"], 2) . "<br>";
                    }

                    if($row["Pago_Cheque"] > 0){
                        $montoTipoPago .= "Cheque: $" . number_format($row["Pago_Cheque"], 2) . "<br>";
                    }

                    if($row["Pago_Tarjeta_Credito"] > 0){
                        $montoTipoPago .= "Tarjeta de credito: $" . number_format($row["Pago_Tarjeta_Credito"], 2) . "<br>";
                    }

                    if($row["Pago_Tarjeta_Debito"] > 0){
                        $montoTipoPago .= "Tarjeta de debito: $" . number_format($row["Pago_Tarjeta_Debito"], 2);
                    }

					$tabla .= '
						<tr>
							<td style="text-align:center; vertical-align: middle;">
								' .$row["ID_Venta"]. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								' .$row["Fecha_Registro"]. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								' .$row["Sucursal"]. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								<br><br>Nombre: <b>' .$row["Nombre_Cliente"]. '</b><b> ' .$row["ApellidoP_Cliente"]. '</b><b> ' .$row["ApellidoM_Cliente"]. '</b><br>Telefono: <b>' .$row["Telefono_Cliente"]. '</b><br>Correo: <b>' .$row["Correo_Cliente"]. '</b><br>RFC: <b>' .$row["RFC_Cliente"].$direccionCliente. '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Pago_Cliente"], 2). '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Total_Venta"] + $row["Descuento_Venta"], 2). '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Descuento_Venta"], 2). '
							</td>
							<td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Total_Venta"], 2). '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Cambio_Venta"], 2). '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								$' .number_format($row["Total_Importes_Venta"], 2). '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								' .$ventaFacturada. '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								' .$row["Estatus_Venta"]. '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								' .$row["Tipo_Pago"]. '
							</td>
                            <td style="text-align:center; vertical-align: middle;">
								<br>' .$montoTipoPago. '
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