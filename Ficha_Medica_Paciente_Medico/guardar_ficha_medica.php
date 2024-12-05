<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica que el campo paciente_id esté presente en el formulario
    if (!isset($_POST['paciente_id'])) {
        die('Error: No se proporcionó el ID del paciente.');
    }

    $paciente_id = $_POST['usuario_id']; // Cambiado de usuario_id a paciente_id
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $edad = $_POST['edad'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $condicion = $_POST['condicion'];
    $ultima_visita = $_POST['ultima_visita'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];  // Agregamos la fecha de nacimiento
    $nacionalidad = $_POST['nacionalidad'];          // Agregamos la nacionalidad
    $direccion = $_POST['direccion'];                // Agregamos la dirección
    $telefono_contacto = $_POST['telefono_contacto']; // Agregamos el teléfono
    $correo_electronico = $_POST['correo_electronico']; // Agregamos el correo electrónico

    // Validación del correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        die('Error: El correo electrónico no es válido.');
    }

    // Depuración: Verificar los datos recibidos
    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';

    // Actualizar los datos en la base de datos
    $query = "UPDATE pacientes SET 
                nombre = :nombre, 
                genero = :genero, 
                edad = :edad, 
                tipo_sangre = :tipo_sangre, 
                condicion = :condicion, 
                ultima_visita = :ultima_visita,
                fecha_nacimiento = :fecha_nacimiento, 
                nacionalidad = :nacionalidad,
                direccion = :direccion,
                telefono_contacto = :telefono_contacto,
                correo_electronico = :correo_electronico 
              WHERE id = :id";
    $stmt = $conn->prepare($query);

    // Vincular los parámetros
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_sangre', $tipo_sangre, PDO::PARAM_STR);
    $stmt->bindParam(':condicion', $condicion, PDO::PARAM_STR);
    $stmt->bindParam(':ultima_visita', $ultima_visita, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
    $stmt->bindParam(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->bindParam(':telefono_contacto', $telefono_contacto, PDO::PARAM_STR);
    $stmt->bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_STR);
    $stmt->bindParam(':id', $paciente_id, PDO::PARAM_INT);

    // Ejecutar la consulta y verificar si la ejecución es exitosa
    if ($stmt->execute()) {
        echo "<script>alert('Ficha médica actualizada correctamente.'); window.location.href='buscar_paciente.html';</script>";
    } else {
        // Obtener detalles del error
        $errorInfo = $stmt->errorInfo();
        echo "<script>alert('Error al actualizar la ficha médica: " . $errorInfo[2] . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Método no permitido.'); window.history.back();</script>";
}
?>