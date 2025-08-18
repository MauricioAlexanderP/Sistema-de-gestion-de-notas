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
        }

        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
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

        .course-card.dropped {
            border-color: #e74c3c;
            background-color: #fff0f0;
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

        .status-dropped {
            background-color: #e74c3c;
            color: white;
        }

        .status-unavailable {
            background-color: #95a5a6;
            color: white;
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

        .warning-message {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 13px;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard del Alumno</h1>
    </div>

    <div class="container">
        <div class="user-info">
            <h2>Bienvenido, Juan Pérez</h2>
            <p><strong>Carnet:</strong> 2024-0123</p>
            <p><strong>Carrera:</strong> Ingeniería en Sistemas</p>
        </div>
        <div class="courses-section">
            <h2 class="section-title">Cursos Disponibles</h2>
            
            <div class="course-grid">
                <!-- Curso disponible -->
                <div class="course-card available">
                    <div class="course-name">Programación I</div>
                    <div class="course-info">
                        <p><strong>Código:</strong> PROG-101</p>
                        <p><strong>Horario:</strong> Lun-Mié-Vie 8:00-10:00</p>
                        <p><strong>Profesor:</strong> Dr. García</p>
                        <p><strong>Cupos:</strong> 5/30</p>
                    </div>
                    <span class="course-status status-available">DISPONIBLE</span>
                    <br>
                    <button class="btn btn-enroll">Inscribirse</button>
                </div>

                <!-- Curso inscrito -->
                <div class="course-card enrolled">
                    <div class="course-name">Matemáticas II</div>
                    <div class="course-info">
                        <p><strong>Código:</strong> MAT-102</p>
                        <p><strong>Horario:</strong> Mar-Jue 10:00-12:00</p>
                        <p><strong>Profesor:</strong> Dra. López</p>
                        <p><strong>Cupos:</strong> 25/30</p>
                    </div>
                    <span class="course-status status-enrolled">INSCRITO</span>
                    <br>
                    <button class="btn btn-drop">Darse de Baja</button>
                </div>

                <!-- Curso abandonado (no puede reinscribirse) -->
                <div class="course-card dropped">
                    <div class="course-name">Física I</div>
                    <div class="course-info">
                        <p><strong>Código:</strong> FIS-101</p>
                        <p><strong>Horario:</strong> Lun-Vie 14:00-16:00</p>
                        <p><strong>Profesor:</strong> Dr. Martínez</p>
                        <p><strong>Cupos:</strong> 10/30</p>
                    </div>
                    <span class="course-status status-dropped">ABANDONADO</span>
                    <br>
                    <button class="btn btn-disabled" disabled>No Disponible</button>
                    <div class="warning-message">
                        Ya te diste de baja de este curso. No puedes volver a inscribirte.
                    </div>
                </div>

                <!-- Curso lleno -->
                <div class="course-card">
                    <div class="course-name">Química General</div>
                    <div class="course-info">
                        <p><strong>Código:</strong> QUI-101</p>
                        <p><strong>Horario:</strong> Mar-Jue 16:00-18:00</p>
                        <p><strong>Profesor:</strong> Dra. Rodríguez</p>
                        <p><strong>Cupos:</strong> 30/30</p>
                    </div>
                    <span class="course-status status-unavailable">LLENO</span>
                    <br>
                    <button class="btn btn-disabled" disabled>Sin Cupos</button>
                </div>

                <!-- Otro curso disponible -->
                <div class="course-card available">
                    <div class="course-name">Historia Universal</div>
                    <div class="course-info">
                        <p><strong>Código:</strong> HIS-101</p>
                        <p><strong>Horario:</strong> Mié-Vie 16:00-18:00</p>
                        <p><strong>Profesor:</strong> Lic. Hernández</p>
                        <p><strong>Cupos:</strong> 15/25</p>
                    </div>
                    <span class="course-status status-available">DISPONIBLE</span>
                    <br>
                    <button class="btn btn-enroll">Inscribirse</button>
                </div>

                <!-- Otro curso abandonado -->
                <div class="course-card dropped">
                    <div class="course-name">Inglés I</div>
                    <div class="course-info">
                        <p><strong>Código:</strong> ING-101</p>
                        <p><strong>Horario:</strong> Lun-Mié 18:00-20:00</p>
                        <p><strong>Profesor:</strong> Prof. Johnson</p>
                        <p><strong>Cupos:</strong> 8/20</p>
                    </div>
                    <span class="course-status status-dropped">ABANDONADO</span>
                    <br>
                    <button class="btn btn-disabled" disabled>No Disponible</button>
                    <div class="warning-message">
                        Ya te diste de baja de este curso. No puedes volver a inscribirte.
                    </div>
                </div>
            </div>
        </div>

        <div class="my-courses">
            <div class="courses-section">
                <h2 class="section-title">Mis Cursos Actuales</h2>
                
                <div class="enrolled-course">
                    <div class="course-details">
                        <h4>Matemáticas II</h4>
                        <p>MAT-102 - Dr. López - Mar-Jue 10:00-12:00</p>
                    </div>
                    <button class="btn btn-drop">Darse de Baja</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>