<?php
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ReporteInventario.xls");

    $con = mysqli_connect('localhost','miscelanearios_alex','Sistemaalex_2024','miscelanearios_sistemaalex2025');
	// $con = new mysqli("localhost", "wits_userBD", "ZfX7y99GSs", "wits_sistemaalex");
	//$con = new mysqli("localhost", "root", "", "wits_sistemaalex3");

	$Sucursales =  $_GET["sucursales"];
	$Proveedor =  $_GET["proveedor"];

	$tabla = "<table>
			<thead>
	  			<tr>
	                <th color:#000;  width: 200px;'>
	                	Codigo
	                </th>
	                <th color:#000;  width: 200px;'>
	                	Descripcion
	                </th>
					<th color:#000;  width: 100px;'>
	                    Existencia
	                </th>
	                <th color:#000; width: 200px;'>
	                	Proveedor
	                </th>
	                <th color:#000; width: 200px;'>
	                	Sucursal
	                </th>
	  			</tr>
	  		</thead>
	  		<tbody>";



	if($_GET["palabra"] != ''){

		$qWhere = "";
		$qAnd = "";
		$qSucursales = "";
		$qProveedor = "";

		if ($Proveedor != "") {
			$qProveedor = "FK_Proveedor = '$Proveedor'";
		}

		$cadenaSucursales = "";
		$sucursales = json_decode($Sucursales, true);
		foreach ($sucursales as $sucursal) {
			$cadenaSucursales .= $sucursal["ID"].",";
		}
		$string = rtrim($cadenaSucursales, ",");
		if ($string != "") {
			$qSucursales = "inventario.FK_Sucursal IN(".$string.")";
		}
		
		if ($qSucursales != "" || $qProveedor != "") {
			$qWhere = "WHERE";
		}

		if ($qSucursales != "" && $qProveedor != "") {
			$qAnd = " AND ";
		}


		$palabra = $_GET["palabra"];
		$separa = explode(' ', trim($palabra));
		$busqueda = ' WHERE ';
		for ($i=0; $i < count($separa); $i++) { 
			$busqueda .= "CONCAT(IFNULL(presentaciones.Codigo, productos.Codigo), Descripcion, Cantidad, IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor'), (SELECT Nombre FROM sucursales WHERE FK_Sucursal = ID_Sucursal)) REGEXP '".$separa[$i]."'";
			if($i < (count($separa)-1)){
				$busqueda .= ' AND ';
			}
		}
		

		$query = "SELECT IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo, presentaciones.Codigo AS CodPresentacion, productos.Codigo AS CodProducto, Descripcion, IFNULL(presentaciones.Nombre, '') AS NombrePresentacion, Cantidad AS Existencia, (SELECT Nombre FROM sucursales WHERE FK_Sucursal = ID_Sucursal) AS Sucursal, IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor') AS Proveedor, FK_Sucursal, FK_Proveedor, (SELECT COUNT(*) FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto $busqueda $qWhere $qProveedor $qAnd $qSucursales) AS Num FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto $busqueda $qWhere $qProveedor $qAnd $qSucursales";
	}else {

		$qWhere = "";
		$qAnd = "";
		$qSucursales = "";
		$qProveedor = "";

		if ($Proveedor != "") {
			$qProveedor = "FK_Proveedor = '$Proveedor'";
		}

		$cadenaSucursales = "";
		$sucursales = json_decode($Sucursales, true);
		foreach ($sucursales as $sucursal) {
			$cadenaSucursales .= $sucursal["ID"].",";
		}
		$string = rtrim($cadenaSucursales, ",");
		if ($string != "") {
			$qSucursales = "inventario.FK_Sucursal IN(".$string.")";
		}
		
		if ($qSucursales != "" || $qProveedor != "") {
			$qWhere = "WHERE";
		}

		if ($qSucursales != "" && $qProveedor != "") {
			$qAnd = " AND ";
		}

		$query = "SELECT IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo, presentaciones.Codigo AS CodPresentacion, productos.Codigo AS CodProducto, Descripcion, IFNULL(presentaciones.Nombre, '') AS NombrePresentacion, Cantidad AS Existencia, (SELECT Nombre FROM sucursales WHERE FK_Sucursal = ID_Sucursal) AS Sucursal, IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor') AS Proveedor, FK_Sucursal, FK_Proveedor, (SELECT COUNT(*) FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto $qWhere $qProveedor $qAnd $qSucursales) AS Num FROM inventario INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto $qWhere $qProveedor $qAnd $qSucursales";
	}
	
	if ($res = $con->query($query)) {
		if ($res->num_rows > 0) {
			while ($row = $res->fetch_assoc()) {
				
				if ($res->num_rows > 0) {
					$tabla .= '
						<tr>
							<td style="text-align:center;">
								' .$row["Codigo"].'   
							</td>
							<td style="text-align:center;">
								' .utf8_encode(utf8_decode($row["Descripcion"])). '   
								<br>
								' .utf8_encode(utf8_decode($row["NombrePresentacion"])). '   
							</td>
							<td style="text-align:center;">
								' . $row["Existencia"]. '
							</td>
							<td style="text-align:center;">
								' . $row["Proveedor"]. ' 
							</td>
							<td style="text-align:center;">
								' . $row["Sucursal"]. ' 
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