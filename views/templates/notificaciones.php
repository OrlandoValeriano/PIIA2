<?php
function obtenerNotificaciones($conn, $usuario_id)
{
    // Obtener carrera y tipo del usuario
    $queryUsuario = "
        SELECT carrera_carrera_id, tipo_usuario_tipo_usuario_id
        FROM usuario
        WHERE usuario_id = :usuario_id
    ";
    $stmtUsuario = $conn->prepare($queryUsuario);
    $stmtUsuario->bindParam(':usuario_id', $usuario_id);
    $stmtUsuario->execute();
    $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    $carrera_id = $usuario['carrera_carrera_id'];
    $tipo_usuario = $usuario['tipo_usuario_tipo_usuario_id'];

    $notificaciones = [];

    // Personaliza según tipo de usuario
    if ($tipo_usuario == 1) {
        // Mostrar notificaciones del usuario activo
        $queryNotificaciones = "
            SELECT n.id_notificacion, n.mensaje, n.fecha, n.vista,
                   u.nombre_usuario, u.apellido_p, u.apellido_m
            FROM notificaciones n
            JOIN usuario u ON n.usuario_id = u.usuario_id
            WHERE n.usuario_id = :usuario_id
            ORDER BY n.fecha DESC
        ";
        $stmtNotif = $conn->prepare($queryNotificaciones);
        $stmtNotif->bindParam(':usuario_id', $usuario_id);
        $stmtNotif->execute();
        $notificaciones = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);
    }elseif ($tipo_usuario == 2) {
        $queryNotificaciones = "
        SELECT n.id_notificacion, n.mensaje, n.fecha, n.vista,
               u.nombre_usuario, u.apellido_p, u.apellido_m
        FROM notificaciones n
        JOIN usuario u ON n.usuario_id = u.usuario_id
        WHERE n.carrera_id = :carrera_id
          AND n.tipo_usuario_id = :tipo_usuario
        ORDER BY n.fecha DESC
    ";

        $stmtNotif = $conn->prepare($queryNotificaciones);
        $stmtNotif->bindParam(':carrera_id', $carrera_id);
        $stmtNotif->bindParam(':tipo_usuario', $tipo_usuario);
        $stmtNotif->execute();
        $notificaciones = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);
    }elseif ($tipo_usuario == 3) {
        $queryNotificaciones = "
        SELECT n.id_notificacion, n.mensaje, n.fecha, n.vista,
               u.nombre_usuario, u.apellido_p, u.apellido_m
        FROM notificaciones n
        JOIN usuario u ON n.usuario_id = u.usuario_id
        WHERE n.mensaje = 'La incidencia ha sido aprobada por Subdireccion'
        ORDER BY n.fecha DESC
    ";
        $stmtNotif = $conn->prepare($queryNotificaciones);
        $stmtNotif->execute();
        $notificaciones = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);
    }elseif ($tipo_usuario == 7) {
        $queryNotificaciones = "
        SELECT n.id_notificacion, n.mensaje, n.fecha, n.vista,
               u.nombre_usuario, u.apellido_p, u.apellido_m
        FROM notificaciones n
        JOIN usuario u ON n.usuario_id = u.usuario_id
        WHERE n.mensaje = 'La incidencia ha sido aprobada por Division'
        ORDER BY n.fecha DESC
    ";
        $stmtNotif = $conn->prepare($queryNotificaciones);
        $stmtNotif->execute();
        $notificaciones = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);
    }


    return $notificaciones;
}

function formatearNombreCompleto($usuario, $tipo_usuario)
{
    switch ($tipo_usuario) { 
        case 2: //opcion posible para casos futuros
        default:
            return htmlspecialchars(
                $usuario['nombre_usuario'] . ' ' .
                $usuario['apellido_p'] . ' ' .
                $usuario['apellido_m']
            );
    }
}
