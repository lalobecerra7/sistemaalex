<?php
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y procesar los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    // Aquí puedes realizar cualquier otra validación o procesamiento necesario
    
    // Simular el registro exitoso
    $mensaje = "¡Registro exitoso! Gracias por registrarte, $nombres $apellidos.";
    echo '<div class="alert alert-success">'.$mensaje.'</div>';
} else {
    // Si no se recibieron datos por POST, mostrar un mensaje de error
    echo '<div class="alert alert-danger">Error: No se recibieron datos del formulario.</div>';
}
?>