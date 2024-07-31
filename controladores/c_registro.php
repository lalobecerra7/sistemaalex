<?php
class registro {
    public function _insertar() {
        $omodelo = new m_modelo();
        extract($_POST);
        $fecha = date('Y-m-d H:i:s');
        $finFecha = date('Y-m-d H:i:s', strtotime('+3 month', strtotime($fecha)));

        $nombres = $omodelo->link->real_escape_string(trim($nombres));
        $apellidos1 = $omodelo->link->real_escape_string(trim($apellidos1));
        $apellidos2 = $omodelo->link->real_escape_string($apellidos2);
        $correo = $omodelo->link->real_escape_string($correo);
        $contrasena = $omodelo->link->real_escape_string($contrasena);
        $hash = password_hash($contrasena, PASSWORD_BCRYPT, ['cost' => 12]);

        $omodelo->link->query("use bigtool_punto_venta");
        $query = "INSERT INTO usuarios SET Nombre = '$nombres', Primer_Apellido = '$apellidos1', Segundo_Apellido = '$apellidos2', Correo = '$correo', Contrasena = '$hash', Tipo_Usuario = '1', Fecha_Alta = CURDATE(), Activo = '1', Tipo_Login = '1', FK_Sucursal = NULL";
        $error = $omodelo->_insertar($query);
        $status = 0;

        if ($error == 'si') {
          echo "Error 1: " . mysqli_error($omodelo->link);
          $status = 1;
        } else {

            echo 'Correcto';

            $id = mysqli_insert_id($omodelo->link);

            $query1 = "INSERT INTO suscripciones SET Tipo = 'prueva', FK_Usuario = '$id', Fecha_Inicio = '$fecha', Fecha_Fin = '$finFecha'";
            $error1 = $omodelo->_insertar($query1);  

            $omodelo->movimiento($query, $id);
                        
            $omodelo->_crear($id);
        }
      }
}

/*
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y procesar los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos1 = $_POST['apellidos1'];
    $apellidos2 = $_POST['apellidos2'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $hash = password_hash($contrasena, PASSWORD_BCRYPT, ['cost' => 12]);
    
    // Crear la conexión a la base de datos
    $servername = "localhost"; // Nombre del servidor de la base de datos
    $username = "root"; // Nombre de usuario de la base de datos
    $password = ""; // Contraseña de la base de datos
    $dbname = "bt_punto_venta_general"; // Nombre de la base de datos

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para insertar los datos en la tabla
    $sql = "INSERT INTO usuarios SET Nombre = '$nombres', Primer_Apellido = '$apellidos1', Segundo_Apellido = '$apellidos2', Correo = '$correo', Contrasena = '$hash', Tipo_Usuario = '1', Fecha_Alta = CURDATE(), FK_Sucursal = NULL";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo 'Correcto';
    } else {
        echo 'Error al registrar los datos: ' . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se recibieron datos por POST, mostrar un mensaje de error
    echo 'Error: No se recibieron datos del formulario.';
}*/
?>