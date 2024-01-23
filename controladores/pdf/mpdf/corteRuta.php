<?php
require_once __DIR__ . '/vendor/autoload.php';
include '../../../modelo/m_modelo.php';
session_start();

// if(isset($_SESSION['user_admin']['ID_Usuario'])) return http_response_code(400);
extract($_GET);

$omodelo = new m_modelo();
$id = $omodelo->link->real_escape_string($id);

$data = array();

$query = "SELECT ID_Corte, Ruta, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y') AS FechaI, DATE_FORMAT(Fecha_Fin, '%d-%m-%Y') AS FechaF, Verificado, FK_Chofer, FK_Vehiculo, Estado, Imagen, Fecha_Registro AS Fecha, Total, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Recaudado FROM cortes_ruta WHERE ID_Corte = $id";
$row = $omodelo->_consultar($query);
$numerofilas = $omodelo->numerofilas;

if($row == "si"){
    echo "Error: ".mysqli_error($omodelo->link);
}else{
    $data['Corte_Data'] = $row[0];
}

$query2 = "SELECT c.Orden_Ruta, CONCAT(c.Nombre, ' ', c.Primer_Apellido, ' ', c.Segundo_Apellido ) AS Nombre_Cliente, CONCAT('C. ',c.Calle, ', No. ', c.No_Exterior, (CASE WHEN NULLIF(c.No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', c.No_Interior, ', ') ELSE ', ' END), c.Colonia, ', ', c.Ciudad, ', ', c.Estado, ', ', c.Pais) AS Domicilio_Cliente, (SELECT SUM(v.Total) FROM ventas AS v WHERE v.FK_Cliente = c.ID_Cliente AND FIND_IN_SET(v.ID_Venta, dcr.Ventas)) AS Total_Cliente FROM detalles_corte_ruta as dcr LEFT JOIN cortes_ruta AS cr ON dcr.FK_Corte_Ruta = cr.ID_Corte LEFT JOIN clientes AS c ON dcr.FK_Cliente = c.ID_Cliente WHERE cr.ID_Corte = $id";
$row2 = $omodelo->_consultar($query2);
$numerofilas2 = $omodelo->numerofilas;

if($row2 == "si"){
    echo "Error: ".mysqli_error($omodelo->link);
}else{
    $data['Corte_Clientes'] = $row2;
}

$query3 = "SELECT Descripcion, Coste, SUM(Coste) as Total_Gastos FROM gastos_cortes_ruta WHERE FK_Corte_Ruta = $id";
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
        <img src="../../../vistas/assets/img/favicon/favicon.jpg" style="height: 1.5cm;"/>
        <img src="../../../vistas/assets/img/report/cabecera.svg"/>
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
            <p ><b>Chofer:</b> '.$data['Corte_Data']['FK_Chofer'].'</p>
        </div>
        <p style="margin-top: 0px;"><b>Vehiculo</b> '.$data['Corte_Data']['FK_Vehiculo'].'</p>
    </div>
    <div>
        <table style="width: 100%;">
            <thead>
                <tr class="table-title">
                    <td colspan="4">Clientes de ruta</td>
                </tr>
                <tr>
                    <th>Orden</th>
                    <th>Nombre</th>
                    <th>Domicilio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';

            for ($i=0; $i < $numerofilas2; $i++) { 
                $htmlContent .= '
                <tr>
                    <td>'.$data['Corte_Clientes'][$i]['Orden_Ruta'].'</td>
                    <td>'.$data['Corte_Clientes'][$i]['Nombre_Cliente'].'</td>
                    <td>'.$data['Corte_Clientes'][$i]['Domicilio_Cliente'].'</td>
                    <td>$'.number_format($data['Corte_Clientes'][$i]['Total_Cliente']).'</td>
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
                        <td>$'.number_format($data['Corte_Data']['Total']).'</td>
                    </tr>
                    <tr>
                        <td>Gastos</td>
                        <td>$'.number_format($data['Corte_Gastos'][0]['Coste']).'</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td>Total neto</td>
                        <td>$'.number_format($data['Corte_Data']['Total'] - $data['Corte_Gastos'][0]['Coste']).'</td>
                    </tr>
                    <tr>
                        <td>Total recaudado</td>
                        <td>$'.number_format($data['Corte_Data']['Recaudado']).'</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Balance: $'.number_format($data['Corte_Data']['Recaudado'] - ($data['Corte_Data']['Total'] - $data['Corte_Gastos'][0]['Coste'])).'</td>
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
                        <td>$'.number_format($data['Corte_Gastos'][$j]['Coste']).'</td>
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

// echo $htmlContent;
$mpdf->WriteHTML($htmlContent);
$mpdf->Output();

?>