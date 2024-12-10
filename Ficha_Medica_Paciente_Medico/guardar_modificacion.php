<?php
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $nacionalidad = $_POST['nacionalidad'];
    $direccion = $_POST['direccion'];
    $telefono_contacto = $_POST['telefono_contacto'];
    $correo_electronico = $_POST['correo_electronico'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $condicion = $_POST['condicion'];
    $ultima_visita = $_POST['ultima_visita'];

    // Actualizar la información del paciente en la base de datos
    $query = "UPDATE pacientes SET nombre = :nombre, fecha_nacimiento = :fecha_nacimiento, edad = :edad, genero = :genero, nacionalidad = :nacionalidad, direccion = :direccion, telefono_contacto = :telefono_contacto, correo_electronico = :correo_electronico, tipo_sangre = :tipo_sangre, condicion = :condicion, ultima_visita = :ultima_visita WHERE rut = :rut";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':edad', $edad);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':nacionalidad', $nacionalidad);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':telefono_contacto', $telefono_contacto);
    $stmt->bindParam(':correo_electronico', $correo_electronico);
    $stmt->bindParam(':tipo_sangre', $tipo_sangre);
    $stmt->bindParam(':condicion', $condicion);
    $stmt->bindParam(':ultima_visita', $ultima_visita);
    $stmt->bindParam(':rut', $rut);
    
    if ($stmt->execute()) {
        echo "<script>alert('Información actualizada correctamente.'); window.location.href='modificar_ficha_medica.html';</script>";
    } else {
        echo "<script>alert('Error al actualizar la información.'); window.location.href='modificar_ficha_medica.html';</script>";
    }
} else {
    echo "<script>alert('Método no permitido. '); window.location.href='modificar_ficha_medica.html';</script>";
}
?> 