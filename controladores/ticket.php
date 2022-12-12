<?php  
  session_start();
  if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
    header('Location: ../index.php');
  }

//$con = mysqli_connect('localhost','root','','smartpoi_negocio'.$_SESSION['user_smart']['cliente']['id_cliente']);
date_default_timezone_set('America/Mexico_City');
$con = mysqli_connect('localhost','root','','wits_sistemaalex');
$arreglo = '';
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
    
    $sql = "SELECT ID_Compra, LPAD(ID_Compra, 8, '0') AS Folio, FK_Proveedor, (SELECT proveedores.Nombre FROM proveedores WHERE ID_Proveedor = FK_Proveedor) AS NombreProveedor, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, compras.Descuento, detalle_compras.Subtotal, compras.Total, compras.Anticipo, compras.Estatus, compras.Fecha FROM compras INNER JOIN detalle_compras ON FK_Compra = ID_Compra WHERE ID_Compra = '".$_GET["id"]."'";
    
    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $arreglo = array(
          'ID_Compra'=> $row['ID_Compra'], 
          'Folio' => $row['Folio'], 
          'Proveedor' => $row['NombreProveedor'],  
          'Usuario' => $row['NombreUsuario'], 
          'Descuento' => $row['Descuento'], 
          'Subtotal' => $row['Subtotal'], 
          'Total' => $row['Total'], 
          'Anticipo' => $row['Anticipo'],
          'Estatus' => $row['Estatus'], 
          'Fecha_Registro' => $row['Fecha']
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
        <?php echo "<h1>CREMASI</h1>"; ?>
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
            $sql = "SELECT ID_Detalle_Compra, FK_Compra, FK_Producto, productos.Descripcion AS NombreProducto, detalle_compras.Costo AS Costo, Cantidad, Subtotal, productos.Codigo FROM detalle_compras INNER JOIN productos ON FK_Producto = ID_Producto WHERE FK_Compra = '".$arreglo['ID_Compra']."'";
            $mostrar= "";
            $subtotal = 0;
            $contador = 0;
            if($res=$con->query($sql)){
              if ($res->num_rows > 0) {
                while($row = $res->fetch_assoc()){
                    $mostrar .= "<tr>
                        <td class='codigo'>".$row["Codigo"]."</td>
                        <td class='producto'>".$row["NombreProducto"]."</td>
                        <td class='cantidad'>".(round($row['Cantidad']*100)/100)."</td> 
                        <td class='precio'>$".(round($row['Costo']*100)/100)."</td>
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
        echo '<p class="derecha" style="font-size: 15px;">Subtotal: <b style="font-size: 15px;">$'.(round(($subtotal)*100)/100).'</b></p>';  
        echo '<p class="derecha" style="font-size: 15px;">Descuento: <b style="font-size: 15px;">$'.(round($arreglo['Descuento']*100)/100).'</b></p>'; 
        echo "</br>
          <p class='derecha negra'><b>TOTAL: $".(round($arreglo['Total']*100)/100)."</b></p>
        ";
        echo '<p class="derecha negra">Importe Pagado: $'.(round($arreglo['Anticipo']*100)/100).'</p>'; 
        
        echo '<p class="derecha">Administrador: '.$arreglo['Usuario'].'</p>';
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