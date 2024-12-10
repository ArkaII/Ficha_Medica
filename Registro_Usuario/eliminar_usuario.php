<?php
// Incluir la conexión a la base de datos
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

// Verificar si se ha enviado el RUT a través de POST
if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    // Preparar la consulta para eliminar el usuario
    $sql = "DELETE FROM usuarios WHERE rut = :rut";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontró ningún usuario con ese RUT.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el usuario.']);
    }

    $stmt->closeCursor(); // Cerrar el cursor
} else {
    echo json_encode(['status' => 'error', 'message' => 'RUT no proporcionado.']);
}
?>