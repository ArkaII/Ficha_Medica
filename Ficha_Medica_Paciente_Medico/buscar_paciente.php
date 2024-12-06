<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta

if (isset($_GET['rut'])) {
    $rut = $_GET['rut'];

    // Consultar la base de datos para obtener información del paciente
    $query = "SELECT * FROM pacientes WHERE rut = :rut"; // Cambié de "usuarios" a "pacientes"
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Variables para datos del paciente
        $usuario_id = $usuario['id'];
        $nombre = htmlspecialchars($usuario['nombre']);
        $genero = isset($usuario['genero']) ? $usuario['genero'] : '';
        $edad = isset($usuario['edad']) ? $usuario['edad'] : '';
        $tipo_sangre = isset($usuario['tipo_sangre']) ? $usuario['tipo_sangre'] : '';
        $condicion = isset($usuario['condicion']) ? $usuario['condicion'] : '';
        // Convertir la fecha con hora al formato adecuado (Y-m-d)
        $ultima_visita = isset($usuario['ultima_visita']) ? date('Y-m-d', strtotime($usuario['ultima_visita'])) : '';
        // Nuevas variables para los datos agregados
        $fecha_nacimiento = isset($usuario['fecha_nacimiento']) ? date('Y-m-d', strtotime($usuario['fecha_nacimiento'])) : '';
        $nacionalidad = isset($usuario['nacionalidad']) ? $usuario['nacionalidad'] : '';
        $direccion = isset($usuario['direccion']) ? $usuario['direccion'] : '';
        $telefono = isset($usuario['telefono_contacto']) ? $usuario['telefono_contacto'] : '';
        $correo = isset($usuario['correo_electronico']) ? $usuario['correo_electronico'] : '';

        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Actualizar Ficha Médica</title>
            <style>
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
                .btn-back {
                    background-color: #6c757d;
                }
                .btn-back:hover {
                    background-color: #5a6268;
                }
            </style>
        </head>
        <body>
            <h1>Actualizar Ficha Médica de <?php echo $nombre; ?></h1>
            <form action="guardar_ficha_medica.php" method="POST">
            <input type="hidden" name="paciente_id" value="<?php echo $usuario_id; ?>">
            <input type="hidden" name="rut" value="<?php echo htmlspecialchars($rut); ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option value="Masculino" <?php echo ($genero == 'Masculino' ? 'selected' : ''); ?>>Masculino</option>
                <option value="Femenino" <?php echo ($genero == 'Femenino' ? 'selected' : ''); ?>>Femenino</option>
                <option value="Otro" <?php echo ($genero == 'Otro' ? 'selected' : ''); ?>>Otro</option>
            </select>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" value="<?php echo $edad; ?>" required>

            <label for="tipo_sangre">Tipo de Sangre:</label>
            <input type="text" id="tipo_sangre" name="tipo_sangre" value="<?php echo htmlspecialchars($tipo_sangre); ?>" required>

            <label for="condicion">Condición Médica:</label>
            <input type="text" id="condicion" name="condicion" value="<?php echo htmlspecialchars($condicion); ?>" required>

            <label for="ultima_visita">Última Visita:</label>
            <input type="date" id="ultima_visita" name="ultima_visita" value="<?php echo htmlspecialchars($ultima_visita); ?>" required>

            <!-- Nuevos campos -->
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" required>

            <label for="nacionalidad">Nacionalidad:</label>
            <input type="text" id="nacionalidad" name="nacionalidad" value="<?php echo htmlspecialchars($nacionalidad); ?>" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>" required>

            <label for="telefono">Teléfono de Contacto:</label>
            <input type="text" id="telefono_contacto" name="telefono_contacto" value="<?php echo htmlspecialchars($telefono); ?>" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo_electronico" value="<?php echo htmlspecialchars($correo); ?>" required>

            <button type="submit">Guardar Ficha Médica</button>
            <button type="button" onclick="location.href='/Ficha_Medica_Paciente_Medico/Ficha_Medica_Paciente.html';" class="btn-back">Volver Atrás</button>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Paciente no encontrado.'); window.location.href='buscar_paciente.html';</script>";
    }
} else {
    echo "<script>alert('Por favor ingresa el RUT del paciente.'); window.location.href='buscar_paciente.html';</script>";
}
?>  