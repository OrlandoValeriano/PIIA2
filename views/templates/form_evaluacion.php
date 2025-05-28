<?php
include('../../models/session.php');
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/accesso_restringido.php');
include('aside.php');

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser  = $consultas->obtenerImagen($idusuario);

// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];

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
// Verificar si se ha enviado el formulario de cerrar sesión
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
    ['icon' => 'fe-briefcase', 'color' => 'bg-primary', 'text' => 'Registro de escenario', 'url' => 'form_edificio.php']
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

</head>

<body class="vertical  light  ">
  <div class="wrapper">
    <nav class="topnav navbar navbar-light">
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
    <main role="main" class="main-content mt-5">

    evaluación docente

<!-- Contenedor de Promedio de Calificaciones -->

          <div class="container-fluid mt-5  box-shadow-div p-5">
            <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
              Promedio de Calificaciones
            </div>
            <div class="container-fluid p-3">
              <div class="row">
                <!-- Tabla de Promedio de Calificaciones -->
                <div class="col-md-12 carta_Informacion">
                    <div class="form-group">
                        <label for="carrera_carrera_id">Selecciona una carrera:</label>
                        <select class="form-control" id="carrera_carrera_id" name="carrera_carrera_id" onchange="filtrarUsuariosPorCarrera()" required>
                            <option value="">Seleccione una carrera</option>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?php echo $carrera['carrera_id']; ?>">
                                    <?php echo $carrera['nombre_carrera']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="periodo_periodo_id" class="form-label-custom">Periodo:</label>
                        <select class="form-control" id="periodo_periodo_id" name="periodo_periodo_id" required onchange="filtrarUsuariosPorCarrera()">
                            <option value="">Selecciona un periodo</option>
                            <?php foreach ($periodos as $periodo): ?>
                                <option value="<?php echo $periodo['periodo_id']; ?>"><?php echo htmlspecialchars($periodo['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    

                    <div class="table-responsive">
                    <table class="table table-bordered" id="docentes-table">
                        <thead>
                            <tr>
                                <th>Nombre del Docente</th>
                                <th>Evaluación TECNM</th>
                                <th>Evaluación Estudiantil</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="docentes-table-body">
                        <!-- Las filas se llenan dinámicamente -->
                        </tbody>
                            </table>
                        </div>
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
                </div> <!-- /.container-fluid -->
            </div> <!-- /.container-fluid -->
          <script>
function filtrarUsuariosPorCarrera() {
    var carrera_id = document.getElementById("carrera_carrera_id").value;
    var periodo_id = document.getElementById("periodo_periodo_id").value; // Obtener el valor del periodo

    if (carrera_id === "" || periodo_id === "") {
        document.querySelector("#docentes-table-body").innerHTML = ""; // Vacía la tabla si no hay selección
        return;
    }

    fetch('../../models/obtener_docentes.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `carrera_id=${carrera_id}&periodo_id=${periodo_id}` // Incluye el periodo en la solicitud
    })
    .then(response => response.json())
    .then(data => {
        var tbody = document.querySelector("#docentes-table-body");
        tbody.innerHTML = ""; // Limpiar tabla antes de agregar nuevas filas

        if (data.error) {
            tbody.innerHTML = `<tr><td colspan="4">${data.error}</td></tr>`;
            return;
        }

        data.forEach(docente => {
            // Obtener las evaluaciones previas (si existen)
            var evaluacionTecnm = docente.evaluacion_tecnm || "00.0";
            var evaluacionEstudiantil = docente.evaluacion_estudiantil || "00.0";

            var row = `
<tr>
    <td>${docente.nombre_completo}</td>
    <td>
        <input type="number" name="evaluacionTECNM" class="evaluacionTECNM" value="${evaluacionTecnm}" min="0" max="100" step="0.1" required>
    </td>
    <td>
        <input type="number" name="evaluacionEstudiantil" class="evaluacionEstudiantil" value="${evaluacionEstudiantil}" min="0" max="100" step="0.1" required>
    </td>
    <td>
        <form method="POST" action="../../models/insert.php">
            <input type="hidden" name="usuario_usuario_id" value="${docente.usuario_id}">
            <input type="hidden" name="form_type" value="evaluacion-docente">
            <input type="hidden" name="periodo_periodo_id" id="periodo_periodo_id_value">
            <input type="hidden" class="input-tecnm" name="evaluacionTECNM" value="${evaluacionTecnm}">
            <input type="hidden" class="input-estudiantil" name="evaluacionEstudiantil" value="${evaluacionEstudiantil}">
            <button type="submit" class="btn btn-success btn-sm" onclick="actualizarInputs(this)">Guardar</button>
        </form>
    </td>
</tr>
`;
            tbody.innerHTML += row;
        });
    })
    .catch(error => console.error("Error al obtener docentes:", error));
}



function actualizarInputs(btn) {
    // Previene el envío del formulario
    event.preventDefault();

    var row = btn.closest("tr");
    var periodoValue = document.getElementById("periodo_periodo_id").value;
    row.querySelector("#periodo_periodo_id_value").value = periodoValue;
    row.querySelector(".input-tecnm").value = row.querySelector(".evaluacionTECNM").value;
    row.querySelector(".input-estudiantil").value = row.querySelector(".evaluacionEstudiantil").value;

    // Validación opcional
    if (row.querySelector(".evaluacionTECNM").value === "00.0" || 
        row.querySelector(".evaluacionEstudiantil").value === "00.0") {
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'Asegúrate de ingresar una evaluación válida antes de guardar.',
            allowOutsideClick: false,
        });
        return false;
    }

    // Muestra el SweetAlert que el usuario cierra manualmente
    Swal.fire({
        icon: 'success',
        title: '¡Registro exitoso!',
        text: 'Se ha registrado con éxito.',
        allowOutsideClick: false,
        confirmButtonText: 'Cerrar',
    }).then(() => {
        // Enviar formulario después de mostrar SweetAlert
        btn.closest("form").submit();
    });

    return false; // Impide envío automático
}


</script>

        
        <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
          aria-hidden="true">
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
                  <div class="list-group-item bg-transparent">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="fe fe-box fe-24"></span>
                      </div>
                      <div class="col">
                        <small><strong>Package has uploaded successfull</strong></small>
                        <div class="my-0 text-muted small">Package is zipped and uploaded</div>
                        <small class="badge badge-pill badge-light text-muted">1m ago</small>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item bg-transparent">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="fe fe-download fe-24"></span>
                      </div>
                      <div class="col">
                        <small><strong>Widgets are updated successfull</strong></small>
                        <div class="my-0 text-muted small">Just create new layout Index, form, table</div>
                        <small class="badge badge-pill badge-light text-muted">2m ago</small>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item bg-transparent">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="fe fe-inbox fe-24"></span>
                      </div>
                      <div class="col">
                        <small><strong>Notifications have been sent</strong></small>
                        <div class="my-0 text-muted small">Fusce dapibus, tellus ac cursus commodo</div>
                        <small class="badge badge-pill badge-light text-muted">30m ago</small>
                      </div>
                    </div> <!-- / .row -->
                  </div>
                  <div class="list-group-item bg-transparent">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="fe fe-link fe-24"></span>
                      </div>
                      <div class="col">
                        <small><strong>Link was attached to menu</strong></small>
                        <div class="my-0 text-muted small">New layout has been attached to the menu</div>
                        <small class="badge badge-pill badge-light text-muted">1h ago</small>
                      </div>
                    </div>
                  </div> <!-- / .row -->
                </div> <!-- / .list-group -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Clear All</button>
              </div>
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