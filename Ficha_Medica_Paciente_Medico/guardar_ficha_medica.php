<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica que el campo paciente_id esté presente en el formulario
    if (!isset($_POST['paciente_id'])) {
        die('Error: No se proporcionó el ID del paciente.');
    }

    $paciente_id = $_POST['paciente_id']; // Cambiado de usuario_id a paciente_id
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $edad = $_POST['edad'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $condicion = $_POST['condicion'];
    $ultima_visita = $_POST['ultima_visita'];

    // Actualizar los datos en la base de datos
    $query = "UPDATE pacientes SET 
                nombre = :nombre, 
                genero = :genero, 
                edad = :edad, 
                tipo_sangre = :tipo_sangre, 
                condicion = :condicion, 
                ultima_visita = :ultima_visita 
              WHERE id = :id";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_sangre', $tipo_sangre, PDO::PARAM_STR);
    $stmt->bindParam(':condicion', $condicion, PDO::PARAM_STR);
    $stmt->bindParam(':ultima_visita', $ultima_visita, PDO::PARAM_STR);
    $stmt->bindParam(':id', $paciente_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Ficha médica actualizada correctamente.'); window.location.href='buscar_paciente.html';</script>";
    } else {
        echo "<script>alert('Error al actualizar la ficha médica.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Método no permitido.'); window.history.back();</script>";
}
?>
