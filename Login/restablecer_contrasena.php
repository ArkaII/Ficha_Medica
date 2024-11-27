<?php
require 'vendor/autoload.php'; // Asegúrate de que la ruta de autoload.php sea correcta

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener el correo electrónico del formulario
    if (isset($_POST["correo"])) {
        $correo = $_POST["correo"];
        
        // Verificar si el correo existe en la base de datos
        $query = "SELECT id, nombre_usuario FROM usuarios WHERE correo = :correo";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Generar un token único y su fecha de expiración
            $token = bin2hex(random_bytes(16));  // Genera un token aleatorio
            $fecha_expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));  // El token expira en 1 hora

            // Insertar el token en la base de datos
            $query = "INSERT INTO recuperacion_contrasena (usuario_id, token, fecha_expiracion) VALUES (:usuario_id, :token, :fecha_expiracion)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario['id'], PDO::PARAM_INT);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_expiracion', $fecha_expiracion, PDO::PARAM_STR);
            $stmt->execute();

            // Enviar correo con el enlace de recuperación
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Cambia esto según el servidor SMTP que estés utilizando
                $mail->SMTPAuth = true;
                $mail->Username = 'tu-correo@gmail.com';  // Tu correo
                $mail->Password = 'tu-contraseña';  // Tu contraseña o contraseña de aplicación si tienes 2FA
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Destinatario
                $mail->setFrom('tu-correo@gmail.com', 'Tu Nombre');
                $mail->addAddress($correo, $usuario['nombre_usuario']);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de Contraseña';
                $mail->Body = 'Haz clic en el siguiente enlace para restablecer tu contraseña: <a href="http://localhost/recuperacion/restaurar.php?token=' . $token . '">Restablecer Contraseña</a>';

                // Enviar el correo
                $mail->send();
                echo "Correo de recuperación enviado a " . $correo;
            } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        } else {
            echo "Correo no registrado.";
        }
        
    }
}
?>
