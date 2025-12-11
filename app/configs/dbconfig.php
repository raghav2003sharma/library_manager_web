<?php
require_once 'env_loader.php';
loadEnv(__DIR__ . '/../../.env');
class Database {
 private   $host ;
private $username;
 private $password;
private $dbname;

    public $conn;

    public function __construct() {
          $this->host     = $_ENV['host'];
        $this->username = $_ENV['username'];
        $this->password = $_ENV['password'];
        $this->dbname   = $_ENV['dbname'];
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("DB Connection Failed: " . $this->conn->connect_error);
        }
    }
}
