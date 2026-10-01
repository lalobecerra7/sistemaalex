<?php
  session_start();
  include '../../modelo/m_modelo.php';
  if (!isset($_SESSION['user_admin'])) return http_response_code(400);
  extract($_GET);

  $omodelo = new m_modelo();
  $id = $omodelo->link->real_escape_string($id);
  
  $query = "SELECT ID_Lote, 
                   IF(lotes.FK_Presentacion = 0, productos.Codigo, (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion)) AS Codigo, 
                   lotes.FK_Producto AS FK_Producto, 
                   FK_Presentacion, 
                   Descripcion AS Producto, 
                   IFNULL((SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion), '') AS Presentacion, 
                   FK_Sucursal, 
                   lotes.Nombre AS Lote, 
                   Cantidad, 
                   DATE_FORMAT(Fecha_Caducidad, '%d-%m-%Y') AS Fecha_Caducidad 
            FROM lotes 
            INNER JOIN productos ON ID_Producto = lotes.FK_Producto 
            WHERE ID_Lote = '$id'";
  $row = $omodelo->_consultar($query);
  $numerofilas = $omodelo->numerofilas;

  if ($row == 'si') {
    echo "Error: " . mysqli_error($omodelo->link);
  } else {
    if ($numerofilas > 0) {
      $cantidad = (int)$row[0]['Cantidad']; // cuántas etiquetas
      echo '<!DOCTYPE HTML>
        <html lang="en-US">
          <head>
            <meta charset="UTF-8">
            <title></title>
            <script src="../../vistas/assets/vendor/libs/jquery/jquery.js"></script>
            <script src="../../vistas/assets/plugins/JsBarcode.all.min.js"></script>
            <style>
              body{
                padding-top: 15px;
              }

              .etiqueta {
                position: relative;
                width: 80mm;
                height: 40mm;
                align-items: center;
                justify-content: center;
                margin-top: 15px;
                page-break-inside: avoid; /* evita que se corte en impresión */
              }

              @media print {
                @page {
                  size: 80mm 40mm; /* Tamaño de la etiqueta */
                  margin: 0;
                }
                body {
                  margin: 0;
                  width: 80mm;
                  height: auto;
                  font-size: 10px;
                }
              }
            </style>
          </head>
          <body>';
      
      // Generamos tantas etiquetas como la cantidad
      for ($i = 1; $i <= $cantidad; $i++) {
        echo '<div class="etiqueta">
                <span style="position: absolute; top: -30px; font-family: arial; text-align: center;">
                  <span style="font-size: 12px;">Cad. '.$row[0]['Fecha_Caducidad'].'</span><br>
                  <span style="font-size: 12px;">'.$row[0]['Producto'].($row[0]['Presentacion'] != '' ? ' ('.$row[0]['Presentacion'].')' : '').'</span>
                </span>
                <canvas id="barcode'.$i.'" style="height: 80%;"></canvas>
              </div>';
      }

      // Script que recorre y genera todos los códigos
      echo '<script>
              $(document).ready(function(){';
      for ($i = 1; $i <= $cantidad; $i++) {
        echo '$("#barcode'.$i.'").JsBarcode("'.$row[0]['Codigo'].'~'.$row[0]['ID_Lote'].'",{displayValue:true,fontSize:20});';
      }
      echo '});
            </script>
          </body>
        </html>';
    }
  }
?>