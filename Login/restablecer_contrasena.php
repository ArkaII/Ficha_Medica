<?php
require_once '../Conexion/conexion_Surface.php';

// Verificar si el método es GET (cuando se hace clic en el enlace del correo)
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];

    // Buscar el token en la base de datos
    $query = "SELECT usuario_id, fecha_expiracion FROM recuperacion_contrasena WHERE token = :token";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        // Verificar si el token ha expirado
        if (strtotime($registro["fecha_expiracion"]) > time()) {
            // El token es válido y no ha expirado, mostrar el formulario para cambiar la contraseña
            $usuario_id = $registro['usuario_id']; // ID del usuario
            include 'restablecer_contrasena.html'; // Incluimos el HTML para el formulario
        } else {
            echo "El enlace de recuperación ha expirado.";
        }
    } else {
        echo "Token no válido.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["token"])) {
    // El formulario de nueva contraseña ha sido enviado (POST)
    $token = $_POST["token"];
    $nueva_contrasena = $_POST["nueva_contrasena"];

    // Buscar el token en la base de datos
    $query = "SELECT usuario_id FROM recuperacion_contrasena WHERE token = :token";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        // El token es válido, proceder a actualizar la contraseña
        $usuario_id = $registro['usuario_id'];
        $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_BCRYPT); // Asegúrate de encriptar la nueva contraseña

        // Actualizar la contraseña en la base de datos
        $query = "UPDATE usuarios SET contrasena = :nueva_contrasena WHERE id = :usuario_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nueva_contrasena', $nueva_contrasena_hash, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        // Eliminar el token de la base de datos después de usarlo
        $query = "DELETE FROM recuperacion_contrasena WHERE token = :token";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        echo "<script>
                alert('Contraseña restablecida con éxito.');
                window.location.href = '../Login/index.html';
                
                </script>";
    } else {
        echo "Token no válido.";
    }
} else {
    echo "Método no permitido.";
}
?>
