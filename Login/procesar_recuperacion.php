<?php
// Incluir PHPMailer
require '../vendor/autoload.php';  // Asegúrate de que la ruta a PHPMailer es correcta
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir el archivo de conexión
require_once '../Conexion/conexion_Surface.php'; // Asegúrate de que la ruta sea correcta

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener el correo electrónico del formulario
        $correo = trim($_POST["correo"]);

        // Verificar que el campo no esté vacío
        if (empty($correo)) {
            throw new Exception("Por favor, ingrese un correo electrónico válido.");
        }

        // Buscar el correo en la base de datos
        $query = "SELECT id, nombre FROM usuarios WHERE correo = :correo";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Generar un token único para la recuperación
            $token = bin2hex(random_bytes(16));
            $fecha_expiracion = date("Y-m-d H:i:s", strtotime("+1 hour")); // Expira en 1 hora

            // Insertar el token en la base de datos
            $query = "INSERT INTO recuperacion_contrasena (usuario_id, token, fecha_expiracion) 
                      VALUES (:usuario_id, :token, :fecha_expiracion)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario["id"], PDO::PARAM_INT);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_expiracion', $fecha_expiracion, PDO::PARAM_STR);
            $stmt->execute();

            // URL de recuperación
            $url_recuperacion = "http://localhost/Login/restablecer_contrasena.php?token=$token";

            // Configurar el correo
            $mail = new PHPMailer(true);

            // Configuración del servidor SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'pedroignacioperez7@gmail.com';  // Tu correo de Gmail
            $mail->Password = 'tnxc lgvz dfmh etwq';  // Tu contraseña de Gmail (o usar una contraseña de aplicación si tienes 2FA activado)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Receptor del correo
            $mail->setFrom('tu_correo@gmail.com', 'Soporte');
            $mail->addAddress($correo, $usuario["nombre"]);

            // Asunto y cuerpo del correo
            $mail->isHTML(false);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body    = "Hola " . $usuario["nombre"] . ",\n\n";
            $mail->Body   .= "Recibimos una solicitud para restablecer tu contraseña.\n";
            $mail->Body   .= "Por favor, haz clic en el siguiente enlace para restablecerla:\n\n";
            $mail->Body   .= $url_recuperacion . "\n\n";
            $mail->Body   .= "Este enlace es válido por 1 hora.\n\n";
            $mail->Body   .= "Si no realizaste esta solicitud, puedes ignorar este correo.\n\n";
            $mail->Body   .= "Saludos,\nTu equipo de soporte.";

            // Enviar el correo
            if ($mail->send()) {
                echo "<script>
                    alert('Se ha enviado un correo con las instrucciones para restablecer tu contraseña.');
                    window.location.href = '../Login/index.html'; // Redirigir al índice
                </script>";
            } else {
                throw new Exception("No se pudo enviar el correo electrónico. Inténtalo nuevamente.");
            }
        } else {
            throw new Exception("El correo electrónico no está registrado.");
        }
    } else {
        throw new Exception("Método no permitido.");
    }
} catch (Exception $e) {
    echo "<script>
        alert('Error: " . $e->getMessage() . "');
        window.location.href = '../Login/recuperar_contrasena.html'; // Redirigir a la página de recuperación
    </script>";
}
?>