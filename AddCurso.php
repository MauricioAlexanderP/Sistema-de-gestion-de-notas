<?php
session_start();
require_once __DIR__ . '/model/Curso.php';
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/model/Inscripcion.php';

// Verificar si el usuario está logueado y es profesor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
  header('Location: login.php');
  exit;
}

$profesor_id = $_SESSION['usuario_id'];
$cursoModel = new Curso();
$usuarioModel = new Usuario();
$inscripcionModel = new Inscripcion();

$profesores = $usuarioModel->obtenerPorRol('profesor');
$alumnos = $usuarioModel->obtenerPorRol('alumno');
$cursos_profesor = $cursoModel->obtenerPorProfesor($profesor_id);
$mensaje = null;
$error = null;

$modo_edicion = false;
$curso_editar = null;

$old_nombre = '';
$old_codigo = '';
$old_profesor_id = '';

// Manejar edición de curso
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
  $curso_id = (int)$_GET['editar'];
  $curso_editar = $cursoModel->obtenerPorId($curso_id);
  // Verificar que el curso pertenece al profesor logueado
  if ($curso_editar && $curso_editar['profesor_id'] == $profesor_id) {
    $modo_edicion = true;
    $old_nombre = $curso_editar['nombre'];
    $old_codigo = $curso_editar['codigo'];
    $old_profesor_id = $curso_editar['profesor_id'];
  }
}

// Manejar eliminación de curso
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  $curso_id = (int)$_GET['eliminar'];
  $curso_eliminar = $cursoModel->obtenerPorId($curso_id);
  // Verificar que el curso pertenece al profesor logueado
  if ($curso_eliminar && $curso_eliminar['profesor_id'] == $profesor_id) {
    $cursoModel->id = $curso_id;
    if ($cursoModel->eliminar()) {
      $mensaje = 'Curso "' . htmlspecialchars($curso_eliminar['nombre'], ENT_QUOTES, 'UTF-8') . '" eliminado correctamente.';
    } else {
      $error = 'No se pudo eliminar el curso. Intenta nuevamente.';
    }
  } else {
    $error = 'Curso no encontrado o no tienes permisos para eliminarlo.';
  }
}

// Manejar inscripción de alumno
if (isset($_GET['inscribir']) && is_numeric($_GET['inscribir'])) {
  $curso_id = (int)$_GET['inscribir'];
  $curso_inscribir = $cursoModel->obtenerPorId($curso_id);
  // Verificar que el curso pertenece al profesor logueado
  if ($curso_inscribir && $curso_inscribir['profesor_id'] == $profesor_id) {
    $modo_inscripcion = true;
    $curso_seleccionado = $curso_inscribir;
  }
}

// Manejar eliminación de inscripción
if (isset($_GET['eliminar_inscripcion']) && is_numeric($_GET['eliminar_inscripcion'])) {
  $inscripcion_id = (int)$_GET['eliminar_inscripcion'];
  $inscripcion_eliminar = $inscripcionModel->obtenerPorId($inscripcion_id);
  if ($inscripcion_eliminar) {
    // Verificar que la inscripción pertenece a un curso del profesor
    $curso_verificar = $cursoModel->obtenerPorId($inscripcion_eliminar['curso_id']);
    if ($curso_verificar && $curso_verificar['profesor_id'] == $profesor_id) {
      $inscripcionModel->id = $inscripcion_id;
      if ($inscripcionModel->eliminar()) {
        $mensaje = 'Inscripción eliminada correctamente.';
      } else {
        $error = 'No se pudo eliminar la inscripción. Intenta nuevamente.';
      }
    } else {
      $error = 'No tienes permisos para eliminar esta inscripción.';
    }
  } else {
    $error = 'Inscripción no encontrada.';
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = isset($_POST['accion']) ? $_POST['accion'] : 'crear';

  if ($accion === 'inscribir') {
    // Lógica para inscribir alumno
    $alumno_id = isset($_POST['alumno_id']) ? (int)$_POST['alumno_id'] : 0;
    $curso_id = isset($_POST['curso_id']) ? (int)$_POST['curso_id'] : 0;

    if ($alumno_id <= 0 || $curso_id <= 0) {
      $error = 'Debes seleccionar un alumno y un curso.';
    } else {
      // Verificar que el curso pertenece al profesor logueado
      $curso_verificar = $cursoModel->obtenerPorId($curso_id);
      if ($curso_verificar && $curso_verificar['profesor_id'] == $profesor_id) {
        $inscripcionModel->alumno_id = $alumno_id;
        $inscripcionModel->curso_id = $curso_id;

        if ($inscripcionModel->crear()) {
          $mensaje = 'Alumno inscrito correctamente en el curso.';
        } else {
          $error = 'No se pudo inscribir al alumno. Posiblemente ya está inscrito en este curso.';
        }
      } else {
        $error = 'No tienes permisos para inscribir en este curso.';
      }
    }
  } else {
    // Lógica para crear/editar cursos (existente)
    $old_nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $old_codigo = isset($_POST['codigo']) ? strtoupper(trim($_POST['codigo'])) : '';
    $old_profesor_id = isset($_POST['profesor_id']) ? trim($_POST['profesor_id']) : '';
    $curso_id = isset($_POST['curso_id']) ? (int)$_POST['curso_id'] : 0;

    if ($old_nombre === '' || $old_profesor_id === '') {
      $error = 'El nombre del curso y el profesor son obligatorios.';
    } else {
      try {
        if ($accion === 'editar') {
          // Lógica para editar curso
          if ($curso_id <= 0) {
            throw new Exception('ID de curso inválido.');
          }

          // Verificar que el curso pertenece al profesor logueado
          $curso_verificar = $cursoModel->obtenerPorId($curso_id);
          if (!$curso_verificar || $curso_verificar['profesor_id'] != $profesor_id) {
            throw new Exception('No tienes permisos para editar este curso.');
          }

          // Validar código si se cambió
          if ($old_codigo !== '') {
            if (!$cursoModel->validarFormatoCodigo($old_codigo)) {
              throw new Exception('El código no cumple el formato AAA-123 (3-5 letras mayúsculas, guión y 3 dígitos).');
            }
            if ($cursoModel->codigoExiste($old_codigo, $curso_id)) {
              throw new Exception('El código ingresado ya existe. Prueba con otro.');
            }
            $codigo = $old_codigo;
          } else {
            throw new Exception('El código es obligatorio para editar un curso.');
          }

          $cursoModel->id = $curso_id;
          $cursoModel->nombre = $old_nombre;
          $cursoModel->codigo = $codigo;
          $cursoModel->profesor_id = (int)$old_profesor_id;

          if ($cursoModel->actualizar()) {
            $mensaje = 'Curso actualizado correctamente.';
            // Limpiar valores del formulario tras éxito
            $old_nombre = '';
            $old_codigo = '';
            $old_profesor_id = '';
            $modo_edicion = false;
            $curso_editar = null;
          } else {
            $error = 'No se pudo actualizar el curso. Intenta nuevamente.';
          }
        } else {
          // Lógica para crear curso (existente)
          if ($old_codigo === '') {
            $codigo_generado = $cursoModel->generarCodigoDesdeNombre($old_nombre);
            $codigo = $codigo_generado;
          } else {
            if (!$cursoModel->validarFormatoCodigo($old_codigo)) {
              throw new Exception('El código no cumple el formato AAA-123 (3-5 letras mayúsculas, guión y 3 dígitos).');
            }
            if ($cursoModel->codigoExiste($old_codigo)) {
              throw new Exception('El código ingresado ya existe. Prueba con otro o deja el campo vacío para generar uno automáticamente.');
            }
            $codigo = $old_codigo;
          }

          $cursoModel->nombre = $old_nombre;
          $cursoModel->codigo = $codigo;
          $cursoModel->profesor_id = (int)$old_profesor_id;

          if ($cursoModel->crear()) {
            $mensaje = 'Curso creado correctamente con código ' . htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8');
            // Limpiar valores del formulario tras éxito
            $old_nombre = '';
            $old_codigo = '';
            $old_profesor_id = '';
          } else {
            $error = 'No se pudo crear el curso. Intenta nuevamente.';
          }
        }
      } catch (Exception $e) {
        $error = $e->getMessage();
      }
    }
  }
}

// Obtener cursos del profesor y sus inscripciones
$cursos = $cursoModel->obtenerPorProfesor($profesor_id);
$inscripciones = $inscripcionModel->obtenerPorProfesor($profesor_id);
?>
<!DOCTYPE html>

<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Cursos e Inscripciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e3f2fd, #f1f8e9);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
    }

    .card-header {
      background: linear-gradient(90deg, #1976d2, #42a5f5);
    }

    .btn-primary {
      background: linear-gradient(90deg, #1976d2, #42a5f5);
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #1565c0, #1e88e5);
    }

    .btn-success {
      background: linear-gradient(90deg, #388e3c, #66bb6a);
      border: none;
    }

    .btn-success:hover {
      background: linear-gradient(90deg, #2e7d32, #43a047);
    }

    .btn-secondary {
      background-color: #9e9e9e;
      border: none;
    }

    .form-select,
    .form-control {
      border-radius: 10px;
    }

    h4 {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="card shadow">
      <div class="card-header text-white">
        <div class="d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Sistema de Gestión</h4>
          <div class="d-flex align-items-center">
            <span class="me-3">Profesor: <?php echo htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
          </div>
        </div>
      </div>
      <div class="card-body">

        <?php if ($mensaje): ?>
          <div class="alert alert-success" role="alert">
            <?php echo $mensaje; ?>
          </div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos" type="button" role="tab">Cursos</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="inscripciones-tab" data-bs-toggle="tab" data-bs-target="#inscripciones" type="button" role="tab">Inscripciones</button>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">

          <!-- Cursos -->
          <div class="tab-pane fade show active" id="cursos" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Listado de Cursos</h5>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCurso">Agregar Curso</button>
            </div>
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Código</th>
                  <th>Profesor</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($cursos)): ?>
                  <?php foreach ($cursos as $curso): ?>
                    <tr>
                      <td><?php echo (int)$curso['id']; ?></td>
                      <td><?php echo htmlspecialchars($curso['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($curso['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($curso['profesor_nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <a href="?editar=<?php echo (int)$curso['id']; ?>" class="btn btn-sm btn-success">Editar</a>
                        <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo (int)$curso['id']; ?>, '<?php echo htmlspecialchars($curso['nombre'], ENT_QUOTES, 'UTF-8'); ?>')">Eliminar</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center">No hay cursos registrados.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Inscripciones -->
          <div class="tab-pane fade" id="inscripciones" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Listado de Inscripciones</h5>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInscripcion">Agregar Inscripción</button>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="row mb-3">
              <div class="col-md-3">
                <div class="card bg-primary text-white">
                  <div class="card-body text-center">
                    <h6>Total Inscripciones</h6>
                    <h4><?php echo count($inscripciones); ?></h4>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-success text-white">
                  <div class="card-body text-center">
                    <h6>Mis Cursos</h6>
                    <h4><?php echo count($cursos); ?></h4>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-info text-white">
                  <div class="card-body text-center">
                    <h6>Alumnos Únicos</h6>
                    <h4><?php echo count(array_unique(array_column($inscripciones, 'alumno_id'))); ?></h4>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-warning text-white">
                  <div class="card-body text-center">
                    <h6>Total Alumnos</h6>
                    <h4><?php echo count($alumnos); ?></h4>
                  </div>
                </div>
              </div>
            </div>

            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Alumno</th>
                  <th>Curso</th>
                  <th>Código Curso</th>
                  <th>Fecha Inscripción</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($inscripciones)): ?>
                  <?php foreach ($inscripciones as $inscripcion): ?>
                    <tr>
                      <td><?php echo (int)$inscripcion['id']; ?></td>
                      <td><?php echo htmlspecialchars($inscripcion['alumno_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($inscripcion['curso_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($inscripcion['curso_codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo date('d/m/Y H:i', strtotime($inscripcion['fecha_inscripcion'])); ?></td>
                      <td>
                        <button class="btn btn-sm btn-danger" onclick="confirmarEliminarInscripcion(<?php echo (int)$inscripcion['id']; ?>, '<?php echo htmlspecialchars($inscripcion['alumno_nombre'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($inscripcion['curso_nombre'], ENT_QUOTES, 'UTF-8'); ?>')">Eliminar</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center">No hay inscripciones registradas.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal Agregar Curso -->
  <div class="modal fade" id="modalCurso" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Curso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" id="formCurso">
            <input type="hidden" name="accion" value="crear">
            <div class="mb-3">
              <label for="nombreCurso" class="form-label">Nombre del Curso</label>
              <input type="text" id="nombreCurso" name="nombre" class="form-control" value="<?php echo htmlspecialchars($old_nombre, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="mb-3">
              <label for="codigoCurso" class="form-label">Código</label>
              <input type="text" id="codigoCurso" name="codigo" class="form-control" value="<?php echo htmlspecialchars($old_codigo, ENT_QUOTES, 'UTF-8'); ?>" placeholder="ABC-001">
              <div class="form-text">Formato AAA-123. Déjalo vacío para generar uno automáticamente.</div>
            </div>
            <div class="mb-3">
              <label for="profesorCurso" class="form-label">Profesor</label>
              <select id="profesorCurso" name="profesor_id" class="form-select" required>
                <option value="">Selecciona un profesor</option>
                <?php foreach ($profesores as $prof): ?>
                  <option value="<?php echo (int)$prof['id']; ?>" <?php echo ($old_profesor_id !== '' && (int)$old_profesor_id === (int)$prof['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($prof['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" form="formCurso">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Curso -->
  <?php if ($modo_edicion && $curso_editar): ?>
    <div class="modal fade show" id="modalEditarCurso" tabindex="-1" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Curso</h5>
            <a href="?" class="btn-close"></a>
          </div>
          <div class="modal-body">
            <form method="post" id="formEditarCurso">
              <input type="hidden" name="accion" value="editar">
              <input type="hidden" name="curso_id" value="<?php echo (int)$curso_editar['id']; ?>">
              <div class="mb-3">
                <label for="nombreCursoEdit" class="form-label">Nombre del Curso</label>
                <input type="text" id="nombreCursoEdit" name="nombre" class="form-control" value="<?php echo htmlspecialchars($old_nombre, ENT_QUOTES, 'UTF-8'); ?>" required>
              </div>
              <div class="mb-3">
                <label for="codigoCursoEdit" class="form-label">Código</label>
                <input type="text" id="codigoCursoEdit" name="codigo" class="form-control" value="<?php echo htmlspecialchars($old_codigo, ENT_QUOTES, 'UTF-8'); ?>" required>
                <div class="form-text">Formato AAA-123. El código es obligatorio para editar.</div>
              </div>
              <div class="mb-3">
                <label for="profesorCursoEdit" class="form-label">Profesor</label>
                <select id="profesorCursoEdit" name="profesor_id" class="form-select" required>
                  <option value="">Selecciona un profesor</option>
                  <?php foreach ($profesores as $prof): ?>
                    <option value="<?php echo (int)$prof['id']; ?>" <?php echo ($old_profesor_id !== '' && (int)$old_profesor_id === (int)$prof['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($prof['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" form="formEditarCurso">Actualizar</button>
            <a href="?" class="btn btn-secondary">Cancelar</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade show"></div>
  <?php endif; ?>

  <!-- Modal Agregar Inscripción -->
  <div class="modal fade" id="modalInscripcion" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Inscripción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" id="formInscripcion">
            <input type="hidden" name="accion" value="inscribir">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="buscarAlumno" class="form-label">Buscar Alumno</label>
                  <input type="text" id="buscarAlumno" class="form-control" placeholder="Escribe para buscar...">
                </div>
                <div class="mb-3">
                  <label for="alumnoInscripcion" class="form-label">Alumno</label>
                  <select id="alumnoInscripcion" name="alumno_id" class="form-select" required>
                    <option value="">Selecciona un alumno</option>
                    <?php foreach ($alumnos as $alumno): ?>
                      <option value="<?php echo (int)$alumno['id']; ?>" data-nombre="<?php echo htmlspecialchars($alumno['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($alumno['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="cursoInscripcion" class="form-label">Curso</label>
                  <select id="cursoInscripcion" name="curso_id" class="form-select" required>
                    <option value="">Selecciona un curso</option>
                    <?php foreach ($cursos_profesor as $curso): ?>
                      <option value="<?php echo (int)$curso['id']; ?>">
                        <?php echo htmlspecialchars($curso['nombre'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($curso['codigo'], ENT_QUOTES, 'UTF-8'); ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="fechaInscripcion" class="form-label">Fecha de Inscripción</label>
                  <input type="date" id="fechaInscripcion" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
              </div>
            </div>

            <div class="alert alert-info">
              <strong>Información:</strong>
              <ul class="mb-0 mt-2">
                <li>Solo puedes inscribir alumnos en tus propios cursos.</li>
                <li>Un alumno no puede estar inscrito dos veces en el mismo curso.</li>
                <li>La fecha de inscripción se puede modificar si es necesario.</li>
              </ul>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" form="formInscripcion">Guardar Inscripción</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function confirmarEliminar(cursoId, nombreCurso) {
      if (confirm('¿Estás seguro de que quieres eliminar el curso "' + nombreCurso + '"?')) {
        window.location.href = '?eliminar=' + cursoId;
      }
    }

    function confirmarEliminarInscripcion(inscripcionId, alumnoNombre, cursoNombre) {
      if (confirm('¿Estás seguro de que quieres eliminar la inscripción de "' + alumnoNombre + '" en el curso "' + cursoNombre + '"?')) {
        window.location.href = '?eliminar_inscripcion=' + inscripcionId;
      }
    }

    // Funcionalidad de búsqueda de alumnos
    document.addEventListener('DOMContentLoaded', function() {
      const buscarAlumno = document.getElementById('buscarAlumno');
      const selectAlumno = document.getElementById('alumnoInscripcion');

      if (buscarAlumno && selectAlumno) {
        buscarAlumno.addEventListener('input', function() {
          const busqueda = this.value.toLowerCase();
          const opciones = selectAlumno.querySelectorAll('option');

          opciones.forEach(function(opcion) {
            if (opcion.value === '') {
              opcion.style.display = 'block'; // Siempre mostrar la opción por defecto
              return;
            }

            const nombre = opcion.getAttribute('data-nombre') || opcion.textContent;
            if (nombre.toLowerCase().includes(busqueda)) {
              opcion.style.display = 'block';
            } else {
              opcion.style.display = 'none';
            }
          });
        });
      }
    });
  </script>
</body>

</html>