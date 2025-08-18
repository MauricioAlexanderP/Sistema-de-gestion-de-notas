<?php
// CourseManager_front.php
// Front-end only template for a Course Management UI.
// No backend processing — all forms and buttons are static and only show UI feedback.
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestor de Cursos — Front-end</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Estilos personalizados */
    body { background: #f4f6fb; }
    .card { box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
    .muted { color: #6b7280; }
    .table thead th { border-bottom: 2px solid #e6e9ef; }
    .btn-ghost { background: transparent; border: 1px solid rgba(0,0,0,0.06); }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Gestor de Cursos</h1>
        <small class="muted">Interfaz front-end (solo diseño) — CRUD visual sin funcionalidad</small>
      </div>
      <div class="d-flex gap-2">
        <input id="search" class="form-control form-control-sm" placeholder="Buscar cursos..." style="min-width:240px;">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">Agregar Curso</button>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table mb-0 align-middle">
                <thead>
                  <tr>
                    <th style="width:50px">#</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Instructor</th>
                    <th style="width:100px">Cupos</th>
                    <th style="width:150px">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- FILAS ESTÁTICAS DE EJEMPLO -->
                  <tr>
                    <td>1</td>
                    <td>Introducción a JavaScript</td>
                    <td>Programación</td>
                    <td>Ana Pérez</td>
                    <td>30</td>
                    <td>
                      <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="1" data-bs-toggle="modal" data-bs-target="#modalEdit">Editar</button>
                      <button class="btn btn-sm btn-danger btn-delete" data-id="1">Eliminar</button>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Bases de Datos con MySQL</td>
                    <td>Bases de datos</td>
                    <td>Carlos Ruiz</td>
                    <td>25</td>
                    <td>
                      <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="2" data-bs-toggle="modal" data-bs-target="#modalEdit">Editar</button>
                      <button class="btn btn-sm btn-danger btn-delete" data-id="2">Eliminar</button>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Diseño UX/UI</td>
                    <td>Diseño</td>
                    <td>María López</td>
                    <td>20</td>
                    <td>
                      <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="3" data-bs-toggle="modal" data-bs-target="#modalEdit">Editar</button>
                      <button class="btn btn-sm btn-danger btn-delete" data-id="3">Eliminar</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <nav aria-label="paginacion ejemplo">
          <ul class="pagination pagination-sm">
            <li class="page-item disabled"><a class="page-link">«</a></li>
            <li class="page-item active"><a class="page-link">1</a></li>
            <li class="page-item"><a class="page-link">2</a></li>
            <li class="page-item"><a class="page-link">3</a></li>
            <li class="page-item"><a class="page-link">»</a></li>
          </ul>
        </nav>
      </div>

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="mb-3">Detalles del curso (vista)</h6>
            <p class="muted">Selecciona un curso para ver detalles. (UI estática — no hay interacción real)</p>
            <dl class="row mb-0">
              <dt class="col-5">Título</dt>
              <dd class="col-7">Introducción a JavaScript</dd>
              <dt class="col-5">Instructor</dt>
              <dd class="col-7">Ana Pérez</dd>
              <dt class="col-5">Cupos</dt>
              <dd class="col-7">30</dd>
              <dt class="col-5">Categoría</dt>
              <dd class="col-7">Programación</dd>
            </dl>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h6 class="mb-2">Atajos</h6>
            <div class="d-grid gap-2">
              <button class="btn btn-ghost btn-sm" disabled>Importar CSV (placeholder)</button>
              <button class="btn btn-ghost btn-sm" disabled>Exportar (placeholder)</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Agregar -->
  <div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Curso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formAdd">
            <div class="mb-3">
              <label class="form-label">Título</label>
              <input class="form-control" placeholder="Nombre del curso">
            </div>
            <div class="mb-3">
              <label class="form-label">Categoría</label>
              <input class="form-control" placeholder="Categoría">
            </div>
            <div class="mb-3">
              <label class="form-label">Instructor</label>
              <input class="form-control" placeholder="Nombre del instructor">
            </div>
            <div class="mb-3">
              <label class="form-label">Cupos</label>
              <input type="number" class="form-control" placeholder="0">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-sm" onclick="noBackendAlert()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar -->
  <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Curso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formEdit">
            <div class="mb-3">
              <label class="form-label">Título</label>
              <input class="form-control" value="Introducción a JavaScript">
            </div>
            <div class="mb-3">
              <label class="form-label">Categoría</label>
              <input class="form-control" value="Programación">
            </div>
            <div class="mb-3">
              <label class="form-label">Instructor</label>
              <input class="form-control" value="Ana Pérez">
            </div>
            <div class="mb-3">
              <label class="form-label">Cupos</label>
              <input type="number" class="form-control" value="30">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-sm" onclick="noBackendAlert()">Actualizar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // UI-only behaviour: mostrar alert cuando el usuario intenta guardar/editar/eliminar
    function noBackendAlert() {
      alert('Esta plantilla es solo front-end. Aquí no hay funcionalidad de servidor.');
    }

    // prevenir acciones reales en botones de eliminar
    document.querySelectorAll('.btn-delete').forEach(b => {
      b.addEventListener('click', e => {
        e.preventDefault();
        if (confirm('Eliminar (UI-only): ¿Desea continuar?')) {
          noBackendAlert();
        }
      });
    });

    // búsqueda visual (filtrado DOM simple para demostración UX)
    document.getElementById('search').addEventListener('input', function() {
      const q = this.value.toLowerCase();
      document.querySelectorAll('tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
      });
    });

    // al abrir modal de editar, podemos inyectar datos de ejemplo (UI-only)
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        // aquí podríamos cargar datos al formulario de edición (solo visual)
      });
    });
  </script>
</body>
</html>
