<?php
class Database
{
  private $host = 'localhost';
  private $port = '3306';
  private $database = 'sistema_cursos';
  private $username = 'root';
  private $password = '';
  private $connection;

  public function __construct()
  {
    $this->connect();
  }

  private function connect()
  {
    try {
      $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset=utf8mb4";
      $this->connection = new PDO($dsn, $this->username, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
    }
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function disconnect()
  {
    $this->connection = null;
  }
}
