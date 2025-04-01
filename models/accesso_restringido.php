<?php
// acceso_restringido.php - Control de accesos por tipo de usuario
include_once(__DIR__ . '/../controllers/db.php');  // Incluir la conexión a la base de datos

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el archivo db.php existe antes de incluirlo
if (!file_exists(__DIR__ . '/../controllers/db.php')) {
    die("Archivo db.php no encontrado en: " . __DIR__ . '/../controllers/db.php');
}


require_once(__DIR__ . '/../controllers/db.php');
require_once(__DIR__ . '/../models/consultas.php');

class ControlAcceso {
    private $conn;
    private $consultas;
    
    public function __construct() {
        global $conn; // Usar la conexión de db.php
        $this->conn = $conn;
        $this->consultas = new Consultas($this->conn);
    }
    
    public function verificarAcceso($pagina_actual) {
        // 1. Verificar sesión activa
        if (!isset($_SESSION['user_id'])) {
            $this->registrarIntento(false, "No autenticado", $pagina_actual);
            header("Location: ../templates/auth-login.php");
            exit();
        }
        
        // 2. Obtener datos del usuario
        $user_id = (int)$_SESSION['user_id'];
        $tipo_usuario = $this->consultas->obtenerTipoUsuarioPorId($user_id);
        
        if (!$tipo_usuario) {
            $this->registrarIntento(false, "Tipo usuario no encontrado", $pagina_actual);
            header("Location: ../../templates/error.php?codigo=404");
            exit();
        }
        
        // 3. Mapeo de permisos por tipo de usuario
        $permisos = [
            1 => ['dashboard_docentes.php', 'form_incidencias.php', 'validacion_incidencia.php'],
            2 => ['dashboard_docentes.php', 'dashboard_carreras.php', 'form_incidencias.php', 'validacion_incidencia.php', 'form_horario.php'],
            3 => ['recursos_humanos_empleados.php', 'formulario_usuario.php', 'form_incidencias.php', 'validacion_incidencia.php'],
            4 => ['desarrollo_academico_docentes.php', 'form_materia.php', 'form_carrera.php', 'formulario_grupo.php', 'form_usuarios-carreras.php', 'form_edificio.php', 'form_horario.php'],
            5 => ['dashboard_docentes.php', 'dashboard_carreras.php', 'desarrollo_academico_docentes.php', 'recursos_humanos_empleados.php'],
            6 => ['index.php'],
            7 => ['validacion_incidencia.php']
        ];
        
        // 4. Verificación de acceso
        if (!isset($permisos[$tipo_usuario])) {
            $this->registrarIntento(false, "Tipo usuario inválido", $pagina_actual);
            header("Location: ../../templates/error.php");
            exit();
        }
        
        if (!in_array($pagina_actual, $permisos[$tipo_usuario])) {
            $this->registrarIntento(false, "Sin permisos", $pagina_actual);
            $_SESSION['detalle_error'] = [
                'tipo_usuario' => $tipo_usuario,
                'pagina_solicitada' => $pagina_actual,
                'paginas_permitidas' => $permisos[$tipo_usuario]
            ];
            header("Location: ../../templates/error.php?codigo=403");
            exit();
        }
        
        // 5. Registro de acceso exitoso
        $this->registrarIntento(true, "Acceso concedido", $pagina_actual);
    }
    
    private function registrarIntento($exitoso, $razon, $pagina) {
        $log_data = [
            'fecha' => date('Y-m-d H:i:s'),
            'usuario_id' => $_SESSION['user_id'] ?? null,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'pagina' => $pagina,
            'exitoso' => $exitoso,
            'razon' => $razon
        ];
        
        error_log("CONTROL_ACCESO: " . json_encode($log_data));
    }
}

// Uso del controlador
$control_acceso = new ControlAcceso();
$pagina_actual = basename($_SERVER['SCRIPT_NAME']);
$control_acceso->verificarAcceso($pagina_actual);
?>
