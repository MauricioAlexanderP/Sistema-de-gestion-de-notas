<?php
require_once 'model/Usuario.php';
$mensaje = '';

// Verificar si hay mensaje de eliminación en la URL
if (isset($_GET['mensaje'])) {
  $mensaje = $_GET['mensaje'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'] ?? '';
  $correo = $_POST['correo'] ?? '';
  $contraseña = $_POST['contrasena'] ?? '';
  $rol = strtolower($_POST['rolUsuario'] ?? '');

  $usuario = new Usuario();
  $usuario->nombre = $nombre;
  $usuario->correo = $correo;
  $usuario->contrasena = $contraseña;
  $usuario->rol = $rol;

  if ($usuario->crear()) {
    $mensaje = 'Usuario agregado correctamente.';
  } else {
    $mensaje = 'Error al agregar usuario.';
  }
}

// Obtener todos los usuarios para mostrar en la tabla
$usuario = new Usuario();
$usuarios = $usuario->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        <h4 class="mb-0">Agregar Usuario</h4>
      </div>
      <div class="card-body">
        <?php if ($mensaje) echo "<div class='alert alert-info'>$mensaje</div>"; ?>
        <form method="POST">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese el nombre" required>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" name="correo" class="form-control" placeholder="Ingrese el correo electrónico" required>
          </div>
          <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Ingrese la contraseña" required>
          </div>
          <div class="mb-3">
            <label for="rolUsuario" class="form-label">Seleccionar Rol</label>
            <select id="rolUsuario" name="rolUsuario" class="form-select" required>
              <option value="profesor">Profesor</option>
              <option value="alumno">Alumno</option>
            </select>
          </div>
          <div class="mb-3">
          </div>
          <button type="submit" class="btn btn-success">Guardar</button>
          <a href="#" class="btn btn-secondary">Cancelar</a>
        </form>
      </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="card shadow mt-4">
      <div class="card-header text-white">
        <h4 class="mb-0">Lista de Usuarios</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha Creación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($usuarios as $u): ?>
                <tr>
                  <td><?= htmlspecialchars($u['id']) ?></td>
                  <td><?= htmlspecialchars($u['nombre']) ?></td>
                  <td><?= htmlspecialchars($u['correo']) ?></td>
                  <td>
                    <span class="badge <?= $u['rol'] === 'profesor' ? 'bg-primary' : 'bg-success' ?>">
                      <?= ucfirst($u['rol']) ?>
                    </span>
                  </td>
                  <td><?= date('d/m/Y H:i', strtotime($u['creado_en'])) ?></td>
                  <td>
                    <a href="EditarUsuario.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="EliminarUsuario.php?id=<?= $u['id'] ?>"
                      class="btn btn-danger btn-sm"
                      onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                      <i class="fas fa-trash"></i> Eliminar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($usuarios)): ?>
                <tr>
                  <td colspan="6" class="text-center text-muted">No hay usuarios registrados</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>