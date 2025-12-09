<?php 
require_once __DIR__ . "/../configs/dbconfig.php";


class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

   public function getUserByEmail($email) {//used in login and register
        $stmt = $this->conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function register($username,$email,$hashedPassword){
         $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        return $stmt->execute();

    }
    public function addUser($username, $email, $hashedPassword, $role){
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
        return $stmt->execute();
    }
    public function updateUser($name, $email, $role, $user_id){
            $sql = "UPDATE users SET name = ?,email=?,role=? WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $role, $user_id);
            return $stmt->execute();
    }
    public function deleteUser($user_id){
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

 

   
}
?>