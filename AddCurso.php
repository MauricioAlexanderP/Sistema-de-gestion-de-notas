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
    .form-select, .form-control {
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
        <h4 class="mb-0">Sistema de Gestión</h4>
      </div>
      <div class="card-body">

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
                <tr>
                  <td>1</td>
                  <td>Matemáticas Básicas</td>
                  <td>MAT101</td>
                  <td>Juan Pérez</td>
                  <td>
                    <button class="btn btn-sm btn-success">Editar</button>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Historia Universal</td>
                  <td>HIS202</td>
                  <td>María López</td>
                  <td>
                    <button class="btn btn-sm btn-success">Editar</button>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Inscripciones -->
          <div class="tab-pane fade" id="inscripciones" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Listado de Inscripciones</h5>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInscripcion">Agregar Inscripción</button>
            </div>
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Alumno</th>
                  <th>Curso</th>
                  <th>Fecha</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Carlos Hernández</td>
                  <td>Matemáticas Básicas</td>
                  <td>2025-08-10</td>
                  <td>
                    <button class="btn btn-sm btn-success">Editar</button>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                  </td>
                </tr>
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
          <form>
            <div class="mb-3">
              <label for="nombreCurso" class="form-label">Nombre del Curso</label>
              <input type="text" id="nombreCurso" class="form-control">
            </div>
            <div class="mb-3">
              <label for="codigoCurso" class="form-label">Código</label>
              <input type="text" id="codigoCurso" class="form-control">
            </div>
            <div class="mb-3">
              <label for="profesorCurso" class="form-label">Profesor</label>
              <select id="profesorCurso" class="form-select">
                <option value="1">Juan Pérez</option>
                <option value="2">María López</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Agregar Inscripción -->
  <div class="modal fade" id="modalInscripcion" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Inscripción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="alumnoInscripcion" class="form-label">Alumno</label>
              <select id="alumnoInscripcion" class="form-select">
                <option value="1">Carlos Hernández</option>
                <option value="2">Laura Martínez</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="cursoInscripcion" class="form-label">Curso</label>
              <select id="cursoInscripcion" class="form-select">
                <option value="1">Matemáticas Básicas</option>
                <option value="2">Historia Universal</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="fechaInscripcion" class="form-label">Fecha</label>
              <input type="date" id="fechaInscripcion" class="form-control">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
