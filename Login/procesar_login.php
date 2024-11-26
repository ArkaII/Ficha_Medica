<?php
// Incluir el archivo de conexión
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

try {
    // Verificar si los datos han sido enviados mediante el formulario
    if (isset($_POST["correo"]) && isset($_POST["contrasena"])) {
        $correo = trim($_POST["correo"]);
        $contrasena = $_POST["contrasena"];

        // Validar que los campos no estén vacíos
        if (empty($correo) || empty($contrasena)) {
            throw new Exception("Por favor, ingrese su correo electrónico y contraseña.");
        }

        // Preparar la consulta SQL para verificar las credenciales del usuario
        $query = "SELECT id, nombre, contrasena FROM usuarios WHERE correo = :correo";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener el resultado de la consulta
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verificar la contraseña con el hash almacenado
            if (password_verify($contrasena, $usuario['contrasena'])) {
                // Las credenciales son válidas
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["nombre"] = $usuario["nombre"];
                header("Location: ../Ficha_Medica_Paciente.html");
                exit;
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta.";
            }
        } else {
            // Correo no encontrado
            echo "Correo electrónico no encontrado.";
        }
    } else {
        echo "Por favor, ingrese su correo electrónico y contraseña.";
    }
} catch (Exception $e) {
    // Manejar errores
    echo "Error: " . $e->getMessage();
}
?>
