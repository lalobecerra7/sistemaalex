<?php
include '../../modelo/m_modelo.php';
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ReporteInventario.xls");
	$omodelo = new m_modelo();
	
	$tabla = "<table>";


	// Consultar sucursales.
    $querySucursales = "SELECT ID_Sucursal, Nombre FROM sucursales";
    $resultSucursales = $omodelo->_consultar($querySucursales);
   	$numerofilasSucursales = $omodelo->numerofilas;
        
    $sucursalesColumnas = [];
    for($i = 0; $i < $numerofilasSucursales; $i++){
       	$sucursalesColumnas[] = "SUM(CASE WHEN FK_Sucursal = {$resultSucursales[$i]['ID_Sucursal']} THEN Cantidad ELSE 0 END) AS `{$resultSucursales[$i]['Nombre']}`";
    }
    // echo $numerofilasSucursales;
    
        
    $columnasPivot = implode(", ", $sucursalesColumnas);

    $query = "SELECT 
        IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo,
        IFNULL(presentaciones.Costo, productos.Costo) AS Costo,
        IFNULL(presentaciones.Codigo, '') AS Cod_Presentacion,
        IFNULL(productos.Codigo, '') AS Cod_Producto,
        Descripcion,
        IFNULL(presentaciones.Nombre, 'Sin presentación') AS Nombre_Presentacion,
        $columnasPivot,
        SUM(cantidad) AS Total_Existencias,
        IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor') AS Proveedor
    	
    	FROM inventario
        INNER JOIN productos ON FK_Producto = ID_Producto
        LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion
        LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto
        GROUP BY Codigo, Cod_Presentacion, Cod_Producto, Descripcion, Nombre_Presentacion, Proveedor";
        // echo $query;
        $row = $omodelo->_consultar($query);
        $numerofilas = $omodelo->numerofilas;
        $arreglo = [];
    
        if ($row == 'si') {
            echo "Error: ".mysqli_error($omodelo->link);
        } else {
            if ($numerofilas > 0) {
                $trTabla = '';
                for ($i=0; $i<$numerofilas; $i++) {
                    $sumaTotalesSucursales = 0;
                    $trSucursales = '';
                    $trTabla .= '<tr>
                    <td>'.$row[$i]["Codigo"].'</td>
                    <td>'.$row[$i]["Descripcion"]. ' (' .$row[$i]["Nombre_Presentacion"]. ')</td>
                    <td>$'.$row[$i]["Costo"].'</td>
                    <td>'.$row[$i]["Proveedor"].'</td>';
                    
                    for ($j = 0; $j < $numerofilasSucursales; $j++) {
                        $trSucursales .= '<td>'.$row[$i][$resultSucursales[$j]['Nombre']].'</td>';
                        $sumaTotalesSucursales += $row[$i][$resultSucursales[$j]['Nombre']];
                    }
                    
                    $costoTotal = $row[$i]["Costo"] * $sumaTotalesSucursales;
                    $trTabla .= '<td>' .$sumaTotalesSucursales. '</td>' . $trSucursales. '<td>$'.$costoTotal.'</td></tr>';
                }
            }
            
            $arreglo['sucursales'] = [];
            $thSucursales = '<tr><th>Codigo</th>
		                        <th>Descripción</th>
								<th>Costo Neto</th>
		                        <th>Proveedor</th>
		                        <th>Existencias totales</th>';
            foreach ($resultSucursales as $sucursal) {
                if (!is_null($sucursal) && isset($sucursal["Nombre"])) {
                    $thSucursales .= '<th>'.$sucursal["Nombre"].'</th>';
                }
            }
            $thSucursales .= '<th>Costo total</th></tr>';
        }
	
	$tabla .= "
			<thead>
				".$thSucursales."
			</thead>
			<tbody>
				".$trTabla."
			</tbody>
		</table>";

	echo $tabla;
}

exit;
?>