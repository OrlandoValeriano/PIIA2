<?php
// Incluir la conexión a la base de datos y las clases necesarias
require_once '../controllers/db.php';
require_once '../models/consultas.php';

// Crear una instancia de la clase Consultas
$consultas = new Consultas($conn);

// Verificar el tipo de formulario
if (isset($_POST['form_type'])) {
    $form_type = $_POST['form_type'];

    // Manejar la recepción de los datos según el tipo de formulario
    if ($form_type === 'materia') {
        // Crear una instancia de la clase Materia
        $materia = new Materia($conn);
        $materia->handleFormSubmission();  // Método para procesar el formulario de materias
    } elseif ($form_type === 'carrera') {
        // Crear una instancia de la clase Carrera
        $carrera = new Carrera($conn);
        $carrera->handleFormSubmission();  // Método para procesar el formulario de carrera

    } elseif ($form_type === 'usuario') {
        // Crear una instancia de la clase Usuario
        $usuario = new Usuario($conn);
        $usuario->usuarios();  // Método para procesar el formulario de usuario
        
    } elseif ($form_type === 'grupo') {
        // Crear una instancia de la clase Grupo
        $grupo = new Grupo($conn);
        $grupo->handleFormSubmission();  // Método para procesar el formulario de grupo

    } elseif ($form_type === 'materia-grupo') {
        // Crear una instancia de la clase Usuario
        $materiagrupo = new MateriaGrupo($conn);
        $materiagrupo->GrupoMateria();  // Método para procesar el formulario de usuario

    }elseif ($form_type ===  'usuario-carrera'){
        $usuarioCarrera = new UsuarioHasCarrera($conn);
        $usuarioCarrera-> UsuarioCarrera();  // Método para procesar el formulario de usuario-carrera

    } elseif ($form_type === 'incidencia-usuario') {
        // Crear una instancia de la clase IncidenciaUsuario
        $incidenciaUsuario = new IncidenciaUsuario($conn);
        $incidenciaUsuario->handleRequest();  // Método para procesar el formulario de incidencia-usuario

    } elseif ($form_type === 'validacion-incidencia') {
        // Crear una instancia de la clase IncidenciaUsuario
        $incidenciaUsuario = new ActualizarEstado($conn);
        $incidenciaUsuario->handleForm();  // Método para procesar el formulario de incidencia-usuario
    } elseif ($form_type === 'edificio') {
        // Crear una instancia de la clase Edificio
        $edificio = new Edificio($conn);
        $edificio->gestionarEdificio();  // Método para procesar el formulario de edificio

    } elseif ($form_type === 'salon') {
        // Crear una instancia de la clase Salon
        $salon = new Salon($conn);
        $salon->gestionarSalon();  // Método para procesar el formulario de salón
        
    } elseif ($form_type === 'usuario-grupo') {
        // Crear una instancia de la clase UsuarioGrupo
        $usuarioGrupo = new UsuarioGrupo($conn);
        $usuarioGrupo->gestionarUsuarioGrupo();  // Método para procesar el formulario de usuario-grupo
        
    } elseif ($form_type === 'horario') {
        // Crear una instancia de la clase Horario
        $horario = new Horario($conn);
        $horario->gestionarHorario();  // Método para procesar el formulario de horarios
    } elseif ($form_type === 'borrar-horario') {
        // Crear una instancia de la clase BorrarHorario
        $borrarHorario = new BorrarHorario($conn);
        $borrarHorario->eliminarHorario(); // Método para manejar el formulario de borrado de horarios
    } elseif ($form_type === 'certificacion-usuario') {
        $certificacionUsuario = new CertificacionUsuario($conn);
        $certificacionUsuario->handleRequest(); // Nuevo método para manejar certificaciones
        
    } elseif ($form_type === 'actualizar-certificacion-usuario') {
        // Crear una instancia de la clase ActualizarCertificacionUsuario para actualizar certificación
        $actualizarCertificacionUsuario = new ActualizarCertificacionUsuario($conn);
        $actualizarCertificacionUsuario->handleRequest(); // Método para actualizar la certificación

    
        
    } elseif ($form_type === 'eliminar-certificacion-usuario') {
        // Crear una instancia de la clase CertificacionUsuarioUpdate para actualizar certificaciones
    $certificacionUsuarioDelete = new BorrarCertificacion($conn);
    $certificacionUsuarioDelete->eliminarCertificacion();

} elseif ($form_type === 'evaluacion-docente') {
    // Crear una instancia de la clase EvaluacionDocentes
    $evaluacionDocentes = new EvaluacionDocentes($conn);
    $evaluacionDocentes->gestionarEvaluacion();  // Método para manejar el formulario de evaluación docente


    } else {
        // Manejar otros formularios o enviar un mensaje de error
        echo "Formulario no reconocido.";
    }
} else {
    echo "Tipo de formulario no especificado.";
}

?>