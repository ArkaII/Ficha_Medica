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
        $query = "SELECT id, nombre, contrasena, rol FROM usuarios WHERE correo = :correo";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener el resultado de la consulta
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verificar la contraseña con el hash almacenado
            if (password_verify($contrasena, $usuario['contrasena'])) {
                // Verificar el rol del usuario
                if ($usuario['rol'] === 'Medico') {
                    // Las credenciales son válidas y el rol es Médico
                    session_start();
                    $_SESSION["loggedin"] = true;
                    $_SESSION["usuario_id"] = $usuario["id"];
                    $_SESSION["nombre"] = $usuario["nombre"];
                    header("Location: ../Ficha_Medica_Medico.html"); // Redirige a la página del médico
                    exit;
                } else {
                    // El usuario no es un Médico
                    echo "<script>
                        alert('Acceso denegado. Solo los médicos pueden iniciar sesión.');
                        setTimeout(function() {
                            window.location.href = '../Login_Medicos/index_Medico.html';  // Redirige al índice
                        }, 1); // Espera 1 segundo antes de redirigir
                    </script>";
                }
            } else {
                // Contraseña incorrecta, mostrar mensaje y redirigir
                echo "<script>
                    alert('Contraseña incorrecta.');
                    setTimeout(function() {
                        window.location.href = '../Login_Medicos/index_Medico.html';  // Redirige al índice
                    }, 1); // Espera 1 segundo antes de redirigir
                </script>";
            }
        } else {
            // No se encontró un usuario con ese correo electrónico, mostrar mensaje y redirigir
            echo "<script>
                alert('No se encontró un usuario con ese correo electrónico.');
                setTimeout(function() {
                    window.location.href = '../Login_Medicos/index_Medico.html';  // Redirige al índice
                }, 1); // Espera 1 segundo antes de redirigir
            </script>";
        }
    }
} catch (Exception $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}
?>