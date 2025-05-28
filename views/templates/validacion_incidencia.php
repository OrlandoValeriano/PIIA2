<?php
include('../../models/session.php');
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/accesso_restringido.php');
include('aside.php');
require_once '../../views/templates/notificaciones.php';

if (isset($_POST['logout'])) {
  $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}

$conn = $database->getConnection();
$consultas = new Consultas($conn); 
// Obtener el ID del usuario actual y su tipo
$idusuario = (int) $_SESSION['user_id']; 
// Obtener notificaciones segun el tipo de usuario
$notificaciones = obtenerNotificaciones($conn, $idusuario);
// Obtener el tipo de usuario
$usuario_tipo = $consultas->obtenerTipoUsuarioPorId($idusuario);

// Obtener la carrera del usuario (asegúrate de que esté en la sesión o consulta si no está)
$carreraId = $_SESSION['carrera_id'] ?? $consultas->obtenerCarreraPorUsuarioId($idusuario);

// Si la carrera viene de la sesión, la tomamos directamente; si no, la consultamos.
$carreraData = $_SESSION['carrera_id'] ?? $consultas->obtenerCarreraPorUsuarioId($idusuario);

// Si se obtuvo desde la base de datos, extraer el ID del arreglo
if (is_array($carreraData)) {
  $carreraId = $carreraData['carrera_id'];
} else {
  $carreraId = $carreraData; // Ya es un ID numérico directo desde sesión
}

// Mostrarlo en consola
echo "<script>console.log('Carrera ID: " . $carreraId . "');</script>";


if (!$usuario_tipo) {
  die("Error: Tipo de usuario no encontrado para el ID proporcionado.");
}

if (!$carreraId) {
  die("Error: Carrera del usuario no encontrada.");
}

// Obtener incidencias según el tipo de usuario
if ($usuario_tipo == 1) {
  // Usuario tipo 1: solo incidencias propias
  $incidencias = $consultas->obtenerIncidenciasPorUsuario($idusuario);
} elseif (in_array($usuario_tipo, [2, 4, 6])) {
  // Usuarios tipo 2, 4 y 6: solo incidencias de su carrera
  $incidencias = $consultas->obtenerIncidenciasPorCarrera($carreraId);
} else {
  // Otros usuarios: todas las incidencias
  $incidencias = $consultas->obtenerDatosincidencias();
}
// Obtener las carreras
$carreras = $consultas->obtenerCarreras();

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser = $consultas->obtenerImagen($idusuario);
?>

<?php
// Crear instancia de Consultas
$consultas = new Consultas($conn);

// Obtener el ID del usuario actual y su tipo desde la sesión
$idusuario = (int) $_SESSION['user_id'];
$tipoUsuarioId = $consultas->obtenerTipoUsuarioPorId($idusuario);

// Validar tipo de usuario
if (!$tipoUsuarioId) {
  die("Error: Tipo de usuario no encontrado.");
}

// Definir atajos según el tipo de usuario
$atajos = [];
if ($tipoUsuarioId === 1) { // Usuario tipo 1 
  $atajos = [
    ['icon' => 'fe-coffee', 'color' => 'bg-primary', 'text' => 'Docentes', 'url' => 'dashboard_docentes.php'],
    ['icon' => 'fe-folder-minus', 'color' => 'bg-primary', 'text' => 'Incidencias', 'url' => 'form_incidencias.php'],
    ['icon' => 'fe-x-circle', 'color' => 'bg-primary', 'text' => 'Estado de incidencias', 'url' => 'validacion_incidencia.php']
  ];
} elseif ($tipoUsuarioId === 2) { // Usuario tipo 2 
  $atajos = [
    ['icon' => 'fe-coffee', 'color' => 'bg-primary', 'text' => 'Docentes', 'url' => 'dashboard_docentes.php'],
    ['icon' => 'fe-clipboard', 'color' => 'bg-primary', 'text' => 'Carrera', 'url' => 'dashboard_carreras.php'],
    ['icon' => 'fe-folder-minus', 'color' => 'bg-primary', 'text' => 'Incidencias', 'url' => 'form_incidencias.php'],
    ['icon' => 'fe-x-circle', 'color' => 'bg-primary', 'text' => 'Estado de incidencias', 'url' => 'validacion_incidencia.php'],
    ['icon' => 'fe-calendar', 'color' => 'bg-primary', 'text' => 'Horario', 'url' => 'form_horario.php']

  ];
} elseif ($tipoUsuarioId === 3) { // Usuario tipo 3 
  $atajos = [
    ['icon' => 'fe-users', 'color' => 'bg-primary', 'text' => 'Recursos humanos', 'url' => 'recursos_humanos_empleados.php'],
    ['icon' => 'fe-user', 'color' => 'bg-primary', 'text' => 'Registro de usuarios', 'url' => 'formulario_usuario.php'],
    ['icon' => 'fe-folder-minus', 'color' => 'bg-primary', 'text' => 'Registro de incidencias', 'url' => 'form_incidencias.php'],
    ['icon' => 'fe-x-circle', 'color' => 'bg-primary', 'text' => 'Estado de incidencias', 'url' => 'validacion_incidencia.php']
  ];
} elseif ($tipoUsuarioId === 4) { // Usuario tipo 4 
  $atajos = [
    ['icon' => 'fe-trending-up', 'color' => 'bg-primary', 'text' => 'Desarrollo academico', 'url' => 'desarrollo_academico_docentes.php'],
    ['icon' => 'fe-edit', 'color' => 'bg-primary', 'text' => 'Registro de materias', 'url' => 'form_materia.php'],
    ['icon' => 'fe-folder-minus', 'color' => 'bg-primary', 'text' => 'Registro de carreras', 'url' => 'form_carrera.php'],
    ['icon' => 'fe-users', 'color' => 'bg-primary', 'text' => 'Registro de grupos', 'url' => 'formulario_grupo.php'],
    ['icon' => 'fe-folder-plus', 'color' => 'bg-primary', 'text' => 'Asignacion de carreras', 'url' => 'form_usuarios-carreras.php'],
    ['icon' => 'fe-briefcase', 'color' => 'bg-primary', 'text' => 'Registro de escenario', 'url' => 'form_edificio.php'],
    ['icon' => 'fe-calendar', 'color' => 'bg-primary', 'text' => 'Horario', 'url' => 'form_horario.php']
  ];
} elseif ($tipoUsuarioId === 5) { // Usuario tipo 5
  $atajos = [
    ['icon' => 'fe-coffee', 'color' => 'bg-primary', 'text' => 'Docentes', 'url' => 'dashboard_docentes.php'],
    ['icon' => 'fe-clipboard', 'color' => 'bg-primary', 'text' => 'Carrera', 'url' => 'dashboard_carreras.php'],
    ['icon' => 'fe-trending-up', 'color' => 'bg-primary', 'text' => 'Desarrollo academico', 'url' => 'desarrollo_academico_docentes.php'],
    ['icon' => 'fe-users', 'color' => 'bg-primary', 'text' => 'Recursos humanos', 'url' => 'recursos_humanos_empleados.php']
  ];
} elseif ($tipoUsuarioId === 6) { // Usuario tipo 6
  $atajos = [
    ['icon' => 'fe-home', 'color' => 'bg-primary', 'text' => 'Inicio', 'url' => 'index.php']
  ];
} elseif ($tipoUsuarioId === 7) { // Usuario tipo 7
  $atajos = [
    ['icon' => 'fe-x-circle', 'color' => 'bg-primary', 'text' => 'Estado de incidencias', 'url' => 'validacion_incidencia.php']
  ];
} elseif ($tipoUsuarioId === 8) { // Usuario tipo 8
  $atajos = [
    ['icon' => 'fe-home', 'color' => 'bg-primary', 'text' => 'Inicio', 'url' => 'index.php'],
    ['icon' => 'fe-x-circle', 'color' => 'bg-primary', 'text' => 'Estado de incidencias', 'url' => 'validacion_incidencia.php'],
    ['icon' => 'fe-folder-minus', 'color' => 'bg-primary', 'text' => 'Incidencias', 'url' => 'form_incidencias.php'],
    ['icon' => 'fe-coffee', 'color' => 'bg-primary', 'text' => 'Docentes', 'url' => 'dashboard_docentes.php'],
    ['icon' => 'fe-clipboard', 'color' => 'bg-primary', 'text' => 'Carrera', 'url' => 'dashboard_carreras.php'],
    ['icon' => 'fe-calendar', 'color' => 'bg-primary', 'text' => 'Horario', 'url' => 'form_horario.php'],
    ['icon' => 'fe-users', 'color' => 'bg-primary', 'text' => 'Recursos humanos', 'url' => 'recursos_humanos_empleados.php'],
    ['icon' => 'fe-user', 'color' => 'bg-primary', 'text' => 'Registro de usuarios', 'url' => 'formulario_usuario.php'],
    ['icon' => 'fe-trending-up', 'color' => 'bg-primary', 'text' => 'Desarrollo academico', 'url' => 'desarrollo_academico_docentes.php'],
    ['icon' => 'fe-edit', 'color' => 'bg-primary', 'text' => 'Registro de materias', 'url' => 'form_materia.php'],
    ['icon' => 'fe-folder-minus', 'color' => 'bg-primary', 'text' => 'Registro de carreras', 'url' => 'form_carrera.php'],
    ['icon' => 'fe-users', 'color' => 'bg-primary', 'text' => 'Registro de grupos', 'url' => 'formulario_grupo.php'],
    ['icon' => 'fe-folder-plus', 'color' => 'bg-primary', 'text' => 'Asignacion de carreras', 'url' => 'form_usuarios-carreras.php'],
    ['icon' => 'fe-briefcase', 'color' => 'bg-primary', 'text' => 'Registro de escenario', 'url' => 'form_edificio.php'],
    ['icon' => 'fe-check-square', 'color' => 'bg-primary', 'text' => 'Evaluacion docente', 'url' => 'form_evaluacion.php'],
  ];
} else { // Otro tipo de usuario
  $atajos = [
    ['icon' => 'fe-home', 'color' => 'bg-primary', 'text' => 'Inicio', 'url' => 'index.php']
  ];
}
?>

<!-- Aquí sigue tu código HTML para el formulario -->

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Reporte de Incidencias</title>
  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="css/simplebar.css">
  <!-- Fonts CSS -->
  <link
    href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="css/feather.css">
  <link rel="stylesheet" href="css/select2.css">
  <link rel="stylesheet" href="css/dropzone.css">
  <link rel="stylesheet" href="css/uppy.min.css">
  <link rel="stylesheet" href="css/jquery.steps.css">
  <link rel="stylesheet" href="css/jquery.timepicker.css">
  <link rel="stylesheet" href="css/quill.snow.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="css/daterangepicker.css">
  <!-- App CSS -->
  <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Include DataTables CSS and JS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

  <script src="js/form_carrera.js"></script>
  <script src="js/navbar-animation.js" defer></script>

</head>

<body class="vertical  light  ">
  <div class="wrapper">
    <nav class="topnav navbar navbar-light" id="nav-bar">
      <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
      </button>
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
            <i class="fe fe-sun fe-16"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
            <span class="fe fe-grid fe-16"></span>
          </a>
        </li>
        <li class="nav-item nav-notif">
          <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
            <span class="fe fe-bell fe-16"></span>
            <span class="dot dot-md bg-success"></span>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="avatar avatar-sm mt-2">
              <img src="<?= htmlspecialchars($imgUser['imagen_url'] ?? './assets/avatars/default.jpg') ?>"
                alt="Avatar del usuario" class="avatar-img rounded-circle"
                style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="Perfil.php"><i class="fas fa-user"></i> Profile</a>
            <a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a>
            <a class="dropdown-item" href="#"><i class="fas fa-tasks"></i> Activities</a>
            <form method="POST" action="" id="logoutForm">
              <button class="dropdown-item" type="submit" name="logout"><i class="fas fa-sign-out-alt"></i> Cerrar
                sesión</button>
            </form>
          </div>
        </li>
      </ul>
    </nav>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <main role="main" class="main-content">
    <div class="col-md-12 mt-5">
      <div class="card shadow mb-4">
        <div class="card-body">
          <!-- LOGOS -->
          <div class="logo-container mb-3">
            <img class="form-logo-left" src="assets/images/logo-teschi.png" alt="Logo Izquierda">
            <img class="form-logo-right" src="assets/icon/icon_piia.png" alt="Logo Derecha">
          </div>

          <!-- CONTENEDOR DE ÁREA Y FECHA -->
          <div class="container-fluid">
            <div class="row justify-content-center">
              <div class="col-12">
                <div class="row my-4">
                  <div class="col-md-12">
                    <div class="card shadow p-5">
                      <div class="table-responsive">

                        <!-- TÍTULO -->
                        <div class="d-flex justify-content-center align-items-center mb-3 col">
                          <p class="titulo-grande"><strong>ESTADO INCIDENCIAS</strong></p>
                        </div>

                        <!-- FILTRO -->
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="filter-container-status ml-auto dis">
                            <label for="statusFilter" class="mr-2">Filtro Estatus:</label>
                            <select id="statusFilter" class="form-control form-control-sm">
                              <option value="all">Todas</option>
                              <option value="1">Aprobadas</option>
                              <option value="2">Rechazadas</option>
                              <option value="3" selected>Pendientes</option>
                            </select>
                          </div>
                        </div>

                        <!-- TABLA -->
                        <table class="table datatables" id="dataTable-1">
                          <thead>
                            <tr>
                              <th>Tipo Incidencia</th>
                              <th>Usuario</th>
                              <th>Fecha Solicitada</th>
                              <th>Motivo</th>
                              <th>Otros</th>
                              <th>Documento Médico</th>
                              <th>Horario Inicio</th>
                              <th>Horario Término</th>
                              <th>Horario Incidencia</th>
                              <th>Día Incidencia</th>
                              <th>Carrera</th>
                              <th>Validación por División Académica</th>
                              <th>Validación por Subdirección</th>
                              <th>Validación por Recursos Humanos</th>
                              <th>Estado de la Incidencia</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            function getStatus($validacionDivision, $validacionSubdireccion, $validacionRH)
                            {
                              if ($validacionDivision == 2 || $validacionSubdireccion == 2 || $validacionRH == 2)
                                return 2;
                              if ($validacionDivision == 3 || $validacionSubdireccion == 3 || $validacionRH == 3)
                                return 3;
                              return 1;
                            }

                            function getStatusClass($status)
                            {
                              switch ($status) {
                                case 1:
                                  return 'status-color-green';
                                case 2:
                                  return 'status-color-red';
                                case 3:
                                  return 'status-color-yellow';
                                default:
                                  return 'status-color-gray';
                              }
                            }
                            ?>

                            <?php foreach ($incidencias as $incidencia): ?>
                              <tr>
                                <td><?= $incidencia['descripcion_incidencia']; ?></td>
                                <td>
                                  <?= $incidencia['nombre_usuario'] . ' ' . $incidencia['apellido_paterno'] . ' ' . $incidencia['apellido_materno']; ?>
                                </td>
                                <td><?= $incidencia['fecha_solicitada']; ?></td>
                                <td><?= $incidencia['motivo']; ?></td>
                                <td><?= !empty($incidencia['otro']) ? $incidencia['otro'] : '-'; ?></td>

                                <!-- Documento Médico -->
                                <td class="text-center">
                                  <?php if (!empty($incidencia['doc_medico'])): ?>
                                    <?php $correctFilePath = str_replace('views/', '', $incidencia['doc_medico']); ?>
                                    <a href="<?= $correctFilePath; ?>" target="_blank" class="btn btn-sm btn-primary">Ver
                                      Documento</a>
                                  <?php else: ?>
                                    No disponible
                                  <?php endif; ?>
                                </td>

                                <td><?= $incidencia['horario_inicio']; ?></td>
                                <td><?= $incidencia['horario_termino']; ?></td>
                                <td><?= $incidencia['horario_incidencia']; ?></td>
                                <td><?= $incidencia['dia_incidencia']; ?></td>
                                <td><?= $incidencia['nombre_carrera']; ?></td>

                                <!-- Validaciones -->
                                <td class="text-center">
                                  <?php $statusClass = getStatusClass($incidencia['validacion_division_academica']); ?>
                                  <span class="status-color <?= $statusClass; ?>" <?php if ($usuario_tipo == 2): ?>
                                    onclick="validarIncidencia(this)"
                                    data-incidencia-id="<?= $incidencia['incidencia_has_usuario_id']; ?>"
                                    data-validacion="division" <?php endif; ?>>
                                  </span>
                                </td>

                                <td class="text-center">
                                  <?php $statusClass = getStatusClass($incidencia['validacion_subdireccion']); ?>
                                  <span class="status-color <?= $statusClass; ?>" <?php if ($usuario_tipo == 7): ?>
                                    onclick="validarIncidencia(this)"
                                    data-incidencia-id="<?= $incidencia['id_incidencia_has_usuario']; ?>"
                                    data-validacion="subdireccion" <?php endif; ?>>
                                  </span>
                                </td>

                                <td class="text-center">
                                  <?php $statusClass = getStatusClass($incidencia['validacion_rh']); ?>
                                  <span class="status-color <?= $statusClass; ?>" <?php if ($usuario_tipo == 3): ?>
                                    onclick="validarIncidencia(this)"
                                    data-incidencia-id="<?= $incidencia['id_incidencia_has_usuario']; ?>"
                                    data-validacion="rh" <?php endif; ?>>
                                  </span>
                                </td>

                                <!-- Estado final -->
                                <td class="text-center">
                                  <?php $statusClass = getStatusClass($incidencia['status_incidencia_id']); ?>
                                  <span class="status-color <?= $statusClass; ?>"
                                    data-status="<?= $incidencia['status_incidencia_id']; ?>"></span>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div> <!-- .table-responsive -->
                    </div> <!-- .card -->
                  </div> <!-- .col-md-12 -->
                </div> <!-- .row.my-4 -->
              </div> <!-- .col-12 -->
            </div> <!-- .row -->
          </div> <!-- .container-fluid -->
        </div> <!-- .card-body -->
      </div> <!-- .card -->
    </div> <!-- .col-md-12 -->
  </main>

  <!-- MODAL DE CONFIRMACIÓN -->
  <div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="customModalLabel">AVISO DE JUSTIFICACIÓN DE PUNTUALIDAD Y ASISTENCIA</h5>
        </div>
        <div class="modal-body">DATOS ENVIADOS.</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="closeModal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de notificaciones -->
  <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="defaultModalLabel">Notifications</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="list-group list-group-flush my-n3">
            <?php foreach ($notificaciones as $notificacion): ?>
              <?php
              $fondo = $notificacion['vista'] == 0 ? 'background-color: #CAE1C9' : '';
              ?>
              <a href="marcar_vista.php?id=<?php echo $notificacion['id_notificacion']; ?>"
                class="list-group-item text-reset text-decoration-none"
                style="<?php echo $fondo; ?>"
                data-id="<?php echo $notificacion['id_notificacion']; ?>">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="fe fe-box fe-24"></span></div>
                  <div class="col">
                    <small>
                      <strong><?php echo formatearNombreCompleto($notificacion, $usuario_tipo); ?></strong>
                      </strong>
                    </small>
                    <div class="my-0 text-muted small"><?php echo htmlspecialchars($notificacion['mensaje']); ?></div>
                    <small class="badge badge-pill badge-light text-muted">
                      <?php echo date('g:i A - d M Y', strtotime($notificacion['fecha'])); ?>
                    </small>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Clear All</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="defaultModalLabel">Shortcuts</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-5">
          <div class="row align-items-center justify-content-start">
            <?php foreach ($atajos as $atajo): ?>
              <div class="col-6 text-center">
                <a href="<?= $atajo['url'] ?>" class="text-decoration-none">
                  <div class="squircle justify-content-center">
                    <i class="fe <?= $atajo['icon'] ?> fe-32 align-self-center text-white"></i>
                  </div>
                  <p class="letra-atajo"><?= htmlspecialchars($atajo['text']) ?></p>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/moment.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/simplebar.min.js"></script>
  <script src='js/daterangepicker.js'></script>
  <script src='js/jquery.stickOnScroll.js'></script>
  <script src="js/tinycolor-min.js"></script>
  <script src="js/config.js"></script>
  <script src="js/d3.min.js"></script>
  <script src="js/topojson.min.js"></script>
  <script src="js/datamaps.all.min.js"></script>
  <script src="js/datamaps-zoomto.js"></script>
  <script src="js/datamaps.custom.js"></script>
  <script src="js/Chart.min.js"></script>
  <!-- Incluir SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('statusFilter').addEventListener('change', function() {
      const selectedStatus = this.value; // Valor seleccionado en el filtro
      const rows = document.querySelectorAll('#dataTable-1 tbody tr'); // Filas de la tabla

      rows.forEach(row => {
        const statusCell = row.querySelector('td:last-child span'); // Buscar el span del estado dentro de la última celda
        if (statusCell) {
          const statusValue = statusCell.getAttribute('data-status'); // Obtener el valor data-status

          if (selectedStatus === 'all') {
            row.style.display = ''; // Mostrar todas si selecciona 'Todas'
          } else if (statusValue === selectedStatus) {
            row.style.display = ''; // Mostrar solo las que coinciden
          } else {
            row.style.display = 'none'; // Ocultar las que no coinciden
          }
        }
      });
    });

    // Aplicar el filtro por defecto (Pendientes) al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
      // Establecer el valor predeterminado como '3' (Pendientes)
      document.getElementById('statusFilter').value = '3';
      // Activar el filtro para aplicar la acción
      document.getElementById('statusFilter').dispatchEvent(new Event('change'));
    });
  </script>

  <script>
    function validarIncidencia(element) {
      const incidenciaId = element.getAttribute("data-incidencia-id"); // ID de la incidencia seleccionada
      const validacion = element.getAttribute("data-validacion"); // Tipo de validación (division, subdireccion, rh)

      Swal.fire({
        title: '¿Aceptar o rechazar esta incidencia?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        denyButtonText: 'Rechazar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          actualizarIncidencia(incidenciaId, validacion, 1); // Estado 1: Aceptar

        } else if (result.isDenied) {
          actualizarIncidencia(incidenciaId, validacion, 2); // Estado 2: Rechazar
        }
      });
    }

    function actualizarIncidencia(incidenciaId, validacion, estado) {
      const formData = new FormData();

      formData.append("form_type", "validacion-incidencia"); // Tipo de formulario
      formData.append("incidencia_id", incidenciaId); // Enviar solo el ID de la incidencia seleccionada
      formData.append("validacion", validacion);
      formData.append("estado", estado); // Estado (1, 2, o 3)

      fetch('../../models/insert.php', {
          method: 'POST',
          body: formData,
        })
        .then((response) => response.text())
        .then((data) => {
          console.log("Respuesta del servidor:", data); // Depuración

          if (data.includes('La división académica no ha aprobado aún.')) {
            Swal.fire('Error', 'La división académica no ha aprobado aún.', 'error');
          } else if (data.includes('La subdirección no ha aprobado aún.')) {
            Swal.fire('Error', 'La subdirección no ha aprobado aún.', 'error');
          } else if (data.includes('success')) {
            Swal.fire('Actualizado', 'La incidencia fue actualizada correctamente.', 'success')
              .then(() => {
                // Actualiza solo la fila correspondiente con el ID de la incidencia
                let fila = document.querySelector(`[data-incidencia-id="${incidenciaId}"]`).closest('tr');
                if (fila) {
                  let statusCell = fila.querySelector(`.status-color[data-validacion="${validacion}"]`);

                  // Actualizar el color de la clase según el estado
                  if (estado === 1) {
                    statusCell.classList.add('status-color-green');
                    statusCell.classList.remove('status-color-red', 'status-color-yellow');
                  } else if (estado === 2) {
                    statusCell.classList.add('status-color-red');
                    statusCell.classList.remove('status-color-green', 'status-color-yellow');
                  } else {
                    statusCell.classList.add('status-color-yellow');
                    statusCell.classList.remove('status-color-green', 'status-color-red');
                  }
                }
                // Recargar la página después de mostrar el mensaje de éxito
                location.reload();
              });
          } else {
            Swal.fire('Error', `${data}`, 'error');
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          Swal.fire('Error', 'Ocurrió un error en el servidor. Por favor, inténtalo más tarde.', 'error');
        });
    }
  </script>




  <script>
    function getNextBusinessDays(date, days) {
      let result = new Date(date);
      let addedDays = 0;

      while (addedDays < days) {
        result.setDate(result.getDate() + 1);
        // Skip Saturdays (6) and Sundays (0)
        if (result.getDay() !== 6 && result.getDay() !== 0) {
          addedDays++;
        }
      }
      return result;
    }

    function getPreviousBusinessDays(date, days) {
      let result = new Date(date);
      let subtractedDays = 0;

      while (subtractedDays < days) {
        result.setDate(result.getDate() - 1);
        // Skip Saturdays (6) and Sundays (0)
        if (result.getDay() !== 6 && result.getDay() !== 0) {
          subtractedDays++;
        }
      }
      return result;
    }

    // Obtenemos la fecha actual
    const today = new Date();

    // Calculamos las fechas mínima y máxima excluyendo fines de semana
    const minDate = getPreviousBusinessDays(today, 3); // 3 días hábiles antes
    const maxDate = getNextBusinessDays(today, 3); // 3 días hábiles después

    // Convertimos las fechas al formato YYYY-MM-DD
    const minDateString = minDate.toISOString().split("T")[0];
    const maxDateString = maxDate.toISOString().split("T")[0];

    // Establecemos los atributos min y max en el input de fecha
    const fechaInput = document.getElementById("fecha");
    fechaInput.min = minDateString;
    fechaInput.max = maxDateString;
  </script>
  <script>
    /* defind global options */
    Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
    Chart.defaults.global.defaultFontColor = colors.mutedColor;
  </script>
  <script src="js/gauge.min.js"></script>
  <script src="js/jquery.sparkline.min.js"></script>
  <script src="js/apexcharts.min.js"></script>
  <script src="js/apexcharts.custom.js"></script>
  <script src='js/jquery.mask.min.js'></script>
  <script src='js/select2.min.js'></script>
  <script src='js/jquery.steps.min.js'></script>
  <script src='js/jquery.validate.min.js'></script>
  <script src='js/jquery.timepicker.js'></script>
  <script src='js/dropzone.min.js'></script>
  <script src='js/uppy.min.js'></script>
  <script src='js/quill.min.js'></script>
  <script>
    $('.select2').select2({
      theme: 'bootstrap4',
    });
    $('.select2-multi').select2({
      multiple: true,
      theme: 'bootstrap4',
    });
    $('.drgpicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      showDropdowns: true,
      locale: {
        format: 'MM/DD/YYYY'
      }
    });
    $('.time-input').timepicker({
      'scrollDefault': 'now',
      'zindex': '9999' /* fix modal open */
    });
    /** date range picker */
    if ($('.datetimes').length) {
      $('.datetimes').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'M/DD hh:mm A'
        }
      });
    }
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#reportrange').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
    }, cb);
    cb(start, end);
    $('.input-placeholder').mask("00/00/0000", {
      placeholder: "__/__/____"
    });
    $('.input-zip').mask('00000-000', {
      placeholder: "____-___"
    });
    $('.input-money').mask("#.##0,00", {
      reverse: true
    });
    $('.input-phoneus').mask('(000) 000-0000');
    $('.input-mixed').mask('AAA 000-S0S');
    $('.input-ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
      translation: {
        'Z': {
          pattern: /[0-9]/,
          optional: true
        }
      },
      placeholder: "___.___.___.___"
    });
    // editor
    var editor = document.getElementById('editor');
    if (editor) {
      var toolbarOptions = [
        [{
          'font': []
        }],
        [{
          'header': [1, 2, 3, 4, 5, 6, false]
        }],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{
            'header': 1
          },
          {
            'header': 2
          }
        ],
        [{
            'list': 'ordered'
          },
          {
            'list': 'bullet'
          }
        ],
        [{
            'script': 'sub'
          },
          {
            'script': 'super'
          }
        ],
        [{
            'indent': '-1'
          },
          {
            'indent': '+1'
          }
        ], // outdent/indent
        [{
          'direction': 'rtl'
        }], // text direction
        [{
            'color': []
          },
          {
            'background': []
          }
        ], // dropdown with defaults from theme
        [{
          'align': []
        }],
        ['clean'] // remove formatting button
      ];
      var quill = new Quill(editor, {
        modules: {
          toolbar: toolbarOptions
        },
        theme: 'snow'
      });
    }
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>
  <script>
    var uptarg = document.getElementById('drag-drop-area');
    if (uptarg) {
      var uppy = Uppy.Core().use(Uppy.Dashboard, {
        inline: true,
        target: uptarg,
        proudlyDisplayPoweredByUppy: false,
        theme: 'dark',
        width: 770,
        height: 210,
        plugins: ['Webcam']
      }).use(Uppy.Tus, {
        endpoint: 'https://master.tus.io/files/'
      });
      uppy.on('complete', (result) => {
        console.log('Upload complete! We’ve uploaded these files:', result.successful)
      });
    }
  </script>
  <script src="js/apps.js"></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-56159088-1');
  </script>
</body>

</html>