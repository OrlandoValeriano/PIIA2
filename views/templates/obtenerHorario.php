<?php
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/session.php');
if (isset($_POST['usuario_id']) && isset($_POST['carrera_id'])) {
    $usuario_id = (int)$_POST['usuario_id'];
    $carrera_id = (int)$_POST['carrera_id'];

    try {
        // Consulta para obtener el horario del docente y la carrera específica
        $query = "SELECT hora, lunes, martes, miercoles, jueves, viernes, sabado 
                  FROM horarios 
                  WHERE usuario_id = :usuario_id AND carrera_id = :carrera_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':carrera_id', $carrera_id, PDO::PARAM_INT);
        $stmt->execute();
        $horario = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Consulta para obtener las horas de tutorías, apoyo y frente a grupo
        $queryHoras = "SELECT horas_tutorias, horas_apoyo, horas_frente_grupo 
                       FROM horas_docente 
                       WHERE usuario_id = :usuario_id AND carrera_id = :carrera_id";
        $stmtHoras = $conn->prepare($queryHoras);
        $stmtHoras->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmtHoras->bindParam(':carrera_id', $carrera_id, PDO::PARAM_INT);
        $stmtHoras->execute();
        $horas = $stmtHoras->fetch(PDO::FETCH_ASSOC);

        // Consulta para obtener el nombre del docente
        $queryNombre = "SELECT nombre_usuario, apellido_p, apellido_m 
                        FROM usuarios 
                        WHERE usuario_id = :usuario_id";
        $stmtNombre = $conn->prepare($queryNombre);
        $stmtNombre->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmtNombre->execute();
        $docente = $stmtNombre->fetch(PDO::FETCH_ASSOC);

        // Devolver la respuesta en formato JSON
        echo json_encode([
            'success' => true,
            'horario' => $horario,
            'horas_tutorias' => $horas['horas_tutorias'],
            'horas_apoyo' => $horas['horas_apoyo'],
            'horas_frente_grupo' => $horas['horas_frente_grupo'],
            'docente_nombre' => $docente['nombre_usuario'] . ' ' . $docente['apellido_p'] . ' ' . $docente['apellido_m']
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al obtener el horario: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan parámetros (usuario_id o carrera_id).'
    ]);

}