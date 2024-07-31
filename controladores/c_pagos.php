<?php
class pagos {
    public function _insertar() {
        $omodelo = new m_modelo();
        extract($_POST);
        $fecha = date('Y-m-d');
        $finFecha = '';
        $idUsuario = $_SESSION['user_punto_venta']['BD_Usar'];

        $tipo = $omodelo->link->real_escape_string(trim($tipo));
        $plan = $omodelo->link->real_escape_string(trim($plan));
        $precio = $omodelo->link->real_escape_string(trim($precio));
        $planUsando =  $_SESSION['user_punto_venta']['Plan'];

        if ($planUsando == $plan) {
            if ($tipo == 'Mensual') {
                $fechaActual = $_SESSION['user_punto_venta']['FechaSub'];

                $finFecha = date('Y-m-d', strtotime('+1 month', strtotime($fechaActual)));

             }else{
                $fechaActual = $_SESSION['user_punto_venta']['FechaSub'];

                $finFecha = date('Y-m-d', strtotime('+1 year', strtotime($fechaActual)));
 
            }

        }else{
            if ($tipo == 'Mensual') {
                $finFechaSum = 'MONTH';
                $volorDia = $precio/30;
             }else{
                $finFechaSum = 'YEAR'; 
                $volorDia = $precio/365; 
            }

            $tipoUsando =  $_SESSION['user_punto_venta']['Tipo'];
            $fecha_subscripcion = new DateTime($_SESSION['user_punto_venta']['FechaSub']);


            $fecha_actual = new DateTime(date('Y-m-d H:i:s'));


            $diferencia = $fecha_subscripcion->diff($fecha_actual);
            $diasTranscurridos = $diferencia->days;

            if ($tipoUsando == "Mensual") {
                if ($planUsando == "1") {
                    $precioDiaActual = 3.5;
                }else if ($planUsando == "2") {
                    $precioDiaActual = 6.5;
                }else if($planUsando == "3"){
                    $precioDiaActual = 10;
                }
            }else{
                if ($planUsando == "1") {
                    $precioDiaActual = 2.5;
                }else if ($planUsando == "2") {
                    $precioDiaActual = 4.5;
                }else if($planUsando == "3"){
                    $precioDiaActual = 7;
                }
            }

            $totalDinero = $diasTranscurridos * $precioDiaActual;

            $diasAgregar = intval($totalDinero / $volorDia);

            $fechaFin = new DateTime($fecha);
            $fechaFin->modify('+1 '.$finFechaSum.'');
            $fechaFin->modify('+' . $diasAgregar . ' days');

            $finFecha = $fechaFin->format('Y-m-d');
        }



        $omodelo->link->query("use bigtool_punto_venta");
        $query = "INSERT INTO suscripciones (Tipo, Plan, FK_Usuario, Fecha_Inicio, Fecha_Fin) VALUES ('$tipo', '$plan', '$idUsuario', '$fecha', '$finFecha')";
        $error = $omodelo->_insertar($query);
        $status = 0;

        if ($error == 'si') {
          echo "Error 1: " . mysqli_error($omodelo->link);
          $status = 1;
        } else {
            
            $_SESSION['user_punto_venta']['FechaSub'] = $finFecha;
            $_SESSION['user_punto_venta']['Plan'] = $plan;
            
            echo 'Correcto';

        }
      }
}
?>