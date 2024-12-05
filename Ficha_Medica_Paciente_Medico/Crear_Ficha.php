<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta
if (isset($_GET['rut']) && !empty($_GET['rut'])) {
    $rut = $_GET['rut'];

    // Verificar si el RUT está registrado en la tabla "usuarios"
    $query = "SELECT id, nombre, rut FROM usuarios WHERE rut = :rut"; 
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Si el usuario existe, mostrar el formulario para crear un paciente
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Crear Paciente</title>
            <style>
                /* Estilos para el formulario */
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: #f7f7f7;
                }
                h1 {
                    text-align: center;
                }
                form {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: bold;
                }
                input, select, button {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }
                button {
                    cursor: pointer;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    transition: background-color 0.3s ease;
                }
                button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <h1>Crear Ficha Médica para <?php echo htmlspecialchars($usuario['nombre']); ?></h1>
            <form action="guardar_paciente.php" method="POST">
                <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario['id']); ?>">

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>

                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" required>

                <label for="tipo_sangre">Tipo de Sangre:</label>
                <input type="text" id="tipo_sangre" name="tipo_sangre" required>

                <label for="condicion">Condición Médica:</label>
                <input type="text" id="condicion" name="condicion" required>

                <label for="ultima_visita">Última Visita:</label>
                <input type="date" id="ultima_visita" name="ultima_visita" required>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

                <label for="nacionalidad">Nacionalidad:</label>
                <input type="text" id="nacionalidad" name="nacionalidad" required>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>

                <label for="telefono">Teléfono de Contacto:</label>
                <input type="text" id="telefono_contacto" name="telefono_contacto" required>

                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo_electronico" required>

                <button type="submit">Guardar Ficha Médica</button>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='buscar_usuario.html';</script>";
    }
} else {
    echo "<script>alert('Por favor ingresa el RUT del usuario.'); window.location.href='buscar_usuario.html';</script>";
}
?>
