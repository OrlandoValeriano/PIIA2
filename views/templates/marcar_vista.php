<?php
session_start();
require 'C:\wamp64\www\PIIA2-Allfusion\controllers\db.php'; // Ajusta esta ruta si está en otra carpeta

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $query = "UPDATE notificaciones SET vista = 1 WHERE id_notificacion = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    

    // Redirige a la página que deseas mostrar después
    header("Location: validacion_incidencia.php");
    exit();
} else {
    // Redirige si no hay un id válido
    header("Location: index.php");
    exit();
}
?>
