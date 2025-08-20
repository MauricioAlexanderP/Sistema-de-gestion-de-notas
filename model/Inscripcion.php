<?php
require_once __DIR__ . '/Database.php';

class Inscripcion
{
  private $db;
  private $connection;

  // Propiedades de la tabla inscripciones
  public $id;
  public $alumno_id;
  public $curso_id;
  public $fecha_inscripcion;

  public function __construct()
  {
    $this->db = new Database();
    $this->connection = $this->db->getConnection();
  }

  // Crear una nueva inscripción
  public function crear()
  {
    // Verificar que no existe ya una inscripción para este alumno y curso
    if ($this->yaInscrito($this->alumno_id, $this->curso_id)) {
      return false;
    }

    $query = "INSERT INTO inscripciones (alumno_id, curso_id) VALUES (:alumno_id, :curso_id)";
    $stmt = $this->connection->prepare($query);

    $stmt->bindParam(':alumno_id', $this->alumno_id);
    $stmt->bindParam(':curso_id', $this->curso_id);

    if ($stmt->execute()) {
      $this->id = $this->connection->lastInsertId();
      return true;
    }
    return false;
  }

  // Obtener inscripción por ID
  public function obtenerPorId($id)
  {
    $query = "SELECT i.*, 
                         u.nombre as alumno_nombre, u.correo as alumno_correo,
                         c.nombre as curso_nombre, c.codigo as curso_codigo,
                         p.nombre as profesor_nombre
                  FROM inscripciones i 
                  LEFT JOIN usuarios u ON i.alumno_id = u.id 
                  LEFT JOIN cursos c ON i.curso_id = c.id
                  LEFT JOIN usuarios p ON c.profesor_id = p.id
                  WHERE i.id = :id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
      $this->id = $row['id'];
      $this->alumno_id = $row['alumno_id'];
      $this->curso_id = $row['curso_id'];
      $this->fecha_inscripcion = $row['fecha_inscripcion'];
      return $row;
    }
    return false;
  }

  // Obtener todas las inscripciones
  public function obtenerTodas()
  {
    $query = "SELECT i.*, 
                         u.nombre as alumno_nombre, u.correo as alumno_correo,
                         c.nombre as curso_nombre, c.codigo as curso_codigo,
                         p.nombre as profesor_nombre
                  FROM inscripciones i 
                  LEFT JOIN usuarios u ON i.alumno_id = u.id 
                  LEFT JOIN cursos c ON i.curso_id = c.id
                  LEFT JOIN usuarios p ON c.profesor_id = p.id
                  ORDER BY i.fecha_inscripcion DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Obtener inscripciones por alumno
  public function obtenerPorAlumno($alumno_id)
  {
    $query = "SELECT i.*, 
                         c.nombre as curso_nombre, c.codigo as curso_codigo,
                         p.nombre as profesor_nombre
                  FROM inscripciones i 
                  LEFT JOIN cursos c ON i.curso_id = c.id
                  LEFT JOIN usuarios p ON c.profesor_id = p.id
                  WHERE i.alumno_id = :alumno_id 
                  ORDER BY i.fecha_inscripcion DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Obtener inscripciones por curso
  public function obtenerPorCurso($curso_id)
  {
    $query = "SELECT i.*, 
                         u.nombre as alumno_nombre, u.correo as alumno_correo
                  FROM inscripciones i 
                  LEFT JOIN usuarios u ON i.alumno_id = u.id 
                  WHERE i.curso_id = :curso_id 
                  ORDER BY i.fecha_inscripcion DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':curso_id', $curso_id);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Obtener inscripciones por profesor (cursos que imparte)
  public function obtenerPorProfesor($profesor_id)
  {
    $query = "SELECT i.*, 
                         u.nombre as alumno_nombre, u.correo as alumno_correo,
                         c.nombre as curso_nombre, c.codigo as curso_codigo
                  FROM inscripciones i 
                  LEFT JOIN usuarios u ON i.alumno_id = u.id 
                  LEFT JOIN cursos c ON i.curso_id = c.id
                  WHERE c.profesor_id = :profesor_id 
                  ORDER BY c.nombre, i.fecha_inscripcion DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':profesor_id', $profesor_id);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Eliminar inscripción
  public function eliminar()
  {
    $query = "DELETE FROM inscripciones WHERE id = :id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $this->id);
    return $stmt->execute();
  }

  // Eliminar inscripción por alumno y curso
  public function eliminarPorAlumnoCurso($alumno_id, $curso_id)
  {
    $query = "DELETE FROM inscripciones WHERE alumno_id = :alumno_id AND curso_id = :curso_id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->bindParam(':curso_id', $curso_id);
    return $stmt->execute();
  }

  // Verificar si un alumno ya está inscrito en un curso
  public function yaInscrito($alumno_id, $curso_id)
  {
    $query = "SELECT id FROM inscripciones WHERE alumno_id = :alumno_id AND curso_id = :curso_id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->bindParam(':curso_id', $curso_id);
    $stmt->execute();
    return $stmt->rowCount() > 0;
  }

  // Contar inscripciones totales
  public function contarTotales()
  {
    $query = "SELECT COUNT(*) as total FROM inscripciones";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'];
  }

  // Contar inscripciones por curso
  public function contarPorCurso($curso_id)
  {
    $query = "SELECT COUNT(*) as total FROM inscripciones WHERE curso_id = :curso_id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':curso_id', $curso_id);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'];
  }

  // Contar inscripciones por alumno
  public function contarPorAlumno($alumno_id)
  {
    $query = "SELECT COUNT(*) as total FROM inscripciones WHERE alumno_id = :alumno_id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'];
  }

  // Obtener estadísticas de inscripciones
  public function obtenerEstadisticas()
  {
    $query = "SELECT 
                    COUNT(*) as total_inscripciones,
                    COUNT(DISTINCT alumno_id) as total_alumnos_inscritos,
                    COUNT(DISTINCT curso_id) as total_cursos_con_inscripciones
                  FROM inscripciones";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt->fetch();
  }
}
