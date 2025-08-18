<?php
require_once 'Database.php';

class Curso
{
  private $db;
  private $connection;

  // Propiedades de la tabla cursos
  public $id;
  public $nombre;
  public $codigo;
  public $profesor_id;
  public $creado_en;

  public function __construct()
  {
    $this->db = new Database();
    $this->connection = $this->db->getConnection();
  }

  // Crear un nuevo curso
  public function crear()
  {
    $query = "INSERT INTO cursos (nombre, codigo, profesor_id) VALUES (:nombre, :codigo, :profesor_id)";
    $stmt = $this->connection->prepare($query);

    $stmt->bindParam(':nombre', $this->nombre);
    $stmt->bindParam(':codigo', $this->codigo);
    $stmt->bindParam(':profesor_id', $this->profesor_id);

    if ($stmt->execute()) {
      $this->id = $this->connection->lastInsertId();
      return true;
    }
    return false;
  }

  // Obtener curso por ID
  public function obtenerPorId($id)
  {
    $query = "SELECT c.*, u.nombre as profesor_nombre 
                  FROM cursos c 
                  LEFT JOIN usuarios u ON c.profesor_id = u.id 
                  WHERE c.id = :id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
      $this->id = $row['id'];
      $this->nombre = $row['nombre'];
      $this->codigo = $row['codigo'];
      $this->profesor_id = $row['profesor_id'];
      $this->creado_en = $row['creado_en'];
      return $row;
    }
    return false;
  }

  // Obtener curso por código
  public function obtenerPorCodigo($codigo)
  {
    $query = "SELECT c.*, u.nombre as profesor_nombre 
                  FROM cursos c 
                  LEFT JOIN usuarios u ON c.profesor_id = u.id 
                  WHERE c.codigo = :codigo";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
      $this->id = $row['id'];
      $this->nombre = $row['nombre'];
      $this->codigo = $row['codigo'];
      $this->profesor_id = $row['profesor_id'];
      $this->creado_en = $row['creado_en'];
      return $row;
    }
    return false;
  }

  // Obtener todos los cursos
  public function obtenerTodos()
  {
    $query = "SELECT c.*, u.nombre as profesor_nombre 
                  FROM cursos c 
                  LEFT JOIN usuarios u ON c.profesor_id = u.id 
                  ORDER BY c.creado_en DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Obtener cursos por profesor
  public function obtenerPorProfesor($profesor_id)
  {
    $query = "SELECT c.*, u.nombre as profesor_nombre 
                  FROM cursos c 
                  LEFT JOIN usuarios u ON c.profesor_id = u.id 
                  WHERE c.profesor_id = :profesor_id 
                  ORDER BY c.nombre";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':profesor_id', $profesor_id);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Obtener cursos donde está inscrito un alumno
  public function obtenerPorAlumno($alumno_id)
  {
    $query = "SELECT c.*, u.nombre as profesor_nombre, i.fecha_inscripcion
                  FROM cursos c 
                  LEFT JOIN usuarios u ON c.profesor_id = u.id
                  INNER JOIN inscripciones i ON c.id = i.curso_id
                  WHERE i.alumno_id = :alumno_id 
                  ORDER BY i.fecha_inscripcion DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Actualizar curso
  public function actualizar()
  {
    $query = "UPDATE cursos SET nombre = :nombre, codigo = :codigo, profesor_id = :profesor_id WHERE id = :id";
    $stmt = $this->connection->prepare($query);

    $stmt->bindParam(':nombre', $this->nombre);
    $stmt->bindParam(':codigo', $this->codigo);
    $stmt->bindParam(':profesor_id', $this->profesor_id);
    $stmt->bindParam(':id', $this->id);

    return $stmt->execute();
  }

  // Eliminar curso
  public function eliminar()
  {
    // Primero eliminar inscripciones relacionadas
    $query_inscripciones = "DELETE FROM inscripciones WHERE curso_id = :id";
    $stmt_inscripciones = $this->connection->prepare($query_inscripciones);
    $stmt_inscripciones->bindParam(':id', $this->id);
    $stmt_inscripciones->execute();

    // Luego eliminar el curso
    $query = "DELETE FROM cursos WHERE id = :id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $this->id);
    return $stmt->execute();
  }

  // Verificar si el código ya existe
  public function codigoExiste($codigo, $excluir_id = null)
  {
    $query = "SELECT id FROM cursos WHERE codigo = :codigo";
    if ($excluir_id) {
      $query .= " AND id != :excluir_id";
    }

    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':codigo', $codigo);
    if ($excluir_id) {
      $stmt->bindParam(':excluir_id', $excluir_id);
    }

    $stmt->execute();
    return $stmt->rowCount() > 0;
  }

  // Contar alumnos inscritos en el curso
  public function contarAlumnosInscritos()
  {
    $query = "SELECT COUNT(*) as total FROM inscripciones WHERE curso_id = :id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['total'];
  }

  // Obtener alumnos inscritos en el curso
  public function obtenerAlumnosInscritos()
  {
    $query = "SELECT u.*, i.fecha_inscripcion 
                  FROM usuarios u 
                  INNER JOIN inscripciones i ON u.id = i.alumno_id 
                  WHERE i.curso_id = :id AND u.rol = 'alumno'
                  ORDER BY u.nombre";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
