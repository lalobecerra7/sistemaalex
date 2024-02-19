<?php
  session_start();
  include '../../modelo/m_modelo.php';
  if (!isset($_SESSION['user_admin'])) return http_response_code(400);
  extract($_GET);

  $modelo = new m_modelo();
  $id = $modelo->link->real_escape_string($id);
  $queryVentas = "SELECT ID_Corte, Ruta, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y %r') AS Fecha_Inicio, DATE_FORMAT(Fecha_Fin, '%d-%m-%Y %r') AS Fecha_Fin, FK_Chofer, FK_Vehiculo, Total, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro FROM cortes_ruta WHERE ID_Corte = '$id'";
  $rowVentas = $modelo->_consultar($queryVentas);
  $folio = str_pad($rowVentas[0]['ID_Corte'], 6, "0", STR_PAD_LEFT);

  if ($rowVentas == 'si') {
    echo "Error: " . mysqli_error($modelo->link);
    return;
  }

  $queryDetalles = "SELECT FK_Producto, FK_Presentacion, IF (FK_Presentacion <> 0, (SELECT Codigo FROM presentaciones WHERE FK_Presentacion = ID_Presentacion), (SELECT Codigo FROM productos WHERE FK_Producto = ID_Producto)) AS Codigo, Descripcion AS Producto, SUM(Cantidad) as Cantidadtol, IFNULL((SELECT Cantidad FROM productos_verificados_corte_ruta WHERE FK_Corte_Ruta = '$id' AND FK_Producto = detalles_ventas.FK_Producto AND FK_Presentacion = detalles_ventas.FK_Presentacion), 0) AS Verificacion FROM detalles_ventas WHERE FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$id') GROUP BY Codigo ORDER BY (SELECT Importe FROM productos WHERE ID_Producto = detalles_ventas.FK_Producto )";
  $rowDetalles = $modelo->_consultar($queryDetalles);

  if ($rowDetalles == 'si') {
    echo "Error: " . mysqli_error($modelo->link);
    return;
  }

  $tabla = '
    <table>
      <thead>
        <tr>
          <th colspan="4">Descripcion</th>
        </tr>
        <tr>
          <th>Codigo</th>
          <th>Cantidad</th>
          <th>Verificada</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>';

  foreach ($rowDetalles as $detalle) {
    if ($detalle == null) continue;
      if ($detalle['Cantidadtol'] == $detalle['Verificacion']) {
        $estado = 'Listo';
      }else{
        $estado = 'Pendiente';
      }
    $tabla .= '
      <tr>
        <td colspan="4">' . $detalle['Producto'] . '</td>
      </tr>
      <tr>
        <td>' . $detalle['Codigo'] . '</td>
        <td>' . $detalle['Cantidadtol'] . '</td>
        <td>' . $detalle['Verificacion'] . '</td>
        <td>' . $estado. '</td>
      </tr>';
  }

  $tabla .= '</tbody></table>';

  $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Ticket</title>
      <style>
        * {
          box-sizing: border-box;
          font-family: "Arial";
          margin: 0;
          padding: 0;
          font-weight: bold;
          text-transform: uppercase;
        }

        body {
          display: flex;
          justify-content: center;
          align-items: center;
          flex-flow: column nowrap;
          padding: 0.5rem;
          max-width: 410px;
          width: 410px;
        }

        button {
          background-color: #f5f5f5;
          border: 1px solid #ccc;
          border-radius: 4px;
          color: #333;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: 400;
          line-height: 1.42857;
          margin-bottom: 0;
          padding: 6px 12px;
          text-align: center;
          white-space: nowrap;

          margin-bottom: 3rem;

          display: block;
        }

        .nombre {
          font-size: 2rem;
          font-weight: bold;
        }

        .domicilio {
          font-size: 1.5rem;
          text-align: center;
        }

        .domicilio+p {
          margin-top: 0.25rem;
        }

        .telefono {
          font-size: 1.5rem;
        }

        .fecha {
          margin: 0.5rem 0;
          font-size: 18px;
        }

        .tablas {
          display: flex;
          flex-flow: column nowrap;
          justify-content: flex-start;
          align-items: center;
          width: 100%;
          gap: 0.5rem;
        }

        table {
          width: 100%;
          margin: 0.5rem 0;
          font-size: 1rem;
        }

        caption {
          font-size: 1.25rem;
          font-weight: bold;
          margin-bottom: 0.1rem;
        }

        th {
          border-bottom: 3px solid #333;
        }

        td {
          text-align: center;
          font-size: 20px;
        }

        td[colspan="3"] {
          text-align: left;
          font-size: 22px;
        }

        .pago {
          width: 100%;
          font-size: 2rem;
          text-align: right;
        }

        .pago p {
          text-transform: uppercase;
          margin-right: 0.8rem;
        }

        footer {
          margin-top: 1rem;
          margin-bottom: 500px;
        }

        .separador {
          font-size: 2rem;
          font-weight: bold;
          text-align: center;
          margin: 20px 0px;
          line-height: 0.2rem;
        }

        .gracias {
          font-weight: bold;
          text-transform: uppercase;
          font-size: 1.5rem;
          text-align: center;
          margin-bottom: 0.5rem;
        }

        .detalles {
          display: flex;
          text-align: right;
          flex-flow: column nowrap;
          justify-content: flex-end;
          font-size: 16px;
          margin-right: 0.5rem;
          margin-bottom: 0.5rem;
        }

        .vendedor {
          font-size: 14px;
        }

        @media print {
          button {
            display: none;
          }
        }
      </style>
    </head>
    <body onafterprint="funcionDespues()">
     <button type="submit" class="oculto-impresion" onclick="imprimir()">
      Imprimir
     </button> 
    
    <img src="../../vistas/assets/img/favicon/favicon.jpg" width="150px" style="margin-bottom: 10px;" />
    <p class="domicilio">MISCELANEA RIOS</p>
    <p class="domicilio">Corte de ruta</p>
    <p class="fecha"><strong>Ruta:</strong> ' . $rowVentas[0]['Ruta'] . ' </p>
    <p class="fecha"><strong>Fecha inicio:</strong> ' . $rowVentas[0]['Fecha_Inicio'] . ' </p>
    <p class="fecha"><strong>Fecha Fin:</strong> ' . $rowVentas[0]['Fecha_Fin'] . ' </p>
    <p class="fecha"><strong>Folio:</strong> ' . $folio . ' </p>
    ' . $tabla . '
    <div class="pago">
      <p>Total: $' . number_format($rowVentas[0]['Total'], 2) . '</p>
    </div>
    <footer>
      <div class="detalles">
        <p class="vendedor">Chefer: ' . $rowVentas[0]['FK_Chofer'] . '</p>
        <p class="vendedor">Vehiculo: ' . $rowVentas[0]['FK_Vehiculo'] . '</p>
      </div>
      <p class="separador">********************************</p>
      <p class="gracias">¡Gracias por su preferencia!</p>
      <p class="separador">********************************</p>
    </footer>
    </body>
    <script>
    window.print();

    function imprimir() {
      window.print();
    }

    function funcionDespues() {
      //parent.document.documentElement.requestFullscreen();
      setTimeout(function(){
        parent.document.getElementById("bAbrirCaja").click();
      }, 300);
    }
    </script>
  </html>';

  echo $html;
?>
