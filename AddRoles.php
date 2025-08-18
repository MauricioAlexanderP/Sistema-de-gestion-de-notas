<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Usuario</title>
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
        <h4 class="mb-0">Agregar Usuario</h4>
      </div>
      <div class="card-body">
        <form>
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" class="form-control" placeholder="Ingrese el nombre">
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" class="form-control" placeholder="Ingrese el correo electrónico">
          </div>
          <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" id="contrasena" class="form-control" placeholder="Ingrese la contraseña">
          </div>
          <div class="mb-3">
            <label for="rolUsuario" class="form-label">Seleccionar Rol</label>
            <select id="rolUsuario" class="form-select">
              <option value="Profesor">Profesor</option>
              <option value="Alumno">Alumno</option>
            </select>
          </div>
          <div class="mb-3">
           
          </div>
          <button type="button" class="btn btn-success" onclick="alert('Solo vista front — sin funcionalidad backend')">Guardar</button>
          <a href="#" class="btn btn-secondary">Cancelar</a>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
