<?php
// Incluir la conexión a la base de datos
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];
    $especialidad = isset($_POST['especialidad']) ? $_POST['especialidad'] : null;

    // Preparar la consulta para actualizar los datos del usuario
    $sql = "UPDATE usuarios SET nombre = :nombre, correo = :correo, telefono = :telefono, rol = :rol, especialidad = :especialidad WHERE rut = :rut";
    $stmt = $conn->prepare($sql);

    // Enlazar los parámetros
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
    $stmt->bindParam(':especialidad', $especialidad, PDO::PARAM_STR);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>
                alert('Usuario modificado exitosamente.');
                window.location.href = '../Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html'; // Cambia 'index.php' por la ruta correcta a tu página de inicio
              </script>";
    } else {
        echo "<script>
                alert('Error al modificar el usuario: " . addslashes($stmt->errorInfo()[2]) . "');
                window.location.href = '../Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html'; // Cambia 'index.php' por la ruta correcta a tu página de inicio
              </script>";
    }

    $stmt->closeCursor(); // Cerrar el cursor
} else {
    echo "<script>
            alert('Método de solicitud no válido.');
            window.location.href = '../Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html'; // Cambia 'index.php' por la ruta correcta a tu página de inicio
          </script>";
}
?>