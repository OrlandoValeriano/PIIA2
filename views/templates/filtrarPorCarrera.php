<?php
include('../../models/consultas.php');
include('../../controllers/db.php');

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido");
    }

    $carrera_id = $_POST['carrera_id'] ?? null;
    $all_carreras = $_POST['all_carreras'] ?? 0;
    
    $consultas = new Consultas($conn);
    
    if ($all_carreras == 1) {
        // Obtener todos los docentes con información de carrera
        $sql = "SELECT u.usuario_id, u.nombre_usuario, u.apellido_p, u.apellido_m, 
                       u.imagen_url, u.edad, u.fecha_contratacion, u.grado_academico, 
                       u.cedula, u.correo, u.numero_empleado, 
                       c.carrera_id, c.nombre_carrera
                FROM usuario u
                LEFT JOIN usuario_has_carrera uc ON u.usuario_id = uc.usuario_usuario_id
                LEFT JOIN carrera c ON uc.carrera_carrera_id = c.carrera_id
                WHERE u.tipo_usuario_tipo_usuario_id = 1";
    } else {
        // Obtener docentes por carrera específica
        $sql = "SELECT u.usuario_id, u.nombre_usuario, u.apellido_p, u.apellido_m, 
                       u.imagen_url, u.edad, u.fecha_contratacion, u.grado_academico, 
                       u.cedula, u.correo, u.numero_empleado, 
                       c.carrera_id, c.nombre_carrera
                FROM usuario u
                JOIN usuario_has_carrera uc ON u.usuario_id = uc.usuario_usuario_id
                JOIN carrera c ON uc.carrera_carrera_id = c.carrera_id
                WHERE u.tipo_usuario_tipo_usuario_id = 1 AND c.carrera_id = :carrera_id";
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($all_carreras != 1) {
        $stmt->bindParam(':carrera_id', $carrera_id, PDO::PARAM_INT);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta");
    }
    
    $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Verificar si se obtuvieron resultados
    if (empty($docentes)) {
        echo json_encode([]);
        exit;
    }
    
    echo json_encode($docentes);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>