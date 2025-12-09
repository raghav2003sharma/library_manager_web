<?php
class Database {
 private   $host = "localhost";
private $username = "root";
 private $password = "Raghav123!";
private $dbname = "Library_manager_db";

    public $conn;

    public function __construct() {
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
