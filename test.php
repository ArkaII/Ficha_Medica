<?php
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

?>