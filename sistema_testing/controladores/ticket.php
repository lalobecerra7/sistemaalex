<?php  
include('pdf/phpqrcode/qrlib.php');

  session_start();
  if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
    header('Location: ../index.php');
  }

date_default_timezone_set('America/Mexico_City');
$con = mysqli_connect('localhost','miscelanearios_alex','Sistemaalex_2024','miscelanearios_sistemaalex2025');
//$con = mysqli_connect('localhost','root','','wits_sistemaalex3');
$arreglo = '';
$arregloVenta = '';

  function _valorEnLetras($x, $mos){ 
    if ($x<0) { 
      $signo = "menos ";
    } 
    else      { $signo = "";} 
    $x = abs ($x); 
    $C1 = $x; 

    $G6 = floor($x/(1000000));  // 7 y mas 

    $E7 = floor($x/(100000)); 
    $G7 = $E7-$G6*10;   // 6 

    $E8 = floor($x/1000); 
    $G8 = $E8-$E7*100;   // 5 y 4 

    $E9 = floor($x/100); 
    $G9 = $E9-$E8*10;  //  3 

    $E10 = floor($x); 
    $G10 = $E10-$E9*100;  // 2 y 1 


    $G11 = round(($x-$E10)*100,0);  // Decimales 
    ////////////////////// 

    $H6 = unidades($G6); 

    if($G7==1 AND $G8==0) { 
      $H7 = "Cien "; 
    } 
    else {    $H7 = decenas($G7); } 

    $H8 = unidades($G8); 

    if($G9==1 AND $G10==0) { 
      $H9 = "Cien ";
    } 
    else {    $H9 = decenas($G9); } 

    $H10 = unidades($G10); 

    if($G11 < 10) { $H11 = "0".$G11; } 
    else { $H11 = $G11; } 

    ///////////////////////////// 
        if($G6==0) { $I6=" "; } 
    elseif($G6==1) {
      $I6="Millón "; 
    } 
    else { 
      $I6="Millones ";      
    } 
              
    if ($G8==0 AND $G7==0) { $I8=" "; } 
            else { 
          $I8="Mil "; 
            } 
              
    if($mos == 1){
      $I10 = "PESOS "; 
      $I11 = "/100 MONEDA NACIONAL DE MÉXICO";  
    }else{
      $I10 = ""; 
      $I11 = "";
      $H11 = "";
    }

    $C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$I10.$H11.$I11; 

    return $C3; //Retornar el resultado 
  } 

  function decenas($d){   
    if ($d==0)  {$rd = "";} 
    elseif ($d==1)  {$rd = "Ciento ";} 
    elseif ($d==2)  {$rd = "Doscientos ";} 
    elseif ($d==3)  {$rd = "Trescientos ";} 
    elseif ($d==4)  {$rd = "Cuatrocientos ";} 
    elseif ($d==5)  {$rd = "Quinientos ";} 
    elseif ($d==6)  {$rd = "Seiscientos ";} 
    elseif ($d==7)  {$rd = "Setecientos ";} 
    elseif ($d==8)  {$rd = "Ochocientos ";} 
    else            {$rd = "Novecientos ";} 

    return $rd; //Retornar el resultado 
  }

  function unidades($u){
    if ($u==0)  {$ru = " ";} 
    elseif ($u==1)  {$ru = "Un ";} 
    elseif ($u==2)  {$ru = "Dos ";} 
    elseif ($u==3)  {$ru = "Tres ";} 
    elseif ($u==4)  {$ru = "Cuatro ";} 
    elseif ($u==5)  {$ru = "Cinco ";} 
    elseif ($u==6)  {$ru = "Seis ";} 
    elseif ($u==7)  {$ru = "Siete ";} 
    elseif ($u==8)  {$ru = "Ocho ";} 
    elseif ($u==9)  {$ru = "Nueve ";} 
    elseif ($u==10) {$ru = "Diez ";} 

    elseif ($u==11) {$ru = "Once ";} 
    elseif ($u==12) {$ru = "Doce ";} 
    elseif ($u==13) {$ru = "Trece ";} 
    elseif ($u==14) {$ru = "Catorce ";} 
    elseif ($u==15) {$ru = "Quince ";} 
    elseif ($u==16) {$ru = "Dieciseis ";} 
    elseif ($u==17) {$ru = "Decisiete ";} 
    elseif ($u==18) {$ru = "Dieciocho ";} 
    elseif ($u==19) {$ru = "Diecinueve ";} 
    elseif ($u==20) {$ru = "Veinte ";} 
    elseif ($u==21) {$ru = "Veintiun ";} 
    elseif ($u==22) {$ru = "Veintidos ";} 
    elseif ($u==23) {$ru = "Veintitres ";} 
    elseif ($u==24) {$ru = "Veinticuatro ";} 
    elseif ($u==25) {$ru = "Veinticinco ";} 
    elseif ($u==26) {$ru = "Veintiseis ";} 
    elseif ($u==27) {$ru = "Veintisiente ";} 
    elseif ($u==28) {$ru = "Veintiocho ";} 
    elseif ($u==29) {$ru = "Veintinueve ";} 
    elseif ($u==30) {$ru = "Treinta";} 

    elseif ($u==31) {$ru = "Treinta y un ";} 
    elseif ($u==32) {$ru = "Treinta y dos ";} 
    elseif ($u==33) {$ru = "Treinta y tres ";} 
    elseif ($u==34) {$ru = "Treinta y cuatro ";} 
    elseif ($u==35) {$ru = "Treinta y cinco ";} 
    elseif ($u==36) {$ru = "Treinta y seis ";} 
    elseif ($u==37) {$ru = "Treinta y siete ";} 
    elseif ($u==38) {$ru = "Treinta y ocho ";} 
    elseif ($u==39) {$ru = "Treinta y nueve ";} 
    elseif ($u==40) {$ru = "Cuarenta ";} 

    elseif ($u==41) {$ru = "Cuarenta y un ";} 
    elseif ($u==42) {$ru = "Cuarenta y dos ";} 
    elseif ($u==43) {$ru = "Cuarenta y tres ";} 
    elseif ($u==44) {$ru = "Cuarenta y cuatro ";} 
    elseif ($u==45) {$ru = "Cuarenta y cinco ";} 
    elseif ($u==46) {$ru = "Cuarenta y seis ";} 
    elseif ($u==47) {$ru = "Cuarenta y siete ";} 
    elseif ($u==48) {$ru = "Cuarenta y ocho ";} 
    elseif ($u==49) {$ru = "Cuarenta y nueve ";} 
    elseif ($u==50) {$ru = "Cincuenta ";} 

    elseif ($u==51) {$ru = "Cincuenta y un ";} 
    elseif ($u==52) {$ru = "Cincuenta y dos ";} 
    elseif ($u==53) {$ru = "Cincuenta y tres ";} 
    elseif ($u==54) {$ru = "Cincuenta y cuatro ";} 
    elseif ($u==55) {$ru = "Cincuenta y cinco ";} 
    elseif ($u==56) {$ru = "Cincuenta y seis ";} 
    elseif ($u==57) {$ru = "Cincuenta y siete ";} 
    elseif ($u==58) {$ru = "Cincuenta y ocho ";} 
    elseif ($u==59) {$ru = "Cincuenta y nueve ";} 
    elseif ($u==60) {$ru = "Sesenta ";} 

    elseif ($u==61) {$ru = "Sesenta y un ";} 
    elseif ($u==62) {$ru = "Sesenta y dos ";} 
    elseif ($u==63) {$ru = "Sesenta y tres ";} 
    elseif ($u==64) {$ru = "Sesenta y cuatro ";} 
    elseif ($u==65) {$ru = "Sesenta y cinco ";} 
    elseif ($u==66) {$ru = "Sesenta y seis ";} 
    elseif ($u==67) {$ru = "Sesenta y siete ";} 
    elseif ($u==68) {$ru = "Sesenta y ocho ";} 
    elseif ($u==69) {$ru = "Sesenta y nueve ";} 
    elseif ($u==70) {$ru = "Setenta ";} 

    elseif ($u==71) {$ru = "Setenta y un ";} 
    elseif ($u==72) {$ru = "Setenta y dos ";} 
    elseif ($u==73) {$ru = "Setenta y tres ";} 
    elseif ($u==74) {$ru = "Setenta y cuatro ";} 
    elseif ($u==75) {$ru = "Setenta y cinco ";} 
    elseif ($u==76) {$ru = "Setenta y seis ";} 
    elseif ($u==77) {$ru = "Setenta y siete ";} 
    elseif ($u==78) {$ru = "Setenta y ocho ";} 
    elseif ($u==79) {$ru = "Setenta y nueve ";} 
    elseif ($u==80) {$ru = "Ochenta ";} 

    elseif ($u==81) {$ru = "Ochenta y un ";} 
    elseif ($u==82) {$ru = "Ochenta y dos ";} 
    elseif ($u==83) {$ru = "Ochenta y tres ";} 
    elseif ($u==84) {$ru = "Ochenta y cuatro ";} 
    elseif ($u==85) {$ru = "Ochenta y cinco ";} 
    elseif ($u==86) {$ru = "Ochenta y seis ";} 
    elseif ($u==87) {$ru = "Ochenta y siete ";} 
    elseif ($u==88) {$ru = "Ochenta y ocho ";} 
    elseif ($u==89) {$ru = "Ochenta y nueve ";} 
    elseif ($u==90) {$ru = "Noventa ";} 

    elseif ($u==91) {$ru = "Noventa y un ";} 
    elseif ($u==92) {$ru = "Noventa y dos ";} 
    elseif ($u==93) {$ru = "Noventa y tres ";} 
    elseif ($u==94) {$ru = "Noventa y cuatro ";} 
    elseif ($u==95) {$ru = "Noventa y cinco ";} 
    elseif ($u==96) {$ru = "Noventa y seis ";} 
    elseif ($u==97) {$ru = "Noventa y siete ";} 
    elseif ($u==98) {$ru = "Noventa y ocho ";} 
    else            {$ru = "Noventa y nueve ";} 

    return $ru; //Retornar el resultado 
  } 

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ticket</title>
  <style>
    * {
      font-size: 20px;
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
    
    $sql = "SELECT NOW() AS FechaHoy, ID_Ticket, FK_Sucursal, Imagen, Ruta_Imagen, tickets.Nombre AS MostrarNombre, Domicilio, tickets.Telefono AS MostrarTelefono, tickets.Email AS MostrarEmail, Total_Letras, Incluir_Mensaje, Mensaje, Moneda, Simbolo, Origen, sucursales.Nombre AS NombreSucursal, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, sucursales.Email AS CorreoSucursal, sucursales.Telefono AS TelefonoSucursal, Segundo_Telefono, FK_Zona FROM tickets INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Sucursal = '".$_GET["idSucursal"]."'";
    if($res=$con->query($sql)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        $folio = str_pad($_GET["id"], 8, "0", STR_PAD_LEFT);

        $arreglo = array(
          'ID_Ticket' => $row["ID_Ticket"],
          'FK_Sucursal' => $row["FK_Sucursal"],
          'MostrarNombre' => $row["MostrarNombre"],
          'Domicilio' => $row["Domicilio"],
          'MostrarTelefono' => $row["MostrarTelefono"],
          'MostrarEmail' => $row["MostrarEmail"],
          'Total_Letras' => $row["Total_Letras"],
          'Incluir_Mensaje' => $row["Incluir_Mensaje"],
          'Mensaje' => $row["Mensaje"],
          'Moneda' => $row["Moneda"],
          'Simbolo' => $row["Simbolo"],
          'Origen' => $row["Origen"],
          'NombreSucursal' => $row["NombreSucursal"],
          'Calle' => $row["Calle"],
          'No_Exterior' => $row["No_Exterior"],
          'No_Interior' => $row["No_Interior"],
          'Colonia' => $row["Colonia"],
          'CP' => $row["CP"],
          'Ciudad' => $row["Ciudad"],
          'Estado' => $row["Estado"],
          'Pais' => $row["Pais"],
          'TelefonoSucursal' => $row["TelefonoSucursal"],
          'CorreoSucursal' => $row["CorreoSucursal"],
          'Segundo_Telefono' => $row["Segundo_Telefono"],
          'FK_Zona' => $row["FK_Zona"],
          'Folio' => $folio,
          'FechaHoy' => $row["FechaHoy"]
        );
      }else{
        echo "No se encontraron resultados";
      }
    }


    $sql2 = "SELECT ID_Venta, FK_Direccion, Estatus, FK_Usuario, (SELECT CONCAT(Nombre,' ',Primer_Apellido) FROM usuarios WHERE ID_Usuario = FK_Usuario) AS NombreUsuario, FK_Sucursal, (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal) AS NombreSucursal, FK_Cliente,  (SELECT CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) FROM clientes WHERE ID_Cliente = FK_Cliente) AS NombreCliente, (SELECT Telefono FROM clientes WHERE ID_Cliente = FK_Cliente) AS TelefonoCliente, (SELECT Celular FROM clientes WHERE ID_Cliente = FK_Cliente) AS CelularCliente, Descuento, Total, Total_Importes, Tipo_Pago, Pago, Cambio, Notas, Fecha_Registro, Fecha_Cancelacion, Regreso_Inventario, 

    (SELECT CONCAT(Calle,' ',No_Exterior,', Colonia:', Colonia) FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion) AS DireccionCalleCliente, 

    (SELECT Ciudad FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion) AS DireccionCiudadCliente, 

    (SELECT CONCAT(Latitud,', ',Longitud) FROM detalles_clientes WHERE ID_Detalle_Cliente = FK_Direccion) AS CoordenadasCliente, 

    (SELECT CONCAT(Calle,' ',No_Exterior,', Colonia:', Colonia) FROM clientes WHERE FK_Cliente = ID_Cliente) AS CalleCliente, IFNULL((SELECT Nombre FROM rutas WHERE ID_Ruta = (SELECT FK_Ruta FROM clientes WHERE ID_Cliente = FK_Cliente)), '') AS Ruta, IFNULL((SELECT Orden_Ruta FROM clientes WHERE ID_Cliente = FK_Cliente), '') AS Orden, (SELECT Ciudad FROM clientes WHERE FK_Cliente = ID_Cliente) AS CiudadCliente, Facturada, Version_CFDI, Fecha_Expedicion_CFDI, Sello_CFDI, Forma_Pago_CFDI, No_Certificado_CFDI, Certificado_CFDI, Moneda_CFDI, Tipo_Comprobante_CFDI, Exportacion_CFDI, Metodo_Pago_CFDI, Lugar_Expedicion_CFDI, Confirmacion_CFDI, Emisor_RFC_CFDI, Emisor_Nombre_CFDI, Emisor_Regimen_Fiscal_CFDI, Receptor_RFC_CFDI, Receptor_Nombre_CFDI, Receptor_Domicilio_CFDI, Receptor_Regimen_Fiscal_CFDI, Receptor_Uso_CFDI, UUID_CFDI, Fecha_Timbrado_CFDI, Rfc_ProvCertif_CFDI, Sello_CFD_CFDI, No_Certificado_SAT_CFDI, Sello_SAT_CFDI, Periodicidad_CFDI, Meses_CFDI, Ano_CFDI, Relacion_CFDI, Cadena_CFDI FROM ventas WHERE ID_Venta = '".$_GET["id"]."'";

    if($res=$con->query($sql2)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        $folio = str_pad($_GET["id"], 8, "0", STR_PAD_LEFT);

        $direccionCliente = '';
        if ($row["FK_Cliente"] == 1) {
          $direccionCliente = '';
        }else{
          if ($row["FK_Direccion"] != 0) { //CONSULTAR LA DIRECCION SELECCIONADA DEL CLIENTE
            $queryDireccion = "SELECT FK_Cliente, CONCAT(Calle,' ',No_Exterior) AS DireccionCliente, Colonia, Codigo_Postal, Ciudad, Detalles AS Referencia, Entre_Calles, CONCAT(Latitud,', ',Longitud) AS Coordenadas FROM detalles_clientes WHERE ID_Detalle_Cliente = '".$row["FK_Direccion"]."'";
            if($resDir=$con->query($queryDireccion)){
              if ($resDir->num_rows > 0) {
                $rowDir = $resDir->fetch_assoc();
                $direccionCliente = 
                "Dirección: <b>".$rowDir["DireccionCliente"]."</b><br>
                Colonia: <b>".$rowDir["Colonia"]."</b><br>
                Código Postal: <b>".$rowDir["Codigo_Postal"]."</b><br>
                Ciudad: <b>".$rowDir["Ciudad"]."</b><br>
                Ref. Visual: <b>".$rowDir["Referencia"]."</b><br>
                Entre las calles: <b>".$rowDir["Entre_Calles"]."</b><br>
                Coordenadas: <b>".$rowDir["Coordenadas"]."</b>";
              }
            }


          }else{
            $direccionCliente .= "Dirección: ".$row["CalleCliente"]."<br>Ciudad: ".$row["CiudadCliente"];
          }
        }

        $arregloVenta = $row;
        $arregloVenta['DireccionCliente'] = $direccionCliente;
        $arregloVenta['TotalFinal'] = ($row["Total_Importes"] + $row["Total"]);
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
        <?php echo "<h1>MISCELÁNEA RIOS</h1>"; ?>
        <?php  
          $FechaHoy = date('Y-m-d H:i:s');
          echo '<p>'.$arreglo["FechaHoy"].'</p>'; 
          if ($arreglo["Domicilio"] == 1) {
            if ($arreglo["Calle"] != "") {
              echo $arreglo["Calle"]." ";
            }
            if ($arreglo["No_Exterior"] != "") {
              echo "Ext: #".$arreglo["No_Exterior"]." ";
            }
            if ($arreglo["No_Interior"] != "") {
              echo "Int: # ".$arreglo["No_Interior"]." ";
            }
            if ($arreglo["Colonia"] != "") {
              echo "<p>".$arreglo["Colonia"]."</p>";
            }
            if ($arreglo["CP"] != "") {
              echo "<p>".$arreglo["CP"]."</p>";
            }
            if ($arreglo["Ciudad"] != "") {
              echo "<p>".$arreglo["Ciudad"]."</p>";
            }
            if ($arreglo["Estado"] != "") {
              echo "<p>".$arreglo["Estado"]."</p>";
            }
            if ($arreglo["Pais"] != "") {
              echo "<p>".$arreglo["Pais"]."</p>";
            }
          }

          if ($arreglo["MostrarTelefono"] == 1) {
            echo "<p>".$arreglo["TelefonoSucursal"]."</p>";
          }

          if ($arreglo["MostrarEmail"] == 1) {
            echo "<p>".$arreglo["CorreoSucursal"]."</p>";
          }

        ?>
      </div>
      <?php  
        echo '<p class="centrado">FOLIO: <b>'.$arreglo['Folio'].'</b></p>
          <p class="centrado">ESTATUS: '.$arregloVenta['Estatus'].'</p>';
        if ($arregloVenta['Estatus'] == "Cancelada") {
          echo '<p class="centrado">MOTIVO: '.$arregloVenta['Notas'].'</p>';
        }
        echo '<p class="centrado">CLIENTE: <b>'.$arregloVenta['NombreCliente'].'</b></p>';
        echo '<p class="centrado">TELÉFONO: '.$arregloVenta['TelefonoCliente'].'</p>';
        echo '<p class="centrado">CELULAR: '.$arregloVenta['CelularCliente'].'</p>';
        echo '<p class="centrado">'.$arregloVenta['DireccionCliente'].'</p>';
        echo '<p class="centrado">ATENDIO: '.$arregloVenta['NombreUsuario'].'</p>';
        
        if ($arregloVenta['Ruta'] != "" && $arregloVenta['Ruta'] != '0') {
          echo  '<p class="centrado"><b>Ruta: </b>'.$arregloVenta['Ruta'].'</p>';
            
          if($arregloVenta['Orden_Ruta'] != "" && $arregloVenta['Orden_Ruta'] != 0){
            echo '<p class="centrado"><b>Orden de Ruta: </b>'.$arregloVenta['Orden_Ruta'].'</p>';
          }
        }else{
          echo'<p class="centrado"><b>Ruta: </b>Sin ruta</p>';
        }

        if($arregloVenta['Facturada'] == '1'){
          QRcode::png('https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id='.$arregloVenta['UUID_CFDI'].'&re='.$arregloVenta['Emisor_RFC_CFDI'].'&rr='.$arregloVenta['Receptor_RFC_CFDI'].'&tt='.$arregloVenta['Total'].'&fe='.substr($arregloVenta['Sello_CFD_CFDI'], -8), 'qr.png', 'H', 7);

          echo '<br><div class="centrado">
            <p style="margin: 2px 0px; font-size: 14px;"><b>Versión CFDI:</b> '.$arregloVenta['Version_CFDI'].'</p>
            <p style="margin: 2px 0px; font-size: 14px;"><b>Fecha Emisión:</b></p>
            <p style="margin: 2px 0px; font-size: 14px;">'.str_replace('T', ' ', $arregloVenta['Fecha_Expedicion_CFDI']).'</p>
            <p style="margin: 2px 0px; font-size: 14px;"><b>Fecha Timbrado:</b></p>
            <p style="margin: 2px 0px; font-size: 14px;">'.str_replace('T', ' ', $arregloVenta['Fecha_Timbrado_CFDI']).'</p>
            <p style="margin: 2px 0px; font-size: 14px;"><b>Folio Fiscal:</b></p>
            <p style="margin: 2px 0px; font-size: 14px;">'.$arregloVenta['UUID_CFDI'].'</p>
            <p style="margin: 2px 0px; font-size: 14px;"><b>No. Certificado Digital:</b></p>
            <p style="margin: 2px 0px; font-size: 14px;">'.$arregloVenta['No_Certificado_CFDI'].'</p>
            <p style="margin: 2px 0px; font-size: 14px;"><b>No. Certificado SAT:</b></p>
            <p style="margin: 2px 0px; font-size: 14px;">'.$arregloVenta['No_Certificado_SAT_CFDI'].'</p>
          </div>
          <div style="text-align: center;">
            <p><img src="qr.png" style="width: 50%"/></p>
          </div>';
        }
      ?>
      <br>
      <table class="centrado" width="100%">
        <thead>
          <tr>
            <?php  
              echo '<th class="codigo">Cód.</th>';  
              echo '<th class="cantidad">Cant.</th>';
              echo '<th class="precio">Prec.</th>';  
              echo '<th class="impuestos">Impu.</th>'; 
              echo '<th class="precio">Importe</th>';
            ?>
          </tr>
        </thead>
        <tbody> 
          <?php 
            if ($arreglo["NombreSucursal"] == "Bodega") {
              $mostrar= "";
              $subtotal = 0;
              $contador = 0;
              $sumaTotalImpuestos = 0;
              $sumaTotalDescuentos = 0;
              $sql1 = "SELECT productos.FK_Categoria, categorias.Nombre FROM detalles_ventas LEFT JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN categorias ON productos.FK_Categoria = ID_Categoria WHERE FK_Venta = '".$arregloVenta['ID_Venta']."' GROUP BY FK_Categoria ORDER BY categorias.Nombre ASC";
              if($res1=$con->query($sql1)){
                if ($res1->num_rows > 0) {
                  while($row1 = $res1->fetch_assoc()){
                      if ($row1["Nombre"] == "") {
                        $mostrar .= "<tr class='negra'>
                          <td colspan='6'>SIN FAMILIA</td>
                        </tr>";
                      }else{
                        $mostrar .= "<tr class='negra'>
                          <td colspan='6'>".$row1["Nombre"]."</td>
                        </tr>";
                      }

                      //CONSULTAR PRODUCTOS POR CATEGORIA
                      $sql = "SELECT ID_Detalle_Venta, FK_Venta, detalles_ventas.FK_Producto, productos.FK_Categoria AS IDCategoria, categorias.Nombre AS NombreCategoria, productos.Codigo AS CodigoProducto, presentaciones.Codigo AS CodigoPresentacion, FK_Presentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura, detalles_ventas.Descripcion, detalles_ventas.Precio, Cantidad, detalles_ventas.Descuento, Total, Regreso_Inventario FROM detalles_ventas LEFT JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN categorias ON productos.FK_Categoria = ID_Categoria WHERE FK_Venta = '".$arregloVenta['ID_Venta']."' AND FK_Categoria = '".$row1["FK_Categoria"]."' ORDER BY IDCategoria";
                      if($res=$con->query($sql)){
                        if ($res->num_rows > 0) {
                          while($row = $res->fetch_assoc()){
                              $subtotalProducto = 0;


                              //CONSULTAR LAS DEVOLUCIONES
                              $cantidadDevuelto = 0;
                              $sqldev = "SELECT ID_Detalle_Devolucion, FK_Devolucion, FK_Detalle_Venta, SUM(detalles_devolucion.Cantidad) AS Cantidad, SUM(detalles_devolucion.Total) AS Total FROM detalles_devolucion INNER JOIN detalles_ventas ON FK_Detalle_Venta = ID_Detalle_Venta WHERE detalles_ventas.FK_Venta = '".$arregloVenta['ID_Venta']."' AND FK_Detalle_Venta = '".$row["ID_Detalle_Venta"]."' GROUP BY FK_Detalle_Venta"; 
                              if($resdev=$con->query($sqldev)){
                                if ($resdev->num_rows > 0) {
                                  while($rowdev = $resdev->fetch_assoc()){
                                    $cantidadDevuelto = $rowdev["Cantidad"];
                                  }
                                }
                              }

                              $row["Cantidad"] = $row['Cantidad'] - $cantidadDevuelto;
                              $totalImpuesto = 0;
                              $mostrarImpuestos = "";
                              $sql2 = "SELECT ID_Impuesto, FK_Detalle_Venta, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row["ID_Detalle_Venta"]."'"; 
                              if($res2=$con->query($sql2)){
                                if ($res2->num_rows > 0) {
                                  while($row2 = $res2->fetch_assoc()){
                                    $sumaImpuestos = 0;
                                    $totalProducto=0; $descuento=0; $totalFinal=0; $totalImpuesto = 0;
                                    $totalProducto = $row["Precio"]*$row["Cantidad"];
                                    $descuento = $row["Descuento"];
                                    $totalFinal = $totalProducto - $descuento;
                                    if ($row2["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
                                      $sumaImpuestos += $totalFinal * ($row2["Tasa_Cuota_CFDI"] / 100);
                                    }else if($row2["Tipo_Impuesto_CFDI"] == "Retenido" && $row2["Tipo_Factor_CFDI"] != "Exento"){ //Se resta al total
                                      $sumaImpuestos -= $totalFinal * ($row2["Tasa_Cuota_CFDI"] / 100);
                                    }else if($row2["Tipo_Impuesto_CFDI"] == "Retenido" && $row2["Tipo_Factor_CFDI"] == "Exento"){ ////No se suma ni se resta
                                      $sumaImpuestos += 0;
                                    }
                                    $sumaTotalImpuestos += $sumaImpuestos;
                                    $totalImpuesto = $totalFinal * ($row2["Tasa_Cuota_CFDI"] / 100);
                                    $mostrarImpuestos .= $row2["Impuesto_CFDI"]."(".$row2["Tasa_Cuota_CFDI"]."%) $".number_format($totalImpuesto, 2)."<br>";
                                  }
                                }
                              }

                              $nombrePresentacion = "";
                              $codigoactual = "";
                              if ($row['Presentacion'] != "") {
                                $nombrePresentacion = " ".$row['Presentacion']." (".$row['Abreviatura'].")";
                                $codigoactual = $row['CodigoPresentacion'];
                              }else{
                                $nombrePresentacion = "";
                                $codigoactual = $row['CodigoProducto'];
                              }

                              $subtotalProducto = ($row['Cantidad'] * $row['Precio']);
                              $row['Total'] = $subtotalProducto + $totalImpuesto - $row['Descuento'];
                              
                              if ($row['Cantidad'] > 0) {
                                // if ($row['FK_Promocion'] > 0) {
                                //   $mostrar .= "
                                //   <tr>
                                //     <td colspan='5'><b>****PRODUCTO DE PROMOCIÓN****</b></td>
                                //   </tr>
                                //   <tr>
                                //       <td colspan='5' style='text-align: left;'>".$row["Descripcion"].$nombrePresentacion."</td>      
                                //   </tr>
                                //   <tr>
                                //       <td class='codigo'>".$codigoactual."</td>
                                //       <td class='cantidad'><b style='font-size: 17px;'>".(round($row['Cantidad']*100)/100)."</b></td> 
                                //       <td class='precio'>$".(round($row['Precio']*100)/100)."</td>
                                //       <td class='impuestos'>".$mostrarImpuestos."</td>
                                //       <td class=''>$".$subtotalProducto."<br>Desc: ".(round($row['Descuento']*100)/100)."$ <br>$".(round($row['Total']*100)/100)."</td>
                                //   </tr>";
                                // }else{
                                  $mostrar .= "
                                  <tr>
                                      <td colspan='5' style='text-align: left;'>".$row["Descripcion"].$nombrePresentacion."</td>      
                                  </tr>
                                  <tr>
                                      <td class='codigo'>".$codigoactual."</td>
                                      <td class='cantidad'><b style='font-size: 17px;'>".(round($row['Cantidad']*100)/100)."</b></td> 
                                      <td class='precio'>$".(round($row['Precio']*100)/100)."</td>
                                      <td class='impuestos'>".$mostrarImpuestos."</td>
                                      <td class=''>$".$subtotalProducto."<br>Desc: ".(round($row['Descuento']*100)/100)."$ <br>$".(round($row['Total']*100)/100)."</td>
                                  </tr>";
                                //}
                              }

                              $subtotal += ($row['Cantidad'] * $row['Precio']);
                              $sumaTotalDescuentos += $row['Descuento'];
                              $contador++;
                          }
                        }else{
                          echo "No se encontraron resultados";
                        }
                      }else{
                        echo "Error: ".mysqli_error($con);
                      } 
                  }
                }
              }
              echo $mostrar; 
            }else{
              //SI ES CUALQUIER OTRA TIENDA QUE NO SEA BODEGA SE ORDENA POR COMO SE AGREGARON LOS PRODUCTOS

              $mostrar= "";
              $subtotal = 0;
              $contador = 0;
              $sumaTotalImpuestos = 0;
              $sumaTotalDescuentos = 0;
              

              //CONSULTAR PRODUCTOS POR ORDEN DEL DETALLE DE VENTA
              $sql = "SELECT ID_Detalle_Venta, FK_Venta, detalles_ventas.FK_Producto, productos.FK_Categoria AS IDCategoria, categorias.Nombre AS NombreCategoria, productos.Codigo AS CodigoProducto, presentaciones.Codigo AS CodigoPresentacion, FK_Presentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura, detalles_ventas.Descripcion, detalles_ventas.Precio, Cantidad, detalles_ventas.Descuento, Total, Regreso_Inventario FROM detalles_ventas LEFT JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN categorias ON productos.FK_Categoria = ID_Categoria WHERE FK_Venta = '".$arregloVenta['ID_Venta']."' ORDER BY ID_Detalle_Venta ASC";
              if($res=$con->query($sql)){
                if ($res->num_rows > 0) {
                  while($row = $res->fetch_assoc()){
                      $subtotalProducto = 0;

                      //CONSULTAR LAS DEVOLUCIONES
                      $cantidadDevuelto = 0;
                      $sqldev = "SELECT ID_Detalle_Devolucion, FK_Devolucion, FK_Detalle_Venta, SUM(detalles_devolucion.Cantidad) AS Cantidad, SUM(detalles_devolucion.Total) AS Total FROM detalles_devolucion INNER JOIN detalles_ventas ON FK_Detalle_Venta = ID_Detalle_Venta WHERE detalles_ventas.FK_Venta = '".$arregloVenta['ID_Venta']."' AND FK_Detalle_Venta = '".$row["ID_Detalle_Venta"]."' GROUP BY FK_Detalle_Venta"; 
                      if($resdev=$con->query($sqldev)){
                        if ($resdev->num_rows > 0) {
                          while($rowdev = $resdev->fetch_assoc()){
                            $cantidadDevuelto = $rowdev["Cantidad"];
                          }
                        }
                      }

                      $row["Cantidad"] = $row['Cantidad'] - $cantidadDevuelto;
                      $totalImpuesto = 0;
                      $mostrarImpuestos = "";
                      $sql2 = "SELECT ID_Impuesto, FK_Detalle_Venta, Tipo_Impuesto_CFDI, Impuesto_CFDI, Clave_CFDI, Tipo_Factor_CFDI, Tasa_Cuota_CFDI FROM detalles_impuestos_ventas WHERE FK_Detalle_Venta = '".$row["ID_Detalle_Venta"]."'"; 
                      if($res2=$con->query($sql2)){
                        if ($res2->num_rows > 0) {
                          while($row2 = $res2->fetch_assoc()){
                            $sumaImpuestos = 0;
                            $totalProducto=0; $descuento=0; $totalFinal=0; $totalImpuesto = 0;
                            $totalProducto = $row["Precio"]*$row["Cantidad"];
                            $descuento = $row["Descuento"];
                            $totalFinal = $totalProducto - $descuento;
                            if ($row2["Tipo_Impuesto_CFDI"] == "Trasladado") { //Se suma al total
                              $sumaImpuestos += $totalFinal * ($row2["Tasa_Cuota_CFDI"] / 100);
                            }else if($row2["Tipo_Impuesto_CFDI"] == "Retenido" && $row2["Tipo_Factor_CFDI"] != "Exento"){ //Se resta al total
                              $sumaImpuestos -= $totalFinal * ($row2["Tasa_Cuota_CFDI"] / 100);
                            }else if($row2["Tipo_Impuesto_CFDI"] == "Retenido" && $row2["Tipo_Factor_CFDI"] == "Exento"){ ////No se suma ni se resta
                              $sumaImpuestos += 0;
                            }
                            $sumaTotalImpuestos += $sumaImpuestos;
                            $totalImpuesto = $totalFinal * ($row2["Tasa_Cuota_CFDI"] / 100);
                            $mostrarImpuestos .= $row2["Impuesto_CFDI"]."(".$row2["Tasa_Cuota_CFDI"]."%) $".number_format($totalImpuesto, 2)."<br>";
                          }
                        }
                      }

                      $nombrePresentacion = "";
                      $codigoactual = "";
                      if ($row['Presentacion'] != "") {
                        $nombrePresentacion = " ".$row['Presentacion']." (".$row['Abreviatura'].")";
                        $codigoactual = $row['CodigoPresentacion'];
                      }else{
                        $nombrePresentacion = "";
                        $codigoactual = $row['CodigoProducto'];
                      }

                      $subtotalProducto = ($row['Cantidad'] * $row['Precio']);
                      $row['Total'] = $subtotalProducto + $totalImpuesto - $row['Descuento'];
                      
                      if ($row['Cantidad'] > 0) {
                        // if ($row['FK_Promocion'] > 0) {
                        //   $mostrar .= "
                        //   <tr>
                        //     <td colspan='5'><b>****PRODUCTO DE PROMOCIÓN****</b></td>
                        //   </tr>
                        //   <tr>
                        //       <td colspan='5' style='text-align: left;'>".$row["Descripcion"].$nombrePresentacion."</td>      
                        //   </tr>
                        //   <tr>
                        //       <td class='codigo'>".$codigoactual."</td>
                        //       <td class='cantidad'><b style='font-size: 17px;'>".(round($row['Cantidad']*100)/100)."</b></td> 
                        //       <td class='precio'>$".(round($row['Precio']*100)/100)."</td>
                        //       <td class='impuestos'>".$mostrarImpuestos."</td>
                        //       <td class=''>$".$subtotalProducto."<br>Desc: ".(round($row['Descuento']*100)/100)."$ <br>$".(round($row['Total']*100)/100)."</td>
                        //   </tr>";
                        // }else{
                          $mostrar .= "
                          <tr>
                              <td colspan='5' style='text-align: left;'>".$row["Descripcion"].$nombrePresentacion."</td>      
                          </tr>
                          <tr>
                              <td class='codigo'>".$codigoactual."</td>
                              <td class='cantidad'><b style='font-size: 17px;'>".(round($row['Cantidad']*100)/100)."</b></td> 
                              <td class='precio'>$".(round($row['Precio']*100)/100)."</td>
                              <td class='impuestos'>".$mostrarImpuestos."</td>
                              <td class=''>$".$subtotalProducto."<br>Desc: ".(round($row['Descuento']*100)/100)."$ <br>$".(round($row['Total']*100)/100)."</td>
                          </tr>";
                        //}
                      }

                      $subtotal += ($row['Cantidad'] * $row['Precio']);
                      $sumaTotalDescuentos += $row['Descuento'];
                      $contador++;
                  }
                }else{
                  echo "No se encontraron resultados";
                }
              }else{
                echo "Error: ".mysqli_error($con);
              }
              echo $mostrar; 
            }

            $productosPromocion = "";
            //CONSULTAR LOS PRODUCTOS QUE TIENEN SON DE PROMOCION Y MOSTRARLOS EN EL TICEKT
            $sqlPromociones = "SELECT ID_Detalles_Ventas_Promociones, FK_Venta, detalles_ventas_promociones.FK_Producto, productos.FK_Categoria AS IDCategoria, categorias.Nombre AS NombreCategoria, productos.Codigo AS CodigoProducto, presentaciones.Codigo AS CodigoPresentacion, FK_Presentacion, presentaciones.Nombre AS Presentacion, presentaciones.Abreviatura AS Abreviatura, detalles_ventas_promociones.Descripcion, detalles_ventas_promociones.Precio, Cantidad, detalles_ventas_promociones.Descuento, Total FROM detalles_ventas_promociones LEFT JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion LEFT JOIN categorias ON productos.FK_Categoria = ID_Categoria WHERE FK_Venta = '".$arregloVenta['ID_Venta']."'";
            if($resPromociones=$con->query($sqlPromociones)){
              if ($resPromociones->num_rows > 0) {
                while($rowPromociones = $resPromociones->fetch_assoc()){
                    $nombrePresentacion = "";
                    $codigoactual = "";
                    if ($rowPromociones['Presentacion'] != "") {
                      $nombrePresentacion = " ".$rowPromociones['Presentacion']." (".$rowPromociones['Abreviatura'].")";
                      $codigoactual = $rowPromociones['CodigoPresentacion'];
                    }else{
                      $nombrePresentacion = "";
                      $codigoactual = $rowPromociones['CodigoProducto'];
                    }

                    $subtotalProducto = ($rowPromociones['Cantidad'] * $rowPromociones['Precio']);
                    $rowPromociones['Total'] = $subtotalProducto + $totalImpuesto - $rowPromociones['Descuento'];
                    
                    $productosPromocion .= "
                      <tr>
                        <td colspan='5'><b>****PRODUCTO DE PROMOCIÓN****</b></td>
                      </tr>
                      <tr>
                          <td colspan='5' style='text-align: left;'>".$rowPromociones["Descripcion"].$nombrePresentacion."</td>      
                      </tr>
                      <tr>
                          <td class='codigo'>".$codigoactual."</td>
                          <td class='cantidad'><b style='font-size: 17px;'>".(round($rowPromociones['Cantidad']*100)/100)."</b></td> 
                          <td class='precio'>$".(round($rowPromociones['Precio']*100)/100)."</td>
                          <td class='impuestos'></td>
                          <td class=''>$".$subtotalProducto."<br>Desc: ".(round($rowPromociones['Descuento']*100)/100)."$ <br>$0</td>
                      </tr>";
                }
              }
            }

            echo $productosPromocion; 
           ?>
        </tbody>
      </table>
      <hr>
      <?php 
        echo '<p class="derecha">No. de Articulos: '.$contador.'</p>'; 
        echo '<p class="derecha" style="font-size: 15px;">Subtotal: <b style="font-size: 15px;">$'.(round(($subtotal)*100)/100).'</b></p>';  
        echo '<p class="derecha" style="font-size: 15px;">Descuento: <b style="font-size: 15px;">$'.(round($sumaTotalDescuentos*100)/100).'</b></p>'; 
        echo '<p class="derecha" style="font-size: 15px;">Impuestos: <b style="font-size: 15px;">$'.(round(($sumaTotalImpuestos)*100)/100).'</b></p>'; 
        echo "</br>
          <p class='derecha'><b style='font-size: 20px;'>TOTAL DE LA VENTA: $".number_format((round($arregloVenta['Total']*100)/100), 2)."</b></p>
          <p class='derecha'><b style='font-size: 20px;'>TOTAL IMPORTES: $".(round($arregloVenta['Total_Importes']*100)/100)."</b></p>
          <p class='derecha'><b style='font-size: 30px;'>TOTAL: $".number_format((round($arregloVenta['TotalFinal']*100)/100), 2)."</b></p>
          <p class='derecha'><b style='font-size: 14px;'>IMPORTE PAGADO: $".number_format($arregloVenta['Pago'], 2)."</b></p>
          <p class='derecha'><b style='font-size: 14px;'>CAMBIO: $".number_format($arregloVenta['Cambio'], 2)."</b></p>
        ";

        $TotalDevolucion = 0;
        $sqlTotaldev = "SELECT SUM(Total) AS TotalDevolucion FROM detalles_devolucion INNER JOIN devoluciones ON FK_Devolucion = ID_Devolucion WHERE FK_Venta = '".$arregloVenta['ID_Venta']."'";
        if($resTotalDev=$con->query($sqlTotaldev)){
          if ($resTotalDev->num_rows > 0) {
            $rowTotalDev = $resTotalDev->fetch_assoc();
            $TotalDevolucion = $rowTotalDev["TotalDevolucion"];
          }
        }
        $totalFinal = 0;
        if ($TotalDevolucion > 0) {

          $totalFinal = $arregloVenta['Total'] - $TotalDevolucion;

          echo "</br>
            <p class='derecha'><b style='font-size: 20px;'>DEVOLUCIÓN: $".(round($TotalDevolucion*100)/100)."</b></p>
          ";

          echo "</br>
            <p class='derecha'><b style='font-size: 20px;'>TOTAL FINAL: $".(round($totalFinal*100)/100)."</b></p>
          ";
        }

        echo '<p class="derecha">Tipo de pago: '.$arregloVenta['Tipo_Pago'].'</p>'; 
        //echo '<p class="derecha">Administrador: '.$arregloVenta['NombreUsuario'].'</p>';
      ?>
      <br>
      <p class="centrado">***********************************************************</p>
      <p class="centrado">***********************************************************</p>
      <br>
      <p class="centrado"><?php echo $arreglo['Mensaje'] ?></p>
      <br>
      <p class="centrado">***********************************************************</p>
      <p class="centrado">***********************************************************</p>
      <?php 

        $sqlImporte = "SELECT ID_Importe, Pagados, FK_Venta, importes.FK_Producto, presentaciones.Importe AS ImportePresentacion, presentaciones.Nombre AS NombrePrese, presentaciones.Abreviatura AS AbrePrese, productos.Nombre_Unidad AS NombrePreseGenerico, productos.Abreviatura_Unidad AS AbrePreseGenerico, FK_Presentacion, productos.Descripcion, Cantidad, importes.Importe, Total, Estatus FROM importes INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Venta = '".$arregloVenta['ID_Venta']."'";
        if($resImporte=$con->query($sqlImporte)){
          if ($resImporte->num_rows > 0) {
            echo ' <p class="centrado negra">IMPORTES</p>';
            echo '
              <table class="centrado" width="100%">
                <thead>
                  <tr>
                   <th>Prod.</th>
                   <th>Cant.</th>
                   <th>Impor.</th>  
                   <th>Total.</th> 
                   <th>Estatus</th>
                  </tr>
                </thead>
                <tbody>';
            while($rowI = $resImporte->fetch_assoc()){
              $presentacionImporte = "";
              $totalImportes = 0;
              $presentacionImporte = $rowI["Importe"];
              
              $totalImportes = $presentacionImporte * $rowI["Cantidad"];

              $nombrePresentacion = "";
              if ($rowI["NombrePrese"] != "") {
                $nombrePresentacion = $rowI["NombrePrese"];
              }else{
                $nombrePresentacion = $rowI["NombrePreseGenerico"];
              }
              $totalPagado = 0;

              $totalPagado = $rowI["Pagados"] * $presentacionImporte;

               echo '
                  <tr>
                   <th>'.$rowI["Descripcion"].' ('.$nombrePresentacion.')</th>
                   <th>'.$rowI["Cantidad"].' <br> Pagados: '.$rowI["Pagados"].'</th>
                   <th>$'.number_format($presentacionImporte, 2).'</th>  
                   <th>$'.number_format($totalImportes, 2).'<br>Total pagado: $'.number_format($totalPagado, 2).'</th> 
                   <th>'.$rowI["Estatus"].'</th>
                  </tr>';
            }
            echo '
                  </tbody>
            </table>';
          }
        }

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