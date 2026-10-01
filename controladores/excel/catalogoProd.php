<?php
include '../../modelo/m_modelo.php';
session_start();

if (isset($_SESSION['user_admin'])) {
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=catalogo.xls");
	$omodelo = new m_modelo();
	
	$tabla = "<table>";

    $query = "SELECT 
        IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo,
        IFNULL(presentaciones.Costo, productos.Costo) AS Costo,
        IFNULL(presentaciones.Codigo, '') AS Cod_Presentacion,
        IFNULL(productos.Codigo, '') AS Cod_Producto,
        Descripcion,
        IFNULL(presentaciones.Nombre, 'Sin presentación') AS Nombre_Presentacion,
        IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor') AS Proveedor,
    	IFNULL((SELECT Nombre FROM areas WHERE ID_Area = productos.FK_Area), '') AS Area,
        IFNULL((SELECT Nombre FROM categorias WHERE ID_Categoria = productos.FK_Categoria), '') AS Familia
    	FROM inventario
        INNER JOIN productos ON FK_Producto = ID_Producto
        LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion
        LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto
        GROUP BY Codigo, Cod_Presentacion, Cod_Producto, Descripcion, Nombre_Presentacion, Proveedor";
        $row = $omodelo->_consultar($query);
        $numerofilas = $omodelo->numerofilas;
        $arreglo = [];
    
        if ($row == 'si') {
            echo "Error: ".mysqli_error($omodelo->link);
        } else {
            if ($numerofilas > 0) {
                $trTabla = '';
                for ($i=0; $i<$numerofilas; $i++) {
                    $trTabla .= '<tr>
                    <td>'.($row[$i]["Cod_Presentacion"] == '' ? $row[$i]['Codigo'] : $row[$i]["Cod_Presentacion"]).'</td>
                    <td>'.$row[$i]["Descripcion"]. ' (' .$row[$i]["Nombre_Presentacion"]. ')</td>
                    <td>'.$row[$i]["Proveedor"].'</td>
                    <td>'.$row[$i]["Familia"].'</td>
                    <td>'.$row[$i]["Area"].'</td>';
                }
            }            
        }
	
	$tabla .= "
			<thead>
				<tr>
                    <th>Codigo</th>
                    <th>Descripción</th>
                    <th>Proveedor</th>
                    <th>Familia</th>
                    <th>Área</th>
                </tr>    
			</thead>
			<tbody>
				".$trTabla."
			</tbody>
		</table>";

	echo $tabla;
}

exit;
?>