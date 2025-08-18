<?php
require_once 'model/Usuario.php';

$id = $_GET['id'] ?? null;
$usuario = new Usuario();
if (!$id || !$usuario->obtenerPorId($id)) {
  echo 'Usuario no encontrado.';
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario->nombre = $_POST['nombre'] ?? $usuario->nombre;
  $usuario->correo = $_POST['correo'] ?? $usuario->correo;
  $usuario->rol = $_POST['rol'] ?? $usuario->rol;
  if (!empty($_POST['contraseña'])) {
    $usuario->actualizarContraseña($_POST['contraseña']);
  }
  if ($usuario->actualizar()) {
    $mensaje = 'Usuario actualizado correctamente.';
  } else {
    $mensaje = 'Error al actualizar usuario.';
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e3f2fd, #f1f8e9);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
    }

    .card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background: linear-gradient(90deg, #ff9800, #ffb74d);
      color: white;
    }

    .btn-warning {
      background: linear-gradient(90deg, #ff9800, #ffb74d);
      border: none;
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: 500;
    }

    .btn-warning:hover {
      background: linear-gradient(90deg, #f57c00, #ff9800);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
    }

    .btn-secondary {
      background-color: #6c757d;
      border: none;
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: 500;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    .form-select,
    .form-control {
      border-radius: 10px;
      border: 2px solid #e0e0e0;
      padding: 12px 15px;
      transition: all 0.3s ease;
    }

    .form-select:focus,
    .form-control:focus {
      border-color: #ff9800;
      box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.25);
    }

    .form-label {
      font-weight: 600;
      color: #37474f;
      margin-bottom: 8px;
    }

    h4 {
      font-weight: bold;
    }

    .alert {
      border-radius: 15px;
      border: none;
    }

    .container {
      max-width: 600px;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="card shadow">
      <div class="card-header text-white">
        <h4 class="mb-0">
          <i class="fas fa-user-edit me-2"></i>
          Editar Usuario
        </h4>
      </div>
      <div class="card-body p-4">
        <?php if (isset($mensaje)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $mensaje ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label for="nombre" class="form-label">
              <i class="fas fa-user me-1"></i>
              Nombre Completo
            </label>
            <input type="text" id="nombre" name="nombre" class="form-control"
              value="<?= htmlspecialchars($usuario->nombre) ?>"
              placeholder="Ingrese el nombre completo" required>
          </div>

          <div class="mb-3">
            <label for="correo" class="form-label">
              <i class="fas fa-envelope me-1"></i>
              Correo Electrónico
            </label>
            <input type="email" id="correo" name="correo" class="form-control"
              value="<?= htmlspecialchars($usuario->correo) ?>"
              placeholder="ejemplo@correo.com" required>
          </div>

          <div class="mb-3">
            <label for="contrasena" class="form-label">
              <i class="fas fa-lock me-1"></i>
              Nueva Contraseña
            </label>
            <input type="password" id="contrasena" name="contraseña" class="form-control"
              placeholder="Dejar vacío para mantener la actual">
            <div class="form-text">
              <i class="fas fa-info-circle me-1"></i>
              Solo ingrese una contraseña si desea cambiarla
            </div>
          </div>

          <div class="mb-4">
            <label for="rol" class="form-label">
              <i class="fas fa-user-tag me-1"></i>
              Rol del Usuario
            </label>
            <select id="rol" name="rol" class="form-select" required>
              <option value="profesor" <?= $usuario->rol === 'profesor' ? 'selected' : '' ?>>
                <i class="fas fa-chalkboard-teacher"></i> Profesor
              </option>
              <option value="alumno" <?= $usuario->rol === 'alumno' ? 'selected' : '' ?>>
                <i class="fas fa-user-graduate"></i> Alumno
              </option>
            </select>
          </div>

          <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="AddRoles.php" class="btn btn-secondary me-md-2">
              <i class="fas fa-arrow-left me-1"></i>
              Volver
            </a>
            <button type="submit" class="btn btn-warning">
              <i class="fas fa-save me-1"></i>
              Actualizar Usuario
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>