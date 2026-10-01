<?php
// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Guardar errores en log
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

session_start();

if (isset($_SESSION['user_admin'])) {
    
    // 1. CONEXIÓN Y CONFIGURACIÓN DE CARACTERES
    $con = mysqli_connect('localhost', 'phpmyadmin', 'Alex_2026', 'miscelanearios_sistemaalex2025');
    
    if (!$con) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // ESTA LÍNEA ES VITAL: Configura la comunicación con la BD en UTF-8
    mysqli_set_charset($con, "utf8");

    // 2. HEADERS PARA EXCEL
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=ReporteInventario.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $Sucursales = $_GET["sucursales"] ?? '[]';
    $Proveedor = $_GET["proveedor"] ?? '';
    $palabra = $_GET["palabra"] ?? '';

    $tabla = "<table border='1'>
            <thead>
                <tr style='background-color: #cccccc;'>
                    <th style='width: 200px;'>Codigo</th>
                    <th style='width: 300px;'>Descripcion</th>
                    <th style='width: 100px;'>Costo</th>
                    <th style='width: 100px;'>Existencia</th>
                    <th style='width: 200px;'>Proveedor</th>
                    <th style='width: 200px;'>Sucursal</th>
                </tr>
            </thead>
            <tbody>";

    // Lógica de filtros (Se mantiene tu lógica original)
    $qWhere = "";
    $qAnd = "";
    $qSucursales = "";
    $qProveedor = "";

    if ($Proveedor != "") {
        $qProveedor = "FK_Proveedor = '$Proveedor'";
    }

    $cadenaSucursales = "";
    $sucursalesArr = json_decode($Sucursales, true);
    if (is_array($sucursalesArr)) {
        foreach ($sucursalesArr as $sucursal) {
            $cadenaSucursales .= $sucursal["ID"] . ",";
        }
    }
    
    $string = rtrim($cadenaSucursales, ",");
    if ($string != "") {
        $qSucursales = "inventario.FK_Sucursal IN(" . $string . ")";
    }

    if ($qSucursales != "" || $qProveedor != "") {
        $qWhere = "WHERE";
    }

    if ($qSucursales != "" && $qProveedor != "") {
        $qAnd = " AND ";
    }

    if ($palabra != '') {
        $separa = explode(' ', trim($palabra));
        $busqueda = ' WHERE ';
        for ($i = 0; $i < count($separa); $i++) {
            $busqueda .= "CONCAT(IFNULL(presentaciones.Codigo, productos.Codigo), Descripcion, Cantidad, IFNULL(presentaciones.Costo, productos.Costo), IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor'), (SELECT Nombre FROM sucursales WHERE FK_Sucursal = ID_Sucursal)) REGEXP '" . $separa[$i] . "'";
            if ($i < (count($separa) - 1)) {
                $busqueda .= ' AND ';
            }
        }

        if ($busqueda != "" && ($qSucursales != "" || $qProveedor != "")) {
            $qWhere = " AND ";
        }

        $query = "SELECT IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo, 
                  IFNULL(presentaciones.Costo, productos.Costo) AS Costo, 
                  Descripcion, 
                  IFNULL(presentaciones.Nombre, '') AS NombrePresentacion, 
                  Cantidad AS Existencia, 
                  (SELECT Nombre FROM sucursales WHERE FK_Sucursal = ID_Sucursal) AS Sucursal, 
                  IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor') AS Proveedor 
                  FROM inventario 
                  INNER JOIN productos ON FK_Producto = ID_Producto 
                  LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion 
                  LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto 
                  $busqueda $qWhere $qProveedor $qAnd $qSucursales 
                  ORDER BY Codigo DESC";
    } else {
        $query = "SELECT IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo, 
                  IFNULL(presentaciones.Costo, productos.Costo) AS Costo, 
                  Descripcion, 
                  IFNULL(presentaciones.Nombre, 'Sin presentación') AS NombrePresentacion, 
                  Cantidad AS Existencia, 
                  (SELECT Nombre FROM sucursales WHERE FK_Sucursal = ID_Sucursal) AS Sucursal, 
                  IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor') AS Proveedor 
                  FROM inventario 
                  INNER JOIN productos ON FK_Producto = ID_Producto 
                  LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion 
                  LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto 
                  $qWhere $qProveedor $qAnd $qSucursales 
                  ORDER BY Codigo DESC";
    }

    if ($res = $con->query($query)) {
        while ($row = $res->fetch_assoc()) {
            $tabla .= '<tr>
                    <td style="text-align:center;">' . $row["Codigo"] . '</td>
                    <td style="text-align:left;">' . $row["Descripcion"] . ' (' . $row["NombrePresentacion"] . ')</td>
                    <td style="text-align:center;">' . $row["Costo"] . '</td>
                    <td style="text-align:center;">' . $row["Existencia"] . '</td>
                    <td style="text-align:center;">' . $row["Proveedor"] . '</td>
                    <td style="text-align:center;">' . $row["Sucursal"] . '</td>
                </tr>';
        }
    } else {
        // En un archivo de descarga, los errores de SQL pueden corromper el Excel
        error_log("Error SQL: " . mysqli_error($con));
    }

    $tabla .= "</tbody></table>";

    // 3. IMPRESIÓN CON FIRMA BOM (ESTO ARREGLA LAS TILDES EN EXCEL)
    echo "\xEF\xBB\xBF"; 
    echo $tabla;
    
    mysqli_close($con);
    exit;
} else {
    echo "No tiene permisos para ver este reporte.";
}
?>