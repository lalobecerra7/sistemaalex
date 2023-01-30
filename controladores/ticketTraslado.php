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
    $FechaHoy = date('Y-m-d H:i:s');
    
    $sql = "SELECT ID_Traslado, LPAD(ID_Traslado, 8, 0) AS FolioTraslado, Estatus, Detalles, FK_Sucursal_Origen, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Origen) AS Origen, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Destino) AS Destino, DATE_FORMAT(Fecha_Traslado, '%d-%m-%Y') AS Fecha_Traslado, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, FK_Usuario FROM traslados WHERE ID_Traslado = '".$_GET["id"]."'";

    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $arreglo = array(
          'ID_Traslado' => $row['ID_Traslado'],
          'FolioTraslado' => $row['FolioTraslado'],
          'Estatus' => $row['Estatus'],
          'Detalles' => $row['Detalles'],
          'FK_Sucursal_Origen' => $row['FK_Sucursal_Origen'],
          'Origen' => $row['Origen'],
          'Destino' => $row['Destino'],
          'Fecha_Traslado' => $row['Fecha_Traslado'],
          'Fecha_Registro' => $row['Fecha_Registro'],
          'FK_Usuario' => $row['FK_Usuario']
        );
      }else{
        echo "No se encontraron resultados compras";
      }
    }

    $sql2 = "SELECT ID_Ticket, FK_Sucursal, Imagen, Ruta_Imagen, tickets.Nombre AS MostrarNombre, Domicilio, tickets.Telefono AS MostrarTelefono, tickets.Email AS MostrarEmail, Total_Letras, Incluir_Mensaje, Mensaje, Moneda, Simbolo, Origen, sucursales.Nombre AS NombreSucursal, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, sucursales.Email AS CorreoSucursal, sucursales.Telefono AS TelefonoSucursal, Segundo_Telefono, FK_Zona FROM tickets INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Sucursal = '".$arreglo["FK_Sucursal_Origen"]."'";
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
          echo "<h2>Traslado</h2>";
          echo '<p>Folio: '.$arreglo['FolioTraslado'].'</p>';   
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
          <p><b>Sucursal Origen: '.$arreglo['Origen'].'</b></p>
          <br>
          <p><b>Sucursal Destino: '.$arreglo['Destino'].'</b></p>
          <br>
          <p><b>Estatus: '.$arreglo['Estatus'].'</b></p>
          <p><b>'.$arreglo['Detalles'].'</b></p><br>';
        ?>
      </div>
      <br>
      <table class="centrado" width="100%">
        <thead>
          <tr>
            <th class="codigo">Código</th>
            <th class="cantidad">Cantidad</th>
          </tr>
        </thead>
        <tbody> 
          <?php 
            $sql = "SELECT ID_Detalle_Traslado, Codigo, Descripcion, Nombre_Unidad, Abreviatura_Unidad, FK_Presentacion, Cantidad, Nombre, Abreviatura FROM detalles_traslados INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Traslado = '".$_GET['id']."'";
            $mostrar= "";
            $subtotal = 0;
            $contador = 0;
            if($res=$con->query($sql)){
              if ($res->num_rows > 0) {
                while($row = $res->fetch_assoc()){
                    $presentacion = '';
                    if($row['FK_Presentacion'] == 0){
                      $presentacion = $row['Nombre_Unidad'];
                      if(trim($row['Abreviatura_Unidad']) != ''){
                        $presentacion .= '('.$row['Abreviatura_Unidad'].')';
                      }
                    }else{
                      $presentacion = $row['Nombre'];
                      if(trim($row['Abreviatura']) != ''){
                        $presentacion .= '('.$row['Abreviatura'].')';
                      }
                    }

                    $mostrar .= "<tr>
                        <td colspan='1' style='text-align: left;'>".$row['Descripcion'].' '.$presentacion."</td>
                    </tr>
                    <tr>  
                        <td class='codigo'>".$row['Codigo']."</td>
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