<?php  
  session_start();
  if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
    header('Location: ../index.php');
  }

//$con = mysqli_connect('localhost','root','','smartpoi_negocio'.$_SESSION['user_smart']['cliente']['id_cliente']);
date_default_timezone_set('America/Mexico_City');
//$con = mysqli_connect('localhost','root','','wits_sistemaalex');
$con = mysqli_connect('localhost','wits_userBD','ZfX7y99GSs','wits_sistemaalex');
$arreglo = '';
$arreglo2 = '';
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
    
    $sql = "SELECT ID_Compra, FK_Proveedor, (SELECT proveedores.Nombre FROM proveedores WHERE ID_Proveedor = FK_Proveedor) AS NombreProveedor, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, compras.Descuento, detalle_compras.Subtotal, compras.Total, compras.Anticipo, compras.Estatus, compras.Fecha_Registro FROM compras INNER JOIN detalle_compras ON FK_Compra = ID_Compra WHERE ID_Compra = '".$_GET["id"]."'";
    
    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $arreglo = array(
          'ID_Compra'=> $row['ID_Compra'],
          'Proveedor' => $row['NombreProveedor'],  
          'Usuario' => $row['NombreUsuario'], 
          'Descuento' => $row['Descuento'], 
          'Subtotal' => $row['Subtotal'], 
          'Total' => $row['Total'], 
          'Anticipo' => $row['Anticipo'],
          'Estatus' => $row['Estatus'], 
          'Fecha_Registro' => $row['Fecha_Registro']
        );
      }else{
        echo "No se encontraron resultados compras";
      }
    }

    $sql2 = "SELECT ID_Ticket, FK_Sucursal, Imagen, Ruta_Imagen, tickets.Nombre AS MostrarNombre, Domicilio, tickets.Telefono AS MostrarTelefono, tickets.Email AS MostrarEmail, Total_Letras, Incluir_Mensaje, Mensaje, Moneda, Simbolo, Origen, sucursales.Nombre AS NombreSucursal, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, sucursales.Email AS CorreoSucursal, sucursales.Telefono AS TelefonoSucursal, Segundo_Telefono, FK_Zona FROM tickets INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Sucursal = '".$_GET["idSucursal"]."'";
    if($res2=$con->query($sql2)){
      if ($res2->num_rows > 0) {
        $row2 = $res2->fetch_assoc();

        $folio = str_pad($_GET["id"], 8, "0", STR_PAD_LEFT);

        $arreglo2 = array(
          'ID_Ticket' => $row2["ID_Ticket"],
          'FK_Sucursal' => $row2["FK_Sucursal"],
          'MostrarNombre' => $row2["MostrarNombre"],
          'Domicilio' => $row2["Domicilio"],
          'MostrarTelefono' => $row2["MostrarTelefono"],
          'MostrarEmail' => $row2["MostrarEmail"],
          'Total_Letras' => $row2["Total_Letras"],
          'Incluir_Mensaje' => $row2["Incluir_Mensaje"],
          'Mensaje' => $row2["Mensaje"],
          'Moneda' => $row2["Moneda"],
          'Simbolo' => $row2["Simbolo"],
          'Origen' => $row2["Origen"],
          'NombreSucursal' => $row2["NombreSucursal"],
          'Calle' => $row2["Calle"],
          'No_Exterior' => $row2["No_Exterior"],
          'No_Interior' => $row2["No_Interior"],
          'Colonia' => $row2["Colonia"],
          'CP' => $row2["CP"],
          'Ciudad' => $row2["Ciudad"],
          'Estado' => $row2["Estado"],
          'Pais' => $row2["Pais"],
          'TelefonoSucursal' => $row2["TelefonoSucursal"],
          'CorreoSucursal' => $row2["CorreoSucursal"],
          'Segundo_Telefono' => $row2["Segundo_Telefono"],
          'FK_Zona' => $row2["FK_Zona"],
          'Folio' => $folio
        );
      }else{
        echo "No se encontraron resultados ticket";
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

          if ($arreglo2["Domicilio"] == 1) {
            if ($arreglo2["Calle"] != "") {
              echo $arreglo2["Calle"]." ";
            }
            if ($arreglo2["No_Exterior"] != "") {
              echo "Ext: #".$arreglo2["No_Exterior"]." ";
            }
            if ($arreglo2["No_Interior"] != "") {
              echo "Int: # ".$arreglo2["No_Interior"]." ";
            }
            if ($arreglo2["Colonia"] != "") {
              echo "<p>".$arreglo2["Colonia"]."</p>";
            }
            if ($arreglo2["CP"] != "") {
              echo "<p>".$arreglo2["CP"]."</p>";
            }
            if ($arreglo2["Ciudad"] != "") {
              echo "<p>".$arreglo2["Ciudad"]."</p>";
            }
            if ($arreglo2["Estado"] != "") {
              echo "<p>".$arreglo2["Estado"]."</p>";
            }
            if ($arreglo2["Pais"] != "") {
              echo "<p>".$arreglo2["Pais"]."</p>";
            }
          }

          if ($arreglo2["MostrarTelefono"] == 1) {
            echo "<p>".$arreglo2["TelefonoSucursal"]."</p>";
          }

          if ($arreglo2["MostrarEmail"] == 1) {
            echo "<p>".$arreglo2["CorreoSucursal"]."</p>";
          }
        ?>
      </div>
      <?php  
        echo '<p class="centrado">FOLIO: '.$arreglo2['Folio'].'</p>';
        if ($arreglo['Estatus'] == '0') {
          echo '<p class="centrado">Estatus: PENDIENTE</p>';
        }else if ($arreglo['Estatus'] == '1') {
          echo '<p class="centrado">Estatus: COMPLETADA</p>';
        }else if ($arreglo['Estatus'] == '2') {
          echo '<p class="centrado">Estatus: CANCELADA</p>';
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
                echo "No se encontraron resultados detalle";
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
      <p class="centrado"><?php echo $arreglo2['Mensaje'] ?></p>
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