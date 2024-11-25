<?php
$correo = $_POST["correo"];
$contrasena = $_POST["contrasena"];

try {
    // Establecer conexión a la base de datos
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$databaseName");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la consulta SQL para verificar las credenciales del usuario
    $query = "SELECT id, nombre FROM Usuarios WHERE correo = ? AND contrasena = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$correo, $contrasena]);

    // Obtener el resultado de la consulta
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Las credenciales son válidas
        // Iniciar sesión y redirigir al usuario a una página de inicio de sesión exitosa
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["nombre"] = $usuario["nombre"];
        header("Location: Ficha_Medica.html");
        exit;
    } else {
        // Las credenciales son incorrectas
        echo "Correo electrónico o contraseña incorrectos.";
    }
} catch(PDOException $e) {
    // Manejar errores de conexión o consulta
    echo "Error: " . $e->getMessage();
}
?>

