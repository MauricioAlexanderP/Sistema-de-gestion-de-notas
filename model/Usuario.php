<?php
require_once __DIR__ . '/Database.php';

class Usuario
{
  private $db;
  private $connection;

  // Propiedades de la tabla usuarios
  public $id;
  public $nombre;
  public $correo;
  public $contrasena;
  public $rol;
  public $creado_en;

  public function __construct()
  {
    $this->db = new Database();
    $this->connection = $this->db->getConnection();
  }

  // Crear un nuevo usuario
  public function crear()
  {
    $query = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (:nombre, :correo, :contrasena, :rol)";
    $stmt = $this->connection->prepare($query);

    // Guardar la contraseña en texto plano (no recomendado para producción)
    $stmt->bindParam(':nombre', $this->nombre);
    $stmt->bindParam(':correo', $this->correo);
    $stmt->bindParam(':contrasena', $this->contrasena);
    $stmt->bindParam(':rol', $this->rol);

    if ($stmt->execute()) {
      $this->id = $this->connection->lastInsertId();
      return true;
    }
    return false;
  }

  // Obtener usuario por ID
  public function obtenerPorId($id)
  {
    $query = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
      $this->id = $row['id'];
      $this->nombre = $row['nombre'];
      $this->correo = $row['correo'];
      $this->contrasena = $row['contrasena'];
      $this->rol = $row['rol'];
      $this->creado_en = $row['creado_en'];
      return true;
    }
    return false;
  }

  // Obtener usuario por correo
  public function obtenerPorCorreo($correo)
  {
    $query = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
      $this->id = $row['id'];
      $this->nombre = $row['nombre'];
      $this->correo = $row['correo'];
      $this->contrasena = $row['contrasena'];
      $this->rol = $row['rol'];
      $this->creado_en = $row['creado_en'];
      return true;
    }
    return false;
  }

  // Obtener todos los usuarios
  public function obtenerTodos()
  {
    $query = "SELECT * FROM usuarios ORDER BY creado_en DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Obtener usuarios por rol
  public function obtenerPorRol($rol)
  {
    $query = "SELECT * FROM usuarios WHERE rol = :rol ORDER BY nombre";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':rol', $rol);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Actualizar usuario
  public function actualizar()
  {
    $query = "UPDATE usuarios SET nombre = :nombre, correo = :correo, rol = :rol WHERE id = :id";
    $stmt = $this->connection->prepare($query);

    $stmt->bindParam(':nombre', $this->nombre);
    $stmt->bindParam(':correo', $this->correo);
    $stmt->bindParam(':rol', $this->rol);
    $stmt->bindParam(':id', $this->id);

    return $stmt->execute();
  }

  // Actualizar contraseña
  public function actualizarContraseña($nueva_contraseña)
  {
    $query = "UPDATE usuarios SET contrasena = :contrasena WHERE id = :id";
    $stmt = $this->connection->prepare($query);

    // Guardar la nueva contraseña en texto plano (no recomendado para producción)
    $stmt->bindParam(':contrasena', $nueva_contraseña);
    $stmt->bindParam(':id', $this->id);

    return $stmt->execute();
  }

  // Eliminar usuario
  public function eliminar()
  {
    $query = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id', $this->id);
    return $stmt->execute();
  }

  // Verificar contraseña
  public function verificarContraseña($contraseña)
  {
    // Permitir texto plano o hash
    if ($contraseña === $this->contrasena) {
      return true;
    }
    return password_verify($contraseña, $this->contrasena);
  }

  // Verificar si el correo ya existe
  public function correoExiste($correo, $excluir_id = null)
  {
    $query = "SELECT id FROM usuarios WHERE correo = :correo";
    if ($excluir_id) {
      $query .= " AND id != :excluir_id";
    }

    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':correo', $correo);
    if ($excluir_id) {
      $stmt->bindParam(':excluir_id', $excluir_id);
    }

    $stmt->execute();
    return $stmt->rowCount() > 0;
  }
}
