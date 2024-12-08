<?php
// Incluir la conexión a la base de datos
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

// Verificar si se ha enviado el RUT a través de POST
if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    // Preparar la consulta para obtener los datos del usuario
    $sql = "SELECT nombre, correo, telefono, rol, especialidad FROM usuarios WHERE rut = :rut";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            echo json_encode(['status' => 'success', 'data' => $usuario]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta.']);
    }

    $stmt->closeCursor(); // Cerrar el cursor
} else {
    echo json_encode(['status' => 'error', 'message' => 'RUT no proporcionado.']);
}
?>