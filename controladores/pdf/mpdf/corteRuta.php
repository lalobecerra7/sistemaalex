<?php
require_once __DIR__ . '/vendor/autoload.php';
include '../../../modelo/m_modelo.php';
session_start();

if(isset($_SESSION['user_admin']['ID_Usuario'])) return http_response_code(400);
extract($_GET);

$omodelo = new m_modelo();
$id = $omodelo->link->real_escape_string($id);

$data = array();

$query = "SELECT ID_Corte, Ruta, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y') AS FechaI, DATE_FORMAT(Fecha_Fin, '%d-%m-%Y') AS FechaF, Verificado, FK_Chofer, FK_Vehiculo, Estado, Imagen, Fecha_Registro AS Fecha, Total, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Recaudado FROM cortes_ruta WHERE ID_Corte = $id";

if($res = $con->query($query)){
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();

        $mpdf = new \Mpdf\Mpdf(
            [
                'mode' => 'utf-8', 
                'format' => 'Letter', 
                'margin_left' => 15,    	
                'margin_right' => 15,    	
                'margin_top' => 50,     
                'margin_bottom' => 15
            ]
        );

        // $backgroundImage = '../../../vistas/assets/img/report/report_background.jpg';
        // $mpdf->SetDefaultBodyCSS('background', "url($backgroundImage)");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);


        $htmlContent = '
        <div style="font-family: Arial, Helvetica, sans-serif; font-size: 14pt;">
            <style>
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

                .reportHeader{
                    width: 100%;
                    display: flex;
                }

                .row{
                    display: flex;
                    float: left;
                }
            </style>
            <div class="reportHeader">
                <div class="row" style="width: 50%;">
                    <p><b>Usuario:</b> '.$_SESSION['user_admin']['Nombre'].' '.$_SESSION['user_admin']['Primer_Apellido'].' '.$_SESSION['user_admin']['Segundo_Apellido'].'</p>
                    <p><b>Ruta:</b> '.$row['Ruta'].'</p>
                    <p><b>Fecha Fin:</b> '.$row['FechaF'].'</p>
                    <p><b>Vehiculo</b> '.$row['FK_Vehiculo'].'</p>
                </div>
                <div class="row" style="width: 50%;">
                    <p><b>Fecha:</b> '.date('Y-m-d').'</p>
                    <p><b>Fecha Inicio:</b> '.$row['FechaI'].'</p>
                    <p ><b>Chofer:</b> '.$row['FK_Chofer'].'</p>
                </div>
            </div>
            <div>
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Nombre</th>
                            <th>Domicilio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>a</td>
                            <td>a</td>
                            <td>a</td>
                            <td>a</td>
                        </tr>
                    </tbody>
                </table>  
            </div>
        </div>
        ';


        // $query2 = "SELECT c.Orden_Ruta, CONCAT(c.Nombre, ' ', c.Primer_Apellido, ' ', c.Segundo_Apellido ) AS Nombre_Cliente, CONCAT('C. ',c.Calle, ', No. ', c.No_Exterior, (CASE WHEN NULLIF(c.No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', c.No_Interior, ', ') ELSE ', ' END), c.Colonia, ', ', c.Ciudad, ', ', c.Estado, ', ', c.Pais) AS Domicilio_Cliente, (SELECT SUM(v.Total) FROM ventas AS v WHERE v.FK_Cliente = c.ID_Cliente AND FIND_IN_SET(v.ID_Venta, dcr.Ventas)) AS Total_Cliente FROM detalles_corte_ruta as dcr LEFT JOIN cortes_ruta AS cr ON dcr.FK_Corte_Ruta = cr.ID_Corte LEFT JOIN clientes AS c ON dcr.FK_Cliente = c.ID_Cliente WHERE cr.ID_Corte = $id";

        // //Clientes por corte ruta

        // $clientTables = '
        // <div style="position: absolute; top: 9cm; left: 1.5cm; width: 100%;">
        //     <h2 style="font-family: Arial, Helvetica, sans-serif; font-size: 14pt;">Pedidos</h2>
        //     <table>
        //         <tr>
        //             <th>Orden</th>
        //             <th>Nombre</th>
        //             <th>Domicilio</th>
        //             <th>Total</th>
        //         </tr>
        // ';

        // if($res2 = $con->query($query2)){
        //     $row2 = $res2->fetch_assoc();

        //     $clientTables.= '<tr>
        //         <td>'.$row2['Orden_Ruta'].'</td>
        //         <td>'.$row2['Nombre_Cliente'].'</td>
        //         <td>'.$row2['Domicilio_Cliente'].'</td>
        //         <td>'.$row2['Total_Cliente'].'</td>
        //     </tr>';
            
        // }

        // $clientTables.= '</table></div>';

        // //Gastos Corte ruta
        // $query3 = "SELECT Descripcion, Coste, SUM(Coste) as Total_Gastos FROM gastos_cortes_ruta WHERE FK_Corte_Ruta = $id";
        

        // //Balances


        // $footerSign = '
        //     <div style="position: absolute; bottom: 1.5cm; left: 1.5cm; font-family: Arial, Helvetica, sans-serif; font-size: 14pt;">
                
        //     </div>
        // ';
        // $htmlContent .= $clientTables;
        // $htmlContent .= $footerSign;
        // print_r($_SESSION['user_admin']['Primer_Apellido']);

        //echo $htmlContent;

        $mpdf->WriteHTML($htmlContent);
        $mpdf->Output();
    }
}else{
    echo "Error: ".mysqli_error($con);
}

?>