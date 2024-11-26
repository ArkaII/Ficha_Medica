<?php
// Incluir el archivo de conexión desde la carpeta Conexion
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

try {
    // Validar si se recibieron los datos del formulario
    if (isset($_POST['nombre'], $_POST['rut'], $_POST['correo'], $_POST['contrasena'], $_POST['telefono'])) {
        // Limpiar y obtener los datos
        $nombre = trim($_POST['nombre']);
        $rut = trim($_POST['rut']);
        $correo = trim($_POST['correo']);
        $contrasena = $_POST['contrasena'];
        $telefono = trim($_POST['telefono']);

        // Validar que los campos no estén vacíos
        if (empty($nombre) || empty($rut) || empty($correo) || empty($contrasena) || empty($telefono)) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        // Hashear la contraseña con bcrypt
        $contrasenaHasheada = password_hash($contrasena, PASSWORD_BCRYPT);

        // Consulta para insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, rut, correo, contrasena, telefono) 
                VALUES (:nombre, :rut, :correo, :contrasena, :telefono)";
        $stmt = $conn->prepare($sql);
        
        // Asignar parámetros de la consulta
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $contrasenaHasheada);
        $stmt->bindParam(':telefono', $telefono);
        
        // Ejecutar la consulta
        $stmt->execute();

        // Confirmación de registro
        echo "¡Usuario registrado exitosamente!";
    } else {
        echo "Por favor, complete el formulario.";
    }

} catch (PDOException $e) {
    // Manejo de errores de base de datos
    echo "Error en la conexión o consulta: " . $e->getMessage();
} catch (Exception $e) {
    // Manejo de otros errores
    echo "Error: " . $e->getMessage();
}
?>
