<?php
session_start();
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'message' => 'No has iniciado sesión.']);
    exit;
}

// Obtener el RUT del paciente logueado
$rut = $_SESSION["rut"];

// Consulta a la base de datos para obtener la ficha médica
$query = "SELECT * FROM pacientes WHERE rut = :rut";
$stmt = $conn->prepare($query);
$stmt->bindParam(':rut', $rut, PDO::PARAM_STR);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró al paciente
if (!$usuario) {
    echo json_encode(['success' => false, 'message' => 'Paciente no encontrado.']);
    exit;
}

// Devolver los datos del paciente en formato JSON
echo json_encode(['success' => true, 'paciente' => $usuario]);
?>