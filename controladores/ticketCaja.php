<?php  
date_default_timezone_set('America/Mexico_City');
session_start();
if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
  header('Location: ../index.php');
}

$con = mysqli_connect('localhost','root','','wits_sistemaalex');
$arreglo = '';
$arreglo2 = '';
$totalIngresos = 0; $totalEgresos = 0; $totalventas = 0; $totalimportes = 0;   $totalcompras = 0; $totaldevoluciones = 0; $totalpagos = 0; $totalIngresosEfectivo = 0; $totalEgresosEfectivo = 0;
echo $_GET["idsucursal"];
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
  $sql = "SELECT ID_Detalle_Caja, FK_Caja, Fecha_Abrir, Monto_Abrir, FK_Usuario_Abrir, Fecha_Cierre, Monto_Cierre, FK_Usuario_Cierre, DATE_FORMAT(Fecha_Cierre, '%Y-%m-%d %r') AS FechaCerrar, DATE_FORMAT(Fecha_Abrir, '%Y-%m-%d %r') AS FechaAbrir FROM detalles_caja WHERE ID_Detalle_Caja = '".$_GET["id"]."'";
  if($res=$con->query($sql)){
    if ($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      //************************** INGRESOS ***********************//
      //Ventas
      //Importes
      $totalvefectivo = 0;
      $totalvcheque = 0;
      $totalvdeposito = 0;
      $totalvtarjeta = 0;
      $totalvtransferencia = 0;
      $totalvonline = 0;

      $queryv = "SELECT Total, Tipo_Pago FROM ventas WHERE (Fecha_Registro >= '".$row["Fecha_Abrir"]."' AND Fecha_Registro <= '".$row["Fecha_Cierre"]."') AND Estatus = 'Completada' AND Contar_Venta = 0";
      if($resv=$con->query($queryv)){
        if ($resv->num_rows > 0) {
          while($rowv = $resv->fetch_assoc()){
            $totalIngresos += $rowv["Total"];
            $totalventas += $rowv["Total"];
            if ($rowv["Tipo_Pago"] == "Efectivo") {
              $totalvefectivo += $rowv["Total"];
              $totalIngresosEfectivo +=  $rowv["Total"];
            }else if ($rowv["Tipo_Pago"] == "Deposito") {
              $totalvdeposito += $rowv["Total"];
            }else if ($rowv["Tipo_Pago"] == "Cheque") {
              $totalvcheque += $rowv["Total"];
            }else if ($rowv["Tipo_Pago"] == "TransferenciaBancaria") {
              $totalvtransferencia += $rowv["Total"];
            }else if ($rowv["Tipo_Pago"] == "TarjetaCreditoDebito") {
              $totalvtarjeta += $rowv["Total"];
            }else if ($rowv["Tipo_Pago"] == "PagoOnline") {
              $totalvonline += $rowv["Total"];
            }
          }
        }
      }

      $queryi = "SELECT detalles_importes.Cantidad AS CantidadImportes, importes.Importe AS PrecioImporte FROM detalles_importes INNER JOIN importes ON FK_Importe = ID_Importe WHERE (detalles_importes.Fecha_Registro >= '".$row["Fecha_Abrir"]."' AND detalles_importes.Fecha_Registro <= '".$row["Fecha_Cierre"]."')";
      if($resi=$con->query($queryi)){
        if ($resi->num_rows > 0) {
          while($rowi = $resi->fetch_assoc()){
            $totalIngresos += $rowi["CantidadImportes"] * $rowi["PrecioImporte"];
            $totalimportes += $rowi["CantidadImportes"] * $rowi["PrecioImporte"];
            $totalIngresosEfectivo += $rowi["CantidadImportes"] * $rowi["PrecioImporte"];
          }
        }
      }

      //************************** EGRESOS ************************//
      //Compras al contado
      $query2 = "SELECT Total FROM compras WHERE Estatus = 1 AND Tipo_Compra = 'Contado' AND (Fecha_Registro >= '".$row["Fecha_Abrir"]."' AND Fecha_Registro <= '".$row["Fecha_Cierre"]."')";
      if($resc=$con->query($query2)){
        if ($resc->num_rows > 0) {
          while($rowc = $resc->fetch_assoc()){
            $totalEgresos += $rowc["Total"];
            $totalcompras += $rowc["Total"];
          }
        }
      }

      //Pagos de compras al contado
      $pagoscontadoefectivo = 0;
      $pagoscontadocheque = 0;
      $pagoscontadodeposito = 0;
      $pagostarjetacontado = 0;
      $pagoscontadotransferencia = 0;
      $queryContado = "SELECT Monto, Tipo_Pago FROM pagos INNER JOIN compras ON FK_Compra = ID_Compra WHERE (pagos.Fecha >= '".$row["Fecha_Abrir"]."' AND pagos.Fecha <= '".$row["Fecha_Cierre"]."') AND Estatus = 1 AND Tipo_Compra = 'Contado'";
      if($rescontado=$con->query($queryContado)){
        if ($rescontado->num_rows > 0) {
          while($rowcontado = $rescontado->fetch_assoc()){
            if ($rowcontado["Tipo_Pago"] == "Efectivo") {
              $pagoscontadoefectivo += $rowcontado["Monto"];
              $totalEgresosEfectivo += $rowcontado["Monto"];
            }else if ($rowcontado["Tipo_Pago"] == "Deposito") {
              $pagoscontadodeposito += $rowcontado["Monto"];
            }else if ($rowcontado["Tipo_Pago"] == "Cheque") {
              $pagoscontadocheque += $rowcontado["Monto"];
            }else if ($rowcontado["Tipo_Pago"] == "TransferenciaBancaria") {
              $pagoscontadotransferencia += $rowcontado["Monto"];
            }else if ($rowcontado["Tipo_Pago"] == "TarjetaCreditoDebito") {
              $pagostarjetacontado += $rowcontado["Monto"];  
            }
          }
        }
      }

      //Pagos
      $pagosefectivo = 0;
      $pagoscheque = 0;
      $pagosdeposito = 0;
      $pagostarjeta = 0;
      $pagostransferencia = 0;
      $querypagos = "SELECT Monto, Tipo_Pago FROM pagos INNER JOIN compras ON FK_Compra = ID_Compra WHERE (pagos.Fecha >= '".$row["Fecha_Abrir"]."' AND pagos.Fecha <= '".$row["Fecha_Cierre"]."') AND Tipo_Compra = 'Credito'";
      if($resp=$con->query($querypagos)){
        if ($resp->num_rows > 0) {
          while($rowp = $resp->fetch_assoc()){
            $totalEgresos += $rowp["Monto"];
            $totalpagos += $rowp["Monto"];
            if ($rowp["Tipo_Pago"] == "Efectivo") {
              $pagosefectivo += $rowp["Monto"];
              $totalEgresosEfectivo += $rowp["Monto"];
            }else if ($rowp["Tipo_Pago"] == "Deposito") {
              $pagosdeposito += $rowp["Monto"];
            }else if ($rowp["Tipo_Pago"] == "Cheque") {
              $pagoscheque += $rowp["Monto"];
            }else if ($rowp["Tipo_Pago"] == "TransferenciaBancaria") {
              $pagostransferencia += $rowp["Monto"];
            }else if ($rowp["Tipo_Pago"] == "TarjetaCreditoDebito") {
              $pagostarjeta += $rowp["Monto"]; 
            }
          }
        }
      }

      //Devoluciones
      $querydev = "SELECT Total FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE (devoluciones.Fecha_Registro >= '".$row["Fecha_Abrir"]."' AND devoluciones.Fecha_Registro <= '".$row["Fecha_Cierre"]."')";
      if($resdev=$con->query($querydev)){
        if ($resdev->num_rows > 0) {
          while($rowdev = $resdev->fetch_assoc()){
            $totalEgresos += $rowdev["Total"];
            $totaldevoluciones += $rowdev["Total"];
            $totalEgresosEfectivo += $rowdev["Total"];
          }
        }
      }

      $arreglo = array(
        "ID_Detalle_Caja" => $row["ID_Detalle_Caja"],
        "Fecha_Abrir" => $row["FechaAbrir"],
        "Fecha_Cerrar" => $row["FechaCerrar"],
        "Monto_Abrir" => $row["Monto_Abrir"],
        "Monto_Cierre" => $row["Monto_Cierre"],
        "Total_Ingresos" => ($totalIngresos + $row["Monto_Abrir"]),
        "Total_Ingresos_Efectivo" => $totalIngresosEfectivo,
        "Total_Egresos_Efectivo" => $totalEgresosEfectivo,
        "Total_Egresos" => $totalEgresos,
        "Total_Utilidad" => ($totalIngresos - $totalEgresos),
        "Total_Ventas" => $totalventas,
        "Total_Ventas_Efectivo" => $totalvefectivo,
        "Total_Ventas_Deposito" => $totalvdeposito,
        "Total_Ventas_Cheque" => $totalvcheque,
        "Total_Ventas_TransferenciaBancaria" => $totalvtransferencia,
        "Total_Ventas_TarjetaCreditoDebito" => $totalvtarjeta,
        "Total_Ventas_PagoOnline" => $totalvonline,
        "Total_Importes" => $totalimportes,
        "Total_Compras" => $totalcompras,
        "Total_Compras_Efectivo" => $pagoscontadoefectivo,
        "Total_Compras_Cheque" => $pagoscontadocheque,
        "Total_Compras_Deposito" => $pagoscontadodeposito,
        "Total_Compras_Tarjeta" => $pagostarjetacontado,
        "Total_Compras_Transferencia" => $pagoscontadotransferencia,
        "Total_Pagos" => $totalpagos,
        "Total_Pagos_Efectivo" => $pagosefectivo,
        "Total_Pagos_Deposito" => $pagosdeposito,
        "Total_Pagos_Cheque" => $pagoscheque,
        "Total_Pagos_TransferenciaBancaria" => $pagostransferencia,
        "Total_Pagos_TarjetaCreditoDebito" => $pagostarjeta,
        "Total_Devoluciones" => $totaldevoluciones,
      );
    }
  }

  /*if ($_GET["idsucursal"] != "") {
    $sql2 = "SELECT ID_Ticket, FK_Sucursal, Imagen, Ruta_Imagen, tickets.Nombre AS MostrarNombre, Domicilio, tickets.Telefono AS MostrarTelefono, tickets.Email AS MostrarEmail, Total_Letras, Incluir_Mensaje, Mensaje, Moneda, Simbolo, Origen, sucursales.Nombre AS NombreSucursal, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, sucursales.Email AS CorreoSucursal, sucursales.Telefono AS TelefonoSucursal, Segundo_Telefono, FK_Zona FROM tickets INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Sucursal = '".$_GET["idsucursal"]."'";
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
          'FK_Zona' => $row2["FK_Zona"],
        );
      }else{
        echo "No se encontraron resultados ticket";
      }
    }
  }else{*/
    $idSucursal = 1;
    //CONSULTAR SUCURSAL USUARIO
    $sqlSucursal = "SELECT FK_Sucursal FROM usuarios WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
    if($resSucursal=$con->query($sqlSucursal)){
      if ($resSucursal->num_rows > 0) {
        $rowSucursal = $resSucursal->fetch_assoc();
        if ($rowSucursal["FK_Sucursal"] == 0) {
          $idSucursal = 1;
        }else{
          $idSucursal = $rowSucursal["FK_Sucursal"];
        }
      }
    }
    $sql2 = "SELECT ID_Ticket, FK_Sucursal, Imagen, Ruta_Imagen, tickets.Nombre AS MostrarNombre, Domicilio, tickets.Telefono AS MostrarTelefono, tickets.Email AS MostrarEmail, Total_Letras, Incluir_Mensaje, Mensaje, Moneda, Simbolo, Origen, sucursales.Nombre AS NombreSucursal, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, sucursales.Email AS CorreoSucursal, sucursales.Telefono AS TelefonoSucursal, Segundo_Telefono, FK_Zona FROM tickets INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Sucursal = '".$idSucursal."'";
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
          'FK_Zona' => $row2["FK_Zona"],
        );
      }else{
        echo "No se encontraron resultados ticket";
      }
    }



  //}

  

  ?>

  <div class="ticket">
    <br class="oculto-impresion">
    <p class="centrado">
      <button class="oculto-impresion" onclick="imprimir()">IMPRIMIR TICKET</button>
    </p>
    <br class="oculto-impresion">
    <div class="centrado">
      <?php echo "<h1>MISCELÁNEA RÍOS</h1>"; ?>
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
    echo '<p class="centrado">CORTE DE CAJA</p>';
    echo '<p class="centrado">ABRIR CAJA: '.$arreglo["Fecha_Abrir"].'</p>';
    echo '<p class="centrado">CERRAR CAJA: '.$arreglo["Fecha_Cerrar"].'</p>';
    /*echo '<p class="centrado">MONTO DE APERTURA: $'.number_format($arreglo["Monto_Abrir"], 2).'</p>';
    echo '<p class="centrado">MONTO DE CIERRE: $'.number_format($arreglo["Monto_Cierre"], 2).'</p>';*/
    echo "</br>
    <p class='centrado'><b style='font-size: 20px;'>INGRESOS</b></p>
    ";
    echo '<p class="centrado">MONTO DE APERTURA: $'.number_format($arreglo["Monto_Abrir"], 2).'</p>';
    echo '<p class="centrado">TOTAL DE VENTAS: $'.number_format($arreglo["Total_Ventas"], 2).'</p>';

    if ($arreglo["Total_Ventas_Efectivo"]) {
      echo '<p class="">VENTAS EN EFECTIVO: $'.number_format($arreglo["Total_Ventas_Efectivo"], 2).'</p>';
    }
    if ($arreglo["Total_Ventas_Deposito"]) {
      echo '<p class="">VENTAS EN DEPÓSITO: $'.number_format($arreglo["Total_Ventas_Deposito"], 2).'</p>';
    }
    if ($arreglo["Total_Ventas_Cheque"]) {
      echo '<p class="">VENTAS EN CHEQUE: $'.number_format($arreglo["Total_Ventas_Cheque"], 2).'</p>';
    }
    if ($arreglo["Total_Ventas_TransferenciaBancaria"]) {
      echo '<p class="">VENTAS EN TRANSFERENCIA: $'.number_format($arreglo["Total_Ventas_TransferenciaBancaria"], 2).'</p>';
    }
    if ($arreglo["Total_Ventas_TarjetaCreditoDebito"]) {
      echo '<p class="">VENTAS EN TARJETA DE CRÉDITO / DEBITO: $'.number_format($arreglo["Total_Ventas_TarjetaCreditoDebito"], 2).'</p>';
    }
    if ($arreglo["Total_Ventas_PagoOnline"]) {
      echo '<p class="">VENTAS EN PAGOS ONLINE: $'.number_format($arreglo["Total_Ventas_PagoOnline"], 2).'</p>';
    }

    echo '<p class="centrado">TOTAL DE IMPORTES: $'.number_format($arreglo["Total_Importes"], 2).'</p>';
    echo "</br>
    <p class='centrado'><b style='font-size: 20px;'>EGRESOS</b></p>
    ";

    echo '<p class="centrado">TOTAL DE COMPRAS: $'.number_format($arreglo["Total_Compras"], 2).'</p>';

    if ($arreglo["Total_Compras_Efectivo"]) {
      echo '<p class="">COMPRAS EN EFECTIVO: $'.number_format($arreglo["Total_Compras_Efectivo"], 2).'</p>';
    }
    if ($arreglo["Total_Compras_Deposito"]) {
      echo '<p class="">COMPRAS EN DEPÓSITO: $'.number_format($arreglo["Total_Compras_Deposito"], 2).'</p>';
    }
    if ($arreglo["Total_Compras_Cheque"]) {
      echo '<p class="">COMPRAS EN CHEQUE: $'.number_format($arreglo["Total_Compras_Cheque"], 2).'</p>';
    }
    if ($arreglo["Total_Compras_Transferencia"]) {
      echo '<p class="">COMPRAS EN TRANSFERENCIA: $'.number_format($arreglo["Total_Compras_Transferencia"], 2).'</p>';
    }
    if ($arreglo["Total_Compras_Tarjeta"]) {
      echo '<p class="">COMPRAS EN TARJETA DE CRÉDITO / DEBITO: $'.number_format($arreglo["Total_Compras_Tarjeta"], 2).'</p>';
    }

    echo '<p class="centrado">TOTAL DE DEVOLUCIONES: $'.number_format($arreglo["Total_Devoluciones"], 2).'</p>';

    echo '<p class="centrado">TOTAL DE PAGOS: $'.number_format($arreglo["Total_Pagos"], 2).'</p>';

    if ($arreglo["Total_Pagos_Efectivo"]) {
      echo '<p class="">PAGOS EN EFECTIVO: $'.number_format($arreglo["Total_Pagos_Efectivo"], 2).'</p>';
    }
    if ($arreglo["Total_Pagos_Deposito"]) {
      echo '<p class="">PAGOS EN DEPÓSITO: $'.number_format($arreglo["Total_Pagos_Deposito"], 2).'</p>';
    }
    if ($arreglo["Total_Pagos_Cheque"]) {
      echo '<p class="">PAGOS EN CHEQUE: $'.number_format($arreglo["Total_Pagos_Cheque"], 2).'</p>';
    }
    if ($arreglo["Total_Pagos_TransferenciaBancaria"]) {
      echo '<p class="">PAGOS EN TRANSFERENCIA: $'.number_format($arreglo["Total_Pagos_TransferenciaBancaria"], 2).'</p>';
    }
    if ($arreglo["Total_Pagos_TarjetaCreditoDebito"]) {
      echo '<p class="">PAGOS EN TARJETA DE CRÉDITO / DEBITO: $'.number_format($arreglo["Total_Pagos_TarjetaCreditoDebito"], 2).'</p>';
    }
    echo "</br>
    <p class='centrado'><b style='font-size: 12px;'>INGRESOS EN EFECTIVO: $".number_format($arreglo["Total_Ingresos_Efectivo"], 2)."</b></p>
    ";
    echo "</br>
    <p class='centrado'><b style='font-size: 12px;'>EGRESOS EN EFECTIVO: $".number_format($arreglo["Total_Egresos_Efectivo"], 2)."</b></p>
    ";
    $totalActualCaja = $arreglo["Total_Ingresos_Efectivo"] - $arreglo["Total_Egresos_Efectivo"]; 
    echo "</br>
    <p class='centrado'><b style='font-size: 12px;'>TOTAL EFECTIVO: $".number_format($totalActualCaja, 2)."</b></p><hr>
    ";
    echo "</br>
    <p class='centrado'><b style='font-size: 17px;'>MONTO DE CIERRE: $".number_format($arreglo["Monto_Cierre"], 2)."</b></p>
    ";
    echo "</br>
    <p class='centrado'><b style='font-size: 17px;'>MONTO EN LA CAJA (Efectivo): $".number_format($totalActualCaja, 2)."</b></p>
    ";
    $diferenciacaja = $arreglo["Monto_Cierre"] - $totalActualCaja;
    echo "</br>
    <p class='centrado'><b style='font-size: 17px;'>DIFERENCIA: $".number_format($diferenciacaja, 2)."</b></p>
    ";
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