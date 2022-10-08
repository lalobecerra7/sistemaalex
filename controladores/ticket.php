<?php  
  session_start();
  if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
    header('Location: ../index.php');
  }

//$con = mysqli_connect('localhost','root','','smartpoi_negocio'.$_SESSION['user_smart']['cliente']['id_cliente']);
date_default_timezone_set('America/Mexico_City');
$con = mysqli_connect('localhost','root','','cremi');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ticket</title>
  <style>
    * {
      font-size: 12px;
      font-family: Arial;
      text-transform: uppercase;
    }
     
    td,
    th,
    tr,
    table {
      border-top: 1px solid #EEEEEE;
      border-collapse: collapse;
    }

    hr{
      color: #F5F5F5;
    }

    td.producto,
    th.producto {
      width: 90px;
      max-width: 90px;
    }
     
    td.cantidad,
    th.cantidad {
      width: 45px;
      max-width: 45px;
      word-break: break-all;
    }

    td.codigo,
    th.codigo {
      width: 45px;
      max-width: 45px;
      word-break: break-all;
    }
     
    td.precio,
    th.precio {
      width: 85px;
      max-width: 85px;
      word-break: break-all;
    }
     
    .centrado {
      text-align: center;
      align-content: center;
    }

    .derecha {
      text-align: right;
      align-content: center;
    }
     
    .ticket {
      width: 410px;
      max-width: 410px;
    }
     
    img {
      max-width: inherit;
      width: inherit;
    }

    p{
      margin: 2px auto;
      padding-right: 10px;
    }

    h1{
      font-size: 20px;
      margin: 5px auto;
    }

    button{
      padding: 10px 15px;
      border-radius: 5px;
      border: none;
      background: #88F170;
      cursor: pointer;
    }
  
    button:hover{
      background: #92F77B;
    }

    .negra{
      font-size: 20px;
      background: #000;
      color: #FFF;
    }

    .negra b{
      font-size: 20px;
    }

    @media print{
      .oculto-impresion, .oculto-impresion *{
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <?php 
    $FechaHoy = date('Y-m-d H:i:s');

    $sql = "SELECT ID_Venta, LPAD(ID_Venta, 8, '0') AS Folio, FK_Vendedor, (SELECT CONCAT(vendedores.Nombre,' ',vendedores.Primer_Apellido,' ',vendedores.Segundo_Apellido) FROM vendedores WHERE ID_Vendedor = FK_Vendedor) AS NombreVendedor, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, FK_Cliente, ventas.Descuento, Subtotal, Total, Importe_Pagado, Cambio, Estatus, Regreso_Inventario, ventas.Fecha_Registro, Fecha_Cancelada, Motivo_Cancelada FROM ventas WHERE ID_Venta = '".$_GET["id"]."'";

    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $arreglo = array(
          'ID_Venta'=> $row['ID_Venta'], 
          'Folio' => $row['Folio'], 
          'Vendedor' => $row['NombreVendedor'],  //Si la venta la realizo un vendedor
          'Usuario' => $row['NombreUsuario'], //Si la venta la realizo un administrador
          'Descuento' => $row['Descuento'], 
          'Subtotal' => $row['Subtotal'], 
          'Total' => $row['Total'], 
          'Importe_Pagado' => $row['Importe_Pagado'], 
          'Cambio' => $row['Cambio'], 
          'Estatus' => $row['Estatus'], 
          'Fecha_Registro' => $row['Fecha_Registro'], 
          'Fecha_Cancelada' => $row['Fecha_Cancelada'], 
          'Motivo_Cancelada' => $row['Motivo_Cancelada']
        );
      }else{
        echo "No se encontraron resultados";
      }
    }

   ?>

    <div class="ticket">
      <br class="oculto-impresion">
      <p class="centrado">
        <button class="oculto-impresion" onclick="imprimir()">IMPRIMIR TICKET</button>
      </p>
      <br class="oculto-impresion">
      <div class="centrado">
        <?php echo "<h1>CREMI</h1>"; ?>
        <?php  
          $FechaHoy = date('Y-m-d H:i:s');
          echo '<p>'.$FechaHoy.'</p>'; 
          echo '<p>FRANCISCO MORA #740</p>'; 
          echo '<p>TEL: 348 783 0300</p>'; 
        ?>
      </div>
      <?php  
        echo '<p class="centrado">FOLIO: '.$arreglo['Folio'].'</p>';
        echo '<p class="centrado">ESTATUS: '.$arreglo['Estatus'].'</p>';
        if ($arreglo['Estatus'] == "Cancelada") {
          echo '<p class="centrado">MOTIVO: '.$arreglo['Motivo_Cancelada'].'</p>';
        }
      ?>
      <br>
      <table class="centrado" width="100%">
        <thead>
          <tr>
            <?php  
              echo '<th class="codigo">Cód.</th>';  
              echo '<th class="producto">Producto</th>
              <th class="cantidad">Cant.</th>';
              echo '<th class="precio">Prec. Unit.</th>';  
              echo '<th class="precio">Importe</th>';
            ?>
          </tr>
        </thead>
        <tbody> 
          <?php 
            $sql = "SELECT ID_Detalle_Venta, FK_Venta, FK_Producto, Nombre_Producto, detalles_venta.Precio, Cantidad, Subtotal, productos.Codigo FROM detalles_venta INNER JOIN productos ON FK_Producto = ID_Producto WHERE FK_Venta = '".$arreglo['ID_Venta']."'";
            $mostrar= "";
            $subtotal = 0;
            $contador = 0;
            if($res=$con->query($sql)){
              if ($res->num_rows > 0) {
                while($row = $res->fetch_assoc()){
                    $mostrar .= "<tr>
                        <td class='codigo'>".$row["Codigo"]."</td>
                        <td class='producto'>".$row["Nombre_Producto"]."</td>
                        <td class='cantidad'>".(round($row['Cantidad']*100)/100)."</td> 
                        <td class='precio'>$".(round($row['Precio']*100)/100)."</td>
                        <td class='precio'>$".(round($row['Subtotal']*100)/100)."</td>
                      </tr>";

                    $subtotal += $row['Subtotal'];
                    $contador++;
                }
                  echo $mostrar;
              }else{
                echo "No se encontraron resultados";
              }
            }else{
              echo "Error: ".mysqli_error($con);
            } 
           ?>
        </tbody>
      </table>
      <hr>
      <?php 
        echo '<p class="derecha">No. de Articulos: '.$contador.'</p>'; 
        echo '<p class="derecha" style="font-size: 15px;">Subtotal: <b style="font-size: 15px;">$'.(round(($arreglo['Subtotal'])*100)/100).'</b></p>';  
        echo '<p class="derecha" style="font-size: 15px;">Descuento: <b style="font-size: 15px;">$'.(round($arreglo['Descuento']*100)/100).'</b></p>'; 
        echo "</br>
          <p class='derecha negra'><b>TOTAL: $".(round($arreglo['Total']*100)/100)."</b></p>
        ";
        echo '<p class="derecha negra">Importe Pagado: $'.(round($arreglo['Importe_Pagado']*100)/100).'</p>';  

        echo '<p class="derecha negra">Cambio: $'.(round($arreglo['Cambio']*100)/100).'</p>';  
        if ($arreglo['Vendedor'] != "" && $arreglo['Usuario'] == "") {
          echo '<p class="derecha">Vendedor: '.$arreglo['Vendedor'].'</p>';
        }else if ($arreglo['Usuario'] != "" && $arreglo['Vendedor'] == "") {
          echo '<p class="derecha">Administrador: '.$arreglo['Usuario'].'</p>';
        }
      ?>
      <br>
      <p class="centrado">***********************************************************</p>
      <p class="centrado">***********************************************************</p>
      <br>
      <p class="centrado">¡GRACIAS POR TU COMPRA!</p>
      <br>
      <p class="centrado">***********************************************************</p>
      <p class="centrado">***********************************************************</p>
    </div>
  <script>
    window.print();

    function imprimir() {
      window.print();
    }
    //<a href="http://www.forosdelweb.com/f4/como-abrir-ventana-nueva-pequena-pinchar-enlace-1035579/#post4362645" target="_blank" onclick="window.open(this.href, this.target, 'width=300,height=400'); return false;">Link popup</a>
  </script>
</body>
</html>