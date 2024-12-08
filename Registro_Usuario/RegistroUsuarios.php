<?php
// Incluir el archivo de conexión desde la carpeta Conexion
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta
try {
    // Validar si se recibieron los datos del formulario
    if (isset($_POST['nombre'], $_POST['rut'], $_POST['correo'], $_POST['contrasena'], $_POST['telefono'], $_POST['rol'])) {
        // Limpiar y obtener los datos
        $nombre = trim($_POST['nombre']);
        $rut = trim($_POST['rut']);
        $correo = trim($_POST['correo']);
        $contrasena = password_hash(trim($_POST['contrasena']), PASSWORD_DEFAULT);
        $telefono = trim($_POST['telefono']);
        $rol = trim($_POST['rol']);
        $especialidad = isset($_POST['especialidad']) ? trim($_POST['especialidad']) : null;

        // Preparar la consulta SQL
        $sql = "INSERT INTO usuarios (nombre, rut, correo, contrasena, telefono, rol, especialidad) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $rut, $correo, $contrasena, $telefono, $rol, $especialidad]);

        // Mensaje de éxito
        echo "<script>
                alert('Usuario registrado exitosamente.');
                window.location.href = '../Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html'; // Cambia 'index.php' por la ruta correcta a tu página de inicio
              </script>";
    } else {
        echo "<script>
                alert('Por favor, complete todos los campos requeridos.');
                window.location.href = '../Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html'; // Cambia 'index.php' por la ruta correcta a tu página de inicio
              </script>";
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Código de error para violación de restricción única
        echo "<script>
                alert('Error: El RUT ya está registrado.');
                window.location.href = '../Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html'; // Cambia 'index.php' por la ruta correcta a tu página de inicio
              </script>";
    } else {
        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href = '../Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html'; // Cambia 'index.php' por la ruta correcta a tu página de inicio
              </script>";
    }
}
?>