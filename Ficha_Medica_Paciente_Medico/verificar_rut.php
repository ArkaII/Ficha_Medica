<?php
// Incluir la conexión a la base de datos
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta

// Verificar si se ha enviado el RUT a través de POST
if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    // Consulta para verificar si el RUT ya existe
    $query = "SELECT COUNT(*) FROM pacientes WHERE rut = :rut";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el número de filas que coinciden con el RUT
    $result = $stmt->fetchColumn();

    if ($result > 0) {
        // El RUT ya existe en la base de datos
        echo json_encode(['status' => 'exists', 'message' => 'El RUT ya está registrado.']);
    } else {
        // El RUT no existe en la base de datos
        echo json_encode(['status' => 'new', 'message' => 'Es un nuevo paciente.']);
    }
}
?>
