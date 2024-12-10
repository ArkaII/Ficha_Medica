<?php
require_once '../Conexion/conexion_Surface.php'; // Verifica la ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rut'])) {
        $rut = $_POST['rut'];

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Primero, eliminar el registro en la tabla pacientes
            $sql = "DELETE FROM pacientes WHERE rut = :rut"; // Asegúrate de que el campo sea correcto
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);
            $stmt->execute();

            // Confirmar la transacción
            $conn->commit();
            echo "<script>alert('Ficha médica eliminada exitosamente.'); window.location.href='Eliminar_Ficha.html';</script>";
        } catch (Exception $e) {
            // Si hay un error, revertir la transacción
            $conn->rollBack();
            echo "<script>alert('Error al eliminar la ficha médica: " . $e->getMessage() . "'); window.location.href='Eliminar_Ficha.html';</script>";
        }
    } else {
        echo "<script>alert('RUT no proporcionado.'); window.location.href='Eliminar_Ficha.html';</script>";
    }
} else {
    echo "<script>alert('Método no permitido.'); window.location.href='Eliminar_Ficha.html';</script>";
}
?>