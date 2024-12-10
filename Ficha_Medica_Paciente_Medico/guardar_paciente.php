<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $edad = $_POST['edad'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $rut = $_POST['rut']; // Este es el RUT que usaremos para encontrar el usuario_id
    $condicion = $_POST['condicion'];
    $ultima_visita = isset($_POST['ultima_visita']) ? $_POST['ultima_visita'] : null; // Campo opcional
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nacionalidad = $_POST['nacionalidad'];
    $direccion = $_POST['direccion'];
    $telefono_contacto = $_POST['telefono_contacto'];
    $correo_electronico = $_POST['correo_electronico'];

    // Buscar si el RUT ya está registrado en la tabla pacientes
    $query_rut_existente = "SELECT id FROM pacientes WHERE rut = :rut";
    $stmt_rut_existente = $conn->prepare($query_rut_existente);
    $stmt_rut_existente->bindParam(':rut', $rut, PDO::PARAM_STR);
    $stmt_rut_existente->execute();

    // Si el RUT ya existe, se muestra un mensaje y se detiene el proceso
    if ($stmt_rut_existente->rowCount() > 0) {
        echo "<script>alert('El RUT ya está registrado en la base de datos.'); window.location.href='Crear_ficha.html';</script>";
        exit(); // Detener la ejecución
    }

    // Buscar el usuario_id basado en el RUT
    $query_usuario = "SELECT id FROM usuarios WHERE rut = :rut";
    $stmt_usuario = $conn->prepare($query_usuario);
    $stmt_usuario->bindParam(':rut', $rut, PDO::PARAM_STR);
    $stmt_usuario->execute();

    // Verificar si el usuario existe
    $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        $usuario_id = $usuario['id']; // Obtener el usuario_id

        // Verificar si el usuario ya tiene una ficha médica
        $query_verificar = "SELECT id FROM pacientes WHERE usuario_id = :usuario_id";
        $stmt_verificar = $conn->prepare($query_verificar);
        $stmt_verificar->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt_verificar->execute();

        if ($stmt_verificar->rowCount() > 0) {
            // Si ya existe una ficha médica para este usuario
            echo "<script>alert('El usuario ya tiene una ficha médica registrada.'); window.location.href='crear_paciente.php';</script>";
        } else {
            // Insertar los datos del paciente en la tabla "pacientes"
            $query = "INSERT INTO pacientes (usuario_id, nombre, genero, edad, tipo_sangre, rut, ultima_visita, condicion, fecha_nacimiento, nacionalidad, direccion, telefono_contacto, correo_electronico) 
                      VALUES (:usuario_id, :nombre, :genero, :edad, :tipo_sangre, :rut, :ultima_visita, :condicion, :fecha_nacimiento, :nacionalidad, :direccion, :telefono_contacto, :correo_electronico)";
            
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
            $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_sangre', $tipo_sangre, PDO::PARAM_STR);
            $stmt->bindParam(':rut', $rut, PDO::PARAM_STR); // Vinculando el RUT
            $stmt->bindParam(':ultima_visita', $ultima_visita, PDO::PARAM_STR);
            $stmt->bindParam(':condicion', $condicion, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
            $stmt->bindParam(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':telefono_contacto', $telefono_contacto, PDO::PARAM_STR);
            $stmt->bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_STR);

            try {
                if ($stmt->execute()) {
                    echo "<script>alert('Paciente creado con éxito'); window.location.href='Crear_ficha.html';</script>";
                } else {
                    echo "<script>alert('Error al guardar la ficha médica.'); window.location.href='Crear_ficha.html';</script>";
                }
            } catch (PDOException $e) {
                // Capturamos el error de violación de la restricción UNIQUE
                if ($e->getCode() == 23000) {  // Violación de la restricción UNIQUE (RUT duplicado)
                    echo "<script>alert('El RUT ya está registrado en la base de datos.'); window.location.href='Crear_ficha.html';</script>";
                } else {
                    echo "<script>alert('Error inesperado: " . $e->getMessage() . "'); window.location.href='Crear_ficha.html';</script>";
                }
            }
        }
    } else {
        // Si el usuario no existe, mostrar un mensaje de error
        echo "<script>alert('Usuario no registrado, Favor crear usuario.'); window.location.href='../Registro_Usuario/RegistrarUsuarios.html';</script>";
    }
}
?>
