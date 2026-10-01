<?php  
  session_start();
  if (!isset($_SESSION['user_admin']['ID_Usuario'])) {
    header('Location: ../index.php');
  }

date_default_timezone_set('America/Mexico_City');
$con = mysqli_connect('localhost','phpmyadmin','Alex_2026','miscelanearios_sistemaalex2025');
$arreglo = '';
$arregloRecibo = '';

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
  <title>Recibo</title>
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
      height: 1px;
      background: black;
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
      width: 700px;
      max-width: 700px;
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
    
    $sql = "SELECT ID_Ticket, FK_Sucursal, Imagen, Ruta_Imagen, tickets.Nombre AS MostrarNombre, Domicilio, tickets.Telefono AS MostrarTelefono, tickets.Email AS MostrarEmail, Total_Letras, Incluir_Mensaje, Mensaje, Moneda, Simbolo, Origen, sucursales.Nombre AS NombreSucursal, FK_Encargado, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, sucursales.Email AS CorreoSucursal, sucursales.Telefono AS TelefonoSucursal, Segundo_Telefono, FK_Zona FROM tickets INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
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
          'Folio' => $folio
        );
      }else{
        echo "No se encontraron resultados";
      }
    }

    $sql2 = "SELECT ID_Recibo_Dinero, LPAD(ID_Recibo_Dinero, 8, '0') AS Folio, Fecha, Monto, Descripcion, Fecha_Registro, FK_Usuario, Nombre_Proveedor FROM recibos_dinero WHERE ID_Recibo_Dinero = '".$_GET["id"]."'";

    if($res=$con->query($sql2)){
      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        $folio = str_pad($_GET["id"], 8, "0", STR_PAD_LEFT);

        $mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $separarFecha = explode("-", $row["Fecha"]);
        $FechaLetras = $separarFecha[2]." de ".$mes[intval($separarFecha[1])]." del ".$separarFecha[0];
        $arregloRecibo = array(
          'ID_Recibo_Dinero' => $row["ID_Recibo_Dinero"],
          'Fecha' => $FechaLetras,
          'Folio' => $row["Folio"],
          'Monto' => $row["Monto"],
          'Descripcion' => $row["Descripcion"],
          'Fecha_Registro' => $row["Fecha_Registro"],
          'FK_Usuario' => $row["FK_Usuario"],
          'Nombre_Proveedor' => $row["Nombre_Proveedor"]
        );
      }else{
        echo "No se encontraron resultados";
      }
    }

   ?>

    <div class="ticket">
      <br class="oculto-impresion">
      <p class="centrado">
        <button class="oculto-impresion" onclick="imprimir()">IMPRIMIR RECIBO</button>
      </p>
      <br class="oculto-impresion">
      <div class="derecha">
        <?php  
          $DireccionRecibo = "";
          if ($arreglo["Ciudad"] != "") {
            $DireccionRecibo .= "<b style='font-size: 15px;'>".$arreglo["Ciudad"]."</b>";
          }
          if ($arreglo["Estado"] != "") {
            $DireccionRecibo .= "<b style='font-size: 15px;'>, ".$arreglo["Estado"]."</b>.";
          }
          $DireccionRecibo .= "<b style='font-size: 15px;'>".$arregloRecibo["Fecha"]."</b>.";
          echo $DireccionRecibo;

          echo "<p><br><br><b style='font-size: 15px;'>BUENO POR: $".number_format($arregloRecibo["Monto"], 2)."</b></p>";

          echo "<p><br><b style='font-size: 12px;'>("._valorEnLetras($arregloRecibo["Monto"], 0)." PESOS 00/100 M.N.)</b></p>";
        ?>
      </div>

      <div class="centrado">
        <?php  
          echo "<p><br><br><span style='font-size: 15px;'>RECIBO DEL SR. ALEJANDRO RIOS TORRES LA CANTIDAD ARRIBA MENCIONADA POR EL CONCEPTO DE: <b style='font-size: 15px;'>".$arregloRecibo["Descripcion"]."</b></span>
          <br><br><br><br><br><br><br>";
          echo "<hr>";
          echo "<p><b style='font-size: 20px;'>".$arregloRecibo["Nombre_Proveedor"]."</b>";
        ?>
      </div>

    </div>
    <div class="ticket" style="margin-top: 150px;">
      <br class="oculto-impresion">
      <br class="oculto-impresion">
      <div class="derecha">
        <?php  
          $DireccionRecibo = "";
          if ($arreglo["Ciudad"] != "") {
            $DireccionRecibo .= "<b style='font-size: 15px;'>".$arreglo["Ciudad"]."</b>";
          }
          if ($arreglo["Estado"] != "") {
            $DireccionRecibo .= "<b style='font-size: 15px;'>, ".$arreglo["Estado"]."</b>.";
          }
          $DireccionRecibo .= "<b style='font-size: 15px;'>".$arregloRecibo["Fecha"]."</b>.";
          echo $DireccionRecibo;

          echo "<p><br><br><b style='font-size: 15px;'>BUENO POR: $".number_format($arregloRecibo["Monto"], 2)."</b></p>";

          echo "<p><br><b style='font-size: 12px;'>("._valorEnLetras($arregloRecibo["Monto"], 0)." PESOS 00/100 M.N.)</b></p>";
        ?>
      </div>

      <div class="centrado">
        <?php  
          echo "<p><br><br><span style='font-size: 15px;'>RECIBO DEL SR. ALEJANDRO RIOS TORRES LA CANTIDAD ARRIBA MENCIONADA POR EL CONCEPTO DE: <b style='font-size: 15px;'>".$arregloRecibo["Descripcion"]."</b></span>
          <br><br><br><br><br><br><br>";
          echo "<hr>";
          echo "<p><b style='font-size: 20px;'>".$arregloRecibo["Nombre_Proveedor"]."</b>";
        ?>
      </div>

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