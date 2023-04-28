<?php
class DatabaseConnection {
  private $host = "localhost";
  private $username = "root";
  private $password = "";
  private $dbName = "react-crud";
  private $conn;

  public function __construct() {
    try {
      $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbName;
      $this->conn = new PDO($dsn, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      throw new Exception("Connection failed: " . $e->getMessage());
    }
  }

  public function getConnection() {
    return $this->conn;
  }
}
?>





