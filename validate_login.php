<?php

var_dump($_POST); // Verifica los datos recibidos

$serverName = "Pperez\\SQLEXPRESS";
$databaseName = "Ficha_Medica";
try {
    // Crear la conexión con PDO
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$databaseName");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validar que se recibieron los datos del formulario
    if (!isset($_POST['correo']) || !isset($_POST['contrasena'])) {
        throw new Exception("No se recibieron los datos del formulario.");
    }

    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Validar que los campos no estén vacíos
    if (empty($correo) || empty($contrasena)) {
        throw new Exception("El correo y la contraseña son obligatorios.");
    }

    // Verificar si se encontró el usuario
    $stmt = $conn->prepare("SELECT id, correo, contrasena FROM usuarios WHERE correo = :correo");
    $stmt->execute(['correo' => $correo]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        var_dump($user); // Verifica si se ha encontrado un usuario
        // Verifica la contraseña ingresada
        if (password_verify($contrasena, $user['contrasena'])) {
            session_start();
            $_SESSION['correo'] = $correo;
            header("Location: Nuevo_Paciente.html");
            exit;
        } else {
            die("Contraseña incorrecta.");
        }
    } else {
        die("Correo no encontrado.");
    }

} catch (PDOException $e) {
    error_log("Error en la conexión o consulta: " . $e->getMessage());
    echo "<script>alert('Ocurrió un error en el servidor. Intente nuevamente más tarde.'); window.history.back();</script>";
} catch (Exception $e) {
    echo "<script>alert('" . $e->getMessage() . "'); window.history.back();</script>";
}
?>