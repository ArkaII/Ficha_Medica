<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta de tu conexión

// Verifica que el RUT haya sido enviado
if (isset($_GET['rut'])) {
    $rut = $_GET['rut'];

    // Consulta a la base de datos
    $query = "SELECT * FROM pacientes WHERE rut = :rut";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Si el paciente existe, enviamos los datos en formato JSON
        echo json_encode([
            'success' => true,
            'nombre' => $usuario['nombre'],
            'genero' => $usuario['genero'],
            'edad' => $usuario['edad'],
            'fecha_nacimiento' => $usuario['fecha_nacimiento'], // Nuevo campo
            'nacionalidad' => $usuario['nacionalidad'],         // Nuevo campo
            'direccion' => $usuario['direccion'],               // Nuevo campo
            'telefono' => $usuario['telefono_contacto'],        // Nuevo campo
            'correo' => $usuario['correo_electronico'],         // Nuevo campo
            'sangre' => $usuario['tipo_sangre'],
            'rut' => $usuario['rut'],
            'ultima_visita' => $usuario['ultima_visita'],
            'condicion' => $usuario['condicion']
        ]);
    } else {
        // Si no se encuentra el paciente, devolvemos un error
        echo json_encode(['success' => false]);
    }
} else {
    // Si no se ha recibido el RUT, devolvemos un error
    echo json_encode(['success' => false]);
}
?>