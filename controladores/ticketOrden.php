<?php  
  date_default_timezone_set('America/Mexico_City');
  session_start();
  if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
    header('Location: ../index.php');
  }

$con = mysqli_connect('localhost','root','','wits_sistemaalex');
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
      /*background: #000;*/
      color: #000;
    }

    .negra b{
      font-size: 20px;
    }

    .oculto {
      display: none !important;
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
    $permisosMo = null;
    $sql = "SELECT Permisos, Tipo_Usuario FROM usuarios WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";

    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        if($row['Tipo_Usuario'] == 'Administrador'){
          $permisosMo = 'Administrador';
        }else{
          $modulos = explode('~', $row['Permisos']);

          for ($i=0; $i < count($modulos); $i++) {
            $cadena = explode(',', $modulos[$i]);
            $nombreModu = $cadena[0];
            unset($cadena[0]);
            $permisosMo[$nombreModu] = $cadena;
          }
        }
      }
    }

    $FechaHoy = date('Y-m-d H:i:s');
    
    $sql = "SELECT ID_Orden_Compra, FK_Proveedor, (SELECT proveedores.Nombre FROM proveedores WHERE ID_Proveedor = FK_Proveedor) AS NombreProveedor, FK_Usuario, (SELECT usuarios.Nombre FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, Descuento, Total, Estatus, Fecha_Registro FROM ordenes_compra WHERE ID_Orden_Compra = '".$_GET["id"]."'";
    
    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $arreglo = array(
          'ID_Orden_Compra'=> $row['ID_Orden_Compra'],
          'Proveedor' => $row['NombreProveedor'],  
          'Usuario' => $row['NombreUsuario'], 
          'Descuento' => $row['Descuento'],
          'Total' => $row['Total'], 
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
        echo '<p class="centrado">FOLIO: '.$arreglo2['Folio'].'</p>
        <p class="centrado">Estatus: '.$arreglo['Estatus'].'</p>';
      ?>
      <br>
      <table class="centrado" width="100%">
        <thead>
          <tr>
            <?php  
              $clase = ''; 
              if($permisosMo != 'Administrador'){
                if(@$permisosMo['v_compras'][2] == '0' || @$permisosMo['v_ordenes_compra'][5] == '0'){
                  $clase = 'oculto'; 
                }
              }

              echo '<th class="codigo">Cód.</th>
              <th class="cantidad">Cant.</th>
              <th class="precio '.$clase.'">Costo. Unit.</th>  
              <th class="precio '.$clase.'">Importe</th>';
            ?>
          </tr>
        </thead>
        <tbody> 
          <?php 
            $sql = "SELECT ID_Detalle_Orden, FK_Presentacion, productos.Descripcion AS NombreProducto, Nombre_Unidad, Abreviatura_Unidad, detalles_orden.Costo AS Costo, Cantidad, Subtotal, productos.Codigo, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura FROM detalles_orden INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Orden = '".$arreglo['ID_Orden_Compra']."'";
            $mostrar= "";
            $subtotal = 0;
            $contador = 0;
            if($res=$con->query($sql)){
              if ($res->num_rows > 0) {
                while($row = $res->fetch_assoc()){
                    $presentacion = ' - '.$row['Nombre_Unidad'].' ('.$row['Abreviatura_Unidad'].')';
                    if($row['FK_Presentacion'] != 0){
                      $presentacion = ' - '.$row['Presentacion'].' ('.$row['Abreviatura'].')';
                    }

                    if($presentacion == ' -  ()'){
                      $presentacion = '';
                    }

                    $mostrar .= "<tr>
                        <td style='text-align: left;' class='producto' colspan='4'>".$row["NombreProducto"].$presentacion."</td>
                      </tr>  
                      <tr>  
                        <td class='codigo'>".$row["Codigo"]."</td>
                        <td class='cantidad'>".(round($row['Cantidad']*100)/100)."</td> 
                        <td class='precio ".$clase."'>$".(round($row['Costo']*100)/100)."</td>
                        <td class='precio ".$clase."'>$".(round($row['Subtotal']*100)/100)."</td>
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
        echo '<p class="derecha '.$clase.'" style="font-size: 15px;">Subtotal: <b style="font-size: 15px;">$'.(round(($subtotal)*100)/100).'</b></p>';  
        echo '<p class="derecha '.$clase.'" style="font-size: 15px;">Descuento: <b style="font-size: 15px;">$'.(round($arreglo['Descuento']*100)/100).'</b></p>'; 
        echo "</br>
          <p class='derecha negra ".$clase."'><b>TOTAL: $".(round($arreglo['Total']*100)/100)."</b></p>
        ";
        
        echo '<p class="derecha">Usuario: '.$arreglo['Usuario'].'</p>';
      ?>
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