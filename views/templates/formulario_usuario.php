<?php
include('../../models/session.php');
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/accesso_restringido.php');
include('aside.php');
require_once '../../views/templates/notificaciones.php';

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser  = $consultas->obtenerImagen($idusuario);

// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];

// Obtener notificaciones segun el tipo de usuario
$notificaciones = obtenerNotificaciones($conn, $idusuario);
// Obtener el tipo de usuario
$usuario_tipo = $consultas->obtenerTipoUsuarioPorId($idusuario);

// Intenta conectar a la base de datos
try {
  // Inicializa las consultas
  $consultas = new Consultas($conn);

  // Obtén los sexos
  $sexo = $consultas->obtenerSexos();

  // Obtén las carreras
  $carreras = $consultas->obtenerCarreras();

  // Obtén los cuerpos colegiados
  $cuerposColegiados = $consultas->obtenerCuerposColegiados();

  $periodos = $consultas->obtenerPeriodos();

  // Obtén los tipos de usuario
  $tiposUsuario = $consultas->obtenerTiposDeUsuario();

  $usuarios = $consultas->obtenerDatosUsuario();
} catch (Exception $e) {
  // Si falla la conexión, retorna un error
  $response['message'] = 'Error al conectar con la base de datos: ' . $e->getMessage();
  echo json_encode($response);
  exit();  // Finaliza la ejecución si no hay conexión
}
if (isset($_POST['logout'])) {
  $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}
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
} else { // Otro tipo de usuario
  $atajos = [
    ['icon' => 'fe-home', 'color' => 'bg-primary', 'text' => 'Inicio', 'url' => 'index.php']
  ];
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Formulario Usuarios</title>
  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="css/simplebar.css">
  <!-- Fonts CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="css/feather.css">
  <link rel="stylesheet" href="css/select2.css">
  <link rel="stylesheet" href="css/dropzone.css">
  <link rel="stylesheet" href="css/uppy.min.css">
  <link rel="stylesheet" href="css/jquery.steps.css">
  <link rel="stylesheet" href="css/jquery.timepicker.css">
  <link rel="stylesheet" href="css/quill.snow.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Incluir SweetAlert CSS y JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="css/daterangepicker.css">
  <!-- App CSS -->
  <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Luego incluye SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
          <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="avatar avatar-sm mt-2">
              <img src="<?= htmlspecialchars($imgUser['imagen_url'] ?? './assets/avatars/default.jpg') ?>"
                alt="Avatar del usuario"
                class="avatar-img rounded-circle"
                style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a>
            <a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a>
            <a class="dropdown-item" href="#"><i class="fas fa-tasks"></i> Activities</a>
            <form method="POST" action="" id="logoutForm">
              <button class="dropdown-item" type="submit" name="logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
            </form>
          </div>
        </li>
      </ul>
    </nav>
  </div>

  <main role="main" class="main-content">
    <!-- Formulario para subir datos de usuario -->
    <div class="col-md-12 mt-5">
      <div class="card shadow mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-center align-items-center mb-3">
            <p class="titulo-grande"><strong>Registro de Usuario</strong></p>
          </div>
          <form id="formUsuario" method="post" action="../../models/insert.php" enctype="multipart/form-data">
            <input type="hidden" name="form_type" value="usuario">
            <div id="smartwizard">
              <ul class="nav nav-pills justify-content-center flex-wrap flex-md-nowrap">
                <li class="nav-item"><a href="#step-1" class="nav-link">Paso 1<br><small>Datos Personales</small></a></li>
                <li class="nav-item"><a href="#step-2" class="nav-link">Paso 2<br><small>Datos Profesionales</small></a></li>
                <li class="nav-item"><a href="#step-3" class="nav-link">Paso 3<br><small>Correo y Contraseña</small></a></li>
                <li class="nav-item"><a href="#step-4" class="nav-link">Paso 4<br><small>Datos Finales</small></a></li>
              </ul>
              <div class="mt-4">
                <!-- Paso 1: Datos Personales -->
                <div id="step-1" class="step-content" style="display: block;">
                  <div class="row">
                    <div class="col-sm-12 col-md-4 mt-3">
                      <label for="usuario_nombre" class="form-label">Nombre:</label>
                      <input type="text" id="usuario_nombre" name="usuario_nombre" class="form-control" required>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3">
                      <label for="usuario_apellido_p" class="form-label">Apellido Paterno:</label>
                      <input type="text" id="usuario_apellido_p" name="usuario_apellido_p" class="form-control" required>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3">
                      <label for="usuario_apellido_m" class="form-label">Apellido Materno:</label>
                      <input type="text" id="usuario_apellido_m" name="usuario_apellido_m" class="form-control" required>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                  </div>
                  <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-3 mt-3 mr-3">
                      <label for="edad" class="form-label">Edad:</label>
                      <input type="number" id="edad" name="edad" class="form-control" required>
                      <div class="invalid-feedback">La edad debe estar entre 18 y 90 años.</div>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3">
                      <label for="sexo_sexo_id" class="form-label">Sexo:</label>
                      <select id="sexo_sexo_id" name="sexo_sexo_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione una opcion.</option>
                        <?php foreach ($sexo as $sexo): ?>
                          <option value="<?php echo $sexo['sexo_id']; ?>"><?php echo htmlspecialchars($sexo['descripcion']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Seleccione alguna opcion.</div>
                    </div>
                  </div>
                </div>

                <!-- Paso 2: Datos Profesionales -->
                <div id="step-2" class="step-content" style="display: none;">
                  <div class="row">
                    <div class="col-sm-12 col-md-4 mt-3">
                      <label for="numero_empleado" class="form-label">Número de Empleado:</label>
                      <input type="text" id="numero_empleado" name="numero_empleado" class="form-control" required>
                      <div class="invalid-feedback">Maximo 4 caracteres.</div>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3">
                      <label for="grado_academico" class="form-label">Grado Académico:</label>
                      <input type="text" id="grado_academico" name="grado_academico" class="form-control" required>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3">
                      <label for="cedula" class="form-label">Cédula:</label>
                      <input type="text" id="cedula" name="cedula" class="form-control" required>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 col-md-3 mt-3">
                      <label for="carrera_carrera_id" class="form-label">Carrera:</label>
                      <select id="carrera_carrera_id" name="carrera_carrera_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione una carrera</option>
                        <?php foreach ($carreras as $carrera): ?>
                          <option value="<?php echo $carrera['carrera_id']; ?>"><?php echo htmlspecialchars($carrera['nombre_carrera']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-3 mt-3">
                      <label for="periodo_periodo_id" class="form-label">Periodos:</label>
                      <select id="periodo_periodo_id" name="periodo_periodo_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione un periodo</option>
                        <?php foreach ($periodos as $periodo): ?>
                          <option value="<?php echo $periodo['periodo_id']; ?>"><?php echo htmlspecialchars($periodo['descripcion']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-3 mt-3">
                      <label for="cuerpo_colegiado_cuerpo_colegiado_id" class="form-label">Cuerpo Colegiado:</label>
                      <select id="cuerpo_colegiado_cuerpo_colegiado_id" name="cuerpo_colegiado_cuerpo_colegiado_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione una opcion.</option>
                        <?php foreach ($cuerposColegiados as $cuerpo): ?>
                          <option value="<?php echo $cuerpo['cuerpo_colegiado_id']; ?>"><?php echo htmlspecialchars($cuerpo['descripcion']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-3 mt-3">
                      <label for="fecha_contratacion" class="form-label">Fecha de Contratación:</label>
                      <input type="date" id="fecha_contratacion" name="fecha_contratacion" class="form-control" required>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                  </div>
                </div>

                <!-- Paso 3: Correo y Contraseña -->
                <div id="step-3" class="step-content" style="display: none;">
                  <div class="row">
                    <div class="col-sm-12 col-md-12 mt-3">
                      <label for="correo" class="form-label">Correo:</label>
                      <input type="email" id="correo" name="correo" class="form-control" required>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-6 mt-3">
                      <label for="password" class="form-label">Contraseña:</label>
                      <input type="password" id="password" name="password" class="form-control" required>
                      <div class="invalid-feedback">Minimo 8 Caracteres.</div>
                    </div>
                    <div class="col-sm-12 col-md-6 mt-3">
                      <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                      <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                      <div class="invalid-feedback">Las contraseñas deben coincidir.</div>
                    </div>
                  </div>
                </div>

                <!-- Paso 4: Datos Finales -->
                <div id="step-4" class="step-content" style="display: none;">
                  <div class="row">
                    <div class="col-sm-12 col-md-6 mt-3">
                      <label for="tipo_usuario_tipo_usuario_id" class="form-label">Tipo de Usuario:</label>
                      <select id="tipo_usuario_tipo_usuario_id" name="tipo_usuario_tipo_usuario_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione un tipo de usuario</option>
                        <?php foreach ($tiposUsuario as $tipo): ?>
                          <option value="<?php echo $tipo['tipo_usuario_id']; ?>"><?php echo htmlspecialchars($tipo['descripcion']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    <div class="col-sm-12 col-md-6 mt-3">
                      <label for="fileInput" class="form-label">Subir Imagen:</label>
                      <input type="file" id="fileInput" class="form-control" name="imagen_url" accept="image/*" required onchange="previewImage()">
                    </div>
                    <div class="col-sm-12 mt-3 d-flex justify-content-center">
                      <img id="imagePreview" src="#" alt="Vista previa de la imagen" style="display:none; width: 200px; height: 200px;">
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-md-12 text-center">
                      <span>Revise todos sus datos antes de confirmar el registro.</span>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-md-12 text-center">
                      <button type="submit" class="btn btn-success btn-lg">Registrar Usuario</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
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
    <div class="container-fluid ">
      <div class="row justify-content-center">
        <div class="col-12 ">
          <div class="d-flex justify-content-center align-items-center mb-3 col">
            <p class="titulo-grande"><strong>Registro de Usuarios</strong></p>
          </div>
          <div class="row my-4">
            <!-- Small table -->
            <div class="col-md-12 ">
              <div class="card shadow p-5">
                <div class="table-responsive">
                  <table class="table datatables" id="dataTable-1">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Edad</th>
                        <th>Correo</th>
                        <th>Fecha de Contratación</th>
                        <th>Número de Empleado</th>
                        <th>Grado Académico</th>
                        <th>Cédula</th>
                        <th>Sexo ID</th>
                        <th>Status ID</th>
                        <th>Tipo Usuario ID</th>
                        <th>Cuerpo Colegiado ID</th>
                        <th>Carrera ID</th>
                        <th>Periodo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                          <td><?php echo $usuario['usuario_id']; ?></td>
                          <td><?php echo $usuario['nombre_usuario']; ?></td>
                          <td><?php echo $usuario['apellido_p']; ?></td>
                          <td><?php echo $usuario['apellido_m']; ?></td>
                          <td><?php echo $usuario['edad']; ?></td>
                          <td><?php echo $usuario['correo']; ?></td>
                          <td><?php echo $usuario['fecha_contratacion']; ?></td>
                          <td><?php echo $usuario['numero_empleado']; ?></td>
                          <td><?php echo $usuario['grado_academico']; ?></td>
                          <td><?php echo $usuario['cedula']; ?></td>
                          <td><?php echo $usuario['sexo']; ?></td>
                          <td><?php echo $usuario['status']; ?></td>
                          <td><?php echo $usuario['tipo_usuario']; ?></td>
                          <td><?php echo $usuario['cuerpo_colegiado']; ?></td>
                          <td><?php echo $usuario['carrera']; ?></td>
                          <td><?php echo $usuario['periodo']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div> <!-- simple table -->
            </div> <!-- end section -->
          </div> <!-- .col-12 -->
        </div> <!-- .row -->
      </div> <!-- .container-fluid -->
    </div>
  </main>

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


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <!-- DataTables Bootstrap4 JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

  <!-- SmartWizard JS -->
  <script src="js/form_usuario.js"></script>
  <script src="js/jquery.smartWizard.min.js"></script>

  <!-- Otros scripts -->
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/moment.min.js"></script>
  <script src="js/simplebar.min.js"></script>
  <script src='js/daterangepicker.js'></script>
  <script src='js/jquery.stickOnScroll.js'></script>
  <script src="js/tinycolor-min.js"></script>
  <script src="js/config.js"></script>
  <script src='js/jquery.mask.min.js'></script>
  <script src='js/select2.min.js'></script>
  <script src='js/jquery.steps.min.js'></script>
  <script src='js/jquery.validate.min.js'></script>
  <script src='js/jquery.timepicker.js'></script>|
  <script src='js/dropzone.min.js'></script>
  <script src='js/uppy.min.js'></script>
  <script src='js/quill.min.js'></script>
  <script src='js/apps.js'></script>

  <script>
    $(function() {
      $('#dataTable-1').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "language": {
          "decimal": "",
          "emptyTable": "No hay usuarios",
          "info": "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
          "infoEmpty": "Mostrando 0 a 0 de 0 Saldos",
          "thousands": ",",
          "lengthMenu": "Mostrar  _MENU_ usuarios",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscador de usuarios",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        // Define la opción lengthMenu
        "lengthMenu": [
          [16, 32, 64, -1],
          [16, 32, 64, "Todos"]
        ]
      });
    });
  </script>
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