<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=CatalogoClientes.xls");

    $con = mysqli_connect('localhost','miscelanearios_alex','Sistemaalex_2024','miscelanearios_sistemaalex2025');
	// $con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	// $con = new mysqli("localhost", "root", "", "miscelanearios_sistemaalex2025");

	$tabla = "<table border='1' style='border-collapse:collapse; width:100%;'>
			<thead style='background-color:#b2b2b2; font-weight:bold;'>
	  			<tr>
	  				<th color:#000;  width: 200px;'>
	                	Fecha
	                </th>
	  				<th color:#000;  width: 200px;'>
	                	Cliente
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Tipo de Persona
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Contacto
	                </th>
					<th color:#000;  width: 100px;'>
	                    Detalles
	                </th>
	                <th color:#000; width: 200px;'>
	                	Empresa
	                </th>
	                <th color:#000; width: 200px;'>
	                	Direccion fiscal
	                </th>
	                <th color:#000; width: 200px;'>
	                	Facturacion
	                </th>
                    <th color:#000; width: 200px;'>
	                	Sucursales
	                </th>
                    <th color:#000; width: 200px;'>
	                	Direcciones
	                </th>
                    <th color:#000; width: 200px;'>
	                	Rutas
	                </th>
	  			</tr>
	  		</thead>
	  		<tbody>";
    
    
            if($_GET["palabra"] != ''){
        
                $palabra = $_GET["palabra"];
                $separa = explode(' ', trim($palabra));
                $busqueda = ' WHERE ';
                for ($i=0; $i < count($separa); $i++) {
                    $busqueda .= "CONCAT(DATE_FORMAT(clientes.Fecha_Registro, '%Y-%m-%d'), LPAD(clientes.ID_Cliente, 4, '0'), clientes.Nombre, clientes.Primer_Apellido, clientes.Segundo_Apellido) REGEXP '".$separa[$i]."'";
                    if($i < (count($separa)-1)){
                        $busqueda .= ' AND ';
                    }
                }
                
	            $query = "SELECT LPAD(clientes.ID_Cliente, 4, '0') AS ID_Cliente, DATE_FORMAT(clientes.Fecha_Registro, '%d-%m-%Y') AS Fecha_Registro, clientes.Nombre AS Nombre_Cliente, clientes.Primer_Apellido AS ApellidoP_Cliente, clientes.Segundo_Apellido AS ApellidoM_Cliente, clientes.Tipo_Persona AS Tipo_Persona, clientes.Telefono AS Telefono, clientes.Celular AS Celular, clientes.Correo AS Correo, DATE_FORMAT(clientes.Fecha_Nacimiento, '%d-%m-%Y') AS Fecha_Nacimiento, clientes.Sexo AS Sexo, clientes.INE as INE, clientes.Nombre_Contacto AS Nombre_Contacto, clientes.Puesto_Contacto AS Puesto_Contacto, clientes.Email_Contacto AS Email_Contacto, clientes.Tel_Contacto AS Tel_Contacto, clientes.Calle AS Calle, clientes.No_Interior AS No_Interior, clientes.No_Exterior AS No_Exterior, clientes.Codigo_Postal AS Codigo_Postal, clientes.Colonia AS Colonia, clientes.Ciudad AS Ciudad, clientes.Estado AS Estado, clientes.Pais AS Pais, clientes.Razon_CFDI AS Razon_CFDI, clientes.Regimen_CFDI AS Regimen_CFDI, clientes.RFC AS RFC, rutas.Nombre AS Ruta, clientes.Orden_Ruta AS Orden_Ruta FROM clientes LEFT JOIN rutas ON clientes.FK_Ruta = rutas.ID_Ruta" .$busqueda. "";

            }else {

	            $query = "SELECT LPAD(clientes.ID_Cliente, 4, '0') AS ID_Cliente, DATE_FORMAT(clientes.Fecha_Registro, '%d-%m-%Y') AS Fecha_Registro, clientes.Nombre AS Nombre_Cliente, clientes.Primer_Apellido AS ApellidoP_Cliente, clientes.Segundo_Apellido AS ApellidoM_Cliente, clientes.Tipo_Persona AS Tipo_Persona, clientes.Telefono AS Telefono, clientes.Celular AS Celular, clientes.Correo AS Correo, DATE_FORMAT(clientes.Fecha_Nacimiento, '%d-%m-%Y') AS Fecha_Nacimiento, clientes.Sexo AS Sexo, clientes.INE as INE, clientes.Nombre_Contacto AS Nombre_Contacto, clientes.Puesto_Contacto AS Puesto_Contacto, clientes.Email_Contacto AS Email_Contacto, clientes.Tel_Contacto AS Tel_Contacto, clientes.Calle AS Calle, clientes.No_Interior AS No_Interior, clientes.No_Exterior AS No_Exterior, clientes.Codigo_Postal AS Codigo_Postal, clientes.Colonia AS Colonia, clientes.Ciudad AS Ciudad, clientes.Estado AS Estado, clientes.Pais AS Pais, clientes.Razon_CFDI AS Razon_CFDI, clientes.Regimen_CFDI AS Regimen_CFDI, clientes.RFC AS RFC, rutas.Nombre AS Ruta, clientes.Orden_Ruta AS Orden_Ruta FROM clientes LEFT JOIN rutas ON clientes.FK_Ruta = rutas.ID_Ruta";

            }
	
	if ($res = $con->query($query)) {
		if ($res->num_rows > 0) {

			while ($row = $res->fetch_assoc()) {
                $direcciones = "";
                $queryDirecciones = "SELECT detalles_clientes.ID_Detalle_Cliente AS ID_Detalle_Cliente, detalles_clientes.FK_Cliente AS FK_Cliente_Detalles_Cliente, detalles_clientes.Calle AS Calle_Detalles_Cliente, detalles_clientes.No_Interior AS No_Int_Detalles_Cliente, detalles_clientes.No_Exterior AS No_Ext_Detalles_Cliente, detalles_clientes.Codigo_Postal AS Cod_Pos_Detalles_Cliente, detalles_clientes.Colonia AS Colonia_Detalles_Cliente, detalles_clientes.Ciudad AS Ciudad_Detalles_Cliente, detalles_clientes.Estado AS Estado_Detalles_Cliente FROM detalles_clientes WHERE detalles_clientes.FK_Cliente = " .$row['ID_Cliente'];

                if ($resDirecciones = $con->query($queryDirecciones)) {
                    if ($resDirecciones->num_rows > 0) {
                        while ($rowDirecciones = $resDirecciones->fetch_assoc()){
                            $direcciones .= 'Calle: ' . $rowDirecciones['Calle_Detalles_Cliente'];
                            $direcciones .= '<br>Ext. ' . $rowDirecciones['No_Ext_Detalles_Cliente'];
                            if ($rowDirecciones['No_Int_Detalles_Cliente'] != '') {
                                $direcciones .= '<br>Int. ' . $rowDirecciones['No_Int_Detalles_Cliente'];
                            }
                            if ($rowDirecciones['Cod_Pos_Detalles_Cliente'] != '') {
                                $direcciones .= '<br>Cod. Postal: ' . $rowDirecciones['Cod_Pos_Detalles_Cliente'];
                            }
                            if ($rowDirecciones['Colonia_Detalles_Cliente'] != '') {
                                $direcciones .= '<br>Colonia: ' . $rowDirecciones['Colonia_Detalles_Cliente'];
                            }
                            if ($rowDirecciones['Ciudad_Detalles_Cliente'] != '') {
                                $direcciones .= '<br>Ciudad: ' . $rowDirecciones['Ciudad_Detalles_Cliente'];
                            }
                            if ($rowDirecciones['Estado_Detalles_Cliente'] != '') {
                                $direcciones .= '<br>Estado: ' . $rowDirecciones['Estado_Detalles_Cliente'] . '<br><br>';
                            }
                        }

                    }
                }

                $sucursales = "";
                $querySucursales = "SELECT detalles_clientes_sucursal.ID_Detalle_Cliente_Sucursal AS ID_Detalle_Cliente_Sucursal, detalles_clientes_sucursal.FK_Sucursal AS FK_Sucursal, sucursales.Nombre AS Nombre_Sucursal FROM detalles_clientes_sucursal INNER JOIN sucursales ON detalles_clientes_sucursal.FK_Sucursal = sucursales.ID_Sucursal WHERE detalles_clientes_sucursal.FK_Cliente = " . $row['ID_Cliente'];

                if($resSucursales = $con->query($querySucursales)){
                    if ($resSucursales->num_rows > 0) {
                        while ($rowSucursales = $resSucursales->fetch_assoc()) {
                            $sucursales .= $rowSucursales['Nombre_Sucursal'] . '<br>';
                        }
                    }
                }

                $tabla .= '
                    <tr>
                        <td style="text-align:center; vertical-align: middle;">
                            ' .$row["Fecha_Registro"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            ' .$row["Nombre_Cliente"]. ' ' .$row["ApellidoP_Cliente"]. ' ' .$row["ApellidoM_Cliente"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            ' .$row["Tipo_Persona"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            <br><br><br>Telefono: ' .$row["Telefono"]. ' <br>Celular: ' .$row["Celular"]. ' <br>Correo: ' .$row["Correo"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            <br><br><br>Fecha de nacimiento: ' .$row["Fecha_Nacimiento"]. ' <br>Sexo: ' .$row["Sexo"]. ' <br>INE: ' .$row["INE"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            <br><br><br>Contacto: ' .$row["Nombre_Contacto"]. ' <br>Puesto: ' .$row["Puesto_Contacto"]. '<br> Correo: ' .$row["Email_Contacto"]. ' <br>Telefono: ' .$row["Tel_Contacto"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            <br><br>Calle: ' .$row["Calle"]. ' <br>Num. Int.: ' .$row["No_Interior"]. ' <br>Num. Ext.: ' .$row["No_Exterior"]. ' <br>Cod. Postal: ' .$row["Codigo_Postal"]. ' <br>Colonia: ' .$row["Colonia"]. ' <br>Ciudad: ' .$row["Ciudad"]. '<br>Estado: ' .$row["Estado"]. ' <br>Pais: ' .$row["Pais"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            <br><br><br>Razon Social: ' .$row["Razon_CFDI"]. ' <br>Regimen Fiscal: ' .$row["Regimen_CFDI"]. ' <br>RFC: ' .$row["RFC"]. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            <br><br><br>' .$sucursales. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            ' .$direcciones. '
                        </td>
                        <td style="text-align:center; vertical-align: middle;">
                            <br><br><br><br>Ruta: ' .$row["Ruta"]. ' <br>Orden de ruta: ' .$row["Orden_Ruta"]. '
                        </td>
                    </tr>';
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