<?php
session_start();
require_once __DIR__ . '/../controllers/db.php';
require_once __DIR__ . '/consultas.php';

$modelo = new Consultas($conn);
$userId = $_SESSION['user_id'];
$tipoUsuario = (new Consultas($conn))->obtenerTipoUsuarioPorId($userId);

$carrera_id = $_GET['carrera_id'] ?? 'all';

// Forzar restricción si es tipo 1
if ($tipoUsuario === 1) {
    // Sobrescribe carrera_id para evitar que vea más
    $carreraData = $modelo->datosCarreraPorId($userId);
    $carrera_id = $carreraData['carrera_id'] ?? -1;
}

$usuarios = $modelo->obtenerUsuariosPorCarrera($carrera_id);
header('Content-Type: application/json');
echo json_encode($usuarios);


