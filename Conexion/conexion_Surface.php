<?php
$serverName = "Pperez\SQLEXPRESS";
$databaseName = "Ficha_Medica";

try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$databaseName");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa";
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>