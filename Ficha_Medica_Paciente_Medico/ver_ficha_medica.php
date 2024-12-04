<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta

if (isset($_GET['rut'])) {
    $rut = $_GET['rut'];

    // Consultar la base de datos para obtener la ficha médica
    $query = "SELECT * FROM pacientes WHERE rut = :rut"; // Asegúrate de que sea la tabla correcta
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Variables para los datos del paciente
        $nombre = htmlspecialchars($usuario['nombre']);
        $genero = isset($usuario['genero']) ? $usuario['genero'] : '';
        $edad = isset($usuario['edad']) ? $usuario['edad'] : '';
        $tipo_sangre = isset($usuario['tipo_sangre']) ? $usuario['tipo_sangre'] : '';
        $condicion = isset($usuario['condicion']) ? $usuario['condicion'] : '';
        // Convertir la fecha con hora al formato adecuado (Y-m-d)
        $ultima_visita = isset($usuario['ultima_visita']) ? date('Y-m-d', strtotime($usuario['ultima_visita'])) : '';
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ficha Médica</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                    background-color: #f7f7f7;
                }
                h1 {
                    text-align: center;
                }
                .ficha-medica {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                .ficha-medica label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: bold;
                }
                .ficha-medica input {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    background-color: #f0f0f0;
                }
                .ficha-medica input[readonly] {
                    cursor: not-allowed;
                }
                button {
                    cursor: pointer;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                }
                button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <h1>Ficha Médica de <?php echo $nombre; ?></h1>
            <div class="ficha-medica">
                <form action="" method="POST">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" readonly>

                    <label for="genero">Género:</label>
                    <input type="text" id="genero" name="genero" value="<?php echo $genero; ?>" readonly>

                    <label for="edad">Edad:</label>
                    <input type="text" id="edad" name="edad" value="<?php echo $edad; ?>" readonly>

                    <label for="tipo_sangre">Tipo de Sangre:</label>
                    <input type="text" id="tipo_sangre" name="tipo_sangre" value="<?php echo $tipo_sangre; ?>" readonly>

                    <label for="condicion">Condición Médica:</label>
                    <input type="text" id="condicion" name="condicion" value="<?php echo $condicion; ?>" readonly>

                    <label for="ultima_visita">Última Visita:</label>
                    <input type="text" id="ultima_visita" name="ultima_visita" value="<?php echo $ultima_visita; ?>" readonly>

                    <button type="button" onclick="window.history.back();">Volver Atrás</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Paciente no encontrado.'); window.location.href='buscar_ficha_medica.php';</script>";
    }
} else {
    echo "<script>alert('Por favor ingresa el RUT del paciente.'); window.location.href='buscar_ficha_medica.php';</script>";
}
?>
