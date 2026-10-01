<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';
include '../../modelo/m_modelo.php';
session_start();

// if(isset($_SESSION['user_admin']['ID_Usuario'])) return http_response_code(400);
extract($_GET);

$omodelo = new m_modelo();
$id = $omodelo->link->real_escape_string($id);

$data = array();

$query = "SELECT ID_Corte, Monto, FK_Ruta, FK_Sucursal, sucursales.Nombre AS Sucursal, rutas.Nombre AS Ruta, Fecha_Inicio, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y') AS FechaI, DATE_FORMAT(Fecha_Fin, '%d-%m-%Y') AS FechaF, Fecha_Fin, CONCAT(choferes.Nombre, ' ', Primer_Apellido, ' ',Segundo_Apellido) AS Chofer, vehiculos.Descripcion AS Vehiculo, Estatus, Imagen, DATE_FORMAT(cortes_ruta.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro FROM cortes_ruta LEFT JOIN rutas ON FK_Ruta = ID_Ruta LEFT JOIN choferes ON FK_Chofer = ID_Chofer LEFT JOIN vehiculos ON cortes_ruta.FK_Vehiculo = ID_Vehiculo LEFT JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE ID_Corte = $id";
$row = $omodelo->_consultar($query);
$numerofilas = $omodelo->numerofilas;

if($row == "si"){
    echo "Error: ".mysqli_error($omodelo->link);
}else{
    $data['Corte_Data'] = $row[0];
}

$corte = $data['Corte_Data']['ID_Corte'];
$ruta = $data['Corte_Data']['FK_Ruta'];
$fechaIn = $data['Corte_Data']['Fecha_Inicio'];
$fechaFin = $data['Corte_Data']['Fecha_Fin'];
$sucursal = $data['Corte_Data']['FK_Sucursal'];

$query2 = "SELECT ID_Venta, IFNULL((SELECT SUM(Cantidad) FROM detalles_ventas WHERE FK_Venta = ID_Venta), 0) AS Cantidad, IFNULL((SELECT SUM(Cantidad) FROM verificar_ruta WHERE FK_Venta = ID_Venta AND FK_Corte = '$corte'), 0) AS Verificados, DATE_FORMAT(ventas.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, ventas.Fecha_Registro AS Fecha, Total, FK_Cliente, CONCAT(Nombre, ' ', Primer_Apellido, ' ', Segundo_Apellido) AS Cliente, IF((SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0, (SELECT Orden FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte' LIMIT 1), Orden_Ruta) AS Orden, IF(FK_Direccion = 0, CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais), (SELECT CONCAT('C. ',Calle, ', No. ', No_Exterior, (CASE WHEN NULLIF(No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', No_Interior, ', ') ELSE ', ' END), Colonia, ', ', Ciudad, ', ', Estado, ', ', Pais) FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion)) AS Domicilio, IFNULL((SELECT SUM(Total) FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaIn' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND (FK_Ruta = '$ruta' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0) AND ventas.FK_Sucursal = '$sucursal' AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$corte') = 0), 0) AS SumTotal, (SELECT COUNT(*) FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaIn' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND (FK_Ruta = '$ruta' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0) AND ventas.FK_Sucursal = '$sucursal' AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$corte') = 0) AS Num FROM ventas INNER JOIN clientes ON FK_Cliente = ID_Cliente WHERE ventas.Estatus = 'Completada' AND (DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') >= '$fechaIn' AND DATE_FORMAT(ventas.Fecha_Registro, '%Y-%m-%d') <= '$fechaFin') AND (FK_Ruta = '$ruta' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Ruta = '$ruta' AND FK_Corte = '$corte') > 0) AND ventas.FK_Sucursal = '$sucursal' AND (SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = ventas.FK_Cliente AND FK_Corte = '$corte') = 0 ORDER BY Orden DESC";
$row2 = $omodelo->_consultar($query2);
$numerofilas2 = $omodelo->numerofilas;

if($row2 == "si"){
    echo "Error: ".mysqli_error($omodelo->link);
}else{
    $data['Corte_Clientes'] = $row2;
}

$query3 = "SELECT ID_Gasto, Descripcion, Monto, Fecha_Registro, (SELECT IFNULL(SUM(Monto), 0) FROM gastos_ruta WHERE FK_Corte = '$id') AS Total_Monto FROM gastos_ruta WHERE FK_Corte = '$id'";
$row3 = $omodelo->_consultar($query3);
$numerofilas3 = $omodelo->numerofilas;

if($row3 == "si"){
    echo "Error: ".mysqli_error($omodelo->link);
}else{
    $data['Corte_Gastos'] = $row3;
}

$mpdf = new \Mpdf\Mpdf(
    [
        'mode' => 'utf-8', 
        'format' => 'Letter', 
        'margin_left' => 15,    	
        'margin_right' => 15,    	
        'margin_top' => 15,     
        'margin_bottom' => 15
    ]
);

$htmlContent = '
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 14pt;">
    <style>
        .reportHeader{
            width: 100%;
            display: flex;
        }

        .row{
            display: flex;
            float: left;
        }

        table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 5px;
            border: 1px solid #000;
        }

        .table-title td{
            background-color: #D3D3D3 ;
        }

        th{
            background-color: #D3D3D3 ;
        }

        .signrow{
            display: flex;
            float: left;
        }

    </style>
    <div style="text-align: center; margin: auto; width: 300px;">
        <img src="../../vistas/assets/img/favicon/favicon.jpg" style="height: 1.5cm;"/>
        <img src="../../vistas/assets/img/report/cabecera.svg"/>
    </div>
    <br>
    <div class="reportHeader">
        <div class="row" style="width: 50%;">
            <p><b>Usuario:</b> '.$_SESSION['user_admin']['Nombre'].' '.$_SESSION['user_admin']['Primer_Apellido'].' '.$_SESSION['user_admin']['Segundo_Apellido'].'</p>
            <p><b>Ruta:</b> '.$data['Corte_Data']['Ruta'].'</p>
            <p><b>Fecha Fin:</b> '.$data['Corte_Data']['FechaF'].'</p>
        </div>
        <div class="row" style="width: 50%; text-align: right;">
            <p><b>Fecha:</b> '.date('Y-m-d').'</p>
            <p><b>Fecha Inicio:</b> '.$data['Corte_Data']['FechaI'].'</p>
            <p ><b>Chofer:</b> '.$data['Corte_Data']['Chofer'].'</p>
        </div>
        <p style="margin-top: 0px;"><b>Vehiculo</b> '.$data['Corte_Data']['Vehiculo'].'</p>
    </div>
    <div>
        <table style="width: 100%;">
            <thead>
                <tr class="table-title">
                    <td colspan="5">Clientes de ruta</td>
                </tr>
                <tr>
                    <th>Orden</th>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Domicilio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';

            for ($i=0; $i < $numerofilas2; $i++) { 
                $htmlContent .= '
                <tr>
                    <td>'.$data['Corte_Clientes'][$i]['Orden'].'</td>
                    <td>'.$data['Corte_Clientes'][$i]['ID_Venta'].'</td>
                    <td>'.$data['Corte_Clientes'][$i]['Cliente'].'</td>
                    <td>'.$data['Corte_Clientes'][$i]['Domicilio'].'</td>
                    <td>$'.number_format($data['Corte_Clientes'][$i]['Total'], 2).'</td>
                </tr>
                ';
            }

$htmlContent .= '
            </tbody>
        </table>
    </div>
    <br>
    <div class="balanceTables">
        <div class="row padding-tables-a" style="width: 50%;">
            <table style="width: 98%;">
                <thead>
                    <tr class="table-title">
                        <td colspan="2">Balance General</td>
                    </tr>
                    <tr>
                        <th style="width: 70%;">Titulo</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total</td>
                        <td>$'.number_format($data['Corte_Clientes'][0]['SumTotal'], 2).'</td>
                    </tr>
                    <tr>
                        <td>Gastos</td>
                        <td>$'.number_format($data['Corte_Gastos'][0]['Total_Monto'], 2).'</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td>Total neto</td>
                        <td>$'.number_format($data['Corte_Clientes'][0]['SumTotal'] - $data['Corte_Gastos'][0]['Total_Monto'], 2).'</td>
                    </tr>
                    <tr>
                        <td>Total recaudado</td>
                        <td>$'.number_format($data['Corte_Data']['Monto'], 2).'</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Balance: $'.number_format($data['Corte_Data']['Monto'] - ($data['Corte_Clientes'][0]['SumTotal'] - $data['Corte_Gastos'][0]['Total_Monto']), 2).'</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row padding-tables-b" style="width: 50%;">
            <table style="width: 100%;">
                <thead>
                    <tr class="table-title">
                        <td colspan="2">Gastos Realizados</td>
                    </tr>
                    <tr>
                        <th style="width: 70%;">Descripcion</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>';

                for ($j=0; $j < $numerofilas3; $j++) { 
                    $htmlContent.= '
                    <tr>
                        <td>'.$data['Corte_Gastos'][$j]['Descripcion'].'</td>
                        <td>$'.number_format($data['Corte_Gastos'][$j]['Monto'], 2).'</td>
                    </tr>
                    ';
                }

$htmlContent.='
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <div style="text-align: center; margin: auto; width: 300px;">
        <div style="height: 2px; width: 300px; background-color: #000;"></div>
        Firma de enterado
    </div>
</div>
';

//echo $htmlContent;
$mpdf->WriteHTML($htmlContent);
$mpdf->Output();

?>