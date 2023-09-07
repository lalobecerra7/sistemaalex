<?php  
  date_default_timezone_set('America/Mexico_City');
  session_start();
  if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
    header('Location: ../index.php');
  }
$con = mysqli_connect('localhost','wits_userBD','ZfX7y99GSs','wits_sistemaalex');
//$con = mysqli_connect('localhost','root','','wits_sistemaalex');
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
    $FechaHoy = date('Y-m-d H:i:s');
    
    $sql = "SELECT ID_Conversion, conversiones.FK_Producto AS FK_Producto, FK_Sucursal, FK_Presentacion_Origen, Cantidad_Origen, FK_Usuario, DATE_FORMAT(conversiones.Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Descripcion, Nombre_Unidad, Abreviatura_Unidad, Nombre, Abreviatura FROM conversiones INNER JOIN productos ON conversiones.FK_Producto = ID_Producto  LEFT JOIN presentaciones ON FK_Presentacion_Origen = ID_Presentacion WHERE ID_Conversion = '".$_GET["id"]."'";

    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $arreglo = array(
          'ID_Conversion' => $row['ID_Conversion'],
          'FK_Producto' => $row['FK_Producto'],
          'FK_Sucursal' => $row['FK_Sucursal'],
          'FK_Presentacion_Origen' => $row['FK_Presentacion_Origen'],
          'Cantidad_Origen' => $row['Cantidad_Origen'],
          'FK_Usuario' => $row['FK_Usuario'],
          'Fecha_Registro' => $row['Fecha_Registro'],
          'Descripcion' => $row['Descripcion'],
          'Nombre_Unidad' => $row['Nombre_Unidad'],
          'Abreviatura_Unidad' => $row['Abreviatura_Unidad'],
          'Nombre' => $row['Nombre'],
          'Abreviatura' => $row['Abreviatura']
        );
      }else{
        echo "No se encontraron resultados compras";
      }
    }

    $sql2 = "SELECT ID_Ticket, FK_Sucursal, Imagen, Ruta_Imagen, tickets.Nombre AS MostrarNombre, Domicilio, tickets.Telefono AS MostrarTelefono, tickets.Email AS MostrarEmail, Total_Letras, Incluir_Mensaje, Mensaje, Moneda, Simbolo, Origen, sucursales.Nombre AS NombreSucursal, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, sucursales.Email AS CorreoSucursal, sucursales.Telefono AS TelefonoSucursal, Segundo_Telefono, FK_Zona FROM tickets INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Sucursal = '".$arreglo["FK_Sucursal"]."'";
    if($res2=$con->query($sql2)){
      if ($res2->num_rows > 0) {
        $row2 = $res2->fetch_assoc();

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
          'FK_Zona' => $row2["FK_Zona"]
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
        <?php  
          echo "<h1>MISCELÁNEA RÍOS</h1>";
          echo "<h2>Conversion</h2>";
          $FechaHoy = date('Y-m-d H:i:s');
          echo '<p>'.$arreglo['Fecha_Registro'].'</p>'; 

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

          echo '<br><br>
          <p><b>Sucursal: '.$arreglo2['NombreSucursal'].'</b></p>
          <br>
          <p><b>'.$arreglo['Descripcion'].'</b></p>
          <p><b>'.$arreglo['Nombre_Unidad'].'</b></p>
          <p><b>'.$arreglo['Abreviatura_Unidad'].'</b></p>
          <br>
          <p><b>Cantidad: '.$arreglo['Cantidad_Origen'].'</b></p>';
        ?>
      </div>
      <br>
      <table class="centrado" width="100%">
        <thead>
          <tr>
            <th class="codigo">Presentación</th>
            <th class="cantidad">Cantidad</th>
          </tr>
        </thead>
        <tbody> 
          <?php 
            $sql = "SELECT ID_Detalle_Conversion, FK_Presentacion, Cantidad, Nombre, Abreviatura FROM detalles_conversion LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Conversion = '".$_GET['id']."'";
            $mostrar= "";
            $subtotal = 0;
            $contador = 0;
            if($res=$con->query($sql)){
              if ($res->num_rows > 0) {
                while($row = $res->fetch_assoc()){
                    $presentacion = '';
                    if($row['FK_Presentacion'] == 0){
                      $presentacion = $arreglo['Nombre_Unidad'];
                      if(trim($arreglo['Abreviatura_Unidad']) != ''){
                        $presentacion .= '('.$arreglo['Abreviatura_Unidad'].')';
                      }
                    }else{
                      $presentacion = $row['Nombre'];
                      if(trim($row['Abreviatura']) != ''){
                        $presentacion .= '('.$row['Abreviatura'].')';
                      }
                    }

                    $mostrar .= "<tr>  
                        <td class='codigo'>".$presentacion."</td>
                        <td class='cantidad'>".number_format($row['Cantidad'], 2)."</td> 
                      </tr>";
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
        //echo '<p class="derecha">Usuario: '.$arreglo['Usuario'].'</p>';
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