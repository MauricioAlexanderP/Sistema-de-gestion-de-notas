<?php
session_start();
require_once 'model/Database.php';
require_once 'model/Curso.php';
require_once 'model/Inscripcion.php';
require_once 'model/Usuario.php';

// Verificar que el usuario esté logueado y sea alumno
/*if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'alumno') {
    header("Location: login.php");
    exit();
}*/

$database = new Database();
$db = $database->getConnection();

$curso = new Curso($db);
$inscripcion = new Inscripcion($db);
$usuario = new Usuario($db);

// Obtener información del alumno
$alumno_info = $usuario->obtenerPorId($_SESSION['usuario_id']);

$mensaje = '';
$mensaje_tipo = '';

// Procesar formulario de inscripción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_curso'])) {
    $codigo_curso = trim($_POST['codigo_curso']);
    
    // Validar que el código no esté vacío
    if (empty($codigo_curso)) {
        $mensaje = "Por favor ingrese un código de curso válido.";
        $mensaje_tipo = "error";
    } else {
        // Verificar si el curso existe
        $curso_info = $curso->obtenerPorCodigo($codigo_curso);
        
        if (!$curso_info) {
            $mensaje = "El código de curso ingresado no existe. Por favor verifique el código e intente nuevamente.";
            $mensaje_tipo = "error";
        } else {
            // Verificar si el alumno ya está inscrito
            if ($inscripcion->yaInscrito($_SESSION['usuario_id'], $curso_info['id'])) {
                $mensaje = "Ya estás inscrito en este curso. No puedes inscribirte más de una vez.";
                $mensaje_tipo = "error";
            } else {
                // Inscribir al alumno
                $inscripcion->alumno_id = $_SESSION['usuario_id'];
                $inscripcion->curso_id = $curso_info['id'];
                
                if ($inscripcion->crear()) {
                    $mensaje = "¡Inscripción exitosa! Te has inscrito correctamente en el curso: " . $curso_info['nombre'];
                    $mensaje_tipo = "success";
                } else {
                    $mensaje = "Error al procesar la inscripción. Por favor intente nuevamente.";
                    $mensaje_tipo = "error";
                }
            }
        }
    }
}

// Obtener cursos disponibles (no inscritos)
$cursos_disponibles = [];
$cursos_totales = $curso->obtenerTodos();
$cursos_inscritos = $curso->obtenerPorAlumno($_SESSION['usuario_id']);

// Crear array de IDs de cursos inscritos
$cursos_inscritos_ids = array_column($cursos_inscritos, 'id');

// Filtrar cursos disponibles
foreach ($cursos_totales as $curso_item) {
    if (!in_array($curso_item['id'], $cursos_inscritos_ids)) {
        $curso_item['alumnos_inscritos'] = $curso->contarAlumnosInscritos($curso_item['id']);
        $cursos_disponibles[] = $curso_item;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Alumno - Inscripción</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .user-info {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .courses-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .enrollment-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-enroll {
            background-color: #27ae60;
            color: white;
        }

        .btn-enroll:hover {
            background-color: #219a52;
        }

        .btn-drop {
            background-color: #e74c3c;
            color: white;
        }

        .btn-drop:hover {
            background-color: #c0392b;
        }

        .btn-disabled {
            background-color: #95a5a6;
            color: #666;
            cursor: not-allowed;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .course-card {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s;
        }

        .course-card.available {
            border-color: #27ae60;
            background-color: #f8fff8;
        }

        .course-card.enrolled {
            border-color: #3498db;
            background-color: #f0f8ff;
        }

        .course-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .course-info {
            color: #666;
            margin-bottom: 15px;
        }

        .course-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .status-available {
            background-color: #27ae60;
            color: white;
        }

        .status-enrolled {
            background-color: #3498db;
            color: white;
        }

        .status-full {
            background-color: #e74c3c;
            color: white;
        }

        .my-courses {
            margin-top: 30px;
        }

        .enrolled-course {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .course-details h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .course-details p {
            color: #666;
            font-size: 14px;
        }

        .no-courses {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard del Alumno</h1>
    </div>

    <div class="container">
        <div class="user-info">
            <h2>Bienvenido</h2>
           
        </div>

        <!-- Formulario de inscripción -->
        <div class="courses-section">
            <h2 class="section-title">Inscribirse a un Curso</h2>
            
            <?php if ($mensaje): ?>
                <div class="message <?php echo $mensaje_tipo; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="enrollment-form">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="codigo_curso">Código del Curso:</label>
                        <input type="text" id="codigo_curso" name="codigo_curso" 
                               placeholder="Ingrese el código proporcionado por el profesor" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Inscribirse</button>
                </form>
            </div>
        </div>

        <!-- Cursos disponibles -->
        <div class="courses-section">
            <h2 class="section-title">Cursos Disponibles</h2>
            
            <?php if (empty($cursos_disponibles)): ?>
                <div class="no-courses">
                    <p>No hay cursos disponibles para inscripción en este momento.</p>
                </div>
            <?php else: ?>
                <div class="course-grid">
                    <?php foreach ($cursos_disponibles as $curso_item): ?>
                        <div class="course-card available">
                            <div class="course-name"><?php echo htmlspecialchars($curso_item['nombre']); ?></div>
                            <div class="course-info">
                                <p><strong>Código:</strong> <?php echo htmlspecialchars($curso_item['codigo']); ?></p>
                                <p><strong>Profesor:</strong> <?php echo htmlspecialchars($curso_item['profesor_nombre']); ?></p>
                                <p><strong>Creado:</strong> <?php echo date('d/m/Y', strtotime($curso_item['creado_en'])); ?></p>
                                <p><strong>Alumnos inscritos:</strong> <?php echo $curso_item['alumnos_inscritos']; ?></p>
                            </div>
                            <span class="course-status status-available">DISPONIBLE</span>
                            <br>
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="codigo_curso" value="<?php echo htmlspecialchars($curso_item['codigo']); ?>">
                                <button type="submit" class="btn btn-enroll">Inscribirse</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Mis cursos inscritos -->
        <div class="my-courses">
            <div class="courses-section">
                <h2 class="section-title">Mis Cursos Actuales</h2>
                
                <?php
                $cursos_inscritos = $curso->obtenerPorAlumno($_SESSION['usuario_id']);
                if (empty($cursos_inscritos)): ?>
                    <div class="no-courses">
                        <p>No estás inscrito en ningún curso actualmente.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($cursos_inscritos as $curso_inscrito): ?>
                        <div class="enrolled-course">
                            <div class="course-details">
                                <h4><?php echo htmlspecialchars($curso_inscrito['nombre']); ?></h4>
                                <p>
                                    <strong>Código:</strong> <?php echo htmlspecialchars($curso_inscrito['codigo']); ?> | 
                                    <strong>Profesor:</strong> <?php echo htmlspecialchars($curso_inscrito['profesor_nombre']); ?> | 
                                    <strong>Inscrito:</strong> <?php echo date('d/m/Y', strtotime($curso_inscrito['fecha_inscripcion'])); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
